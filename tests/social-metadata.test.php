<?php
/** Local WordPress integration: automatic previews and unpublished-content boundaries. */
if ( ! defined( 'WP_CLI' ) || ! WP_CLI || 'local' !== wp_get_environment_type() ) {
	throw new RuntimeException( 'Run only in local WordPress with WP-CLI.' );
}
$created = array();
$original_query = $GLOBALS['wp_query'];
$original_main = $GLOBALS['wp_the_query'];
$original_post = $GLOBALS['post'] ?? null;
$original_user = get_current_user_id();
$shortcode_calls = 0;
$dynamic_calls = 0;
$check = static function ( bool $condition, string $message ): void {
	if ( ! $condition ) { throw new RuntimeException( $message ); }
};
$make = static function ( array $args ) use ( &$created ): int {
	$id = wp_insert_post( array_merge( array( 'post_type' => 'fisar_evento', 'post_status' => 'publish', 'post_title' => 'Test locale anteprima', 'post_content' => '<!-- wp:paragraph --><p>Un incontro di degustazione a Jesi.</p><!-- /wp:paragraph -->' ), $args ), true );
	if ( is_wp_error( $id ) ) { throw new RuntimeException( $id->get_error_message() ); }
	$created[] = $id;
	return $id;
};
$query = static function ( array $args ): WP_Query {
	$q = new WP_Query( $args );
	$GLOBALS['wp_query'] = $q;
	$GLOBALS['wp_the_query'] = $q;
	$GLOBALS['post'] = $q->post;
	return $q;
};
$singular = static function ( int $id ) use ( $query ): WP_Query {
	return $query( array( 'post_type' => get_post_type( $id ), 'p' => $id, 'post_status' => array( 'publish', 'draft', 'private', 'future' ) ) );
};
try {
	wp_set_current_user( 1 );
	add_shortcode( 'fisar_test_secret', static function () use ( &$shortcode_calls ): string { $shortcode_calls++; return 'SECRET_SHORTCODE'; } );
	register_block_type( 'fisar-test/private', array( 'render_callback' => static function () use ( &$dynamic_calls ): string { $dynamic_calls++; return 'SECRET_DYNAMIC'; } ) );
	$content = '<!-- wp:paragraph --><p>Calici &amp; curiosità.</p><!-- /wp:paragraph --><!-- wp:list --><ul class="wp-block-list"><!-- wp:list-item --><li>Vini delle Marche</li><!-- /wp:list-item --><!-- wp:list-item --><li>Un incontro a Jesi</li><!-- /wp:list-item --></ul><!-- /wp:list --><!-- wp:gallery --><figure><figcaption>SECRET_CAPTION</figcaption></figure><!-- /wp:gallery --><!-- wp:shortcode -->[fisar_test_secret]SECRET_INNER[/fisar_test_secret]<!-- /wp:shortcode --><!-- wp:fisar-test/private /-->';
	$event = $make( array( 'post_title' => '🍷 Calici "aperti" & amici', 'post_content' => $content, 'meta_input' => array( '_fisar_event_online_url' => 'https://example.test/SECRET_ACCESS' ) ) );
	$singular( $event );
	$data = fisar_cdj_theme_social_metadata();
	$check( str_contains( $data['title'], 'Calici' ) && get_permalink( $event ) === $data['url'] && 'website' === $data['type'], 'New events receive their own title and canonical URL automatically.' );
	$check( 'Calici & curiosità. Vini delle Marche Un incontro a Jesi' === $data['description'], 'Paragraphs and real lists remain readable without HTML or concatenated words.' );
	$check( ! str_contains( json_encode( $data ), 'SECRET' ) && 0 === $shortcode_calls && 0 === $dynamic_calls, 'Galleries, shortcodes, dynamic blocks and private practical fields never feed the preview.' );
	$check( str_ends_with( $data['image']['url'], '/social-default-cover.png' ) && 1200 === $data['image']['width'] && 630 === $data['image']['height'], 'A missing cover uses the institutional raster instead of a gallery photo.' );

	$upload = wp_upload_bits( 'fisar-social-test-' . wp_generate_uuid4() . '.png', null, file_get_contents( get_template_directory() . '/assets/images/social-default-cover.png' ) );
	$check( ! $upload['error'], 'Local test image can be created.' );
	$attachment = wp_insert_attachment( array( 'post_title' => 'Copertina test locale', 'post_mime_type' => 'image/png', 'post_status' => 'inherit' ), $upload['file'] );
	$created[] = $attachment;
	require_once ABSPATH . 'wp-admin/includes/image.php';
	wp_update_attachment_metadata( $attachment, wp_generate_attachment_metadata( $attachment, $upload['file'] ) );
	update_post_meta( $attachment, '_wp_attachment_image_alt', 'Copertina "originale" & calici' );
	set_post_thumbnail( $event, $attachment );
	$singular( $event );
	$image = fisar_cdj_theme_social_metadata()['image'];
	$expected_image = wp_get_attachment_image_src( $attachment, 'large' );
	$check( $expected_image[0] === $image['url'] && abs( $image['width'] / $image['height'] - 1200 / 630 ) < .01 && 'image/png' === $image['type'], 'An editorial cover wins and retains its proportions.' );
	$check( 'Copertina "originale" & calici' === $image['alt'], 'The media alternative text is reused.' );
	wp_update_post( array( 'ID' => $event, 'post_title' => 'Nuovo titolo evento', 'post_content' => '<!-- wp:paragraph --><p>Nuovo testo già aggiornato.</p><!-- /wp:paragraph -->' ) );
	$singular( $event );
	$data = fisar_cdj_theme_social_metadata();
	$check( str_contains( $data['title'], 'Nuovo titolo evento' ) && 'Nuovo testo già aggiornato.' === $data['description'], 'Subsequent title/body edits change previews on the next request without a synchronization step.' );
	wp_update_post( array( 'ID' => $event, 'post_excerpt' => 'Descrizione scelta dall’editor.' ) );
	$singular( $event );
	$check( 'Descrizione scelta dall’editor.' === fisar_cdj_theme_social_metadata()['description'], 'An existing native excerpt has precedence.' );
	wp_update_post( array( 'ID' => $event, 'post_excerpt' => '', 'post_title' => 'Vini "aperti" & amici' ) );
	$singular( $event );
	$stored = get_post( $event );
	ob_start(); fisar_cdj_theme_render_social_metadata(); $html = ob_get_clean();
	$doc = new DOMDocument(); @$doc->loadHTML( '<?xml encoding="utf-8" ?><html><head>' . $html . '</head></html>' ); $xpath = new DOMXPath( $doc );
	$check( 1 === $xpath->query( '//meta[@property="og:title"]' )->length && 1 === $xpath->query( '//meta[@name="description"]' )->length, 'A single complete set is emitted into the head.' );
	$check( str_contains( $xpath->query( '//meta[@property="og:title"]' )->item( 0 )->getAttribute( 'content' ), 'Vini “aperti” & amici' ) && 'Copertina "originale" & calici' === $xpath->query( '//meta[@property="og:image:alt"]' )->item( 0 )->getAttribute( 'content' ), 'Quotes and ampersands survive attribute escaping without creating markup.' );
	$check( $stored->post_content === get_post( $event )->post_content && '' === get_post( $event )->post_excerpt, 'Generating a preview does not write summaries or change original content.' );
	$long = str_repeat( '🍷 città e curiosità ', 30 );
	$short = fisar_cdj_theme_social_text( $long, 200 );
	$check( mb_check_encoding( $short, 'UTF-8' ) && mb_strlen( $short ) <= 200 && str_ends_with( $short, '…' ), 'Descriptions are bounded without corrupting Unicode.' );

	$blog = $make( array( 'post_type' => 'post' ) );
	$singular( $blog );
	$check( 'article' === fisar_cdj_theme_social_metadata()['type'], 'Blog posts use the standard article object type.' );
	$course = $make( array( 'post_type' => 'fisar_corso', 'post_content' => '' ) );
	$singular( $course );
	$check( '' !== fisar_cdj_theme_social_metadata()['description'] && str_ends_with( fisar_cdj_theme_social_metadata()['image']['url'], '.png' ), 'Courses with no text or cover still have an appropriate default.' );
	foreach ( array( 'draft', 'private', 'future' ) as $status ) {
		$id = $make( array( 'post_status' => $status, 'post_date' => '2087-01-01 12:00:00', 'post_date_gmt' => '2087-01-01 11:00:00', 'post_content' => 'SECRET_UNPUBLISHED' ) ); $singular( $id );
		$check( array() === fisar_cdj_theme_social_metadata(), 'Unpublished content is excluded even for a logged-in administrator: ' . $status );
	}
	$protected = $make( array( 'post_password' => 'local-only', 'post_excerpt' => 'SECRET_PASSWORD' ) ); $singular( $protected );
	$check( array() === fisar_cdj_theme_social_metadata(), 'Password-protected content never leaks an excerpt or cover.' );
	$q = $singular( $event ); $q->is_preview = true;
	$check( array() === fisar_cdj_theme_social_metadata(), 'Editor previews emit no social metadata.' );
	foreach ( array( 'fisar_evento', 'fisar_corso' ) as $post_type ) {
		$query( array( 'post_type' => $post_type, 'anno' => 2024, 'search' => 'example' ) );
		$data = fisar_cdj_theme_social_metadata();
		$check( get_post_type_archive_link( $post_type ) === $data['url'] && 'website' === $data['type'] && str_ends_with( $data['image']['url'], '/social-default-cover.png' ), 'Filtered archives share the stable archive URL and institutional cover.' );
	}
	$home_id = (int) get_option( 'page_on_front' );
	if ( $home_id ) {
		$query( array( 'page_id' => $home_id ) );
		$data = fisar_cdj_theme_social_metadata();
		$check( home_url( '/' ) === $data['url'] && get_bloginfo( 'name' ) === $data['title'] && str_ends_with( $data['image']['url'], '/social-default-cover.png' ), 'The homepage uses the institutional preview instead of a legacy hero thumbnail.' );
	}
	$query( array( 's' => 'PRIVATE_SEARCH' ) );
	$check( array() === fisar_cdj_theme_social_metadata(), 'Search requests do not expose query text in a social preview.' );
	WP_CLI::success( 'Social metadata: new content and edits, editorial covers/fallback, safe text, escaping, public-only output and filtered archives passed.' );
} finally {
	remove_shortcode( 'fisar_test_secret' );
	unregister_block_type( 'fisar-test/private' );
	foreach ( array_reverse( $created ) as $id ) {
		if ( 'attachment' === get_post_type( $id ) ) { wp_delete_attachment( $id, true ); } else { wp_delete_post( $id, true ); }
	}
	wp_set_current_user( $original_user );
	$GLOBALS['wp_query'] = $original_query;
	$GLOBALS['wp_the_query'] = $original_main;
	$GLOBALS['post'] = $original_post;
}
