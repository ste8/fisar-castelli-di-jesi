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

function fisar_cdj_get_event_registration_copy( int $event_id ): array {
	$is_free      = (bool) get_post_meta( $event_id, '_fisar_event_is_free', true );
	$is_required  = (bool) get_post_meta( $event_id, '_fisar_event_registration_required', true );
	$deadline     = (string) get_post_meta( $event_id, '_fisar_event_deadline', true );
	$deadline_type = (string) get_post_meta( $event_id, '_fisar_event_deadline_type', true );
	$lines        = array();

	if ( 'all' === get_post_meta( $event_id, '_fisar_event_participation', true ) ) {
		$lines[] = 'La partecipazione è aperta a tutti, anche a chi non è socio FISAR.';
	}

	if ( $is_free && $is_required ) {
		$lines[] = 'INGRESSO GRATUITO, PRENOTAZIONE OBBLIGATORIA';
	} elseif ( $is_free ) {
		$lines[] = 'La partecipazione è libera, non è richiesta la prenotazione.';
	}

	if ( '' !== $deadline ) {
		$lines[] = sprintf( 'Prenotazioni entro il %s.', wp_date( 'j F Y', strtotime( $deadline ) ) );
		if ( 'flexible' === $deadline_type ) {
			$lines[] = 'Dopo tale termine sarà comunque possibile contattarci, ma non potremo garantire la disponibilità.';
		}
	}

	return $lines;
}

function fisar_cdj_get_registration_channels( int $post_id, string $prefix ): array {
	$definitions = array(
		'whatsapp'     => array( 'label' => 'Prenota su WhatsApp', 'type' => 'url' ),
		'email'        => array( 'label' => 'Scrivi una email', 'type' => 'email' ),
		'phone'        => array( 'label' => 'Chiama per informazioni', 'type' => 'phone' ),
		'form_url'     => array( 'label' => 'Compila il modulo di iscrizione', 'type' => 'url' ),
		'other_channel'=> array( 'label' => 'Altro canale di iscrizione', 'type' => 'text' ),
	);
	$channels = array();

	foreach ( $definitions as $suffix => $definition ) {
		$value = trim( (string) get_post_meta( $post_id, "_fisar_{$prefix}_{$suffix}", true ) );
		if ( '' === $value ) {
			continue;
		}

		$url = '';
		if ( 'email' === $definition['type'] ) {
			$url = 'mailto:' . sanitize_email( $value );
		} elseif ( 'phone' === $definition['type'] ) {
			$url = 'tel:' . preg_replace( '/[^0-9+]/', '', $value );
		} elseif ( 'url' === $definition['type'] ) {
			$url = esc_url_raw( $value );
		}

		$channels[] = array(
			'label' => $definition['label'],
			'value' => $value,
			'url'   => $url,
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
 * Reindirizza il precedente URL della pagina canali verso lo slug corrente.
 */
function fisar_cdj_redirect_legacy_follow_page(): void {
	if ( is_admin() || ! is_404() ) {
		return;
	}

	$request_method = isset( $_SERVER['REQUEST_METHOD'] ) ? strtoupper( sanitize_text_field( wp_unslash( $_SERVER['REQUEST_METHOD'] ) ) ) : '';
	if ( ! in_array( $request_method, array( 'GET', 'HEAD' ), true ) ) {
		return;
	}

	$request_uri  = isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
	$request_path = (string) wp_parse_url( $request_uri, PHP_URL_PATH );
	$legacy_path  = (string) wp_parse_url( home_url( '/resta-aggiornato/' ), PHP_URL_PATH );
	if ( untrailingslashit( $request_path ) !== untrailingslashit( $legacy_path ) ) {
		return;
	}

	$page        = get_page_by_path( 'seguici' );
	$destination = $page ? get_permalink( $page ) : home_url( '/seguici/' );
	wp_safe_redirect( $destination, 301, 'FISAR Castelli di Jesi' );
	exit;
}
add_action( 'template_redirect', 'fisar_cdj_redirect_legacy_follow_page', 1 );
