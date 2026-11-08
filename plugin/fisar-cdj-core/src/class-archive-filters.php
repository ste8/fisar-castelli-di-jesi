<?php
/** Public archive filters; reads native post categories and dates without changing content. */
final class Fisar_CDJ_Archive_Filters {
	private const QUERY_VARS = array( 'year' => 'fisar_year', 'month' => 'fisar_month', 'text' => 'fisar_text', 'category' => 'fisar_category', 'per_page' => 'fisar_per_page' );

	public static function page_sizes(): array {
		return array( 12, 24, 36, 48 );
	}

	public static function init(): void {
		add_filter( 'query_vars', array( self::class, 'query_vars' ) );
		add_action( 'pre_get_posts', array( self::class, 'filter_blog' ) );
	}

	public static function query_vars( array $vars ): array {
		return array_merge( $vars, array_values( self::QUERY_VARS ) );
	}

	public static function categories(): array {
		return array(
			'racconti-eventi'  => 'Racconti degli eventi',
			'servizi'         => 'Servizi',
			'corsi'           => 'Corsi',
			'vita-delegazione' => 'Vita della Delegazione',
			'ricordi'         => 'Ricordi',
		);
	}

	public static function normalize( array $values, bool $with_category = true ): array {
		$raw_year = $values['year'] ?? '';
		$raw_month = $values['month'] ?? '';
		$year = is_scalar( $raw_year ) ? (string) $raw_year : '';
		$month = is_scalar( $raw_month ) ? (string) $raw_month : '';
		$text = is_string( $values['text'] ?? null ) ? sanitize_text_field( $values['text'] ) : '';
		$category = is_string( $values['category'] ?? null ) ? $values['category'] : '';
		$raw_size = $values['per_page'] ?? 12;
		$size = is_scalar( $raw_size ) && ctype_digit( (string) $raw_size ) ? (int) $raw_size : 12;
		return array(
			'year'     => preg_match( '/^[1-9][0-9]{3}$/', $year ) ? (int) $year : 0,
			'month'    => ctype_digit( $month ) && (int) $month >= 1 && (int) $month <= 12 ? (int) $month : 0,
			'text'     => function_exists( 'mb_substr' ) ? mb_substr( $text, 0, 200 ) : substr( $text, 0, 200 ),
			'category' => $with_category && ( isset( self::categories()[ $category ] ) || 'altro' === $category ) ? $category : '',
			'per_page' => in_array( $size, self::page_sizes(), true ) ? $size : 12,
		);
	}

	public static function current( string $scope ): array {
		$values = array();
		foreach ( self::QUERY_VARS as $name => $query_var ) {
			$values[ $name ] = get_query_var( $query_var, '' );
		}
		return self::normalize( $values, 'blog' === $scope );
	}

	public static function url_args( array $filters ): array {
		$args = array();
		foreach ( self::normalize( $filters ) as $name => $value ) {
			if ( 'per_page' === $name && 12 === $value ) {
				continue;
			}
			if ( '' !== $value && 0 !== $value ) {
				$args[ self::QUERY_VARS[ $name ] ] = $value;
			}
		}
		return $args;
	}

	public static function has_criteria( array $filters ): bool {
		$args = self::url_args( $filters );
		unset( $args['fisar_per_page'] );
		return (bool) $args;
	}

	public static function available_years( string $scope, bool $past_only = false ): array {
		global $wpdb;
		if ( 'events' === $scope ) {
			$sql = $wpdb->prepare(
				"SELECT DISTINCT LEFT(m.meta_value, 4) FROM {$wpdb->postmeta} m INNER JOIN {$wpdb->posts} p ON p.ID = m.post_id WHERE p.post_type = %s AND p.post_status = 'publish' AND m.meta_key = '_fisar_event_date' AND m.meta_value REGEXP '^[1-9][0-9]{3}-[0-9]{2}-[0-9]{2}$'",
				Fisar_CDJ_Post_Types::EVENT
			);
			if ( $past_only ) {
				$sql .= $wpdb->prepare( ' AND m.meta_value < %s', fisar_cdj_today() );
			}
			$years = $wpdb->get_col( $sql . ' ORDER BY 1 DESC' );
		} else {
			$years = $wpdb->get_col( "SELECT DISTINCT YEAR(post_date) FROM {$wpdb->posts} WHERE post_type = 'post' AND post_status = 'publish' AND YEAR(post_date) > 0 ORDER BY 1 DESC" );
		}
		return array_map( 'intval', $years );
	}

	public static function filter_blog( WP_Query $query ): void {
		if ( is_admin() || ! $query->is_main_query() || ! $query->is_home() || $query->is_feed() ) {
			return;
		}
		$values = array();
		foreach ( self::QUERY_VARS as $name => $query_var ) {
			$values[ $name ] = $query->get( $query_var );
		}
		$filters = self::normalize( $values );
		$date = array_filter( array( 'year' => $filters['year'], 'month' => $filters['month'] ) );
		if ( $date ) {
			$existing = $query->get( 'date_query' );
			$query->set( 'date_query', array_merge( is_array( $existing ) ? $existing : array(), array( $date ) ) );
		}
		if ( '' !== $filters['text'] ) {
			$query->set( 's', $filters['text'] );
			$query->set( 'search_columns', array( 'post_title', 'post_content' ) );
			if ( ! $query->get( 'orderby' ) ) {
				$query->set( 'orderby', array( 'date' => 'DESC', 'ID' => 'DESC' ) );
			}
		}
		if ( '' !== $filters['category'] ) {
			$other = 'altro' === $filters['category'];
			$existing = $query->get( 'tax_query' );
			$query->set( 'tax_query', array_merge( is_array( $existing ) ? $existing : array(), array( array(
				'taxonomy'         => 'category',
				'field'            => 'slug',
				'terms'            => $other ? array_keys( self::categories() ) : array( $filters['category'] ),
				'operator'         => $other ? 'NOT IN' : 'IN',
				'include_children' => true,
			) ) ) );
		}
	}

	public static function event_query_args( array $args, array $values ): array {
		$filters = self::normalize( $values, false );
		if ( $filters['year'] ) {
			$start = new DateTimeImmutable( sprintf( '%04d-%02d-01', $filters['year'], $filters['month'] ?: 1 ) );
			$end = $filters['month'] ? $start->modify( 'last day of this month' ) : $start->setDate( $filters['year'], 12, 31 );
			$args['meta_query'][] = array( 'key' => '_fisar_event_date', 'value' => array( $start->format( 'Y-m-d' ), $end->format( 'Y-m-d' ) ), 'compare' => 'BETWEEN', 'type' => 'DATE' );
		} elseif ( $filters['month'] ) {
			$args['meta_query'][] = array( 'key' => '_fisar_event_date', 'value' => sprintf( '-%02d-', $filters['month'] ), 'compare' => 'LIKE' );
		}
		if ( '' !== $filters['text'] ) {
			$args['s'] = $filters['text'];
			$args['search_columns'] = array( 'post_title', 'post_content' );
		}
		return $args;
	}
}
