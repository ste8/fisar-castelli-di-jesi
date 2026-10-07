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
		'_fisar_event_maps_url',
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
		add_meta_box( 'fisar-event-schedule', 'Dettagli dell’evento', array( self::class, 'render_event_schedule' ), Fisar_CDJ_Post_Types::EVENT, 'normal', 'high' );
		add_meta_box( 'fisar-event-location', 'Modalità e luogo', array( self::class, 'render_event_location' ), Fisar_CDJ_Post_Types::EVENT, 'normal', 'high' );
		add_meta_box( 'fisar-event-participation', 'Partecipazione e costi', array( self::class, 'render_event_participation' ), Fisar_CDJ_Post_Types::EVENT, 'normal', 'default' );
		add_meta_box( 'fisar-event-registration', 'Iscrizioni', array( self::class, 'render_event_registration' ), Fisar_CDJ_Post_Types::EVENT, 'normal', 'default' );
		add_meta_box( 'fisar-event-course', 'Corso collegato', array( self::class, 'render_event_course' ), Fisar_CDJ_Post_Types::EVENT, 'side', 'default' );

		add_meta_box( 'fisar-course-details', 'Dettagli del corso', array( self::class, 'render_course_details' ), Fisar_CDJ_Post_Types::COURSE, 'normal', 'high' );
		add_meta_box( 'fisar-course-location', 'Sede', array( self::class, 'render_course_location' ), Fisar_CDJ_Post_Types::COURSE, 'normal', 'default' );
		add_meta_box( 'fisar-course-registration', 'Iscrizioni', array( self::class, 'render_course_registration' ), Fisar_CDJ_Post_Types::COURSE, 'normal', 'default' );
		add_meta_box( 'fisar-course-offer', 'Quota e dotazione', array( self::class, 'render_course_offer' ), Fisar_CDJ_Post_Types::COURSE, 'normal', 'default' );
		add_meta_box( 'fisar-course-calendar', 'Calendario lezioni', array( self::class, 'render_course_calendar' ), Fisar_CDJ_Post_Types::COURSE, 'normal', 'default' );

		// The native excerpt is rendered inside the main details box, not duplicated.
		foreach ( array( Fisar_CDJ_Post_Types::EVENT, Fisar_CDJ_Post_Types::COURSE ) as $post_type ) {
			remove_meta_box( 'postexcerpt', $post_type, 'normal' );
		}
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
		if ( Fisar_CDJ_Post_Types::COURSE === $screen->post_type ) {
			$dependencies = $screen->is_block_editor() ? array( 'wp-dom-ready', 'wp-data', 'wp-editor' ) : array( 'wp-dom-ready' );
			wp_enqueue_script( 'fisar-cdj-course-title', FISAR_CDJ_CORE_URL . 'assets/js/course-title.js', $dependencies, FISAR_CDJ_CORE_VERSION, true );
		}
		if ( $screen->is_block_editor() ) {
			wp_enqueue_script( 'fisar-cdj-editor-excerpt', FISAR_CDJ_CORE_URL . 'assets/js/editor-excerpt.js', array( 'wp-data', 'wp-dom-ready', 'wp-editor' ), FISAR_CDJ_CORE_VERSION, true );
		}
	}

	public static function render_event_schedule( WP_Post $post ): void {
		self::nonce_field();
		self::render_excerpt( $post );
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
		self::input( $post->ID, '_fisar_event_maps_url', 'Link Google Maps', 'url', 'Facoltativo. Incolla il link della posizione condiviso da Google Maps, incluso https://. Nel sito compare solo il collegamento, senza mappa incorporata.' );
		echo '</div>';

		echo '<div class="fisar-conditional" data-show-modes="online,hybrid">';
		echo '<div class="fisar-admin-grid fisar-admin-grid--2">';
		self::input( $post->ID, '_fisar_event_platform', 'Piattaforma', 'text', 'Per esempio: Zoom o Google Meet.' );
		self::input( $post->ID, '_fisar_event_online_url', 'Link online', 'url', 'Inserire un URL completo, incluso https://.' );
		echo '</div>';
		echo '<input type="hidden" name="_fisar_event_online_access_present" value="1">';
		self::checkbox( $post->ID, '_fisar_event_online_access_public', 'Mostra le informazioni per partecipare online', 'Disattivato per default, anche per gli eventi già inseriti. Attiva per rendere pubblici piattaforma e link di accesso nella pagina dell’evento. Se disattivato, i dati restano salvati ma non compaiono sul sito. Non modifica i canali di prenotazione.' );
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
		$fee_help = 'Inserisci un importo senza valuta, per esempio “25”: il simbolo € verrà aggiunto automaticamente. Oppure un testo breve, come “Da 25 €” o “Offerta libera”.';
		echo '<div class="fisar-conditional" data-hide-when-checked="_fisar_event_is_free">';
		echo '<div class="fisar-admin-grid fisar-admin-grid--2">';
		self::input( $post->ID, '_fisar_event_member_price', 'Quota soci', 'text', $fee_help );
		self::input( $post->ID, '_fisar_event_non_member_price', 'Quota non soci', 'text', $fee_help );
		echo '</div>';
		self::render_event_fee_options( $post->ID );
		echo '</div>';
		self::checkbox( $post->ID, '_fisar_event_limited_seats', 'Mostra avviso posti limitati', 'Non viene memorizzato il numero di posti.' );
	}

	public static function render_event_registration( WP_Post $post ): void {
		self::checkbox( $post->ID, '_fisar_event_registration_required', 'Iscrizione richiesta', 'Se non selezionato, i canali non vengono mostrati nel frontend.' );
		echo '<div class="fisar-conditional" data-show-when-checked="_fisar_event_registration_required">';
		self::select(
			$post->ID,
			'_fisar_event_booking_status',
			'Disponibilità dell’evento',
			array( 'available' => 'Ordinaria (non sold-out)', 'sold_out' => 'Sold-out', 'waitlist' => 'Sold-out con lista d’attesa' ),
			'Con la lista d’attesa restano disponibili gli stessi contatti delle prenotazioni. Un termine tassativo superato chiude anche la lista d’attesa. Questa impostazione si applica solo con Iscrizione richiesta attivo.',
			'available'
		);
		self::render_registration_fields( $post->ID, 'event' );
		self::editor( $post->ID, '_fisar_event_registration_notes', 'Informazioni aggiuntive', 'Dettagli utili non coperti dai campi precedenti.' );
		echo '</div>';
	}

	private static function render_event_fee_options( int $post_id ): void {
		$options = fisar_cdj_sanitize_event_fee_options( get_post_meta( $post_id, '_fisar_event_fee_options', true ) );
		$note    = get_post_meta( $post_id, '_fisar_event_fee_note', true );
		?>
		<input type="hidden" name="_fisar_event_fees_present" value="1">
		<div class="fisar-field">
			<label for="_fisar_event_fee_note"><strong>Nota generale sulle quote</strong></label>
			<p class="description" id="fisar-event-fee-note-help">Facoltativa. Spiega cosa comprendono le quote, per esempio “Le quote soci e non soci comprendono il menu con abbinamento vini”.</p>
			<textarea class="widefat" id="_fisar_event_fee_note" name="_fisar_event_fee_note" rows="3" aria-describedby="fisar-event-fee-note-help"><?php echo esc_textarea( is_string( $note ) ? $note : '' ); ?></textarea>
		</div>
		<h3>Altre opzioni di partecipazione</h3>
		<p class="description" id="fisar-event-fee-options-help">Voci facoltative, nell’ordine di inserimento. Compila etichetta e importo: le righe incomplete non vengono mostrate. Nella nota specifica cosa comprende la quota e a chi si applica; se è un supplemento, scrivilo esplicitamente nell’etichetta e nell’importo.</p>
		<div id="fisar-event-fee-options" data-next-index="<?php echo count( $options ) + 1; ?>">
			<?php foreach ( $options as $index => $option ) { self::event_fee_option( $index, $option ); } ?>
			<?php self::event_fee_option( count( $options ) ); ?>
		</div>
		<template id="fisar-event-fee-option-template"><?php self::event_fee_option( '__INDEX__' ); ?></template>
		<p><button type="button" class="button" id="fisar-event-fee-add" hidden>Aggiungi quota</button></p>
		<p class="description" id="fisar-event-fee-noscript">Senza JavaScript puoi compilare la riga vuota e salvare: al caricamento successivo ne troverai un’altra. Per rimuovere una voce, svuota etichetta e importo.</p>
		<span class="screen-reader-text" id="fisar-event-fee-status" role="status" aria-live="polite"></span>
		<?php
	}

	private static function event_fee_option( int|string $index, array $option = array() ): void {
		?>
		<fieldset class="fisar-event-fee-option" aria-describedby="fisar-event-fee-options-help">
			<legend><strong>Quota personalizzata</strong></legend>
			<div class="fisar-admin-grid fisar-admin-grid--2">
				<?php foreach ( array( 'label' => 'Etichetta', 'amount' => 'Importo' ) as $key => $label ) : ?>
					<div class="fisar-field">
						<label for="fisar-event-fee-<?php echo esc_attr( $index . '-' . $key ); ?>"><strong><?php echo esc_html( $label ); ?></strong></label>
						<input class="widefat" type="text" id="fisar-event-fee-<?php echo esc_attr( $index . '-' . $key ); ?>" name="_fisar_event_fee_options[<?php echo esc_attr( $index ); ?>][<?php echo esc_attr( $key ); ?>]" value="<?php echo esc_attr( $option[ $key ] ?? '' ); ?>">
					</div>
				<?php endforeach; ?>
			</div>
			<div class="fisar-field">
				<label for="fisar-event-fee-<?php echo esc_attr( $index ); ?>-note"><strong>Nota facoltativa</strong></label>
				<textarea class="widefat" id="fisar-event-fee-<?php echo esc_attr( $index ); ?>-note" name="_fisar_event_fee_options[<?php echo esc_attr( $index ); ?>][note]" rows="2"><?php echo esc_textarea( $option['note'] ?? '' ); ?></textarea>
			</div>
			<button type="button" class="button-link-delete fisar-event-fee-remove" aria-label="Rimuovi questa quota personalizzata" hidden>Rimuovi quota</button>
		</fieldset>
		<?php
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
		$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
		echo '<div id="fisar-course-title-settings" data-editor="' . ( $screen && $screen->is_block_editor() ? 'block' : 'classic' ) . '">';
		self::select( $post->ID, '_fisar_course_title_mode', 'Titolo del corso', array( 'automatic' => 'Automatico — da livello e città', 'custom' => 'Personalizzato — titolo libero' ), 'Automatico compone il titolo al salvataggio. Per un nome diverso scegli Personalizzato e usa il titolo WordPress in alto. Controlla Città e Provincia nel box Sede.', fisar_cdj_get_course_title_mode( $post->ID ) );
		echo '<p class="description">Titolo automatico: <output id="fisar-course-title-preview">' . esc_html( fisar_cdj_compose_course_title( (string) get_post_meta( $post->ID, '_fisar_course_level', true ), (string) get_post_meta( $post->ID, '_fisar_course_city', true ), (string) get_post_meta( $post->ID, '_fisar_course_province', true ) ) ) . '</output></p></div>';
		self::render_excerpt( $post );
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
		echo '<input type="hidden" name="_fisar_course_maps_present" value="1">';
		self::input( $post->ID, '_fisar_course_maps_url', 'Link Google Maps', 'url', 'Facoltativo. Incolla il link della posizione condiviso da Google Maps, incluso https://. Nel sito compare solo il collegamento, senza mappa incorporata.' );
		echo '</div>';
	}

	public static function render_course_registration( WP_Post $post ): void {
		self::select( $post->ID, '_fisar_course_booking_status', 'Disponibilità del corso', array( 'available' => 'Ordinaria (non sold-out)', 'sold_out' => 'Sold-out', 'waitlist' => 'Sold-out con lista d’attesa' ), 'La lista d’attesa usa gli stessi contatti delle iscrizioni. Un termine tassativo superato chiude anche la lista d’attesa. Un corso concluso non accetta iscrizioni.', 'available' );
		echo '<input type="hidden" name="_fisar_course_booking_present" value="1">';
		self::checkbox( $post->ID, '_fisar_course_limited_seats', 'Mostra avviso posti limitati', 'Non viene memorizzato il numero di posti.' );
		self::render_registration_fields( $post->ID, 'course' );
		self::editor( $post->ID, '_fisar_course_registration_notes', 'Informazioni aggiuntive', 'Modalità o dettagli utili per l’iscrizione.' );
	}

	public static function render_course_offer( WP_Post $post ): void {
		echo '<input type="hidden" name="_fisar_course_offer_present" value="1">';
		self::checkbox( $post->ID, '_fisar_course_offer_enabled', 'Corso in offerta', 'Mostra una fascia in home, elenco e dettaglio. Non modifica la quota: specifica importo e condizioni nel campo Quota di partecipazione. La fascia non compare se il corso è concluso, sold-out o le iscrizioni sono chiuse.' );
		self::input( $post->ID, '_fisar_course_offer_end_date', 'Fine offerta (facoltativa)', 'date', 'Offerta valida fino a questa data inclusa, nel fuso del sito. Dal giorno successivo la fascia scompare. Senza data resta finché disattivi Corso in offerta; la data da sola non attiva l’offerta. Il termine d’iscrizione è distinto.' );
		self::editor( $post->ID, '_fisar_course_fee', 'Quota di partecipazione', 'Può includere quota standard, Early Bird, Under 25, gruppi e modalità di pagamento. Se inserisci solo un importo numerico, per esempio 590, il sito aggiunge €. Nei testi con condizioni specifiche indica anche la valuta.' );
		self::editor( $post->ID, '_fisar_course_includes', 'Cosa comprende il corso', 'Per esempio kit, manuali, calici, degustazioni, software e attestato.' );
		self::editor( $post->ID, '_fisar_course_membership', 'Tesseramento FISAR', 'Tenere separato dal costo del corso.' );
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

		$text_fields = self::EVENT_TEXT_FIELDS;
		if ( ! isset( $_POST['_fisar_event_whatsapp'] ) || metadata_exists( 'post', $post_id, '_fisar_event_whatsapp_contacts' ) ) {
			$text_fields = array_diff( $text_fields, array( '_fisar_event_whatsapp' ) );
		}
		self::save_text_fields( $post_id, $text_fields );
		if ( isset( $_POST['_fisar_event_booking_status'] ) ) {
			update_post_meta( $post_id, '_fisar_event_booking_status', fisar_cdj_sanitize_event_booking_status( wp_unslash( $_POST['_fisar_event_booking_status'] ) ) );
		}
		if ( isset( $_POST['_fisar_event_whatsapp_contacts_present'] ) ) {
			$contacts = fisar_cdj_sanitize_event_whatsapp_contacts( wp_unslash( $_POST['_fisar_event_whatsapp_contacts'] ?? array() ) );
			update_post_meta( $post_id, '_fisar_event_whatsapp_contacts', wp_slash( $contacts ) );
			// Keep the first destination readable by legacy consumers, including empty lists.
			update_post_meta( $post_id, '_fisar_event_whatsapp', wp_slash( $contacts[0]['value'] ?? '' ) );
		}
		self::save_url_fields( $post_id, self::EVENT_URL_FIELDS );
		if ( isset( $_POST['_fisar_event_online_access_present'] ) ) {
			update_post_meta( $post_id, '_fisar_event_online_access_public', '1' === ( $_POST['_fisar_event_online_access_public'] ?? '' ) );
		}
		self::save_boolean_fields( $post_id, self::EVENT_BOOLEAN_FIELDS );
		self::save_rich_fields( $post_id, array( '_fisar_event_registration_notes' ) );
		if ( isset( $_POST['_fisar_event_fees_present'] ) ) {
			$options = fisar_cdj_sanitize_event_fee_options( wp_unslash( $_POST['_fisar_event_fee_options'] ?? array() ) );
			update_post_meta( $post_id, '_fisar_event_fee_options', wp_slash( $options ) );
			$fee_note = wp_unslash( $_POST['_fisar_event_fee_note'] ?? '' );
			update_post_meta( $post_id, '_fisar_event_fee_note', is_string( $fee_note ) ? wp_slash( sanitize_textarea_field( $fee_note ) ) : '' );
		}
		update_post_meta( $post_id, '_fisar_event_course_id', absint( $_POST['_fisar_event_course_id'] ?? 0 ) );
	}

	public static function save_course( int $post_id ): void {
		if ( ! self::can_save( $post_id ) ) {
			return;
		}

		$text_fields = self::COURSE_TEXT_FIELDS;
		if ( ! isset( $_POST['_fisar_course_whatsapp'] ) || metadata_exists( 'post', $post_id, '_fisar_course_whatsapp_contacts' ) ) {
			$text_fields = array_diff( $text_fields, array( '_fisar_course_whatsapp' ) );
		}
		self::save_text_fields( $post_id, $text_fields );
		if ( isset( $_POST['_fisar_course_offer_present'] ) ) {
			$raw_date = wp_unslash( $_POST['_fisar_course_offer_end_date'] ?? '' );
			$end_date = fisar_cdj_sanitize_course_offer_end_date( $raw_date );
			$enabled = in_array( wp_unslash( $_POST['_fisar_course_offer_enabled'] ?? false ), array( true, 1, '1' ), true );
			$enabled = $enabled && ( '' === $raw_date || '' !== $end_date );
			update_post_meta( $post_id, '_fisar_course_offer_enabled', $enabled ? 1 : 0 );
			update_post_meta( $post_id, '_fisar_course_offer_end_date', $end_date );
		}
		if ( isset( $_POST['_fisar_course_booking_status'] ) ) {
			update_post_meta( $post_id, '_fisar_course_booking_status', fisar_cdj_sanitize_booking_status( wp_unslash( $_POST['_fisar_course_booking_status'] ) ) );
		}
		if ( isset( $_POST['_fisar_course_booking_present'] ) ) {
			update_post_meta( $post_id, '_fisar_course_limited_seats', isset( $_POST['_fisar_course_limited_seats'] ) ? 1 : 0 );
		}
		if ( isset( $_POST['_fisar_course_maps_present'] ) ) {
			$value = wp_unslash( $_POST['_fisar_course_maps_url'] ?? '' );
			update_post_meta( $post_id, '_fisar_course_maps_url', is_string( $value ) ? esc_url_raw( $value, array( 'http', 'https' ) ) : '' );
		}
		if ( isset( $_POST['_fisar_course_whatsapp_contacts_present'] ) ) {
			$contacts = fisar_cdj_sanitize_whatsapp_contacts( wp_unslash( $_POST['_fisar_course_whatsapp_contacts'] ?? array() ) );
			update_post_meta( $post_id, '_fisar_course_whatsapp_contacts', wp_slash( $contacts ) );
			update_post_meta( $post_id, '_fisar_course_whatsapp', wp_slash( $contacts[0]['value'] ?? '' ) );
		}
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
		Fisar_CDJ_Course_Titles::save( $post_id );
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

	private static function render_excerpt( WP_Post $post ): void {
		$excerpt = (string) get_post_field( 'post_excerpt', $post->ID, 'raw' );
		$archive = Fisar_CDJ_Post_Types::EVENT === $post->post_type ? 'degli eventi' : 'dei corsi';
		// Keep the native ID/name: classic autosave reads and restores #excerpt.
		?>
		<div class="fisar-field">
			<label for="excerpt"><strong>Breve descrizione</strong></label>
			<textarea class="widefat" id="excerpt" name="excerpt" rows="3" aria-describedby="fisar-post-excerpt-help"><?php echo esc_textarea( $excerpt ); ?></textarea>
			<p class="description" id="fisar-post-excerpt-help">Compare nella lista <?php echo esc_html( $archive ); ?> e sotto il titolo della pagina di dettaglio. È il Riassunto di WordPress. Se lo lasci vuoto, la lista ricava un estratto dalla descrizione; nel dettaglio il sottotitolo non compare.</p>
		</div>
		<?php
	}

	private static function render_registration_fields( int $post_id, string $prefix ): void {
		self::render_whatsapp_contacts( $post_id, $prefix );
		echo '<div class="fisar-admin-grid fisar-admin-grid--2">';
		self::input( $post_id, "_fisar_{$prefix}_email", 'Email', 'email' );
		self::input( $post_id, "_fisar_{$prefix}_phone", 'Telefono', 'tel' );
		self::input( $post_id, "_fisar_{$prefix}_form_url", 'Modulo online', 'url', 'Link completo al modulo.' );
		self::input( $post_id, "_fisar_{$prefix}_other_channel", 'Altro canale', 'text' );
		self::input( $post_id, "_fisar_{$prefix}_deadline", 'course' === $prefix ? 'Termine iscrizioni' : 'Termine prenotazioni', 'date' );
		echo '</div>';
		self::select(
			$post_id,
			"_fisar_{$prefix}_deadline_type",
			'course' === $prefix ? 'Tipo di termine data iscrizione' : 'Tipo di termine data prenotazione',
			array(
				'strict'   => 'Tassativo',
				'flexible' => 'Flessibile',
			),
			'Con il termine flessibile, il frontend invita comunque a contattare la Delegazione.'
		);
	}

	private static function render_whatsapp_contacts( int $post_id, string $prefix ): void {
		$contacts = fisar_cdj_get_registration_whatsapp_contacts( $post_id, $prefix );
		?>
		<h3>Contatti WhatsApp per le <?php echo 'course' === $prefix ? 'iscrizioni' : 'prenotazioni'; ?></h3>
		<input type="hidden" name="_fisar_<?php echo esc_attr( $prefix ); ?>_whatsapp_contacts_present" value="1">
		<p class="description" id="fisar-whatsapp-help">Nominativo facoltativo (persona o segreteria) e numero WhatsApp, con o senza +39: per esempio 335 1234567. Se manca il prefisso, il numero è considerato italiano e il link alla chat viene generato automaticamente. Per numeri esteri indica il prefisso internazionale. Le righe senza numero vengono ignorate. I contatti saranno pubblici nella pagina <?php echo 'course' === $prefix ? 'del corso' : 'dell’evento'; ?>.</p>
		<div id="fisar-whatsapp-contacts" data-next-index="<?php echo count( $contacts ) + 1; ?>">
			<?php foreach ( $contacts as $index => $contact ) { self::whatsapp_contact( $index, $contact, $prefix ); } ?>
			<?php self::whatsapp_contact( count( $contacts ), array(), $prefix ); ?>
		</div>
		<template id="fisar-whatsapp-template"><?php self::whatsapp_contact( '__INDEX__', array(), $prefix ); ?></template>
		<p><button type="button" class="button" id="fisar-whatsapp-add" hidden>Aggiungi contatto WhatsApp</button></p>
		<p class="description" id="fisar-whatsapp-noscript">Senza JavaScript compila la riga vuota e salva per aggiungerne un’altra. Per rimuovere un contatto, svuota il numero.</p>
		<span class="screen-reader-text" id="fisar-whatsapp-status" role="status" aria-live="polite"></span>
		<?php
	}

	private static function whatsapp_contact( int|string $index, array $contact, string $prefix ): void {
		?>
		<fieldset class="fisar-whatsapp-contact" aria-describedby="fisar-whatsapp-help">
			<legend><strong>Contatto WhatsApp</strong></legend>
			<div class="fisar-admin-grid fisar-admin-grid--2">
				<?php foreach ( array( 'name' => 'Nominativo (facoltativo)', 'value' => 'Numero WhatsApp' ) as $key => $label ) : ?>
					<div class="fisar-field">
						<label for="fisar-whatsapp-<?php echo esc_attr( $index . '-' . $key ); ?>"><strong><?php echo esc_html( $label ); ?></strong></label>
						<input class="widefat" type="<?php echo 'value' === $key ? 'tel' : 'text'; ?>"<?php if ( 'value' === $key ) : ?> inputmode="tel" placeholder="335 1234567"<?php endif; ?> id="fisar-whatsapp-<?php echo esc_attr( $index . '-' . $key ); ?>" name="_fisar_<?php echo esc_attr( $prefix ); ?>_whatsapp_contacts[<?php echo esc_attr( $index ); ?>][<?php echo esc_attr( $key ); ?>]" value="<?php echo esc_attr( $contact[ $key ] ?? '' ); ?>">
					</div>
				<?php endforeach; ?>
			</div>
			<button type="button" class="button-link-delete fisar-whatsapp-remove" hidden>Rimuovi contatto</button>
		</fieldset>
		<?php
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

	private static function select( int $post_id, string $key, string $label, array $options, string $description = '', string $default = '' ): void {
		$value = (string) get_post_meta( $post_id, $key, true );
		$value = '' === $value ? $default : $value;
		echo '<div class="fisar-field">';
		printf( '<label for="%1$s"><strong>%2$s</strong></label>', esc_attr( $key ), esc_html( $label ) );
		printf( '<select class="widefat" id="%1$s" name="%1$s">', esc_attr( $key ) );
		if ( '' === $default ) {
			echo '<option value="">Seleziona…</option>';
		}
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
			$raw_value = wp_unslash( $_POST[ $field ] ?? '' );
			$value = is_string( $raw_value ) ? esc_url_raw( $raw_value ) : '';
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
