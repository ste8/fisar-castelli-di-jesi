<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'FISAR_CDJ_THEME_VERSION', '1.0.0' );

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

function fisar_cdj_theme_reading_time( int $post_id ): int {
	$content = wp_strip_all_tags( (string) get_post_field( 'post_content', $post_id ) );
	$words   = str_word_count( $content );

	return max( 1, (int) ceil( $words / 210 ) );
}

