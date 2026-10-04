<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'FISAR_CDJ_THEME_VERSION', '1.6.1' );

function fisar_cdj_theme_setup(): void {
	load_theme_textdomain( 'fisar-cdj', get_template_directory() . '/languages' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'editor-styles' );
	add_editor_style( 'assets/css/main.css' );
	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' )
	);
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 96,
			'width'       => 240,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	register_nav_menus(
		array(
			'primary' => 'Navigazione principale',
			'footer'  => 'Navigazione nel footer',
			'social'  => 'Canali social',
		)
	);
}
add_action( 'after_setup_theme', 'fisar_cdj_theme_setup' );

function fisar_cdj_theme_assets(): void {
	wp_enqueue_style( 'fisar-cdj-style', get_stylesheet_uri(), array(), FISAR_CDJ_THEME_VERSION );
	wp_enqueue_style( 'fisar-cdj-main', get_template_directory_uri() . '/assets/css/main.css', array( 'fisar-cdj-style' ), FISAR_CDJ_THEME_VERSION );
	wp_enqueue_script( 'fisar-cdj-navigation', get_template_directory_uri() . '/assets/js/navigation.js', array(), FISAR_CDJ_THEME_VERSION, true );
}
add_action( 'wp_enqueue_scripts', 'fisar_cdj_theme_assets' );

/**
 * Precarica soltanto i font indispensabili above-the-fold.
 *
 * I file contengono soltanto il subset latino previsto dalla specifica,
 * evitando richieste a servizi esterni durante la navigazione.
 */
function fisar_cdj_theme_preload_fonts(): void {
	$font_base_url = get_template_directory_uri() . '/assets/fonts/';
	$fonts         = array(
		$font_base_url . 'cormorant-garamond/cormorant-garamond-latin-600-700.woff2',
		$font_base_url . 'poppins/poppins-latin-400.woff2',
	);

	foreach ( $fonts as $font_url ) {
		printf(
			'<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
			esc_url( $font_url )
		);
	}
}
add_action( 'wp_head', 'fisar_cdj_theme_preload_fonts', 1 );

function fisar_cdj_theme_admin_notice(): void {
	if ( current_user_can( 'activate_plugins' ) && ! class_exists( 'Fisar_CDJ_Post_Types' ) ) {
		echo '<div class="notice notice-warning"><p>Il tema FISAR Castelli di Jesi richiede il plugin <strong>FISAR Castelli di Jesi — Core</strong> per Eventi, Corsi e relative funzioni.</p></div>';
	}
}
add_action( 'admin_notices', 'fisar_cdj_theme_admin_notice' );

function fisar_cdj_theme_excerpt_length(): int {
	return 24;
}
add_filter( 'excerpt_length', 'fisar_cdj_theme_excerpt_length' );

function fisar_cdj_theme_excerpt_more(): string {
	return '…';
}
add_filter( 'excerpt_more', 'fisar_cdj_theme_excerpt_more' );

/**
 * Restituisce una piccola icona SVG appartenente al linguaggio visivo del tema.
 *
 * Le icone sono decorative: il nome accessibile rimane sempre nel testo
 * adiacente, così da non affidare mai il significato alla sola immagine.
 */
function fisar_cdj_theme_icon( string $name, string $class = '' ): string {
	$icons = array(
		'whatsapp' => '<path d="M20.5 11.8a8.3 8.3 0 0 1-12.3 7.3L3.5 20.5l1.4-4.6A8.3 8.3 0 1 1 20.5 11.8Z"/><path d="M8.2 7.8c.2-.5.4-.5.8-.5h.5l1 2.3-.8 1c.8 1.7 2 2.8 3.7 3.6l1-.9 2.3 1.1v.5c0 .5-.1.8-.5 1.1-.6.5-1.5.7-2.3.5-3.7-1-6.5-3.8-7.4-7.4-.2-.5.2-1 .7-1.3Z"/>',
		'instagram' => '<rect x="3.5" y="3.5" width="17" height="17" rx="4"/><circle cx="12" cy="12" r="4"/><circle cx="17.4" cy="6.7" r=".8" class="icon__fill"/>',
		'facebook'  => '<path d="M13.7 20.5v-7.7h2.7l.4-3h-3.1V8c0-.9.3-1.5 1.6-1.5H17V3.8c-.8-.1-1.6-.2-2.4-.2-2.4 0-4.1 1.5-4.1 4.2v2H7.8v3h2.7v7.7"/>',
		'mail'      => '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="m4 7 8 6 8-6"/>',
		'calendar'  => '<rect x="4" y="5.5" width="16" height="15" rx="2"/><path d="M8 3.5v4M16 3.5v4M4 10h16"/><path d="M8 13h.01M12 13h.01M16 13h.01M8 17h.01M12 17h.01M16 17h.01" class="icon__dots"/>',
		'course'    => '<path d="m2.5 9 9.5-5 9.5 5-9.5 5-9.5-5Z"/><path d="M6.5 11.2v5.1c2.9 2.2 8.1 2.2 11 0v-5.1M21.5 9v6"/>',
		'members'   => '<circle cx="9" cy="8" r="3.2"/><path d="M3.5 20v-2.2c0-3 2.4-5.4 5.4-5.4s5.4 2.4 5.4 5.4V20"/><circle cx="17" cy="9" r="2.4"/><path d="M15.7 13.2c.5-.2 1-.3 1.5-.3 2.4 0 4.3 2 4.3 4.4V20h-4.8"/>',
		'heart'     => '<path d="M20.8 5.8a5.2 5.2 0 0 0-7.4 0L12 7.2l-1.4-1.4a5.2 5.2 0 0 0-7.4 7.4L12 22l8.8-8.8a5.2 5.2 0 0 0 0-7.4Z"/>',
		'leaf'      => '<path d="M12 21V9M12 16c-4.7.1-7.3-2.2-7.8-6.8 4.7-.2 7.3 2.1 7.8 6.8ZM12 12.8c.5-4.6 3.1-6.9 7.8-6.8-.5 4.6-3.1 6.9-7.8 6.8Z"/><path d="M12 18c-3.4 0-5.5 1.2-6.5 3M12 15.5c3.4 0 5.5 1.2 6.5 3"/>',
		'book'      => '<path d="M3.5 5.5c3.2-1.2 6-.5 8.5 1.6v13c-2.5-2.1-5.3-2.8-8.5-1.6v-13ZM20.5 5.5c-3.2-1.2-6-.5-8.5 1.6v13c2.5-2.1 5.3-2.8 8.5-1.6v-13Z"/>',
		'shield'    => '<path d="M12 2.8 20 6v5.8c0 4.8-3.3 8.1-8 9.4-4.7-1.3-8-4.6-8-9.4V6l8-3.2Z"/><path d="m8.5 12 2.2 2.2 4.8-5"/>',
	);

	if ( ! isset( $icons[ $name ] ) ) {
		return '';
	}

	return sprintf(
		'<svg class="site-icon %1$s" viewBox="0 0 24 24" aria-hidden="true" focusable="false">%2$s</svg>',
		esc_attr( $class ),
		$icons[ $name ]
	);
}

/**
 * Identifica un canale social dal testo configurato nel menu WordPress.
 */
function fisar_cdj_theme_social_network( string $title ): string {
	$normalized = strtolower( remove_accents( wp_strip_all_tags( $title ) ) );

	if ( str_contains( $normalized, 'whatsapp' ) ) {
		return 'whatsapp';
	}
	if ( str_contains( $normalized, 'instagram' ) ) {
		return 'instagram';
	}
	if ( str_contains( $normalized, 'facebook' ) ) {
		return 'facebook';
	}

	return '';
}

/**
 * Aggiunge l'icona del canale mantenendo la label testuale del menu.
 */
function fisar_cdj_theme_social_menu_title( string $title, WP_Post $item, stdClass $args, int $depth ): string {
	if ( 'social' !== ( $args->theme_location ?? '' ) ) {
		return $title;
	}

	$network = fisar_cdj_theme_social_network( $title );
	$label   = esc_html( wp_strip_all_tags( $title ) );

	return $network ? fisar_cdj_theme_icon( $network, 'social-link__icon' ) . '<span>' . $label . '</span>' : $label;
}
add_filter( 'nav_menu_item_title', 'fisar_cdj_theme_social_menu_title', 10, 4 );

/**
 * Restituisce i canali configurati per il componente "Come seguirci".
 */
function fisar_cdj_theme_follow_channels( string $newsletter_url ): array {
	$definitions = array(
		'whatsapp' => array(
			'title'       => 'WhatsApp',
			'description' => 'Avvisi rapidi su eventi, posti disponibili e nuove iscrizioni.',
			'cta'         => 'Segui il canale WhatsApp',
		),
		'instagram' => array(
			'title'       => 'Instagram',
			'description' => 'Foto, storie e momenti vissuti insieme alla Delegazione.',
			'cta'         => 'Seguici su Instagram',
		),
		'facebook' => array(
			'title'       => 'Facebook',
			'description' => 'Eventi, comunicazioni e vita della nostra comunità.',
			'cta'         => 'Seguici su Facebook',
		),
	);
	$urls        = array();
	$locations   = get_nav_menu_locations();
	$menu_id     = isset( $locations['social'] ) ? (int) $locations['social'] : 0;
	$menu_items  = $menu_id ? wp_get_nav_menu_items( $menu_id ) : array();

	foreach ( $menu_items ?: array() as $item ) {
		if ( (int) $item->menu_item_parent > 0 ) {
			continue;
		}
		$network = fisar_cdj_theme_social_network( $item->title );
		if ( $network && isset( $definitions[ $network ] ) && ! isset( $urls[ $network ] ) ) {
			$urls[ $network ] = $item->url;
		}
	}

	$channels = array();
	foreach ( $definitions as $network => $definition ) {
		if ( ! isset( $urls[ $network ] ) ) {
			continue;
		}
		$channels[] = array_merge(
			$definition,
			array(
				'key'  => $network,
				'icon' => $network,
				'url'  => $urls[ $network ],
			)
		);
	}

	$channels[] = array(
		'key'         => 'newsletter',
		'icon'        => 'mail',
		'title'       => 'Newsletter',
		'description' => 'Un riepilogo periodico con le novità più importanti.',
		'cta'         => 'Iscriviti alla newsletter',
		'url'         => $newsletter_url,
	);

	return $channels;
}

function fisar_cdj_theme_page_url( string $slug ): string {
	$page = get_page_by_path( $slug );

	return $page ? get_permalink( $page ) : home_url( '/' . trim( $slug, '/' ) . '/' );
}

function fisar_cdj_theme_archive_url( string $post_type, string $fallback ): string {
	$url = get_post_type_archive_link( $post_type );

	return $url ?: home_url( '/' . trim( $fallback, '/' ) . '/' );
}

function fisar_cdj_theme_logo( bool $on_dark = false ): void {
	if ( ! $on_dark && has_custom_logo() ) {
		the_custom_logo();
		return;
	}

	$file = $on_dark ? 'logo-verticale-on-dark.svg' : 'logo-orizzontale.svg';
	$alt  = 'FISAR Castelli di Jesi';
	printf(
		'<a class="site-logo%1$s" href="%2$s" rel="home"><img src="%3$s" alt="%4$s" width="%5$d" height="%6$d"></a>',
		$on_dark ? ' site-logo--footer' : '',
		esc_url( home_url( '/' ) ),
		esc_url( get_template_directory_uri() . '/assets/images/' . $file ),
		esc_attr( $alt ),
		$on_dark ? 160 : 240,
		$on_dark ? 188 : 96
	);
}

function fisar_cdj_theme_post_image( int $post_id, string $class = '', bool $informative = false, string $loading = 'lazy' ): void {
	$attachment_id = get_post_thumbnail_id( $post_id );
	if ( ! $attachment_id ) {
		return;
	}

	$url = wp_get_attachment_url( $attachment_id );
	if ( ! $url ) {
		return;
	}

	$alt = '';
	if ( $informative ) {
		$alt = (string) get_post_meta( $attachment_id, '_wp_attachment_image_alt', true );
		if ( '' === $alt ) {
			$alt = get_the_title( $post_id );
		}
	}

	printf(
		'<img class="%1$s" src="%2$s" alt="%3$s" loading="%4$s" decoding="async">',
		esc_attr( $class ),
		esc_url( $url ),
		esc_attr( $alt ),
		esc_attr( $loading )
	);
}

function fisar_cdj_theme_format_date( string $date ): string {
	if ( '' === $date ) {
		return '';
	}
	$timestamp = strtotime( $date );

	return $timestamp ? wp_date( 'j F Y', $timestamp ) : '';
}

function fisar_cdj_theme_format_date_with_day( string $date ): string {
	if ( '' === $date ) {
		return '';
	}
	$timestamp = strtotime( $date );

	return $timestamp ? wp_date( 'l j F Y', $timestamp ) : '';
}

function fisar_cdj_theme_format_date_compact( string $date ): string {
	if ( '' === $date ) {
		return '';
	}
	$timestamp = strtotime( $date );

	return $timestamp ? strtoupper( wp_date( 'D j M Y', $timestamp ) ) : '';
}

function fisar_cdj_theme_reading_time( int $post_id ): int {
	$content = wp_strip_all_tags( (string) get_post_field( 'post_content', $post_id ) );
	$words   = str_word_count( $content );

	return max( 1, (int) ceil( $words / 210 ) );
}
