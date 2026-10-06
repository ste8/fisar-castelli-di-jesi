<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Fisar_CDJ_Post_Types {
	public const EVENT = 'fisar_evento';
	public const COURSE = 'fisar_corso';

	public static function init(): void {
		add_action( 'init', array( self::class, 'register' ) );
	}

	public static function register(): void {
		register_post_type(
			self::EVENT,
			array(
				'labels'             => self::event_labels(),
				'public'             => true,
				'show_in_rest'       => true,
				'has_archive'        => 'eventi',
				'rewrite'            => array( 'slug' => 'eventi' ),
				'menu_icon'          => 'dashicons-calendar-alt',
				'menu_position'      => 5,
				'supports'           => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions' ),
				'publicly_queryable' => true,
				'show_ui'            => true,
				'show_in_nav_menus'  => true,
				'template'           => array(),
			)
		);

		register_post_type(
			self::COURSE,
			array(
				'labels'             => self::course_labels(),
				'public'             => true,
				'show_in_rest'       => true,
				'has_archive'        => 'corsi',
				'rewrite'            => array( 'slug' => 'corsi' ),
				'menu_icon'          => 'dashicons-welcome-learn-more',
				'menu_position'      => 6,
				'supports'           => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions' ),
				'publicly_queryable' => true,
				'show_ui'            => true,
				'show_in_nav_menus'  => true,
				'template'           => array(),
			)
		);

		self::register_meta_fields();
	}

	private static function register_meta_fields(): void {
		$event_fields = array(
			'_fisar_event_date'                  => 'string',
			'_fisar_event_welcome_time'          => 'string',
			'_fisar_event_start_time'            => 'string',
			'_fisar_event_end_time'              => 'string',
			'_fisar_event_mode'                  => 'string',
			'_fisar_event_venue'                 => 'string',
			'_fisar_event_address'               => 'string',
			'_fisar_event_city'                  => 'string',
			'_fisar_event_province'              => 'string',
			'_fisar_event_maps_url'              => 'string',
			'_fisar_event_platform'              => 'string',
			'_fisar_event_online_url'            => 'string',
			'_fisar_event_online_access_public'  => 'boolean',
			'_fisar_event_participation'         => 'string',
			'_fisar_event_is_free'               => 'boolean',
			'_fisar_event_member_price'          => 'string',
			'_fisar_event_non_member_price'      => 'string',
			'_fisar_event_fee_note'              => 'string',
			'_fisar_event_fee_options'           => 'array',
			'_fisar_event_registration_required' => 'boolean',
			'_fisar_event_booking_status'        => 'string',
			'_fisar_event_whatsapp'              => 'string',
			'_fisar_event_whatsapp_contacts'     => 'array',
			'_fisar_event_email'                 => 'string',
			'_fisar_event_phone'                 => 'string',
			'_fisar_event_form_url'              => 'string',
			'_fisar_event_other_channel'         => 'string',
			'_fisar_event_registration_notes'    => 'string',
			'_fisar_event_deadline'              => 'string',
			'_fisar_event_deadline_type'         => 'string',
			'_fisar_event_limited_seats'         => 'boolean',
			'_fisar_event_course_id'             => 'integer',
		);

		$course_fields = array(
			'_fisar_course_director'           => 'string',
			'_fisar_course_level'              => 'string',
			'_fisar_course_start_date'         => 'string',
			'_fisar_course_end_date'           => 'string',
			'_fisar_course_venue'              => 'string',
			'_fisar_course_address'            => 'string',
			'_fisar_course_city'               => 'string',
			'_fisar_course_province'           => 'string',
			'_fisar_course_whatsapp'           => 'string',
			'_fisar_course_email'              => 'string',
			'_fisar_course_phone'              => 'string',
			'_fisar_course_form_url'           => 'string',
			'_fisar_course_other_channel'      => 'string',
			'_fisar_course_registration_notes' => 'string',
			'_fisar_course_deadline'           => 'string',
			'_fisar_course_deadline_type'      => 'string',
			'_fisar_course_fee'                => 'string',
			'_fisar_course_membership'         => 'string',
			'_fisar_course_includes'           => 'string',
			'_fisar_course_calendar'           => 'array',
		);

		foreach ( $event_fields as $key => $type ) {
			self::register_single_meta( self::EVENT, $key, $type );
		}

		foreach ( $course_fields as $key => $type ) {
			self::register_single_meta( self::COURSE, $key, $type );
		}
	}

	private static function register_single_meta( string $post_type, string $key, string $type ): void {
		$rich_text_fields = array(
			'_fisar_event_registration_notes',
			'_fisar_course_registration_notes',
			'_fisar_course_fee',
			'_fisar_course_membership',
			'_fisar_course_includes',
		);
		$args = array(
			'single'            => true,
			'type'              => $type,
			'show_in_rest'      => false,
			'auth_callback'     => static fn(): bool => current_user_can( 'edit_posts' ),
			'sanitize_callback' => static function ( mixed $value ) use ( $type, $key, $rich_text_fields ): mixed {
				if ( '_fisar_event_online_access_public' === $key ) {
					return in_array( $value, array( true, 1, '1' ), true );
				}
				if ( '_fisar_event_booking_status' === $key ) {
					return fisar_cdj_sanitize_event_booking_status( $value );
				}
				if ( '_fisar_event_whatsapp_contacts' === $key ) {
					return fisar_cdj_sanitize_event_whatsapp_contacts( $value );
				}
				if ( '_fisar_event_whatsapp' === $key && is_string( $value ) && preg_match( '#^https?://#i', trim( $value ) ) ) {
					return esc_url_raw( trim( $value ), array( 'http', 'https' ) );
				}
				if ( '_fisar_event_fee_options' === $key ) {
					return fisar_cdj_sanitize_event_fee_options( $value );
				}
				if ( '_fisar_event_fee_note' === $key ) {
					return is_string( $value ) ? sanitize_textarea_field( $value ) : '';
				}
				if ( '_fisar_event_maps_url' === $key ) {
					return is_string( $value ) ? esc_url_raw( $value, array( 'http', 'https' ) ) : '';
				}
				if ( 'boolean' === $type ) {
					return (bool) $value;
				}
				if ( 'integer' === $type ) {
					return absint( $value );
				}
				if ( 'array' === $type ) {
					return is_array( $value ) ? $value : array();
				}
				if ( in_array( $key, $rich_text_fields, true ) ) {
					return is_string( $value ) ? wp_kses_post( $value ) : '';
				}

				return is_string( $value ) ? sanitize_text_field( $value ) : '';
			},
		);

		if ( '_fisar_event_online_access_public' === $key ) {
			$args['default'] = false;
		}
		register_post_meta( $post_type, $key, $args );
	}

	private static function event_labels(): array {
		return array(
			'name'                  => 'Eventi',
			'singular_name'         => 'Evento',
			'menu_name'             => 'Eventi',
			'name_admin_bar'        => 'Evento',
			'add_new'               => 'Aggiungi evento',
			'add_new_item'          => 'Aggiungi nuovo evento',
			'edit_item'             => 'Modifica evento',
			'new_item'              => 'Nuovo evento',
			'view_item'             => 'Visualizza evento',
			'view_items'            => 'Visualizza eventi',
			'search_items'          => 'Cerca eventi',
			'not_found'             => 'Nessun evento trovato.',
			'not_found_in_trash'    => 'Nessun evento nel cestino.',
			'all_items'             => 'Tutti gli eventi',
			'archives'              => 'Archivio eventi',
			'featured_image'        => 'Locandina o immagine evento',
			'set_featured_image'    => 'Imposta locandina o immagine',
			'remove_featured_image' => 'Rimuovi immagine',
		);
	}

	private static function course_labels(): array {
		return array(
			'name'                  => 'Corsi',
			'singular_name'         => 'Corso',
			'menu_name'             => 'Corsi',
			'name_admin_bar'        => 'Corso',
			'add_new'               => 'Aggiungi corso',
			'add_new_item'          => 'Aggiungi nuovo corso',
			'edit_item'             => 'Modifica corso',
			'new_item'              => 'Nuovo corso',
			'view_item'             => 'Visualizza corso',
			'view_items'            => 'Visualizza corsi',
			'search_items'          => 'Cerca corsi',
			'not_found'             => 'Nessun corso trovato.',
			'not_found_in_trash'    => 'Nessun corso nel cestino.',
			'all_items'             => 'Tutti i corsi',
			'archives'              => 'Archivio corsi',
			'featured_image'        => 'Immagine del corso',
			'set_featured_image'    => 'Imposta immagine del corso',
			'remove_featured_image' => 'Rimuovi immagine',
		);
	}
}
