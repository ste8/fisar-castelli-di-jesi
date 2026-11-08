# Pubblicazione e manutenzione Aruba

## Ambiente attuale e confini

Anteprima: [www.fisarcastellidijesi.it/anteprima/](https://www.fisarcastellidijesi.it/anteprima/), WordPress indipendente nella sottocartella Linux con database dedicato. Frontend senza password, admin con login; noindex sia HTTP sia meta. Conoscere l’URL non rende i contenuti privati.

**Aruba è la fonte principale dei contenuti dal trasferimento iniziale. Non importare più il database locale per un aggiornamento di codice.** Editor, gallerie, menu e configurazione del database devono essere preservati. Root pubblica precedente, DNS, email e impostazioni PHP globali non sono parte di un normale aggiornamento dell’anteprima.

Ultimo rilascio: plugin Core 1.3.40 e tema 1.6.108, il 6 novembre 2026: metadati dei racconti nel Blog, collegamento inverso automatico dagli Eventi e rimandi Facebook/Instagram. Aggiornamento nativo dei soli componenti, nessun import DB o migrazione dei contenuti. Primo resoconto sui vini giapponesi dell’8 ottobre preparato in bozza e poi pubblicato su richiesta dell’utente, con testo originale Facebook e dieci foto originali. Instagram lasciato vuoto perché l’accesso richiede una verifica sul telefono di un’altra persona. Versioni attive, galleria/ingrandimento, CTA automatica e noindex verificati; 155 contenuti preesistenti invariati nel confronto WXR prima/dopo la preparazione. Core remoto osservato 7.1.3; PHP remoto osservato in precedenza 8.5/MySQL 8, distinto da PHP 8.3/MariaDB locale. Ricontrollare l’ambiente prima di un rilascio; non chiedere password in chat né salvarle nella documentazione.

Aggiornamento successivo del 7 novembre 2026: tema 1.6.110 per lo stile facoltativo Griglia uniforme dei racconti; plugin invariato a 1.3.40. Stile applicato al solo articolo sui vini giapponesi, con foto ad alta risoluzione e prima foto della galleria come copertina. Data dell’articolo corretta al 12 ottobre 2026, ore 20:39; recuperato e aggiunto anche il link Instagram tramite accesso personale, con rimando pubblico verificato. Backup del tema 1.6.108 e contenuto precedente conservati; native gallerie/immagini originali e metadati preservati, nessun import DB. Prove e pacchetti in `release/photo-grid-2026-11-07/`.

Ultimo aggiornamento del 7 novembre 2026: tema 1.6.111 per allineare titoli, date, estratti e collegamenti nelle righe dell’archivio Blog, mantenendo i titoli completi. Plugin invariato a 1.3.40. Collaudo locale e remoto a 1280/768/390 px, nessun overflow; HTML del contenuto Blog identico prima/dopo e noindex conservato. Pacchetto, rollback 1.6.110 e prove in `release/blog-card-alignment-2026-11-07/`. Nessun import DB o modifica ai contenuti editoriali.

Aggiornamento del 7 novembre 2026: tema 1.6.112 per distanziare il pulsante dei racconti dal separatore dei dettagli Evento (20 px), con spaziatura coerente della freccia. Verificato in locale e su Aruba a 1280/768/390 px, senza overflow; contenuto Evento invariato e noindex conservato. Pacchetto, rollback 1.6.111 e prove in `release/report-button-spacing-2026-11-07/`. Plugin invariato, nessun import DB.

Rilascio del 7 novembre 2026: tema 1.6.113 con anteprima del racconto sotto il titolo Evento, foto dell’articolo e invito “Rivivi l’evento leggendo il racconto”, nella stessa scheda. Gestiti più racconti e articoli senza copertina; il riquadro non compare senza racconti pubblici. Lint PHP e test WordPress superati; verifiche Aruba a 1280/768/390 px, navigazione reale nella stessa scheda, testi/dettagli evento invariati e noindex conservato. Pacchetto, rollback 1.6.112 e prove in `release/report-preview-2026-11-07/`. Solo tema, nessun import DB.

Rilascio del 7 novembre 2026: tema 1.6.114 con sfondo bordò, testo bianco e freccia oro nel banner del racconto. Fotografia, testi, collegamento nella stessa scheda e markup pubblico invariati. Verificato su Aruba a 1280/768/390 px, senza overflow e con noindex conservato. Pacchetto, rollback 1.6.113 e prove in `release/report-bordeaux-2026-11-07/`.

Rilascio del 7 novembre 2026: tema 1.6.115 con Poppins 500 come font predefinito dei titoli, correggendo gli stati vuoti, i risultati di ricerca e la pagina 404. Audit prima/dopo su 16 pagine: tutti i titoli verificati in Poppins, testi invariati; Cormorant mantenuto nel motto della Home e della Carta dei Valori. Controlli locali e Aruba desktop/mobile senza overflow; noindex conservato. Pacchetto, rollback 1.6.114 e prove in `release/typography-2026-11-07/`.

Rilascio del 7 novembre 2026: tema 1.6.116 con 12 articoli per pagina nel Blog, contatore sopra la griglia e nel pannello inferiore, pulsante “Altri articoli →”. Test WordPress locale su tre pagine superato; su Aruba i 32 articoli mantengono URL, titoli, date e ordine, distribuiti 12/12/8 invece di 10/10/10/2. Controlli a 1280/768/390 px senza overflow, noindex conservato. Plugin invariato a 1.3.40; nessuna modifica editoriale o import DB. Pacchetto, rollback 1.6.115 e confronti in `release/blog-pagination-2026-11-07/`.

Correzione successiva: tema 1.6.117, rimossi i contatori testuali “Pagina X di Y” su richiesta dell’utente. Azzerato il margine ereditato dalla navigazione interna: controlli centrati nel riquadro con 21 px sopra e sotto. Prima e ultima pagina verificate su Aruba a 1280/390 px, senza overflow; dimensione pagina e collegamenti preservati. Pacchetto, rollback 1.6.116 e misure in `release/blog-pagination-spacing-2026-11-07/`.

Rilascio del 7 novembre 2026: tema 1.6.118 e Core 1.3.41. Applicata la variante approvata con fondo bianco, bordo/pulsante bordò e controlli centrati a Blog ed Eventi. Archivio Eventi con due paginazioni indipendenti da 12 elementi e link alla relativa sezione; chiamate esistenti alle API preservate. Test locali Blog/Eventi superati; confronto Aruba dei 25 eventi identico per URL, titolo, data e ordine su pagine 12/12/1. Blog ed Eventi verificati a 1280/768/390 px senza overflow, spaziatura verticale simmetrica; noindex mantenuto. Pacchetti, rollback tema 1.6.117/Core 1.3.40 e prove in `release/archive-pagination-2026-11-07/`. Aggiornare prima Core e poi tema; per il rollback ripristinare prima il tema e poi Core. Nessun import DB o modifica editoriale.

Rilascio del 7 novembre 2026: tema 1.6.119 con copertina automatica delle card Blog/Home senza foto (grappolo originale FISAR su fondo chiaro), senza assegnazioni nel database. Core invariato a 1.3.41. Aggiornamento editoriale separato, richiesto dall’utente: immagini in evidenza per i ricordi di Carlo Pigini Campanari (anteprima Facebook dell’articolo WineSurf, 966 × 504), Ampelio Bucci (foto originale FIVI, 2048 × 1365) e il servizio alla Slow Wine Fair 2025 (prima foto del post FISAR Nazionale condiviso, 1440 × 1267, credito Michele Purin). Provenienza e crediti registrati nei media. Titoli, corpi, date e collegamenti social dei tre articoli identici prima/dopo. Pacchetto, rollback 1.6.118, foto e confronti in `release/blog-default-cover-2026-11-07/`. Nessun import DB.

Rilascio del 7 novembre 2026: tema 1.6.120 e Core 1.3.42 con filtri anno/mese/titolo-corpo per Blog/Eventi e categorie native nel Blog, più Altro come complemento delle principali. Prima del rilascio, create cinque categorie e classificati i 32 articoli tramite UI nativa: 13 racconti, 7 servizi, 4 corsi, 2 vita della Delegazione, 6 ricordi. Export WXR prima/dopo confrontati: 422 elementi conservati integralmente, solo categorie e date di modifica cambiate per i 32 articoli; nessun import DB. Tutte le categorie verificate sul frontend, ricerca nel corpo combinata con data/categoria, ricerche vuote/reset e paginazioni filtrate Blog/Eventi collaudate. Archivi senza filtri invariati; controlli desktop/tablet/mobile e noindex conservato. Pacchetti, rollback tema 1.6.119/Core 1.3.41, WXR e prove in `release/archive-filters-2026-11-07/`. Ripristinare prima il tema e poi Core; per annullare la classificazione usare il mapping precedente senza reimportare l’intero DB.

Rilascio successivo del 7 novembre 2026: tema 1.6.121 e Core 1.3.43. Filtri spostati nella sola sezione Eventi conclusi; i prossimi non vengono più filtrati. Invio/reset tornano a `#past-events`, anni del form limitati ai conclusi. Barra Blog/Eventi senza sfondo, pulsante chiaro, reset accanto al pulsante e conteggio nel titolo solo con filtri attivi. Altezza desktop ridotta a circa 77 px rispetto ai precedenti 159 px; anno/mese affiancati anche su telefono. Test locale con due appuntamenti futuri preservati e collaudo responsive completati. Pacchetti, rollback tema 1.6.120/Core 1.3.42 e prove in `release/compact-archive-filters-2026-11-07/`. Nessuna modifica ai contenuti o alle categorie, nessun import DB.

Rilascio dell’8 novembre 2026: tema 1.6.122 e Core 1.3.44. Controllo 12/24/36/48 risultati per pagina, reset e conteggio totale sotto il form a destra, opzioni brevi Tutti con etichette associate. Aggiornamento automatico delle sole liste con focus mantenuto, debounce testo 650 ms, gestione cronologia/richieste obsolete e fallback GET. Date delle card ricalcolate dopo aggiornamenti parziali. Test PHP e JavaScript superati, responsive locale 1280/1024/768/390/320 px senza overflow. Pacchetti e rollback tema 1.6.121/Core 1.3.43 in `release/live-archive-filters-2026-11-08/`. Distribuire prima Core poi tema; rollback in ordine inverso. Nessuna modifica editoriale o import DB. Aggiornamento nativo WordPress completato su Aruba (Core prima del tema). Blog 32 articoli verificato a 24+8 e 36/48; Eventi 25 conclusi a 24+1 e 36/48. Categorie/anno/Altro, ricerca nel corpo con focus preservato, reset con dimensione mantenuta, URL e responsive 1280/1024/768/390/320 px collaudati online. Nessun errore JavaScript osservato, noindex mantenuto. Prove in `remote-verification.json` della stessa cartella privata.

Rilascio successivo dell’8 novembre 2026: tema 1.6.123, Core invariato a 1.3.44. Eliminata la navigazione completa da Azzera filtri e dalla paginazione di Blog/Eventi conclusi: aggiornamento dello stesso frammento, reset con focus alla ricerca e dimensione mantenuta, paginazione con focus/vista sui nuovi risultati. Retry tramite Filtra resta parziale anche dopo errore di rete; GET ordinario solo senza JavaScript. History API conserva URL e Indietro senza ricaricare il documento. Test automatici di click modificati, retry, richieste obsolete/reset in corso superati; collaudo Aruba di reset, pagine successive e Indietro su entrambi gli archivi. Pacchetto, rollback tema 1.6.122 e verifiche in `release/archive-without-reload-2026-11-08/`. Nessuna modifica editoriale o import DB.

Rilascio successivo dell’8 novembre 2026: tema 1.6.124 e Core 1.3.45. Parametri pubblici semplificati in `search`, `anno`, `mese`, `categoria`, `per_pagina`; alias `fisar_*` mantenuti in lettura, con precedenza dei nuovi anche quando vuoti. Form, reset e paginazione generano i soli nuovi nomi, compresi i link aperti da un URL precedente. JavaScript ripristina anche gli alias tornando con Indietro; aggiornamenti sempre parziali. Test locali PHP/JS e collaudo Aruba di tutti i parametri combinati, vecchi URL, paginazione Blog/Eventi e reset superati. Pacchetti, rollback tema 1.6.123/Core 1.3.44 e prove in `release/simple-archive-urls-2026-11-08/`. Aggiornato Core prima del tema; rollback in ordine inverso. Nessuna modifica editoriale o import DB.

Rilascio successivo dell’8 novembre 2026: tema 1.6.125, Core invariato a 1.3.45. Pulsante Cerca e relativi messaggi aggiornati. Freccia decorativa delle select a 12 px dal bordo, 36 px riservati a destra anche su mobile, colonna categoria desktop con minimo 14rem per i testi lunghi. Controlli nativi preservati; modalità colori forzati con aspetto del sistema. Verificati su Aruba Blog/Eventi, invio Cerca, reset, selezione nativa da tastiera con aggiornamento parziale e responsive 1280/1024/768/390/320 px senza overflow. Pacchetto, rollback tema 1.6.124 e prove in `release/archive-controls-polish-2026-11-08/`. Nessuna modifica editoriale o import DB.

## Prima di aggiornare

1. Verificare branch/worktree, differenza da distribuire, versione remota e ambiente `/anteprima/` corretto.
2. Fare backup recuperabile e definire il rollback. Per codice: versione precedente del tema/plugin e dati eventualmente coinvolti. Per operazioni sul DB: backup SQL e media/config necessari, non solo WXR.
3. Collaudare il cambiamento in locale; verificare che il pacchetto non includa configurazione Docker, `.env`, `wp-config`, credenziali, SQL, log, backup o sorgenti fotografiche non selezionate.
4. Distinguere rilasci di codice da aggiornamenti editoriali. Per aggiungere foto non serve necessariamente cambiare il tema.

## Codice

Tema/plugin installabili sono le sottocartelle omonime, non l’intero repository. Preparare uno ZIP con la sola cartella del componente e verificare indice/integrità/versione; annotare l’impronta se utile al rilascio. Cambiare la versione del tema sia in `style.css` sia in `FISAR_CDJ_THEME_VERSION` quando serve invalidare gli asset aggiornati.

Su Aruba è già stato usato l’aggiornamento nativo **Aspetto → Temi → Aggiungi tema → Carica tema**, con confronto della versione e sostituzione del tema installato. Preservare nome della cartella e opzioni del sito. Distribuire il plugin solo se coinvolto; mai attivare un reimport demo come effetto collaterale.

Alternativa: File Manager Aruba o FTPS sullo spazio Linux. Host usato `ftplnx.fisarcastellidijesi.it`, porta 21, TLS esplicito; un eventuale filtro IP richiede autorizzazione dell’IP corretto. Non disabilitare TLS o aggirare avvisi del certificato per risolvere un errore. Non salvare credenziali nel repository. Intervenire soltanto sulla directory verificata dell’anteprima; un ZIP remoto temporaneo deve rimanere non accessibile dal web e la pulizia non deve coinvolgere backup o file pubblici.

Non caricare il file `docker/compose.yaml`, la configurazione locale o il DB locale. La configurazione Aruba resta propria; preservare `.htaccess` e le protezioni per config/archivi/dump/log. Non aggiungere endpoint amministrativi temporanei pubblici per facilitare un deploy.

## Contenuti e foto

Usare editor e Libreria media dell’anteprima. Prima di cambiare una porzione di pagina salvarne il contenuto corrente, conservare una revisione e confrontare il resto; non sostituire la pagina completa con la copia locale se Aruba è stata aggiornata.

Per la hero caricare soltanto i WebP scelti, impostare alt e aggiungerli alla Galleria `hero-collage` nell’ordine desiderato. Sei fotografie per gruppo; calice separato. Verificare primi gruppi invariati e numero di nuove immagini atteso. Il manifest del repository non viene letto dal sito né sincronizzato con l’editor. Vedere la [guida editoriale](editorial-guide.md#home-e-carosello).

## Collaudo e rollback

- HTTP 200 su Home, archivi/dettagli interessati e asset; nessun URL localhost nel frontend remoto.
- Noindex meta/HTTP conservati finché si tratta di anteprima; admin protetto dal login, config e backup non accessibili.
- Desktop/tablet/mobile, soglia 1023/1024 della hero, overflow, immagini/font, tastiera/focus, pausa/movimento ridotto; console senza nuovi errori.
- Verificare salvataggio e riapertura dell’editor per i contenuti cambiati. Nessun invio reale di newsletter/iscrizione solo per fare uno smoke test.
- Confrontare i dati da preservare e la root pubblica quando pertinente; non assumere che un messaggio di upload equivalga al rilascio riuscito.

In caso di problema ripristinare il componente precedente o la revisione editoriale coinvolta. Non ripristinare l’intero DB a una copia vecchia se nel frattempo sono entrati contenuti nuovi. Un ripristino SQL completo richiede decisione esplicita, ambito e backup dello stato attuale.

## Copie disponibili

Solo percorsi relativi alla cartella privata ignorata `.local-deployment/`; nessuna credenziale o dump deve essere committato.

| Posizione | Contenuto / limite |
|---|---|
| `release/event-reports-2026-11-06/` | Pacchetti Core 1.3.40/tema 1.6.108 e rollback 1.3.39/1.6.107, WXR prima/dopo la preparazione, confronto dei 155 contenuti precedenti, sorgente Facebook e mapping delle dieci foto. Prima una sola bozza, poi pubblicazione esplicitamente richiesta e CTA verificata nel dettaglio dell’Evento. Nessun import DB; WXR non è un backup SQL |
| `backups/2026-10-22/public-files.tgz` e `public-database.sql` | Backup del vecchio sito: archivio e SQL verificati strutturalmente, non un ripristino completo provato |
| `backups/2026-10-22/wp-before-7.1.3/` | Database/file locali prima dell’aggiornamento core |
| `backups/2026-10-24/` | WXR Aruba prima/dopo hero, copie Home e ZIP rollback tema 1.6.98 |
| `backups/2026-10-25/`, `release/hero-2026-10-25/` | Copie Home e mapping dei sei media del quarto collage |
| `backups/2026-10-26/`, `release/values-2026-10-26/` | Export WXR prima del rilascio, ZIP rollback 1.6.99, pagine/CSS precedenti e pacchetto 1.6.102; confronti e schermate dopo il rilascio. WXR non è un backup SQL |
| `release/values-layout-2026-10-26/` | Pacchetto 1.6.105, Home/CSS/root prima e dopo, schermata desktop Aruba. Rollback al pacchetto 1.6.102 in `release/values-2026-10-26/`, verificato contro il CSS remoto precedente |
| `release/event-meeting-2026-10-26/` | Pacchetti plugin 1.3.31/tema 1.6.106, rollback plugin 1.3.30, confronti pubblici prima/dopo e schermata editor Aruba. Rollback tema 1.6.105 in `release/values-layout-2026-10-26/`. Nessuna migrazione/import DB |
| `release/editors-2026-10-26/` | Pacchetti plugin 1.3.33/tema 1.6.107, export WXR prima del rilascio, confronti Home/root/CSS e schermate dei pannelli Aruba. Rollback ai pacchetti 1.3.31/1.6.106 in `release/event-meeting-2026-10-26/`, verificati contro gli asset remoti precedenti. WXR non è un backup SQL |
| `release/whatsapp-editor-2026-10-26/` | Pacchetto plugin 1.3.34, confronti Home/root/script prima e dopo e schermata versione attiva. Rollback 1.3.33 in `release/editors-2026-10-26/`, verificato contro lo script remoto precedente; nessun trasferimento DB o modifica al tema |
| `release/whatsapp-generator-2026-10-27/` | Pacchetto plugin 1.3.38, confronti Home/root/CSS prima e dopo, JS generatore pubblicato e schermate della versione attiva/dialog Aruba. Rollback 1.3.34 in `release/whatsapp-editor-2026-10-26/`, integrità ZIP e CSS/JS verificati contro gli asset remoti precedenti. Generazione provata su evento esistente senza salvarlo o inviare messaggi; nessun trasferimento DB o modifica al tema |
| `release/whatsapp-apostrophe-2026-10-27/` | Pacchetto plugin 1.3.39, confronti Home/root/JS e schermata dell’anteprima corretta nell’evento Edoardo Dottori, senza salvarlo. Rollback 1.3.38 in `release/whatsapp-generator-2026-10-27/`, integrità ZIP e JS confrontati con lo script remoto precedente; nessun trasferimento DB o modifica al tema |

I pacchetti di trasferimento iniziale e la copia DB di build del 22 ottobre **non sono la fonte editoriale e non vanno reimportati**. Le vecchie impronte e i resoconti dettagliati di migrazione sono recuperabili in Git prima della pulizia documentale (`6ec7e27`). Verificare esistenza/integrità delle copie prima di usarle; non trattare un vecchio backup come attuale.

## Checklist per il lancio definitivo

- Confermare pubblicazione, URL finali e gestione del vecchio sito; l’eventuale pagina “lavori in corso” richiede una scelta esplicita.
- Backup aggiornati e percorso di rollback, finestre di modifica concordate.
- Verificare testi/demo residui, contatti, quote/date associative e utilizzabilità pubblica delle fotografie.
- Verificare informativa rispetto a hosting/log/conservazione/cookie effettivi; Mailchimp: consenso, opt-in, tracking e disiscrizione. Il testo storico non è una certificazione della configurazione attuale.
- Rivedere cron, aggiornamenti automatici e sicurezza, sospesi/configurati per lo staging durante il trasferimento.
- Portare l’installazione e il suo **database Aruba aggiornato** all’URL finale, senza reinserire i contenuti. Per cambi URL usare strumenti WordPress consapevoli della serializzazione, non sostituzioni testuali su SQL.
- Verificare permalink, media, redirect, form, HTTPS e admin. Rimuovere noindex soltanto al lancio autorizzato e verificare gli header effettivi.

Questa checklist sostituisce un elenco separato di “Attività aperte”. Non implica che ogni verifica sia già completata o che il lancio sia autorizzato.
