<?php
/** Local integration: combined filters, native categories, dates and paginated coverage. */
if ( ! defined( 'WP_CLI' ) || ! WP_CLI || 'local' !== wp_get_environment_type() ) {
	throw new RuntimeException( 'Run only in local WordPress with WP-CLI.' );
}
$created = array();
$created_terms = array();
$blog_ids = array();
$event_ids = array();
$original_query = $GLOBALS['wp_query'];
$original_main = $GLOBALS['wp_the_query'];
$original_post = $GLOBALS['post'] ?? null;
$original_uri = $_SERVER['REQUEST_URI'] ?? '';
$year = (int) substr( fisar_cdj_today(), 0, 4 ) - 1;
$check = static function ( bool $condition, string $message ): void {
	if ( ! $condition ) { throw new RuntimeException( $message ); }
};
$term = static function ( string $slug, int $parent = 0 ) use ( &$created_terms ): int {
	$existing = get_term_by( 'slug', $slug, 'category' );
	if ( $existing ) { return $existing->term_id; }
	$result = wp_insert_term( $slug, 'category', array( 'slug' => $slug, 'parent' => $parent ) );
	if ( is_wp_error( $result ) ) { throw new RuntimeException( $result->get_error_message() ); }
	$created_terms[] = $result['term_id'];
	return $result['term_id'];
};
$create = static function ( string $type, string $title, string $content, array $extra = array(), array $terms = array() ) use ( &$created, &$blog_ids, &$event_ids, $year ): int {
	$id = wp_insert_post( array_merge( array( 'post_type' => $type, 'post_status' => 'publish', 'post_title' => $title, 'post_content' => $content, 'post_date' => $year . '-03-15 12:00:00' ), $extra ), true );
	if ( is_wp_error( $id ) ) { throw new RuntimeException( $id->get_error_message() ); }
	$created[] = $id;
	if ( 'post' === $type ) { $blog_ids[] = $id; } else { $event_ids[] = $id; }
	if ( $terms ) { wp_set_post_categories( $id, $terms ); }
	return $id;
};
$query_blog = static function ( array $filters, int $page = 1, bool $main = true ) use ( &$blog_ids ): WP_Query {
	$query = new WP_Query();
	if ( $main ) { $GLOBALS['wp_query'] = $query; $GLOBALS['wp_the_query'] = $query; }
	$query->query( array_merge( array( 'post_type' => 'post', 'post__in' => $blog_ids, 'posts_per_page' => 12, 'paged' => $page, 'orderby' => 'ID', 'order' => 'ASC' ), $filters ) );
	return $query;
};
$isolate_events = static function ( WP_Query $query ) use ( &$event_ids ): void {
	if ( Fisar_CDJ_Post_Types::EVENT === $query->get( 'post_type' ) ) { $query->set( 'post__in', $event_ids ); }
};
try {
	$services = $term( 'servizi' );
	$memories = $term( 'ricordi' );
	$other = $term( 'fisar-test-other' );
	$child = $term( 'fisar-test-services-child', $services );
	$a = $create( 'post', 'Barrique nel titolo', 'Territorio e degustazione', array(), array( $services ) );
	$b = $create( 'post', 'Un ricordo', 'Barrique nel corpo', array(), array( $memories ) );
	$c = $create( 'post', 'Barrique altro mese', 'Territorio', array( 'post_date' => $year . '-04-15 12:00:00' ), array( $other ) );
	$create( 'post', 'Barrique altro anno', 'Territorio', array( 'post_date' => ( $year - 1 ) . '-03-15 12:00:00' ), array( $services, $other ) );
	$create( 'post', 'Solo estratto', 'Nessuna corrispondenza', array( 'post_excerpt' => 'Barrique' ), array( $memories ) );
	$uncategorized = $create( 'post', 'Nessuna categoria principale', 'Territorio' );
	$sub = $create( 'post', 'Barrique sottocategoria', 'Territorio', array(), array( $child ) );
	$combined = array( 'anno' => $year, 'mese' => 3, 'search' => 'Barrique' );
	$check( wp_list_pluck( $query_blog( $combined )->posts, 'ID' ) === array( $a, $b, $sub ), 'Combine publication year/month and title/body search; exclude excerpt-only matches.' );
	$check( wp_list_pluck( $query_blog( array( 'fisar_year' => $year, 'fisar_month' => 3, 'fisar_text' => 'Barrique', 'fisar_category' => 'servizi' ) )->posts, 'ID' ) === array( $a, $sub ), 'Keep old URLs working with the same combined filters.' );
	$check( $query_blog( array( 'search' => '', 'fisar_text' => 'unmatchedtestword' ) )->found_posts === count( $blog_ids ), 'An explicitly empty new parameter takes precedence over an old parameter.' );
	$registered = apply_filters( 'query_vars', array() );
	foreach ( array( 'search', 'anno', 'mese', 'categoria', 'per_pagina', 'fisar_text', 'fisar_year', 'fisar_month', 'fisar_category', 'fisar_per_page' ) as $name ) {
		$check( in_array( $name, $registered, true ), 'Register new public names and their backward-compatible aliases.' );
	}
	$check( wp_list_pluck( $query_blog( $combined + array( 'categoria' => 'servizi' ) )->posts, 'ID' ) === array( $a, $sub ), 'Use native categories, including their descendants.' );
	$check( wp_list_pluck( $query_blog( array( 'categoria' => 'altro' ) )->posts, 'ID' ) === array( $c, $uncategorized ), 'Other is the complement of all main categories, including uncategorized posts but excluding mixed/main/child categories.' );
	$check( $query_blog( array( 'search' => 'unmatchedtestword' ) )->found_posts === 0, 'A search without matches returns no results.' );
	$secondary = $query_blog( array( 'search' => 'unmatchedtestword' ), 1, false );
	$check( $secondary->found_posts === count( $blog_ids ), 'Home and other secondary queries are not filtered implicitly.' );
	$normalized = Fisar_CDJ_Archive_Filters::normalize( array( 'year' => array( 2025 ), 'month' => '13', 'text' => array( 'bad' ), 'category' => 'unknown' ) );
	$check( array( 'year' => 0, 'month' => 0, 'text' => '', 'category' => '', 'per_page' => 12 ) === $normalized, 'Ignore malformed values and unknown categories.' );
	$check( 12 === Fisar_CDJ_Archive_Filters::normalize( array( 'per_page' => 9999 ) )['per_page'], 'Reject unsupported page sizes rather than allowing unlimited archive requests.' );
	$check( ! Fisar_CDJ_Archive_Filters::has_criteria( array( 'per_page' => 24 ) ), 'Page size is a view preference, not a search criterion.' );
	$pages = array();
	for ( $n = 0; $n < 25; $n++ ) { $pages[] = $create( 'post', 'FiltroCompleto ' . $n, 'Territorio', array(), array( $services ) ); }
	$filters = array( 'anno' => $year, 'mese' => 3, 'search' => 'FiltroCompleto', 'categoria' => 'servizi' );
	$legacy = array( 'fisar_year' => $year, 'fisar_month' => 3, 'fisar_text' => 'FiltroCompleto', 'fisar_category' => 'servizi', 'fisar_per_page' => 24 );
	$_SERVER['REQUEST_URI'] = '/news/?' . http_build_query( $legacy );
	$old_query = $query_blog( $legacy );
	$check( 24 === $old_query->post_count && 25 === $old_query->found_posts, 'Old page-size URLs preserve their result counts.' );
	$html = get_the_posts_pagination( array( 'add_args' => Fisar_CDJ_Archive_Filters::url_args( Fisar_CDJ_Archive_Filters::current( 'blog' ) ) ) );
	$check( ! str_contains( $html, 'fisar_' ) && str_contains( $html, 'search=FiltroCompleto' ) && str_contains( $html, 'per_pagina=24' ), 'Pagination emitted from old URLs uses only the simplified names.' );
	$_SERVER['REQUEST_URI'] = '/news/?' . http_build_query( $filters );
	$seen = array();
	foreach ( array( 12, 12, 1 ) as $i => $count ) {
		$query = $query_blog( $filters, $i + 1 );
		$check( $count === count( $query->posts ) && 25 === $query->found_posts, 'Counts and pagination operate on the complete filtered archive.' );
		$seen = array_merge( $seen, wp_list_pluck( $query->posts, 'ID' ) );
		$html = get_the_posts_pagination( array( 'add_args' => Fisar_CDJ_Archive_Filters::url_args( Fisar_CDJ_Archive_Filters::current( 'blog' ) ) ) );
		preg_match_all( '/href="([^"]+)"/', $html, $links );
		foreach ( $links[1] as $href ) {
			parse_str( wp_parse_url( html_entity_decode( $href ), PHP_URL_QUERY ) ?? '', $parameters );
			foreach ( $filters as $key => $value ) { $check( (string) $value === ( $parameters[ $key ] ?? '' ), 'Keep every active filter in pagination URLs.' ); }
		}
	}
	$check( $pages === $seen, 'Do not lose or duplicate articles between filtered pages.' );
	foreach ( array( 24, 36, 48 ) as $size ) {
		$one = $query_blog( $filters + array( 'per_pagina' => $size ) );
		$check( min( $size, 25 ) === $one->post_count && 25 === $one->found_posts, 'Use the selected multiple of twelve without changing the total result count.' );
		$check( Fisar_CDJ_Archive_Filters::current( 'blog' )['per_page'] === $size, 'Keep the page-size selection in the current URL state.' );
		if ( 24 === $size ) {
			$seen = wp_list_pluck( $one->posts, 'ID' );
			$html = get_the_posts_pagination( array( 'add_args' => Fisar_CDJ_Archive_Filters::url_args( Fisar_CDJ_Archive_Filters::current( 'blog' ) ) ) );
			$check( str_contains( $html, 'per_pagina=24' ), 'Preserve page size in next-page links.' );
			$two = $query_blog( $filters + array( 'per_pagina' => 24 ), 2 );
			$check( array_merge( $seen, wp_list_pluck( $two->posts, 'ID' ) ) === $pages, 'Cover all filtered results with 24/1 pagination.' );
		}
	}
	add_action( 'pre_get_posts', $isolate_events );
	$event_pages = array();
	for ( $n = 0; $n < 13; $n++ ) {
		$id = $create( Fisar_CDJ_Post_Types::EVENT, 'Incontro ' . $n, 'FiltroEvento nel corpo', array( 'post_date' => ( $year - 1 ) . '-01-01 12:00:00' ) );
		update_post_meta( $id, '_fisar_event_date', $year . '-03-12' );
		$event_pages[] = $id;
	}
	$future = $create( Fisar_CDJ_Post_Types::EVENT, 'Futuro', 'FiltroEvento nel corpo' );
	update_post_meta( $future, '_fisar_event_date', '2087-03-12' );
	$event_filters = array( 'year' => $year, 'month' => 3, 'text' => 'FiltroEvento' );
	$one = fisar_cdj_get_past_events( 12, 1, $event_filters );
	$two = fisar_cdj_get_past_events( 12, 2, $event_filters );
	$check( array_merge( wp_list_pluck( $one->posts, 'ID' ), wp_list_pluck( $two->posts, 'ID' ) ) === $event_pages, 'Events filter by event date rather than publication date and retain chronological page coverage.' );
	$check( 1 === fisar_cdj_get_past_events( 12, 2, $event_filters )->post_count, 'Preserve the final event on page two.' );
	$check( 13 === fisar_cdj_get_past_events( -1, 1, array( 'month' => 3 ) )->post_count, 'Month-only filtering works across years.' );
	$check( array( $future ) === wp_list_pluck( fisar_cdj_get_upcoming_events( 12, 1, array( 'year' => 2087, 'month' => 3, 'text' => 'FiltroEvento' ) )->posts, 'ID' ), 'Upcoming events use the same explicit filters without mixing past events.' );
	$check( in_array( 2087, Fisar_CDJ_Archive_Filters::available_years( 'events' ), true ), 'Event year options come from event metadata.' );
	$check( ! in_array( 2087, Fisar_CDJ_Archive_Filters::available_years( 'events', true ), true ), 'Past-event year options exclude years containing only future events.' );
	$query_blog( array( 'anno' => $year, 'mese' => 3, 'search' => 'FiltroEvento', 'per_pagina' => 24 ) );
	ob_start();
	include get_template_directory() . '/archive-fisar_evento.php';
	$archive = ob_get_clean();
	$document = new DOMDocument();
	@$document->loadHTML( '<?xml encoding="utf-8" ?>' . $archive );
	$xpath = new DOMXPath( $document );
	$future_titles = $xpath->query( '//section[@aria-labelledby="future-events"]//h3' );
	$check( 1 === $future_titles->length && 'Futuro' === trim( $future_titles->item( 0 )->textContent ), 'Past filters never hide or filter upcoming events.' );
	$check( 0 === $xpath->query( '//section[@aria-labelledby="future-events"]//form' )->length && 1 === $xpath->query( '//section[@aria-labelledby="past-events"]//form' )->length, 'The only event filter form belongs to the past section.' );
	$check( 13 === $xpath->query( '//div[contains(@class,"event-grid--past")]//article' )->length, 'The event archive also uses the selected page size.' );
	$check( 1 === $xpath->query( '//div[@data-archive-summary]//p[@class="archive-results"]' )->length, 'Show the result count in the summary row below the controls.' );
	$check( 0 === $xpath->query( '//h2[@id="past-events"]//*[@class="archive-results"]' )->length, 'Do not repeat the count in the heading.' );
	$reset = $xpath->query( '//a[@data-archive-reset]' )->item( 0 )->getAttribute( 'href' );
	$check( str_contains( $reset, 'per_pagina=24' ) && ! str_contains( $reset, 'anno' ), 'Reset clears criteria while preserving the chosen page size.' );
	$check( str_contains( $archive, '#past-events' ), 'Filtering and clearing return to the past section.' );
	$query_blog( array() );
	ob_start();
	get_template_part( 'template-parts/archive-results', null, array( 'scope' => 'blog', 'count' => 32 ) );
	$check( str_contains( ob_get_clean(), '32 risultati' ), 'Show the total count even for the unfiltered archive.' );
	WP_CLI::success( 'Archive filters: combined dates/title/body/categories, Other complement, invalid inputs, counts, URL persistence, pagination and event dates passed.' );
} finally {
	remove_action( 'pre_get_posts', $isolate_events );
	foreach ( array_reverse( $created ) as $id ) { wp_delete_post( $id, true ); }
	foreach ( array_reverse( $created_terms ) as $id ) { wp_delete_term( $id, 'category' ); }
	$GLOBALS['wp_query'] = $original_query;
	$GLOBALS['wp_the_query'] = $original_main;
	$GLOBALS['post'] = $original_post;
	$_SERVER['REQUEST_URI'] = $original_uri;
}
