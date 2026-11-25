<?php
/** Explicit, per-article publication; saving/importing a post never sends anything. */
final class Fisar_CDJ_Social_Publishing {
	public const COMPOSER_META = '_fisar_social_composer';
	public const DELIVERY_META = '_fisar_social_delivery';
	public const NETWORKS = array(
		'facebook' => array( 'label' => 'Facebook', 'images' => 10, 'characters' => 63206 ),
		'instagram' => array( 'label' => 'Instagram', 'images' => 10, 'characters' => 2200 ),
	);

	public static function is_live(): bool { return ! empty( Fisar_CDJ_Social_Settings::values()['live'] ); }

	public static function deliveries( int $post_id ): array {
		$value = get_post_meta( $post_id, self::DELIVERY_META, true );
		return is_array( $value ) ? array_filter( array_intersect_key( $value, self::NETWORKS ), 'is_array' ) : array();
	}

	public static function article_images( WP_Post $post ): array {
		$ids = array();
		$walk = static function ( array $blocks ) use ( &$walk, &$ids ): void {
			foreach ( $blocks as $block ) {
				if ( 'core/image' === $block['blockName'] && ! empty( $block['attrs']['id'] ) ) { $ids[] = (int) $block['attrs']['id']; }
				if ( 'core/gallery' === $block['blockName'] ) { $ids = array_merge( $ids, array_map( 'intval', $block['attrs']['ids'] ?? array() ) ); }
				$walk( $block['innerBlocks'] ?? array() );
			}
		};
		$walk( parse_blocks( $post->post_content ) );
		preg_match_all( '/\bwp-image-(\d+)\b/', $post->post_content, $matches );
		$ids = array_merge( $ids, array_map( 'intval', $matches[1] ) );
		if ( ! $ids && get_post_thumbnail_id( $post ) ) { $ids[] = get_post_thumbnail_id( $post ); }
		return array_values( array_filter( array_unique( $ids ), 'wp_attachment_is_image' ) );
	}

	/** Text prepared as an editable suggestion, without executing blocks or shortcodes. */
	public static function initial_text( WP_Post $post ): string {
		$html = strip_shortcodes( $post->post_content );
		$html = preg_replace( '#<(script|style|iframe)\b[^>]*>.*?</\1>#is', '', $html );
		$html = preg_replace( '#<li\b[^>]*>#i', "\n• ", $html );
		$html = preg_replace( '#</(?:p|h[1-6]|div|li|figcaption)>|<br\s*/?>#i', "\n", $html );
		$body = trim( preg_replace( '/\n[\t ]*\n(?:[\t ]*\n)+/', "\n\n", html_entity_decode( wp_strip_all_tags( $html ), ENT_QUOTES | ENT_HTML5, 'UTF-8' ) ) );
		$title = html_entity_decode( wp_strip_all_tags( $post->post_title ), ENT_QUOTES | ENT_HTML5, 'UTF-8' );
		return $title . "\n\n" . $body;
	}

	public static function composer( WP_Post $post ): array {
		$saved = get_post_meta( $post->ID, self::COMPOSER_META, true );
		if ( is_array( $saved ) && isset( $saved['text'], $saved['images'], $saved['networks'] ) ) { return $saved; }
		// Preserve a preparation saved in the first local prototype; do not merge or crop photos.
		if ( is_array( $saved ) && is_array( $saved['networks']['facebook'] ?? null ) ) {
			$source = ! empty( $saved['networks']['facebook']['enabled'] ) ? $saved['networks']['facebook'] : $saved['networks']['instagram'];
			return array(
				'text' => $source['text'], 'images' => $source['images'],
				'networks' => array_map( static fn( array $row ): bool => ! empty( $row['enabled'] ), $saved['networks'] ),
				'mode' => $saved['mode'], 'scheduled_at' => $saved['scheduled_at'],
			);
		}
		return array( 'text' => self::initial_text( $post ), 'images' => self::article_images( $post ), 'networks' => array( 'facebook' => true, 'instagram' => true ), 'mode' => 'now', 'scheduled_at' => '' );
	}

	public static function validate_composer( mixed $raw, WP_Post $post ): array|WP_Error {
		if ( ! is_array( $raw ) || ! is_array( $raw['networks'] ?? null ) || ! is_string( $raw['text'] ?? null ) || ! is_array( $raw['images'] ?? null ) || count( $raw['images'] ) > 200 || ! in_array( $raw['mode'] ?? '', array( 'now', 'scheduled' ), true ) || ! is_string( $raw['scheduled_at'] ?? '' ) ) {
			return new WP_Error( 'social_invalid', 'Preparazione social non valida.' );
		}
		$ids = array();
		foreach ( $raw['images'] as $id ) {
			if ( ! is_int( $id ) || $id < 1 || ! wp_attachment_is_image( $id ) || ! current_user_can( 'read_post', $id ) ) { return new WP_Error( 'social_media', 'Una delle immagini non è disponibile nella libreria media.' ); }
			$ids[] = $id;
		}
		if ( array_diff( $ids, self::article_images( $post ) ) ) { return new WP_Error( 'social_article_images', 'Scegli solo foto già presenti nell’articolo. Salva l’articolo e rileggilo se hai aggiunto nuove foto.' ); }
		$clean = array( 'text' => sanitize_textarea_field( $raw['text'] ), 'images' => array_values( array_unique( $ids ) ), 'networks' => array(), 'mode' => $raw['mode'], 'scheduled_at' => sanitize_text_field( $raw['scheduled_at'] ?? '' ) );
		foreach ( self::NETWORKS as $service => $network ) {
			if ( ! is_bool( $raw['networks'][ $service ] ?? null ) ) { return new WP_Error( 'social_invalid', 'Selezione dei canali non valida.' ); }
			$clean['networks'][ $service ] = $raw['networks'][ $service ];
		}
		return $clean;
	}

	public static function media( int $id, string $service = 'instagram' ): array {
		$variant = Fisar_CDJ_Media_Variants::social( $id, $service );
		$base = array(
			'id' => $id, 'url' => '', 'width' => 0, 'height' => 0, 'bytes' => 0, 'mime' => '',
			'thumbnail' => wp_get_attachment_image_url( $id, 'thumbnail' ),
			'preview' => wp_get_attachment_image_url( $id, 'medium' ),
			'alt' => (string) get_post_meta( $id, '_wp_attachment_image_alt', true ),
			'title' => get_the_title( $id ), 'error' => '',
		);
		if ( is_wp_error( $variant ) ) { $base['error'] = $variant->get_error_message(); return $base; }
		return array_merge( $base, $variant, array( 'preview' => $variant['url'] ) );
	}

	public static function validate_send( WP_Post $post, array $composer, bool $live ): true|WP_Error {
		if ( array_diff( $composer['images'], self::article_images( $post ) ) ) { return new WP_Error( 'social_article_images', 'Scegli solo foto già presenti nell’articolo.' ); }
		if ( $live && ( 'publish' !== $post->post_status || $post->post_password || Fisar_CDJ_Content_Visibility::is_unlisted( $post->ID ) ) ) {
			return new WP_Error( 'social_private', 'Per pubblicare sui social l’articolo deve essere pubblicato, elencato e senza password.' );
		}
		$selected = false;
		foreach ( self::NETWORKS as $service => $network ) {
			if ( ! $composer['networks'][ $service ] ) { continue; }
			$selected = true;
			$count = count( $composer['images'] );
			if ( $count > $network['images'] ) { return new WP_Error( 'social_image_limit', $network['label'] . ': hai selezionato ' . $count . ' foto. Il limite è ' . $network['images'] . '. Scegli quali mantenere: nessuna foto viene esclusa automaticamente.' ); }
			if ( ! trim( $composer['text'] ) || mb_strlen( $composer['text'] ) > $network['characters'] ) { return new WP_Error( 'social_text_limit', $network['label'] . ': inserisci un testo entro ' . $network['characters'] . ' caratteri.' ); }
			if ( 'instagram' === $service && ! $count ) { return new WP_Error( 'social_image_required', 'Instagram richiede almeno una foto.' ); }
			foreach ( $composer['images'] as $id ) {
				$image = self::media( $id, $service );
				if ( $image['error'] ) { return new WP_Error( 'social_media_prepare', $image['error'] ); }
				if ( ! in_array( $image['mime'], array( 'image/jpeg', 'image/png' ), true ) ) { return new WP_Error( 'social_image_format', 'Per questa prima versione scegli foto JPG o PNG: ' . $image['title'] ); }
				if ( 'instagram' === $service && $image['height'] ) {
					$ratio = $image['width'] / $image['height'];
					if ( $image['width'] < 320 || $image['width'] > 1440 || $ratio < 0.75 || $ratio > 1.91 || ! $image['bytes'] || $image['bytes'] > 8 * MB_IN_BYTES ) { return new WP_Error( 'social_image_size', 'Instagram: la copia della foto «' . $image['title'] . '» non rispetta i limiti. Rileggi l’articolo per prepararla di nuovo.' ); }
				}
				if ( $live && ( 'https' !== wp_parse_url( $image['url'], PHP_URL_SCHEME ) || ! wp_http_validate_url( $image['url'] ) ) ) { return new WP_Error( 'social_image_url', 'Le foto devono avere URL HTTPS pubblici. Le immagini su localhost possono essere usate solo nella simulazione.' ); }
			}
		}
		if ( ! $selected ) { return new WP_Error( 'social_network_required', 'Seleziona almeno un social.' ); }
		if ( 'scheduled' === $composer['mode'] ) {
			$due = self::scheduled_date( $composer['scheduled_at'] );
			if ( ! $due || $due->getTimestamp() < time() + 60 ) { return new WP_Error( 'social_date', 'Scegli una data e un’ora future, nel fuso del sito.' ); }
		}
		return true;
	}

	public static function scheduled_date( string $value ): DateTimeImmutable|false {
		$date = DateTimeImmutable::createFromFormat( '!Y-m-d\TH:i', $value, wp_timezone() );
		return $date && $date->format( 'Y-m-d\TH:i' ) === $value ? $date : false;
	}

	public static function payload( string $service, string $channel, array $composer ): array|WP_Error {
		$assets = array();
		foreach ( $composer['images'] as $id ) {
			$image = self::media( $id, $service );
			if ( $image['error'] ) { return new WP_Error( 'social_media_prepare', $image['error'] ); }
			$assets[] = array( 'image' => array( 'url' => $image['url'], 'metadata' => array( 'altText' => $image['alt'] ) ) );
		}
		$input = array(
			'channelId' => $channel, 'text' => $composer['text'], 'schedulingType' => 'automatic',
			'mode' => 'scheduled' === $composer['mode'] ? 'customScheduled' : 'shareNow',
			'metadata' => array( $service => array( 'type' => 'post' ) ),
			'assets' => $assets,
		);
		if ( 'instagram' === $service ) { $input['metadata']['instagram']['shouldShareToFeed'] = true; }
		if ( 'scheduled' === $composer['mode'] ) { $input['dueAt'] = self::scheduled_date( $composer['scheduled_at'] )->setTimezone( new DateTimeZone( 'UTC' ) )->format( 'Y-m-d\TH:i:s\Z' ); }
		return $input;
	}

	/** Persist before each mutation: an interrupted/ambiguous request must not be repeated. */
	public static function send( WP_Post $post, array $composer ): array|WP_Error {
		$live = self::is_live();
		$valid = self::validate_send( $post, $composer, $live );
		if ( is_wp_error( $valid ) ) { return $valid; }
		if ( ! $live ) { return array( 'simulation' => true, 'message' => 'Simulazione riuscita. Nessuna richiesta di pubblicazione inviata e nessun post creato sui social.' ); }
		$lock = self::lock( $post->ID );
		if ( is_wp_error( $lock ) ) { return $lock; }
		try {
			$settings = Fisar_CDJ_Social_Settings::values();
			$channels = Fisar_CDJ_Buffer_Client::discover_channels();
			if ( is_wp_error( $channels ) ) { return $channels; }
			$delivery = self::deliveries( $post->ID );
			// Validate every destination before sending to the first one.
			foreach ( self::NETWORKS as $service => $network ) {
				if ( ! $composer['networks'][ $service ] || ! empty( $delivery[ $service ]['id'] ) || 'unknown' === ( $delivery[ $service ]['status'] ?? '' ) ) { continue; }
				$found = false;
				foreach ( $channels as $channel ) { if ( $service === $channel['service'] && ( $settings[ $service ] ?? '' ) === $channel['id'] && $channel['available'] ) { $found = true; } }
				if ( ! $found ) { return new WP_Error( 'social_channel', 'Configura o ricollega l’account ' . $network['label'] . ' nelle impostazioni Pubblicazione social.' ); }
			}
			foreach ( self::NETWORKS as $service => $network ) {
				if ( ! $composer['networks'][ $service ] || ! empty( $delivery[ $service ]['id'] ) || 'unknown' === ( $delivery[ $service ]['status'] ?? '' ) ) { continue; }
				$payload = self::payload( $service, $settings[ $service ], $composer );
				if ( is_wp_error( $payload ) ) { return $payload; }
				$delivery[ $service ] = array( 'status' => 'unknown', 'message' => 'Invio avviato. Se l’esito non arriva, verifica in Buffer prima di riprovare.', 'channel' => $settings[ $service ], 'at' => gmdate( 'c' ) );
				update_post_meta( $post->ID, self::DELIVERY_META, wp_slash( $delivery ) );
				$result = Fisar_CDJ_Buffer_Client::create_post( $payload );
				if ( is_wp_error( $result ) ) {
					$delivery[ $service ]['status'] = 'buffer_uncertain' === $result->get_error_code() ? 'unknown' : 'rejected';
					$delivery[ $service ]['message'] = $result->get_error_message();
				} else { $delivery[ $service ] = self::delivery_record( $result, $delivery[ $service ] ); }
				update_post_meta( $post->ID, self::DELIVERY_META, wp_slash( $delivery ) );
				self::save_social_link( $post->ID, $service, $delivery[ $service ] );
			}
			return array( 'delivery' => $delivery );
		} finally { self::unlock( $post->ID, $lock ); }
	}

	public static function refresh( int $post_id ): array|WP_Error {
		$lock = self::lock( $post_id );
		if ( is_wp_error( $lock ) ) { return $lock; }
		try {
			$delivery = self::deliveries( $post_id );
			foreach ( $delivery as $service => &$entry ) {
				if ( empty( $entry['id'] ) || 'sent' === ( $entry['status'] ?? '' ) ) { continue; }
				$result = Fisar_CDJ_Buffer_Client::post( $entry['id'] );
				if ( is_wp_error( $result ) ) { $entry['message'] = $result->get_error_message(); }
				else { $entry = self::delivery_record( $result, $entry ); self::save_social_link( $post_id, $service, $entry ); }
			}
			unset( $entry );
			update_post_meta( $post_id, self::DELIVERY_META, wp_slash( $delivery ) );
			return $delivery;
		} finally { self::unlock( $post_id, $lock ); }
	}

	/** One lock covers both publication and status reads that update the delivery ledger. */
	private static function lock( int $post_id ): string|WP_Error {
		$name = 'fisar_social_lock_' . $post_id;
		$token = time() . ':' . wp_generate_uuid4();
		if ( add_option( $name, $token, '', false ) ) { return $token; }
		$previous = (string) get_option( $name );
		if ( time() - (int) $previous > 600 ) {
			self::unlock( $post_id, $previous );
			if ( add_option( $name, $token, '', false ) ) { return $token; }
		}
		return new WP_Error( 'social_busy', 'Un’operazione social è già in corso. Attendi e aggiorna gli esiti.' );
	}

	/** Conditional removal prevents a slow request from deleting another request's lock. */
	private static function unlock( int $post_id, string $token ): void {
		global $wpdb;
		$name = 'fisar_social_lock_' . $post_id;
		$wpdb->delete( $wpdb->options, array( 'option_name' => $name, 'option_value' => $token ) );
		wp_cache_delete( $name, 'options' );
	}

	private static function delivery_record( array $remote, array $previous ): array {
		return array_merge( $previous, array(
			'id' => (string) $remote['id'], 'status' => (string) $remote['status'],
			'dueAt' => $remote['dueAt'] ?? '', 'url' => esc_url_raw( $remote['externalLink'] ?? '' ),
			'message' => Fisar_CDJ_Buffer_Client::safe_message( $remote['error']['message'] ?? '' ),
		) );
	}

	private static function save_social_link( int $post_id, string $service, array $delivery ): void {
		$key = '_fisar_report_' . $service . '_url';
		if ( 'sent' === ( $delivery['status'] ?? '' ) && ! empty( $delivery['url'] ) && ! get_post_meta( $post_id, $key, true ) ) {
			update_post_meta( $post_id, $key, $delivery['url'] );
		}
	}
}
