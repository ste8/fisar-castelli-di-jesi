<?php
/** Local integration: courses and blog have the same unlisted contract as events. */
if ( ! defined( 'WP_CLI' ) || ! WP_CLI || 'local' !== wp_get_environment_type() ) {
	throw new RuntimeException( 'Run only in local WordPress with WP-CLI.' );
}
$created = array();
$original_user = get_current_user_id();
$original_request = $_POST;
$original_screen = $GLOBALS['current_screen'] ?? null;
$original_query = $GLOBALS['wp_query'];
$original_post = $GLOBALS['post'] ?? null;
$check = static function ( bool $condition, string $message ): void {
	if ( ! $condition ) { throw new RuntimeException( $message ); }
};
$make = static function ( string $type, array $values = array() ) use ( &$created ): int {
	$id = wp_insert_post( array_merge( array( 'post_type' => $type, 'post_status' => 'publish', 'post_title' => 'FisarContentVisibilityFixture ' . count( $created ), 'post_content' => '<p>Testo originale conservato.</p>' ), $values ), true );
	if ( is_wp_error( $id ) ) { throw new RuntimeException( $id->get_error_message() ); }
	$created[] = $id;
	return $id;
};
try {
	$_POST = array();
	$admin = get_users( array( 'role' => 'administrator', 'number' => 1 ) )[0]->ID;
	wp_set_current_user( $admin );
	foreach ( array( 'post', 'fisar_corso' ) as $type ) {
		$key = Fisar_CDJ_Content_Visibility::FIELDS[ $type ];
		$visible = $make( $type );
		$hidden = $make( $type, array( 'meta_input' => array( $key => true, '_fisar_course_archived' => true, '_fisar_course_archive_year' => '1002', '_fisar_course_city' => 'UnlistedFixtureCity' ), 'post_date' => '1002-01-01 12:00:00' ) );
		$draft = $make( $type, array( 'post_status' => 'draft', 'meta_input' => array( $key => true ) ) );
		$before = get_post( $hidden, ARRAY_A );
		$url = get_permalink( $hidden );
		wp_set_current_user( 0 );
		$direct = new WP_Query( array( 'post_type' => $type, 'p' => $hidden ) );
		$check( $direct->is_singular() && $direct->post->ID === $hidden, 'Anonymous direct detail remains available: ' . $type );
		$check( ! ( new WP_Query( array( 'post_type' => $type, 'p' => $draft ) ) )->have_posts(), 'Drafts remain restricted.' );
		foreach ( array( array(), array( 's' => 'FisarContentVisibilityFixture' ), array( 'feed' => 'rss2' ) ) as $extra ) {
			$query = new WP_Query( $extra + array( 'post_type' => $type, 'post__in' => array( $hidden, $visible ), 'posts_per_page' => 1 ) );
			$check( 1 === $query->found_posts && array( $visible ) === array_map( 'intval', wp_list_pluck( $query->posts, 'ID' ) ), 'Collections, feeds and search count only listed content.' );
		}
		$GLOBALS['wp_query'] = $direct;
		$robots = apply_filters( 'wp_robots', array( 'index' => true ) );
		$check( ! isset( $robots['index'] ) && $robots['noindex'], 'Direct details request noindex.' );
		$GLOBALS['wp_query'] = $original_query;
		$urls = array_column( ( new WP_Sitemaps_Posts() )->get_url_list( 1, $type ), 'loc' );
		$check( ! in_array( $url, $urls, true ) && in_array( get_permalink( $visible ), $urls, true ), 'Sitemap excludes only the unlisted content.' );
		$route = '/wp/v2/' . ( 'post' === $type ? 'posts' : $type );
		$request = new WP_REST_Request( 'GET', $route );
		$request->set_param( 'include', array( $hidden, $visible ) );
		$check( array( $visible ) === array_column( rest_do_request( $request )->get_data(), 'id' ), 'Anonymous REST lists exclude unlisted content.' );
		$check( 200 === rest_do_request( new WP_REST_Request( 'GET', $route . '/' . $hidden ) )->get_status(), 'Known REST detail remains accessible.' );
		$request->set_param( 'context', 'edit' );
		$check( rest_do_request( $request )->get_status() >= 400, 'Anonymous editor bypass is denied.' );
		wp_set_current_user( $admin );
		$check( in_array( $hidden, array_column( rest_do_request( $request )->get_data(), 'id' ), true ), 'Authenticated editor includes unlisted content.' );
		set_current_screen( $type ); get_current_screen()->is_block_editor( true );
		Fisar_CDJ_Meta_Boxes::add_meta_boxes();
		$check( isset( $GLOBALS['wp_meta_boxes'][ $type ]['normal']['high']['fisar-content-visibility'] ), 'Visibility belongs to the main metadata panel.' );
		ob_start(); Fisar_CDJ_Content_Visibility::render( get_post( $hidden ) ); $html = ob_get_clean();
		$check( str_contains( $html, 'name="' . $key . '"' ) && str_contains( $html, 'checked=' ) && str_contains( $html, 'fisar_corso' === $type ? 'aprire il corso' : 'aprire l’articolo' ), 'Editor uses the right field, state and wording.' );
		$GLOBALS['current_screen'] = $original_screen;
		$nonce = wp_create_nonce( 'fisar_cdj_save_event_visibility' );
		foreach ( array( array(), array( '_fisar_event_visibility_present' => '1', '_fisar_event_visibility_nonce' => 'bad' ), array( '_fisar_event_visibility_present' => '1', '_fisar_event_visibility_nonce' => $nonce, $key => array( '1' ) ) ) as $invalid ) {
			$_POST = $invalid; Fisar_CDJ_Content_Visibility::save( $hidden );
			$check( Fisar_CDJ_Content_Visibility::is_unlisted( $hidden ), 'Partial and malformed saves preserve visibility.' );
		}
		$_POST = array( '_fisar_event_visibility_present' => '1', '_fisar_event_visibility_nonce' => $nonce );
		wp_set_current_user( 0 ); Fisar_CDJ_Content_Visibility::save( $hidden );
		$check( Fisar_CDJ_Content_Visibility::is_unlisted( $hidden ), 'Anonymous writes cannot change visibility.' );
		wp_set_current_user( $admin ); Fisar_CDJ_Content_Visibility::save( $hidden );
		$check( ! Fisar_CDJ_Content_Visibility::is_unlisted( $hidden ) && get_permalink( $hidden ) === $url && get_post( $hidden, ARRAY_A ) === $before, 'Unchecking preserves URL, content, dates and status.' );
		$_POST[ $key ] = '1'; Fisar_CDJ_Content_Visibility::save( $hidden ); $_POST = array();
		$registered = get_registered_meta_keys( 'post', $type )[ $key ];
		$check( false === $registered['default'] && 'boolean' === $registered['type'] && false === $registered['show_in_rest'], 'Flags default to listed and are not public REST metadata.' );
		if ( 'post' === $type ) { $hidden_report = $hidden; $visible_report = $visible; }
		else { $hidden_course = $hidden; $visible_course = $visible; }
	}
	wp_set_current_user( 0 );
	$check( ! in_array( 1002, Fisar_CDJ_Archive_Filters::available_years( 'blog' ), true ), 'Blog years do not advertise unlisted-only years.' );
	$options = Fisar_CDJ_Course_Filters::options();
	$check( ! in_array( 1002, $options['years'], true ) && ! in_array( 'UnlistedFixtureCity', $options['cities'], true ), 'Course filters exclude hidden-only years and cities.' );
	$implicit_blog = new WP_Query( array( 'post__in' => array( $hidden_report, $visible_report ) ) );
	$check( array( $visible_report ) === array_map( 'intval', wp_list_pluck( $implicit_blog->posts, 'ID' ) ), 'Native default Blog query also excludes unlisted articles.' );
	$event = $make( 'fisar_evento', array( 'meta_input' => array( '_fisar_event_date' => '2026-12-04' ) ) );
	foreach ( array( $hidden_report, $visible_report ) as $id ) {
		update_post_meta( $id, Fisar_CDJ_Event_Reports::EVENT_META, $event );
		add_post_meta( $id, Fisar_CDJ_Event_Reports::COURSE_META, $hidden_course );
		add_post_meta( $id, Fisar_CDJ_Event_Reports::COURSE_META, $visible_course );
	}
	$check( array( $visible_report ) === array_column( Fisar_CDJ_Event_Reports::for_event( $event ), 'ID' ), 'Event banners do not reveal unlisted articles.' );
	$check( array( $visible_report ) === array_column( Fisar_CDJ_Event_Reports::for_course( $visible_course ), 'ID' ), 'Course stories do not reveal unlisted articles.' );
	$check( array( $visible_course ) === Fisar_CDJ_Event_Reports::courses_for_report( $visible_report ), 'Blog backlinks hide unlisted courses.' );
	$check( array( $hidden_course, $visible_course ) === Fisar_CDJ_Event_Reports::courses_for_report( $visible_report, true ), 'Editor retains all saved associations.' );
	$check( array( $hidden_course, $visible_course ) === array_map( 'intval', get_post_meta( $visible_report, Fisar_CDJ_Event_Reports::COURSE_META, false ) ), 'Public omission does not erase relationships.' );
	update_post_meta( $event, '_fisar_event_course_id', $hidden_course );
	$GLOBALS['wp_query'] = new WP_Query( array( 'post_type' => 'fisar_evento', 'p' => $event ) );
	ob_start(); include get_template_directory() . '/single-fisar_evento.php'; $event_html = ob_get_clean();
	$check( ! str_contains( $event_html, get_permalink( $hidden_course ) ), 'Event detail does not advertise an unlisted course.' );
	$GLOBALS['wp_query'] = $original_query;
	$navigation = array();
	foreach ( range( 1, 3 ) as $minute ) {
		$navigation[] = $make( 'post', array( 'post_date' => '1901-01-01 12:0' . $minute . ':00', 'meta_input' => array( '_fisar_post_unlisted' => 2 === $minute ) ) );
	}
	$GLOBALS['post'] = get_post( $navigation[0] );
	$check( $navigation[2] === get_next_post()->ID, 'Next article skips unlisted content.' );
	$GLOBALS['post'] = get_post( $navigation[2] );
	$check( $navigation[0] === get_previous_post()->ID, 'Previous article skips unlisted content.' );
	$order = Fisar_CDJ_Content_Visibility::box_order( array( 'normal' => 'other,fisar-content-visibility', 'side' => 'fisar-content-visibility,third-party' ) );
	$check( array( 'normal' => 'fisar-content-visibility,other', 'side' => 'third-party' ) === $order && $order === Fisar_CDJ_Content_Visibility::box_order( $order ), 'Saved placement puts visibility first without disturbing unrelated boxes.' );
	WP_CLI::success( 'Unlisted courses/blog: discovery, direct access, safe editor saves, REST, filters, reports, adjacent navigation and reversal passed.' );
} finally {
	$_POST = array(); $GLOBALS['current_screen'] = $original_screen; $GLOBALS['wp_query'] = $original_query; $GLOBALS['post'] = $original_post;
	foreach ( array_reverse( $created ) as $id ) { wp_delete_post( $id, true ); }
	wp_set_current_user( $original_user ); $_POST = $original_request;
}
