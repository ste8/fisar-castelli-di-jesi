<?php
/** Local integration: public mixed search, locations, relevance and paginated visibility. */
if ( ! defined( 'WP_CLI' ) || ! WP_CLI || 'local' !== wp_get_environment_type() ) {
	throw new RuntimeException( 'Run only in local WordPress with WP-CLI.' );
}
$created = array();
$original_query = $GLOBALS['wp_query'];
$original_main = $GLOBALS['wp_the_query'];
$original_post = $GLOBALS['post'] ?? null;
$original_user = get_current_user_id();
$excluded_pages = static function ( array $paths ): array { return array_merge( $paths, array( 'global-search-hidden-fixture' ) ); };
add_filter( 'fisar_cdj_search_excluded_page_paths', $excluded_pages );
$check = static function ( bool $condition, string $message ): void {
	if ( ! $condition ) { throw new RuntimeException( $message ); }
};
$make = static function ( string $type, array $extra = array() ) use ( &$created ): int {
	$id = wp_insert_post( array_merge( array( 'post_type' => $type, 'post_status' => 'publish', 'post_title' => 'FisarGlobalFixture ' . count( $created ), 'post_content' => '<p>Testo di prova.</p>' ), $extra ), true );
	if ( is_wp_error( $id ) ) { throw new RuntimeException( $id->get_error_message() ); }
	$created[] = $id;
	return $id;
};
$query = static function ( string $text, array $extra = array(), bool $main = true ): WP_Query {
	$q = new WP_Query();
	$GLOBALS['wp_query'] = $q;
	if ( $main ) { $GLOBALS['wp_the_query'] = $q; }
	$q->query( array_merge( array( 's' => $text ), $extra ) );
	return $q;
};
try {
	wp_set_current_user( 0 );
	$article = $make( 'post' );
	$event = $make( 'fisar_evento', array( 'meta_input' => array( '_fisar_event_date' => '2012-07-06', '_fisar_event_city' => 'CittàSegretaFixture', '_fisar_event_venue' => 'Villa Locale', '_fisar_event_address' => 'Via Fixture 18' ) ) );
	$course = $make( 'fisar_corso', array( 'meta_input' => array( '_fisar_course_archived' => true, '_fisar_course_city' => 'CittàSegretaFixture', '_fisar_course_venue' => 'Villa Locale', '_fisar_course_archive_year' => '2013' ) ) );
	$page = $make( 'page' );
	$make( 'page', array( 'post_name' => 'global-search-hidden-fixture', 'post_title' => 'HiddenPageFixture', 'post_content' => 'Pagina in revisione.' ) );
	$make( 'page', array( 'post_status' => 'draft' ) );
	$make( 'page', array( 'post_status' => 'private' ) );
	$make( 'page', array( 'post_password' => 'fixture-only' ) );
	$make( 'attachment', array( 'post_status' => 'inherit' ) );
	$make( 'post', array( 'post_status' => 'draft' ) );
	$make( 'post', array( 'post_status' => 'private' ) );
	$make( 'post', array( 'post_password' => 'fixture-only' ) );
	$make( 'post', array( 'post_status' => 'future', 'post_date' => '2099-01-01 12:00:00' ) );
	foreach ( Fisar_CDJ_Content_Visibility::FIELDS as $type => $key ) {
		$make( $type, array( 'meta_input' => array( $key => '1' ) ) );
	}
	$all = $query( 'FisarGlobalFixture' );
	$check( 4 === (int) $all->found_posts, 'Only published listed articles, events, courses and pages appear, once each.' );
	$check( array( $page, $course, $event, $article ) === array_map( 'intval', wp_list_pluck( $all->posts, 'ID' ) ), 'Ties have stable order.' );
	foreach ( array( 'eventi' => $event, 'blog' => $article, 'corsi' => $course, 'pagine' => $page ) as $type => $id ) {
		$filtered = $query( 'FisarGlobalFixture', array( 'tipo' => $type ) );
		$check( array( $id ) === array_map( 'intval', wp_list_pluck( $filtered->posts, 'ID' ) ), 'Type filter: ' . $type );
	}
	$check( 4 === (int) $query( 'FisarGlobalFixture', array( 'tipo' => 'page', 'post_type' => 'page', 'post_status' => 'any' ) )->found_posts, 'URL parameters cannot widen supported types or statuses.' );
	$check( 4 === (int) $query( 'FisarGlobalFixture', array( 'tipo' => array( 'blog' ) ) )->found_posts, 'Malformed type falls back to all supported contents.' );
	$check( 0 === (int) $query( 'HiddenPageFixture' )->found_posts, 'A published page kept available only for direct review stays excluded.' );
	$check( 0 === (int) $query( 'HiddenPageFixture', array( 'tipo' => 'pagine' ) )->found_posts, 'The Pages filter cannot expose excluded pages.' );
	$contacts = get_page_by_path( 'contatti' );
	$check( $contacts instanceof WP_Post, 'Local Contacts page exists.' );
	$contacts_before = get_post( $contacts->ID, ARRAY_A );
	$delegate = fisar_cdj_get_delegation_details()['delegate_name'];
	$contact_results = $query( $delegate, array( 'tipo' => 'pagine' ) );
	$check( in_array( $contacts->ID, wp_list_pluck( $contact_results->posts, 'ID' ), true ), 'The current delegate matches generated public Contacts information.' );
	$check( str_contains( Fisar_CDJ_Global_Search::page_text( $contacts ), $delegate ), 'The excerpt can show generated information instead of omitting the matched name.' );
	$check( $contacts_before === get_post( $contacts->ID, ARRAY_A ), 'Searching generated details never writes them into the page.' );
	$excluded_fixture = get_page_by_path( 'global-search-hidden-fixture' );
	$extra_text = static function ( string $text, WP_Post $post ) use ( $excluded_fixture ): string { return $post->ID === $excluded_fixture->ID ? 'HiddenGeneratedFixture' : $text; };
	add_filter( 'fisar_cdj_search_page_extra_text', $extra_text, 10, 2 );
	$check( 0 === (int) $query( 'HiddenGeneratedFixture' )->found_posts, 'Supplementary text cannot expose an excluded page.' );
	remove_filter( 'fisar_cdj_search_page_extra_text', $extra_text );
	$location = $query( 'CittàSegretaFixture' );
	$check( 2 === (int) $location->found_posts, 'Locations are searched in both events and historical courses.' );
	$check( 2 === (int) $query( 'FisarGlobalFixture "Villa Locale"' )->found_posts, 'Terms may match title and a phrase in venue metadata.' );
	$check( 2 === (int) $query( 'FisarGlobalFixture -CittàSegretaFixture' )->found_posts, 'Negative terms exclude location matches.' );
	$check( 1 === (int) $query( '"Via Fixture 18"' )->found_posts, 'Addresses are searchable without duplicate rows.' );
	$check( 0 === (int) $query( '' )->found_posts && 0 === (int) $query( '   ' )->found_posts, 'Opening or resetting a blank search lists no contents.' );
	$check( '' === Fisar_CDJ_Global_Search::state( $query( '', array( 's' => array( 'bad' ) ) ) )['text'], 'Malformed input becomes empty.' );
	$make( 'post', array( 'post_title' => 'Solo estratto', 'post_excerpt' => 'FisarExcerptOnlyFixture' ) );
	$check( 0 === (int) $query( 'FisarExcerptOnlyFixture' )->found_posts, 'The excerpt is not a search source.' );
	$make( 'fisar_evento', array( 'post_title' => 'Meta riservati', 'meta_input' => array( '_fisar_event_online_url' => 'https://example.test/FisarPrivateLocationFixture' ) ) );
	$check( 0 === (int) $query( 'FisarPrivateLocationFixture' )->found_posts, 'Private access metadata is not searched.' );
	$title_match = $make( 'post', array( 'post_title' => 'FisarRelevanceFixture', 'post_date' => '2012-01-01 12:00:00' ) );
	$make( 'post', array( 'post_title' => 'Una menzione nel testo', 'post_content' => 'FisarRelevanceFixture', 'post_date' => '2025-01-01 12:00:00' ) );
	$check( $title_match === $query( 'FisarRelevanceFixture' )->posts[0]->ID, 'Title matches rank ahead of a more recent body match.' );
	$body_nicole = $make( 'post', array( 'post_title' => 'Un incontro speciale', 'post_content' => '<p>Con <strong>Nicolé</strong>, una visita da ricordare.</p>', 'post_date' => '2012-01-01 12:00:00' ) );
	$whole_ids = array( $body_nicole );
	$partial_ids = array();
	for ( $i = 0; $i < 13; $i++ ) {
		$partial_ids[] = $make( 'post', array( 'post_title' => 'Le aziende vinicole ' . $i, 'post_content' => '<p>Un incontro tra cantine.</p>', 'post_date' => '2025-01-01 12:00:00' ) );
		$whole_ids[] = $make( 'post', array( 'post_title' => 'Nicole e le degustazioni ' . $i, 'post_date' => '2013-01-01 12:00:00' ) );
	}
	$attribute_only = $make( 'post', array( 'post_title' => 'Le aziende vinicole in foto', 'post_content' => '<p>Visita alle aziende vinicole.</p><img src="https://example.test/nicole.jpg" alt="Foto">', 'post_date' => '2026-01-01 12:00:00' ) );
	$partial_ids[] = $attribute_only;
	$word_fixtures = array_merge( $whole_ids, $partial_ids );
	$ranked = array();
	foreach ( array( 12, 12, 4 ) as $page_index => $page_count ) {
		$result = $query( 'nicole', array( 'post__in' => $word_fixtures, 'paged' => $page_index + 1 ) );
		$check( 28 === (int) $result->found_posts && $page_count === $result->post_count, 'Whole-word ranking keeps totals and page sizes.' );
		$ranked = array_merge( $ranked, wp_list_pluck( $result->posts, 'ID' ) );
	}
	$check( 28 === count( array_unique( $ranked ) ), 'Ranking is stable without duplicates between pages.' );
	$check( ! array_diff( array_slice( $ranked, 0, 14 ), $whole_ids ), 'All whole-word Nicole matches precede newer title matches inside vinicole, across page boundaries.' );
	$check( $body_nicole === $ranked[13], 'An accented whole word in the body outranks a substring in the title; whole title matches still come first.' );
	$check( in_array( $attribute_only, array_slice( $ranked, 14 ), true ), 'An image filename cannot promote a partial visible-text match to whole-word relevance.' );
	$venue_nicole = $make( 'fisar_evento', array( 'post_title' => 'Una visita guidata', 'meta_input' => array( '_fisar_event_venue' => 'Cantina Nicole' ), 'post_date' => '2011-01-01 12:00:00' ) );
	$ranked_venue = $query( 'nicole', array( 'post__in' => array( $venue_nicole, $attribute_only ) ) );
	$check( $venue_nicole === $ranked_venue->posts[0]->ID, 'Whole-word matches in a public venue also outrank title substrings.' );
	$phrase = $make( 'post', array( 'post_title' => 'I fondatori', 'post_content' => '<p>Sauro Bini ci accompagna.</p>', 'post_date' => '2010-01-01 12:00:00' ) );
	$separate = $make( 'post', array( 'post_title' => 'Sauro e Bini', 'post_date' => '2025-01-01 12:00:00' ) );
	$check( $phrase === $query( 'sauro bini', array( 'post__in' => array( $phrase, $separate ) ) )->posts[0]->ID, 'A complete phrase in the body outranks separate title words.' );
	$delegate_body = $make( 'post', array( 'post_title' => 'Un riferimento recente', 'post_content' => '<p>DeMichelisCorrado.</p>', 'post_date' => '2026-01-01 12:00:00' ) );
	$check( $contacts->ID === $query( 'corrado', array( 'post__in' => array( $contacts->ID, $delegate_body ) ) )->posts[0]->ID, 'Generated public Contacts names participate in whole-word relevance.' );
	$check( 14 === (int) $query( 'vinicole', array( 'post__in' => $word_fixtures ) )->found_posts, 'Ranking does not remove legitimate partial or whole-word search results.' );
	$exclusion_only = $query( '-ExcludedWordFixture', array( 'post__in' => $word_fixtures ) );
	$check( 28 === (int) $exclusion_only->found_posts, 'An exclusion-only search keeps native ordering without an empty phrase score.' );
	for ( $i = 0; $i < 25; $i++ ) { $make( 'post', array( 'post_title' => 'FisarPaginationFixture ' . $i ) ); }
	$seen = array();
	foreach ( array( 12, 12, 1 ) as $i => $count ) {
		$result = $query( 'FisarPaginationFixture', array( 'paged' => $i + 1 ) );
		$check( 25 === (int) $result->found_posts && $count === $result->post_count, 'Pagination keeps totals and page sizes.' );
		$seen = array_merge( $seen, wp_list_pluck( $result->posts, 'ID' ) );
	}
	$check( 25 === count( array_unique( $seen ) ), 'No duplicates or omissions between pages.' );
	$admin = get_users( array( 'role' => 'administrator', 'number' => 1 ) )[0]->ID;
	wp_set_current_user( $admin );
	$check( 4 === (int) $query( 'FisarGlobalFixture' )->found_posts, 'Public search stays public even for an authenticated editor.' );
	wp_set_current_user( 0 );
	$secondary = $query( 'FisarGlobalFixture', array( 'post_type' => 'page' ), false );
	$check( 1 === (int) $secondary->found_posts && 'page' === $secondary->posts[0]->post_type, 'Unrelated secondary queries remain unchanged.' );
	WP_CLI::success( 'Global search: visibility, types, locations, relevance, pagination and isolation passed.' );
} finally {
	remove_filter( 'fisar_cdj_search_excluded_page_paths', $excluded_pages );
	foreach ( $created as $id ) { wp_delete_post( $id, true ); }
	wp_set_current_user( $original_user );
	$GLOBALS['wp_query'] = $original_query;
	$GLOBALS['wp_the_query'] = $original_main;
	$GLOBALS['post'] = $original_post;
}
