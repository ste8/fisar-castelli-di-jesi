<?php
/** Local-only calendar regression; fixtures are explicitly retained for browser QA. */
if ( ! defined( 'WP_CLI' ) || ! WP_CLI || 'local' !== wp_get_environment_type() ) { throw new RuntimeException( 'Run only in local WordPress.' ); }
$created = array();
$keep = array();
$original_post = $_POST;
$original_user = get_current_user_id();
$today = fisar_cdj_today();
$day = static fn( string $offset ): string => ( new DateTimeImmutable( $today ) )->modify( $offset )->format( 'Y-m-d' );
$year = (int) substr( $today, 0, 4 ) - 1;
$check = static function ( bool $ok, string $message ): void { if ( ! $ok ) { throw new RuntimeException( $message ); } };
$create = static function ( string $title, string $date, string $mode = 'single', string $end = '', array $extra = array(), string $status = 'publish' ) use ( &$created ): int {
	$id = wp_insert_post( array( 'post_type' => 'fisar_evento', 'post_status' => $status, 'post_title' => 'Test locale — ' . $title, 'post_content' => '<p>Esempio locale per verificare le date: non è un evento reale.</p>' ) );
	$created[] = $id;
	foreach ( array( 'date' => $date, 'date_mode' => $mode, 'end_date' => $end, 'additional_dates' => $extra, 'participation' => 'all', 'mode' => 'presence', 'course_id' => 654321 ) as $key => $value ) { update_post_meta( $id, '_fisar_event_' . $key, wp_slash( $value ) ); }
	return $id;
};
$isolate = static function ( WP_Query $query ) use ( &$created ): void { if ( 'fisar_evento' === $query->get( 'post_type' ) && is_array( $query->get( 'fisar_event_calendar_ids', null ) ) ) { $query->set( 'post__in', $created ?: array( 0 ) ); } };
$ids = static fn( WP_Query $query ): array => wp_list_pluck( $query->posts, 'ID' );
try {
	wp_set_current_user( get_users( array( 'role' => 'administrator', 'number' => 1 ) )[0]->ID );
	add_action( 'pre_get_posts', $isolate );
	$legacy = $create( 'giornata precedente', $day( '-2 days' ) );
	delete_post_meta( $legacy, '_fisar_event_date_mode' );
	$single = $create( 'giornata futura', $day( '+2 days' ) );
	$ongoing = $create( 'periodo in corso', $day( '-5 days' ), 'range', $day( '+3 days' ) );
	$gap = $create( 'date distinte', $day( '-10 days' ), 'multiple', '', array( array( 'date' => $day( '+6 days' ), 'note' => "Uscita con l’amico Francesco" ) ) );
	$separate = $create( 'date non consecutive nel passato', "$year-01-10", 'multiple', '', array( array( 'date' => "$year-03-17", 'note' => '<b>Seconda data</b>' ), array( 'date' => "$year-01-10" ), array( 'date' => '2025-02-30' ) ) );
	$cross = $create( 'periodo tra due anni', "$year-12-30", 'range', ( $year + 1 ) . '-01-02' );
	$empty = $create( 'nessuna data', '' );
	$draft = $create( 'bozza', $day( '+1 day' ), 'single', '', array(), 'draft' );
	$check( array( $ongoing, $single, $gap ) === $ids( fisar_cdj_get_upcoming_events() ), 'Order ongoing events first, then future events by next occurrence; exclude drafts/missing dates.' );
	$check( ! fisar_cdj_is_event_past( $ongoing ) && ! fisar_cdj_is_event_past( $gap ) && fisar_cdj_is_event_past( $legacy ), 'Remain upcoming until the last day, including gaps between dates.' );
	$check( array( $separate ) === $ids( fisar_cdj_get_past_events( -1, 1, array( 'year' => $year, 'month' => 3 ) ) ), 'Match additional dates instead of just the first date.' );
	$check( array() === $ids( fisar_cdj_get_past_events( -1, 1, array( 'year' => $year, 'month' => 2 ) ) ), 'A gap between January and March must not match February.' );
	$check( in_array( $cross, $ids( fisar_cdj_get_past_events( -1, 1, array( 'year' => $year + 1, 'month' => 1 ) ) ), true ), 'Match a continuous period in the second year/month.' );
	$check( Fisar_CDJ_Event_Calendar::matches( array( array( 'start' => '2024-12-30', 'end' => '2025-01-02' ) ), 0, 1 ), 'Month-only filters handle year crossings.' );
	$check( ! Fisar_CDJ_Event_Calendar::matches( array( array( 'start' => '2024-12-30', 'end' => '2025-01-02' ) ), 0, 2 ), 'Do not match months outside a range.' );
	$check( Fisar_CDJ_Event_Calendar::matches( array( array( 'start' => '2023-12-30', 'end' => '2025-01-02' ) ), 0, 8 ), 'A multi-year range contains the intermediate months.' );
	$check( in_array( $year + 1, Fisar_CDJ_Event_Calendar::years( true ), true ), 'The year dropdown includes every year touched by a past event.' );
	$check( array_merge( $ids( fisar_cdj_get_upcoming_events( 2, 1 ) ), $ids( fisar_cdj_get_upcoming_events( 2, 2 ) ) ) === array( $ongoing, $single, $gap ), 'Paginate distinct events once, never once per date.' );
	$long_past = $create( 'periodo concluso ieri', "$year-01-01", 'range', $day( '-1 day' ) );
	$check( $ids( fisar_cdj_get_past_events( 1 ) ) === array( $long_past ), 'Order concluded periods by their final day, not their older starting day.' );
	$check( in_array( $gap, $ids( fisar_cdj_get_course_events( 654321 ) ), true ), 'Course-linked events retain multi-date entries.' );
	$check( str_contains( Fisar_CDJ_Event_Calendar::card_label( $gap ), 'PROSSIMA DATA' ) && str_contains( Fisar_CDJ_Event_Calendar::card_label( $ongoing ), 'IN CORSO' ), 'Cards emphasize the next date or current period.' );
	$check( 2 === count( Fisar_CDJ_Event_Calendar::for_event( $separate ) ), 'Deduplicate dates and reject impossible dates.' );
	$before = get_post_meta( $gap );
	$body = Fisar_CDJ_Event_WhatsApp_Message::generate( get_post( $gap ) );
	$check( str_contains( $body['message'], Fisar_CDJ_Event_Calendar::format_date( $day( '-10 days' ) ) ) && str_contains( $body['message'], 'Francesco' ), 'WhatsApp includes all dates and their notes, even elapsed dates.' );
	$check( $before === get_post_meta( $gap ), 'Calendar reads, labels and exports never write metadata.' );
	$nonce = wp_create_nonce( 'fisar_cdj_save_meta' );
	$_POST = array( 'fisar_cdj_meta_nonce' => $nonce, '_fisar_event_calendar_present' => 1, '_fisar_event_date' => $day( '+15 days' ), '_fisar_event_date_mode' => 'multiple', '_fisar_event_date_note' => "Prima uscita con l'amico", '_fisar_event_additional_dates' => array( array( 'date' => $day( '+16 days' ), 'note' => '<script>bad</script> Seconda uscita' ) ) );
	Fisar_CDJ_Meta_Boxes::save_event( $gap );
	$check( count( Fisar_CDJ_Event_Calendar::for_event( $gap ) ) === 2 && get_post_meta( $gap, '_fisar_event_date_note', true ) === "Prima uscita con l'amico", 'Save calendar fields with authorization, escaping and sanitized notes.' );
	$saved = Fisar_CDJ_Event_Calendar::values( $gap );
	$_POST['_fisar_event_additional_dates'][] = $_POST['_fisar_event_additional_dates'][0];
	Fisar_CDJ_Meta_Boxes::save_event( $gap );
	$check( $saved === Fisar_CDJ_Event_Calendar::values( $gap ), 'A duplicate row must not silently overwrite previously saved dates or notes.' );
	$_POST['_fisar_event_date_mode'] = 'range'; $_POST['_fisar_event_end_date'] = $day( '-1 day' );
	Fisar_CDJ_Meta_Boxes::save_event( $gap );
	$check( $saved === Fisar_CDJ_Event_Calendar::values( $gap ), 'An invalid range must preserve all previously saved calendar data.' );
	delete_transient( 'fisar_event_dates_error_' . get_current_user_id() . '_' . $gap );
	$_POST = array( 'fisar_cdj_meta_nonce' => $nonce, '_fisar_event_date' => $day( '+14 days' ) );
	Fisar_CDJ_Meta_Boxes::save_event( $gap );
	$check( 'multiple' === get_post_meta( $gap, '_fisar_event_date_mode', true ) && 1 === count( get_post_meta( $gap, '_fisar_event_additional_dates', true ) ), 'An old editor omitting the new marker preserves additional dates.' );
	$_POST = array( 'fisar_cdj_meta_nonce' => $nonce, '_fisar_event_calendar_present' => 1, '_fisar_event_date' => $day( '+14 days' ), '_fisar_event_date_mode' => 'single' );
	Fisar_CDJ_Meta_Boxes::save_event( $gap );
	$check( count( Fisar_CDJ_Event_Calendar::for_event( $gap ) ) === 1 && get_post_meta( $gap, '_fisar_event_additional_dates', true ) === array(), 'Explicitly switch back to one day and clear additional dates.' );
	$_POST['fisar_cdj_meta_nonce'] = 'invalid'; $_POST['_fisar_event_date'] = $day( '+50 days' );
	Fisar_CDJ_Meta_Boxes::save_event( $gap );
	$check( get_post_meta( $gap, '_fisar_event_date', true ) === $day( '+14 days' ), 'Reject calendar updates without a valid nonce.' );
	Fisar_CDJ_Event_Calendar::save_request( $single, array( '_fisar_event_date_mode' => 'multiple', '_fisar_event_date' => $day( '+20 days' ), '_fisar_event_date_note' => 'Ultima data', '_fisar_event_additional_dates' => array( array( 'date' => $day( '+18 days' ), 'note' => 'Prima data' ) ) ) );
	$check( get_post_meta( $single, '_fisar_event_date', true ) === $day( '+18 days' ) && get_post_meta( $single, '_fisar_event_date_note', true ) === 'Prima data', 'An explicit save orders dates and moves each note with its date, preserving the legacy first-date field.' );
	if ( 'fixtures' === ( $args[0] ?? '' ) ) {
		$fivi = $create( 'Esempio FIVI — due uscite in giornata', $day( '+15 days' ), 'multiple', '', array( array( 'date' => $day( '+16 days' ), 'note' => 'Uscita in giornata con Corrado' ) ) );
		update_post_meta( $fivi, '_fisar_event_date_note', 'Uscita in giornata con Francesco' );
		$keep = array( $fivi, $ongoing, $separate );
		WP_CLI::log( wp_json_encode( array_map( static fn( $id ) => array( 'id' => $id, 'url' => get_permalink( $id ), 'edit' => get_edit_post_link( $id, 'raw' ) ), $keep ) ) );
	}
	WP_CLI::success( 'Event calendar: ranges, distinct dates, states, ordering, filters, pagination, WhatsApp, validation and legacy saves passed.' );
} finally {
	remove_action( 'pre_get_posts', $isolate );
	foreach ( $created as $id ) { if ( ! in_array( $id, $keep, true ) ) { wp_delete_post( $id, true ); } }
	$_POST = $original_post;
	wp_set_current_user( $original_user );
}
