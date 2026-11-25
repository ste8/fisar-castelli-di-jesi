<?php
/** Dedicated persistent local draft for UI review; no real social operation. */
if ( ! defined( 'WP_CLI' ) || ! WP_CLI || 'local' !== wp_get_environment_type() ) { throw new RuntimeException( 'Local only.' ); }
$existing = get_posts( array( 'post_type' => 'post', 'post_status' => 'draft', 'meta_key' => '_fisar_social_ui_fixture', 'meta_value' => 1, 'posts_per_page' => 1 ) );
if ( $existing ) { WP_CLI::line( get_edit_post_link( $existing[0]->ID, 'raw' ) ); return; }
$images = get_posts( array( 'post_type' => 'attachment', 'post_status' => 'inherit', 'post_mime_type' => 'image/jpeg', 'posts_per_page' => 200, 'fields' => 'ids' ) );
$ids = array();
foreach ( $images as $image ) {
	$meta = wp_get_attachment_metadata( $image );
	$ratio = ( $meta['width'] ?? 0 ) / max( 1, ( $meta['height'] ?? 1 ) );
	if ( $ratio >= 0.75 && $ratio <= 1.91 && is_file( get_attached_file( $image ) ?: '' ) ) { $ids[] = $image; }
	if ( 12 === count( $ids ) ) { break; }
}
if ( count( $ids ) < 12 ) { WP_CLI::error( 'Sono necessarie 12 foto JPG già disponibili localmente.' ); }
$body = '<!-- wp:paragraph --><p>Una prova del nuovo pannello social: testo modificabile per Facebook e Instagram, foto già nella libreria e nessun invio automatico.</p><!-- /wp:paragraph -->';
$body .= '<!-- wp:gallery {"columns":3,"imageCrop":true} --><figure class="wp-block-gallery has-nested-images columns-3 is-cropped">';
foreach ( $ids as $image ) { $body .= '<!-- wp:image {"id":' . $image . ',"sizeSlug":"large"} --><figure class="wp-block-image size-large"><img src="' . esc_url( wp_get_attachment_image_url( $image, 'large' ) ) . '" alt="" class="wp-image-' . $image . '"/></figure><!-- /wp:image -->'; }
$body .= '</figure><!-- /wp:gallery -->';
$id = wp_insert_post( array( 'post_type' => 'post', 'post_status' => 'draft', 'post_title' => 'Prova pubblicazione social — 12 foto', 'post_content' => $body, 'meta_input' => array( '_fisar_social_ui_fixture' => 1 ) ), true );
if ( is_wp_error( $id ) ) { WP_CLI::error( $id->get_error_message() ); }
WP_CLI::line( 'Articolo di prova: ' . $id . ' — http://localhost:8090/wp-admin/post.php?post=' . $id . '&action=edit' );
