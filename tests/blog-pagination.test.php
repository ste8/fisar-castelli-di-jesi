<?php
/** Local integration: page boundaries must not lose articles or affect other queries. */
if ( ! defined( 'WP_CLI' ) || ! WP_CLI || 'local' !== wp_get_environment_type() ) {
	throw new RuntimeException( 'Run this test only in local WordPress with WP-CLI.' );
}

$created = array();
$original_query = $GLOBALS['wp_query'];
$original_main_query = $GLOBALS['wp_the_query'];
$original_uri = $_SERVER['REQUEST_URI'] ?? '';
$check = static function ( bool $condition, string $message ): void {
	if ( ! $condition ) {
		throw new RuntimeException( $message );
	}
};
$query = static function ( array $arguments, bool $main = true ): WP_Query {
	$query = new WP_Query();
	if ( $main ) {
		$GLOBALS['wp_query'] = $query;
		$GLOBALS['wp_the_query'] = $query;
	}
	$query->query( $arguments );
	return $query;
};

try {
	for ( $number = 1; $number <= 25; $number++ ) {
		$id = wp_insert_post( array( 'post_type' => 'post', 'post_status' => 'publish', 'post_title' => 'Test locale — paginazione ' . $number, 'post_content' => 'Articolo locale temporaneo per verificare la navigazione del blog.' ), true );
		if ( is_wp_error( $id ) ) {
			throw new RuntimeException( $id->get_error_message() );
		}
		$created[] = $id;
	}
	$_SERVER['REQUEST_URI'] = '/news/';
	$arguments = array( 'post_type' => 'post', 'post__in' => $created, 'posts_per_page' => 10, 'ignore_sticky_posts' => true, 'orderby' => 'ID', 'order' => 'DESC' );
	$seen = array();
	foreach ( array( 1 => 12, 2 => 12, 3 => 1 ) as $page => $expected_count ) {
		$result = $query( array_merge( $arguments, array( 'paged' => $page ) ) );
		$check( count( $result->posts ) === $expected_count && 3 === (int) $result->max_num_pages, 'Use twelve articles per page and keep the remaining article on the last page.' );
		$seen = array_merge( $seen, wp_list_pluck( $result->posts, 'ID' ) );
		$navigation = get_the_posts_pagination( array( 'next_text' => 'Altri articoli →' ) );
		$check( ( $page < 3 ) === str_contains( $navigation, 'class="next page-numbers"' ), 'Show the next-page link only while more articles remain.' );
		$check( ( $page > 1 ) === str_contains( $navigation, 'class="prev page-numbers"' ), 'Allow returning to newer articles from subsequent pages.' );
	}
	$check( $seen === array_reverse( $created ), 'Every article must appear exactly once in the original order.' );
	$secondary = $query( $arguments, false );
	$check( 10 === count( $secondary->posts ), 'Secondary queries, including Home cards, retain their own page size.' );
	$search = $query( array_merge( $arguments, array( 's' => 'paginazione', 'posts_per_page' => 7 ) ) );
	$check( 7 === count( $search->posts ), 'Search results retain their own page size.' );
	WP_CLI::success( 'Blog pagination: 12/12/1, complete ordered coverage, next/previous boundaries and unaffected secondary/search queries passed.' );
} finally {
	foreach ( array_reverse( $created ) as $id ) {
		wp_delete_post( $id, true );
	}
	$GLOBALS['wp_query'] = $original_query;
	$GLOBALS['wp_the_query'] = $original_main_query;
	$_SERVER['REQUEST_URI'] = $original_uri;
}
