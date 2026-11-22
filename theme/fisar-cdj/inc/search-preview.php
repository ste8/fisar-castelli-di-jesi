<?php
/** Contextual search excerpts; output is escaped except for the marks we create. */
final class Fisar_CDJ_Search_Preview {
	public static function plain_text( string $html ): string {
		$html = strip_shortcodes( $html );
		$html = preg_replace( '~<br\b[^>]*>|</(?:p|h[1-6]|li|div|section|ul|ol|blockquote|tr|td|th)>~i', ' ', $html );
		$text = html_entity_decode( wp_strip_all_tags( $html ), ENT_QUOTES | ENT_HTML5, 'UTF-8' );
		return trim( preg_replace( '/\s+/u', ' ', $text ) );
	}

	/** Use WordPress's parsed positive terms, including quoted phrases, not the raw query. */
	private static function terms( WP_Query $query ): array {
		$prefix = apply_filters( 'wp_query_search_exclusion_prefix', '-' );
		$terms = array_filter( (array) $query->get( 'search_terms' ), static fn ( $term ) => '' !== $term && ! ( $prefix && str_starts_with( $term, $prefix ) ) );
		$terms = array_values( array_unique( array_map( array( self::class, 'fold' ), $terms ) ) );
		usort( $terms, static fn ( $a, $b ) => strlen( $b ) <=> strlen( $a ) );
		return $terms;
	}

	private static function fold( string $text ): string {
		$text = remove_accents( $text );
		return function_exists( 'mb_strtolower' ) ? mb_strtolower( $text, 'UTF-8' ) : strtolower( $text );
	}

	/** Map normalized matches back to original UTF-8 byte offsets, preserving accents and case. */
	private static function matches( string $text, WP_Query $query ): array {
		$terms = self::terms( $query );
		if ( ! $terms || '' === $text ) { return array(); }
		preg_match_all( '/./us', $text, $characters, PREG_OFFSET_CAPTURE );
		$folded = '';
		$starts = array();
		$ends = array();
		foreach ( $characters[0] as [ $character, $offset ] ) {
			$normalized = self::fold( $character );
			for ( $i = 0; $i < strlen( $normalized ); $i++ ) {
				$starts[] = $offset;
				$ends[] = $offset + strlen( $character );
			}
			$folded .= $normalized;
		}
		$pattern = '~' . implode( '|', array_map( static fn ( $term ) => preg_quote( $term, '~' ), $terms ) ) . '~u';
		preg_match_all( $pattern, $folded, $found, PREG_OFFSET_CAPTURE );
		$ranges = array();
		foreach ( $found[0] as [ $match, $offset ] ) {
			$start = $starts[ $offset ];
			$end = $ends[ $offset + strlen( $match ) - 1 ];
			$last = count( $ranges ) - 1;
			if ( $last >= 0 && $start < $ranges[ $last ][1] ) {
				$ranges[ $last ][1] = max( $end, $ranges[ $last ][1] );
			} else {
				$ranges[] = array( $start, $end );
			}
		}
		return $ranges;
	}

	public static function highlight( string $text, WP_Query $query ): string {
		$html = '';
		$cursor = 0;
		foreach ( self::matches( $text, $query ) as [ $start, $end ] ) {
			$html .= esc_html( substr( $text, $cursor, $start - $cursor ) );
			$html .= '<mark>' . esc_html( substr( $text, $start, $end - $start ) ) . '</mark>';
			$cursor = $end;
		}
		return $html . esc_html( substr( $text, $cursor ) );
	}

	/** Show the matching passage, or the introduction if the match is in title/location only. */
	public static function excerpt( string $body, WP_Query $query ): string {
		$text = self::plain_text( $body );
		$matches = self::matches( $text, $query );
		if ( ! $matches ) { return self::highlight( wp_trim_words( $text, 35, '…' ), $query ); }
		preg_match_all( '/\S+/u', $text, $words, PREG_OFFSET_CAPTURE );
		$anchor = 0;
		foreach ( $words[0] as $index => [ $word, $offset ] ) {
			if ( $offset > $matches[0][0] ) { break; }
			$anchor = $index;
		}
		$first = max( 0, $anchor - 10 );
		$last = min( count( $words[0] ) - 1, $first + 39 );
		$start = $words[0][ $first ][1];
		$end = max( $matches[0][1], $words[0][ $last ][1] + strlen( $words[0][ $last ][0] ) );
		$excerpt = substr( $text, $start, $end - $start );
		return ( $start ? '… ' : '' ) . self::highlight( $excerpt, $query ) . ( $end < strlen( $text ) ? ' …' : '' );
	}
}
