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
		// A complete name/phrase is stronger than isolated words, including inside other names.
		$phrase = implode( ' ', $terms );
		if ( count( $terms ) > 1 && preg_match( self::word_pattern( $phrase ), $folded ) ) {
			$terms = array( $phrase );
		}
		$hits = array();
		foreach ( $terms as $term ) {
			preg_match_all( self::word_pattern( $term ), $folded, $found, PREG_OFFSET_CAPTURE );
			// Keep partial searches useful when there is no whole-word match for this term.
			if ( ! $found[0] ) { preg_match_all( '~' . preg_quote( $term, '~' ) . '~u', $folded, $found, PREG_OFFSET_CAPTURE ); }
			$hits = array_merge( $hits, $found[0] );
		}
		usort( $hits, static fn ( $a, $b ) => ( $a[1] <=> $b[1] ) ?: ( strlen( $b[0] ) <=> strlen( $a[0] ) ) );
		$ranges = array();
		foreach ( $hits as [ $match, $offset ] ) {
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

	private static function word_pattern( string $term ): string {
		return '~(?<![\p{L}\p{N}_])' . preg_quote( $term, '~' ) . '(?![\p{L}\p{N}_])~u';
	}

	/** Prefer passages containing more query terms; ties favor terms closer together. */
	private static function excerpt_range( string $text, array $matches, WP_Query $query ): array {
		preg_match_all( '/\S+/u', $text, $words, PREG_OFFSET_CAPTURE );
		$terms = self::terms( $query );
		$anchor = 0;
		$best = array( 0, strlen( $text ) );
		$best_score = array( -1, -1, -PHP_INT_MAX );
		foreach ( $matches as [ $match_start, $match_end ] ) {
			while ( isset( $words[0][ $anchor + 1 ] ) && $words[0][ $anchor + 1 ][1] <= $match_start ) { $anchor++; }
			$first = max( 0, $anchor - 10 );
			$last = min( count( $words[0] ) - 1, $first + 39 );
			$start = $words[0][ $first ][1];
			$end = max( $match_end, $words[0][ $last ][1] + strlen( $words[0][ $last ][0] ) );
			$passage = self::fold( substr( $text, $start, $end - $start ) );
			$positions = array();
			$ends = array();
			$whole_words = 0;
			foreach ( $terms as $term ) {
				if ( preg_match( self::word_pattern( $term ), $passage, $found, PREG_OFFSET_CAPTURE ) ) {
					$position = $found[0][1];
					$whole_words++;
				} else { $position = strpos( $passage, $term ); }
				if ( false !== $position ) { $positions[] = $position; $ends[] = $position + strlen( $term ); }
			}
			$score = array( count( $positions ), $whole_words, $positions ? - ( max( $ends ) - min( $positions ) ) : 0 );
			if ( $score > $best_score ) { $best_score = $score; $best = array( $start, $end ); }
			if ( 1 === count( $terms ) ) { break; }
		}
		return $best;
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
		[ $start, $end ] = self::excerpt_range( $text, $matches, $query );
		$excerpt = substr( $text, $start, $end - $start );
		return ( $start ? '… ' : '' ) . self::highlight( $excerpt, $query ) . ( $end < strlen( $text ) ? ' …' : '' );
	}
}
