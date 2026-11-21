<?php
/** Local integration: explicit refund conditions, guarded saves and shared public/WhatsApp output. */
if ( ! defined( 'WP_CLI' ) || ! WP_CLI || 'local' !== wp_get_environment_type() ) { throw new RuntimeException( 'Local WordPress only.' ); }
$created = array(); $keep = false;
$original_user = get_current_user_id(); $original_request = $_POST; $original_screen = $GLOBALS['current_screen'] ?? null;
$check = static function ( bool $condition, string $message ): void { if ( ! $condition ) { throw new RuntimeException( $message ); } };
$render = static function ( string $part, array $args ): string { ob_start(); get_template_part( 'template-parts/' . $part, null, $args ); return ob_get_clean(); };
try {
	$_POST = array(); $admin = get_users( array( 'role' => 'administrator', 'number' => 1 ) )[0]->ID; wp_set_current_user( $admin );
	$id = wp_insert_post( array( 'post_type' => 'fisar_evento', 'post_status' => 'draft', 'post_title' => 'Prova locale — Cancellazioni e rimborsi', 'post_content' => '<p>Testo originale dell’evento.</p>', 'meta_input' => array( '_fisar_test_fixture' => 'event-cancellation', '_fisar_event_date' => '2026-12-04', '_fisar_event_start_time' => '20:15', '_fisar_event_venue' => 'Sede di prova', '_fisar_event_mode' => 'presence', '_fisar_event_registration_required' => true, '_fisar_event_deadline' => '2026-11-20', '_fisar_event_deadline_type' => 'strict' ) ), true );
	$check( ! is_wp_error( $id ), 'Create own fixture.' ); $created[] = $id;
	$before = get_post( $id, ARRAY_A ); $original_meta = get_post_meta( $id );
	$check( array() === Fisar_CDJ_Event_Cancellation::presentation( $id )['lines'], 'Legacy events have no inferred refund conditions.' );
	$check( '' === $render( 'event-cancellation', array( 'policy' => Fisar_CDJ_Event_Cancellation::presentation( $id ) ) ), 'No empty public section.' );
	$check( ! str_contains( Fisar_CDJ_Event_WhatsApp_Message::generate( get_post( $id ) )['message'], '*↩️ CANCELLAZIONI E RIMBORSI*' ), 'No inferred WhatsApp policy.' );
	$policy = array( 'mode' => 'rules', 'rules' => array( array( 'date' => '2026-11-29', 'time' => '18:30', 'percent' => '30' ), array( 'date' => '2026-11-27', 'time' => '', 'percent' => '100' ), array( 'date' => '', 'time' => '', 'percent' => '' ) ), 'after' => 'verify', 'notes' => '<p>Contatta la Delegazione per la disdetta dell\'evento.</p>' );
	$nonce = wp_create_nonce( Fisar_CDJ_Event_Cancellation::NONCE_ACTION );
	$request = array( '_fisar_event_cancellation_present' => '1', Fisar_CDJ_Event_Cancellation::NONCE_NAME => $nonce, Fisar_CDJ_Event_Cancellation::META => wp_slash( $policy ) );
	$_POST = $request; Fisar_CDJ_Event_Cancellation::save( $id );
	$stored = get_post_meta( $id, Fisar_CDJ_Event_Cancellation::META, true );
	$check( array( '2026-11-27', '2026-11-29' ) === array_column( $stored['rules'], 'date' ) && '' === $stored['rules'][0]['time'], 'Rules sorted, blank rows ignored and no hour invented.' );
	$check( $policy['notes'] === $stored['notes'], 'Keep apostrophes in rich text.' );
	$presentation = Fisar_CDJ_Event_Cancellation::presentation( $id );
	$check( str_contains( $presentation['lines'][0], 'rimborso completo' ) && ! str_contains( $presentation['lines'][0], '23:59' ) && str_contains( $presentation['lines'][1], '18:30' ) && str_contains( $presentation['lines'][1], '30%' ), 'Dates, optional times, ranges and percentages match the policy.' );
	$check( str_contains( $presentation['lines'][2], 'verificata con la Delegazione' ), 'After-deadline refund is uncertain, not an additional cancellation fee.' );
	$html = $render( 'event-cancellation', array( 'policy' => $presentation ) );
	$check( str_contains( $html, 'Cancellazioni e rimborsi' ) && substr_count( $html, '<li>' ) === 3 && str_contains( $html, 'dell\'evento' ), 'Structured public policy and note render once.' );
	foreach ( array( true, false ) as $past ) {
		$registration = array( 'past' => $past, 'required' => true, 'has_fees' => false, 'channels' => array(), 'notes' => '', 'details' => fisar_cdj_get_event_registration_details( $id ), 'cancellation' => $presentation );
		$check( str_contains( $render( 'event-registration', array( 'registration' => $registration ) ), 'Cancellazioni e rimborsi' ), 'Policy remains available for past events and closed bookings.' );
	}
	$message = Fisar_CDJ_Event_WhatsApp_Message::generate( get_post( $id ) )['message'];
	$check( str_contains( $message, '*↩️ CANCELLAZIONI E RIMBORSI*' ) && str_contains( $message, 'rimborso completo' ) && str_contains( $message, '30%' ) && str_contains( $message, 'verificata con la Delegazione' ) && ! str_contains( $message, '<p>' ), 'WhatsApp exports the same conditions as the site.' );
	foreach ( array( array(), array_replace( $request, array( Fisar_CDJ_Event_Cancellation::NONCE_NAME => 'bad' ) ), array_diff_key( $request, array( Fisar_CDJ_Event_Cancellation::META => true ) ) ) as $partial ) {
		$_POST = $partial; Fisar_CDJ_Event_Cancellation::save( $id );
		$check( $stored === get_post_meta( $id, Fisar_CDJ_Event_Cancellation::META, true ), 'Older editors, invalid nonces and partial saves preserve the policy.' );
	}
	$_POST = $request; wp_set_current_user( 0 ); Fisar_CDJ_Event_Cancellation::save( $id );
	$check( $stored === get_post_meta( $id, Fisar_CDJ_Event_Cancellation::META, true ), 'Unauthorized save is ignored.' ); wp_set_current_user( $admin );
	foreach ( array( '101', '-1', '12.345', array( '30' ) ) as $bad_percent ) {
		$invalid = $policy; $invalid['rules'][0]['percent'] = $bad_percent;
		$check( is_wp_error( Fisar_CDJ_Event_Cancellation::validate( $invalid ) ), 'Reject invalid refund percentages.' );
	}
	$invalid_cases = array( 'bad', array( 'mode' => 'unknown' ), array( 'mode' => 'text', 'text' => '' ), array( 'mode' => 'text', 'text' => '<p>&nbsp;</p>' ), array_replace( $policy, array( 'after' => '' ) ), array_replace( $policy, array( 'rules' => array() ) ), array_replace( $policy, array( 'after' => 'percent', 'after_percent' => '200' ) ), array_replace( $policy, array( 'rules' => array_fill( 0, 21, $policy['rules'][0] ) ) ) );
	foreach ( array( array( 'date' => '2026-02-30', 'percent' => '100' ), array( 'date' => '', 'percent' => '100' ), array( 'date' => '2026-11-27', 'time' => '25:00', 'percent' => '100' ), array( 'date' => '2026-11-27', 'time' => '23:59', 'percent' => '30' ) ) as $bad_row ) { $invalid_cases[] = array_replace( $policy, array( 'rules' => array( $policy['rules'][1], $bad_row ) ) ); }
	foreach ( $invalid_cases as $invalid ) {
		$_POST = array_replace( $request, array( Fisar_CDJ_Event_Cancellation::META => wp_slash( $invalid ) ) ); Fisar_CDJ_Event_Cancellation::save( $id );
		$check( $stored === get_post_meta( $id, Fisar_CDJ_Event_Cancellation::META, true ) && get_transient( Fisar_CDJ_Event_Cancellation::error_key( $id ) ), 'Malformed rules preserve existing policy and explain the error.' );
	}
	foreach ( array( '0', '12,5', '100' ) as $percent ) {
		$case = array_replace( $policy, array( 'after' => 'percent', 'after_percent' => $percent ) );
		$check( ! is_wp_error( Fisar_CDJ_Event_Cancellation::validate( $case ) ), 'Zero, decimal and complete refunds are valid.' );
	}
	foreach ( array( '0' => 'nessun rimborso', '100' => 'rimborso completo', '12,5' => 'rimborso del 12,5%' ) as $percent => $expected ) {
		$case = array_replace( $policy, array( 'rules' => array( array( 'date' => '2026-11-27', 'time' => '', 'percent' => (string) $percent ) ), 'after' => 'percent', 'after_percent' => (string) $percent ) );
		update_post_meta( $id, Fisar_CDJ_Event_Cancellation::META, wp_slash( $case ) );
		$view = Fisar_CDJ_Event_Cancellation::presentation( $id );
		$check( str_ends_with( $view['lines'][0], ': ' . $expected . '.' ) && 'Dopo questa data: ' . $expected . '.' === $view['lines'][1], 'One date and both refund positions use natural, consistent labels.' );
		$check( str_contains( Fisar_CDJ_Event_WhatsApp_Message::generate( get_post( $id ) )['message'], $view['lines'][1] ), 'WhatsApp uses exactly the same refund wording.' );
	}
	$case = array_replace( $policy, array( 'rules' => array( $policy['rules'][1] ) ) );
	update_post_meta( $id, Fisar_CDJ_Event_Cancellation::META, wp_slash( $case ) );
	$check( str_starts_with( Fisar_CDJ_Event_Cancellation::presentation( $id )['lines'][1], 'Dopo questa data,' ), 'A single date uses this date, also for refunds to verify.' );
	$case['rules'][0]['time'] = '18:30';
	update_post_meta( $id, Fisar_CDJ_Event_Cancellation::META, wp_slash( $case ) );
	$check( str_starts_with( Fisar_CDJ_Event_Cancellation::presentation( $id )['lines'][1], 'Dopo questo termine,' ), 'A precise time uses this deadline.' );
	update_post_meta( $id, Fisar_CDJ_Event_Cancellation::META, wp_slash( $policy ) );
	$check( str_starts_with( Fisar_CDJ_Event_Cancellation::presentation( $id )['lines'][2], 'Dopo l’ultima scadenza,' ), 'Several dates keep last-deadline wording.' );
	$case = array_replace( $policy, array( 'after' => 'none' ) );
	$_POST = array_replace( $request, array( Fisar_CDJ_Event_Cancellation::META => $case ) ); Fisar_CDJ_Event_Cancellation::save( $id );
	$lines = Fisar_CDJ_Event_Cancellation::presentation( $id )['lines'];
	$check( str_contains( end( $lines ), 'nessun rimborso' ), 'Explicit non-refundable remainder.' );
	$text = '<p>Rimborso <strong>completo</strong> entro venerdì.</p><ul><li>Contatta la Delegazione.</li></ul><script>alert(1)</script>';
	$_POST = array_replace( $request, array( Fisar_CDJ_Event_Cancellation::META => array( 'mode' => 'text', 'text' => $text ) ) ); Fisar_CDJ_Event_Cancellation::save( $id );
	$presentation = Fisar_CDJ_Event_Cancellation::presentation( $id );
	$check( ! $presentation['lines'] && str_contains( $presentation['text'], '<strong>completo</strong>' ) && ! str_contains( $presentation['text'], '<script>' ), 'Text-only mode retains safe formatting and does not mix in inactive rules.' );
	$check( str_contains( Fisar_CDJ_Event_WhatsApp_Message::generate( get_post( $id ) )['message'], '*completo*' ), 'Text-only policy keeps WhatsApp formatting.' );
	$_POST = array_replace( $request, array( Fisar_CDJ_Event_Cancellation::META => array( 'mode' => 'none' ) ) ); Fisar_CDJ_Event_Cancellation::save( $id );
	$check( '' === $render( 'event-cancellation', array( 'policy' => Fisar_CDJ_Event_Cancellation::presentation( $id ) ) ), 'Non specificata removes public policy.' );
	$check( $before === get_post( $id, ARRAY_A ), 'Policy saves never rewrite title, body, dates or status.' );
	foreach ( $original_meta as $key => $values ) { $check( $values === get_post_meta( $id, $key, false ), 'Other event metadata preserved: ' . $key ); }
	set_current_screen( 'fisar_evento' ); get_current_screen()->is_block_editor( true );
	Fisar_CDJ_Meta_Boxes::add_meta_boxes();
	$check( isset( $GLOBALS['wp_meta_boxes']['fisar_evento']['normal']['default']['fisar-event-cancellation'] ), 'Refund conditions belong to the main metadata panel.' );
	$_POST = $request; Fisar_CDJ_Event_Cancellation::save( $id );
	ob_start(); Fisar_CDJ_Event_Cancellation_Editor::render( get_post( $id ) ); $editor = ob_get_clean();
	$check( str_contains( $editor, 'Solo testo' ) && str_contains( $editor, 'Aggiungi scadenza' ) && str_contains( $editor, 'Rimborso da verificare con la Delegazione' ), 'Editor exposes all agreed options.' );
	$dom = new DOMDocument(); @$dom->loadHTML( '<div id="fisar-event-cancellation">' . $editor . '</div>' );
	$ids = array(); foreach ( $dom->getElementsByTagName( '*' ) as $element ) { if ( $element->hasAttribute( 'id' ) ) { $ids[] = $element->getAttribute( 'id' ); } }
	$check( count( $ids ) === count( array_unique( $ids ) ), 'Editor controls and their parent metabox have unique IDs.' );
	WP_CLI::success( 'Refund policy: guarded saves, validation, optional times, percentages, public/WhatsApp parity, closed/past events, text-only and unchanged event data passed.' );
	$keep = 'fixtures' === ( $args[0] ?? '' );
	if ( $keep ) { WP_CLI::line( wp_json_encode( array( 'id' => $id, 'url' => get_permalink( $id ), 'editor' => get_edit_post_link( $id, 'raw' ) ) ) ); }
} finally {
	$_POST = array(); delete_transient( Fisar_CDJ_Event_Cancellation::error_key( $id ?? 0 ) ); $GLOBALS['current_screen'] = $original_screen;
	if ( ! $keep ) { foreach ( $created as $fixture_id ) { wp_delete_post( $fixture_id, true ); } }
	wp_set_current_user( $original_user ); $_POST = $original_request;
}
