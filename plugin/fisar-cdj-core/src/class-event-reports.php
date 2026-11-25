<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Articles link to an event and/or course; their pages find public reports automatically. */
final class Fisar_CDJ_Event_Reports {
	public const EVENT_META = '_fisar_report_event_id';
	/** Keep historical multi-course relations readable without a data migration. */
	public const COURSE_META = '_fisar_report_course_id';
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
		register_post_meta( 'post', self::COURSE_META, array(
			'single' => false,
			'type' => 'integer',
			'show_in_rest' => false,
			'auth_callback' => static fn(): bool => current_user_can( 'edit_posts' ),
			'sanitize_callback' => array( self::class, 'sanitize_course_id' ),
		) );
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

	public static function sanitize_course_id( mixed $value ): int {
		if ( ! is_scalar( $value ) || ! ctype_digit( (string) $value ) || (int) $value < 1 ) {
			return 0;
		}
		$course = get_post( (int) $value );
		return $course && Fisar_CDJ_Post_Types::COURSE === $course->post_type && 'publish' === $course->post_status && '' === $course->post_password ? $course->ID : 0;
	}

	private static function is_public_event( int $event_id ): bool {
		if ( $event_id < 1 ) {
			return false;
		}
		$event = get_post( $event_id );
		return $event && Fisar_CDJ_Post_Types::EVENT === $event->post_type && 'publish' === $event->post_status && '' === $event->post_password;
	}

	public static function add_meta_box(): void {
		add_meta_box( 'fisar-post-report', 'Racconto di eventi e corsi', array( self::class, 'render' ), 'post', 'normal', 'default' );
	}

	/** Sort editions by their start date, never by their publication date. */
	private static function course_options(): array {
		$courses = get_posts( array(
			'post_type' => Fisar_CDJ_Post_Types::COURSE,
			'post_status' => 'publish',
			'has_password' => false,
			'posts_per_page' => -1,
		) );
		$options = array();
		foreach ( $courses as $course ) {
			$start = (string) get_post_meta( $course->ID, '_fisar_course_start_date', true );
			$date = DateTimeImmutable::createFromFormat( '!Y-m-d', $start, wp_timezone() );
			$year = Fisar_CDJ_Course_Filters::year( $course->ID );
			if ( $date && $date->format( 'Y-m-d' ) === $start ) {
				$sort_date = $start;
				$period = wp_date( 'm/Y', $date->getTimestamp(), wp_timezone() );
			} else {
				$sort_date = $year ? sprintf( '%04d-00-00', $year ) : '';
				$period = $year ? (string) $year : 'Data non disponibile';
			}
			$options[] = array( 'id' => $course->ID, 'date' => $sort_date, 'label' => $period . ' — ' . get_the_title( $course ) );
		}
		usort( $options, static fn( array $a, array $b ): int => strcmp( $b['date'], $a['date'] ) ?: $b['id'] <=> $a['id'] );
		return $options;
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
		$courses = self::course_options();
		$selected_courses = self::courses_for_report( $post->ID, true );
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
		<div class="fisar-field">
			<label for="<?php echo esc_attr( self::COURSE_META ); ?>"><strong>Corso collegato (facoltativo)</strong></label>
			<input type="hidden" name="_fisar_report_course_present" value="1">
			<select class="widefat" id="<?php echo esc_attr( self::COURSE_META ); ?>" name="<?php echo esc_attr( self::COURSE_META ); ?>" aria-describedby="fisar-report-courses-help">
				<option value="0">Nessun corso</option>
				<?php foreach ( $courses as $course ) : ?>
					<option value="<?php echo (int) $course['id']; ?>"<?php selected( $selected_courses[0] ?? 0, $course['id'] ); ?>><?php echo esc_html( $course['label'] ); ?></option>
				<?php endforeach; ?>
			</select>
			<p class="description" id="fisar-report-courses-help">Scegli il corso raccontato. I rimandi compaiono automaticamente nella pagina del corso e nell’articolo.</p>
			<?php if ( count( $selected_courses ) > 1 ) : ?><p class="description">Questo articolo è collegato a più corsi. Salvando verrà mantenuto solo il corso selezionato.</p><?php endif; ?>
		</div>
		<?php
	}

	/** Existing source URLs live beside the social publishing tools, outside the dialog. */
	public static function render_social_urls( WP_Post $post ): void {
		?>
		<div class="fisar-admin-section">
		<h3>Post pubblicati sui social</h3>
		<div class="fisar-admin-grid fisar-admin-grid--2">
			<?php foreach ( self::SOCIAL_FIELDS as $key => $label ) : ?>
				<div class="fisar-field">
					<label for="<?php echo esc_attr( $key ); ?>"><strong>URL del post <?php echo esc_html( $label ); ?> (facoltativo)</strong></label>
					<input class="widefat" type="url" id="<?php echo esc_attr( $key ); ?>" name="<?php echo esc_attr( $key ); ?>" value="<?php echo esc_attr( get_post_meta( $post->ID, $key, true ) ); ?>" aria-describedby="fisar-report-social-help">
					<input type="hidden" name="_fisar_report_social_original[<?php echo esc_attr( $key ); ?>]" value="<?php echo esc_attr( get_post_meta( $post->ID, $key, true ) ); ?>">
				</div>
			<?php endforeach; ?>
		</div>
		<p class="description" id="fisar-report-social-help">Incolla l’URL del singolo post, anche se pubblicato direttamente su Facebook o Instagram. I link compaiono in fondo all’articolo; lascia vuoto un canale se non è disponibile.</p>
		</div>
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
		// A new marker preserves links when an older checkbox editor submits its previous format.
		if ( isset( $_POST['_fisar_report_course_present'], $_POST[ self::COURSE_META ] ) && is_string( $_POST[ self::COURSE_META ] ) && ctype_digit( $_POST[ self::COURSE_META ] ) ) {
			$course_id = self::sanitize_course_id( wp_unslash( $_POST[ self::COURSE_META ] ) );
			$course_ids = $course_id ? array( $course_id ) : array();
			$stored_ids = array_map( 'intval', get_post_meta( $post_id, self::COURSE_META, false ) );
			foreach ( array_diff( $stored_ids, $course_ids ) as $course_id ) {
				delete_post_meta( $post_id, self::COURSE_META, $course_id );
			}
			foreach ( array_diff( $course_ids, $stored_ids ) as $course_id ) {
				add_post_meta( $post_id, self::COURSE_META, $course_id );
			}
		}
		foreach ( self::SOCIAL_FIELDS as $key => $label ) {
			if ( isset( $_POST[ $key ] ) && is_string( $_POST[ $key ] ) ) {
				$value = self::sanitize_url( wp_unslash( $_POST[ $key ] ) );
				$original = $_POST['_fisar_report_social_original'][ $key ] ?? null;
				// A link filled by Buffer after this editor loaded must survive an unchanged form.
				if ( is_string( $original ) && $value === self::sanitize_url( wp_unslash( $original ) ) && $value !== get_post_meta( $post_id, $key, true ) ) { continue; }
				update_post_meta( $post_id, $key, wp_slash( $value ) );
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
		$event_id = self::sanitize_event_id( get_post_meta( $post_id, self::EVENT_META, true ) );
		return Fisar_CDJ_Content_Visibility::is_unlisted( $event_id ) ? 0 : $event_id;
	}

	/** Find reports in reading order without changing the course itself. */
	public static function for_course( int $course_id ): array {
		if ( ! self::sanitize_course_id( $course_id ) ) {
			return array();
		}
		return get_posts( array(
			'post_type' => 'post',
			'post_status' => 'publish',
			'has_password' => false,
			'posts_per_page' => -1,
			'meta_key' => self::COURSE_META,
			'meta_value' => $course_id,
			'orderby' => array( 'date' => 'ASC', 'ID' => 'ASC' ),
		) );
	}

	public static function courses_for_report( int $post_id, bool $include_unlisted = false ): array {
		$ids = array_values( array_unique( array_filter( array_map( array( self::class, 'sanitize_course_id' ), get_post_meta( $post_id, self::COURSE_META, false ) ) ) ) );
		return $include_unlisted ? $ids : array_values( array_filter( $ids, static fn( $id ) => ! Fisar_CDJ_Content_Visibility::is_unlisted( $id ) ) );
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
