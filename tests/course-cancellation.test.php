<?php
/** Local integration: reversible cancellation, public lists and neutral archive labels. */
if ( ! defined( 'WP_CLI' ) || ! WP_CLI || 'local' !== wp_get_environment_type() ) {
	throw new RuntimeException( 'Run only in local WordPress with WP-CLI.' );
}
$created = array();
$original_post = $GLOBALS['post'] ?? null;
$original_screen = $GLOBALS['current_screen'] ?? null;
$original_request = $_POST;
$original_user = get_current_user_id();
$check = static function ( bool $condition, string $message ): void {
	if ( ! $condition ) { throw new RuntimeException( $message ); }
};
$make = static function ( array $meta, string $type = 'fisar_corso' ) use ( &$created ): int {
	$id = wp_insert_post( array( 'post_type' => $type, 'post_status' => 'publish', 'post_title' => 'FisarCancellationFixture', 'post_content' => 'Testo originale conservato', 'meta_input' => $meta ), true );
	if ( is_wp_error( $id ) ) { throw new RuntimeException( $id->get_error_message() ); }
	$created[] = $id;
	return $id;
};
$ids = static fn( WP_Query $q ): array => wp_list_pluck( $q->posts, 'ID' );
$in_fixtures = static function ( WP_Query $q ) use ( &$created ): array {
	return array_values( array_intersect( wp_list_pluck( $q->posts, 'ID' ), $created ) );
};
try {
	wp_set_current_user( 1 );
	$past_meta = array( '_fisar_course_archived' => '1', '_fisar_course_archive_year' => '2021', '_fisar_course_city' => 'Jesi' );
	$visible = array();
	for ( $i = 0; $i < 13; $i++ ) { $visible[] = $make( $past_meta ); }
	$cancelled = $make( array( '_fisar_course_archived' => '1', '_fisar_course_archive_year' => '1998', '_fisar_course_city' => 'CancelledOnlyCity', '_fisar_course_cancelled' => '1' ) );
	$cancelled_future = $make( array( '_fisar_course_start_date' => '2087-01-01', '_fisar_course_end_date' => '2087-12-31', '_fisar_course_cancelled' => '1', '_fisar_course_offer_enabled' => '1', '_fisar_course_form_url' => 'https://example.test/signup' ) );
	$ordinary_future = $make( array( '_fisar_course_start_date' => '2087-01-01', '_fisar_course_end_date' => '2087-12-31' ) );
	$article = $make( array( '_fisar_course_cancelled' => '1' ), 'post' );
	$filters = array( 'text' => 'FisarCancellationFixture' );
	$first = fisar_cdj_get_past_courses( 12, 1, $filters );
	$second = fisar_cdj_get_past_courses( 12, 2, $filters );
	$check( 13 === $first->found_posts && 12 === $first->post_count && 1 === $second->post_count, 'Cancellation is excluded before counting and pagination.' );
	$check( array_reverse( $visible ) === array_merge( $ids( $first ), $ids( $second ) ), 'Archive pages contain every visible course exactly once.' );
	$check( array( $ordinary_future ) === $in_fixtures( fisar_cdj_get_active_courses() ), 'Cancelled future courses never appear among active courses.' );
	$check( ! fisar_cdj_is_course_active( $cancelled_future ) && 'cancelled' === fisar_cdj_get_course_booking_status( $cancelled_future ), 'Cancellation takes precedence over future dates.' );
	$check( fisar_cdj_get_course_registration_details( $cancelled_future )['closed'] && ! fisar_cdj_get_course_offer( $cancelled_future )['active'] && array() === fisar_cdj_get_registration_channels( $cancelled_future, 'course' ), 'Cancellation closes bookings and hides promotions and contact channels without clearing metadata.' );
	$check( 'https://example.test/signup' === get_post_meta( $cancelled_future, '_fisar_course_form_url', true ), 'Stored registration data remains intact.' );
	$options = Fisar_CDJ_Course_Filters::options();
	$check( ! in_array( 1998, $options['years'], true ) && ! in_array( 'CancelledOnlyCity', $options['cities'], true ), 'Filter options do not reveal cancelled-only years or cities.' );
	$search = new WP_Query( array( 'post_type' => 'any', 's' => 'FisarCancellationFixture', 'posts_per_page' => -1 ) );
	$check( ! in_array( $cancelled, $ids( $search ), true ) && ! in_array( $cancelled_future, $ids( $search ), true ) && in_array( $article, $ids( $search ), true ), 'Mixed public search excludes cancelled courses without hiding other post types.' );
	$direct = new WP_Query( array( 'post_type' => 'fisar_corso', 'p' => $cancelled ) );
	$check( array( $cancelled ) === $ids( $direct ), 'Excluding lists does not silently change publication status or direct access.' );
	set_current_screen( 'edit-fisar_corso' );
	$admin = new WP_Query( array( 'post_type' => 'fisar_corso', 's' => 'FisarCancellationFixture', 'posts_per_page' => -1 ) );
	$check( in_array( $cancelled, $ids( $admin ), true ), 'The administration still lists cancelled courses.' );
	$check( ! in_array( $cancelled, $ids( fisar_cdj_get_past_courses( -1, 1, $filters ) ), true ), 'Explicit public APIs keep excluding cancellations on admin screens.' );
	$states = apply_filters( 'display_post_states', array(), get_post( $cancelled ) );
	$check( 'Corso annullato' === $states['fisar_course_cancelled'], 'The admin list identifies cancelled courses.' );
	$GLOBALS['current_screen'] = $original_screen;
	$calendar = array( array( 'number' => '1', 'date' => '2026-02-17', 'time' => '20:30', 'title' => 'Le funzioni del sommelier', 'speaker' => '', 'notes' => '' ) );
	$editable = $make( $past_meta + array( '_fisar_course_calendar' => $calendar, '_fisar_course_fee' => 'Quota originale' ) );
	ob_start(); Fisar_CDJ_Meta_Boxes::render_course_details( get_post( $editable ) ); $editor = ob_get_clean();
	$check( str_contains( $editor, 'Corso annullato' ) && str_contains( $editor, '_fisar_course_cancellation_present' ), 'The editor exposes an independently marked cancellation checkbox.' );
	$_POST = array( 'fisar_cdj_meta_nonce' => 'invalid', '_fisar_course_cancellation_present' => '1', '_fisar_course_cancelled' => '1' );
	Fisar_CDJ_Meta_Boxes::save_course( $editable );
	$check( ! fisar_cdj_is_course_cancelled( $editable ), 'Invalid nonce cannot cancel a course.' );
	$_POST = array( 'fisar_cdj_meta_nonce' => wp_create_nonce( 'fisar_cdj_save_meta' ), '_fisar_course_cancellation_present' => '1', '_fisar_course_cancelled' => '1', 'fisar_course_calendar' => $calendar, '_fisar_course_fee' => 'Quota originale' );
	wp_set_current_user( 0 ); Fisar_CDJ_Meta_Boxes::save_course( $editable );
	$check( ! fisar_cdj_is_course_cancelled( $editable ), 'An unauthorised user cannot cancel a course.' );
	wp_set_current_user( 1 ); Fisar_CDJ_Meta_Boxes::save_course( $editable );
	$check( fisar_cdj_is_course_cancelled( $editable ), 'An authorised editor can cancel the course.' );
	$check( $calendar == get_post_meta( $editable, '_fisar_course_calendar', true ), 'Calendar content survives cancellation, regardless of normalized field ordering.' );
	$check( 'Quota originale' === get_post_meta( $editable, '_fisar_course_fee', true ) && 'Testo originale conservato' === get_post( $editable )->post_content, 'Fees and original content survive cancellation.' );
	unset( $_POST['_fisar_course_cancellation_present'], $_POST['_fisar_course_cancelled'] );
	$_POST['_fisar_course_archive_present'] = '1'; $_POST['_fisar_course_archived'] = '1';
	Fisar_CDJ_Meta_Boxes::save_course( $editable );
	$check( fisar_cdj_is_course_cancelled( $editable ), 'Older editors that know archive fields but not cancellation must preserve it.' );
	$_POST['_fisar_course_cancellation_present'] = '1';
	Fisar_CDJ_Meta_Boxes::save_course( $editable );
	$check( ! fisar_cdj_is_course_cancelled( $editable ) && in_array( $editable, $ids( fisar_cdj_get_past_courses( -1, 1, $filters ) ), true ), 'Unchecking restores the course to public lists.' );
	$meta = get_registered_meta_keys( 'post', 'fisar_corso' );
	$check( 'boolean' === $meta['_fisar_course_cancelled']['type'] && false === $meta['_fisar_course_cancelled']['show_in_rest'], 'Cancellation is typed and not exposed in public REST metadata.' );
	$GLOBALS['post'] = get_post( $editable ); setup_postdata( $GLOBALS['post'] );
	ob_start(); get_template_part( 'template-parts/course-card' ); $card = ob_get_clean();
	$check( str_contains( $card, '>In archivio</span>' ) && ! str_contains( $card, '>Concluso</span>' ), 'Historical cards use neutral archive wording.' );
	WP_CLI::success( 'Course cancellation: protected saves, omitted fields, reversal, preserved data, admin visibility, public search/filters/pagination and neutral wording passed.' );
} finally {
	$_POST = $original_request;
	wp_set_current_user( $original_user );
	$GLOBALS['current_screen'] = $original_screen;
	foreach ( array_reverse( $created ) as $id ) { wp_delete_post( $id, true ); }
	$GLOBALS['post'] = $original_post;
	wp_reset_postdata();
}
