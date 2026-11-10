<?php
/** Integration checks for incomplete historical dates; local WordPress only. */
if ( ! defined( 'WP_CLI' ) || ! WP_CLI || 'local' !== wp_get_environment_type() ) {
	throw new RuntimeException( 'Run only in local WordPress with WP-CLI.' );
}
$created = array();
$original_post = $GLOBALS['post'] ?? null;
$original_request = $_POST;
$original_user = get_current_user_id();
$check = static function ( bool $condition, string $message ): void {
	if ( ! $condition ) { throw new RuntimeException( $message ); }
};
$make = static function ( array $meta, string $status = 'publish' ) use ( &$created ): int {
	$id = wp_insert_post( array( 'post_type' => 'fisar_corso', 'post_status' => $status, 'post_title' => 'Test locale — archivio corsi', 'meta_input' => $meta ), true );
	if ( is_wp_error( $id ) ) { throw new RuntimeException( $id->get_error_message() ); }
	$created[] = $id;
	return $id;
};
try {
	$today = fisar_cdj_today();
	$future = current_datetime()->modify( '+1 year' )->format( 'Y-m-d' );
	$past = current_datetime()->modify( '-1 year' )->format( 'Y-m-d' );
	$active = $make( array( '_fisar_course_start_date' => $today, '_fisar_course_end_date' => $future ) );
	$dated = $make( array( '_fisar_course_start_date' => '2020-06-09', '_fisar_course_end_date' => '2020-06-23' ) );
	$open_end = $make( array( '_fisar_course_start_date' => '2024-04-08', '_fisar_course_archived' => '1' ) );
	$year_only = $make( array( '_fisar_course_archive_year' => '2023', '_fisar_course_archived' => '1', '_fisar_course_type' => 'introductory' ) );
	$unknown = $make( array( '_fisar_course_start_date' => $past, '_fisar_course_end_date' => '' ) );
	$forced = $make( array( '_fisar_course_start_date' => '2021-09-20', '_fisar_course_end_date' => $future, '_fisar_course_archived' => '1' ) );
	$draft = $make( array( '_fisar_course_archive_year' => '2025', '_fisar_course_archived' => '1' ), 'draft' );
	$fixture_ids = static fn( WP_Query $q ): array => array_values( array_intersect( wp_list_pluck( $q->posts, 'ID' ), $created ) );
	$check( array( $active ) === $fixture_ids( fisar_cdj_get_active_courses() ), 'Only dated active editions belong to active courses; explicit archive always takes precedence.' );
	$expected = array( $open_end, $year_only, $forced, $dated );
	$check( $expected === $fixture_ids( fisar_cdj_get_past_courses() ), 'Archive includes unknown end and year-only courses in start/year order, excluding draft and uncertain ongoing courses.' );
	$check( 1 === count( fisar_cdj_get_past_courses( 1 )->posts ), 'The existing limit still applies.' );
	foreach ( array( $dated, $open_end, $year_only, $forced ) as $id ) {
		$check( ! fisar_cdj_is_course_active( $id ) && 'concluded' === fisar_cdj_get_course_booking_status( $id ), 'Historical editions cannot accept bookings or display promotional offers.' );
	}
	$check( '' === get_post_meta( $open_end, '_fisar_course_end_date', true ) && '' === get_post_meta( $year_only, '_fisar_course_start_date', true ), 'Queries never fill in unknown dates.' );
	$GLOBALS['post'] = get_post( $year_only ); setup_postdata( $GLOBALS['post'] );
	ob_start(); get_template_part( 'template-parts/course-card' ); $card = ob_get_clean();
	$check( str_contains( $card, 'Edizione 2023' ) && ! str_contains( $card, 'datetime="2023-' ), 'Year-only cards show the year without a fabricated machine-readable day.' );
	ob_start(); Fisar_CDJ_Meta_Boxes::render_course_details( get_post( $open_end ) ); $editor = ob_get_clean();
	$check( ! preg_match( '/name="_fisar_course_(?:start|end)_date"[^>]*required/', $editor ), 'Incomplete historical dates can be edited and saved.' );
	$check( ! preg_match( '/name="_fisar_course_director"[^>]*required/', $editor ), 'An undocumented historical director does not prevent editing.' );
	wp_set_current_user( 1 );
	$_POST = array( 'fisar_cdj_meta_nonce' => wp_create_nonce( 'fisar_cdj_save_meta' ), '_fisar_course_archive_present' => '1', '_fisar_course_archived' => '1', '_fisar_course_archive_year' => '2024', '_fisar_course_type' => 'thematic' );
	Fisar_CDJ_Meta_Boxes::save_course( $year_only );
	$check( '1' === get_post_meta( $year_only, '_fisar_course_archived', true ) && '2024' === fisar_cdj_get_course_archive_year( $year_only ) && 'Corso tematico' === fisar_cdj_get_course_type_label( $year_only ), 'Native editor saves archive state, year and type.' );
	unset( $_POST['_fisar_course_archive_present'], $_POST['_fisar_course_archived'], $_POST['_fisar_course_archive_year'], $_POST['_fisar_course_type'] );
	Fisar_CDJ_Meta_Boxes::save_course( $year_only );
	$check( '1' === get_post_meta( $year_only, '_fisar_course_archived', true ), 'Older editor submissions preserve archive fields when omitted.' );
	$_POST['_fisar_course_archive_present'] = '1'; $_POST['_fisar_course_archive_year'] = '2024-01-01';
	Fisar_CDJ_Meta_Boxes::save_course( $year_only );
	$check( ! get_post_meta( $year_only, '_fisar_course_archived', true ) && '' === fisar_cdj_get_course_archive_year( $year_only ), 'Unchecking archive and rejecting malformed years works.' );
	WP_CLI::success( 'Course archive: incomplete dates, year-only display, state, booking closure, ordering, limits and native saves passed.' );
} finally {
	$_POST = $original_request; wp_set_current_user( $original_user );
	foreach ( $created as $id ) { wp_delete_post( $id, true ); }
	$GLOBALS['post'] = $original_post;
	wp_reset_postdata();
}
