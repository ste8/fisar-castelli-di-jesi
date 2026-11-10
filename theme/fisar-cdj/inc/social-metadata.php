<?php
/** Automatic sharing previews, derived from public editorial content. */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Read saved text without rendering embeds, galleries, shortcodes or dynamic blocks. */
function fisar_cdj_theme_social_block_text( array $blocks ): string {
	$text = array();
	$excluded = array( 'core/image', 'core/gallery', 'core/embed', 'core/video', 'core/audio', 'core/file', 'core/shortcode', 'core/block', 'core/query', 'core/template-part', 'core/navigation' );
	// These text blocks have render callbacks in WordPress, but their saved HTML is sufficient.
	$saved_text_blocks = array( 'core/list', 'core/heading' );
	foreach ( $blocks as $block ) {
		$block_type = $block['blockName'] ? WP_Block_Type_Registry::get_instance()->get_registered( $block['blockName'] ) : null;
		$dynamic = $block_type && $block_type->is_dynamic() && ! in_array( $block['blockName'], $saved_text_blocks, true );
		if ( in_array( $block['blockName'], $excluded, true ) || $dynamic ) {
			continue;
		}
		if ( $block['innerBlocks'] ) {
			$text[] = fisar_cdj_theme_social_block_text( $block['innerBlocks'] );
		} else {
			$text[] = $block['innerHTML'];
		}
	}
	return implode( ' ', $text );
}

/** Keep Unicode text readable and HTML safely confined to a single meta attribute. */
function fisar_cdj_theme_social_text( string $text, int $limit = 0 ): string {
	$text = strip_shortcodes( $text );
	$text = preg_replace( '/<\/?(?:p|div|li|ul|ol|h[1-6]|br|blockquote|figure)\b[^>]*>/i', ' ', $text ) ?? $text;
	$text = html_entity_decode( wp_strip_all_tags( $text ), ENT_QUOTES | ENT_HTML5, 'UTF-8' );
	$text = trim( preg_replace( '/[\s\p{Z}\p{Cc}]+/u', ' ', $text ) ?? '' );
	if ( $limit > 0 && mb_strlen( $text ) > $limit ) {
		$prefix = mb_substr( $text, 0, $limit - 1 );
		$boundary = mb_strrpos( $prefix, ' ' );
		if ( false !== $boundary ) {
			$prefix = mb_substr( $prefix, 0, $boundary );
		}
		$text = ( preg_replace( '/[\s.,;:–—-]+$/u', '', $prefix ) ?? $prefix ) . '…';
	}
	return $text;
}

function fisar_cdj_theme_social_description( WP_Post $post ): string {
	$excerpt = fisar_cdj_theme_social_text( $post->post_excerpt, 200 );
	return $excerpt ?: fisar_cdj_theme_social_text( fisar_cdj_theme_social_block_text( parse_blocks( $post->post_content ) ), 200 );
}

/** Prefer the actual cover, without cropping or substituting a photo from the story. */
function fisar_cdj_theme_social_image( ?WP_Post $post ): array {
	$id = $post ? (int) get_post_thumbnail_id( $post ) : 0;
	$mime = $id ? (string) get_post_mime_type( $id ) : '';
	if ( in_array( $mime, array( 'image/jpeg', 'image/png', 'image/webp', 'image/gif' ), true ) ) {
		$image = wp_get_attachment_image_src( $id, 'large' );
		if ( $image && $image[1] > 0 && $image[2] > 0 ) {
			$alt = fisar_cdj_theme_social_text( (string) get_post_meta( $id, '_wp_attachment_image_alt', true ) );
			return array( 'url' => $image[0], 'width' => $image[1], 'height' => $image[2], 'type' => $mime, 'alt' => $alt ?: fisar_cdj_theme_social_text( get_the_title( $post ) ) );
		}
	}
	return array(
		'url' => get_template_directory_uri() . '/assets/images/social-default-cover.png',
		'width' => 1200,
		'height' => 630,
		'type' => 'image/png',
		'alt' => 'FISAR Castelli di Jesi',
	);
}

function fisar_cdj_theme_social_metadata(): array {
	if ( is_admin() || is_feed() || is_search() || is_404() || is_preview() || is_embed() ) {
		return array();
	}
	$post = null;
	$title = wp_get_document_title();
	$description = '';
	$url = '';
	$type = 'website';
	$site_name = fisar_cdj_theme_social_text( get_bloginfo( 'name' ) );
	$default_description = 'Corsi per sommelier, degustazioni e incontri per conoscere il mondo del vino con la Delegazione FISAR Castelli di Jesi.';

	if ( is_singular() ) {
		$post = get_queried_object();
		if ( ! $post instanceof WP_Post || ! is_post_publicly_viewable( $post ) || $post->post_password || 'attachment' === $post->post_type ) {
			return array();
		}
		$url = wp_get_canonical_url( $post );
		$description = fisar_cdj_theme_social_description( $post );
		$type = 'post' === $post->post_type ? 'article' : 'website';
	}
	if ( is_front_page() ) {
		$post = null;
		$title = $site_name;
		$url = home_url( '/' );
		$description = $default_description;
	} elseif ( is_home() ) {
		$page_id = (int) get_option( 'page_for_posts' );
		$title = ( $page_id ? get_the_title( $page_id ) : 'Blog' ) . ' – ' . $site_name;
		$url = $page_id ? get_permalink( $page_id ) : home_url( '/' );
		$description = 'Approfondimenti, racconti e novità dalla nostra Delegazione.';
	} elseif ( is_post_type_archive( array( 'fisar_evento', 'fisar_corso' ) ) ) {
		$is_course = is_post_type_archive( 'fisar_corso' );
		$title = ( $is_course ? 'Corsi' : 'Eventi' ) . ' – ' . $site_name;
		$url = get_post_type_archive_link( $is_course ? 'fisar_corso' : 'fisar_evento' );
		$description = $is_course ? 'Corsi per sommelier, minicorsi e corsi tematici della Delegazione FISAR Castelli di Jesi.' : 'Degustazioni, visite e incontri per scoprire e condividere con la Delegazione FISAR Castelli di Jesi.';
	}
	if ( ! $url ) {
		return array();
	}
	return array(
		'title' => fisar_cdj_theme_social_text( $title ),
		'description' => $description ?: $default_description,
		'url' => $url,
		'type' => $type,
		'site_name' => $site_name,
		'locale' => get_locale(),
		'image' => fisar_cdj_theme_social_image( $post ),
	);
}

function fisar_cdj_theme_render_social_metadata(): void {
	$data = fisar_cdj_theme_social_metadata();
	if ( ! $data ) {
		return;
	}
	printf( '<meta name="description" content="%s">' . "\n", esc_attr( $data['description'] ) );
	$properties = array(
		'og:type' => $data['type'],
		'og:site_name' => $data['site_name'],
		'og:locale' => $data['locale'],
		'og:title' => $data['title'],
		'og:description' => $data['description'],
		'og:url' => esc_url_raw( $data['url'], array( 'http', 'https' ) ),
		'og:image' => esc_url_raw( $data['image']['url'], array( 'http', 'https' ) ),
		'og:image:type' => $data['image']['type'],
		'og:image:width' => $data['image']['width'],
		'og:image:height' => $data['image']['height'],
		'og:image:alt' => $data['image']['alt'],
	);
	foreach ( $properties as $property => $value ) {
		printf( '<meta property="%s" content="%s">' . "\n", esc_attr( $property ), esc_attr( (string) $value ) );
	}
}
add_action( 'wp_head', 'fisar_cdj_theme_render_social_metadata', 5 );
