# Architettura e regole funzionali

## Confini

| Area | Responsabilità |
|---|---|
| `plugin/fisar-cdj-core/` | Tipi di contenuto, meta, validazione, stati, query, relazioni, import e configurazione funzionale |
| `theme/fisar-cdj/` | Template, componenti, formattazione, CSS, JavaScript e stili del canvas editoriale |
| `docker/` | Solo ambiente locale e bootstrap |
| `assets/` | Sorgenti del brand e delle fotografie; non una cartella da distribuire integralmente sul web |
| `docs/` | Contratti attuali, guide operative e decisioni durature |

WordPress nativo, tema classico PHP, nessun page builder, framework frontend o ACF. Unica libreria frontend integrata: Clayzo WebGL 0.3.21, caricata su richiesta per la decorazione del calice nella Home. Core/database/WP-CLI sono definiti in `docker/compose.yaml`. I testi pubblicati e gli allegati risiedono nel database e nella Libreria media del rispettivo ambiente; Aruba è la fonte editoriale principale.

## Modello e persistenza

- Eventi: CPT `fisar_evento`, archivio `/eventi/`, meta `_fisar_event_*`.
- Corsi: CPT `fisar_corso`, archivio `/corsi/`, meta `_fisar_course_*`.
- Blog: Articoli nativi (`post`), pagina articoli `/news/`; non introdurre un CPT News o duplicati dei campi nativi.
- Home e pagine associative/editoriali: pagine native, menu WordPress `primary`, `footer`, `social`.
- Breve descrizione di Eventi/Corsi/Articoli: `post_excerpt`, presentato nel metabox principale (Articoli: “Descrizione e immagini dell’articolo”); nessun meta parallelo. Nell’editor a blocchi `editor-excerpt.js` sincronizza il campo nativo e nasconde il pannello duplicato.
- Un Evento può riferirsi a un Corso. La relazione è salvata sull’Evento; la pagina Corso interroga gli Eventi collegati.
- Un Articolo può raccontare un Evento: `_fisar_report_event_id` è salvato soltanto sull’Articolo. `Fisar_CDJ_Event_Reports` gestisce editor, sanitizzazione, salvataggi e query inversa: l’Evento elenca tutti i racconti pubblicati senza essere risalvato. Bozze, privati, programmati, cestinati e protetti da password sono esclusi; anche l’Evento collegato deve essere pubblico. I due URL social facoltativi (`_fisar_report_facebook_url`, `_fisar_report_instagram_url`) appartengono all’Articolo, con soli protocolli HTTP/HTTPS. Nessuna copia dei link nell’Evento o migrazione dei contenuti.

`class-post-types.php` registra CPT/meta, `class-meta-boxes.php` gestisce editor e salvataggi, `class-calendar-importer.php` importa le lezioni, `class-course-titles.php` compone i titoli. Le definizioni dei campi restano nel codice: non mantenerne una seconda copia esaustiva nella documentazione.

Salvataggi con autorizzazioni, nonce, sanitizzazione, protezioni autosave/revisioni. I marker dei metabox distinguono un campo non inviato da un elenco volutamente svuotato: un editor precedente non deve azzerare dati che non conosce. Non introdurre migrazioni o riscritture automatiche senza richiesta.

## Stati temporali e iscrizioni

Date canoniche `YYYY-MM-DD`, confrontate nel fuso WordPress (`Europe/Rome`). Le letture non aggiornano il database e non richiedono cron.

| Regola | Comportamento |
|---|---|
| Evento concluso | Ultima giornata valida non vuota e precedente a oggi; i template non propongono una prenotazione operativa |
| Corso attivo | Non annullato né archiviato esplicitamente, con data fine non vuota e maggiore o uguale a oggi; distinto dalle iscrizioni disponibili |
| Corso annullato | Meta `_fisar_course_cancelled`; incluso nei Corsi passati con dicitura In archivio, iscrizioni/offerte chiuse; dati conservati |
| Termine tassativo | Data valida inclusa: chiusura dal giorno successivo. Per gli Eventi richiede anche `Iscrizione richiesta` |
| Termine flessibile | Non chiude automaticamente; avviso sulla disponibilità non garantita |
| Sold-out semplice | Nessun canale operativo; la scadenza non è mostrata, ma resta salvata |
| Sold-out con lista d’attesa | Riusa i recapiti esistenti; richiesta manuale, nessuna garanzia o lista memorizzata dal sito |

La scadenza tassativa superata prevale sulla disponibilità editoriale. Per i Corsi lo stato concluso prevale su tutto; per gli Eventi i template gestiscono separatamente la data passata. Senza richiesta di iscrizione gli Eventi non applicano il sold-out come vincolo di prenotazione. Non confondere l’avviso `Posti limitati` con un conteggio: non esistono disponibilità numeriche, pagamenti o prenotazioni automatiche.

Per i Corsi, il valore interno `concluded` resta compatibile con le API precedenti ma il frontend usa **In archivio**, senza dedurre il completamento effettivo dalle date. L’annullamento ha precedenza sulle date e restituisce lo stato interno `cancelled`, chiudendo anche i canali di iscrizione. Il flag è booleano, non esposto nei metadati REST pubblici; il marker indipendente `_fisar_course_cancellation_present` consente di togliere la spunta senza azzerarla con salvataggi di editor precedenti. Nessuna riscrittura di contenuti, date, calendario, quote o stato di pubblicazione.

I corsi annullati pubblicati sono inclusi nell’archivio, nelle ricerche e nelle opzioni anno/città, con la stessa dicitura **In archivio** degli altri corsi storici. La query dei corsi attivi li esclude, mentre quella dei passati li include anche con date future o senza data finale. Conteggi e paginazione seguono la stessa selezione. Nessuna migrazione o cache persistente.

API di riferimento in `src/functions.php`:

- query: `fisar_cdj_get_upcoming_events`, `fisar_cdj_get_past_events`, `fisar_cdj_get_active_courses`, `fisar_cdj_get_past_courses`, `fisar_cdj_get_course_events`;
- iscrizioni: `fisar_cdj_get_event_booking_status`, `fisar_cdj_get_course_booking_status`, `fisar_cdj_get_event_registration_details`, `fisar_cdj_get_course_registration_details`, `fisar_cdj_get_registration_channels`;
- quote: `fisar_cdj_get_event_fees`; offerta: `fisar_cdj_get_course_offer`;
- identità Corsi: `fisar_cdj_get_course_identity`; calendario: `fisar_cdj_get_course_calendar`.
- ritrovo Eventi: `fisar_cdj_get_event_meeting`, gruppo opzionale indipendente da sede e orari effettivi; nascosto per gli eventi solo online. Meta registrati/sanitizzati nel plugin, salvataggio protetto da marker per preservare dati da editor precedenti; nessuna migrazione degli eventi esistenti o deduzione dell’ora d’inizio dal ritrovo.

Il tema usa queste API, senza ricalcolare disponibilità, gratuità o scadenze. Un’eventuale cache HTML deve scadere/invalidarsi al cambio di giorno per non mostrare stati superati.

## Calendario degli Eventi

`Fisar_CDJ_Event_Calendar` gestisce una giornata (`single`), un periodo continuativo (`range`) o date distinte (`multiple`). `_fisar_event_date` resta la data iniziale/prima data per i lettori precedenti. Nuovi meta: `_fisar_event_date_mode`, `_fisar_event_end_date` inclusa, `_fisar_event_date_note` per la prima giornata e `_fisar_event_additional_dates` come righe `{date, note}`. Default single, lettura compatibile senza migrazione. Nel salvataggio esplicito delle date multiple si riordinano anche le note e si mantiene la prima data nel campo legacy. Nessuna ricorrenza automatica o prenotazione distinta per giornata; differenze pratiche nelle note pubbliche, eventi autonomi come schede separate.

Date reali validate, righe vuote ignorate, date duplicate/incomplete o periodo invertito rifiutati nel salvataggio del calendario conservando i dati precedenti. Il marker `_fisar_event_calendar_present` distingue il nuovo editor da un client precedente: campi nuovi omessi restano salvati. Il salvataggio usa i controlli nonce/capacità/autosave del metabox; JS blocca il salvataggio con un messaggio visibile, il server protegge comunque il calendario. I campi inattivi restano disponibili quando si cambia modalità; non vengono mostrati sul frontend.

Un evento è concluso solo dopo la sua ultima giornata, anche quando ci sono intervalli fra gli appuntamenti. I prossimi sono ordinati per prossima giornata, i periodi in corso per oggi; i conclusi per giornata finale. Il filtro mese/anno considera l’intersezione con ciascuna giornata o periodo, senza includere i vuoti fra date distinte. Gli anni disponibili includono i passaggi di anno. Query e filtri selezionano/ordinano gli ID con una lettura dei soli cinque meta calendario dei pubblicati, senza caricare corpi o media; un filtro SQL limitato alle query con marker interno conserva poi ricerca nativa, relazioni, conteggi e paginazione WordPress. Nessuna tabella, indice duplicato, cron o scrittura giornaliera; pensato per l’archivio di alcune centinaia di eventi. Query estranee restano invariate.

`class-event-calendar-editor.php` e `event-calendar.js` curano l’editor. Il tema usa `event-calendar.php` per tutte le date/note e `card_label()` per etichette compatte, prossima data e periodi in corso; markup `time` distinto per ciascuna data, senza attribuire una sola data macchina a una lista. Anche WhatsApp elenca tutte le giornate e le note, comprese quelle trascorse come previsto dal suo export editoriale.

## Quote, contatti e accesso online

Quote Eventi: Soci/Non soci più opzioni ordinate `{label, amount, note}` e nota generale. Righe incomplete scartate, `0` valido, nessun calcolo o supplemento dedotto. Gratuità nasconde quote e note senza cancellarle. Quota Corsi, dotazione e tessera sono testi editoriali distinti; il tema aggiunge `€` solo a importi numerici isolati.

WhatsApp: elenco ordinato `{name, value}`, nome facoltativo. Il numero italiano può essere con o senza `+39`; la normalizzazione riguarda il link, non il dato salvato. URL legacy e prefissi esteri espliciti sono preservati. Il vecchio campo singolo è fallback solo se il nuovo elenco non esiste; un elenco salvato vuoto resta vuoto. Non ricavare il gruppo pubblico dai numeri personali per iscriversi.

Eventi online/ibridi: `fisar_cdj_get_event_online_access` restituisce i soli dati pubblicabili. Flag di visibilità falso per default; senza consenso editoriale niente piattaforma/link nell’HTML pubblico. Negli eventi passati l’URL di partecipazione è omesso. Nessun invio automatico agli iscritti, mappa incorporata, geocodifica o provider aggiuntivo; Maps è solo un URL facoltativo.

Offerta Corso: spunta esplicita, iscrizioni ordinarie disponibili e scadenza valida non superata. Data inclusa; senza data disattivazione manuale. Sold-out/lista/chiusura/conclusione nascondono il banner. Non modificare prezzi o scadenze iscrizioni in base all’offerta.

## Calendario e titolo Corso

Lezioni come array `{number, date, time, title, speaker, notes}`, non HTML. Import TSV nuovo a sei colonne e precedente senza numero entrambi accettati; date italiane e ISO. Data e titolo validi necessari; ordine cronologico data/orario, numero manuale senza rinumerazione. Anteprima errata non deve sostituire righe valide; dopo import riuscito il testo sorgente è svuotato per non sovrascrivere successive modifiche manuali.

Modalità titolo Automatico/Personalizzato: Automatico compone il titolo nativo da livello, città e provincia al salvataggio; Personalizzato conserva il titolo libero. Le card possono usare l’identità strutturata senza migrare il titolo esistente. Nessuna città estratta dal titolo e nessun cambio automatico di slug.

## Frontend ed editor

Template condividono componenti per date/orari, luogo, quote, scadenze, canali, newsletter. Home: fino a quattro Eventi, tre Corsi attivi e quattro Articoli; i totali dei link arrivano dalla query originale, senza seconda query di conteggio. Archivi mantengono i contenuti completi previsti.

Il tema imposta 12 articoli per pagina come default nella sola query principale pubblica del Blog tramite `pre_get_posts`; il controllo pubblico consente anche 24, 36 o 48 elementi. Amministrazione, feed, ricerca e query secondarie mantengono le proprie dimensioni. La navigazione nativa usa `max_num_pages` della stessa query, senza conteggi aggiuntivi.

L’archivio Eventi pagina separatamente i prossimi (12 per pagina) e i conclusi (12 di default, selezionabili 24/36/48), attraverso i parametri pubblici `eventi_futuri` / `eventi_conclusi`; i link riportano al titolo della rispettiva sezione. Non usa la pagina della query principale WordPress, che mescola entrambe le categorie. Le API `fisar_cdj_get_upcoming_events` / `fisar_cdj_get_past_events` accettano un secondo argomento pagina (default 1) e conservano il limite predefinito senza paginazione. Ordinamento dei prossimi per prossima giornata (oggi per un periodo in corso), dei conclusi per ultima giornata decrescente, poi ID crescente a parità di data, per evitare duplicati tra pagine; Home e relazioni Corso non cambiano.

`Fisar_CDJ_Archive_Filters` gestisce i parametri pubblici `anno`, `mese`, `search`, `categoria`, `per_pagina`. I precedenti `fisar_year`, `fisar_month`, `fisar_text`, `fisar_category`, `fisar_per_page` restano alias in lettura; il nuovo nome ha precedenza anche se vuoto. Form, reset e link di paginazione generano solo i nomi semplificati. I nomi anno/mese evitano le variabili riservate della query WordPress. La dimensione pagina è limitata alla whitelist 12/24/36/48, con fallback 12; non è un criterio di ricerca e non modifica i conteggi totali. Il Blog filtra la sola query principale pubblica (esclusi feed/admin/ricerca generale), usando la data di pubblicazione e le categorie native. Nell’archivio Eventi il form e i filtri riguardano soltanto i conclusi; i prossimi restano sempre presenti e non filtrati. Le API accettano filtri espliciti come terzo argomento opzionale e considerano tutte le date o ogni giorno di un periodo continuativo: nessun filtro implicito sulle query Home/Corso. Ricerca nativa limitata a titolo/corpo, combinazione AND dei filtri prima della paginazione, conteggi dalle query filtrate. Gli anni disponibili sono letture distinte dei contenuti pubblicati; il form Eventi richiede solo quelli con eventi già conclusi. Nessuna scrittura o cache persistente.

Le cinque categorie principali hanno slug `racconti-eventi`, `servizi`, `corsi`, `vita-delegazione`, `ricordi`. Il filtro virtuale `altro` esclude l’unione di queste categorie e dei loro discendenti: comprende gli articoli senza categorie principali, anche se assegnati ad altre categorie. Un articolo con categoria principale e Uncategorized non rientra in Altro. Nessuna creazione automatica di termini o classificazione al caricamento del plugin. I form GET ripartono dall’URL base dell’archivio; paginazione e selezioni conservano i filtri, reset verso l’archivio senza criteri, conservando la dimensione pagina scelta. Per gli Eventi invio e reset puntano a `#past-events`. Il conteggio totale compare sempre nella riga sotto il form, a destra accanto ad Azzera filtri (quest’ultimo solo con criteri attivi).

`archive-filters.js` migliora il form GET senza introdurre API: selezioni immediate e ricerca dopo 650 ms di pausa, con Cerca come alternativa. Richiede la stessa pagina HTML e sostituisce solo risultati/reset/conteggio, mantenendo campi, focus e cursore. Richieste obsolete annullate e ignorate, composizione IME rispettata; errore di rete con risultati precedenti conservati e nuovo tentativo tramite Cerca, sempre senza navigazione completa. Il GET nativo rimane solo quando JavaScript non è disponibile. URL condivisibili, cronologia per le selezioni e sostituzione durante la scrittura, ripristino con Indietro/Avanti. Reset e paginazione delle liste filtrabili usano lo stesso aggiornamento parziale: il reset pulisce i criteri, mantiene la dimensione pagina e riporta il focus alla ricerca senza spostare la pagina; la paginazione porta focus e vista all’inizio dei nuovi risultati. Click modificati, link agli articoli, nuove schede e download mantengono il comportamento nativo. L’URL cambia con History API senza ricaricare il documento. Nessuna ricerca automatica al caricamento iniziale; gli Eventi futuri restano fuori dal frammento aggiornato.

La hero legge la Galleria top-level `hero-collage`: immagini native in gruppi di sei, validazione degli allegati, `srcset`, alt della Libreria media e classi `hero-focus-X-Y` limitate a 0–100. Calice da Immagine top-level `hero-glass`, decorativo. Nessun ID di upload o manifest fotografico letto dal tema. Senza JS resta il primo gruppo; senza galleria resta l’immagine in evidenza/fallback. Selezione e rigenerazione sono descritte nell’[archivio fotografico](../assets/photography/sito-home/README.md).

CSS/JS del carosello solo in Home; un pallino per gruppo e pausa, cambio ogni sei secondi. Ingresso iniziale delle foto in successione (A, circa 1,39 s), poi sostituzione fotografia per fotografia (B, circa 1,92 s). Le due slide originali si sovrappongono soltanto durante il cambio: nessuna clonazione dei media. Stop dopo focus/scelta manuale fino a ripresa esplicita; sospensione con hover, scheda nascosta, fuori viewport e sotto 64rem. Movimento ridotto disattiva automatismo, ingresso e transizioni. Pausa esplicita sospende anche foto e vino; tab nascosta, fuori viewport e layout stretto ripristinano lo stato statico. Solo il gruppo attivo è esposto alle tecnologie assistive; annunci solo manuali.

`hero-wine.js` mantiene la prima oscillazione leggera approvata (`assets/animations/hero-wine.json`, 4,8 s), avviata soltanto all’ingresso iniziale della pagina. I cambi automatici, manuali o da tastiera non la riavviano. Su desktop con mouse, un’area invisibile ritagliata sulla sola superficie del vino riattiva l’effetto all’ingresso del puntatore o spostandolo di almeno 4 px nel liquido, dopo che il movimento precedente è terminato. Nessun riavvio mentre l’animazione è già attiva o in pausa. L’area non è un comando focusabile e non intercetta vetro, marchio o stelo; su touch e con movimento ridotto resta inerte. Legge il calice corrente dalla Libreria media, non un ID o una copia dimostrativa. Vetro/marchio/stelo restano fermi; l’immagine nativa rimane il fallback. Dopo il primo frame valido il canvas resta visibile anche a riposo, fermo al frame iniziale: i replay non scambiano più immagine e canvas. Render serializzati e `releaseWhenOffscreen: false` sul solo piccolo calice evitano frame obsoleti e rilascio dei pixel tra due oscillazioni; nessun ciclo di rendering a riposo. Errori e rimozione del player ripristinano l’immagine nativa. Runtime Clayzo originale e licenze in `assets/js/vendor/clayzo/`, import dinamico soltanto su desktop con movimento consentito e WebGL2. Nessuna CDN, account o servizio remoto a playback. Il filtro `fisar_cdj_hero_wine_enabled` permette di disattivare soltanto il vino senza cambiare A/B; se viene sostituita l’illustrazione del calice occorre verificare la posizione del liquido o disattivare l’effetto. Sorgente riproducibile in `tools/hero-animation/`; nessun selettore, slider o iframe della prova distribuito.

`event-dates.js` abbrevia il mese quando la data estesa non entra, dopo caricamento font, resize e aggiornamento dei risultati (`fisar:archive-updated`); senza JS o a forte zoom si può andare a capo. Gli editor Eventi/Corsi/Articoli condividono `editor-content.css` per la scala del canvas, non una simulazione completa della pagina; non mantenere copie separate per tipo di contenuto. Le nuove Gallerie del Blog ricevono il default lightbox tramite variante nativa; blocchi esistenti restano invariati.

`class-event-whatsapp-message.php` esporta un messaggio dai dati **salvati** dell’Evento, riusando le API del plugin per quote, ritrovo, stato iscrizioni, contatti e accesso online pubblico. Endpoint AJAX solo autenticato, nonce e autorizzazione `edit_post` sul singolo Evento; nessun handler pubblico, dato scritto, invio WhatsApp o servizio esterno. Non accetta contenuti/campi da salvare. Il testo del corpo viene convertito senza `the_content`, rendering dinamico, esecuzione shortcode o caricamento di risorse; DOM senza accesso di rete, fallback testuale se l’estensione DOM manca. Non dipende dal tema.

Il solo export ignora la data passata e le scadenze trascorse, senza alterare lo stato editoriale sold-out/lista d’attesa o il flag di visibilità online. Le API Eventi di stato, dettagli iscrizioni, canali e accesso online accettano l’opzione `ignore_elapsed_dates`, disattivata per default: il generatore la richiede esplicitamente, il frontend mantiene le regole temporali normali. Nessuna modifica ai dati o alla data corrente globale.

`event-whatsapp-message.js` è caricato solo nell’editor Eventi e gestisce una dialog nativa, modifica effimera, anteprima costruita con nodi testuali/strong/em e copia del testo originale. Nessuna interpolazione HTML dal messaggio. La modifica dell’anteprima non aggiorna i campi WordPress; chiudere e riaprire genera un nuovo testo, senza pulsante di rigenerazione. La scelta dei dati salvati evita copie JavaScript delle regole di disponibilità e salvataggi impliciti.

Il comando vive nel metabox normale a priorità bassa `fisar-event-whatsapp-message`, mantenuto in fondo dal filtro di sola lettura delle preferenze già usato per il ritrovo. Il controllo delle modifiche non salvate combina lo stato nativo `core/editor` con un’istantanea dei campi del plugin e degli editor testuali classici. Un middleware nativo `wp.apiFetch` osserva solo il salvataggio metabox di quell’Evento, senza alterare richiesta/risposta: conferma l’istantanea inviata soltanto dopo HTTP riuscito. Autosave/errori non azzerano l’avviso, modifiche durante il salvataggio restano rilevabili. Con evento nuovo, modifiche o salvataggi pendenti la generazione viene bloccata con un avviso; nessun aggiornamento implicito dei contenuti.

## Configurazione condivisa e redirect

Nel plugin: `fisar_cdj_get_delegation_details` per dati associativi/recapiti; `fisar_cdj_get_newsletter_signup_config` (filtro omonimo) per Mailchimp; `fisar_cdj_get_short_link_redirects` per destinazioni dei link brevi. Il tema presenta i dati senza copie proprie.

Alias `/whatsapp/`, `/facebook/`, `/instagram/`, `/newsletter/`, `/statuto/`: 302/no-cache, path esatto con o senza slash, GET/HEAD, nessuna destinazione da input o query inoltrata. Newsletter richiede Seguici pubblicata e porta a `#newsletter-page`, non direttamente a Mailchimp. Legacy 301: `/resta-aggiornato/` → Seguici; `/chi-siamo/` → Delegazione; `/cookie-policy/` → privacy nativa `#navigazione-e-cookie`. Le pagine reali non vengono scavalcate dai redirect 404.

Newsletter: POST diretto al form Mailchimp, nome/cognome/email e consenso, honeypot; nessuna API key, salvataggio contatti in WordPress o CSS/JS Mailchimp a runtime. Double opt-in/tracking si configurano nell’account Mailchimp, non nel tema.

## Seed e strumenti

`class-demo-content.php` e `content/` sono materiale di bootstrap per nuovi ambienti, non sincronizzazione delle pagine esistenti. Il database Aruba è più aggiornato del seed. I file editoriali del plugin sono dipendenze operative e non vanno rimossi come documentazione duplicata.

`tools/import-home-collage.php` è un import locale esplicito, non caricato dal plugin: WP-CLI/ambiente locale, manifest, WebP e SHA-256 atteso della Home; validazione, riuso allegati, revisione, sostituzione del solo blocco riconosciuto e verifica di idempotenza. Non eseguirlo su Aruba.

### Corsi storici con date incomplete

`_fisar_course_archived` indica esplicitamente un’edizione conclusa e prevale sulla data finale per stato, iscrizioni e offerte. Le edizioni senza termine non entrano automaticamente nello storico: il flag è una decisione editoriale. `_fisar_course_archive_year` conserva il solo anno documentato quando manca l’inizio esatto; non viene trasformato in una data. `_fisar_course_type` distingue `sommelier`, `introductory` e `thematic`, con comportamento precedente per i corsi senza tipo.

`fisar_cdj_get_past_courses` include le edizioni archiviate e quelle con data finale non vuota trascorsa. L’ordinamento SQL è limitato alle query Corso con marker `fisar_cdj_course_archive`: inizio, anno, fine o data editoriale, poi ID decrescente per stabilità. Nessuna migrazione dei contenuti o scrittura nelle query.

### Ricerca nello storico Corsi

`Fisar_CDJ_Course_Filters` applica criteri solo alle query esplicite dello storico: anno da inizio, poi anno archivio, poi fine; nessun anno dedotto dalla pubblicazione. Ricerca con parsing WordPress dei termini/frasi su titolo/corpo e metadati città/sede/indirizzo tramite `EXISTS`, senza duplicazioni delle righe. Tipo mancante compatibile con Sommelier. Opzioni anno/città ricavate dai soli corsi conclusi pubblicati. Il form condiviso usa `anno`, `search`, `tipologia`, `citta`, `per_pagina`; `corsi_conclusi` gestisce la pagina. Il miglioramento JavaScript già usato da Blog/Eventi sostituisce solo risultati/contatore e preserva focus, reset, cronologia e fallback GET. Nessuna query globale Corsi, scrittura o migrazione automatica.

## Metadati delle anteprime social

`theme/fisar-cdj/inc/social-metadata.php` genera in `wp_head` Open Graph e la descrizione HTML usando la query pubblica corrente. Dati calcolati a ogni richiesta, senza nuovi campi, scritture editoriali o duplicazione del riassunto. URL canonico WordPress per i dettagli, URL generale per gli archivi, nessuna query arbitraria inoltrata. L’estrazione legge i blocchi salvati senza eseguire rendering, shortcode o contenuti dinamici; elenchi e titoli nativi vengono letti dal loro HTML statico.

Immagine in evidenza nel formato `large` non ritagliato; fallback istituzionale PNG 1200 × 630 del tema per immagini mancanti/non supportate e Home/archivi. I riferimenti sono generati con le API WordPress dell’ambiente corrente, senza dominio o `/anteprima/` codificati. Nessuna uscita per contenuti non pubblici/protetti, ricerche, 404 o anteprime. Non altera canonical o robots nativi. Se si introduce in futuro un plugin che genera Open Graph, disattivare uno dei due generatori per evitare duplicati.

### Prova del percorso animato

`inc/training-journey.php` inserisce il componente prima del blocco lista `training-levels` nella sola pagina `diventare-sommelier`, senza modificare i blocchi salvati. CSS/JS vengono accodati solo lì; Clayzo 0.3.21 è importato al primo ingresso effettivo in viewport, senza movimento ridotto. Documento vettoriale e fallback SVG provengono dalle stesse geometrie in `tools/training-animation/`. La sequenza di 4,5 s non gira in loop: rimane al frame completo, con ripetizione esplicita tramite pulsante. Uscita dalla viewport o tab nascosta completano il disegno; errore o assenza WebGL/JS mantengono l’SVG. Pubblicato su Aruba il 14 novembre 2026 (pagina 3926), accessibile dal solo URL `/diventare-sommelier/`, senza richiami da menu o altre pagine.

### Icone interattive dei canali

La Home passa `animate_icons` al componente condiviso `follow-channels`. Solo quel richiamo usa `follow-icon` con accenti decorativi e gli asset `follow-motion.css/js`, caricati esclusivamente in Home. Le icone sono complete e statiche al caricamento: nessun ingresso, observer o timer di avvio. Effetto finito su pointerenter mouse o focusin; nessun touch-hover, riavvio durante movimento attivo o loop. Movimento ridotto, scheda nascosta e pagehide cancellano gli effetti. Il token di riproduzione impedisce alle promesse annullate di modificare un’interazione successiva. Tutti gli SVG/accenni sono aria-hidden, i link restano il comando accessibile. Nessun SDK aggiunto; riflesso Instagram di 1,6 secondi.
