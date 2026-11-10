<?php
/** Local integration: historical edition year, type/city/text, pagination and template isolation. */
if ( ! defined( 'WP_CLI' ) || ! WP_CLI || 'local' !== wp_get_environment_type() ) {
	throw new RuntimeException( 'Run only in local WordPress with WP-CLI.' );
}
$created = array();
$original_query = $GLOBALS['wp_query'];
$original_main = $GLOBALS['wp_the_query'];
$original_post = $GLOBALS['post'] ?? null;
$original_uri = $_SERVER['REQUEST_URI'] ?? '';
$check = static function ( bool $condition, string $message ): void {
	if ( ! $condition ) { throw new RuntimeException( $message ); }
};
$make = static function ( string $title, array $meta, array $extra = array() ) use ( &$created ): int {
	$id = wp_insert_post( array_merge( array( 'post_type' => 'fisar_corso', 'post_status' => 'publish', 'post_title' => $title, 'post_content' => 'Corpo documentato', 'post_excerpt' => 'Solonellestratto', 'post_date' => '2026-01-01 12:00:00', 'meta_input' => $meta ), $extra ), true );
	if ( is_wp_error( $id ) ) { throw new RuntimeException( $id->get_error_message() ); }
	$created[] = $id;
	return $id;
};
$ids = static fn( WP_Query $q ): array => wp_list_pluck( $q->posts, 'ID' );
$isolate = static function ( WP_Query $q ) use ( &$created ): void {
	if ( 'fisar_corso' === $q->get( 'post_type' ) ) { $q->set( 'post__in', $created ); }
};
try {
	add_action( 'pre_get_posts', $isolate );
	$meta = array( '_fisar_course_archived' => '1', '_fisar_course_city' => 'Città d’Arco', '_fisar_course_venue' => 'Sala del Glicine', '_fisar_course_address' => 'Via dei Tigli 12', '_fisar_course_archive_year' => '2022' );
	$a = $make( 'Degustazione nel titolo', $meta + array( '_fisar_course_start_date' => '2023-02-14' ) );
	$b = $make( 'Minicorso', $meta + array( '_fisar_course_type' => 'introductory' ) );
	$c = $make( 'Corso tematico', array_merge( $meta, array( '_fisar_course_city' => 'Jesi', '_fisar_course_venue' => '', '_fisar_course_address' => '', '_fisar_course_type' => 'thematic', '_fisar_course_start_date' => '2024-02-14' ) ), array( 'post_content' => 'Unico nel corpo' ) );
	$active = $make( 'Corso attivo', array( '_fisar_course_start_date' => '2087-01-01', '_fisar_course_end_date' => '2087-12-31', '_fisar_course_city' => 'Solofutura' ) );
	$draft = $make( 'Bozza', $meta, array( 'post_status' => 'draft' ) );
	$check( array( $a ) === $ids( fisar_cdj_get_past_courses( -1, 1, array( 'year' => 2023 ) ) ), 'Year follows start date before archive year and ignores publication date.' );
	$check( array( $b ) === $ids( fisar_cdj_get_past_courses( -1, 1, array( 'year' => 2022 ) ) ), 'Year-only editions remain searchable, without inventing a date.' );
	foreach ( array( 'Degustazione', 'Unico', 'Glicine', 'Tigli', 'Città d’Arco' ) as $text ) {
		$expected = match ( $text ) { 'Degustazione' => array( $a ), 'Unico' => array( $c ), default => array( $a, $b ) };
		$check( $expected === $ids( fisar_cdj_get_past_courses( -1, 1, array( 'text' => $text ) ) ), 'Search finds title, body, venue, address and city: ' . $text );
	}
	$check( array( $a ) === $ids( fisar_cdj_get_past_courses( -1, 1, array( 'text' => 'Degustazione Glicine' ) ) ), 'Terms can match across title and metadata, with AND semantics.' );
	$check( array( $c ) === $ids( fisar_cdj_get_past_courses( -1, 1, array( 'text' => '-Glicine' ) ) ), 'WordPress excluded terms also apply to location metadata.' );
	$check( 0 === fisar_cdj_get_past_courses( -1, 1, array( 'text' => 'Solonellestratto' ) )->post_count, 'Excerpt-only matches are excluded.' );
	$check( 0 === fisar_cdj_get_past_courses( -1, 1, array( 'text' => '%' ) )->post_count, 'LIKE wildcards remain literal user text.' );
	$check( array( $b ) === $ids( fisar_cdj_get_past_courses( 12, 1, array( 'year' => 2022, 'city' => 'Città d’Arco', 'type' => 'introductory', 'text' => 'Glicine' ) ) ), 'All four criteria combine without duplicate rows.' );
	$check( array( $a ) === $ids( fisar_cdj_get_past_courses( -1, 1, array( 'type' => 'sommelier' ) ) ), 'Existing courses without type metadata retain the Sommelier default.' );
	$check( array( $active ) === $ids( fisar_cdj_get_active_courses() ), 'Active courses are not filtered or mixed into the archive.' );
	$clean = Fisar_CDJ_Course_Filters::normalize( array( 'year' => array( 2022 ), 'month' => 2, 'type' => array( 'thematic' ), 'city' => array( 'Jesi' ), 'per_page' => 100000, 'text' => array() ) );
	$check( 0 === $clean['year'] && 0 === $clean['month'] && '' === $clean['type'] && '' === $clean['city'] && 12 === $clean['per_page'], 'Malformed inputs and irrelevant month are ignored; page size remains bounded.' );
	$check( 0 === fisar_cdj_get_past_courses( -1, 1, array( 'city' => "Jesi' OR 1=1 --" ) )->found_posts, 'City values are SQL parameters, not expressions.' );
	$pages = array();
	for ( $i = 0; $i < 25; $i++ ) { $pages[] = $make( 'CoperturaPagine', array_merge( $meta, array( '_fisar_course_archive_year' => '2020' ) ) ); }
	$filters = array( 'year' => 2020, 'type' => 'sommelier', 'city' => 'Città d’Arco', 'text' => 'CoperturaPagine' );
	$seen = array();
	foreach ( array( 12, 12, 1 ) as $page => $count ) {
		$q = fisar_cdj_get_past_courses( 12, $page + 1, $filters );
		$check( $q->post_count === $count && 25 === $q->found_posts, 'Filtered count and pagination cover the whole archive.' );
		$seen = array_merge( $seen, $ids( $q ) );
	}
	$check( array_reverse( $pages ) === $seen, 'Stable archive order has no missing or repeated courses between pages.' );
	$check( 24 === fisar_cdj_get_past_courses( 24, 1, $filters )->post_count && 1 === fisar_cdj_get_past_courses( 24, 2, $filters )->post_count, 'Page size changes preserve full coverage.' );
	$options = Fisar_CDJ_Course_Filters::options();
	$check( array( 2024, 2023, 2022, 2020 ) === $options['years'] && array( 'Città d’Arco', 'Jesi' ) === $options['cities'], 'Menu options come only from published concluded courses.' );
	$query = new WP_Query(); $GLOBALS['wp_query'] = $query; $GLOBALS['wp_the_query'] = $query;
	$query->query( array( 'post_type' => 'fisar_corso', 'anno' => 2020, 'tipologia' => 'sommelier', 'citta' => 'Città d’Arco', 'search' => 'CoperturaPagine', 'per_pagina' => 24 ) );
	$_SERVER['REQUEST_URI'] = '/corsi/?' . http_build_query( Fisar_CDJ_Archive_Filters::url_args( $filters ) );
	ob_start(); include get_template_directory() . '/archive-fisar_corso.php'; $html = ob_get_clean();
	$doc = new DOMDocument(); @$doc->loadHTML( '<?xml encoding="utf-8" ?>' . $html ); $xpath = new DOMXPath( $doc );
	$check( 0 === $xpath->query( '//section[@aria-labelledby="active-courses"]//form' )->length && 1 === $xpath->query( '//section[@aria-labelledby="past-courses"]//form' )->length, 'Filters belong only to concluded courses.' );
	$check( 0 === $xpath->query( '//select[@name="mese"]' )->length && 1 === $xpath->query( '//select[@name="tipologia"]' )->length && 1 === $xpath->query( '//select[@name="citta"]' )->length, 'Course form has type and city instead of month.' );
	$check( 24 === $xpath->query( '//div[@data-archive-results]//article' )->length, 'Template uses the selected page size.' );
	$check( 1 === $xpath->query( '//section[@aria-labelledby="active-courses"]//article' )->length, 'Active course is still rendered with criteria selected.' );
	$reset = html_entity_decode( $xpath->query( '//a[@data-archive-reset]' )->item( 0 )->getAttribute( 'href' ) );
	$check( str_contains( $reset, 'per_pagina=24' ) && ! str_contains( $reset, 'citta=' ) && str_ends_with( $reset, '#past-courses' ), 'Reset preserves page size and clears all search criteria.' );
	$link = $xpath->query( '//a[contains(@class,"next")]' )->item( 0 )->getAttribute( 'href' );
	parse_str( wp_parse_url( html_entity_decode( $link ), PHP_URL_QUERY ), $params );
	$check( '2' === $params['corsi_conclusi'] && 'sommelier' === $params['tipologia'] && 'Città d’Arco' === $params['citta'] && '24' === $params['per_pagina'], 'Pagination retains type, city and page size.' );
	$check( ! in_array( $draft, $seen, true ) && ! in_array( $active, $seen, true ), 'Drafts and active editions remain excluded.' );
	WP_CLI::success( 'Course filters: year, type, city/location text, combinations, malformed input, pagination, options and active-section isolation passed.' );
} finally {
	remove_action( 'pre_get_posts', $isolate );
	foreach ( array_reverse( $created ) as $id ) { wp_delete_post( $id, true ); }
	$GLOBALS['wp_query'] = $original_query; $GLOBALS['wp_the_query'] = $original_main; $GLOBALS['post'] = $original_post;
	$_SERVER['REQUEST_URI'] = $original_uri;
}
