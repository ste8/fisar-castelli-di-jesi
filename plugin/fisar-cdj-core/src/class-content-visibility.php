<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Unlisted content remains public at its URL, without appearing in public collections. */
final class Fisar_CDJ_Content_Visibility {
	public const META = '_fisar_event_unlisted';
	public const FIELDS = array( 'fisar_evento' => self::META, 'fisar_corso' => '_fisar_course_unlisted', 'post' => '_fisar_post_unlisted' );
	private const NONCE_ACTION = 'fisar_cdj_save_event_visibility';
	private const NONCE_NAME = '_fisar_event_visibility_nonce';
	private const EDITOR_QUERY = 'fisar_event_visibility_editor';

	public static function init(): void {
		foreach ( self::FIELDS as $type => $key ) {
			add_action( 'save_post_' . $type, array( self::class, 'save' ) );
			add_filter( 'rest_' . $type . '_query', array( self::class, 'rest_query' ), 10, 2 );
			if ( 'fisar_evento' !== $type ) { add_filter( 'get_user_option_meta-box-order_' . $type, array( self::class, 'box_order' ) ); }
		}
		add_action( 'pre_get_posts', array( self::class, 'filter_query' ), 30 );
		add_filter( 'get_previous_post_where', array( self::class, 'adjacent_where' ) );
		add_filter( 'get_next_post_where', array( self::class, 'adjacent_where' ) );
		add_filter( 'wp_robots', array( self::class, 'robots' ) );
		add_filter( 'display_post_states', array( self::class, 'post_states' ), 10, 2 );
	}

	public static function is_unlisted( int $post_id ): bool {
		$key = self::FIELDS[ get_post_type( $post_id ) ] ?? '';
		return $post_id > 0 && $key && '1' === (string) get_post_meta( $post_id, $key, true );
	}

	public static function add_meta_box(): void {
		foreach ( self::FIELDS as $type => $key ) {
			add_meta_box( 'fisar_evento' === $type ? 'fisar-event-visibility' : 'fisar-content-visibility', 'Visibilità nel sito', array( self::class, 'render' ), $type, 'normal', 'high' );
		}
	}

	public static function render( WP_Post $post ): void {
		$key = self::FIELDS[ $post->post_type ];
		$subject = match ( $post->post_type ) { 'fisar_evento' => 'l’evento', 'fisar_corso' => 'il corso', default => 'l’articolo' };
		wp_nonce_field( self::NONCE_ACTION, self::NONCE_NAME );
		?>
		<input type="hidden" name="_fisar_event_visibility_present" value="1">
		<p><label><input type="checkbox" name="<?php echo esc_attr( $key ); ?>" value="1"<?php checked( self::is_unlisted( $post->ID ) ); ?>> <strong>Non elencato — accessibile tramite link</strong></label></p>
		<p class="description">Dopo la pubblicazione, chi ha il link può aprire <?php echo esc_html( $subject ); ?> senza un account WordPress, ma non compare negli elenchi, nella ricerca o nei collegamenti automatici del sito.</p>
		<p class="description">Il link può essere inoltrato: non è una protezione con password. Togli la spunta e salva per mostrare <?php echo esc_html( $subject ); ?> normalmente. Una bozza resta accessibile solo agli utenti autorizzati.</p>
		<?php
	}

	public static function save( int $post_id ): void {
		$key = self::FIELDS[ get_post_type( $post_id ) ] ?? '';
		if ( ! $key || wp_is_post_autosave( $post_id ) || wp_is_post_revision( $post_id ) || ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
		// An older editor or a partial save must not make unlisted content visible.
		if ( ! isset( $_POST['_fisar_event_visibility_present'], $_POST[ self::NONCE_NAME ] ) || ! is_string( $_POST[ self::NONCE_NAME ] ) || ! wp_verify_nonce( wp_unslash( $_POST[ self::NONCE_NAME ] ), self::NONCE_ACTION ) ) {
			return;
		}
		if ( isset( $_POST[ $key ] ) && ! is_string( $_POST[ $key ] ) ) {
			return;
		}
		update_post_meta( $post_id, $key, '1' === ( $_POST[ $key ] ?? '' ) );
	}

	/** This private query flag is never registered as a public URL parameter. */
	public static function rest_query( array $args, WP_REST_Request $request ): array {
		if ( 'edit' === $request->get_param( 'context' ) && current_user_can( 'edit_posts' ) ) {
			$args[ self::EDITOR_QUERY ] = true;
		}
		return $args;
	}

	/** Also applies to secondary queries, feeds, REST search and native sitemap queries. */
	public static function filter_query( WP_Query $query ): void {
		if ( is_admin() || $query->is_singular() || $query->get( self::EDITOR_QUERY ) ) {
			return;
		}
		$types = $query->get( 'post_type' );
		if ( $types && ! in_array( 'any', (array) $types, true ) && ! array_intersect( array_keys( self::FIELDS ), (array) $types ) ) {
			return;
		}
		$visible = array( 'relation' => 'AND' );
		foreach ( self::FIELDS as $type => $key ) {
			if ( $types && ! in_array( 'any', (array) $types, true ) && ! in_array( $type, (array) $types, true ) ) { continue; }
			$visible[] = array(
				'relation' => 'OR',
				array( 'key' => $key, 'compare' => 'NOT EXISTS' ),
				array( 'key' => $key, 'value' => '1', 'compare' => '!=' ),
			);
		}
		$existing = $query->get( 'meta_query' );
		$query->set( 'meta_query', $existing ? array( 'relation' => 'AND', $existing, $visible ) : $visible );
	}

	/** Adjacent-post navigation uses its own SQL instead of WP_Query. */
	public static function adjacent_where( string $where ): string {
		if ( is_admin() ) { return $where; }
		$key = self::FIELDS[ get_post_type() ] ?? '';
		if ( ! $key ) { return $where; }
		global $wpdb;
		return $where . $wpdb->prepare( " AND NOT EXISTS (SELECT 1 FROM {$wpdb->postmeta} visibility WHERE visibility.post_id = p.ID AND visibility.meta_key = %s AND visibility.meta_value = '1')", $key );
	}

	public static function box_order( mixed $order ): mixed {
		if ( ! is_array( $order ) ) { return $order; }
		foreach ( $order as $context => $boxes ) {
			if ( is_string( $boxes ) ) { $order[ $context ] = implode( ',', array_diff( explode( ',', $boxes ), array( 'fisar-content-visibility' ) ) ); }
		}
		if ( is_string( $order['normal'] ?? null ) && '' !== $order['normal'] ) {
			$order['normal'] = 'fisar-content-visibility,' . $order['normal'];
		}
		return $order;
	}

	public static function robots( array $robots ): array {
		if ( is_singular( array_keys( self::FIELDS ) ) && self::is_unlisted( get_queried_object_id() ) ) {
			unset( $robots['index'] );
			$robots['noindex'] = true;
		}
		return $robots;
	}

	public static function post_states( array $states, WP_Post $post ): array {
		if ( self::is_unlisted( $post->ID ) ) {
			$states['fisar_unlisted'] = 'Non elencato';
		}
		return $states;
	}
}
