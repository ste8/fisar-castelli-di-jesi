<?php
/** Local integration: multiple course relations, safe saves and reciprocal public links. */
if ( ! defined( 'WP_CLI' ) || ! WP_CLI || 'local' !== wp_get_environment_type() ) {
	throw new RuntimeException( 'This test requires local WordPress and WP-CLI.' );
}
$created = array();
$original_user = get_current_user_id();
$original_request = $_POST;
$keep = false;
$check = static function ( bool $condition, string $message ): void {
	if ( ! $condition ) { throw new RuntimeException( $message ); }
};
$create = static function ( string $type, string $status, string $title, array $extra = array() ) use ( &$created ): int {
	$id = wp_insert_post( array_merge( array( 'post_type' => $type, 'post_status' => $status, 'post_title' => 'Test locale — ' . $title, 'post_content' => '<!-- wp:paragraph --><p>Testo originale del racconto.</p><!-- /wp:paragraph -->' ), $extra ), true );
	if ( is_wp_error( $id ) ) { throw new RuntimeException( $id->get_error_message() ); }
	$created[] = $id;
	update_post_meta( $id, '_fisar_test_fixture', 'course-reports' );
	return $id;
};
$render = static function ( string $part, array $arguments ): string {
	ob_start(); get_template_part( 'template-parts/' . $part, null, $arguments ); return ob_get_clean();
};
try {
	$admin = get_users( array( 'role' => 'administrator', 'number' => 1 ) );
	wp_set_current_user( $admin[0]->ID );
	$course = $create( 'fisar_corso', 'publish', 'Corso con racconti' );
	$other = $create( 'fisar_corso', 'publish', 'Secondo corso' );
	$draft = $create( 'fisar_corso', 'draft', 'Corso non pubblico' );
	$protected = $create( 'fisar_corso', 'publish', 'Corso protetto', array( 'post_password' => 'local-only' ) );
	$event = $create( 'fisar_evento', 'publish', 'Evento collegato' );
	$report = $create( 'post', 'draft', 'Primo racconto', array( 'post_date' => '2026-10-22 19:24:00' ) );
	$course_before = get_post_meta( $course );
	$content_before = get_post_field( 'post_content', $report, 'raw' );
	$nonce = wp_create_nonce( 'fisar_cdj_save_report' );
	$request = array( 'fisar_cdj_report_nonce' => $nonce, '_fisar_report_fields_present' => '1', '_fisar_report_courses_present' => '1', Fisar_CDJ_Event_Reports::COURSE_META => array( (string) $course, (string) $other, (string) $course, (string) $draft, (string) $protected, (string) $event, '-1', array( $course ) ), Fisar_CDJ_Event_Reports::EVENT_META => (string) $event, '_fisar_report_facebook_url' => 'https://www.facebook.com/photo/?fbid=123&set=pcb.456' );
	$_POST = $request;
	Fisar_CDJ_Event_Reports::save( $report );
	$check( Fisar_CDJ_Event_Reports::courses_for_report( $report ) === array( $course, $other ), 'Keep distinct, public courses only, with one metadata row per ID.' );
	$check( get_post_meta( $course ) === $course_before && get_post_field( 'post_content', $report, 'raw' ) === $content_before, 'Linking must not change course metadata or article text.' );
	$check( Fisar_CDJ_Event_Reports::event_for_report( $report ) === $event, 'Course links coexist with the original event relation.' );
	$check( array() === Fisar_CDJ_Event_Reports::for_course( $course ), 'Draft reports remain absent.' );
	$stored = get_post_meta( $report );
	foreach ( array( array(), array( 'fisar_cdj_report_nonce' => $nonce, '_fisar_report_fields_present' => '1' ), array_merge( $request, array( 'fisar_cdj_report_nonce' => 'bad' ) ), array_merge( $request, array( Fisar_CDJ_Event_Reports::COURSE_META => 'malformed' ) ) ) as $request_without_valid_courses ) {
		$_POST = $request_without_valid_courses;
		Fisar_CDJ_Event_Reports::save( $report );
		$check( get_post_meta( $report ) === $stored, 'Missing controls, invalid nonce or malformed list preserve all links.' );
	}
	$_POST = $request;
	wp_set_current_user( 0 ); Fisar_CDJ_Event_Reports::save( $report );
	$check( get_post_meta( $report ) === $stored, 'Unauthorized saves preserve links.' );
	wp_set_current_user( $admin[0]->ID ); $_POST = array();
	wp_update_post( array( 'ID' => $report, 'post_status' => 'publish', 'edit_date' => true, 'post_date' => '2026-10-22 19:24:00', 'post_date_gmt' => get_gmt_from_date( '2026-10-22 19:24:00' ) ) );
	$second = $create( 'post', 'publish', 'Secondo racconto', array( 'post_date' => '2026-11-12 17:57:00' ) );
	add_post_meta( $second, Fisar_CDJ_Event_Reports::COURSE_META, $course );
	foreach ( array( 'draft', 'private', 'future', 'trash' ) as $status ) {
		$id = $create( 'post', $status, 'Racconto nascosto', 'future' === $status ? array( 'post_date' => '2099-01-01 12:00:00' ) : array() );
		add_post_meta( $id, Fisar_CDJ_Event_Reports::COURSE_META, $course );
	}
	$id = $create( 'post', 'publish', 'Racconto protetto', array( 'post_password' => 'local-only' ) );
	add_post_meta( $id, Fisar_CDJ_Event_Reports::COURSE_META, $course );
	$reports = Fisar_CDJ_Event_Reports::for_course( $course );
	$check( array_column( $reports, 'ID' ) === array( $report, $second ), 'Public reports are in chronological order; expected ' . wp_json_encode( array( $report, $second ) ) . ', got ' . wp_json_encode( array_map( static fn( $p ) => array( $p->ID, $p->post_date, $p->post_status ), $reports ) ) );
	$html = $render( 'course-reports', array( 'reports' => $reports ) );
	$check( substr_count( $html, '<li>' ) === 2 && str_contains( $html, '2026-10-22' ) && str_contains( $html, esc_url( get_permalink( $second ) ) ), 'The course lists all reports with dates and real links.' );
	$check( '' === $render( 'course-reports', array( 'reports' => array() ) ), 'No empty section without reports.' );
	$preview = $render( 'event-report-preview', array( 'reports' => $reports, 'label' => 'Il corso raccontato', 'cta' => 'leggi i racconti', 'list_url' => '#course-reports' ) );
	$check( str_contains( $preview, '#course-reports' ) && str_contains( $preview, 'Il corso raccontato' ) && ! str_contains( $preview, 'target=' ), 'Multiple reports lead to the course list in the same tab.' );
	$links = $render( 'report-links', array( 'post_id' => $report ) );
	$check( str_contains( $links, 'I corsi raccontati' ) && str_contains( $links, get_permalink( $course ) ) && str_contains( $links, get_permalink( $other ) ) && str_contains( $links, get_permalink( $event ) ) && str_contains( $links, 'Guarda il racconto su Facebook' ), 'An article can link back to two courses, its event and the social source.' );
	wp_update_post( array( 'ID' => $other, 'post_status' => 'draft' ) );
	$check( Fisar_CDJ_Event_Reports::courses_for_report( $report ) === array( $course ) && array() === Fisar_CDJ_Event_Reports::for_course( $other ), 'Unpublishing a course removes its public links without deleting the stored relation.' );
	wp_update_post( array( 'ID' => $other, 'post_status' => 'publish' ) );
	$_POST = array( 'fisar_cdj_report_nonce' => $nonce, '_fisar_report_fields_present' => '1', '_fisar_report_courses_present' => '1', Fisar_CDJ_Event_Reports::COURSE_META => array( (string) $other ) );
	Fisar_CDJ_Event_Reports::save( $report );
	$check( array_column( Fisar_CDJ_Event_Reports::for_course( $course ), 'ID' ) === array( $second ) && array_column( Fisar_CDJ_Event_Reports::for_course( $other ), 'ID' ) === array( $report ), 'Changing the article updates both courses automatically.' );
	unset( $_POST[ Fisar_CDJ_Event_Reports::COURSE_META ] ); Fisar_CDJ_Event_Reports::save( $report );
	$check( array() === Fisar_CDJ_Event_Reports::courses_for_report( $report ) && Fisar_CDJ_Event_Reports::event_for_report( $report ) === $event, 'Unchecking all courses clears only the course links.' );
	$check( array() === Fisar_CDJ_Event_Reports::for_course( $event ) && array() === Fisar_CDJ_Event_Reports::for_course( $protected ), 'Wrong types and protected courses cannot expose reports.' );
	WP_CLI::success( 'Course reports: multiple relations, guarded saves, privacy, chronology, reassignment, clearing and reciprocal templates passed.' );
	if ( 'fixtures' === ( $args[0] ?? '' ) ) {
		$_POST = array(); add_post_meta( $report, Fisar_CDJ_Event_Reports::COURSE_META, $course );
		$keep = true;
		WP_CLI::line( wp_json_encode( array( 'ids' => $created, 'course' => get_permalink( $course ), 'report' => get_permalink( $report ) ) ) );
	}
} finally {
	$_POST = array();
	if ( ! $keep ) { foreach ( array_reverse( $created ) as $id ) { wp_delete_post( $id, true ); } }
	wp_set_current_user( $original_user ); $_POST = $original_request;
}
