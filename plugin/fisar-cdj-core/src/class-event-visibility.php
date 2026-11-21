<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Unlisted events remain public at their URL, without appearing in public collections. */
final class Fisar_CDJ_Event_Visibility {
	public const META = '_fisar_event_unlisted';
	private const NONCE_ACTION = 'fisar_cdj_save_event_visibility';
	private const NONCE_NAME = '_fisar_event_visibility_nonce';
	private const EDITOR_QUERY = 'fisar_event_visibility_editor';

	public static function init(): void {
		add_action( 'save_post_' . Fisar_CDJ_Post_Types::EVENT, array( self::class, 'save' ) );
		add_action( 'pre_get_posts', array( self::class, 'filter_query' ), 30 );
		add_filter( 'rest_' . Fisar_CDJ_Post_Types::EVENT . '_query', array( self::class, 'rest_query' ), 10, 2 );
		add_filter( 'wp_robots', array( self::class, 'robots' ) );
		add_filter( 'display_post_states', array( self::class, 'post_states' ), 10, 2 );
	}

	public static function is_unlisted( int $event_id ): bool {
		return $event_id > 0 && Fisar_CDJ_Post_Types::EVENT === get_post_type( $event_id ) && '1' === (string) get_post_meta( $event_id, self::META, true );
	}

	public static function add_meta_box(): void {
		add_meta_box( 'fisar-event-visibility', 'Visibilità nel sito', array( self::class, 'render' ), Fisar_CDJ_Post_Types::EVENT, 'normal', 'high' );
	}

	public static function render( WP_Post $post ): void {
		wp_nonce_field( self::NONCE_ACTION, self::NONCE_NAME );
		?>
		<input type="hidden" name="_fisar_event_visibility_present" value="1">
		<p><label><input type="checkbox" name="<?php echo esc_attr( self::META ); ?>" value="1"<?php checked( self::is_unlisted( $post->ID ) ); ?>> <strong>Non elencato — accessibile tramite link</strong></label></p>
		<p class="description">Dopo la pubblicazione, chi ha il link può aprire l’evento senza un account WordPress, ma non compare negli elenchi, nella ricerca o nei collegamenti automatici del sito.</p>
		<p class="description">Il link può essere inoltrato: non è una protezione con password. Togli la spunta e salva per mostrare l’evento normalmente. Una bozza resta accessibile solo agli utenti autorizzati.</p>
		<?php
	}

	public static function save( int $post_id ): void {
		if ( Fisar_CDJ_Post_Types::EVENT !== get_post_type( $post_id ) || wp_is_post_autosave( $post_id ) || wp_is_post_revision( $post_id ) || ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
		// An older editor or a partial save must not make an unlisted event visible.
		if ( ! isset( $_POST['_fisar_event_visibility_present'], $_POST[ self::NONCE_NAME ] ) || ! is_string( $_POST[ self::NONCE_NAME ] ) || ! wp_verify_nonce( wp_unslash( $_POST[ self::NONCE_NAME ] ), self::NONCE_ACTION ) ) {
			return;
		}
		if ( isset( $_POST[ self::META ] ) && ! is_string( $_POST[ self::META ] ) ) {
			return;
		}
		update_post_meta( $post_id, self::META, '1' === ( $_POST[ self::META ] ?? '' ) );
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
		if ( $types && 'any' !== $types && ! in_array( Fisar_CDJ_Post_Types::EVENT, (array) $types, true ) ) {
			return;
		}
		if ( ! $types && ! $query->is_search() && ! $query->is_feed() ) {
			return;
		}
		$visible = array(
			'relation' => 'OR',
			array( 'key' => self::META, 'compare' => 'NOT EXISTS' ),
			array( 'key' => self::META, 'value' => '1', 'compare' => '!=' ),
		);
		$existing = $query->get( 'meta_query' );
		$query->set( 'meta_query', $existing ? array( 'relation' => 'AND', $existing, $visible ) : $visible );
	}

	public static function robots( array $robots ): array {
		if ( is_singular( Fisar_CDJ_Post_Types::EVENT ) && self::is_unlisted( get_queried_object_id() ) ) {
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
