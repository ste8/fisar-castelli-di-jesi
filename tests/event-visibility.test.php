<?php
/** Local integration: public direct access without public discovery or editorial data loss. */
if ( ! defined( 'WP_CLI' ) || ! WP_CLI || 'local' !== wp_get_environment_type() ) {
	throw new RuntimeException( 'Run only in local WordPress with WP-CLI.' );
}
$created = array();
$original_user = get_current_user_id();
$original_request = $_POST;
$original_screen = $GLOBALS['current_screen'] ?? null;
$original_query = $GLOBALS['wp_query'];
$keep = false;
$check = static function ( bool $condition, string $message ): void {
	if ( ! $condition ) { throw new RuntimeException( $message ); }
};
$make = static function ( string $type, string $title, array $meta = array(), string $status = 'publish' ) use ( &$created ): int {
	$id = wp_insert_post( array( 'post_type' => $type, 'post_status' => $status, 'post_title' => 'FisarUnlistedFixture ' . $title, 'post_content' => '<p>Testo originale conservato.</p>', 'meta_input' => $meta ), true );
	if ( is_wp_error( $id ) ) { throw new RuntimeException( $id->get_error_message() ); }
	$created[] = $id;
	return $id;
};
$ids = static fn( WP_Query $query ): array => array_map( 'intval', wp_list_pluck( $query->posts, 'ID' ) );
try {
	$_POST = array();
	$admins = get_users( array( 'role' => 'administrator', 'number' => 1 ) );
	wp_set_current_user( $admins[0]->ID );
	$future = current_datetime()->modify( '+30 days' )->format( 'Y-m-d' );
	$past = current_datetime()->modify( '-30 days' )->format( 'Y-m-d' );
	$course = $make( 'fisar_corso', 'Corso' );
	$visible = $make( 'fisar_evento', 'Evento visibile', array( '_fisar_event_date' => $future, '_fisar_event_course_id' => $course ) );
	$hidden = $make( 'fisar_evento', 'Evento non elencato', array( '_fisar_event_date' => '2095-02-03', '_fisar_event_course_id' => $course, '_fisar_event_unlisted' => true ) );
	$old_hidden = $make( 'fisar_evento', 'Evento storico non elencato', array( '_fisar_event_date' => '1001-02-03', '_fisar_event_unlisted' => true ) );
	$old_visible = $make( 'fisar_evento', 'Evento storico visibile', array( '_fisar_event_date' => $past ) );
	$draft = $make( 'fisar_evento', 'Bozza non elencata', array( '_fisar_event_unlisted' => true ), 'draft' );
	$article = $make( 'post', 'Racconto' );
	update_post_meta( $article, Fisar_CDJ_Event_Reports::EVENT_META, $hidden );
	$url = get_permalink( $hidden );
	$post_before = get_post( $hidden, ARRAY_A );
	$meta_before = get_post_meta( $hidden );
	$check( ! Fisar_CDJ_Content_Visibility::is_unlisted( $visible ) && Fisar_CDJ_Content_Visibility::is_unlisted( $hidden ), 'Existing events are listed by default; only an explicit flag hides one.' );
	$check( 'publish' === get_post_status( $hidden ) && is_post_publicly_viewable( $hidden ), 'Unlisted is independent of published status.' );
	wp_set_current_user( 0 );
	$direct = new WP_Query( array( 'post_type' => 'fisar_evento', 'name' => get_post_field( 'post_name', $hidden ) ) );
	$check( $direct->is_singular() && array( $hidden ) === $ids( $direct ), 'The direct event URL resolves for anonymous visitors.' );
	$check( array() === $ids( new WP_Query( array( 'post_type' => 'fisar_evento', 'p' => $draft ) ) ), 'An unlisted draft is still not publicly accessible.' );
	$upcoming = fisar_cdj_get_upcoming_events( -1 );
	$check( in_array( $visible, $ids( $upcoming ), true ) && ! in_array( $hidden, $ids( $upcoming ), true ), 'Home and upcoming archive exclude unlisted events.' );
	$past_query = fisar_cdj_get_past_events( -1 );
	$check( in_array( $old_visible, $ids( $past_query ), true ) && ! in_array( $old_hidden, $ids( $past_query ), true ), 'Past events also exclude unlisted entries.' );
	$check( array( $visible ) === $ids( fisar_cdj_get_course_events( $course ) ), 'The automatic course event list hides the event.' );
	$check( ! in_array( 1001, Fisar_CDJ_Event_Calendar::years( true ), true ) && ! in_array( 2095, Fisar_CDJ_Event_Calendar::years( false ), true ), 'Year filters do not reveal dates used only by unlisted events.' );
	$search = new WP_Query( array( 'post_type' => 'any', 's' => 'FisarUnlistedFixture', 'posts_per_page' => -1 ) );
	$check( ! in_array( $hidden, $ids( $search ), true ) && ! in_array( $old_hidden, $ids( $search ), true ) && in_array( $article, $ids( $search ), true ) && in_array( $course, $ids( $search ), true ), 'Mixed search excludes unlisted events without changing other content types.' );
	$collection = array( 'post_type' => 'fisar_evento', 'post__in' => array( $hidden, $visible, $old_hidden, $old_visible ), 'orderby' => 'ID', 'order' => 'ASC', 'posts_per_page' => 1 );
	$page_one = new WP_Query( $collection );
	$page_two = new WP_Query( $collection + array( 'paged' => 2 ) );
	$check( 2 === $page_one->found_posts && 2 === $page_one->max_num_pages && array( $visible ) === $ids( $page_one ) && array( $old_visible ) === $ids( $page_two ), 'Exclusion happens before counting and pagination, without holes.' );
	$feed = new WP_Query( array( 'post_type' => 'fisar_evento', 'feed' => 'rss2', 'post__in' => array( $hidden, $visible ) ) );
	$check( array( $visible ) === $ids( $feed ), 'Public event feeds do not advertise unlisted events.' );
	$check( 0 === Fisar_CDJ_Event_Reports::event_for_report( $article ) && $hidden === (int) get_post_meta( $article, Fisar_CDJ_Event_Reports::EVENT_META, true ), 'The public blog backlink disappears while the saved association is preserved.' );
	$check( array( $article ) === array_column( Fisar_CDJ_Event_Reports::for_event( $hidden ), 'ID' ), 'The direct event page can still show its published reports.' );
	$GLOBALS['wp_query'] = $direct;
	$robots = apply_filters( 'wp_robots', array( 'index' => true, 'follow' => true ) );
	$check( ! isset( $robots['index'] ) && true === $robots['noindex'], 'The unlisted detail explicitly requests noindex, even if the site is indexable.' );
	$GLOBALS['wp_query'] = $original_query;
	$sitemap = new WP_Sitemaps_Posts();
	$sitemap_urls = array_column( $sitemap->get_url_list( 1, 'fisar_evento' ), 'loc' );
	$check( ! in_array( $url, $sitemap_urls, true ) && in_array( get_permalink( $visible ), $sitemap_urls, true ), 'Native sitemap discovery excludes the unlisted URL.' );
	$rest_list = new WP_REST_Request( 'GET', '/wp/v2/fisar_evento' );
	$rest_list->set_param( 'include', array( $hidden, $visible ) );
	$rest_result = rest_do_request( $rest_list );
	$check( 200 === $rest_result->get_status() && array( $visible ) === array_column( $rest_result->get_data(), 'id' ), 'Anonymous REST collection also hides unlisted events.' );
	$rest_direct = rest_do_request( new WP_REST_Request( 'GET', '/wp/v2/fisar_evento/' . $hidden ) );
	$check( 200 === $rest_direct->get_status() && $hidden === $rest_direct->get_data()['id'], 'A known event remains accessible directly through REST.' );
	$rest_search = new WP_REST_Request( 'GET', '/wp/v2/search' );
	$rest_search->set_param( 'search', 'FisarUnlistedFixture' );
	$rest_search->set_param( 'subtype', 'fisar_evento' );
	$rest_search->set_param( 'per_page', 100 );
	$search_result = rest_do_request( $rest_search );
	$check( 200 === $search_result->get_status() && ! in_array( $hidden, array_column( $search_result->get_data(), 'id' ), true ) && in_array( $visible, array_column( $search_result->get_data(), 'id' ), true ), 'REST search does not expose the hidden event but still finds listed events.' );
	$rest_list->set_param( 'context', 'edit' );
	$check( in_array( rest_do_request( $rest_list )->get_status(), array( 401, 403 ), true ), 'Anonymous visitors cannot use the editor context to bypass exclusion.' );
	$check( ! in_array( 'fisar_event_visibility_editor', apply_filters( 'query_vars', array() ), true ), 'The editor bypass is not a public query parameter.' );
	wp_set_current_user( $admins[0]->ID );
	$rest_list->set_param( 'context', 'edit' );
	$editor_result = rest_do_request( $rest_list );
	$check( in_array( $hidden, array_column( $editor_result->get_data(), 'id' ), true ), 'Authorized editor collections retain unlisted events.' );
	set_current_screen( 'edit-fisar_evento' );
	$admin_query = new WP_Query( $collection + array( 'nopaging' => true ) );
	$check( in_array( $hidden, $ids( $admin_query ), true ), 'The WordPress admin list still shows the event.' );
	$check( 'Non elencato' === apply_filters( 'display_post_states', array(), get_post( $hidden ) )['fisar_unlisted'], 'The admin list labels the event clearly.' );
	$GLOBALS['current_screen'] = $original_screen;
	ob_start(); Fisar_CDJ_Content_Visibility::render( get_post( $hidden ) ); $editor_html = ob_get_clean();
	$check( str_contains( $editor_html, 'Non elencato — accessibile tramite link' ) && str_contains( $editor_html, 'checked=' ), 'The saved checkbox is visible and checked in the editor.' );
	$nonce = wp_create_nonce( 'fisar_cdj_save_event_visibility' );
	foreach ( array( array(), array( '_fisar_event_visibility_present' => '1', '_fisar_event_visibility_nonce' => 'invalid' ), array( '_fisar_event_visibility_present' => '1', '_fisar_event_visibility_nonce' => $nonce, '_fisar_event_unlisted' => array( '1' ) ) ) as $invalid ) {
		$_POST = $invalid; Fisar_CDJ_Content_Visibility::save( $hidden );
		$check( Fisar_CDJ_Content_Visibility::is_unlisted( $hidden ), 'Omitted controls, malformed input and invalid nonces never make the event listed.' );
	}
	$_POST = array( '_fisar_event_visibility_present' => '1', '_fisar_event_visibility_nonce' => $nonce );
	wp_set_current_user( 0 ); Fisar_CDJ_Content_Visibility::save( $hidden );
	$check( Fisar_CDJ_Content_Visibility::is_unlisted( $hidden ), 'Anonymous saves cannot change event visibility.' );
	wp_set_current_user( $admins[0]->ID ); Fisar_CDJ_Content_Visibility::save( $hidden );
	$check( ! Fisar_CDJ_Content_Visibility::is_unlisted( $hidden ) && in_array( $hidden, $ids( fisar_cdj_get_upcoming_events( -1 ) ), true ), 'Unchecking restores normal listings immediately.' );
	$check( $url === get_permalink( $hidden ) && $post_before === get_post( $hidden, ARRAY_A ) && $hidden === Fisar_CDJ_Event_Reports::event_for_report( $article ), 'Reversal preserves URL, content and the report relation.' );
	$_POST[ Fisar_CDJ_Content_Visibility::META ] = '1'; Fisar_CDJ_Content_Visibility::save( $hidden );
	$check( $meta_before === get_post_meta( $hidden ), 'Checking again restores the original metadata without modifying practical event data.' );
	$registered = get_registered_meta_keys( 'post', 'fisar_evento' )[ Fisar_CDJ_Content_Visibility::META ];
	$check( 'boolean' === $registered['type'] && false === $registered['default'] && false === $registered['show_in_rest'], 'The flag is typed, false by default and not public REST metadata.' );
	WP_CLI::success( 'Unlisted events: anonymous direct access, public discovery, pagination, REST/admin, robots, safe saves and reversal passed.' );
	$keep = 'fixtures' === ( $args[0] ?? '' );
	if ( $keep ) { WP_CLI::line( wp_json_encode( array( 'ids' => $created, 'event' => $hidden, 'url' => $url, 'visible_url' => get_permalink( $visible ) ) ) ); }
} finally {
	$_POST = array(); $GLOBALS['current_screen'] = $original_screen; $GLOBALS['wp_query'] = $original_query;
	if ( ! $keep ) { foreach ( array_reverse( $created ) as $id ) { wp_delete_post( $id, true ); } }
	wp_set_current_user( $original_user ); $_POST = $original_request;
}
