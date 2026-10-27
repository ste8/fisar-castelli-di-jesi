<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Read-only editorial export: saved content without automatic date-based closure. */
final class Fisar_CDJ_Event_WhatsApp_Message {
	public const ACTION = 'fisar_cdj_event_whatsapp_message';

	public static function init(): void {
		add_action( 'wp_ajax_' . self::ACTION, array( self::class, 'ajax' ) );
		add_action( 'admin_enqueue_scripts', array( self::class, 'enqueue_assets' ) );
	}

	public static function enqueue_assets( string $hook ): void {
		$screen = get_current_screen();
		if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) || ! $screen || Fisar_CDJ_Post_Types::EVENT !== $screen->post_type ) {
			return;
		}
		$dependencies = $screen->is_block_editor() ? array( 'wp-dom-ready', 'wp-data', 'wp-editor', 'wp-api-fetch' ) : array( 'wp-dom-ready' );
		wp_enqueue_script( 'fisar-cdj-event-whatsapp', FISAR_CDJ_CORE_URL . 'assets/js/event-whatsapp-message.js', $dependencies, FISAR_CDJ_CORE_VERSION, true );
	}

	public static function render( WP_Post $post ): void {
		?>
		<p>Genera un messaggio con descrizione, data, luogo, quote e informazioni per prenotare.</p>
		<p><strong>Salva prima le modifiche all’evento:</strong> il messaggio usa i dati salvati.</p>
		<button type="button" class="button button-secondary" id="fisar-whatsapp-message-open" data-post-id="<?php echo esc_attr( $post->ID ); ?>" data-saved="<?php echo 'auto-draft' === $post->post_status ? '0' : '1'; ?>" data-url="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( self::ACTION ) ); ?>">Genera messaggio WhatsApp</button>
		<noscript><p>Per generare e copiare il messaggio è necessario JavaScript.</p></noscript>
		<dialog id="fisar-whatsapp-message-dialog" class="fisar-message-dialog" aria-labelledby="fisar-message-title" aria-describedby="fisar-message-help">
			<div class="fisar-message-dialog__header">
				<h2 id="fisar-message-title" tabindex="-1">Messaggio WhatsApp</h2>
				<button type="button" class="button" id="fisar-message-close" aria-label="Chiudi il messaggio WhatsApp">Chiudi</button>
			</div>
			<p id="fisar-message-help">Generato dai dati salvati. Puoi modificare il messaggio qui prima di copiarlo: queste modifiche non cambiano l’evento. Il grassetto usa * e il corsivo _.</p>
			<div id="fisar-message-status" role="status" aria-live="polite"></div>
			<ul id="fisar-message-warnings" class="fisar-message-warnings" hidden></ul>
			<div class="fisar-message-dialog__panes">
				<div>
					<label for="fisar-message-text"><strong>Messaggio da copiare</strong></label>
					<textarea id="fisar-message-text" rows="16" spellcheck="true" disabled></textarea>
				</div>
				<section aria-labelledby="fisar-message-preview-title">
					<h3 id="fisar-message-preview-title">Anteprima della formattazione</h3>
					<div id="fisar-message-preview" class="fisar-message-preview"></div>
				</section>
			</div>
			<div class="fisar-message-dialog__actions">
				<button type="button" class="button button-primary" id="fisar-message-copy" disabled>Copia messaggio</button>
			</div>
		</dialog>
		<?php
	}

	public static function ajax(): void {
		nocache_headers();
		$result = self::request( wp_unslash( $_POST ) );
		if ( is_wp_error( $result ) ) {
			wp_send_json_error( array( 'message' => $result->get_error_message() ), 403 );
		}
		wp_send_json_success( $result );
	}

	/** Separate validation from transport so authorization can be regression-tested. */
	public static function request( array $request ): array|WP_Error {
		$id = is_scalar( $request['post_id'] ?? null ) ? absint( $request['post_id'] ) : 0;
		$nonce = is_string( $request['nonce'] ?? null ) ? $request['nonce'] : '';
		if ( ! wp_verify_nonce( $nonce, self::ACTION ) || ! $id || Fisar_CDJ_Post_Types::EVENT !== get_post_type( $id ) || ! current_user_can( 'edit_post', $id ) ) {
			return new WP_Error( 'forbidden', 'Non è possibile generare il messaggio. Verifica l’accesso e ricarica l’editor dopo aver salvato le modifiche.' );
		}
		return self::generate( get_post( $id ) );
	}

	public static function generate( WP_Post $post ): array {
		$id = $post->ID;
		$meta = static fn( string $key ): string => (string) get_post_meta( $id, '_fisar_event_' . $key, true );
		$warnings = array();
		$sections = array( '🍷 *' . mb_strtoupper( self::plain( $post->post_title ?: 'Evento FISAR Castelli di Jesi' ), 'UTF-8' ) . '*' );
		$body = self::from_html( $post->post_content );
		$sections[] = $body ?: self::from_html( $post->post_excerpt );
		if ( preg_match( '/<(?:img|iframe|video|audio)\b|<!-- wp:(?:gallery|embed|shortcode)\b/i', $post->post_content ) ) {
			$warnings[] = 'Immagini, gallerie e contenuti incorporati non vengono copiati: allega separatamente l’eventuale locandina.';
		}
		$date = self::date( $meta( 'date' ) );
		$info = array( '*ℹ️ DATA E LUOGO*' );
		if ( $date ) {
			$info[] = '📅 ' . $date;
		} else {
			$warnings[] = 'La data dell’evento manca o non è valida: completala prima di condividere il messaggio.';
		}
		$meeting = fisar_cdj_get_event_meeting( $id );
		if ( $meeting['has_details'] ) {
			if ( $meeting['time'] ) { $info[] = '🕒 *Ritrovo:* ore ' . $meeting['time']; }
			$place = self::place( $meeting['venue'], $meeting['address'], $meeting['city'] );
			if ( $place ) { $info[] = '📍 *Punto di ritrovo:* ' . $place; }
			if ( $meeting['maps_url'] ) { $info[] = 'Posizione del ritrovo: ' . $meeting['maps_url']; }
			if ( $meeting['notes'] ) { $info[] = "\n" . self::plain( $meeting['notes'] ); }
		}
		$times = array();
		foreach ( array( 'welcome_time' => 'Accoglienza dalle', 'start_time' => 'Inizio ore', 'end_time' => 'Fine ore' ) as $key => $label ) {
			if ( preg_match( '/^(?:[01]\d|2[0-3]):[0-5]\d$/', $meta( $key ) ) ) { $times[] = $label . ' ' . $meta( $key ); }
		}
		if ( $times ) { $info[] = '🕒 ' . implode( ' · ', $times ); }
		$mode = $meta( 'mode' );
		if ( 'online' !== $mode ) {
			$place = self::place( $meta( 'venue' ), $meta( 'address' ), fisar_cdj_format_city( $meta( 'city' ), $meta( 'province' ) ) );
			if ( 'hybrid' === $mode ) { $info[] = '*Evento ibrido: in presenza e online*'; }
			if ( $place ) { $info[] = '📍 *' . ( $meeting['has_details'] ? 'Luogo dell’evento' : 'Luogo' ) . ':* ' . $place; }
			$maps = esc_url_raw( $meta( 'maps_url' ), array( 'https', 'http' ) );
			if ( $maps ) { $info[] = 'Posizione: ' . $maps; }
		} else {
			$info[] = '📍 *Online*';
		}
		$online = fisar_cdj_get_event_online_access( $id, ignore_elapsed_dates: true );
		if ( $online['platform'] ) { $info[] = 'Piattaforma: ' . self::plain( $online['platform'] ); }
		if ( $online['url'] ) { $info[] = 'Partecipa online: ' . $online['url']; }
		if ( count( $info ) > 1 ) { $sections[] = implode( "\n", $info ); }

		$participation = match ( $meta( 'participation' ) ) {
			'all' => 'La partecipazione è aperta a tutti, anche a chi non è socio FISAR.',
			'members' => 'La partecipazione è riservata ai soci FISAR.',
			'members_and_companions' => 'La partecipazione è riservata ai soci FISAR e ai loro accompagnatori.',
			default => '',
		};
		if ( $participation ) { $sections[] = "*👥 PARTECIPAZIONE*\n" . $participation; }
		$fees = fisar_cdj_get_event_fees( $id );
		if ( $fees['has_fees'] ) {
			$lines = array( '*💵 QUOTE DI PARTECIPAZIONE*' );
			if ( $fees['free'] ) {
				$lines[] = 'Ingresso gratuito.';
			} else {
				foreach ( array( 'member' => 'Soci FISAR', 'nonmember' => 'Non soci' ) as $key => $label ) {
					if ( '' !== $fees[ $key ] ) { $lines[] = '- ' . $label . ': ' . self::amount( $fees[ $key ] ); }
				}
				foreach ( $fees['options'] as $option ) {
					$lines[] = '- ' . self::plain( $option['label'] ) . ': ' . self::amount( $option['amount'] );
					if ( $option['note'] ) { $lines[] = '  ' . self::plain( $option['note'] ); }
				}
				if ( $fees['note'] ) { $lines[] = "\n" . self::plain( $fees['note'] ); }
			}
			$sections[] = implode( "\n", $lines );
		}

		$details = fisar_cdj_get_event_registration_details( $id, ignore_elapsed_dates: true );
		$required = (bool) get_post_meta( $id, '_fisar_event_registration_required', true );
		if ( $required && $details['status_notice'] ) {
			$status_notice = $details['waiting_list'] ? 'SOLD-OUT - LISTA D’ATTESA' : $details['status_notice'];
			$sections[] = '*⚠️ ' . $status_notice . '*' . ( $details['availability_notice'] ? "\n" . $details['availability_notice'] : '' );
		}
		if ( $required && ! $details['closed'] ) {
			$deadline = mb_strtoupper( self::date( $details['deadline'], 'l j F' ), 'UTF-8' );
			if ( $deadline ) {
				$sections[] = '*⚠️ PRENOTAZIONI ENTRO ' . $deadline . ( $details['waiting_list'] ? ' (LISTA DI ATTESA)' : '' ) . '*' . ( $details['deadline_notice'] ? "\n" . $details['deadline_notice'] : '' );
			}
			if ( $details['limited_seats_notice'] ) { $sections[] = "*⚠️ POSTI LIMITATI*\n" . $details['limited_seats_notice']; }
			$contacts = array();
			$icons = array( 'WhatsApp' => '📱', 'Email' => '✉️', 'Telefono' => '☎️', 'Modulo online' => '🔗' );
			foreach ( fisar_cdj_get_registration_channels( $id, 'event', ignore_elapsed_dates: true ) as $channel ) {
				$reference = $channel['reference'];
				if ( 'WhatsApp' === $channel['reference_label'] ) { $reference = preg_replace( '/^(?:\+39|0039)\s*/', '', $reference ); }
				$contacts[] = ( $icons[ $channel['reference_label'] ] ?? '🔗' ) . ' ' . $channel['reference_label'] . ': ' . self::plain( trim( ( $channel['name'] ?? '' ) . ' ' . $reference ) );
			}
			if ( $contacts ) {
				$sections[] = "*👉 INFO E PRENOTAZIONI*\n" . implode( "\n", $contacts );
			} else {
				$warnings[] = 'È richiesta la prenotazione ma non ci sono canali di contatto: completali prima di condividere il messaggio.';
			}
			if ( ! $details['waiting_list'] ) { $sections[] = self::from_html( $meta( 'registration_notes' ) ); }
		} elseif ( ! $required ) {
			$sections[] = '*Non è richiesta la prenotazione.*';
		}
		if ( 'publish' === $post->post_status && ! $post->post_password ) {
			$sections[] = "*🌐 TROVI I DETTAGLI ANCHE SUL SITO*\n" . get_permalink( $post );
		} else {
			$warnings[] = 'L’evento non è pubblicato liberamente: il link alla pagina non è stato incluso.';
		}
		$message = implode( "\n\n", array_filter( $sections, static fn( string $section ): bool => '' !== trim( $section ) ) );
		if ( mb_strlen( $message ) > 4000 ) { $warnings[] = 'Il messaggio è lungo: valuta di accorciarlo nell’anteprima, senza perdere le informazioni pratiche.'; }
		return array( 'message' => $message, 'warnings' => $warnings );
	}

	private static function plain( string $text ): string {
		return trim( html_entity_decode( wp_strip_all_tags( $text ), ENT_QUOTES | ENT_HTML5, 'UTF-8' ) );
	}

	private static function amount( string $amount ): string {
		$amount = self::plain( $amount );
		return preg_match( '/^\d+(?:[.,]\d{1,2})?$/', $amount ) ? $amount . ' €' : $amount;
	}

	private static function place( string $venue, string $address, string $city ): string {
		return implode( ' – ', array_filter( array_map( array( self::class, 'plain' ), array( $venue, $address, $city ) ) ) );
	}

	private static function date( string $value, string $format = 'l j F Y' ): string {
		$date = DateTimeImmutable::createFromFormat( '!Y-m-d', $value, wp_timezone() );
		return $date && $date->format( 'Y-m-d' ) === $value ? wp_date( $format, $date->getTimestamp(), wp_timezone() ) : '';
	}

	/** Do not render blocks, shortcodes or embeds: exporting must never execute content. */
	public static function from_html( string $html ): string {
		$html = wpautop( strip_shortcodes( $html ) );
		if ( ! class_exists( 'DOMDocument' ) ) {
			return self::plain( preg_replace( '#</(?:p|div|h[1-6]|li)>|<br\s*/?>#i', "\n", $html ) );
		}
		$previous = libxml_use_internal_errors( true );
		try {
			$dom = new DOMDocument();
			$dom->loadHTML( '<!doctype html><html><head><meta charset="UTF-8"></head><body>' . $html . '</body></html>', LIBXML_NONET );
			$text = self::node_text( $dom->getElementsByTagName( 'body' )->item( 0 ) );
		} finally {
			libxml_clear_errors();
			libxml_use_internal_errors( $previous );
		}
		$text = preg_replace( '/\n[\t ]*\n(?:[\t ]*\n)+/', "\n\n", $text );
		// A standalone bold line is a section title, including titles authored as paragraphs.
		return trim( preg_replace( '/(^|\n)([\t ]*[\p{So}\p{Mn}\x{200D}\x{FE0F}\t ]*\*[^*\n]+\*[\t ]*)\n(?:[\t ]*\n)+/u', '$1$2' . "\n", $text ) );
	}

	private static function node_text( ?DOMNode $node, string $active_markers = '' ): string {
		if ( ! $node ) { return ''; }
		if ( XML_TEXT_NODE === $node->nodeType ) {
			if ( '' === trim( $node->nodeValue ) && in_array( strtolower( $node->parentNode->nodeName ), array( 'body', 'section', 'ul', 'ol' ), true ) ) { return ''; }
			return preg_replace( '/\s+/u', ' ', $node->nodeValue );
		}
		if ( XML_ELEMENT_NODE !== $node->nodeType ) { return ''; }
		$tag = strtolower( $node->nodeName );
		if ( in_array( $tag, array( 'script', 'style', 'iframe', 'img', 'video', 'audio', 'noscript', 'template' ), true ) ) { return ''; }
		if ( 'br' === $tag ) { return "\n"; }
		if ( in_array( $tag, array( 'ul', 'ol' ), true ) ) {
			$lines = array();
			$index = max( 1, (int) $node->getAttribute( 'start' ) );
			foreach ( $node->childNodes as $item ) {
				if ( 'li' !== strtolower( $item->nodeName ) ) { continue; }
				$lines[] = ( 'ol' === $tag ? $index++ . '. ' : '- ' ) . trim( self::node_text( $item, $active_markers ) );
			}
			return "\n" . implode( "\n", $lines ) . "\n\n";
		}
		$is_heading = in_array( $tag, array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' ), true );
		$marker = $is_heading || in_array( $tag, array( 'strong', 'b' ), true ) ? '*' : ( in_array( $tag, array( 'em', 'i' ), true ) ? '_' : '' );
		$add_marker = '' !== $marker && ! str_contains( $active_markers, $marker );
		$text = '';
		foreach ( $node->childNodes as $child ) { $text .= self::node_text( $child, $active_markers . $marker ); }
		if ( $marker ) {
			$text = trim( $text );
			$wrap = $add_marker ? $marker : '';
			return '' === $text ? '' : ( $is_heading ? "\n\n" : '' ) . $wrap . $text . $wrap . ( $is_heading ? "\n\n" : '' );
		}
		if ( 'a' === $tag ) {
			$url = esc_url_raw( $node->getAttribute( 'href' ), array( 'http', 'https' ) );
			return $url && trim( $text ) !== $url ? trim( $text ) . ' (' . $url . ')' : $text;
		}
		if ( in_array( $tag, array( 'td', 'th' ), true ) ) { return trim( $text ) . ' | '; }
		return in_array( $tag, array( 'p', 'div', 'blockquote', 'figure', 'section', 'table', 'tr' ), true ) ? trim( $text ) . "\n\n" : $text;
	}
}
