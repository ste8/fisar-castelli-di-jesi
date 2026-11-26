<?php
/** Main-panel composer. Only explicit authenticated requests can send to Buffer. */
final class Fisar_CDJ_Social_Editor {
	public static function init(): void {
		Fisar_CDJ_Social_Settings::init();
		add_action( 'add_meta_boxes_post', static function (): void {
			add_meta_box( 'fisar-social-publishing', 'Pubblica su Facebook e Instagram', array( self::class, 'render' ), 'post', 'normal', 'low' );
		} );
		add_action( 'admin_enqueue_scripts', array( self::class, 'enqueue' ) );
		add_action( 'rest_api_init', array( self::class, 'routes' ) );
	}

	public static function enqueue( string $hook ): void {
		$screen = get_current_screen();
		if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) || ! $screen || 'post' !== $screen->post_type ) { return; }
		wp_enqueue_style( 'fisar-social-editor', FISAR_CDJ_CORE_URL . 'assets/css/social-editor.css', array(), FISAR_CDJ_CORE_VERSION );
		wp_enqueue_script( 'fisar-social-photo-grid', FISAR_CDJ_CORE_URL . 'assets/js/social-photo-grid.js', array(), FISAR_CDJ_CORE_VERSION, true );
		wp_enqueue_script( 'fisar-social-photo-tags', FISAR_CDJ_CORE_URL . 'assets/js/social-photo-tags.js', array(), FISAR_CDJ_CORE_VERSION, true );
		wp_enqueue_script( 'fisar-social-editor', FISAR_CDJ_CORE_URL . 'assets/js/social-editor.js', array( 'wp-dom-ready', 'wp-api-fetch', 'wp-data', 'fisar-social-photo-grid', 'fisar-social-photo-tags' ), FISAR_CDJ_CORE_VERSION, true );
	}

	public static function render( WP_Post $post ): void {
		?><div id="fisar-social-composer" data-post-id="<?php echo (int) $post->ID; ?>">
		<p>Prepara un unico post per Facebook e Instagram usando le foto già caricate nell’articolo.</p>
		<p><button type="button" class="button button-secondary" id="fisar-social-open" aria-haspopup="dialog">Prepara post social</button></p>
		<p id="fisar-social-launcher-status" class="description" role="status" aria-live="polite"><?php echo Fisar_CDJ_Social_Publishing::is_live() ? 'La condivisione parte solo dal pulsante nella finestra di preparazione.' : 'Modalità prova: nessun post viene pubblicato sui social.'; ?></p>
		<?php Fisar_CDJ_Event_Reports::render_social_urls( $post ); ?>
		<noscript><p>Attiva JavaScript per preparare i post social.</p></noscript>
		<dialog id="fisar-social-dialog" class="fisar-social-dialog" aria-labelledby="fisar-social-dialog-title">
			<div class="fisar-social-dialog-header">
				<h2 id="fisar-social-dialog-title" tabindex="-1">Prepara post social</h2>
				<button type="button" class="button" id="fisar-social-close">Chiudi</button>
			</div>
			<div class="fisar-social-dialog-body">
				<?php if ( current_user_can( 'manage_options' ) ) : ?><p><a href="<?php echo esc_url( admin_url( 'options-general.php?page=fisar-social' ) ); ?>">Impostazioni Pubblicazione social</a></p><?php endif; ?>
				<div data-social-content><p>Caricamento della preparazione social…</p></div>
			</div>
			<div class="fisar-social-dialog-footer" data-social-footer role="group" aria-label="Azioni e stato del post social" hidden></div>
		</dialog>
		</div><?php
	}

	public static function routes(): void {
		register_rest_route( 'fisar-cdj/v1', '/social/(?P<id>\d+)', array(
			array( 'methods' => 'GET', 'callback' => array( self::class, 'state' ), 'permission_callback' => array( self::class, 'permission' ) ),
			array( 'methods' => 'POST', 'callback' => array( self::class, 'action' ), 'permission_callback' => array( self::class, 'permission' ) ),
		) );
	}

	public static function permission( WP_REST_Request $request ): bool {
		$post = get_post( (int) $request['id'] );
		return $post && 'post' === $post->post_type && current_user_can( 'edit_post', $post->ID );
	}

	public static function version( WP_Post $post ): string {
		return hash( 'sha256', wp_json_encode( array( $post->post_content, $post->post_title, $post->post_modified_gmt, $post->post_status, $post->post_password, Fisar_CDJ_Content_Visibility::is_unlisted( $post->ID ) ) ) );
	}

	private static function social_urls( int $post_id ): array {
		$urls = array();
		foreach ( Fisar_CDJ_Event_Reports::SOCIAL_FIELDS as $key => $label ) { $urls[ $key ] = (string) get_post_meta( $post_id, $key, true ); }
		return $urls;
	}

	public static function state( WP_REST_Request $request ): array {
		$post = get_post( (int) $request['id'] );
		$composer = Fisar_CDJ_Social_Publishing::composer( $post );
		$ids = Fisar_CDJ_Social_Publishing::article_images( $post );
		$ids = array_merge( $ids, $composer['images'] );
		$settings = Fisar_CDJ_Social_Settings::values();
		$accounts = array();
		foreach ( $settings['channels'] ?? array() as $channel ) {
			if ( ( $settings[ $channel['service'] ] ?? '' ) === $channel['id'] ) { $accounts[ $channel['service'] ] = $channel['name']; }
		}
		return array(
			'composer' => $composer, 'version' => self::version( $post ),
			'networks' => Fisar_CDJ_Social_Publishing::NETWORKS,
			'instagramTagLimit' => Fisar_CDJ_Social_Publishing::MAX_INSTAGRAM_TAGS,
			'articleImages' => Fisar_CDJ_Social_Publishing::article_images( $post ),
			'suggestion' => Fisar_CDJ_Social_Publishing::initial_text( $post ),
			'media' => array_map( static fn( int $id ): array => array(
				'id' => $id, 'variants' => array(
					'facebook' => Fisar_CDJ_Social_Publishing::media( $id, 'facebook' ),
					'instagram' => Fisar_CDJ_Social_Publishing::media( $id, 'instagram' ),
				),
			), array_values( array_unique( $ids ) ) ),
			'delivery' => Fisar_CDJ_Social_Publishing::deliveries( $post->ID ),
			'socialUrls' => self::social_urls( $post->ID ),
			'live' => Fisar_CDJ_Social_Publishing::is_live(), 'accounts' => $accounts,
			'canPublish' => current_user_can( 'publish_posts' ), 'timezone' => wp_timezone_string(),
		);
	}

	public static function action( WP_REST_Request $request ): array|WP_Error {
		$post = get_post( (int) $request['id'] );
		$raw = $request->get_json_params();
		$intent = $raw['intent'] ?? '';
		if ( ! in_array( $intent, array( 'save', 'send', 'refresh' ), true ) ) { return new WP_Error( 'social_action', 'Operazione non valida.', array( 'status' => 400 ) ); }
		if ( 'save' !== $intent && ! current_user_can( 'publish_posts' ) ) { return new WP_Error( 'social_forbidden', 'Non hai il permesso di pubblicare sui social.', array( 'status' => 403 ) ); }
		if ( 'refresh' === $intent ) {
			$result = Fisar_CDJ_Social_Publishing::refresh( $post->ID );
			return is_wp_error( $result ) ? $result : array( 'delivery' => $result, 'socialUrls' => self::social_urls( $post->ID ) );
		}
		if ( ! is_string( $raw['version'] ?? null ) || ! hash_equals( self::version( $post ), $raw['version'] ) ) {
			return new WP_Error( 'social_changed', 'L’articolo è cambiato. Aggiorna i dati dell’articolo e controlla la preparazione prima di inviare.', array( 'status' => 409 ) );
		}
		$composer = Fisar_CDJ_Social_Publishing::validate_composer( $raw['composer'] ?? null, $post );
		if ( is_wp_error( $composer ) ) { return $composer; }
		update_post_meta( $post->ID, Fisar_CDJ_Social_Publishing::COMPOSER_META, wp_slash( $composer ) );
		$result = 'send' === $intent ? Fisar_CDJ_Social_Publishing::send( $post, $composer ) : array( 'message' => 'Preparazione social salvata. Nessun post inviato.' );
		return is_wp_error( $result ) ? $result : array_merge( $result, array( 'socialUrls' => self::social_urls( $post->ID ) ) );
	}
}
