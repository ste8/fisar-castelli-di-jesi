# Architettura e regole funzionali

## Confini

| Area | Responsabilità |
|---|---|
| `plugin/fisar-cdj-core/` | Tipi di contenuto, meta, validazione, stati, query, relazioni, import e configurazione funzionale |
| `theme/fisar-cdj/` | Template, componenti, formattazione, CSS, JavaScript e stili del canvas editoriale |
| `docker/` | Solo ambiente locale e bootstrap |
| `assets/` | Sorgenti del brand e delle fotografie; non una cartella da distribuire integralmente sul web |
| `docs/` | Contratti attuali, guide operative e decisioni durature |

WordPress nativo, tema classico PHP, nessun page builder, framework frontend, ACF o libreria JavaScript esterna. Core/database/WP-CLI sono definiti in `docker/compose.yaml`. I testi pubblicati e gli allegati risiedono nel database e nella Libreria media del rispettivo ambiente; Aruba è la fonte editoriale principale.

## Modello e persistenza

- Eventi: CPT `fisar_evento`, archivio `/eventi/`, meta `_fisar_event_*`.
- Corsi: CPT `fisar_corso`, archivio `/corsi/`, meta `_fisar_course_*`.
- Blog: Articoli nativi (`post`), pagina articoli `/news/`; non introdurre un CPT News o duplicati dei campi nativi.
- Home e pagine associative/editoriali: pagine native, menu WordPress `primary`, `footer`, `social`.
- Breve descrizione di Eventi/Corsi: `post_excerpt`, presentato nel metabox principale; nessun meta parallelo.
- Un Evento può riferirsi a un Corso. La relazione è salvata sull’Evento; la pagina Corso interroga gli Eventi collegati.

`class-post-types.php` registra CPT/meta, `class-meta-boxes.php` gestisce editor e salvataggi, `class-calendar-importer.php` importa le lezioni, `class-course-titles.php` compone i titoli. Le definizioni dei campi restano nel codice: non mantenerne una seconda copia esaustiva nella documentazione.

Salvataggi con autorizzazioni, nonce, sanitizzazione, protezioni autosave/revisioni. I marker dei metabox distinguono un campo non inviato da un elenco volutamente svuotato: un editor precedente non deve azzerare dati che non conosce. Non introdurre migrazioni o riscritture automatiche senza richiesta.

## Stati temporali e iscrizioni

Date canoniche `YYYY-MM-DD`, confrontate nel fuso WordPress (`Europe/Rome`). Le letture non aggiornano il database e non richiedono cron.

| Regola | Comportamento |
|---|---|
| Evento concluso | Data evento non vuota e precedente a oggi; i template non propongono una prenotazione operativa |
| Corso attivo | Data fine non vuota e maggiore o uguale a oggi; distinto dalle iscrizioni disponibili |
| Termine tassativo | Data valida inclusa: chiusura dal giorno successivo. Per gli Eventi richiede anche `Iscrizione richiesta` |
| Termine flessibile | Non chiude automaticamente; avviso sulla disponibilità non garantita |
| Sold-out semplice | Nessun canale operativo; la scadenza non è mostrata, ma resta salvata |
| Sold-out con lista d’attesa | Riusa i recapiti esistenti; richiesta manuale, nessuna garanzia o lista memorizzata dal sito |

La scadenza tassativa superata prevale sulla disponibilità editoriale. Per i Corsi lo stato concluso prevale su tutto; per gli Eventi i template gestiscono separatamente la data passata. Senza richiesta di iscrizione gli Eventi non applicano il sold-out come vincolo di prenotazione. Non confondere l’avviso `Posti limitati` con un conteggio: non esistono disponibilità numeriche, pagamenti o prenotazioni automatiche.

API di riferimento in `src/functions.php`:

- query: `fisar_cdj_get_upcoming_events`, `fisar_cdj_get_past_events`, `fisar_cdj_get_active_courses`, `fisar_cdj_get_past_courses`, `fisar_cdj_get_course_events`;
- iscrizioni: `fisar_cdj_get_event_booking_status`, `fisar_cdj_get_course_booking_status`, `fisar_cdj_get_event_registration_details`, `fisar_cdj_get_course_registration_details`, `fisar_cdj_get_registration_channels`;
- quote: `fisar_cdj_get_event_fees`; offerta: `fisar_cdj_get_course_offer`;
- identità Corsi: `fisar_cdj_get_course_identity`; calendario: `fisar_cdj_get_course_calendar`.
- ritrovo Eventi: `fisar_cdj_get_event_meeting`, gruppo opzionale indipendente da sede e orari effettivi; nascosto per gli eventi solo online. Meta registrati/sanitizzati nel plugin, salvataggio protetto da marker per preservare dati da editor precedenti; nessuna migrazione degli eventi esistenti o deduzione dell’ora d’inizio dal ritrovo.

Il tema usa queste API, senza ricalcolare disponibilità, gratuità o scadenze. Un’eventuale cache HTML deve scadere/invalidarsi al cambio di giorno per non mostrare stati superati.

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

La hero legge la Galleria top-level `hero-collage`: immagini native in gruppi di sei, validazione degli allegati, `srcset`, alt della Libreria media e classi `hero-focus-X-Y` limitate a 0–100. Calice da Immagine top-level `hero-glass`, decorativo. Nessun ID di upload o manifest fotografico letto dal tema. Senza JS resta il primo gruppo; senza galleria resta l’immagine in evidenza/fallback. Selezione e rigenerazione sono descritte nell’[archivio fotografico](../assets/photography/sito-home/README.md).

CSS/JS del carosello solo in home; un pallino per gruppo e pausa, cambio ogni sei secondi/dissolvenza 500 ms. Stop dopo focus/scelta manuale fino a ripresa esplicita; sospensione con hover, scheda nascosta, fuori viewport e sotto 64rem. Movimento ridotto disattiva automatismo e transizione. Solo il gruppo attivo è esposto alle tecnologie assistive; annunci solo manuali.

`event-dates.js` abbrevia il mese quando la data estesa non entra, dopo caricamento font e resize; senza JS o a forte zoom si può andare a capo. Gli editor Eventi/Corsi usano CSS dedicati al canvas, non una simulazione completa della pagina. Le nuove Gallerie del Blog ricevono il default lightbox tramite variante nativa; blocchi esistenti restano invariati.

## Configurazione condivisa e redirect

Nel plugin: `fisar_cdj_get_delegation_details` per dati associativi/recapiti; `fisar_cdj_get_newsletter_signup_config` (filtro omonimo) per Mailchimp; `fisar_cdj_get_short_link_redirects` per destinazioni dei link brevi. Il tema presenta i dati senza copie proprie.

Alias `/whatsapp/`, `/facebook/`, `/instagram/`, `/newsletter/`, `/statuto/`: 302/no-cache, path esatto con o senza slash, GET/HEAD, nessuna destinazione da input o query inoltrata. Newsletter richiede Seguici pubblicata e porta a `#newsletter-page`, non direttamente a Mailchimp. Legacy 301: `/resta-aggiornato/` → Seguici; `/chi-siamo/` → Delegazione; `/cookie-policy/` → privacy nativa `#navigazione-e-cookie`. Le pagine reali non vengono scavalcate dai redirect 404.

Newsletter: POST diretto al form Mailchimp, nome/cognome/email e consenso, honeypot; nessuna API key, salvataggio contatti in WordPress o CSS/JS Mailchimp a runtime. Double opt-in/tracking si configurano nell’account Mailchimp, non nel tema.

## Seed e strumenti

`class-demo-content.php` e `content/` sono materiale di bootstrap per nuovi ambienti, non sincronizzazione delle pagine esistenti. Il database Aruba è più aggiornato del seed. I file editoriali del plugin sono dipendenze operative e non vanno rimossi come documentazione duplicata.

`tools/import-home-collage.php` è un import locale esplicito, non caricato dal plugin: WP-CLI/ambiente locale, manifest, WebP e SHA-256 atteso della Home; validazione, riuso allegati, revisione, sostituzione del solo blocco riconosciuto e verifica di idempotenza. Non eseguirlo su Aruba.
