<?php
/** Run via WP-CLI eval-file in the local environment, never on a published site. */
if ( ! defined( 'WP_CLI' ) || ! WP_CLI || 'local' !== wp_get_environment_type() ) {
	throw new RuntimeException( 'This integration test requires local WordPress and WP-CLI.' );
}

$fixture_mode = 'fixtures' === ( $args[0] ?? '' );
$keep_fixtures = false;
$created = array();
$original_user = get_current_user_id();
$original_post = $_POST;
$check = static function ( bool $condition, string $message ): void {
	if ( ! $condition ) {
		throw new RuntimeException( $message );
	}
};
$create_event = static function ( string $title, array $meta ) use ( &$created ): int {
	$id = wp_insert_post( array( 'post_type' => 'fisar_evento', 'post_status' => 'publish', 'post_title' => 'Test locale — ' . $title, 'post_content' => '<!-- wp:paragraph --><p>Evento di prova locale per verificare ritrovo e sede effettiva.</p><!-- /wp:paragraph -->' ), true );
	if ( is_wp_error( $id ) ) {
		throw new RuntimeException( $id->get_error_message() );
	}
	$created[] = $id;
	foreach ( array_merge( array( '_fisar_test_fixture' => 'event-meeting', '_fisar_event_date' => '2026-11-28', '_fisar_event_mode' => 'presence', '_fisar_event_is_free' => '1', '_fisar_event_participation' => 'all' ), $meta ) as $key => $value ) {
		update_post_meta( $id, $key, wp_slash( $value ) );
	}
	return $id;
};

try {
	$admin = get_users( array( 'role' => 'administrator', 'number' => 1 ) );
	wp_set_current_user( $admin[0]->ID );
	$meeting_meta = array(
		'_fisar_event_meeting_time' => '18:15',
		'_fisar_event_meeting_venue' => 'Parcheggio Gala Superstore',
		'_fisar_event_meeting_address' => 'Piazzale del supermercato',
		'_fisar_event_meeting_city' => 'Monte Roberto',
		'_fisar_event_meeting_province' => 'an',
		'_fisar_event_meeting_maps_url' => 'https://maps.google.com/?q=Monte+Roberto',
		'_fisar_event_meeting_notes' => "Dal parcheggio ci sposteremo insieme in vigna, organizzandoci in auto condivise.\nChi arriverà più tardi potrà raggiungerci direttamente: le coordinate saranno comunicate successivamente.",
	);
	$event_meta = array( '_fisar_event_start_time' => '19:00', '_fisar_event_welcome_time' => '18:45', '_fisar_event_end_time' => '22:00', '_fisar_event_venue' => 'Cantina di prova', '_fisar_event_address' => 'Via della Vigna, 12', '_fisar_event_city' => 'Cupramontana', '_fisar_event_province' => 'AN', '_fisar_event_maps_url' => 'https://maps.google.com/?q=Cupramontana' );
	$ordinary = $create_event( 'Solo evento', $event_meta );
	$meeting_only = $create_event( 'Solo ritrovo', $meeting_meta );
	$both = $create_event( 'Ritrovo ed evento', array_merge( $meeting_meta, $event_meta ) );
	$check( ! fisar_cdj_get_event_meeting( $ordinary )['has_details'], 'Ordinary events must not get a meeting.' );
	$meeting = fisar_cdj_get_event_meeting( $meeting_only );
	$check( $meeting['time'] === '18:15' && $meeting['city'] === 'Monte Roberto (AN)' && $meeting['has_location'], 'Meeting-only data must remain distinct and normalized.' );
	$check( '' === get_post_meta( $meeting_only, '_fisar_event_start_time', true ), 'Do not invent an event start time.' );
	$check( fisar_cdj_get_event_meeting( $both )['time'] === '18:15' && get_post_meta( $both, '_fisar_event_start_time', true ) === '19:00', 'Both schedules must coexist.' );
	$check( get_post_meta( $both, '_fisar_event_maps_url', true ) !== fisar_cdj_get_event_meeting( $both )['maps_url'], 'Meeting and event Maps links must remain separate.' );

	foreach ( array( '25:00', '18:77', '<script>18:15</script>', array( '18:15' ) ) as $invalid ) {
		$check( '' === fisar_cdj_sanitize_event_meeting_value( $invalid, 'time' ), 'Reject invalid meeting times.' );
	}
	$check( '' === fisar_cdj_sanitize_event_meeting_value( 'javascript:alert(1)', 'maps_url' ), 'Reject unsafe Maps URLs.' );
	$check( 'AN' === fisar_cdj_sanitize_event_meeting_value( 'a-n', 'province' ), 'Normalize province.' );
	$check( ! str_contains( fisar_cdj_sanitize_event_meeting_value( '<b>Nota</b>', 'notes' ), '<b>' ), 'Notes must be plain text.' );
	update_post_meta( $meeting_only, '_fisar_event_meeting_time', '99:00' );
	$check( '' === get_post_meta( $meeting_only, '_fisar_event_meeting_time', true ), 'Registered meta must sanitize direct writes too.' );
	update_post_meta( $meeting_only, '_fisar_event_meeting_time', '18:15' );

	update_post_meta( $both, '_fisar_event_mode', 'online' );
	$check( ! fisar_cdj_get_event_meeting( $both )['has_details'] && '' === fisar_cdj_get_event_meeting( $both )['notes'], 'Online-only events must not expose meeting details.' );
	update_post_meta( $both, '_fisar_event_mode', 'hybrid' );
	$check( fisar_cdj_get_event_meeting( $both )['has_details'], 'Hybrid events may have a meeting.' );
	update_post_meta( $both, '_fisar_event_mode', 'presence' );

	$_POST = array( 'fisar_cdj_meta_nonce' => wp_create_nonce( 'fisar_cdj_save_meta' ), '_fisar_event_mode' => 'presence' );
	Fisar_CDJ_Meta_Boxes::save_event( $both );
	$check( '18:15' === fisar_cdj_get_event_meeting( $both )['time'], 'An old editor without the meeting marker must not erase meeting data.' );
	$_POST['_fisar_event_meeting_present'] = '1';
	$_POST['_fisar_event_meeting_time'] = '17:45';
	$_POST['_fisar_event_meeting_notes'] = wp_slash( "L'arrivo è previsto qui.\nSeconda riga." );
	Fisar_CDJ_Meta_Boxes::save_event( $both );
	$check( '17:45' === fisar_cdj_get_event_meeting( $both )['time'], 'Save the submitted meeting time.' );
	$check( "L'arrivo è previsto qui.\nSeconda riga." === fisar_cdj_get_event_meeting( $both )['notes'], 'Preserve apostrophes and newlines.' );
	$check( 'Parcheggio Gala Superstore' === fisar_cdj_get_event_meeting( $both )['venue'], 'Omitted fields must not erase meeting values.' );
	$_POST['_fisar_event_meeting_time'] = '';
	$_POST['_fisar_event_meeting_notes'] = '';
	Fisar_CDJ_Meta_Boxes::save_event( $both );
	$check( '' === fisar_cdj_get_event_meeting( $both )['time'] && '' === fisar_cdj_get_event_meeting( $both )['notes'], 'Explicitly empty fields must clear their values.' );
	foreach ( array_merge( $meeting_meta, $event_meta, array( '_fisar_event_date' => '2026-11-28', '_fisar_event_mode' => 'presence', '_fisar_event_is_free' => '1', '_fisar_event_participation' => 'all' ) ) as $key => $value ) {
		update_post_meta( $both, $key, wp_slash( $value ) );
	}

	ob_start();
	Fisar_CDJ_Meta_Boxes::render_event_schedule( get_post( $meeting_only ) );
	$schedule_editor = ob_get_clean();
	$check( ! preg_match( '/id="_fisar_event_start_time"[^>]*\brequired\b/', $schedule_editor ), 'The editor must allow an unknown actual start time.' );
	ob_start();
	Fisar_CDJ_Meta_Boxes::render_event_meeting( get_post( $both ) );
	$meeting_editor = ob_get_clean();
	foreach ( Fisar_CDJ_Post_Types::EVENT_MEETING_FIELDS as $field ) {
		$check( str_contains( $meeting_editor, 'name="_fisar_event_meeting_' . $field . '"' ), 'Editor must expose every meeting field.' );
	}
	ob_start();
	get_template_part( 'template-parts/event-meeting', null, array( 'meeting' => fisar_cdj_get_event_meeting( $both ) ) );
	$rendered = ob_get_clean();
	$check( str_contains( $rendered, '18:15' ) && str_contains( $rendered, 'Monte Roberto (AN)' ) && str_contains( $rendered, '<br' ), 'Render meeting time, place and multiline instructions.' );
	foreach ( array( 'home', 'archive', 'related' ) as $context ) {
		foreach ( array( $ordinary, $meeting_only, $both ) as $id ) {
			$GLOBALS['post'] = get_post( $id );
			setup_postdata( $GLOBALS['post'] );
			ob_start();
			get_template_part( 'template-parts/event-card', null, array( 'context' => $context ) );
			$card = ob_get_clean();
			$check( ( $id !== $ordinary ) === str_contains( $card, 'Ritrovo: Parcheggio Gala Superstore' ), 'Cards must prefer an explicitly labeled meeting, never relabel the event venue.' );
		}
	}
	wp_reset_postdata();
	$_POST = array( '_fisar_event_meeting_present' => '1', '_fisar_event_meeting_time' => '12:00' );
	Fisar_CDJ_Meta_Boxes::save_event( $both );
	$check( '18:15' === fisar_cdj_get_event_meeting( $both )['time'], 'A save without a valid nonce must not change the meeting.' );
	wp_set_current_user( 0 );
	$_POST['fisar_cdj_meta_nonce'] = wp_create_nonce( 'fisar_cdj_save_meta' );
	Fisar_CDJ_Meta_Boxes::save_event( $both );
	$check( '18:15' === fisar_cdj_get_event_meeting( $both )['time'], 'A user without edit permission must not change the meeting.' );
	WP_CLI::success( 'Event meeting: ordinary/meeting-only/both, sanitization, online/hybrid, guarded saves, clear, editor and shared template passed.' );
	$keep_fixtures = $fixture_mode;
	if ( $fixture_mode ) {
		foreach ( $created as $id ) {
			WP_CLI::line( $id . ' ' . get_permalink( $id ) );
		}
	}
} finally {
	$_POST = $original_post;
	wp_set_current_user( $original_user );
	if ( ! $keep_fixtures ) {
		foreach ( $created as $id ) {
			wp_delete_post( $id, true );
		}
	}
}
