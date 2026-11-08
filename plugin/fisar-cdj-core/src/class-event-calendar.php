<?php
/** Explicit event dates. Legacy events keep their single date without migration. */
final class Fisar_CDJ_Event_Calendar {
	private const FIELDS = array( 'date', 'date_mode', 'end_date', 'date_note', 'additional_dates' );

	public static function init(): void {
		add_filter( 'posts_clauses', array( self::class, 'query_clauses' ), 20, 2 );
	}

	public static function date( mixed $value ): string {
		if ( ! is_string( $value ) || ! preg_match( '/^([1-9][0-9]{3})-([0-9]{2})-([0-9]{2})$/', $value, $parts ) ) { return ''; }
		return checkdate( (int) $parts[2], (int) $parts[3], (int) $parts[1] ) ? $value : '';
	}

	public static function mode( mixed $value ): string {
		return in_array( $value, array( 'single', 'range', 'multiple' ), true ) ? $value : 'single';
	}

	public static function note( mixed $value ): string {
		return is_string( $value ) ? sanitize_text_field( $value ) : '';
	}

	public static function additional_dates( mixed $rows ): array {
		$dates = array();
		foreach ( is_array( $rows ) ? $rows : array() as $row ) {
			if ( ! is_array( $row ) ) { continue; }
			$date = self::date( $row['date'] ?? '' );
			if ( $date && ! isset( $dates[ $date ] ) ) { $dates[ $date ] = array( 'date' => $date, 'note' => self::note( $row['note'] ?? '' ) ); }
		}
		ksort( $dates );
		return array_values( $dates );
	}

	/** Normalized periods: a distinct date is a period with equal endpoints. */
	public static function periods( array $values ): array {
		$date = self::date( $values['date'] ?? '' );
		if ( ! $date ) { return array(); }
		$mode = self::mode( $values['date_mode'] ?? '' );
		$end = self::date( $values['end_date'] ?? '' );
		$rows = array( $date => array( 'start' => $date, 'end' => 'range' === $mode && $end >= $date ? $end : $date, 'note' => 'multiple' === $mode ? self::note( $values['date_note'] ?? '' ) : '' ) );
		if ( 'multiple' === $mode ) {
			foreach ( self::additional_dates( $values['additional_dates'] ?? array() ) as $row ) {
				if ( ! isset( $rows[ $row['date'] ] ) ) { $rows[ $row['date'] ] = array( 'start' => $row['date'], 'end' => $row['date'], 'note' => $row['note'] ); }
			}
		}
		ksort( $rows );
		return array_values( $rows );
	}

	public static function values( int $id ): array {
		$values = array();
		foreach ( self::FIELDS as $field ) { $values[ $field ] = get_post_meta( $id, '_fisar_event_' . $field, true ); }
		return $values;
	}

	public static function for_event( int $id ): array {
		return self::periods( self::values( $id ) );
	}

	public static function last_date( array $periods ): string {
		return $periods ? max( array_column( $periods, 'end' ) ) : '';
	}

	public static function next_date( array $periods, string $today ): string {
		foreach ( $periods as $period ) {
			if ( $period['end'] >= $today ) { return max( $today, $period['start'] ); }
		}
		return '';
	}

	public static function matches( array $periods, int $year, int $month ): bool {
		if ( ! $year && ! $month ) { return true; }
		foreach ( $periods as $period ) {
			$y = $year ?: (int) substr( $period['start'], 0, 4 );
			if ( ! $year && (int) substr( $period['start'], 5, 2 ) > $month ) { $y++; }
			$start = sprintf( '%04d-%02d-01', $y, $month ?: 1 );
			$end = $month ? ( new DateTimeImmutable( $start ) )->format( 'Y-m-t' ) : $y . '-12-31';
			if ( $period['start'] <= $end && $period['end'] >= $start ) { return true; }
		}
		return false;
	}

	/** One narrow metadata read; no content loads, daily writes or persistent cache. */
	private static function published_calendars(): array {
		global $wpdb;
		$keys = array_map( static fn( $field ) => '_fisar_event_' . $field, self::FIELDS );
		$placeholders = implode( ',', array_fill( 0, count( $keys ), '%s' ) );
		$rows = $wpdb->get_results( $wpdb->prepare( "SELECT p.ID, m.meta_key, m.meta_value FROM {$wpdb->posts} p INNER JOIN {$wpdb->postmeta} m ON p.ID = m.post_id WHERE p.post_type = %s AND p.post_status = 'publish' AND m.meta_key IN ($placeholders) ORDER BY m.meta_id", Fisar_CDJ_Post_Types::EVENT, ...$keys ) );
		$values = array();
		foreach ( $rows as $row ) {
			$field = substr( $row->meta_key, strlen( '_fisar_event_' ) );
			if ( ! isset( $values[ $row->ID ][ $field ] ) ) { $values[ $row->ID ][ $field ] = maybe_unserialize( $row->meta_value ); }
		}
		return array_map( array( self::class, 'periods' ), $values );
	}

	/** Select/order before WordPress applies native search, counts and pagination. */
	public static function query_args( array $args, string $state, array $filters = array() ): array {
		$today = fisar_cdj_today();
		$selected = array();
		foreach ( self::published_calendars() as $id => $periods ) {
			$last = self::last_date( $periods );
			if ( ! $last || ( 'past' === $state ? $last >= $today : ( 'upcoming' === $state && $last < $today ) ) ) { continue; }
			if ( ! self::matches( $periods, $filters['year'] ?? 0, $filters['month'] ?? 0 ) ) { continue; }
			$selected[ $id ] = 'past' === $state ? $last : ( 'upcoming' === $state ? self::next_date( $periods, $today ) : $periods[0]['start'] );
		}
		uksort( $selected, static function ( $a, $b ) use ( $selected, $state ): int {
			$comparison = strcmp( $selected[ $a ], $selected[ $b ] );
			return ( 'past' === $state ? -$comparison : $comparison ) ?: ( (int) $a <=> (int) $b );
		} );
		$args['fisar_event_calendar_ids'] = array_map( 'intval', array_keys( $selected ) );
		return $args;
	}

	public static function query_clauses( array $clauses, WP_Query $query ): array {
		$ids = $query->get( 'fisar_event_calendar_ids', null );
		if ( ! is_array( $ids ) ) { return $clauses; }
		global $wpdb;
		$ids = implode( ',', array_map( 'absint', $ids ) ) ?: '0';
		$clauses['where'] .= " AND {$wpdb->posts}.ID IN ($ids)";
		$clauses['orderby'] = "FIELD({$wpdb->posts}.ID,$ids)";
		return $clauses;
	}

	public static function years( bool $past_only ): array {
		$years = array();
		foreach ( self::published_calendars() as $periods ) {
			if ( $past_only && self::last_date( $periods ) >= fisar_cdj_today() ) { continue; }
			foreach ( $periods as $period ) {
				for ( $year = (int) substr( $period['start'], 0, 4 ); $year <= (int) substr( $period['end'], 0, 4 ); $year++ ) { $years[ $year ] = $year; }
			}
		}
		rsort( $years );
		return $years;
	}

	public static function format_date( string $date, string $format = 'l j F Y' ): string {
		return self::date( $date ) ? wp_date( $format, ( new DateTimeImmutable( $date, wp_timezone() ) )->getTimestamp(), wp_timezone() ) : '';
	}

	public static function period_label( array $period, bool $compact = false, bool $short = false ): string {
		$format = $compact ? ( $short ? 'j M Y' : 'j F Y' ) : 'l j F Y';
		if ( $period['start'] === $period['end'] ) { return self::format_date( $period['start'], $format ); }
		if ( ! $compact ) { return 'Dal ' . self::format_date( $period['start'] ) . ' al ' . self::format_date( $period['end'] ); }
		$left_format = substr( $period['start'], 0, 7 ) === substr( $period['end'], 0, 7 ) ? 'j' : ( substr( $period['start'], 0, 4 ) === substr( $period['end'], 0, 4 ) ? ( $short ? 'j M' : 'j F' ) : $format );
		return self::format_date( $period['start'], $left_format ) . '–' . self::format_date( $period['end'], $format );
	}

	public static function card_label( int $id, bool $short = false ): string {
		$rows = self::for_event( $id );
		if ( ! $rows ) { return ''; }
		$today = fisar_cdj_today();
		if ( count( $rows ) === 1 ) {
			$row = $rows[0];
			if ( $row['start'] < $today && $row['end'] >= $today ) {
				$until = in_array( (int) substr( $row['end'], 8, 2 ), array( 1, 8, 11 ), true ) ? 'FINO ALL’' : 'FINO AL ';
				return 'IN CORSO · ' . $until . mb_strtoupper( self::format_date( $row['end'], $short ? 'j M Y' : 'j F Y' ) );
			}
			// Preserve the existing single-day labels, including the weekday.
			return mb_strtoupper( $row['start'] === $row['end'] ? self::format_date( $row['start'], $short ? 'D j M Y' : 'D j F Y' ) : self::period_label( $row, true, $short ) );
		}
		$next = self::next_date( $rows, $today );
		if ( $next && ( $rows[0]['start'] < $today || count( $rows ) > 2 ) ) { return 'PROSSIMA DATA: ' . mb_strtoupper( self::format_date( $next, $short ? 'j M Y' : 'j F Y' ) ); }
		if ( count( $rows ) === 2 ) {
			$same_month = substr( $rows[0]['start'], 0, 7 ) === substr( $rows[1]['start'], 0, 7 );
			return mb_strtoupper( self::format_date( $rows[0]['start'], $same_month ? 'j' : ( $short ? 'j M Y' : 'j F Y' ) ) . ' e ' . self::format_date( $rows[1]['start'], $short ? 'j M Y' : 'j F Y' ) );
		}
		return mb_strtoupper( self::period_label( $rows[0], true, $short ) ) . ' (+' . ( count( $rows ) - 1 ) . ' DATE)';
	}

	/** Called only after the shared nonce/capability/autosave checks. */
	public static function save_request( int $id, array $input ): void {
		$values = array();
		foreach ( self::FIELDS as $field ) { $values[ $field ] = $input[ '_fisar_event_' . $field ] ?? ''; }
		$mode = self::mode( $values['date_mode'] );
		$rows = self::periods( $values );
		$invalid = 'range' === $mode && ( ! self::date( $values['date'] ) || ! self::date( $values['end_date'] ) || $values['end_date'] < $values['date'] );
		$invalid = $invalid || ( 'multiple' === $mode && count( $rows ) < 2 );
		if ( 'multiple' === $mode ) {
			$seen = array( self::date( $values['date'] ) );
			foreach ( is_array( $values['additional_dates'] ) ? $values['additional_dates'] : array() as $row ) {
				if ( ! is_array( $row ) ) { $invalid = true; continue; }
				$date = self::date( $row['date'] ?? '' );
				if ( ! $date && empty( $row['date'] ) && ! self::note( $row['note'] ?? '' ) ) { continue; }
				if ( ! $date || in_array( $date, $seen, true ) ) { $invalid = true; }
				$seen[] = $date;
			}
		}
		if ( $invalid ) {
			set_transient( 'fisar_event_dates_error_' . get_current_user_id() . '_' . $id, 'Le date non sono state aggiornate. Controlla la data finale e le righe incomplete o duplicate; per più date servono almeno due giornate distinte.', MINUTE_IN_SECONDS * 10 );
			return;
		}
		if ( 'multiple' === $mode ) {
			$values['date'] = $rows[0]['start'];
			$values['date_note'] = $rows[0]['note'];
			$values['additional_dates'] = array_map( static fn( $row ) => array( 'date' => $row['start'], 'note' => $row['note'] ), array_slice( $rows, 1 ) );
		}
		update_post_meta( $id, '_fisar_event_date', self::date( $values['date'] ) );
		update_post_meta( $id, '_fisar_event_date_mode', $mode );
		update_post_meta( $id, '_fisar_event_end_date', self::date( $values['end_date'] ) );
		update_post_meta( $id, '_fisar_event_date_note', wp_slash( self::note( $values['date_note'] ) ) );
		update_post_meta( $id, '_fisar_event_additional_dates', wp_slash( self::additional_dates( $values['additional_dates'] ) ) );
		delete_transient( 'fisar_event_dates_error_' . get_current_user_id() . '_' . $id );
	}
}
