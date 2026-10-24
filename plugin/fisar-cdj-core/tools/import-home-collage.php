<?php
/**
 * Explicit, one-off local import. Not loaded by the plugin or on web requests.
 * wp eval-file <this-file> <photos.json> <webp-directory> <expected-home-sha256>
 * Never use a local database import to update the editorial site on Aruba.
 */
if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) {
	exit;
}
if ( count( $args ) !== 3 ) {
	WP_CLI::error( 'Passare manifest, directory WebP e SHA-256 atteso della Home.' );
}
if ( 'local' !== wp_get_environment_type() ) {
	WP_CLI::error( 'Import riservato all’ambiente locale; Aruba si aggiorna dalla propria Libreria media.' );
}
$home_id = (int) get_option( 'page_on_front' );
$before = get_post_field( 'post_content', $home_id );
if ( ! $home_id || ! hash_equals( $args[2], hash( 'sha256', $before ) ) ) {
	WP_CLI::error( 'Home diversa da quella verificata: import annullato.' );
}
$manifest = json_decode( file_get_contents( $args[0] ), true, 512, JSON_THROW_ON_ERROR );
$approved = array_values( array_filter( $manifest['variants'], static fn( $variant ) => 'E' === $variant['id'] ) )[0] ?? null;
$keys = $approved['photos'] ?? array();
if ( ! $keys || count( $keys ) % 6 || count( array_unique( $keys ) ) !== count( $keys ) ) {
	WP_CLI::error( 'La selezione deve avere gruppi completi di sei fotografie distinte.' );
}
$old_gallery = null;
foreach ( parse_blocks( $before ) as $block ) {
	if ( 'core/gallery' === $block['blockName'] && in_array( 'hero-collage', preg_split( '/\s+/', $block['attrs']['className'] ?? '' ), true ) ) {
		if ( $old_gallery ) {
			WP_CLI::error( 'Più gallerie hero presenti: selezionare manualmente quella da aggiornare.' );
		}
		$old_gallery = serialize_block( $block );
	}
}
if ( ! $old_gallery || 1 !== substr_count( $before, $old_gallery ) ) {
	WP_CLI::error( 'Galleria precedente non riconosciuta; nessuna modifica.' );
}
foreach ( $keys as $key ) {
	if ( ! preg_match( '/^[a-z0-9-]+$/', $key ) || empty( $manifest['photos'][ $key ] ) || ! is_file( $args[1] . '/' . $key . '.webp' ) ) {
		WP_CLI::error( 'Fotografia non disponibile: ' . $key );
	}
}
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/media.php';
require_once ABSPATH . 'wp-admin/includes/image.php';
$gallery = array(
	'blockName' => 'core/gallery',
	'attrs' => array( 'columns' => 3, 'imageCrop' => false, 'linkTo' => 'none', 'className' => 'hero-collage' ),
	'innerBlocks' => array(),
	'innerHTML' => '',
	'innerContent' => array( '<figure class="wp-block-gallery has-nested-images columns-3 hero-collage">' ),
);
foreach ( $keys as $key ) {
	$photo = $manifest['photos'][ $key ];
	$existing = get_posts( array( 'post_type' => 'attachment', 'post_status' => 'inherit', 'posts_per_page' => 1, 'fields' => 'ids', 'meta_key' => '_fisar_hero_photo_key', 'meta_value' => $key ) );
	$image_id = (int) ( $existing[0] ?? 0 );
	if ( ! $image_id ) {
		$temp = wp_tempnam( $key . '.webp' );
		if ( ! $temp || ! copy( $args[1] . '/' . $key . '.webp', $temp ) ) {
			WP_CLI::error( 'Impossibile preparare il file: ' . $key );
		}
		$image_id = media_handle_sideload( array( 'name' => 'hero-' . $key . '.webp', 'tmp_name' => $temp ), $home_id, $photo['alt'] );
		if ( is_wp_error( $image_id ) ) {
			if ( is_file( $temp ) ) {
				unlink( $temp );
			}
			WP_CLI::error( $image_id->get_error_message() );
		}
		update_post_meta( $image_id, '_fisar_hero_photo_key', $key );
		update_post_meta( $image_id, '_wp_attachment_image_alt', $photo['alt'] );
	}
	preg_match( '/^(\d{1,3})% (\d{1,3})%$/', $photo['focus'], $focus );
	$class = sprintf( 'hero-focus-%d-%d', min( 100, (int) ( $focus[1] ?? 50 ) ), min( 100, (int) ( $focus[2] ?? 50 ) ) );
	$html = '<figure class="wp-block-image size-full ' . esc_attr( $class ) . '"><img src="' . esc_url( wp_get_attachment_url( $image_id ) ) . '" alt="' . esc_attr( get_post_meta( $image_id, '_wp_attachment_image_alt', true ) ) . '" class="wp-image-' . (int) $image_id . '"/></figure>';
	$gallery['innerBlocks'][] = array( 'blockName' => 'core/image', 'attrs' => array( 'id' => $image_id, 'sizeSlug' => 'full', 'linkDestination' => 'none', 'className' => $class ), 'innerBlocks' => array(), 'innerHTML' => $html, 'innerContent' => array( $html ) );
	$gallery['innerContent'][] = null;
}
$gallery['innerContent'][] = '</figure>';
$after = str_replace( $old_gallery, serialize_block( $gallery ), $before );
if ( $after === $before ) {
	WP_CLI::success( 'La galleria è già aggiornata; nessuna scrittura.' );
	return;
}
if ( get_post_field( 'post_content', $home_id ) !== $before ) {
	WP_CLI::error( 'Home modificata durante l’import: contenuto non salvato.' );
}
wp_save_post_revision( $home_id );
$result = wp_update_post( array( 'ID' => $home_id, 'post_content' => wp_slash( $after ) ), true );
if ( is_wp_error( $result ) ) {
	WP_CLI::error( $result->get_error_message() );
}
if ( get_post_field( 'post_content', $home_id ) !== $after ) {
	WP_CLI::error( 'Verifica del contenuto salvato non superata.' );
}
WP_CLI::success( count( $keys ) . ' fotografie nella sola galleria Home; introduzione, calice e thumbnail preservati.' );
