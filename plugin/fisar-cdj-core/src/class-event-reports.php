<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** A report belongs to one event; event pages find their published reports. */
final class Fisar_CDJ_Event_Reports {
	public const EVENT_META = '_fisar_report_event_id';
	public const SOCIAL_FIELDS = array(
		'_fisar_report_facebook_url'  => 'Facebook',
		'_fisar_report_instagram_url' => 'Instagram',
	);
	private const NONCE_ACTION = 'fisar_cdj_save_report';
	private const NONCE_NAME = 'fisar_cdj_report_nonce';

	public static function init(): void {
		add_action( 'init', array( self::class, 'register_meta' ) );
		add_action( 'add_meta_boxes_post', array( self::class, 'add_meta_box' ) );
		add_action( 'save_post_post', array( self::class, 'save' ) );
	}

	public static function register_meta(): void {
		register_post_meta( 'post', self::EVENT_META, array(
			'single' => true,
			'type' => 'integer',
			'show_in_rest' => false,
			'auth_callback' => static fn(): bool => current_user_can( 'edit_posts' ),
			'sanitize_callback' => array( self::class, 'sanitize_event_id' ),
		) );
		foreach ( self::SOCIAL_FIELDS as $key => $label ) {
			register_post_meta( 'post', $key, array(
				'single' => true,
				'type' => 'string',
				'show_in_rest' => false,
				'auth_callback' => static fn(): bool => current_user_can( 'edit_posts' ),
				'sanitize_callback' => array( self::class, 'sanitize_url' ),
			) );
		}
	}

	public static function sanitize_event_id( mixed $value ): int {
		if ( ! is_scalar( $value ) || ! ctype_digit( (string) $value ) ) {
			return 0;
		}
		$event_id = (int) $value;
		return self::is_public_event( $event_id ) ? $event_id : 0;
	}

	public static function sanitize_url( mixed $value ): string {
		return is_string( $value ) ? esc_url_raw( trim( $value ), array( 'http', 'https' ) ) : '';
	}

	private static function is_public_event( int $event_id ): bool {
		if ( $event_id < 1 ) {
			return false;
		}
		$event = get_post( $event_id );
		return $event && Fisar_CDJ_Post_Types::EVENT === $event->post_type && 'publish' === $event->post_status && '' === $event->post_password;
	}

	public static function add_meta_box(): void {
		add_meta_box( 'fisar-post-report', 'Racconto dell’evento', array( self::class, 'render' ), 'post', 'normal', 'default' );
	}

	public static function render( WP_Post $post ): void {
		wp_nonce_field( self::NONCE_ACTION, self::NONCE_NAME );
		$selected = (int) get_post_meta( $post->ID, self::EVENT_META, true );
		$events = get_posts( array(
			'post_type' => Fisar_CDJ_Post_Types::EVENT,
			'post_status' => 'publish',
			'has_password' => false,
			'posts_per_page' => -1,
			'meta_key' => '_fisar_event_date',
			'orderby' => array( 'meta_value' => 'DESC', 'ID' => 'DESC' ),
		) );
		?>
		<input type="hidden" name="_fisar_report_fields_present" value="1">
		<div class="fisar-field">
			<label for="<?php echo esc_attr( self::EVENT_META ); ?>"><strong>Evento collegato</strong></label>
			<select class="widefat" id="<?php echo esc_attr( self::EVENT_META ); ?>" name="<?php echo esc_attr( self::EVENT_META ); ?>">
				<option value="0">Nessun evento</option>
				<?php foreach ( $events as $event ) : ?>
					<option value="<?php echo (int) $event->ID; ?>"<?php selected( $selected, $event->ID ); ?>><?php echo esc_html( get_the_title( $event ) . ' — ' . get_post_meta( $event->ID, '_fisar_event_date', true ) ); ?></option>
				<?php endforeach; ?>
			</select>
			<p class="description">Quando pubblichi questo articolo, il collegamento al racconto compare automaticamente nella pagina dell’evento. Non occorre modificare l’evento.</p>
		</div>
		<div class="fisar-admin-grid fisar-admin-grid--2">
			<?php foreach ( self::SOCIAL_FIELDS as $key => $label ) : ?>
				<div class="fisar-field">
					<label for="<?php echo esc_attr( $key ); ?>"><strong>Racconto su <?php echo esc_html( $label ); ?> (facoltativo)</strong></label>
					<input class="widefat" type="url" id="<?php echo esc_attr( $key ); ?>" name="<?php echo esc_attr( $key ); ?>" value="<?php echo esc_attr( get_post_meta( $post->ID, $key, true ) ); ?>">
				</div>
			<?php endforeach; ?>
		</div>
		<p class="description">Incolla il link al singolo post. I collegamenti social compaiono in fondo al racconto; lascia vuoto un canale se non è disponibile.</p>
		<?php
	}

	public static function save( int $post_id ): void {
		if ( 'post' !== get_post_type( $post_id ) || wp_is_post_autosave( $post_id ) || wp_is_post_revision( $post_id ) || ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
		if ( ! isset( $_POST['_fisar_report_fields_present'], $_POST[ self::NONCE_NAME ] ) || ! is_string( $_POST[ self::NONCE_NAME ] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST[ self::NONCE_NAME ] ) ), self::NONCE_ACTION ) ) {
			return;
		}
		if ( isset( $_POST[ self::EVENT_META ] ) ) {
			update_post_meta( $post_id, self::EVENT_META, self::sanitize_event_id( wp_unslash( $_POST[ self::EVENT_META ] ) ) );
		}
		foreach ( self::SOCIAL_FIELDS as $key => $label ) {
			if ( isset( $_POST[ $key ] ) ) {
				update_post_meta( $post_id, $key, wp_slash( self::sanitize_url( wp_unslash( $_POST[ $key ] ) ) ) );
			}
		}
	}

	/** Draft, private, scheduled and password-protected reports never leak into an event. */
	public static function for_event( int $event_id ): array {
		if ( ! self::is_public_event( $event_id ) ) {
			return array();
		}
		return get_posts( array(
			'post_type' => 'post',
			'post_status' => 'publish',
			'has_password' => false,
			'posts_per_page' => -1,
			'meta_key' => self::EVENT_META,
			'meta_value' => $event_id,
			'orderby' => array( 'date' => 'DESC', 'ID' => 'DESC' ),
		) );
	}

	public static function event_for_report( int $post_id ): int {
		return self::sanitize_event_id( get_post_meta( $post_id, self::EVENT_META, true ) );
	}

	public static function social_links( int $post_id ): array {
		$links = array();
		foreach ( self::SOCIAL_FIELDS as $key => $label ) {
			$url = self::sanitize_url( get_post_meta( $post_id, $key, true ) );
			if ( '' !== $url ) {
				$links[] = array( 'label' => 'Guarda il racconto su ' . $label, 'url' => $url );
			}
		}
		return $links;
	}
}
