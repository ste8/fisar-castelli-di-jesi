<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function fisar_cdj_today(): string {
	return current_datetime()->format( 'Y-m-d' );
}

function fisar_cdj_is_event_past( int|WP_Post $event ): bool {
	$event_id = $event instanceof WP_Post ? $event->ID : $event;
	$date     = (string) get_post_meta( $event_id, '_fisar_event_date', true );

	return '' !== $date && $date < fisar_cdj_today();
}

function fisar_cdj_is_course_active( int|WP_Post $course ): bool {
	$course_id = $course instanceof WP_Post ? $course->ID : $course;
	$end_date  = (string) get_post_meta( $course_id, '_fisar_course_end_date', true );

	return '' !== $end_date && $end_date >= fisar_cdj_today();
}

function fisar_cdj_get_upcoming_events( int $limit = -1 ): WP_Query {
	return new WP_Query(
		array(
			'post_type'      => Fisar_CDJ_Post_Types::EVENT,
			'post_status'    => 'publish',
			'posts_per_page' => $limit,
			'meta_key'       => '_fisar_event_date',
			'orderby'        => 'meta_value',
			'order'          => 'ASC',
			'meta_query'     => array(
				array(
					'key'     => '_fisar_event_date',
					'value'   => fisar_cdj_today(),
					'compare' => '>=',
					'type'    => 'DATE',
				),
			),
		)
	);
}

function fisar_cdj_get_past_events( int $limit = -1 ): WP_Query {
	return new WP_Query(
		array(
			'post_type'      => Fisar_CDJ_Post_Types::EVENT,
			'post_status'    => 'publish',
			'posts_per_page' => $limit,
			'meta_key'       => '_fisar_event_date',
			'orderby'        => 'meta_value',
			'order'          => 'DESC',
			'meta_query'     => array(
				array(
					'key'     => '_fisar_event_date',
					'value'   => fisar_cdj_today(),
					'compare' => '<',
					'type'    => 'DATE',
				),
			),
		)
	);
}

function fisar_cdj_get_active_courses( int $limit = -1 ): WP_Query {
	return new WP_Query(
		array(
			'post_type'      => Fisar_CDJ_Post_Types::COURSE,
			'post_status'    => 'publish',
			'posts_per_page' => $limit,
			'meta_key'       => '_fisar_course_start_date',
			'orderby'        => 'meta_value',
			'order'          => 'ASC',
			'meta_query'     => array(
				array(
					'key'     => '_fisar_course_end_date',
					'value'   => fisar_cdj_today(),
					'compare' => '>=',
					'type'    => 'DATE',
				),
			),
		)
	);
}

function fisar_cdj_get_past_courses( int $limit = -1 ): WP_Query {
	return new WP_Query(
		array(
			'post_type'      => Fisar_CDJ_Post_Types::COURSE,
			'post_status'    => 'publish',
			'posts_per_page' => $limit,
			'meta_key'       => '_fisar_course_end_date',
			'orderby'        => 'meta_value',
			'order'          => 'DESC',
			'meta_query'     => array(
				array(
					'key'     => '_fisar_course_end_date',
					'value'   => fisar_cdj_today(),
					'compare' => '<',
					'type'    => 'DATE',
				),
			),
		)
	);
}

function fisar_cdj_get_course_events( int $course_id ): WP_Query {
	return new WP_Query(
		array(
			'post_type'      => Fisar_CDJ_Post_Types::EVENT,
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'meta_key'       => '_fisar_event_date',
			'orderby'        => 'meta_value',
			'order'          => 'ASC',
			'meta_query'     => array(
				array(
					'key'     => '_fisar_event_course_id',
					'value'   => $course_id,
					'compare' => '=',
					'type'    => 'NUMERIC',
				),
			),
		)
	);
}

function fisar_cdj_get_course_calendar( int $course_id ): array {
	return Fisar_CDJ_Calendar_Importer::sanitize_rows( get_post_meta( $course_id, '_fisar_course_calendar', true ) );
}

/** Optional fee choices: ignore incomplete rows and never infer amounts or conditions. */
function fisar_cdj_sanitize_event_fee_options( mixed $options ): array {
	if ( ! is_array( $options ) ) {
		return array();
	}

	$clean_options = array();
	foreach ( $options as $option ) {
		if ( ! is_array( $option ) ) {
			continue;
		}
		$label  = is_string( $option['label'] ?? null ) ? sanitize_text_field( $option['label'] ) : '';
		$amount = is_string( $option['amount'] ?? null ) ? sanitize_text_field( $option['amount'] ) : '';
		$note   = is_string( $option['note'] ?? null ) ? sanitize_textarea_field( $option['note'] ) : '';
		if ( '' === $label || '' === $amount ) {
			continue;
		}
		$clean_options[] = array( 'label' => $label, 'amount' => $amount, 'note' => $note );
	}

	return $clean_options;
}

/** Keep legacy fees compatible while exposing optional, editorially defined choices. */
function fisar_cdj_get_event_fees( int $event_id ): array {
	$is_free = (bool) get_post_meta( $event_id, '_fisar_event_is_free', true );
	$note    = get_post_meta( $event_id, '_fisar_event_fee_note', true );
	$fees    = array(
		'free'      => $is_free,
		'member'    => $is_free ? '' : (string) get_post_meta( $event_id, '_fisar_event_member_price', true ),
		'nonmember' => $is_free ? '' : (string) get_post_meta( $event_id, '_fisar_event_non_member_price', true ),
		'options'   => $is_free ? array() : fisar_cdj_sanitize_event_fee_options( get_post_meta( $event_id, '_fisar_event_fee_options', true ) ),
		'note'      => ! $is_free && is_string( $note ) ? sanitize_textarea_field( $note ) : '',
	);
	$fees['has_fees'] = $is_free || '' !== $fees['member'] || '' !== $fees['nonmember'] || ! empty( $fees['options'] ) || '' !== $fees['note'];

	return $fees;
}

/** Registration data shared by summaries and booking panels; no closing rule is inferred. */
function fisar_cdj_get_event_registration_details( int $event_id ): array {
	$is_free      = (bool) get_post_meta( $event_id, '_fisar_event_is_free', true );
	$is_required  = (bool) get_post_meta( $event_id, '_fisar_event_registration_required', true );
	$deadline     = (string) get_post_meta( $event_id, '_fisar_event_deadline', true );
	$deadline_type = (string) get_post_meta( $event_id, '_fisar_event_deadline_type', true );
	$lines        = array();
	$open_participation_notice = '';

	if ( 'all' === get_post_meta( $event_id, '_fisar_event_participation', true ) ) {
		$lines[] = 'La partecipazione è aperta a tutti, anche a chi non è socio FISAR.';
	}

	if ( $is_free && $is_required ) {
		$lines[] = 'INGRESSO GRATUITO, PRENOTAZIONE OBBLIGATORIA';
	} elseif ( $is_free ) {
		$open_participation_notice = 'La partecipazione è libera, non è richiesta la prenotazione.';
		$lines[] = $open_participation_notice;
	}

	$timestamp = $deadline ? strtotime( $deadline ) : false;
	return array(
		'lines'           => $lines,
		'open_participation_notice' => $open_participation_notice,
		'deadline'        => false !== $timestamp ? $deadline : '',
		'deadline_label'  => false !== $timestamp ? wp_date( 'j F Y', $timestamp ) : '',
		'deadline_notice' => false !== $timestamp && 'flexible' === $deadline_type
			? 'Dopo tale termine sarà comunque possibile contattarci per iscriversi, ma non potremo garantire la disponibilità.' : '',
	);
}

/** Preserve the original text API for callers that do not need structured data. */
function fisar_cdj_get_event_registration_copy( int $event_id ): array {
	$details = fisar_cdj_get_event_registration_details( $event_id );
	$lines   = $details['lines'];
	if ( $details['deadline'] ) {
		$lines[] = sprintf( 'Prenotazioni entro il %s.', $details['deadline_label'] );
		if ( $details['deadline_notice'] ) {
			$lines[] = $details['deadline_notice'];
		}
	}
	return $lines;
}

/**
 * Keep a readable contact even when an international chat link cannot be built.
 * A national number is never assigned a country prefix automatically.
 */
function fisar_cdj_get_whatsapp_contact( string $value ): array {
	$reference = trim( $value );
	$parts     = wp_parse_url( $reference );
	$host      = strtolower( $parts['host'] ?? '' );

	// Older URL inputs may have stored a national number as http://3351234567.
	if ( preg_match( '/^[0-9]{7,15}$/', $host ) && empty( $parts['query'] ) && empty( $parts['fragment'] ) && in_array( $parts['path'] ?? '', array( '', '/' ), true ) ) {
		$reference = $host;
	}

	if ( preg_match( '/^\+?[0-9\s().-]+$/', $reference ) ) {
		$number        = preg_replace( '/[^0-9]/', '', $reference );
		$international = str_starts_with( $reference, '+' ) || str_starts_with( $number, '00' );
		if ( str_starts_with( $number, '00' ) ) {
			$number = substr( $number, 2 );
		}
		return array(
			'reference' => $reference,
			'url'       => $international && preg_match( '/^[1-9][0-9]{6,14}$/', $number ) ? 'https://wa.me/' . $number : '',
		);
	}

	if ( 'wa.me' === $host && preg_match( '#^/([1-9][0-9]{6,14})/?$#', $parts['path'] ?? '', $matches ) ) {
		$reference = '+' . $matches[1];
	} elseif ( in_array( $host, array( 'api.whatsapp.com', 'web.whatsapp.com', 'whatsapp.com', 'www.whatsapp.com' ), true ) && '/send' === ( $parts['path'] ?? '' ) ) {
		parse_str( $parts['query'] ?? '', $query );
		$number = is_string( $query['phone'] ?? null ) ? ltrim( trim( $query['phone'] ), '+' ) : '';
		if ( preg_match( '/^[1-9][0-9]{6,14}$/', $number ) ) {
			$reference = '+' . $number;
		}
	}

	return array( 'reference' => $reference, 'url' => esc_url_raw( $value, array( 'http', 'https' ) ) );
}

function fisar_cdj_get_registration_channels( int $post_id, string $prefix ): array {
	$definitions = array(
		'whatsapp'     => array( 'label' => 'Prenota su WhatsApp', 'reference_label' => 'WhatsApp', 'type' => 'url' ),
		'email'        => array( 'label' => 'Scrivi una email', 'reference_label' => 'Email', 'type' => 'email' ),
		'phone'        => array( 'label' => 'Chiama per informazioni', 'reference_label' => 'Telefono', 'type' => 'phone' ),
		'form_url'     => array( 'label' => 'Compila il modulo di iscrizione', 'reference_label' => 'Modulo online', 'type' => 'url' ),
		'other_channel'=> array( 'label' => 'Altro canale di iscrizione', 'reference_label' => 'Altro canale', 'type' => 'text' ),
	);
	if ( 'event' === $prefix ) {
		$definitions['whatsapp']['label'] = 'Prenota via WhatsApp';
		$definitions['email']['label']    = 'Prenota via mail';
	}
	$channels = array();

	foreach ( $definitions as $suffix => $definition ) {
		$value = trim( (string) get_post_meta( $post_id, "_fisar_{$prefix}_{$suffix}", true ) );
		if ( '' === $value ) {
			continue;
		}

		$url       = '';
		$reference = $value;
		if ( 'whatsapp' === $suffix ) {
			$contact   = fisar_cdj_get_whatsapp_contact( $value );
			$url       = $contact['url'];
			$reference = $contact['reference'];
		} elseif ( 'email' === $definition['type'] ) {
			$url = 'mailto:' . sanitize_email( $value );
		} elseif ( 'phone' === $definition['type'] ) {
			$url = 'tel:' . preg_replace( '/[^0-9+]/', '', $value );
		} elseif ( 'url' === $definition['type'] ) {
			$url = esc_url_raw( $value );
		}

		$channels[] = array(
			'label'           => $definition['label'],
			'value'           => $value,
			'url'             => $url,
			'reference_label' => $definition['reference_label'],
			'reference'       => $reference,
		);
	}

	return $channels;
}

/**
 * Restituisce la configurazione pubblica del modulo newsletter Mailchimp.
 *
 * Gli identificativi del form non sono credenziali: servono esclusivamente a
 * indirizzare l'iscrizione verso l'Audience corretta. Nessuna API key viene
 * esposta o richiesta dal frontend.
 */
function fisar_cdj_get_newsletter_signup_config(): array {
	$config = array(
		'action'         => 'https://fisarcastellidijesi.us18.list-manage.com/subscribe/post?u=5a46e5d06783143549e3d9323&id=d1d5b0068e&f_id=00beade6f0',
		'honeypot_name' => 'b_5a46e5d06783143549e3d9323_d1d5b0068e',
	);

	/**
	 * Permette di sostituire la configurazione senza modificare il tema.
	 *
	 * @param array{action:string,honeypot_name:string} $config Configurazione del form.
	 */
	return apply_filters( 'fisar_cdj_newsletter_signup_config', $config );
}

/**
 * Conserva i collegamenti alle pagine native rinominate.
 */
function fisar_cdj_redirect_legacy_pages(): void {
	if ( is_admin() || ! is_404() ) {
		return;
	}

	$request_method = isset( $_SERVER['REQUEST_METHOD'] ) ? strtoupper( sanitize_text_field( wp_unslash( $_SERVER['REQUEST_METHOD'] ) ) ) : '';
	if ( ! in_array( $request_method, array( 'GET', 'HEAD' ), true ) ) {
		return;
	}

	$request_uri  = isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
	$request_path = (string) wp_parse_url( $request_uri, PHP_URL_PATH );
	$redirects = array( 'resta-aggiornato' => 'seguici', 'chi-siamo' => 'la-nostra-delegazione' );
	foreach ( $redirects as $legacy_slug => $current_slug ) {
		$legacy_path = (string) wp_parse_url( home_url( '/' . $legacy_slug . '/' ), PHP_URL_PATH );
		if ( untrailingslashit( $request_path ) !== untrailingslashit( $legacy_path ) ) {
			continue;
		}
		$page = get_page_by_path( $current_slug );
		if ( $page && 'publish' === $page->post_status ) {
			wp_safe_redirect( get_permalink( $page ), 301, 'FISAR Castelli di Jesi' );
			exit;
		}
	}
}
add_action( 'template_redirect', 'fisar_cdj_redirect_legacy_pages', 1 );
