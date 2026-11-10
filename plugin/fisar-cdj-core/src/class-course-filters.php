<?php
/** Read-only filtering of concluded courses, including incomplete historical editions. */
final class Fisar_CDJ_Course_Filters {
	public static function init(): void {
		add_filter( 'posts_where', array( self::class, 'where' ), 10, 2 );
		add_filter( 'posts_search', array( self::class, 'search' ), 10, 2 );
	}

	public static function types(): array {
		return array( 'sommelier' => 'Percorso Sommelier', 'introductory' => 'Minicorso', 'thematic' => 'Corso tematico' );
	}

	public static function normalize( array $values ): array {
		$filters = Fisar_CDJ_Archive_Filters::normalize( $values, false );
		$filters['month'] = 0;
		$filters['type'] = is_string( $values['type'] ?? null ) && isset( self::types()[ $values['type'] ] ) ? $values['type'] : '';
		$city = is_string( $values['city'] ?? null ) ? trim( sanitize_text_field( $values['city'] ) ) : '';
		$filters['city'] = function_exists( 'mb_substr' ) ? mb_substr( $city, 0, 200 ) : substr( $city, 0, 200 );
		return $filters;
	}

	/** Date of the edition, never its publication date. */
	public static function year( int $id ): int {
		$start = (string) get_post_meta( $id, '_fisar_course_start_date', true );
		$year = $start ? substr( $start, 0, 4 ) : fisar_cdj_get_course_archive_year( $id );
		return (int) ( $year ?: substr( (string) get_post_meta( $id, '_fisar_course_end_date', true ), 0, 4 ) );
	}

	public static function options(): array {
		static $options;
		if ( null !== $options ) { return $options; }
		$years = array();
		$cities = array();
		foreach ( fisar_cdj_get_past_courses()->posts as $course ) {
			$year = self::year( $course->ID );
			if ( $year ) { $years[ $year ] = $year; }
			$city = trim( (string) get_post_meta( $course->ID, '_fisar_course_city', true ) );
			if ( '' !== $city ) {
				$key = function_exists( 'mb_strtolower' ) ? mb_strtolower( $city ) : strtolower( $city );
				$cities[ $key ] = $city;
			}
		}
		rsort( $years );
		natcasesort( $cities );
		$options = array( 'years' => array_values( $years ), 'cities' => array_values( $cities ) );
		return $options;
	}

	public static function query_args( array $args, array $values ): array {
		$filters = self::normalize( $values );
		$args['fisar_cdj_course_filters'] = $filters;
		if ( '' !== $filters['text'] ) {
			$args['s'] = $filters['text'];
			$args['search_columns'] = array( 'post_title', 'post_content' );
		}
		return $args;
	}

	private static function applies( WP_Query $query ): bool {
		return $query->get( 'fisar_cdj_course_archive' ) && Fisar_CDJ_Post_Types::COURSE === $query->get( 'post_type' ) && is_array( $query->get( 'fisar_cdj_course_filters' ) );
	}

	private static function meta_value( string $key ): string {
		global $wpdb;
		return $wpdb->prepare( "(SELECT NULLIF(TRIM(MAX(meta_value)), '') FROM {$wpdb->postmeta} WHERE post_id = {$wpdb->posts}.ID AND meta_key = %s)", $key );
	}

	public static function where( string $where, WP_Query $query ): string {
		if ( ! self::applies( $query ) ) { return $where; }
		global $wpdb;
		$filters = $query->get( 'fisar_cdj_course_filters' );
		if ( $filters['year'] ) {
			$start = self::meta_value( '_fisar_course_start_date' );
			$year = self::meta_value( '_fisar_course_archive_year' );
			$end = self::meta_value( '_fisar_course_end_date' );
			$where .= $wpdb->prepare( " AND LEFT(COALESCE({$start}, {$year}, {$end}), 4) = %s", (string) $filters['year'] );
		}
		if ( '' !== $filters['type'] ) {
			$type = self::meta_value( '_fisar_course_type' );
			$where .= $wpdb->prepare( " AND COALESCE({$type}, 'sommelier') = %s", $filters['type'] );
		}
		if ( '' !== $filters['city'] ) {
			$city = self::meta_value( '_fisar_course_city' );
			$where .= $wpdb->prepare( " AND {$city} = %s", $filters['city'] );
		}
		return $where;
	}

	/** Keep WordPress term/phrase parsing; each term can match text or location. EXISTS avoids duplicate rows. */
	public static function search( string $search, WP_Query $query ): string {
		if ( ! self::applies( $query ) || ! $query->get( 's' ) ) { return $search; }
		global $wpdb;
		$clauses = array();
		$prefix = apply_filters( 'wp_query_search_exclusion_prefix', '-' );
		foreach ( (array) $query->get( 'search_terms' ) as $term ) {
			$exclude = $prefix && str_starts_with( $term, $prefix );
			if ( $exclude ) { $term = substr( $term, strlen( $prefix ) ); }
			$like = '%' . $wpdb->esc_like( $term ) . '%';
			$match = $wpdb->prepare( "({$wpdb->posts}.post_title LIKE %s OR {$wpdb->posts}.post_content LIKE %s OR EXISTS (SELECT 1 FROM {$wpdb->postmeta} course_location WHERE course_location.post_id = {$wpdb->posts}.ID AND course_location.meta_key IN ('_fisar_course_city', '_fisar_course_venue', '_fisar_course_address') AND course_location.meta_value LIKE %s))", $like, $like, $like );
			$clauses[] = ( $exclude ? 'NOT ' : '' ) . $match;
		}
		if ( ! $clauses ) { return $search; }
		$search = ' AND (' . implode( ' AND ', $clauses ) . ')';
		if ( ! is_user_logged_in() ) { $search .= " AND ({$wpdb->posts}.post_password = '')"; }
		return $search;
	}
}
