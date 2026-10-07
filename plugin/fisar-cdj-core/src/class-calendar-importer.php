<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Fisar_CDJ_Calendar_Importer {
	public const NOTICE = 'N.B.: il presente calendario potrebbe subire delle variazioni per motivi organizzativi e di disponibilità dei relatori.';

	/**
	 * @return array{rows: array<int, array<string, string>>, errors: array<int, string>}
	 */
	public static function parse( string $input ): array {
		$rows   = array();
		$errors = array();
		$lines  = preg_split( '/\R/u', trim( $input, "\r\n" ) ) ?: array();

		foreach ( $lines as $index => $line ) {
			if ( '' === trim( $line ) ) {
				continue;
			}

			$columns = str_getcsv( $line, "\t" );
			$columns = array_map( 'trim', $columns );

			if ( self::looks_like_header( $columns ) ) {
				continue;
			}

			// Il formato precedente inizia con la data; quello nuovo con il numero.
			$offset = '' !== self::normalize_date( $columns[0] ?? '' ) ? 0 : 1;
			if ( count( $columns ) < 3 + $offset ) {
				$errors[] = sprintf( 'Riga %d: servono almeno data, orario e titolo.', $index + 1 );
				continue;
			}

			$date = self::normalize_date( $columns[ $offset ] );
			if ( '' === $date ) {
				$errors[] = sprintf( 'Riga %d: data non riconosciuta.', $index + 1 );
				continue;
			}

			$rows[] = array(
				'number'  => $offset ? sanitize_text_field( $columns[0] ) : '',
				'date'    => $date,
				'time'    => sanitize_text_field( $columns[ $offset + 1 ] ?? '' ),
				'title'   => sanitize_text_field( $columns[ $offset + 2 ] ?? '' ),
				'speaker' => sanitize_text_field( $columns[ $offset + 3 ] ?? '' ),
				'notes'   => sanitize_text_field( implode( ' — ', array_slice( $columns, $offset + 4 ) ) ),
			);
		}

		return array(
			'rows'   => $rows,
			'errors' => $errors,
		);
	}

	public static function sanitize_rows( mixed $rows ): array {
		if ( ! is_array( $rows ) ) {
			return array();
		}

		$sanitized = array();
		foreach ( $rows as $row ) {
			if ( ! is_array( $row ) ) {
				continue;
			}

			$date  = self::normalize_date( (string) ( $row['date'] ?? '' ) );
			$title = sanitize_text_field( (string) ( $row['title'] ?? '' ) );
			if ( '' === $date || '' === $title ) {
				continue;
			}

			$sanitized[] = array(
				'number'  => is_scalar( $row['number'] ?? '' ) ? sanitize_text_field( (string) ( $row['number'] ?? '' ) ) : '',
				'date'    => $date,
				'time'    => sanitize_text_field( (string) ( $row['time'] ?? '' ) ),
				'title'   => $title,
				'speaker' => sanitize_text_field( (string) ( $row['speaker'] ?? '' ) ),
				'notes'   => sanitize_text_field( (string) ( $row['notes'] ?? '' ) ),
			);
		}

		usort(
			$sanitized,
			static fn( array $first, array $second ): int => strcmp( $first['date'] . $first['time'], $second['date'] . $second['time'] )
		);

		return $sanitized;
	}

	private static function looks_like_header( array $columns ): bool {
		$first = strtolower( self::strip_accents( (string) ( $columns[0] ?? '' ) ) );

		return in_array( $first, array( 'data', 'data lezione', 'giorno', 'numero', 'numero lezione', 'n°', 'n.', 'n' ), true );
	}

	private static function normalize_date( string $date ): string {
		$date = trim( $date );
		if ( '' === $date ) {
			return '';
		}

		$formats = array( '!Y-m-d', '!d/m/Y', '!d-m-Y', '!d.m.Y' );
		foreach ( $formats as $format ) {
			$parsed = DateTimeImmutable::createFromFormat( $format, $date, wp_timezone() );
			$errors = DateTimeImmutable::getLastErrors();
			if ( $parsed && ( false === $errors || ( 0 === $errors['warning_count'] && 0 === $errors['error_count'] ) ) ) {
				return $parsed->format( 'Y-m-d' );
			}
		}

		return '';
	}

	private static function strip_accents( string $value ): string {
		return function_exists( 'remove_accents' ) ? remove_accents( $value ) : $value;
	}
}
