<?php
/**
 * Plugin Name: FISAR Castelli di Jesi — Core
 * Description: Contenuti, campi, relazioni e logiche di dominio del sito FISAR Castelli di Jesi.
 * Version: 1.3.22
 * Requires at least: 6.7
 * Requires PHP: 8.1
 * Author: FISAR Castelli di Jesi
 * Text Domain: fisar-cdj-core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'FISAR_CDJ_CORE_VERSION', '1.3.22' );
define( 'FISAR_CDJ_CORE_FILE', __FILE__ );
define( 'FISAR_CDJ_CORE_DIR', plugin_dir_path( __FILE__ ) );
define( 'FISAR_CDJ_CORE_URL', plugin_dir_url( __FILE__ ) );

require_once FISAR_CDJ_CORE_DIR . 'src/class-post-types.php';
require_once FISAR_CDJ_CORE_DIR . 'src/class-calendar-importer.php';
require_once FISAR_CDJ_CORE_DIR . 'src/class-meta-boxes.php';
require_once FISAR_CDJ_CORE_DIR . 'src/class-demo-content.php';
require_once FISAR_CDJ_CORE_DIR . 'src/functions.php';
require_once FISAR_CDJ_CORE_DIR . 'src/class-course-titles.php';

function fisar_cdj_core_boot(): void {
	Fisar_CDJ_Post_Types::init();
	Fisar_CDJ_Meta_Boxes::init();
	Fisar_CDJ_Course_Titles::init();
	Fisar_CDJ_Demo_Content::init();
}
add_action( 'plugins_loaded', 'fisar_cdj_core_boot' );

/** Default only new News galleries; never rewrite stored blocks or single images. */
function fisar_cdj_core_news_editor_assets(): void {
	$screen = get_current_screen();
	if ( ! $screen || 'post' !== $screen->base || 'post' !== $screen->post_type ) {
		return;
	}

	wp_enqueue_script(
		'fisar-cdj-news-gallery',
		FISAR_CDJ_CORE_URL . 'assets/js/news-gallery.js',
		array( 'wp-blocks', 'wp-block-library', 'wp-dom-ready', 'wp-i18n' ),
		FISAR_CDJ_CORE_VERSION,
		true
	);
}
add_action( 'enqueue_block_editor_assets', 'fisar_cdj_core_news_editor_assets' );

function fisar_cdj_core_activate(): void {
	Fisar_CDJ_Post_Types::register();
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'fisar_cdj_core_activate' );

function fisar_cdj_core_deactivate(): void {
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'fisar_cdj_core_deactivate' );
