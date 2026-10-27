<?php
/** Local WordPress integration checks; never run against the published site. */
if ( ! defined( 'WP_CLI' ) || ! WP_CLI || 'local' !== wp_get_environment_type() ) {
	throw new RuntimeException( 'Local WordPress and WP-CLI are required.' );
}
$keep = false;
$created = array();
$check = static function ( bool $condition, string $message ): void {
	if ( ! $condition ) {
		throw new RuntimeException( $message );
	}
};
$render = static function ( callable $callback, WP_Post $post ): array {
	ob_start();
	$callback( $post );
	$html = ob_get_clean();
	$dom = new DOMDocument();
	@$dom->loadHTML( '<?xml encoding="UTF-8">' . $html );
	return array( $html, new DOMXPath( $dom ) );
};
try {
	$content = '<!-- wp:paragraph --><p>Testo del contenuto, indipendente dalla breve descrizione.</p><!-- /wp:paragraph --><!-- wp:heading --><h2 class="wp-block-heading">Titolo H2 di prova</h2><!-- /wp:heading --><!-- wp:heading {"level":3} --><h3 class="wp-block-heading">Titolo H3 di prova</h3><!-- /wp:heading --><!-- wp:heading {"level":4} --><h4 class="wp-block-heading">Titolo H4 di prova</h4><!-- /wp:heading -->';
	foreach ( array( 'fisar_corso', 'fisar_evento', 'post' ) as $type ) {
		$id = wp_insert_post( array( 'post_type' => $type, 'post_status' => 'draft', 'post_title' => 'Test editor — ' . $type, 'post_excerpt' => 'Breve descrizione già presente.', 'post_content' => $content ), true );
		$check( ! is_wp_error( $id ), 'Create a dedicated local fixture.' );
		$created[ $type ] = $id;
		update_post_meta( $id, '_fisar_test_fixture', 'editor-workflow' );
	}
	$course = get_post( $created['fisar_corso'] );
	$post = get_post( $created['post'] );
	foreach ( array( '_fisar_course_start_date' => '2026-11-04', '_fisar_course_end_date' => '2027-02-10', '_fisar_course_fee' => '<p>590</p>', '_fisar_course_includes' => '<p>Kit e degustazioni.</p>', '_fisar_course_membership' => '<p>Tessera separata.</p>', '_fisar_course_offer_enabled' => '1', '_fisar_course_offer_end_date' => '2026-10-31' ) as $key => $value ) {
		update_post_meta( $course->ID, $key, $value );
	}
	$before = get_post_meta( $course->ID );
	list( $details, $details_xpath ) = $render( array( 'Fisar_CDJ_Meta_Boxes', 'render_course_details' ), $course );
	$check( $details_xpath->query( '//section[@aria-labelledby="fisar-course-identity-title"]//input[@name="_fisar_course_director"]' )->length === 1, 'Group director/level separately from dates.' );
	$check( $details_xpath->query( '//section[@aria-labelledby="fisar-course-duration-title"]//div[contains(@class,"fisar-admin-grid--dates")]//input[@type="date"]' )->length === 2, 'Put both course dates in one compact row.' );
	list( $fees, $fees_xpath ) = $render( array( 'Fisar_CDJ_Meta_Boxes', 'render_course_offer' ), $course );
	foreach ( array( 'fee' => 'fee', 'includes' => 'includes', 'membership' => 'membership' ) as $section => $field ) {
		$check( $fees_xpath->query( '//section[@aria-labelledby="fisar-course-' . $section . '-title"]//textarea[@name="_fisar_course_' . $field . '"]' )->length === 1, 'Keep each rich field in its own section with the same storage name.' );
	}
	$check( $fees_xpath->query( '//section[@aria-labelledby="fisar-course-promotion-title"]//input[@name="_fisar_course_offer_enabled"]' )->length === 1 && $fees_xpath->query( '//section[@aria-labelledby="fisar-course-promotion-title"]//input[@name="_fisar_course_offer_end_date"]' )->length === 1, 'Keep promotion controls together, distinct from prices.' );
	list( $calendar, $calendar_xpath ) = $render( array( 'Fisar_CDJ_Meta_Boxes', 'render_course_calendar' ), $course );
	$check( $calendar_xpath->query( '//section[@aria-labelledby="fisar-calendar-import-title"]//textarea[@name="fisar_course_calendar_import"]' )->length === 1, 'Import is a separate optional section.' );
	$check( $calendar_xpath->query( '//section[@aria-labelledby="fisar-calendar-lessons-title"]//table[@id="fisar-calendar-table"]' )->length === 1, 'The editable lesson table remains separate and keeps its JS hooks.' );
	list( $registration, $registration_xpath ) = $render( array( 'Fisar_CDJ_Meta_Boxes', 'render_course_registration' ), $course );
	$check( $registration_xpath->query( '//section[@aria-labelledby="fisar-course-registration-notes-title"]//textarea[@name="_fisar_course_registration_notes"]' )->length === 1, 'Registration notes get their own section.' );
	list( $summary, $summary_xpath ) = $render( array( 'Fisar_CDJ_Meta_Boxes', 'render_post_summary' ), $post );
	$check( $summary_xpath->query( '//textarea[@id="excerpt" and @name="excerpt"]' )->length === 1, 'Blog uses exactly one native excerpt field.' );
	$check( str_contains( $summary, 'Breve descrizione già presente.' ) && str_contains( $summary, '1080 × 1080' ) && str_contains( $summary, 'Galleria' ), 'Preserve the existing excerpt and explain featured images versus galleries.' );
	$check( str_contains( $summary, 'ricava un estratto dal testo' ), 'Blog guidance describes its real fallback behavior.' );
	$check( $before === get_post_meta( $course->ID ), 'Rendering must not write or migrate course metadata.' );
	$check( $content === get_post_field( 'post_content', $post->ID, 'raw' ), 'Rendering must not rewrite article blocks.' );
	$check( 'Breve descrizione già presente.' === get_post_field( 'post_excerpt', $post->ID, 'raw' ), 'Rendering must not overwrite the native excerpt.' );
	foreach ( array( 'course' => 'fisar_corso', 'event' => 'fisar_evento' ) as $prefix => $type ) {
		$fixture = get_post( $created[ $type ] );
		$callback = array( 'Fisar_CDJ_Meta_Boxes', 'render_' . $prefix . '_registration' );
		foreach ( array( 0, 1, 2 ) as $count ) {
			$contacts = array_slice( array( array( 'name' => 'Test uno', 'value' => '3350000001' ), array( 'name' => 'Test due', 'value' => '3350000002' ) ), 0, $count );
			update_post_meta( $fixture->ID, '_fisar_' . $prefix . '_whatsapp_contacts', $contacts );
			list( $html, $xpath ) = $render( $callback, $fixture );
			$check( $xpath->query( '//div[@id="fisar-whatsapp-contacts"]/fieldset' )->length === $count, 'Only saved WhatsApp contacts belong to the interactive list.' );
			$check( $xpath->query( '//div[@id="fisar-whatsapp-contacts" and @data-next-index="' . $count . '"]' )->length === 1, 'The add button starts at the first unused index.' );
			$check( $xpath->query( '//noscript/fieldset//input[@name="_fisar_' . $prefix . '_whatsapp_contacts[' . $count . '][value]" and @value=""]' )->length === 1, 'A blank fallback is available only without JavaScript.' );
			$check( $contacts === get_post_meta( $fixture->ID, '_fisar_' . $prefix . '_whatsapp_contacts', true ), 'Opening the editor must not change contacts.' );
		}
	}
	WP_CLI::success( 'Editors: course groups, compact dates, promotion, calendar, notes, native Blog excerpt and read-only rendering passed.' );
	$keep = 'fixtures' === ( $args[0] ?? '' );
	if ( $keep ) {
		foreach ( $created as $type => $id ) {
			WP_CLI::line( $type . ' ' . $id . ' ' . admin_url( 'post.php?post=' . $id . '&action=edit' ) );
		}
	}
} finally {
	if ( ! $keep ) {
		foreach ( $created as $id ) {
			wp_delete_post( $id, true );
		}
	}
}
