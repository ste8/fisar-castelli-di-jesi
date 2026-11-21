<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Place native featured images and blog categories beside the editorial metadata. */
final class Fisar_CDJ_Editor_Metadata {
	private const POST_TYPES = array( 'post', 'fisar_evento', 'fisar_corso' );

	public static function init(): void {
		add_action( 'admin_enqueue_scripts', array( self::class, 'enqueue' ) );
		foreach ( self::POST_TYPES as $type ) {
			add_filter( 'get_user_option_meta-box-order_' . $type, array( self::class, 'box_order' ), 20 );
		}
	}

	public static function is_block_editor(): bool {
		$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
		return ! $screen || $screen->is_block_editor();
	}

	public static function add_meta_boxes(): void {
		foreach ( self::POST_TYPES as $type ) {
			remove_meta_box( 'postimagediv', $type, 'side' );
			remove_meta_box( 'postimagediv', $type, 'normal' );
			if ( ! self::is_block_editor() ) {
				add_meta_box( 'postimagediv', 'Copertina', 'post_thumbnail_meta_box', $type, 'normal', 'high' );
			} elseif ( 'post' !== $type ) {
				add_meta_box( 'fisar-editor-cover', 'Copertina', array( self::class, 'render_cover' ), $type, 'normal', 'high' );
			}
		}
		remove_meta_box( 'categorydiv', 'post', 'side' );
		remove_meta_box( 'categorydiv', 'post', 'normal' );
		if ( ! self::is_block_editor() ) {
			add_meta_box( 'categorydiv', 'Categoria del Blog', 'post_categories_meta_box', 'post', 'normal', 'high', array( 'taxonomy' => 'category' ) );
		}
	}

	public static function render_cover( WP_Post $post ): void {
		if ( self::is_block_editor() ) {
			echo '<div class="fisar-editor-native-cover" data-fisar-editor-cover><p>Imposta la copertina tramite Immagine in evidenza nelle impostazioni del contenuto.</p></div>';
		} else {
			echo '<p>Imposta l’immagine nel riquadro Copertina di questo pannello.</p>';
		}
		if ( 'post' === $post->post_type ) {
			echo '<p class="description">Usa preferibilmente una foto quadrata di <strong>1080 × 1080 px</strong>, evitando testi importanti vicino ai bordi. La stessa immagine compare nella home, nell’elenco del Blog e accanto al titolo dell’articolo.</p>';
		} else {
			echo '<p class="description">Scegli la locandina o la copertina del corso. L’immagine viene usata anche negli elenchi del sito.</p>';
		}
	}

	public static function render_categories(): void {
		if ( self::is_block_editor() ) {
			echo '<div data-fisar-editor-categories><p>Seleziona la categoria nelle impostazioni dell’articolo.</p></div>';
		} else {
			echo '<p>Seleziona la categoria nel riquadro Categoria del Blog di questo pannello.</p>';
		}
	}

	/** Keep old sidebar positions from bringing back a second control. */
	public static function box_order( mixed $order ): mixed {
		if ( ! is_array( $order ) ) { return $order; }
		foreach ( $order as $context => $boxes ) {
			if ( is_string( $boxes ) ) {
				$order[ $context ] = implode( ',', array_diff( explode( ',', $boxes ), array( 'postimagediv', 'categorydiv', 'fisar-editor-cover' ) ) );
			}
		}
		$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
		$type = $screen ? $screen->post_type : '';
		if ( ! empty( $order['normal'] ) && is_string( $order['normal'] ) && in_array( $type, self::POST_TYPES, true ) ) {
			$normal = explode( ',', $order['normal'] );
			$position = array_search( 'fisar-event-visibility', $normal, true );
			$cover = self::is_block_editor() ? ( 'post' === $type ? array() : array( 'fisar-editor-cover' ) ) : array( 'postimagediv' );
			if ( ! self::is_block_editor() && 'post' === $type ) { $cover[] = 'categorydiv'; }
			array_splice( $normal, false === $position ? 0 : $position + 1, 0, $cover );
			$order['normal'] = implode( ',', $normal );
		}
		return $order;
	}

	public static function enqueue( string $hook ): void {
		$screen = get_current_screen();
		if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) || ! $screen || ! in_array( $screen->post_type, self::POST_TYPES, true ) || ! $screen->is_block_editor() ) { return; }
		wp_enqueue_script( 'fisar-cdj-editor-metadata', FISAR_CDJ_CORE_URL . 'assets/js/editor-metadata.js', array( 'wp-dom-ready', 'wp-element', 'wp-editor', 'wp-data' ), FISAR_CDJ_CORE_VERSION, true );
	}
}
