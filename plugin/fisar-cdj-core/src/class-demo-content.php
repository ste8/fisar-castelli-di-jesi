<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Fisar_CDJ_Demo_Content {
	private const VERSION = '1.0.3';
	private const OPTION  = 'fisar_cdj_demo_version';

	public static function init(): void {
		if ( defined( 'WP_CLI' ) && WP_CLI ) {
			WP_CLI::add_command( 'fisar-cdj demo install', array( self::class, 'cli_install' ) );
		}
	}

	/**
	 * Installa o aggiorna i contenuti dimostrativi.
	 *
	 * ## OPTIONS
	 *
	 * [--force]
	 * : Aggiorna anche se la versione demo risulta già installata.
	 */
	public static function cli_install( array $args, array $assoc_args ): void {
		$force = isset( $assoc_args['force'] );
		if ( ! $force && self::VERSION === get_option( self::OPTION ) ) {
			WP_CLI::success( 'Dati demo già installati.' );
			return;
		}

		$result = self::install();
		if ( is_wp_error( $result ) ) {
			WP_CLI::error( $result->get_error_message() );
		}

		update_option( self::OPTION, self::VERSION );
		WP_CLI::success( 'Dati demo FISAR installati e navigazione configurata.' );
	}

	public static function install(): true|WP_Error {
		Fisar_CDJ_Post_Types::register();
		self::remove_default_content();
		$pages = self::create_pages();
		if ( is_wp_error( $pages ) ) {
			return $pages;
		}

		$courses = self::create_courses();
		if ( is_wp_error( $courses ) ) {
			return $courses;
		}

		$events = self::create_events( $courses );
		if ( is_wp_error( $events ) ) {
			return $events;
		}

		$news = self::create_news();
		if ( is_wp_error( $news ) ) {
			return $news;
		}

		self::configure_site( $pages );
		self::create_menus( $pages );

		return true;
	}

	private static function remove_default_content(): void {
		$default_slugs = array( 'hello-world', 'sample-page', 'privacy-policy' );
		foreach ( $default_slugs as $slug ) {
			$post = get_page_by_path( $slug, OBJECT, array( 'post', 'page' ) );
			if ( ! $post || get_post_meta( $post->ID, '_fisar_demo_key', true ) ) {
				continue;
			}

			$is_known_default =
				( 'hello-world' === $slug && 'Hello world!' === $post->post_title ) ||
				( 'sample-page' === $slug && 'Sample Page' === $post->post_title ) ||
				( 'privacy-policy' === $slug && 'Privacy Policy' === $post->post_title && 'draft' === $post->post_status );
			if ( $is_known_default ) {
				wp_delete_post( $post->ID, true );
			}
		}
	}

	private static function create_pages(): array|WP_Error {
		$values_content = <<<'HTML'
<p class="lead">Il vino come punto di partenza, le persone al centro.</p>
<h2>Chi siamo</h2>
<p>Siamo un’associazione di persone unite dalla passione per il vino, dalla voglia di conoscerlo e dal piacere di condividerlo. Crediamo nella competenza e nella professionalità, che promuoviamo attraverso corsi, formazione e occasioni di approfondimento.</p>
<h2>Il valore delle persone e del modo di lavorare</h2>
<p>Ci sentiamo vicini ai principi di Slow Food e privilegiamo produttori che lavorano con attenzione alla terra, all’ambiente e alle persone, cercando la qualità prima di tutto in vigna e limitando gli interventi in cantina.</p>
<h2>Curiosità e apertura</h2>
<p>Il vino è per noi un punto di partenza: ci piace conoscere anche ciò che gli sta intorno, dalle altre bevande al cibo, di cui in Italia abbiamo una cultura straordinariamente ricca.</p>
<h2>Il vino con consapevolezza</h2>
<p>Crediamo in un approccio basato sulla conoscenza, sulla consapevolezza e sulla moderazione. Il vino trova posto all’interno di uno stile di vita equilibrato e attento alla longevità.</p>
<h2>Semplicità e informalità</h2>
<p>Conosciamo e rispettiamo le regole del servizio e della degustazione, ma non amiamo gli eccessivi formalismi. Preferiamo un ambiente informale, senza rinunciare alla professionalità.</p>
<h2>Inclusione e accoglienza</h2>
<p>Le nostre attività sono aperte a tutti, dagli appassionati alle prime armi alle persone che fanno parte di altre associazioni. Preferiamo il confronto e la collaborazione alla competizione.</p>
<h2>Ognuno può contribuire</h2>
<p>Alla vita della Delegazione possono contribuire tutti con idee, proposte e iniziative. Chi ha voglia di dare una mano è sempre benvenuto.</p>
HTML;

		$definitions = array(
			'home' => array(
				'title'   => 'FISAR Castelli di Jesi',
				'slug'    => 'home',
				'excerpt' => 'Eventi, corsi e incontri per conoscere, condividere e vivere insieme la cultura del vino.',
				'content' => '<p>La Delegazione FISAR Castelli di Jesi è una comunità di appassionati, sommelier e persone curiose. Scopri le nostre prossime attività.</p>',
			),
			'news' => array(
				'title'   => 'News',
				'slug'    => 'news',
				'content' => '<p>Racconti, aggiornamenti e vita della Delegazione.</p>',
			),
			'values' => array(
				'title'   => 'Carta dei Valori',
				'slug'    => 'carta-dei-valori',
				'excerpt' => 'I principi che guidano ogni nostra attività e scelta.',
				'content' => $values_content,
			),
			'about' => array(
				'title'   => 'Chi siamo',
				'slug'    => 'chi-siamo',
				'excerpt' => 'Una comunità competente, accogliente, curiosa e conviviale.',
				'content' => '<p class="lead">Siamo la Delegazione FISAR Castelli di Jesi: un luogo in cui imparare, incontrarsi e condividere la cultura del vino con semplicità.</p><h2>Una Delegazione aperta</h2><p>Organizziamo corsi per aspiranti sommelier, degustazioni, visite in cantina e momenti di approfondimento. Accogliamo chi muove i primi passi e chi desidera continuare a formarsi.</p><h2>Il territorio</h2><p>Viviamo tra i Castelli di Jesi, in un paesaggio di vigne, borghi e produttori. Partiamo da qui per esplorare vini, territori e culture vicine e lontane.</p><h2>Le persone</h2><p>La Delegazione cresce grazie a chi partecipa, propone idee e mette a disposizione tempo e competenze. È questo il cuore del nostro modo di lavorare.</p>',
			),
			'contacts' => array(
				'title'   => 'Contatti',
				'slug'    => 'contatti',
				'excerpt' => 'Scrivici: saremo felici di rispondere alle tue domande.',
				'content' => '<p class="lead">Vuoi informazioni su un evento, un corso o sulla vita della Delegazione? Scegli il canale che preferisci.</p><h2>Email</h2><p><a href="mailto:info@fisarcastellidijesi.it">info@fisarcastellidijesi.it</a></p><h2>Telefono e WhatsApp</h2><p><a href="tel:+393331234567">+39 333 123 4567</a></p><h2>Dove ci incontriamo</h2><p>Le sedi cambiano in base all’attività. Ogni pagina Evento o Corso riporta sempre luogo, indirizzo e modalità aggiornati.</p><p><strong>Dati dimostrativi:</strong> sostituire email e telefono con i recapiti ufficiali prima della pubblicazione.</p>',
			),
			'join' => array(
				'title'   => 'Unisciti a noi',
				'slug'    => 'unisciti-a-noi',
				'excerpt' => 'Entra a far parte della nostra comunità e condividi la passione.',
				'content' => '<p class="lead">Non serve essere esperti: bastano curiosità, rispetto e voglia di condividere.</p><h2>Partecipa a un’attività</h2><p>Il modo migliore per conoscerci è prendere parte a un evento aperto a tutti o alla serata di presentazione di un corso.</p><h2>Diventa socio</h2><p>Essere soci FISAR permette di partecipare alla vita associativa e accedere alle opportunità riservate. Contattaci per conoscere modalità e quota annuale aggiornate.</p><h2>Porta il tuo contributo</h2><p>Idee, proposte e una mano nell’organizzazione sono sempre benvenute, non solo da chi fa parte del Consiglio Direttivo.</p>',
			),
			'privacy' => array(
				'title'   => 'Privacy Policy',
				'slug'    => 'privacy-policy',
				'content' => '<p><strong>Bozza dimostrativa.</strong> Prima della pubblicazione, sostituire questa pagina con l’informativa validata dal titolare del trattamento e coerente con i servizi effettivamente attivati.</p>',
			),
			'cookies' => array(
				'title'   => 'Cookie Policy',
				'slug'    => 'cookie-policy',
				'content' => '<p><strong>Bozza dimostrativa.</strong> La V1 locale non integra strumenti di profilazione. Prima della pubblicazione, documentare i cookie e i servizi effettivamente presenti.</p>',
			),
		);

		$ids = array();
		foreach ( $definitions as $key => $definition ) {
			$post_id = self::upsert_post(
				"page-{$key}",
				array(
					'post_type'    => 'page',
					'post_status'  => 'publish',
					'post_title'   => $definition['title'],
					'post_name'    => $definition['slug'],
					'post_excerpt' => $definition['excerpt'] ?? '',
					'post_content' => $definition['content'],
				)
			);
			if ( is_wp_error( $post_id ) ) {
				return $post_id;
			}
			$ids[ $key ] = $post_id;
		}

		$hero = self::create_demo_attachment( 'hero', 'Il vino come punto di partenza', 'Le persone al centro', 'landscape', '#32141c', '#b9aa54' );
		if ( is_wp_error( $hero ) ) {
			return $hero;
		}
		self::set_featured_asset( $ids['home'], $hero );

		return $ids;
	}

	private static function create_courses(): array|WP_Error {
		$now = current_datetime();
		$active_start = $now->modify( '+28 days' );
		$active_end   = $now->modify( '+154 days' );
		$active = self::upsert_post(
			'course-first-level',
			array(
				'post_type'    => Fisar_CDJ_Post_Types::COURSE,
				'post_status'  => 'publish',
				'post_title'   => 'Corso Sommelier FISAR — 1° livello',
				'post_name'    => 'corso-sommelier-primo-livello',
				'post_excerpt' => 'Il primo passo per conoscere il vino con metodo, curiosità e una comunità accogliente.',
				'post_content' => '<p>Un percorso pensato per chi desidera avvicinarsi al vino in modo consapevole e strutturato. Le lezioni alternano teoria, degustazione e confronto, con un linguaggio comprensibile anche a chi parte da zero.</p><h2>A chi è rivolto</h2><p>Ad appassionati, operatori della ristorazione e persone curiose che desiderano acquisire le basi del servizio e della degustazione.</p>',
			)
		);
		if ( is_wp_error( $active ) ) {
			return $active;
		}

		$calendar = array();
		$lessons = array(
			array( 0, '20:45', 'La figura del sommelier e il servizio', 'Laura Bianchi', 'Consegna del materiale didattico' ),
			array( 7, '20:45', 'Viticoltura e ciclo della vite', 'Marco Rossi', '' ),
			array( 14, '20:45', 'Enologia: dalla vendemmia al vino', 'Elena Conti', '' ),
			array( 21, '20:45', 'Tecnica della degustazione', 'Andrea Neri', 'Portare i calici del kit' ),
			array( 35, '20:45', 'Spumanti e vini speciali', 'Sara Moretti', '' ),
		);
		foreach ( $lessons as $lesson ) {
			$calendar[] = array(
				'date'    => $active_start->modify( sprintf( '+%d days', $lesson[0] ) )->format( 'Y-m-d' ),
				'time'    => $lesson[1],
				'title'   => $lesson[2],
				'speaker' => $lesson[3],
				'notes'   => $lesson[4],
			);
		}

		self::set_meta(
			$active,
			array(
				'_fisar_course_director'           => 'Laura Bianchi',
				'_fisar_course_level'              => '1',
				'_fisar_course_start_date'         => $active_start->format( 'Y-m-d' ),
				'_fisar_course_end_date'           => $active_end->format( 'Y-m-d' ),
				'_fisar_course_venue'              => 'Sala del Gusto',
				'_fisar_course_address'            => 'Via delle Cantine 8',
				'_fisar_course_city'               => 'Jesi',
				'_fisar_course_province'           => 'AN',
				'_fisar_course_whatsapp'           => 'https://wa.me/393331234567',
				'_fisar_course_email'              => 'corsi@fisarcastellidijesi.it',
				'_fisar_course_phone'              => '+39 333 123 4567',
				'_fisar_course_form_url'           => home_url( '/contatti/' ),
				'_fisar_course_deadline'           => $active_start->modify( '-7 days' )->format( 'Y-m-d' ),
				'_fisar_course_deadline_type'      => 'flexible',
				'_fisar_course_registration_notes' => '<p>È possibile richiedere un colloquio informativo prima dell’iscrizione.</p>',
				'_fisar_course_fee'                => '<p><strong>Quota standard: € 590</strong>, in due rate. Quota Early Bird: € 550 entro il termine indicato.</p>',
				'_fisar_course_membership'         => '<p>È richiesto il tesseramento FISAR per l’anno in corso. La quota associativa non è inclusa nella quota del corso.</p>',
				'_fisar_course_includes'           => '<ul><li>Kit di calici e valigetta</li><li>Manuali didattici</li><li>Vini in degustazione</li><li>Accesso al software FISAR</li><li>Attestato finale</li></ul>',
				'_fisar_course_calendar'           => $calendar,
			)
		);
		$active_image = self::create_demo_attachment( 'course-first-level', 'Corso Sommelier', '1° livello · Jesi', 'landscape', '#5a1727', '#b9aa54' );
		if ( is_wp_error( $active_image ) ) {
			return $active_image;
		}
		self::set_featured_asset( $active, $active_image );

		$past_start = $now->modify( '-210 days' );
		$past_end   = $now->modify( '-70 days' );
		$past = self::upsert_post(
			'course-second-level-past',
			array(
				'post_type'    => Fisar_CDJ_Post_Types::COURSE,
				'post_status'  => 'publish',
				'post_title'   => 'Corso Sommelier FISAR — 2° livello',
				'post_name'    => 'corso-sommelier-secondo-livello-concluso',
				'post_excerpt' => 'Un viaggio tra territori, vitigni e denominazioni italiane e internazionali.',
				'post_content' => '<p>Edizione conclusa del percorso dedicato alla geografia e alla cultura del vino. La pagina resta disponibile come esempio dell’archivio storico della Delegazione.</p>',
			)
		);
		if ( is_wp_error( $past ) ) {
			return $past;
		}
		self::set_meta(
			$past,
			array(
				'_fisar_course_director'   => 'Paolo Mancini',
				'_fisar_course_level'      => '2',
				'_fisar_course_start_date' => $past_start->format( 'Y-m-d' ),
				'_fisar_course_end_date'   => $past_end->format( 'Y-m-d' ),
				'_fisar_course_venue'      => 'Palazzo dei Convegni',
				'_fisar_course_city'       => 'Jesi',
				'_fisar_course_province'   => 'AN',
				'_fisar_course_fee'        => '<p>Edizione conclusa.</p>',
				'_fisar_course_membership' => '<p>Tesseramento FISAR richiesto durante l’edizione.</p>',
				'_fisar_course_includes'   => '<p>Materiali didattici, degustazioni e attestato finale.</p>',
				'_fisar_course_calendar'   => array(),
			)
		);
		$past_image = self::create_demo_attachment( 'course-second-level', 'Vino e territorio', '2° livello · concluso', 'landscape', '#283c32', '#c59a57' );
		if ( is_wp_error( $past_image ) ) {
			return $past_image;
		}
		self::set_featured_asset( $past, $past_image );

		return array( 'active' => $active, 'past' => $past );
	}

	private static function create_events( array $courses ): array|WP_Error {
		$now = current_datetime();
		$definitions = array(
			array(
				'key'     => 'event-course-presentation',
				'title'   => 'Presentazione del Corso Sommelier di 1° livello',
				'slug'    => 'presentazione-corso-sommelier-primo-livello',
				'excerpt' => 'Una serata aperta per conoscere programma, docenti e vita del corso.',
				'content' => '<p>Incontra il Direttore del Corso, scopri come si svolgono le lezioni e fai tutte le domande utili prima di scegliere. La serata include una breve degustazione guidata.</p>',
				'days'    => 12,
				'colors'  => array( '#601b2c', '#b9aa54' ),
				'meta'    => array(
					'_fisar_event_welcome_time'          => '20:30',
					'_fisar_event_start_time'            => '20:45',
					'_fisar_event_end_time'              => '22:15',
					'_fisar_event_mode'                  => 'presence',
					'_fisar_event_venue'                 => 'Sala del Gusto',
					'_fisar_event_address'               => 'Via delle Cantine 8',
					'_fisar_event_city'                  => 'Jesi',
					'_fisar_event_province'              => 'AN',
					'_fisar_event_participation'         => 'all',
					'_fisar_event_is_free'               => 1,
					'_fisar_event_registration_required' => 1,
					'_fisar_event_whatsapp'              => 'https://wa.me/393331234567',
					'_fisar_event_email'                 => 'corsi@fisarcastellidijesi.it',
					'_fisar_event_deadline_type'         => 'flexible',
					'_fisar_event_limited_seats'         => 1,
					'_fisar_event_course_id'             => $courses['active'],
				),
			),
			array(
				'key'     => 'event-verdicchio',
				'title'   => 'Verdicchio: vigne, annate e persone',
				'slug'    => 'verdicchio-vigne-annate-persone',
				'excerpt' => 'Sei calici per attraversare i Castelli di Jesi e incontrare chi li coltiva.',
				'content' => '<p>Una degustazione comparata dedicata al Verdicchio dei Castelli di Jesi, raccontato attraverso territori, stili e annate. Con noi ci saranno due piccoli produttori della zona.</p>',
				'days'    => 24,
				'colors'  => array( '#6d5420', '#d4b764' ),
				'meta'    => array(
					'_fisar_event_welcome_time'          => '20:15',
					'_fisar_event_start_time'            => '20:45',
					'_fisar_event_end_time'              => '23:00',
					'_fisar_event_mode'                  => 'presence',
					'_fisar_event_venue'                 => 'Enoteca Regionale',
					'_fisar_event_address'               => 'Corso Matteotti 14',
					'_fisar_event_city'                  => 'Jesi',
					'_fisar_event_province'              => 'AN',
					'_fisar_event_participation'         => 'all',
					'_fisar_event_is_free'               => 0,
					'_fisar_event_member_price'          => '€ 25',
					'_fisar_event_non_member_price'      => '€ 30',
					'_fisar_event_registration_required' => 1,
					'_fisar_event_form_url'              => home_url( '/contatti/' ),
					'_fisar_event_phone'                 => '+39 333 123 4567',
					'_fisar_event_deadline_type'         => 'strict',
					'_fisar_event_limited_seats'         => 1,
				),
			),
			array(
				'key'     => 'event-online',
				'title'   => 'Il calice consapevole: incontro online',
				'slug'    => 'calice-consapevole-online',
				'excerpt' => 'Un dialogo aperto su vino, moderazione e cultura alimentare.',
				'content' => '<p>Un incontro gratuito con una nutrizionista e un sommelier per parlare di consapevolezza, qualità e moderazione senza posizioni assolute.</p>',
				'days'    => 39,
				'colors'  => array( '#263c4d', '#b9aa54' ),
				'meta'    => array(
					'_fisar_event_start_time'            => '18:30',
					'_fisar_event_end_time'              => '19:30',
					'_fisar_event_mode'                  => 'online',
					'_fisar_event_platform'              => 'Google Meet',
					'_fisar_event_online_url'            => 'https://meet.google.com/',
					'_fisar_event_participation'         => 'all',
					'_fisar_event_is_free'               => 1,
					'_fisar_event_registration_required' => 1,
					'_fisar_event_email'                 => 'eventi@fisarcastellidijesi.it',
					'_fisar_event_deadline_type'         => 'flexible',
					'_fisar_event_limited_seats'         => 0,
				),
			),
			array(
				'key'     => 'event-hybrid',
				'title'   => 'In cantina con il vignaiolo',
				'slug'    => 'in-cantina-con-il-vignaiolo',
				'excerpt' => 'Visita, assaggi dalla botte e collegamento in diretta per chi è lontano.',
				'content' => '<p>Una visita in una piccola cantina dei Castelli di Jesi per conoscere il lavoro in vigna e assaggiare insieme. Una parte del racconto sarà trasmessa anche online.</p>',
				'days'    => 55,
				'colors'  => array( '#3d492e', '#c3a553' ),
				'meta'    => array(
					'_fisar_event_welcome_time'          => '16:30',
					'_fisar_event_start_time'            => '17:00',
					'_fisar_event_end_time'              => '19:30',
					'_fisar_event_mode'                  => 'hybrid',
					'_fisar_event_venue'                 => 'Cantina Colle Aperto',
					'_fisar_event_address'               => 'Contrada San Michele 2',
					'_fisar_event_city'                  => 'Cupramontana',
					'_fisar_event_province'              => 'AN',
					'_fisar_event_platform'              => 'Zoom',
					'_fisar_event_online_url'            => 'https://zoom.us/',
					'_fisar_event_participation'         => 'members_and_companions',
					'_fisar_event_is_free'               => 0,
					'_fisar_event_member_price'          => '€ 15',
					'_fisar_event_non_member_price'      => '€ 20 per accompagnatore',
					'_fisar_event_registration_required' => 1,
					'_fisar_event_whatsapp'              => 'https://wa.me/393331234567',
					'_fisar_event_deadline_type'         => 'strict',
					'_fisar_event_limited_seats'         => 1,
				),
			),
			array(
				'key'     => 'event-free',
				'title'   => 'Brindisi di Delegazione',
				'slug'    => 'brindisi-di-delegazione',
				'excerpt' => 'Un incontro informale, libero e aperto per ritrovarsi e conoscerci.',
				'content' => '<p>Passa a salutarci, incontra i soci e scopri le prossime iniziative. Non è richiesta la prenotazione.</p>',
				'days'    => 70,
				'colors'  => array( '#7a273d', '#e3bd78' ),
				'meta'    => array(
					'_fisar_event_start_time'            => '19:00',
					'_fisar_event_end_time'              => '21:00',
					'_fisar_event_mode'                  => 'presence',
					'_fisar_event_venue'                 => 'Chiostro di San Floriano',
					'_fisar_event_city'                  => 'Jesi',
					'_fisar_event_province'              => 'AN',
					'_fisar_event_participation'         => 'all',
					'_fisar_event_is_free'               => 1,
					'_fisar_event_registration_required' => 0,
					'_fisar_event_limited_seats'         => 0,
				),
			),
			array(
				'key'     => 'event-past',
				'title'   => 'Visita alle vigne di Montecarotto',
				'slug'    => 'visita-vigne-montecarotto-conclusa',
				'excerpt' => 'Una giornata tra vigna, cantina e tavola nel cuore dei Castelli di Jesi.',
				'content' => '<p>Evento concluso. Conserviamo questa pagina per raccontare una giornata di incontri, paesaggio e degustazione con un produttore del territorio.</p>',
				'days'    => -46,
				'colors'  => array( '#51482a', '#b9aa54' ),
				'meta'    => array(
					'_fisar_event_welcome_time'          => '09:45',
					'_fisar_event_start_time'            => '10:00',
					'_fisar_event_end_time'              => '16:00',
					'_fisar_event_mode'                  => 'presence',
					'_fisar_event_venue'                 => 'Azienda Agricola Colle Verde',
					'_fisar_event_city'                  => 'Montecarotto',
					'_fisar_event_province'              => 'AN',
					'_fisar_event_participation'         => 'members',
					'_fisar_event_is_free'               => 0,
					'_fisar_event_member_price'          => '€ 35',
					'_fisar_event_registration_required' => 1,
					'_fisar_event_limited_seats'         => 1,
				),
			),
		);

		$ids = array();
		foreach ( $definitions as $definition ) {
			$post_id = self::upsert_post(
				$definition['key'],
				array(
					'post_type'    => Fisar_CDJ_Post_Types::EVENT,
					'post_status'  => 'publish',
					'post_title'   => $definition['title'],
					'post_name'    => $definition['slug'],
					'post_excerpt' => $definition['excerpt'],
					'post_content' => $definition['content'],
				)
			);
			if ( is_wp_error( $post_id ) ) {
				return $post_id;
			}

			$event_date = $now->modify( sprintf( '%+d days', $definition['days'] ) );
			$requires_registration = ! empty( $definition['meta']['_fisar_event_registration_required'] );
			$meta = array_merge(
				array(
					'_fisar_event_date'                  => $event_date->format( 'Y-m-d' ),
					'_fisar_event_deadline'              => $definition['days'] > 0 && $requires_registration ? $event_date->modify( '-4 days' )->format( 'Y-m-d' ) : '',
					'_fisar_event_registration_notes'    => '',
				),
				$definition['meta']
			);
			self::set_meta( $post_id, $meta );
			$image_id = self::create_demo_attachment( $definition['key'], $definition['title'], wp_date( 'j F Y', $event_date->getTimestamp() ), 'poster', $definition['colors'][0], $definition['colors'][1] );
			if ( is_wp_error( $image_id ) ) {
				return $image_id;
			}
			self::set_featured_asset( $post_id, $image_id );
			$ids[ $definition['key'] ] = $post_id;
		}

		return $ids;
	}

	private static function create_news(): array|WP_Error {
		$now = current_datetime();
		$definitions = array(
			array( 'news-course', 'Nuovo corso in partenza: aperte le iscrizioni', 'La prossima edizione del primo livello prende forma: ecco come conoscerci prima di iscriversi.', '<p>Abbiamo pubblicato programma e calendario della nuova edizione del Corso Sommelier FISAR di 1° livello. La serata di presentazione è gratuita e aperta a tutti.</p><p>È un’occasione per incontrare il Direttore del Corso, conoscere il metodo didattico e capire se questo percorso fa per te.</p>', -2, '#5a1727', '#b9aa54' ),
			array( 'news-vineyard', 'Una giornata tra le vigne di Montecarotto', 'Il racconto della visita: il lavoro del produttore, il paesaggio e il piacere di stare insieme.', '<p>Siamo tornati a casa con nuovi assaggi, molte domande e la consapevolezza che conoscere un vino significa prima di tutto conoscere chi lo produce e il luogo in cui nasce.</p><p>Grazie a tutte le persone che hanno partecipato e alla cantina che ci ha accolti con semplicità e generosità.</p>', -9, '#3d492e', '#c3a553' ),
			array( 'news-community', 'Le idee della Delegazione nascono insieme', 'Un incontro aperto ai soci per costruire il programma dei prossimi mesi.', '<p>Eventi, visite, approfondimenti e attività sul territorio: abbiamo raccolto proposte diverse e concrete. Ognuno può contribuire alla vita della Delegazione, anche con un’idea piccola.</p>', -18, '#552536', '#d6b26a' ),
			array( 'news-slow', 'Slow Wine: la guida che ci accompagna', 'Perché il modo di lavorare del produttore conta più della sua notorietà.', '<p>La nostra curiosità parte dalla vigna, dall’attenzione alla terra e dalle scelte quotidiane di chi produce. Slow Wine ci aiuta a leggere i vini da questa prospettiva.</p>', -31, '#42402b', '#b9aa54' ),
		);

		$ids = array();
		foreach ( $definitions as $definition ) {
			$post_date = $now->modify( sprintf( '%+d days', $definition[4] ) );
			$post_id = self::upsert_post(
				$definition[0],
				array(
					'post_type'    => 'post',
					'post_status'  => 'publish',
					'post_title'   => $definition[1],
					'post_name'    => sanitize_title( $definition[1] ),
					'post_excerpt' => $definition[2],
					'post_content' => $definition[3],
					'post_date'    => $post_date->format( 'Y-m-d H:i:s' ),
				)
			);
			if ( is_wp_error( $post_id ) ) {
				return $post_id;
			}
			$image_id = self::create_demo_attachment( $definition[0], $definition[1], 'News dalla Delegazione', 'landscape', $definition[5], $definition[6] );
			if ( is_wp_error( $image_id ) ) {
				return $image_id;
			}
			self::set_featured_asset( $post_id, $image_id );
			$ids[] = $post_id;
		}

		return $ids;
	}

	private static function configure_site( array $pages ): void {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $pages['home'] );
		update_option( 'page_for_posts', $pages['news'] );
		update_option( 'blogdescription', 'Il vino come punto di partenza, le persone al centro.' );
		update_option( 'default_comment_status', 'closed' );
		update_option( 'default_ping_status', 'closed' );
		update_option( 'wp_page_for_privacy_policy', $pages['privacy'] );
	}

	private static function create_menus( array $pages ): void {
		$primary = self::ensure_menu( 'Navigazione principale' );
		self::ensure_menu_item( $primary, 'Eventi', get_post_type_archive_link( Fisar_CDJ_Post_Types::EVENT ) ?: home_url( '/eventi/' ), 10 );
		self::ensure_menu_item( $primary, 'Corsi', get_post_type_archive_link( Fisar_CDJ_Post_Types::COURSE ) ?: home_url( '/corsi/' ), 20 );
		self::ensure_menu_item( $primary, 'News', get_permalink( $pages['news'] ), 30 );
		self::ensure_menu_item( $primary, 'Carta dei Valori', get_permalink( $pages['values'] ), 40 );
		self::ensure_menu_item( $primary, 'Chi siamo', get_permalink( $pages['about'] ), 50 );
		self::ensure_menu_item( $primary, 'Contatti', get_permalink( $pages['contacts'] ), 60 );

		$footer = self::ensure_menu( 'Navigazione footer' );
		self::ensure_menu_item( $footer, 'Eventi', get_post_type_archive_link( Fisar_CDJ_Post_Types::EVENT ) ?: home_url( '/eventi/' ), 10 );
		self::ensure_menu_item( $footer, 'Corsi', get_post_type_archive_link( Fisar_CDJ_Post_Types::COURSE ) ?: home_url( '/corsi/' ), 20 );
		self::ensure_menu_item( $footer, 'News', get_permalink( $pages['news'] ), 30 );
		self::ensure_menu_item( $footer, 'Carta dei Valori', get_permalink( $pages['values'] ), 40 );
		self::ensure_menu_item( $footer, 'Privacy Policy', get_permalink( $pages['privacy'] ), 50 );
		self::ensure_menu_item( $footer, 'Cookie Policy', get_permalink( $pages['cookies'] ), 60 );

		$social = self::ensure_menu( 'Canali social' );
		self::ensure_menu_item( $social, 'Canale WhatsApp', 'https://whatsapp.com/channel/', 10 );
		self::ensure_menu_item( $social, 'Instagram', 'https://www.instagram.com/', 20 );
		self::ensure_menu_item( $social, 'Facebook', 'https://www.facebook.com/', 30 );

		set_theme_mod(
			'nav_menu_locations',
			array(
				'primary' => $primary,
				'footer'  => $footer,
				'social'  => $social,
			)
		);
	}

	private static function upsert_post( string $demo_key, array $post_data ): int|WP_Error {
		$existing = get_posts(
			array(
				'post_type'      => 'any',
				'post_status'    => 'any',
				'posts_per_page' => 1,
				'fields'         => 'ids',
				'meta_key'       => '_fisar_demo_key',
				'meta_value'     => $demo_key,
			)
		);
		if ( $existing ) {
			$post_data['ID'] = (int) $existing[0];
		}

		$post_id = wp_insert_post( wp_slash( $post_data ), true );
		if ( ! is_wp_error( $post_id ) ) {
			update_post_meta( $post_id, '_fisar_demo_key', $demo_key );
		}

		return $post_id;
	}

	private static function set_meta( int $post_id, array $metadata ): void {
		foreach ( $metadata as $key => $value ) {
			update_post_meta( $post_id, $key, $value );
		}
	}

	private static function set_featured_asset( int $post_id, int $attachment_id ): void {
		// SVG demo locali e fidati: WordPress non genera metadata raster, quindi
		// impostiamo direttamente la relazione. Il tema legge l'URL originale.
		update_post_meta( $post_id, '_thumbnail_id', $attachment_id );
	}

	private static function create_demo_attachment( string $key, string $title, string $subtitle, string $format, string $background, string $accent ): int|WP_Error {
		$existing = get_posts(
			array(
				'post_type'      => 'attachment',
				'post_status'    => 'inherit',
				'posts_per_page' => 1,
				'fields'         => 'ids',
				'meta_key'       => '_fisar_demo_asset_key',
				'meta_value'     => $key,
			)
		);
		if ( $existing ) {
			return (int) $existing[0];
		}

		if ( ! function_exists( 'imagecreatetruecolor' ) ) {
			return new WP_Error( 'fisar_demo_gd', 'L’estensione GD è necessaria per creare gli asset demo.' );
		}

		$is_poster = 'poster' === $format;
		$width     = $is_poster ? 900 : 1200;
		$height    = $is_poster ? 1200 : 760;
		$image     = imagecreatetruecolor( $width, $height );
		if ( false === $image ) {
			return new WP_Error( 'fisar_demo_image', 'Impossibile inizializzare l’immagine demo.' );
		}

		$start = self::hex_to_rgb( $background );
		$end   = self::hex_to_rgb( '#17131b' );
		for ( $y = 0; $y < $height; $y += 4 ) {
			$ratio = $y / max( 1, $height - 1 );
			$red   = (int) round( $start[0] + ( $end[0] - $start[0] ) * $ratio );
			$green = (int) round( $start[1] + ( $end[1] - $start[1] ) * $ratio );
			$blue  = (int) round( $start[2] + ( $end[2] - $start[2] ) * $ratio );
			$color = imagecolorallocate( $image, $red, $green, $blue );
			imagefilledrectangle( $image, 0, $y, $width, min( $height, $y + 4 ), $color );
		}

		$accent_rgb   = self::hex_to_rgb( $accent );
		$gold         = imagecolorallocate( $image, $accent_rgb[0], $accent_rgb[1], $accent_rgb[2] );
		$gold_soft    = imagecolorallocatealpha( $image, $accent_rgb[0], $accent_rgb[1], $accent_rgb[2], 70 );
		$paper        = imagecolorallocate( $image, 250, 247, 242 );
		$shadow       = imagecolorallocatealpha( $image, 12, 9, 13, 48 );
		$seed         = abs( crc32( $key ) );
		$circle_shift = $seed % max( 1, (int) ( $width * .18 ) );

		imagefilledellipse( $image, (int) ( $width * .2 ) + $circle_shift, (int) ( $height * .2 ), (int) ( $width * .34 ), (int) ( $width * .34 ), $gold_soft );
		imagefilledellipse( $image, (int) ( $width * .82 ), (int) ( $height * .28 ), (int) ( $width * .43 ), (int) ( $width * .43 ), $shadow );
		imagesetthickness( $image, max( 5, (int) ( $width * .008 ) ) );

		// Calice astratto: un segno coerente con il tema, non una fotografia fittizia.
		$glass_x = (int) ( $width * ( $is_poster ? .5 : .68 ) );
		$glass_y = (int) ( $height * .28 );
		$glass_w = (int) ( $width * ( $is_poster ? .28 : .2 ) );
		$glass_h = (int) ( $height * .28 );
		imagearc( $image, $glass_x, $glass_y, $glass_w, $glass_h, 0, 180, $gold );
		imageline( $image, $glass_x - (int) ( $glass_w / 2 ), $glass_y, $glass_x - (int) ( $glass_w * .32 ), $glass_y + (int) ( $glass_h * .62 ), $gold );
		imageline( $image, $glass_x + (int) ( $glass_w / 2 ), $glass_y, $glass_x + (int) ( $glass_w * .32 ), $glass_y + (int) ( $glass_h * .62 ), $gold );
		imagearc( $image, $glass_x, $glass_y + (int) ( $glass_h * .62 ), (int) ( $glass_w * .64 ), (int) ( $glass_h * .45 ), 0, 180, $gold );
		imageline( $image, $glass_x, $glass_y + (int) ( $glass_h * .84 ), $glass_x, $glass_y + (int) ( $glass_h * 1.25 ), $gold );
		imageline( $image, $glass_x - (int) ( $glass_w * .22 ), $glass_y + (int) ( $glass_h * 1.25 ), $glass_x + (int) ( $glass_w * .22 ), $glass_y + (int) ( $glass_h * 1.25 ), $gold );

		// Piccolo grappolo stilizzato, richiamo al marchio senza riprodurlo.
		$grape_x = (int) ( $width * .19 );
		$grape_y = (int) ( $height * .48 );
		$grape_r = max( 18, (int) ( $width * .028 ) );
		foreach ( array( array( -1, 0 ), array( 0, 0 ), array( 1, 0 ), array( -.5, 1 ), array( .5, 1 ), array( 0, 2 ) ) as $point ) {
			imageellipse( $image, $grape_x + (int) ( $point[0] * $grape_r * 2 ), $grape_y + (int) ( $point[1] * $grape_r * 2 ), $grape_r * 2, $grape_r * 2, $gold );
		}

		imagesetthickness( $image, max( 3, (int) ( $width * .004 ) ) );
		imageline( $image, (int) ( $width * .08 ), (int) ( $height * .78 ), (int) ( $width * .92 ), (int) ( $height * .78 ), $gold );
		imagestring( $image, 5, (int) ( $width * .08 ), (int) ( $height * .83 ), 'FISAR CASTELLI DI JESI', $paper );
		imagestring( $image, 3, (int) ( $width * .08 ), (int) ( $height * .88 ), strtoupper( remove_accents( $subtitle ) ), $gold );

		ob_start();
		imagepng( $image, null, 8 );
		$png = (string) ob_get_clean();
		imagedestroy( $image );

		$upload = wp_upload_bits( 'fisar-demo-' . sanitize_file_name( $key ) . '.png', null, $png );
		if ( ! empty( $upload['error'] ) ) {
			return new WP_Error( 'fisar_demo_upload', $upload['error'] );
		}

		$attachment_id = wp_insert_attachment(
			array(
				'post_mime_type' => 'image/png',
				'post_title'     => wp_strip_all_tags( $title ),
				'post_status'    => 'inherit',
			),
			$upload['file']
		);
		if ( is_wp_error( $attachment_id ) ) {
			return $attachment_id;
		}

		require_once ABSPATH . 'wp-admin/includes/image.php';
		$metadata = wp_generate_attachment_metadata( $attachment_id, $upload['file'] );
		if ( is_array( $metadata ) ) {
			wp_update_attachment_metadata( $attachment_id, $metadata );
		}
		update_post_meta( $attachment_id, '_fisar_demo_asset_key', $key );
		update_post_meta( $attachment_id, '_wp_attachment_image_alt', wp_strip_all_tags( $title ) );

		return $attachment_id;
	}

	private static function hex_to_rgb( string $hex ): array {
		$hex = ltrim( $hex, '#' );
		if ( 6 !== strlen( $hex ) || ! ctype_xdigit( $hex ) ) {
			return array( 40, 35, 50 );
		}

		return array(
			hexdec( substr( $hex, 0, 2 ) ),
			hexdec( substr( $hex, 2, 2 ) ),
			hexdec( substr( $hex, 4, 2 ) ),
		);
	}

	private static function ensure_menu( string $name ): int {
		$menu = wp_get_nav_menu_object( $name );

		return $menu ? (int) $menu->term_id : (int) wp_create_nav_menu( $name );
	}

	private static function ensure_menu_item( int $menu_id, string $title, string $url, int $position ): void {
		$items = wp_get_nav_menu_items( $menu_id ) ?: array();
		foreach ( $items as $item ) {
			if ( $item->title === $title ) {
				wp_update_nav_menu_item(
					$menu_id,
					$item->ID,
					array(
						'menu-item-title'    => $title,
						'menu-item-url'      => $url,
						'menu-item-position' => $position,
						'menu-item-status'   => 'publish',
					)
				);
				return;
			}
		}

		wp_update_nav_menu_item(
			$menu_id,
			0,
			array(
				'menu-item-title'    => $title,
				'menu-item-url'      => $url,
				'menu-item-position' => $position,
				'menu-item-status'   => 'publish',
			)
		);
	}
}
