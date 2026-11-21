<?php
/** Local integration: native cover/category storage and main-panel placement. */
if ( ! defined( 'WP_CLI' ) || ! WP_CLI || 'local' !== wp_get_environment_type() ) {
	throw new RuntimeException( 'Run only in local WordPress with WP-CLI.' );
}
$created = array();
$created_terms = array();
$original_screen = $GLOBALS['current_screen'] ?? null;
$original_user = get_current_user_id();
$check = static function ( bool $condition, string $message ): void {
	if ( ! $condition ) { throw new RuntimeException( $message ); }
};
try {
	$admin = get_users( array( 'role' => 'administrator', 'number' => 1 ) );
	wp_set_current_user( $admin[0]->ID );
	$images = get_posts( array( 'post_type' => 'attachment', 'post_status' => 'inherit', 'post_mime_type' => 'image', 'posts_per_page' => 2, 'fields' => 'ids' ) );
	$check( count( $images ) === 2, 'Local demo images are required; existing media sources are read only.' );
	foreach ( range( 1, 2 ) as $number ) {
		$term = wp_insert_term( 'FisarEditorMetadataFixture ' . $number, 'category' );
		$check( ! is_wp_error( $term ), 'Create dedicated local categories.' );
		$created_terms[] = $term['term_id'];
	}
	$categories = $created_terms;
	foreach ( array( 'post', 'fisar_evento', 'fisar_corso' ) as $type ) {
		$id = wp_insert_post( array( 'post_type' => $type, 'post_status' => 'draft', 'post_title' => 'FisarEditorMetadataFixture ' . $type, 'post_content' => '<p>Testo originale conservato.</p>' ), true );
		$check( ! is_wp_error( $id ), 'Create a dedicated draft.' );
		$created[] = $id;
		set_post_thumbnail( $id, $images[0] );
		if ( 'post' === $type ) { wp_set_post_categories( $id, $categories ); }
		$before = get_post_meta( $id );
		set_current_screen( $type ); get_current_screen()->is_block_editor( true );
		Fisar_CDJ_Meta_Boxes::add_meta_boxes();
		$boxes = $GLOBALS['wp_meta_boxes'][ $type ];
		if ( 'post' !== $type ) {
			$check( isset( $boxes['normal']['high']['fisar-editor-cover'] ), 'Event and course covers are registered in the main panel.' );
		}
		ob_start();
		if ( 'post' === $type ) { Fisar_CDJ_Meta_Boxes::render_post_summary( get_post( $id ) ); }
		else { Fisar_CDJ_Editor_Metadata::render_cover( get_post( $id ) ); }
		$html = ob_get_clean();
		$check( substr_count( $html, 'data-fisar-editor-cover' ) === 1 && ! str_contains( $html, 'name="_thumbnail_id"' ), 'One native editor component owns the cover, without a competing legacy input.' );
		$check( ( 'post' === $type ? 1 : 0 ) === substr_count( $html, 'data-fisar-editor-categories' ), 'Only the Blog gets the category control in its main summary panel.' );
		$check( $before === get_post_meta( $id ) && (int) $images[0] === get_post_thumbnail_id( $id ), 'Rendering preserves the existing cover and all metadata.' );
		if ( 'post' === $type ) {
			$check( array_values( array_diff( $categories, wp_get_post_categories( $id ) ) ) === array(), 'Rendering preserves multiple assigned categories.' );
		}
		$order = Fisar_CDJ_Editor_Metadata::box_order( array( 'normal' => 'fisar-event-visibility,other-box,fisar-event-course,fisar-event-whatsapp-message', 'side' => 'postimagediv,categorydiv,third-party' ) );
		$check( 'third-party' === $order['side'] && ! str_contains( $order['normal'], 'categorydiv' ) && ! str_contains( $order['normal'], 'postimagediv' ), 'Saved sidebar orders cannot restore duplicate native controls.' );
		$check( 'post' === $type || str_starts_with( $order['normal'], 'fisar-event-visibility,fisar-editor-cover,' ), 'The cover follows event visibility without disturbing other main boxes.' );
		$check( $order === Fisar_CDJ_Editor_Metadata::box_order( $order ), 'Normalizing stored positions is idempotent.' );
		$request = new WP_REST_Request( 'POST', '/wp/v2/' . ( 'post' === $type ? 'posts' : $type ) . '/' . $id );
		$request->set_body_params( array( 'featured_media' => $images[1] ) + ( 'post' === $type ? array( 'categories' => array( $categories[0] ) ) : array() ) );
		$result = rest_do_request( $request );
		$check( 200 === $result->get_status() && (int) $images[1] === get_post_thumbnail_id( $id ), 'Native edited-post REST saves can replace the featured image.' );
		if ( 'post' === $type ) {
			$check( array( (int) $categories[0] ) === wp_get_post_categories( $id ), 'The native category attribute saves the selected category.' );
		}
		$request->set_body_params( array( 'featured_media' => 0 ) );
		$check( 200 === rest_do_request( $request )->get_status() && 0 === get_post_thumbnail_id( $id ), 'The native cover field also supports removal.' );
		$check( '<p>Testo originale conservato.</p>' === get_post_field( 'post_content', $id, 'raw' ), 'Changing editorial attributes never rewrites the body.' );
		get_current_screen()->is_block_editor( false );
		Fisar_CDJ_Editor_Metadata::add_meta_boxes();
		$classic = $GLOBALS['wp_meta_boxes'][ $type ]['normal']['high'];
		$check( 'post_thumbnail_meta_box' === $classic['postimagediv']['callback'], 'Classic editor keeps the original media control and storage in the main panel.' );
		if ( 'post' === $type ) { $check( 'post_categories_meta_box' === $classic['categorydiv']['callback'], 'Classic Blog category UI remains native too.' ); }
	}
	WP_CLI::success( 'Editor metadata: native cover/category round trips, multiple categories, main-panel placement, saved orders, classic fallback and preserved bodies passed.' );
} finally {
	$GLOBALS['current_screen'] = $original_screen;
	foreach ( array_reverse( $created ) as $id ) { wp_delete_post( $id, true ); }
	foreach ( $created_terms as $id ) { wp_delete_term( $id, 'category' ); }
	wp_set_current_user( $original_user );
}
