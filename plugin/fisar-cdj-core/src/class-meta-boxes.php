<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Fisar_CDJ_Meta_Boxes {
	private const NONCE_ACTION = 'fisar_cdj_save_meta';
	private const NONCE_NAME   = 'fisar_cdj_meta_nonce';

	private const EVENT_TEXT_FIELDS = array(
		'_fisar_event_date',
		'_fisar_event_welcome_time',
		'_fisar_event_start_time',
		'_fisar_event_end_time',
		'_fisar_event_mode',
		'_fisar_event_venue',
		'_fisar_event_address',
		'_fisar_event_city',
		'_fisar_event_province',
		'_fisar_event_platform',
		'_fisar_event_participation',
		'_fisar_event_member_price',
		'_fisar_event_non_member_price',
		'_fisar_event_whatsapp',
		'_fisar_event_email',
		'_fisar_event_phone',
		'_fisar_event_other_channel',
		'_fisar_event_deadline',
		'_fisar_event_deadline_type',
	);

	private const EVENT_URL_FIELDS = array(
		'_fisar_event_online_url',
		'_fisar_event_form_url',
	);

	private const EVENT_BOOLEAN_FIELDS = array(
		'_fisar_event_is_free',
		'_fisar_event_registration_required',
		'_fisar_event_limited_seats',
	);

	private const COURSE_TEXT_FIELDS = array(
		'_fisar_course_director',
		'_fisar_course_level',
		'_fisar_course_start_date',
		'_fisar_course_end_date',
		'_fisar_course_venue',
		'_fisar_course_address',
		'_fisar_course_city',
		'_fisar_course_province',
		'_fisar_course_whatsapp',
		'_fisar_course_email',
		'_fisar_course_phone',
		'_fisar_course_other_channel',
		'_fisar_course_deadline',
		'_fisar_course_deadline_type',
	);

	private const COURSE_URL_FIELDS = array(
		'_fisar_course_form_url',
	);

	private const RICH_TEXT_FIELDS = array(
		'_fisar_event_registration_notes',
		'_fisar_course_registration_notes',
		'_fisar_course_fee',
		'_fisar_course_membership',
		'_fisar_course_includes',
	);

	public static function init(): void {
		add_action( 'add_meta_boxes', array( self::class, 'add_meta_boxes' ) );
		add_action( 'save_post_' . Fisar_CDJ_Post_Types::EVENT, array( self::class, 'save_event' ) );
		add_action( 'save_post_' . Fisar_CDJ_Post_Types::COURSE, array( self::class, 'save_course' ) );
		add_action( 'admin_enqueue_scripts', array( self::class, 'enqueue_assets' ) );
		add_action( 'admin_notices', array( self::class, 'show_import_notice' ) );
	}

	public static function add_meta_boxes(): void {
		add_meta_box( 'fisar-event-schedule', 'Data e orari', array( self::class, 'render_event_schedule' ), Fisar_CDJ_Post_Types::EVENT, 'normal', 'high' );
		add_meta_box( 'fisar-event-location', 'Modalità e luogo', array( self::class, 'render_event_location' ), Fisar_CDJ_Post_Types::EVENT, 'normal', 'high' );
		add_meta_box( 'fisar-event-participation', 'Partecipazione e costi', array( self::class, 'render_event_participation' ), Fisar_CDJ_Post_Types::EVENT, 'normal', 'default' );
		add_meta_box( 'fisar-event-registration', 'Iscrizioni', array( self::class, 'render_event_registration' ), Fisar_CDJ_Post_Types::EVENT, 'normal', 'default' );
		add_meta_box( 'fisar-event-course', 'Corso collegato', array( self::class, 'render_event_course' ), Fisar_CDJ_Post_Types::EVENT, 'side', 'default' );

		add_meta_box( 'fisar-course-details', 'Dettagli del corso', array( self::class, 'render_course_details' ), Fisar_CDJ_Post_Types::COURSE, 'normal', 'high' );
		add_meta_box( 'fisar-course-location', 'Sede', array( self::class, 'render_course_location' ), Fisar_CDJ_Post_Types::COURSE, 'normal', 'default' );
		add_meta_box( 'fisar-course-registration', 'Iscrizioni', array( self::class, 'render_course_registration' ), Fisar_CDJ_Post_Types::COURSE, 'normal', 'default' );
		add_meta_box( 'fisar-course-offer', 'Quota e dotazione', array( self::class, 'render_course_offer' ), Fisar_CDJ_Post_Types::COURSE, 'normal', 'default' );
		add_meta_box( 'fisar-course-calendar', 'Calendario lezioni', array( self::class, 'render_course_calendar' ), Fisar_CDJ_Post_Types::COURSE, 'normal', 'default' );
	}

	public static function enqueue_assets( string $hook_suffix ): void {
		if ( ! in_array( $hook_suffix, array( 'post.php', 'post-new.php' ), true ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( ! $screen || ! in_array( $screen->post_type, array( Fisar_CDJ_Post_Types::EVENT, Fisar_CDJ_Post_Types::COURSE ), true ) ) {
			return;
		}

		wp_enqueue_style( 'fisar-cdj-admin', FISAR_CDJ_CORE_URL . 'assets/css/admin.css', array(), FISAR_CDJ_CORE_VERSION );
		wp_enqueue_script( 'fisar-cdj-admin', FISAR_CDJ_CORE_URL . 'assets/js/admin.js', array(), FISAR_CDJ_CORE_VERSION, true );
	}

	public static function render_event_schedule( WP_Post $post ): void {
		self::nonce_field();
		echo '<div class="fisar-admin-grid fisar-admin-grid--4">';
		self::input( $post->ID, '_fisar_event_date', 'Data evento', 'date', 'La data determina automaticamente se l’evento è futuro o concluso.', true );
		self::input( $post->ID, '_fisar_event_welcome_time', 'Ora accoglienza', 'time', 'Facoltativa.' );
		self::input( $post->ID, '_fisar_event_start_time', 'Ora inizio', 'time', '', true );
		self::input( $post->ID, '_fisar_event_end_time', 'Ora fine', 'time', 'Facoltativa.' );
		echo '</div>';
	}

	public static function render_event_location( WP_Post $post ): void {
		self::select(
			$post->ID,
			'_fisar_event_mode',
			'Modalità',
			array(
				'presence' => 'In presenza',
				'online'   => 'Online',
				'hybrid'   => 'Ibrido',
			),
			'Seleziona dove si svolge l’evento.'
		);

		echo '<div class="fisar-conditional fisar-admin-grid fisar-admin-grid--2" data-show-modes="presence,hybrid">';
		self::input( $post->ID, '_fisar_event_venue', 'Sede', 'text', 'Per esempio: Enoteca Regionale.' );
		self::input( $post->ID, '_fisar_event_address', 'Indirizzo', 'text' );
		self::input( $post->ID, '_fisar_event_city', 'Città', 'text' );
		self::input( $post->ID, '_fisar_event_province', 'Provincia', 'text', 'Sigla di due lettere.', false, 2 );
		echo '</div>';

		echo '<div class="fisar-conditional fisar-admin-grid fisar-admin-grid--2" data-show-modes="online,hybrid">';
		self::input( $post->ID, '_fisar_event_platform', 'Piattaforma', 'text', 'Per esempio: Zoom o Google Meet.' );
		self::input( $post->ID, '_fisar_event_online_url', 'Link online', 'url', 'Inserire un URL completo, incluso https://.' );
		echo '</div>';
	}

	public static function render_event_participation( WP_Post $post ): void {
		self::select(
			$post->ID,
			'_fisar_event_participation',
			'Chi può partecipare',
			array(
				'all'                   => 'Aperto a tutti',
				'members'               => 'Solo soci',
				'members_and_companions'=> 'Soci e accompagnatori',
			)
		);
		self::checkbox( $post->ID, '_fisar_event_is_free', 'Evento gratuito', 'Nasconde le quote e attiva il copy dedicato.' );
		echo '<div class="fisar-conditional fisar-admin-grid fisar-admin-grid--2" data-hide-when-checked="_fisar_event_is_free">';
		self::input( $post->ID, '_fisar_event_member_price', 'Quota soci', 'text', 'Testo libero breve, per esempio “€ 25”.' );
		self::input( $post->ID, '_fisar_event_non_member_price', 'Quota non soci', 'text' );
		echo '</div>';
		self::checkbox( $post->ID, '_fisar_event_limited_seats', 'Mostra avviso posti limitati', 'Non viene memorizzato il numero di posti.' );
	}

	public static function render_event_registration( WP_Post $post ): void {
		self::checkbox( $post->ID, '_fisar_event_registration_required', 'Iscrizione richiesta', 'Se non selezionato, i canali non vengono mostrati nel frontend.' );
		echo '<div class="fisar-conditional" data-show-when-checked="_fisar_event_registration_required">';
		self::render_registration_fields( $post->ID, 'event' );
		self::editor( $post->ID, '_fisar_event_registration_notes', 'Informazioni aggiuntive', 'Dettagli utili non coperti dai campi precedenti.' );
		echo '</div>';
	}

	public static function render_event_course( WP_Post $post ): void {
		$courses = get_posts(
			array(
				'post_type'      => Fisar_CDJ_Post_Types::COURSE,
				'post_status'    => array( 'publish', 'draft', 'future' ),
				'posts_per_page' => -1,
				'orderby'        => 'title',
				'order'          => 'ASC',
			)
		);
		$current = absint( get_post_meta( $post->ID, '_fisar_event_course_id', true ) );

		echo '<p><label for="_fisar_event_course_id"><strong>Corso collegato</strong></label></p>';
		echo '<select class="widefat" id="_fisar_event_course_id" name="_fisar_event_course_id">';
		echo '<option value="0">Nessun corso</option>';
		foreach ( $courses as $course ) {
			printf( '<option value="%1$d"%2$s>%3$s</option>', $course->ID, selected( $current, $course->ID, false ), esc_html( $course->post_title ) );
		}
		echo '</select>';
		echo '<p class="description">La pagina del corso mostrerà automaticamente questo evento.</p>';
	}

	public static function render_course_details( WP_Post $post ): void {
		self::nonce_field();
		echo '<div class="fisar-admin-grid fisar-admin-grid--2">';
		self::input( $post->ID, '_fisar_course_director', 'Direttore del Corso', 'text', 'Nome e cognome.', true );
		self::select(
			$post->ID,
			'_fisar_course_level',
			'Livello',
			array(
				'1' => '1° livello',
				'2' => '2° livello',
				'3' => '3° livello',
			),
			'Il livello FISAR del percorso.'
		);
		self::input( $post->ID, '_fisar_course_start_date', 'Data inizio', 'date', '', true );
		self::input( $post->ID, '_fisar_course_end_date', 'Data fine', 'date', 'Determina automaticamente se il corso è attivo o concluso.', true );
		echo '</div>';
	}

	public static function render_course_location( WP_Post $post ): void {
		echo '<p class="description">Se la sede non è ancora definita, è sufficiente indicare città e provincia.</p>';
		echo '<div class="fisar-admin-grid fisar-admin-grid--2">';
		self::input( $post->ID, '_fisar_course_venue', 'Sede', 'text' );
		self::input( $post->ID, '_fisar_course_address', 'Indirizzo', 'text' );
		self::input( $post->ID, '_fisar_course_city', 'Città', 'text', '', true );
		self::input( $post->ID, '_fisar_course_province', 'Provincia', 'text', 'Sigla di due lettere.', true, 2 );
		echo '</div>';
	}

	public static function render_course_registration( WP_Post $post ): void {
		self::render_registration_fields( $post->ID, 'course' );
		self::editor( $post->ID, '_fisar_course_registration_notes', 'Informazioni aggiuntive', 'Modalità o dettagli utili per l’iscrizione.' );
	}

	public static function render_course_offer( WP_Post $post ): void {
		self::editor( $post->ID, '_fisar_course_fee', 'Quota di partecipazione', 'Può includere quota standard, Early Bird, Under 25, gruppi e modalità di pagamento.' );
		self::editor( $post->ID, '_fisar_course_membership', 'Tesseramento FISAR', 'Tenere separato dal costo del corso.' );
		self::editor( $post->ID, '_fisar_course_includes', 'Cosa comprende il corso', 'Per esempio kit, manuali, calici, degustazioni, software e attestato.' );
	}

	public static function render_course_calendar( WP_Post $post ): void {
		$rows = fisar_cdj_get_course_calendar( $post->ID );
		?>
		<p>Incolla da Excel o Google Sheets le colonne <strong>Data, Orario, Titolo, Relatore, Note</strong>, separate da tabulazioni. Salvando il corso, il testo viene convertito in righe strutturate.</p>
		<label for="fisar_course_calendar_import"><strong>Dati tabulati da importare</strong></label>
		<textarea class="widefat code" id="fisar_course_calendar_import" name="fisar_course_calendar_import" rows="6" placeholder="Data&#9;Orario&#9;Titolo lezione&#9;Relatore&#9;Note"></textarea>
		<p><button type="button" class="button" id="fisar-calendar-preview">Importa e mostra anteprima</button></p>
		<div class="fisar-calendar-table-wrap">
			<table class="widefat striped" id="fisar-calendar-table">
				<thead><tr><th>Data</th><th>Orario</th><th>Titolo lezione</th><th>Relatore</th><th>Note</th><th><span class="screen-reader-text">Azioni</span></th></tr></thead>
				<tbody>
				<?php foreach ( $rows as $index => $row ) : ?>
					<?php self::calendar_row( $index, $row ); ?>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		<p><button type="button" class="button" id="fisar-calendar-add-row">Aggiungi una riga</button></p>
		<p class="description"><?php echo esc_html( Fisar_CDJ_Calendar_Importer::NOTICE ); ?></p>
		<?php
	}

	public static function save_event( int $post_id ): void {
		if ( ! self::can_save( $post_id ) ) {
			return;
		}

		self::save_text_fields( $post_id, self::EVENT_TEXT_FIELDS );
		self::save_url_fields( $post_id, self::EVENT_URL_FIELDS );
		self::save_boolean_fields( $post_id, self::EVENT_BOOLEAN_FIELDS );
		self::save_rich_fields( $post_id, array( '_fisar_event_registration_notes' ) );
		update_post_meta( $post_id, '_fisar_event_course_id', absint( $_POST['_fisar_event_course_id'] ?? 0 ) );
	}

	public static function save_course( int $post_id ): void {
		if ( ! self::can_save( $post_id ) ) {
			return;
		}

		self::save_text_fields( $post_id, self::COURSE_TEXT_FIELDS );
		self::save_url_fields( $post_id, self::COURSE_URL_FIELDS );
		self::save_rich_fields( $post_id, array_diff( self::RICH_TEXT_FIELDS, array( '_fisar_event_registration_notes' ) ) );

		$rows       = Fisar_CDJ_Calendar_Importer::sanitize_rows( wp_unslash( $_POST['fisar_course_calendar'] ?? array() ) );
		$import_tsv = trim( (string) wp_unslash( $_POST['fisar_course_calendar_import'] ?? '' ) );
		if ( '' !== $import_tsv ) {
			$result = Fisar_CDJ_Calendar_Importer::parse( $import_tsv );
			if ( ! empty( $result['rows'] ) ) {
				$rows = $result['rows'];
			}
			self::store_import_notice( $result );
		}
		update_post_meta( $post_id, '_fisar_course_calendar', $rows );
	}

	public static function show_import_notice(): void {
		$key    = 'fisar_cdj_calendar_notice_' . get_current_user_id();
		$notice = get_transient( $key );
		if ( ! is_array( $notice ) ) {
			return;
		}
		delete_transient( $key );

		$class = empty( $notice['errors'] ) ? 'notice-success' : 'notice-warning';
		echo '<div class="notice ' . esc_attr( $class ) . ' is-dismissible"><p>';
		printf( 'Calendario: %d righe importate.', absint( $notice['count'] ?? 0 ) );
		if ( ! empty( $notice['errors'] ) ) {
			echo ' ' . esc_html( implode( ' ', $notice['errors'] ) );
		}
		echo '</p></div>';
	}

	private static function render_registration_fields( int $post_id, string $prefix ): void {
		echo '<div class="fisar-admin-grid fisar-admin-grid--2">';
		self::input( $post_id, "_fisar_{$prefix}_whatsapp", 'WhatsApp', 'text', 'Numero con prefisso internazionale (es. +39 …), oppure link completo alla chat o al canale. Senza prefisso il numero resta visibile, ma non viene generato un link alla chat.' );
		self::input( $post_id, "_fisar_{$prefix}_email", 'Email', 'email' );
		self::input( $post_id, "_fisar_{$prefix}_phone", 'Telefono', 'tel' );
		self::input( $post_id, "_fisar_{$prefix}_form_url", 'Modulo online', 'url', 'Link completo al modulo.' );
		self::input( $post_id, "_fisar_{$prefix}_other_channel", 'Altro canale', 'text' );
		self::input( $post_id, "_fisar_{$prefix}_deadline", 'Termine prenotazioni', 'date' );
		echo '</div>';
		self::select(
			$post_id,
			"_fisar_{$prefix}_deadline_type",
			'Tipo di termine',
			array(
				'strict'   => 'Tassativo',
				'flexible' => 'Flessibile',
			),
			'Con il termine flessibile, il frontend invita comunque a contattare la Delegazione.'
		);
	}

	private static function input( int $post_id, string $key, string $label, string $type = 'text', string $description = '', bool $required = false, ?int $maxlength = null ): void {
		$value = (string) get_post_meta( $post_id, $key, true );
		printf( '<div class="fisar-field"><label for="%1$s"><strong>%2$s%3$s</strong></label>', esc_attr( $key ), esc_html( $label ), $required ? ' <span aria-hidden="true">*</span>' : '' );
		printf(
			'<input class="widefat" type="%1$s" id="%2$s" name="%2$s" value="%3$s"%4$s%5$s>',
			esc_attr( $type ),
			esc_attr( $key ),
			esc_attr( $value ),
			$required ? ' required' : '',
			$maxlength ? ' maxlength="' . absint( $maxlength ) . '"' : ''
		);
		if ( '' !== $description ) {
			printf( '<p class="description">%s</p>', esc_html( $description ) );
		}
		echo '</div>';
	}

	private static function select( int $post_id, string $key, string $label, array $options, string $description = '' ): void {
		$value = (string) get_post_meta( $post_id, $key, true );
		echo '<div class="fisar-field">';
		printf( '<label for="%1$s"><strong>%2$s</strong></label>', esc_attr( $key ), esc_html( $label ) );
		printf( '<select class="widefat" id="%1$s" name="%1$s">', esc_attr( $key ) );
		echo '<option value="">Seleziona…</option>';
		foreach ( $options as $option_value => $option_label ) {
			printf( '<option value="%1$s"%2$s>%3$s</option>', esc_attr( $option_value ), selected( $value, $option_value, false ), esc_html( $option_label ) );
		}
		echo '</select>';
		if ( '' !== $description ) {
			printf( '<p class="description">%s</p>', esc_html( $description ) );
		}
		echo '</div>';
	}

	private static function checkbox( int $post_id, string $key, string $label, string $description = '' ): void {
		$checked = (bool) get_post_meta( $post_id, $key, true );
		echo '<div class="fisar-field fisar-field--checkbox">';
		printf( '<label><input type="checkbox" id="%1$s" name="%1$s" value="1"%2$s> <strong>%3$s</strong></label>', esc_attr( $key ), checked( $checked, true, false ), esc_html( $label ) );
		if ( '' !== $description ) {
			printf( '<p class="description">%s</p>', esc_html( $description ) );
		}
		echo '</div>';
	}

	private static function editor( int $post_id, string $key, string $label, string $description = '' ): void {
		$value = (string) get_post_meta( $post_id, $key, true );
		echo '<div class="fisar-field fisar-field--editor">';
		printf( '<p><strong>%s</strong></p>', esc_html( $label ) );
		if ( '' !== $description ) {
			printf( '<p class="description">%s</p>', esc_html( $description ) );
		}
		wp_editor(
			$value,
			trim( $key, '_' ),
			array(
				'textarea_name' => $key,
				'textarea_rows' => 5,
				'teeny'         => true,
				'media_buttons' => false,
			)
		);
		echo '</div>';
	}

	private static function calendar_row( int $index, array $row = array() ): void {
		$fields = array( 'date', 'time', 'title', 'speaker', 'notes' );
		echo '<tr>';
		foreach ( $fields as $field ) {
			$type  = 'date' === $field ? 'date' : 'text';
			$value = (string) ( $row[ $field ] ?? '' );
			printf(
				'<td><input type="%1$s" name="fisar_course_calendar[%2$d][%3$s]" value="%4$s" aria-label="%5$s"></td>',
				esc_attr( $type ),
				absint( $index ),
				esc_attr( $field ),
				esc_attr( $value ),
				esc_attr( ucfirst( $field ) )
			);
		}
		echo '<td><button type="button" class="button-link-delete fisar-calendar-remove">Rimuovi</button></td></tr>';
	}

	private static function nonce_field(): void {
		if ( ! did_action( 'fisar_cdj_nonce_rendered' ) ) {
			wp_nonce_field( self::NONCE_ACTION, self::NONCE_NAME );
			do_action( 'fisar_cdj_nonce_rendered' );
		}
	}

	private static function can_save( int $post_id ): bool {
		if ( wp_is_post_autosave( $post_id ) || wp_is_post_revision( $post_id ) ) {
			return false;
		}
		if ( ! isset( $_POST[ self::NONCE_NAME ] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST[ self::NONCE_NAME ] ) ), self::NONCE_ACTION ) ) {
			return false;
		}

		return current_user_can( 'edit_post', $post_id );
	}

	private static function save_text_fields( int $post_id, array $fields ): void {
		$allowed_values = array(
			'_fisar_event_mode'             => array( 'presence', 'online', 'hybrid' ),
			'_fisar_event_participation'    => array( 'all', 'members', 'members_and_companions' ),
			'_fisar_event_deadline_type'    => array( 'strict', 'flexible' ),
			'_fisar_course_level'           => array( '1', '2', '3' ),
			'_fisar_course_deadline_type'   => array( 'strict', 'flexible' ),
		);
		foreach ( $fields as $field ) {
			$value = sanitize_text_field( wp_unslash( $_POST[ $field ] ?? '' ) );
			if ( isset( $allowed_values[ $field ] ) && ! in_array( $value, $allowed_values[ $field ], true ) ) {
				$value = '';
			} elseif ( str_ends_with( $field, '_date' ) || str_ends_with( $field, '_deadline' ) ) {
				$value = self::sanitize_date( $value );
			} elseif ( str_ends_with( $field, '_time' ) && ! preg_match( '/^(?:[01]\d|2[0-3]):[0-5]\d$/', $value ) ) {
				$value = '';
			} elseif ( str_ends_with( $field, '_province' ) ) {
				$value = strtoupper( substr( preg_replace( '/[^a-z]/i', '', $value ), 0, 2 ) );
			} elseif ( str_ends_with( $field, '_email' ) ) {
				$value = sanitize_email( $value );
			}
			update_post_meta( $post_id, $field, $value );
		}
	}

	private static function sanitize_date( string $value ): string {
		if ( ! preg_match( '/^(\d{4})-(\d{2})-(\d{2})$/', $value, $matches ) ) {
			return '';
		}

		return checkdate( (int) $matches[2], (int) $matches[3], (int) $matches[1] ) ? $value : '';
	}

	private static function save_url_fields( int $post_id, array $fields ): void {
		foreach ( $fields as $field ) {
			$value = esc_url_raw( wp_unslash( $_POST[ $field ] ?? '' ) );
			update_post_meta( $post_id, $field, $value );
		}
	}

	private static function save_boolean_fields( int $post_id, array $fields ): void {
		foreach ( $fields as $field ) {
			update_post_meta( $post_id, $field, isset( $_POST[ $field ] ) ? 1 : 0 );
		}
	}

	private static function save_rich_fields( int $post_id, array $fields ): void {
		foreach ( $fields as $field ) {
			$value = wp_kses_post( wp_unslash( $_POST[ $field ] ?? '' ) );
			update_post_meta( $post_id, $field, $value );
		}
	}

	private static function store_import_notice( array $result ): void {
		set_transient(
			'fisar_cdj_calendar_notice_' . get_current_user_id(),
			array(
				'count'  => count( $result['rows'] ?? array() ),
				'errors' => $result['errors'] ?? array(),
			),
			MINUTE_IN_SECONDS
		);
	}
}
