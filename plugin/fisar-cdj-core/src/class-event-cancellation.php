<?php
/** Explicit refund conditions: no implied policy and no automatic payments. */
final class Fisar_CDJ_Event_Cancellation {
	public const META = '_fisar_event_cancellation';
	public const NONCE_ACTION = 'fisar_cdj_save_event_cancellation';
	public const NONCE_NAME = '_fisar_event_cancellation_nonce';
	public const MAX_RULES = 20;

	public static function init(): void {
		add_action( 'init', array( self::class, 'register_meta' ) );
		add_action( 'save_post_fisar_evento', array( self::class, 'save' ) );
	}

	public static function register_meta(): void {
		register_post_meta( 'fisar_evento', self::META, array(
			'type' => 'object', 'single' => true, 'show_in_rest' => false,
			'auth_callback' => static fn(): bool => current_user_can( 'edit_posts' ),
		) );
	}

	public static function percentage( mixed $value ): string {
		if ( ! is_scalar( $value ) || is_bool( $value ) ) { return ''; }
		$value = str_replace( ',', '.', trim( (string) $value ) );
		if ( ! preg_match( '/^\d{1,3}(?:\.\d{1,2})?$/', $value ) || (float) $value > 100 ) { return ''; }
		return rtrim( rtrim( number_format( (float) $value, 2, '.', '' ), '0' ), '.' );
	}

	public static function normalize( mixed $value ): array {
		$value = is_array( $value ) ? $value : array();
		$rules = array();
		foreach ( is_array( $value['rules'] ?? null ) ? $value['rules'] : array() as $row ) {
			if ( ! is_array( $row ) ) { $rules[] = array( 'date' => '', 'time' => '', 'percent' => '' ); continue; }
			if ( array() === array_filter( $row, static fn( $field ) => '' !== $field ) ) { continue; }
			$rules[] = array(
				'date' => Fisar_CDJ_Event_Calendar::date( $row['date'] ?? '' ),
				'time' => is_string( $row['time'] ?? null ) && preg_match( '/^(?:[01]\d|2[0-3]):[0-5]\d$/', $row['time'] ) ? $row['time'] : '',
				'percent' => self::percentage( $row['percent'] ?? '' ),
			);
		}
		usort( $rules, static fn( $a, $b ) => strcmp( self::boundary( $a ), self::boundary( $b ) ) );
		return array(
			'mode' => in_array( $value['mode'] ?? '', array( 'none', 'rules', 'text' ), true ) ? $value['mode'] : 'none',
			'rules' => $rules,
			'after' => in_array( $value['after'] ?? '', array( 'verify', 'none', 'percent' ), true ) ? $value['after'] : '',
			'after_percent' => self::percentage( $value['after_percent'] ?? '' ),
			'text' => is_string( $value['text'] ?? null ) ? wp_kses_post( $value['text'] ) : '',
			'notes' => is_string( $value['notes'] ?? null ) ? wp_kses_post( $value['notes'] ) : '',
		);
	}

	private static function boundary( array $row ): string {
		return $row['date'] . ' ' . ( $row['time'] ?: '23:59' );
	}

	public static function validate( mixed $raw ): array|WP_Error {
		if ( ! is_array( $raw ) || ! in_array( $raw['mode'] ?? null, array( 'none', 'rules', 'text' ), true ) ) {
			return new WP_Error( 'cancellation', 'Seleziona una modalità per le condizioni di cancellazione.' );
		}
		$policy = self::normalize( $raw );
		if ( 'text' === $policy['mode'] && '' === preg_replace( '/[\s\x{00A0}]+/u', '', html_entity_decode( wp_strip_all_tags( $policy['text'] ), ENT_QUOTES | ENT_HTML5, 'UTF-8' ) ) ) {
			return new WP_Error( 'cancellation', 'Compila il testo delle condizioni oppure scegli Non specificata.' );
		}
		if ( 'rules' !== $policy['mode'] ) { return $policy; }
		if ( ! is_array( $raw['rules'] ?? null ) || ! $policy['rules'] || count( $policy['rules'] ) > self::MAX_RULES ) {
			return new WP_Error( 'cancellation', 'Inserisci almeno una scadenza e non più di 20.' );
		}
		foreach ( $raw['rules'] as $row ) {
			if ( is_array( $row ) && '' !== ( $row['time'] ?? '' ) && ( ! is_string( $row['time'] ) || ! preg_match( '/^(?:[01]\d|2[0-3]):[0-5]\d$/', $row['time'] ) ) ) {
				return new WP_Error( 'cancellation', 'Inserisci un orario valido oppure lascia vuota l’ora.' );
			}
		}
		$previous = '';
		foreach ( $policy['rules'] as $row ) {
			if ( ! $row['date'] || '' === $row['percent'] ) {
				return new WP_Error( 'cancellation', 'Completa data e rimborso di ogni scadenza. La percentuale deve essere compresa tra 0 e 100.' );
			}
			$boundary = self::boundary( $row );
			if ( $boundary === $previous ) { return new WP_Error( 'cancellation', 'Due scadenze non possono coincidere. Senza ora il termine comprende l’intera data.' ); }
			$previous = $boundary;
		}
		if ( ! $policy['after'] || ( 'percent' === $policy['after'] && '' === $policy['after_percent'] ) ) {
			return new WP_Error( 'cancellation', 'Specifica il rimborso dopo l’ultima scadenza, oppure scegli Da verificare con la Delegazione.' );
		}
		return $policy;
	}

	public static function values( int $post_id ): array {
		return self::normalize( get_post_meta( $post_id, self::META, true ) );
	}

	public static function error_key( int $post_id ): string {
		return 'fisar_event_cancellation_error_' . get_current_user_id() . '_' . $post_id;
	}

	public static function save( int $post_id ): void {
		if ( 'fisar_evento' !== get_post_type( $post_id ) || wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id ) || ! current_user_can( 'edit_post', $post_id ) ) { return; }
		if ( ! isset( $_POST['_fisar_event_cancellation_present'], $_POST[ self::NONCE_NAME ], $_POST[ self::META ] ) || ! is_string( $_POST[ self::NONCE_NAME ] ) || ! wp_verify_nonce( wp_unslash( $_POST[ self::NONCE_NAME ] ), self::NONCE_ACTION ) ) { return; }
		$policy = self::validate( wp_unslash( $_POST[ self::META ] ) );
		if ( is_wp_error( $policy ) ) {
			set_transient( self::error_key( $post_id ), 'Condizioni di cancellazione non salvate: ' . $policy->get_error_message() . ' Restano valide le condizioni precedenti.', 300 );
			return;
		}
		delete_transient( self::error_key( $post_id ) );
		update_post_meta( $post_id, self::META, wp_slash( $policy ) );
	}

	private static function deadline_label( array $row ): string {
		$date = DateTimeImmutable::createFromFormat( '!Y-m-d', $row['date'], wp_timezone() );
		return wp_date( 'l j F Y', $date->getTimestamp(), wp_timezone() ) . ( $row['time'] ? ' alle ' . $row['time'] : '' );
	}

	private static function refund_label( string $percent ): string {
		return match ( $percent ) {
			'100' => 'rimborso completo',
			'0' => 'nessun rimborso',
			default => 'rimborso del ' . str_replace( '.', ',', $percent ) . '%',
		};
	}

	public static function presentation( int $post_id ): array {
		$policy = self::validate( get_post_meta( $post_id, self::META, true ) );
		$empty = array( 'lines' => array(), 'text' => '', 'notes' => '' );
		if ( is_wp_error( $policy ) || 'none' === $policy['mode'] ) { return $empty; }
		if ( 'text' === $policy['mode'] ) { return array( 'lines' => array(), 'text' => wpautop( $policy['text'] ), 'notes' => '' ); }
		$lines = array(); $previous = '';
		foreach ( $policy['rules'] as $row ) {
			$deadline = self::deadline_label( $row );
			$range = $previous ? 'Dopo ' . $previous . ' ed entro ' . $deadline : 'Per disdette entro ' . $deadline;
			$refund = self::refund_label( $row['percent'] ) . '.';
			$lines[] = $range . ': ' . $refund;
			$previous = $deadline;
		}
		$after_label = count( $policy['rules'] ) > 1 ? 'Dopo l’ultima scadenza' : ( $policy['rules'][0]['time'] ? 'Dopo questo termine' : 'Dopo questa data' );
		$lines[] = match ( $policy['after'] ) {
			'verify' => $after_label . ', la possibilità di ottenere un rimborso dovrà essere verificata con la Delegazione.',
			'percent' => $after_label . ': ' . self::refund_label( $policy['after_percent'] ) . '.',
			default => $after_label . ': nessun rimborso.',
		};
		return array( 'lines' => $lines, 'text' => '', 'notes' => wpautop( $policy['notes'] ) );
	}
}
