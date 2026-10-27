<?php
/** Local-only integration tests; dedicated fixtures are removed unless explicitly kept. */
if ( ! defined( 'WP_CLI' ) || ! WP_CLI || 'local' !== wp_get_environment_type() ) {
	throw new RuntimeException( 'Local WordPress and WP-CLI are required.' );
}
$created = array();
$keep = false;
$user = get_current_user_id();
$check = static function ( bool $condition, string $message ): void {
	if ( ! $condition ) { throw new RuntimeException( $message ); }
};
$generate = static fn( int $id ): array => Fisar_CDJ_Event_WhatsApp_Message::generate( get_post( $id ) );
try {
	$admin = get_users( array( 'role' => 'administrator', 'number' => 1 ) );
	wp_set_current_user( $admin[0]->ID );
	$future = current_datetime()->modify( '+60 days' )->format( 'Y-m-d' );
	$deadline = current_datetime()->modify( '+55 days' )->format( 'Y-m-d' );
	$yesterday = current_datetime()->modify( '-1 day' )->format( 'Y-m-d' );
	$content = '<!-- wp:paragraph --><p>Una degustazione con <strong>vini giapponesi</strong> e una <em>light dinner</em>.</p><!-- /wp:paragraph --><!-- wp:heading --><h2>In degustazione</h2><!-- /wp:heading --><!-- wp:list {"ordered":true} --><ol><li>Koshu &amp; sakè</li><li>Rosato</li></ol><!-- /wp:list --><h3>In abbinamento</h3><ul><li>Tempura</li><li>Risotto</li></ul><p><a href="https://example.org/menu">Il menu</a></p>';
	$id = wp_insert_post( array( 'post_type' => 'fisar_evento', 'post_status' => 'draft', 'post_title' => 'Test locale — Degustazione di tè e vini', 'post_content' => $content, 'post_excerpt' => 'Estratto da non duplicare.' ), true );
	$check( ! is_wp_error( $id ), 'Create the dedicated event fixture.' );
	$created[] = $id;
	update_post_meta( $id, '_fisar_test_fixture', 'event-whatsapp-message' );
	$set = static function ( string $key, mixed $value ) use ( $id ): void { update_post_meta( $id, '_fisar_event_' . $key, wp_slash( $value ) ); };
	foreach ( array(
		'date' => $future, 'mode' => 'presence', 'welcome_time' => '19:45', 'start_time' => '20:00', 'end_time' => '23:00',
		'venue' => 'Ristorante di prova', 'address' => 'Via delle Vigne, 4', 'city' => 'Montemarciano', 'province' => 'AN',
		'maps_url' => 'https://maps.google.com/?q=Montemarciano', 'participation' => 'all', 'registration_required' => '1',
		'member_price' => '50', 'non_member_price' => '60', 'fee_note' => 'Menu e vini inclusi.',
		'fee_options' => array( array( 'label' => 'Menu senza vini', 'amount' => '40', 'note' => 'Percorso gastronomico senza abbinamento.' ), array( 'label' => 'Da ignorare', 'amount' => '' ) ),
		'deadline' => $deadline, 'deadline_type' => 'flexible', 'limited_seats' => '1',
		'whatsapp_contacts' => array( array( 'name' => 'Contatto uno', 'value' => '+39 3350000001' ), array( 'name' => 'Contatto due', 'value' => '3350000002' ) ),
		'email' => 'eventi@example.org', 'phone' => '0710000000', 'form_url' => 'https://example.org/prenota', 'other_channel' => 'Segreteria',
		'registration_notes' => '<p>Segnala eventuali <strong>esigenze alimentari</strong>.</p>',
	) as $key => $value ) { $set( $key, $value ); }
	$before_post = get_post( $id, ARRAY_A );
	$before_meta = get_post_meta( $id );
	$result = $generate( $id );
	$message = $result['message'];
	$check( str_starts_with( $message, '🍷 *TEST LOCALE — DEGUSTAZIONE DI TÈ E VINI*' ), 'Uppercase the event title, including accented letters.' );
	$check( str_contains( $message, 'Posizione: https://maps.google.com/' ) && ! str_contains( $message, 'Posizione dell’evento' ), 'Use the shorter map label.' );
	foreach ( array( '*vini giapponesi*', '_light dinner_', '*In degustazione*', "1. Koshu & sakè\n2. Rosato", "- Tempura\n- Risotto", 'Il menu (https://example.org/menu)', 'Accoglienza dalle 19:45 · Inizio ore 20:00 · Fine ore 23:00', 'Via delle Vigne, 4', 'Montemarciano (AN)', 'Soci FISAR: 50 €', 'Non soci: 60 €', 'Menu senza vini: 40 €', 'Percorso gastronomico senza abbinamento.', 'POSTI LIMITATI', 'Dopo tale termine', 'WhatsApp: Contatto uno 3350000001', 'WhatsApp: Contatto due 3350000002', 'eventi@example.org', 'https://example.org/prenota', '*esigenze alimentari*' ) as $expected ) {
		$check( str_contains( $message, $expected ), 'Expected in message: ' . $expected );
	}
	$check( ! str_contains( $message, '**' ) && ! str_contains( $message, '<p>' ) && ! str_contains( $message, '<!--' ), 'WhatsApp markers, not Markdown/HTML/block comments.' );
	$check( str_contains( $message, "*In degustazione*\n1. Koshu" ) && str_contains( $message, "*In abbinamento*\n- Tempura" ), 'No empty line after section headings, including before lists.' );
	$check( ! str_contains( $message, 'Estratto da non duplicare' ) && ! str_contains( $message, 'Da ignorare' ) && ! str_contains( $message, '+39' ), 'No duplicate excerpt, incomplete fees or unnecessary Italian prefix.' );
	$check( ! str_contains( $message, 'TROVI I DETTAGLI ANCHE SUL SITO' ) && ! empty( $result['warnings'] ), 'Drafts must not export a public link.' );
	$check( $before_post === get_post( $id, ARRAY_A ) && $before_meta === get_post_meta( $id ), 'Generation must not write editorial content or metadata.' );
	$set( 'deadline', '2026-10-04' );
	$ordinary_deadline = $generate( $id )['message'];
	$check( str_contains( $ordinary_deadline, '*⚠️ PRENOTAZIONI ENTRO DOMENICA 4 OTTOBRE*' ) && ! str_contains( $ordinary_deadline, '4 OTTOBRE 2026' ) && str_contains( $ordinary_deadline, "*👉 INFO E PRENOTAZIONI*\n" ), 'Ordinary deadline is uppercase without year; contacts have the shared heading.' );
	$set( 'deadline', $deadline );

	$nonce = wp_create_nonce( Fisar_CDJ_Event_WhatsApp_Message::ACTION );
	$check( ! is_wp_error( Fisar_CDJ_Event_WhatsApp_Message::request( array( 'post_id' => $id, 'nonce' => $nonce ) ) ), 'An authorized editor can generate.' );
	$check( is_wp_error( Fisar_CDJ_Event_WhatsApp_Message::request( array( 'post_id' => $id, 'nonce' => 'invalid' ) ) ), 'Reject invalid nonce.' );
	$check( is_wp_error( Fisar_CDJ_Event_WhatsApp_Message::request( array( 'post_id' => array( $id ), 'nonce' => $nonce ) ) ), 'Reject malformed IDs without warnings.' );
	$check( ! has_action( 'wp_ajax_nopriv_' . Fisar_CDJ_Event_WhatsApp_Message::ACTION ), 'No public generator endpoint.' );
	wp_set_current_user( 0 );
	$anonymous_nonce = wp_create_nonce( Fisar_CDJ_Event_WhatsApp_Message::ACTION );
	$check( is_wp_error( Fisar_CDJ_Event_WhatsApp_Message::request( array( 'post_id' => $id, 'nonce' => $anonymous_nonce ) ) ), 'Anonymous requests are forbidden even with a valid anonymous nonce.' );
	wp_set_current_user( $admin[0]->ID );
	$other = wp_insert_post( array( 'post_type' => 'post', 'post_status' => 'draft', 'post_title' => 'Test locale — Tipo non consentito' ) );
	$created[] = $other;
	update_post_meta( $other, '_fisar_test_fixture', 'event-whatsapp-message' );
	$check( is_wp_error( Fisar_CDJ_Event_WhatsApp_Message::request( array( 'post_id' => $other, 'nonce' => $nonce ) ) ), 'Reject other post types.' );

	foreach ( array( 'time' => '18:15', 'venue' => 'Parcheggio di prova', 'address' => 'Via del Ritrovo, 1', 'city' => 'Monte Roberto', 'province' => 'AN', 'maps_url' => 'https://maps.google.com/?q=ritrovo', 'notes' => 'Ci sposteremo insieme in auto. Arrivi successivi direttamente in vigna.' ) as $key => $value ) { $set( 'meeting_' . $key, $value ); }
	$meeting = $generate( $id )['message'];
	$check( str_contains( $meeting, '*Ritrovo:* ore 18:15' ) && str_contains( $meeting, '*Luogo dell’evento:* Ristorante' ) && str_contains( $meeting, 'Inizio ore 20:00' ) && str_contains( $meeting, 'Ci sposteremo insieme' ), 'Both meeting and actual event remain distinct.' );
	foreach ( array( 'welcome_time', 'start_time', 'end_time', 'venue', 'address', 'city', 'province', 'maps_url' ) as $key ) { $set( $key, '' ); }
	$meeting_only = $generate( $id )['message'];
	$check( str_contains( $meeting_only, 'Parcheggio di prova' ) && ! str_contains( $meeting_only, '*Luogo dell’evento:*' ) && ! str_contains( $meeting_only, 'Inizio ore' ), 'Meeting-only event: never invent a venue or start time.' );
	$set( 'mode', 'online' );
	$set( 'platform', 'Piattaforma riservata' );
	$set( 'online_url', 'https://example.org/accesso-segreto' );
	$set( 'online_access_public', false );
	$online = $generate( $id )['message'];
	$check( str_contains( $online, '*Online*' ) && ! str_contains( $online, 'segreto' ) && ! str_contains( $online, 'riservata' ) && ! str_contains( $online, 'Ritrovo' ), 'Do not leak hidden online access or irrelevant meeting data.' );
	$set( 'online_access_public', true );
	$check( str_contains( $generate( $id )['message'], 'https://example.org/accesso-segreto' ), 'Explicitly public online access can be exported.' );
	$set( 'mode', 'hybrid' );
	$check( str_contains( $generate( $id )['message'], 'Evento ibrido: in presenza e online' ), 'Hybrid mode is explicit.' );

	$set( 'booking_status', 'sold_out' );
	$sold_out = $generate( $id )['message'];
	$check( str_contains( $sold_out, 'Sold-out' ) && ! str_contains( $sold_out, 'PRENOTAZIONI ENTRO' ) && ! str_contains( $sold_out, 'eventi@example.org' ), 'Sold-out has neither deadline nor booking channels.' );
	$set( 'booking_status', 'waitlist' );
	$set( 'deadline', '2026-10-04' );
	$waitlist = $generate( $id )['message'];
	$check( str_contains( $waitlist, '*⚠️ SOLD-OUT - LISTA D’ATTESA*' ) && str_contains( $waitlist, '*⚠️ PRENOTAZIONI ENTRO DOMENICA 4 OTTOBRE (LISTA DI ATTESA)*' ) && str_contains( $waitlist, "*👉 INFO E PRENOTAZIONI*\n" ) && str_contains( $waitlist, 'eventi@example.org' ), 'Waiting list has uppercase status with a hyphen, shared deadline format and shared contacts heading.' );
	$check( ! str_contains( $waitlist, 'RICHIESTE DI LISTA' ) && ! str_contains( $waitlist, 'CONTATTI PER LA LISTA' ) && ! str_contains( $waitlist, 'COME PRENOTARE' ), 'No superseded waiting-list labels.' );
	$set( 'deadline_type', 'strict' );
	$set( 'deadline', $yesterday );
	$expired = $generate( $id )['message'];
	$check( str_contains( $expired, 'SOLD-OUT - LISTA D’ATTESA' ) && str_contains( $expired, 'INFO E PRENOTAZIONI' ) && str_contains( $expired, 'eventi@example.org' ) && ! str_contains( $expired, 'Iscrizioni chiuse' ), 'Export ignores an elapsed deadline but preserves the editorial waiting list.' );
	$check( 'closed' === fisar_cdj_get_event_booking_status( $id ) && fisar_cdj_get_event_registration_details( $id )['closed'] && array() === fisar_cdj_get_registration_channels( $id, 'event' ), 'Public APIs still close an expired strict deadline.' );
	$set( 'date', $yesterday );
	$set( 'booking_status', 'available' );
	$before_post = get_post( $id, ARRAY_A );
	$before_meta = get_post_meta( $id );
	$past = $generate( $id )['message'];
	$check( ! str_contains( $past, 'Evento concluso' ) && ! str_contains( $past, 'Iscrizioni chiuse' ) && str_contains( $past, 'PRENOTAZIONI ENTRO' ) && str_contains( $past, 'INFO E PRENOTAZIONI' ) && str_contains( $past, 'accesso-segreto' ) && str_contains( $past, 'eventi@example.org' ), 'Past events export ordinary booking data and explicitly public online access.' );
	$check( fisar_cdj_is_event_past( $id ) && '' === fisar_cdj_get_event_online_access( $id )['url'], 'The public site still recognizes past events and hides their online links.' );
	$check( $before_post === get_post( $id, ARRAY_A ) && $before_meta === get_post_meta( $id ), 'Ignoring elapsed dates during export must not change saved data.' );
	$set( 'online_access_public', false );
	$check( ! str_contains( $generate( $id )['message'], 'accesso-segreto' ), 'Ignoring elapsed dates never overrides hidden online access.' );
	$set( 'booking_status', 'sold_out' );
	$past_sold_out = $generate( $id )['message'];
	$check( str_contains( $past_sold_out, 'Sold-out' ) && ! str_contains( $past_sold_out, 'eventi@example.org' ) && ! str_contains( $past_sold_out, 'PRENOTAZIONI ENTRO' ), 'Editorial sold-out remains effective for old events and deadlines.' );
	$set( 'date', $future );
	$set( 'registration_required', false );
	$set( 'is_free', true );
	$free = $generate( $id )['message'];
	$check( str_contains( $free, 'Ingresso gratuito' ) && str_contains( $free, 'Non è richiesta la prenotazione' ) && ! str_contains( $free, '50 €' ) && ! str_contains( $free, 'eventi@example.org' ), 'Free/no booking omits paid fees and booking channels.' );
	wp_update_post( array( 'ID' => $id, 'post_status' => 'publish' ) );
	$check( str_contains( $generate( $id )['message'], "*🌐 TROVI I DETTAGLI ANCHE SUL SITO*\n" . get_permalink( $id ) ), 'Published events have the web icon and the correct environment link.' );
	wp_update_post( array( 'ID' => $id, 'post_status' => 'draft' ) );

	$unsafe = Fisar_CDJ_Event_WhatsApp_Message::from_html( '<p>A <strong>test</strong><br>new line.</p><script>secret-script</script><iframe>secret-frame</iframe><a href="javascript:alert(1)">test link</a>' );
	$check( ! str_contains( $unsafe, 'secret-' ) && ! str_contains( $unsafe, 'javascript:' ) && str_contains( $unsafe, '*test*' ) && str_contains( $unsafe, "\nnew line" ), 'Safe conversion preserves text and removes executable content.' );
	$nested = Fisar_CDJ_Event_WhatsApp_Message::from_html( '<h2><strong>Titolo</strong></h2><p><b>Testo <strong>e altro</strong></b> <em><i>corsivo</i></em></p>' );
	$check( ! str_contains( $nested, '**' ) && ! str_contains( $nested, '__' ) && str_contains( $nested, '*Titolo*' ) && str_contains( $nested, '*Testo e altro*' ), 'Nested equivalent tags must not produce Markdown double markers.' );
	$emoji_heading = Fisar_CDJ_Event_WhatsApp_Message::from_html( '<p>🍷 <strong>IN DEGUSTAZIONE</strong></p><ol><li>Vino uno</li></ol><p>🍽️ <strong>IN ABBINAMENTO</strong></p><p>Piatto uno</p>' );
	$check( str_contains( $emoji_heading, "🍷 *IN DEGUSTAZIONE*\n1. Vino uno" ) && str_contains( $emoji_heading, "🍽️ *IN ABBINAMENTO*\nPiatto uno" ), 'Standalone bold paragraph headings with emoji also stay close to their content.' );
	$order = Fisar_CDJ_Meta_Boxes::event_meta_box_order( array( 'normal' => 'fisar-event-whatsapp-message,fisar-event-meeting,fisar-event-location,fisar-event-registration', 'side' => 'fisar-event-course' ) );
	$check( str_ends_with( $order['normal'], 'fisar-event-whatsapp-message' ) && ! str_contains( $order['side'], 'whatsapp-message' ), 'Communication tools remain last with a previously saved custom box order.' );
	$partial_order = Fisar_CDJ_Meta_Boxes::event_meta_box_order( array( 'side' => 'fisar-event-course' ) );
	$check( ! isset( $partial_order['normal'] ), 'Partial preferences must not force the new box before default normal boxes.' );
	$shortcode_called = false;
	add_shortcode( 'fisar_whatsapp_test', static function () use ( &$shortcode_called ): string { $shortcode_called = true; return 'Non eseguire'; } );
	Fisar_CDJ_Event_WhatsApp_Message::from_html( '[fisar_whatsapp_test]Testo[/fisar_whatsapp_test]' );
	remove_shortcode( 'fisar_whatsapp_test' );
	$check( ! $shortcode_called, 'Export must never execute shortcodes.' );
	$set( 'date', 'not-a-date' );
	$check( count( $generate( $id )['warnings'] ) >= 2, 'Missing date and draft link produce editorial warnings.' );
	WP_CLI::success( 'WhatsApp export: formatting, all event modes, fees, statuses, link privacy, authorization and read-only generation passed.' );
	$keep = 'fixtures' === ( $args[0] ?? '' );
	if ( $keep ) {
		// A realistic future fixture for browser QA; never reuse a real event.
		$set( 'date', $future ); $set( 'mode', 'presence' ); $set( 'is_free', false ); $set( 'registration_required', true ); $set( 'booking_status', 'available' ); $set( 'deadline', $deadline ); $set( 'deadline_type', 'flexible' );
		WP_CLI::line( admin_url( 'post.php?post=' . $id . '&action=edit' ) );
		WP_CLI::line( 'Fixture IDs: ' . implode( ', ', $created ) );
	}
} finally {
	wp_set_current_user( $user );
	if ( ! $keep ) { foreach ( $created as $id ) { wp_delete_post( $id, true ); } }
}
