<?php
/** Local WordPress integration: editorial saves, public relations and template output. */
if ( ! defined( 'WP_CLI' ) || ! WP_CLI || 'local' !== wp_get_environment_type() ) {
	throw new RuntimeException( 'This integration test requires local WordPress and WP-CLI.' );
}
$created = array();
$original_user = get_current_user_id();
$original_request = $_POST;
$keep = false;
$check = static function ( bool $condition, string $message ): void {
	if ( ! $condition ) { throw new RuntimeException( $message ); }
};
$create = static function ( string $type, string $status, string $title, array $extra = array() ) use ( &$created ): int {
	$id = wp_insert_post( array_merge( array( 'post_type' => $type, 'post_status' => $status, 'post_title' => 'Test locale — ' . $title, 'post_content' => '<!-- wp:paragraph --><p>Racconto originale di prova.</p><!-- /wp:paragraph -->' ), $extra ), true );
	if ( is_wp_error( $id ) ) { throw new RuntimeException( $id->get_error_message() ); }
	$created[] = $id;
	update_post_meta( $id, '_fisar_test_fixture', 'event-reports' );
	return $id;
};
$render = static function ( string $part, array $arguments ): string {
	ob_start();
	get_template_part( 'template-parts/' . $part, null, $arguments );
	return ob_get_clean();
};
try {
	$admin = get_users( array( 'role' => 'administrator', 'number' => 1 ) );
	wp_set_current_user( $admin[0]->ID );
	$event = $create( 'fisar_evento', 'publish', 'Evento con racconti' );
	$other_event = $create( 'fisar_evento', 'publish', 'Altro evento' );
	$draft_event = $create( 'fisar_evento', 'draft', 'Evento non pubblicato' );
	foreach ( array( $event, $other_event ) as $id ) { update_post_meta( $id, '_fisar_event_date', '2026-10-08' ); }
	$report = $create( 'post', 'draft', 'Primo racconto' );
	$ordinary = $create( 'post', 'publish', 'Articolo senza evento' );
	$event_before = get_post_meta( $event );
	$content_before = get_post_field( 'post_content', $report, 'raw' );
	$nonce = wp_create_nonce( 'fisar_cdj_save_report' );
	$_POST = array( 'fisar_cdj_report_nonce' => $nonce, '_fisar_report_fields_present' => '1', '_fisar_report_event_id' => (string) $event, '_fisar_report_facebook_url' => 'https://www.facebook.com/photo/?fbid=123&set=pcb.456', '_fisar_report_instagram_url' => 'https://www.instagram.com/p/Example/' );
	Fisar_CDJ_Event_Reports::save( $report );
	$check( Fisar_CDJ_Event_Reports::event_for_report( $report ) === $event, 'Save the relation on the article.' );
	$check( get_post_meta( $event ) === $event_before, 'Saving a report must not edit its event.' );
	$check( get_post_field( 'post_content', $report, 'raw' ) === $content_before, 'Metadata saves must not rewrite the report text.' );
	$check( get_post_meta( $report, '_fisar_report_facebook_url', true ) === 'https://www.facebook.com/photo/?fbid=123&set=pcb.456', 'Preserve query parameters in source URLs.' );
	$check( array_column( Fisar_CDJ_Event_Reports::social_links( $report ), 'label' ) === array( 'Guarda il racconto su Facebook', 'Guarda il racconto su Instagram' ), 'Use the agreed labels.' );
	$check( array() === Fisar_CDJ_Event_Reports::for_event( $event ), 'A draft must never appear on its public event.' );
	$check( Fisar_CDJ_Event_Reports::sanitize_event_id( $ordinary ) === 0 && Fisar_CDJ_Event_Reports::sanitize_event_id( $draft_event ) === 0 && Fisar_CDJ_Event_Reports::sanitize_event_id( -$event ) === 0 && Fisar_CDJ_Event_Reports::sanitize_event_id( array( $event ) ) === 0, 'Reject wrong types, nonpublic events and malformed IDs.' );
	$check( Fisar_CDJ_Event_Reports::sanitize_url( 'javascript:alert(1)' ) === '' && Fisar_CDJ_Event_Reports::sanitize_url( array( 'bad' ) ) === '', 'Reject unsafe and malformed URLs.' );
	$stored = get_post_meta( $report );
	foreach ( array( array(), array( 'fisar_cdj_report_nonce' => $nonce, '_fisar_report_event_id' => '0' ), array( 'fisar_cdj_report_nonce' => 'wrong', '_fisar_report_fields_present' => '1', '_fisar_report_event_id' => '0' ) ) as $request ) {
		$_POST = $request;
		Fisar_CDJ_Event_Reports::save( $report );
		$check( get_post_meta( $report ) === $stored, 'Missing fields/marker/nonce and an invalid nonce must preserve stored metadata.' );
	}
	$_POST = array( 'fisar_cdj_report_nonce' => $nonce, '_fisar_report_fields_present' => '1', '_fisar_report_event_id' => '0' );
	wp_set_current_user( 0 );
	Fisar_CDJ_Event_Reports::save( $report );
	$check( get_post_meta( $report ) === $stored, 'Unauthorized saves must preserve stored metadata.' );
	wp_set_current_user( $admin[0]->ID );
	$_POST = array();
	wp_update_post( array( 'ID' => $report, 'post_status' => 'publish' ) );
	$check( array_column( Fisar_CDJ_Event_Reports::for_event( $event ), 'ID' ) === array( $report ), 'Publishing automatically exposes the existing relation.' );
	$second = $create( 'post', 'publish', 'Secondo racconto' );
	update_post_meta( $second, Fisar_CDJ_Event_Reports::EVENT_META, $event );
	foreach ( array( 'draft', 'private', 'future', 'trash' ) as $status ) {
		$id = $create( 'post', $status, 'Racconto ' . $status, 'future' === $status ? array( 'post_date' => '2099-01-01 12:00:00' ) : array() );
		update_post_meta( $id, Fisar_CDJ_Event_Reports::EVENT_META, $event );
	}
	$protected = $create( 'post', 'publish', 'Racconto protetto', array( 'post_password' => 'local-fixture-only' ) );
	update_post_meta( $protected, Fisar_CDJ_Event_Reports::EVENT_META, $event );
	$reports = Fisar_CDJ_Event_Reports::for_event( $event );
	$check( count( $reports ) === 2, 'Multiple public reports are listed; private/draft/future/trash/password-protected posts are excluded.' );
	$html = $render( 'event-reports', array( 'reports' => $reports ) );
	$check( substr_count( $html, '<li>' ) === 2 && str_contains( $html, esc_url( get_permalink( $report ) ) ), 'Render each published report with its own link.' );
	$check( '' === $render( 'event-reports', array( 'reports' => array() ) ), 'Events without reports have no empty section.' );
	$check( '' === $render( 'event-report-preview', array( 'reports' => array() ) ), 'Events without reports have no empty preview.' );
	$preview = $render( 'event-report-preview', array( 'reports' => array( get_post( $report ) ) ) );
	$check( str_contains( $preview, 'event-report-preview--without-image' ) && str_contains( $preview, esc_url( get_permalink( $report ) ) ) && ! str_contains( $preview, 'target=' ), 'A report without a cover remains readable and opens in the same tab.' );
	$preview = $render( 'event-report-preview', array( 'reports' => $reports ) );
	$check( str_contains( $preview, 'href="#event-reports"' ) && str_contains( $preview, 'leggendo i racconti' ), 'Multiple reports link to the complete list rather than selecting a single article.' );
	$links = $render( 'report-links', array( 'post_id' => $report ) );
	$check( str_contains( $links, esc_url( get_permalink( $event ) ) ) && str_contains( $links, 'Guarda il racconto su Instagram' ), 'The article links back to its event and its social reports.' );
	$check( '' === $render( 'report-links', array( 'post_id' => $ordinary ) ), 'Ordinary articles remain unchanged.' );
	$_POST = array( 'fisar_cdj_report_nonce' => $nonce, '_fisar_report_fields_present' => '1', '_fisar_report_event_id' => (string) $other_event );
	Fisar_CDJ_Event_Reports::save( $report );
	$check( count( Fisar_CDJ_Event_Reports::for_event( $event ) ) === 1 && count( Fisar_CDJ_Event_Reports::for_event( $other_event ) ) === 1, 'Changing the article relation automatically updates both events.' );
	$check( count( Fisar_CDJ_Event_Reports::social_links( $report ) ) === 2, 'A partial metadata save must preserve omitted social fields.' );
	$_POST = array( 'fisar_cdj_report_nonce' => $nonce, '_fisar_report_fields_present' => '1', '_fisar_report_event_id' => '0', '_fisar_report_facebook_url' => '', '_fisar_report_instagram_url' => '' );
	Fisar_CDJ_Event_Reports::save( $report );
	$check( array() === Fisar_CDJ_Event_Reports::for_event( $other_event ) && array() === Fisar_CDJ_Event_Reports::social_links( $report ), 'Explicit clearing removes links without changing content.' );
	$check( array() === Fisar_CDJ_Event_Reports::for_event( 0 ) && array() === Fisar_CDJ_Event_Reports::for_event( $draft_event ) && array() === Fisar_CDJ_Event_Reports::for_event( $ordinary ), 'Invalid and nonpublic events have no report list.' );
	ob_start(); Fisar_CDJ_Event_Reports::render( get_post( $report ) ); $editor = ob_get_clean();
	$check( str_contains( $editor, 'Evento collegato' ) && str_contains( $editor, 'Racconto su Instagram (facoltativo)' ) && str_contains( $editor, 'fisar_cdj_report_nonce' ), 'The native editor provides the relation, URLs and nonce.' );
	WP_CLI::success( 'Event reports: guarded saves, unchanged events/text, public-only automatic links, multiple reports, reassignment, clearing and social labels passed.' );
	if ( 'fixtures' === ( $args[0] ?? '' ) ) {
		$_POST = array();
		update_post_meta( $report, Fisar_CDJ_Event_Reports::EVENT_META, $event );
		update_post_meta( $report, '_fisar_report_facebook_url', 'https://www.facebook.com/FISAR.Castelli.di.Jesi/' );
		update_post_meta( $report, '_fisar_report_instagram_url', 'https://www.instagram.com/fisarcastellidijesi/' );
		$keep = true;
		WP_CLI::line( wp_json_encode( array( 'ids' => $created, 'event' => get_permalink( $event ), 'report' => get_permalink( $report ) ) ) );
	}
} finally {
	$_POST = array();
	if ( ! $keep ) { foreach ( array_reverse( $created ) as $id ) { wp_delete_post( $id, true ); } }
	wp_set_current_user( $original_user );
	$_POST = $original_request;
}
