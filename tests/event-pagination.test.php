<?php
/** Local integration: independently paginate future/past events without losing dates or IDs. */
if ( ! defined( 'WP_CLI' ) || ! WP_CLI || 'local' !== wp_get_environment_type() ) {
	throw new RuntimeException( 'Run this test only in local WordPress with WP-CLI.' );
}
$created = array();
$past_ids = array();
$future_ids = array();
$original_uri = $_SERVER['REQUEST_URI'] ?? '';
$check = static function ( bool $condition, string $message ): void {
	if ( ! $condition ) {
		throw new RuntimeException( $message );
	}
};
$create = static function ( string $date, string $status = 'publish' ) use ( &$created ): int {
	$id = wp_insert_post( array( 'post_type' => Fisar_CDJ_Post_Types::EVENT, 'post_status' => $status, 'post_title' => 'Test locale — paginazione eventi ' . count( $created ) ), true );
	if ( is_wp_error( $id ) ) {
		throw new RuntimeException( $id->get_error_message() );
	}
	$created[] = $id;
	if ( '' !== $date ) {
		update_post_meta( $id, '_fisar_event_date', $date );
	}
	return $id;
};
$isolate = static function ( WP_Query $query ) use ( &$created ): void {
	if ( Fisar_CDJ_Post_Types::EVENT === $query->get( 'post_type' ) ) {
		$query->set( 'post__in', $created );
	}
};
$render = static function ( WP_Query $query, string $query_var, string $anchor ): string {
	ob_start();
	get_template_part( 'template-parts/event-pagination', null, array( 'query' => $query, 'query_var' => $query_var, 'anchor' => $anchor, 'label' => 'Paginazione eventi di prova', 'prev_text' => '← Eventi precedenti' ) );
	return ob_get_clean();
};
try {
	$today = new DateTimeImmutable( fisar_cdj_today() );
	for ( $n = 1; $n <= 25; $n++ ) {
		$past_ids[] = $create( $today->modify( '-' . (int) ceil( $n / 2 ) . ' days' )->format( 'Y-m-d' ) );
	}
	for ( $n = 1; $n <= 13; $n++ ) {
		$future_ids[] = $create( $today->modify( '+' . (int) floor( $n / 2 ) . ' days' )->format( 'Y-m-d' ) );
	}
	$create( $today->modify( '-1 day' )->format( 'Y-m-d' ), 'draft' );
	$create( '' );
	add_action( 'pre_get_posts', $isolate );
	$_SERVER['REQUEST_URI'] = '/eventi/';
	$expected_past = $past_ids;
	usort( $expected_past, static function ( int $a, int $b ): int {
		$date = strcmp( get_post_meta( $b, '_fisar_event_date', true ), get_post_meta( $a, '_fisar_event_date', true ) );
		return $date ?: $a <=> $b;
	} );
	foreach ( array(
		array( 'fisar_cdj_get_past_events', 'eventi_conclusi', 'past-events', $expected_past, array( 12, 12, 1 ) ),
		array( 'fisar_cdj_get_upcoming_events', 'eventi_futuri', 'future-events', $future_ids, array( 12, 1 ) ),
	) as [ $get_events, $query_var, $anchor, $expected_ids, $counts ] ) {
		$seen = array();
		foreach ( $counts as $index => $count ) {
			$page = $index + 1;
			$query = $get_events( 12, $page );
			$check( $count === count( $query->posts ) && count( $counts ) === (int) $query->max_num_pages, 'Keep twelve events per page and preserve the final remainder.' );
			$seen = array_merge( $seen, wp_list_pluck( $query->posts, 'ID' ) );
			$html = $render( $query, $query_var, $anchor );
			$check( ( $page < count( $counts ) ) === str_contains( $html, 'class="next page-numbers"' ), 'Show next only when events remain.' );
			$check( ( $page > 1 ) === str_contains( $html, 'class="prev page-numbers"' ), 'Show previous on subsequent pages.' );
			preg_match_all( '/href="([^"]+)"/', $html, $matches );
			foreach ( $matches[1] as $href ) {
				$url = wp_parse_url( html_entity_decode( $href ) );
				parse_str( $url['query'] ?? '', $parameters );
				$check( isset( $parameters[ $query_var ] ) && $anchor === ( $url['fragment'] ?? '' ), 'Links address the correct independent section and scroll to its heading.' );
			}
		}
		$check( $expected_ids === $seen, 'Every matching published event appears once in chronological order, including events sharing a date.' );
		$check( $expected_ids === wp_list_pluck( $get_events()->posts, 'ID' ), 'Existing unpaginated API calls still return every event.' );
		$check( '' === $render( $get_events( 100 ), $query_var, $anchor ), 'A single-page list needs no pagination panel.' );
	}
	$vars = apply_filters( 'query_vars', array() );
	$check( in_array( 'eventi_futuri', $vars, true ) && in_array( 'eventi_conclusi', $vars, true ), 'WordPress accepts the independent event page parameters.' );
	WP_CLI::success( 'Event pagination: past 12/12/1, upcoming 12/1, chronological coverage, same-date boundaries, links and unchanged unlimited queries passed.' );
} finally {
	remove_action( 'pre_get_posts', $isolate );
	foreach ( array_reverse( $created ) as $id ) {
		wp_delete_post( $id, true );
	}
	$_SERVER['REQUEST_URI'] = $original_uri;
}
