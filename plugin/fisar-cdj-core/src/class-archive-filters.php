<?php
/** Public archive filters; reads native post categories and dates without changing content. */
final class Fisar_CDJ_Archive_Filters {
	private const QUERY_VARS = array( 'year' => 'anno', 'month' => 'mese', 'text' => 'search', 'category' => 'categoria', 'per_page' => 'per_pagina' );
	private const LEGACY_QUERY_VARS = array( 'year' => 'fisar_year', 'month' => 'fisar_month', 'text' => 'fisar_text', 'category' => 'fisar_category', 'per_page' => 'fisar_per_page' );

	public static function page_sizes(): array {
		return array( 12, 24, 36, 48 );
	}

	public static function init(): void {
		add_filter( 'query_vars', array( self::class, 'query_vars' ) );
		add_action( 'pre_get_posts', array( self::class, 'filter_blog' ) );
		add_filter( 'paginate_links', array( self::class, 'pagination_url' ) );
	}

	public static function query_vars( array $vars ): array {
		return array_merge( $vars, array_values( self::QUERY_VARS ), array_values( self::LEGACY_QUERY_VARS ) );
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
		global $wp_query;
		return self::from_query( $wp_query, $scope );
	}

	public static function from_query( WP_Query $query, string $scope ): array {
		$values = array();
		foreach ( self::QUERY_VARS as $name => $query_var ) {
			$values[ $name ] = $query->get( $query_var, null ) ?? $query->get( self::LEGACY_QUERY_VARS[ $name ], '' );
		}
		return self::normalize( $values, 'blog' === $scope );
	}

	public static function pagination_url( string $url ): string {
		if ( is_admin() || ( ! is_home() && ! is_post_type_archive( Fisar_CDJ_Post_Types::EVENT ) ) ) {
			return $url;
		}
		parse_str( wp_parse_url( $url, PHP_URL_QUERY ) ?? '', $parameters );
		foreach ( self::QUERY_VARS as $name => $query_var ) {
			$legacy = self::LEGACY_QUERY_VARS[ $name ];
			if ( array_key_exists( $legacy, $parameters ) && ! array_key_exists( $query_var, $parameters ) ) {
				$url = add_query_arg( $query_var, $parameters[ $legacy ], $url );
			}
		}
		return remove_query_arg( array_values( self::LEGACY_QUERY_VARS ), $url );
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
		unset( $args['per_pagina'] );
		return (bool) $args;
	}

	public static function available_years( string $scope, bool $past_only = false ): array {
		global $wpdb;
		if ( 'events' === $scope ) {
			return Fisar_CDJ_Event_Calendar::years( $past_only );
		} else {
			$years = $wpdb->get_col( "SELECT DISTINCT YEAR(post_date) FROM {$wpdb->posts} WHERE post_type = 'post' AND post_status = 'publish' AND YEAR(post_date) > 0 ORDER BY 1 DESC" );
		}
		return array_map( 'intval', $years );
	}

	public static function filter_blog( WP_Query $query ): void {
		if ( is_admin() || ! $query->is_main_query() || ! $query->is_home() || $query->is_feed() ) {
			return;
		}
		$filters = self::from_query( $query, 'blog' );
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

	public static function event_query_args( array $args, array $values, string $state = 'all' ): array {
		$filters = self::normalize( $values, false );
		if ( '' !== $filters['text'] ) {
			$args['s'] = $filters['text'];
			$args['search_columns'] = array( 'post_title', 'post_content' );
		}
		return Fisar_CDJ_Event_Calendar::query_args( $args, $state, $filters );
	}
}
