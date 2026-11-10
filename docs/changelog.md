# Changelog sintetico

Solo cambiamenti significativi. Dettagli, prove e versioni precedenti sono nella cronologia Git; il diario pre-pulizia è nel commit `6ec7e27`, al percorso storico `docs/07-development/changelog.md`. Le guide correnti non richiedono di leggere quel diario.

## 10 novembre 2026 — Servizi accessibile dal solo URL, tema 1.6.140 su Aruba

Sospesi i punti di ingresso della pagina durante la revisione: rimosse le voci dai menu principale/footer e il richiamo dalla Home. La pagina pubblica `/servizi/` conserva contenuto, copertina e URL; il riferimento al responsabile in Contatti resta e non rimanda alla pagina. Il componente Home è conservato per una futura riattivazione.

Pubblicazione nativa e accesso diretto verificati su Aruba, Home/Contatti senza rimandi e menu pubblici aggiornati; noindex conservato. Rollback e prova visiva in `.local-deployment/release/services-unlisted-2026-11-10/`.

## 10 novembre 2026 — Pagina Servizi, Core 1.3.50 e tema 1.6.139 su Aruba

Prima versione di Servizi sommelier pubblicata come pagina WordPress nativa: feste, sagre, iniziative in cantina, degustazioni ed eventi aziendali, modalità organizzative e racconti di Staffolo, Slow Wine Fair e Merano. Riutilizzate quattro immagini esistenti, conservando il credito Michele Purin. Aggiunti voce principale/footer e richiamo condizionale in Home; pulsanti verso il nuovo riquadro del Responsabile Servizi in Contatti, con nome/email nella configurazione condivisa del plugin.

Verificati locale e Aruba su sei larghezze da 320 a 1280 px, immagini, allineamento racconti, menu, metadati social e percorso dei pulsanti. Nessun overflow; dati e HTML dei precedenti riquadri Contatti preservati, noindex conservato. Pagina salvata e pubblicazione verificata; il canvas visuale Gutenberg nel browser integrato resta vuoto, quindi il controllo editoriale si basa su codice nativo e risultato pubblico. Aggiornamento nativo dei pacchetti, senza import DB; rollback e prove in `.local-deployment/release/services-page-2026-11-10/`.

## 10 novembre 2026 — Anteprime automatiche dei link, tema 1.6.138 su Aruba

Open Graph per Home, pagine, Eventi, Corsi e Blog: titolo corrente, descrizione nativa o testo originale abbreviato, copertina editoriale oppure immagine istituzionale con logo. Aggiornamento automatico anche per nuovi contenuti, senza nuovi campi o modifiche ai testi salvati. Test locali su creazione/modifica e contenuti non pubblici, confronto di sette pagine Aruba con contenuti/robots invariati.

## 10 novembre 2026 — Barre bordò più compatte, tema 1.6.137 su Aruba

Ridotti padding verticale e distanze sotto il titolo nelle intestazioni principali bordò, mantenendo le dimensioni del testo. Corsi, Eventi e Blog passano da circa 198 a 164 px su desktop; stessa regola nelle altre pagine con intestazione bordò. Verificati desktop/mobile, titoli lunghi e pagine senza sottotitolo; contenuti e noindex invariati.

## 10 novembre 2026 — Titoli più vicini al contenuto, tema 1.6.136 su Aruba

Spazio superiore ridotto a 24 px per intestazioni e sezioni delle pagine Corsi, Eventi e Blog, come negli Eventi conclusi. Verificati desktop e mobile senza overflow; HTML dei contenuti, filtri e noindex invariati.

## 10 novembre 2026 — Archivio Corsi senza nota introduttiva, tema 1.6.135 su Aruba

Rimossa la nota sulla ricostruzione dello storico: i filtri seguono direttamente il titolo Corsi conclusi. Eliminati i soli stili inutilizzati; nota della Carta dei Valori conservata. Lint PHP e confronto Aruba completati: corsi attivi, risultati, filtri e noindex invariati, 63 corsi storici disponibili.

## 10 novembre 2026 — Copertina predefinita Corsi Sommelier, tema 1.6.134 su Aruba

Fotografia generica fornita dalla Delegazione, mostrata automaticamente nelle nove edizioni Sommelier prive di copertina: miniatura quadrata nell’elenco, immagine intera nel dettaglio. Le locandine editoriali hanno precedenza; minicorsi e corsi tematici esclusi. Nessuna assegnazione nel database. WebP ottimizzato senza interpolazione, provenienza e originale conservati. Lint PHP, verifica locale e Aruba desktop/mobile completati; testi, URL e immagini preesistenti delle 37 card invariati. Core invariato a 1.3.49.

## 10 novembre 2026 — Filtri dell’archivio Corsi, Core 1.3.49 e tema 1.6.133

L’archivio dei corsi conclusi dispone di ricerca in titolo, corpo, città, sede e indirizzo; filtri combinabili per anno dell’edizione, tipologia e città, senza mese. Le edizioni con solo anno restano ricercabili e i corsi attivi non vengono filtrati. Risultati 12/24/36/48 per pagina, conteggio, reset e paginazione con aggiornamento parziale e cronologia come Blog/Eventi. Nessuna modifica ai dati editoriali. Test WordPress dedicati e regressioni archivio Corsi/Blog/Eventi/JavaScript superati; collaudo responsive locale e Aruba completato. Pubblicazione nativa verificata sulle 36 schede storiche: copertura completa 12/12/12 e 24/12, corso attivo e card invariati, noindex conservato.

## 7 novembre 2026 — Allineamento schede Blog, tema 1.6.111 su Aruba

Nell’archivio Blog i titoli di diversa lunghezza sfalsavano date ed estratti. Le schede ora condividono le righe di contenuto tramite CSS subgrid da 38rem: titoli completi, date, estratti e collegamenti allineati per riga, senza altezze fisse. Home indipendente, mobile a colonna singola e fallback flex preservati. Verificate dieci schede a 1280, 768 e 390 px, in anteprima locale e su Aruba: allineamento entro un pixel, nessun overflow o errore console osservato. HTML del contenuto del Blog identico prima/dopo. Aggiornamento nativo del solo tema; rollback 1.6.110 e prove in `.local-deployment/release/blog-card-alignment-2026-11-07/`.

## 7 novembre 2026 — Galleria uniforme dei racconti, tema 1.6.110 su Aruba

Nuovo stile nativo facoltativo della Galleria: miniature quadrate su tre colonne desktop/due mobile, ultima riga della stessa misura e priorità ai volti nelle immagini verticali. La cornice ritaglia la miniatura mantenendo le dimensioni proporzionate dell’immagine per il lightbox WordPress: le foto ingrandite restano intere, senza un visualizzatore aggiuntivo. Stile applicato al solo racconto dei vini giapponesi, con testo, foto ad alta risoluzione e metadati preservati. Copertina impostata sulla prima foto della galleria e data dell’articolo allineata al resoconto Facebook del 12 ottobre 2026, ore 20:39. Recuperato anche il resoconto Instagram tramite accesso personale e verificato il collegamento pubblico “Guarda il racconto su Instagram”. Verificati quadrati di tutte le dieci miniature, foto orizzontale/verticale, mobile e assenza di overflow/errori console; tema aggiornato nativamente, plugin invariato a 1.3.40. Rollback 1.6.108 e contenuto precedente conservati in `.local-deployment/release/photo-grid-2026-11-07/`; nessun import DB.

## 6 novembre 2026 — Racconti degli Eventi, Core 1.3.40 e tema 1.6.108 su Aruba

Gli Articoli hanno Evento collegato e URL facoltativi Facebook/Instagram. L’Evento trova automaticamente tutti i racconti pubblici e mostra Leggi il racconto / Leggi i racconti; l’Articolo rimanda all’Evento e usa le diciture Guarda il racconto su Facebook / Instagram. Nessun doppio salvataggio dell’Evento; bozze, privati, programmati, cestinati e contenuti protetti non compaiono nei rimandi pubblici.

Test WordPress per salvataggi autorizzati, nonce, campi omessi, sanitizzazione, pubblicazione, cambio relazione, rimozione e più racconti; regressione editor/riassunto superata. Controllo browser desktop/mobile, nessun overflow o errore console osservato. Rilascio nativo su Aruba con rollback e WXR prima/dopo; 155 contenuti precedenti invariati. Primo racconto sui vini giapponesi dell’8 ottobre 2026 con testo e dieci foto Facebook, inizialmente in bozza, poi pubblicato su richiesta dell’utente: CTA nell’Evento verificata senza modificarlo. Instagram da completare quando sarà disponibile l’accesso. Nessun import DB, aggiornamento core o modifica alla root precedente.

## 27 ottobre 2026 — Correzione anteprima WhatsApp pubblicata su Aruba, plugin 1.3.39

Aggiornato nativamente il solo plugin dalla 1.3.38 alla 1.3.39. Versione attiva e script remoto verificati; nell’evento “Visita alla cantina Edoardo Dottori” il nome dell’azienda è in grassetto anche dopo l’apostrofo, senza modificare o salvare l’evento. Console senza errori osservati, dialog senza overflow desktop. Home e root identiche prima/dopo, noindex HTTP/meta conservati. Nessun import DB, modifica al tema o invio WhatsApp; rollback 1.3.38 verificato e conservato. Prove in `.local-deployment/release/whatsapp-apostrophe-2026-10-27/`.

## 27 ottobre 2026 — Anteprima WhatsApp dopo apostrofo, plugin 1.3.39 locale

Il parser dell’anteprima riconosce grassetto e corsivo anche accanto ad apostrofi semplici o tipografici, per esempio `l'*Azienda Agricola Edoardo Dottori*`. Il testo da copiare resta identico; nessuna modifica al contenuto dell’evento o alla generazione PHP. Test di regressione con titolo, apostrofi, citazioni, corsivo, testo non formattato e copia invariata. Verificato l’esempio nel browser locale modificando solo il messaggio temporaneo, senza salvare l’evento; console senza errori osservati, sintassi JS/PHP regolare. Solo locale; Aruba resta alla 1.3.38.

## 27 ottobre 2026 — Generatore WhatsApp pubblicato su Aruba, plugin 1.3.38

Aggiornato il solo plugin nell’anteprima tramite caricamento e sostituzione nativi WordPress, dalla 1.3.34 alla 1.3.38. Versione attiva e asset CSS/JS verificati; generazione provata sull’evento “Alla scoperta dei vini giapponesi” senza modificare o salvare contenuti, con titolo maiuscolo, scadenza senza anno e diciture lista d’attesa/contatti approvate. Console senza errori osservati, dialog senza overflow desktop; endpoint anonimo negato, archivio/dettaglio HTTP 200. Home e root pubblica identiche prima/dopo, noindex HTTP/meta conservati. Tema invariato a 1.6.107; nessun import DB, reimport demo, invio WhatsApp o modifica a media/menu. Pacchetto precedente 1.3.34 verificato e conservato per rollback; prove in `.local-deployment/release/whatsapp-generator-2026-10-27/`.

## 27 ottobre 2026 — Esempi editoriali WhatsApp nella documentazione

I tre TXT originali spostati dalla root a `docs/sources/whatsapp/`, senza modificarne il contenuto. Collegati nell’indice delle sorgenti e distinti dalle specifiche correnti del generatore; nessuna modifica al sito o al deploy.

## 27 ottobre 2026 — Diciture prenotazioni WhatsApp, plugin 1.3.38 locale

Scadenza tutta maiuscola senza anno, mantenendo completa la data dell’evento. Contatti sempre sotto “INFO E PRENOTAZIONI”, anche per la lista d’attesa; stato “SOLD-OUT - LISTA D’ATTESA” e scadenza ordinaria con suffisso “(LISTA DI ATTESA)”. Test dedicati per entrambe le varianti. Solo generatore locale, nessuna modifica al frontend o ai contenuti Aruba.

## 27 ottobre 2026 — Testo WhatsApp e dialog essenziale, plugin 1.3.37 locale

Titolo evento in maiuscolo Unicode, etichetta “Posizione” e icona web nel richiamo finale. Il generatore ignora eventi e scadenze trascorsi, preservando sold-out/lista d’attesa espliciti e accessi online nascosti; opzione dedicata nelle API condivise, disattivata per default, senza modificare il sito pubblico o i dati. Avviso modifiche non salvate senza istruzioni di formattazione; rimossi messaggio “pronto” e nota finale ridondante. Solo locale, Aruba invariato.

Test PHP/JS dedicati e regressioni editor/ritrovo/carosello superati, con verifica delle regole temporali pubbliche invariate. Browser desktop/mobile: avviso essenziale, istruzioni ripristinate alla riapertura senza modifiche, nessun overflow o errore console osservato; Escape regolare. Lint e HTTP regolari; rimosse le sole due bozze create per il collaudo, nessun contenuto reale modificato.

## 27 ottobre 2026 — Affinamenti generatore WhatsApp, plugin 1.3.36 locale

Funzione spostata in una sezione autonoma finale, anche con ordine personale dei riquadri. Avviso bloccante per eventi nuovi, modifiche non salvate o salvataggi pendenti: controllo nativo del documento e confronto dei campi aggiuntivi, comprese note visuali e contatti dinamici. Il salvataggio riuscito aggiorna l’istantanea senza refresh, autosave/errori non la azzerano. Rimossa la rigenerazione interna: chiusura e riapertura rinnovano il testo. Nessuna riga vuota dopo i titoli di sezione del corpo; link finale introdotto da “TROVI I DETTAGLI ANCHE SUL SITO”. Solo locale; Aruba invariato.

Test PHP/JS e regressioni editor/ritrovo superati; collaudo browser di avviso su data e note, salvataggio della sola bozza di prova e riapertura con dati aggiornati senza refresh. Desktop/mobile senza overflow, Escape/focus e console regolari. Fixture rimosse; contenuti reali non salvati.

## 27 ottobre 2026 — Generatore messaggio WhatsApp Eventi, plugin 1.3.35 locale

Pulsante nel riquadro Dettagli dell’evento con dialog modale, testo modificabile, anteprima di grassetto/corsivo, copia e rigenerazione. Struttura standard ispirata ai tre esempi editoriali ora conservati in `docs/sources/whatsapp/`; titoli, paragrafi, elenchi, enfasi e link convertiti in formato WhatsApp. Dati salvati, API funzionali esistenti e regole coerenti su quote/ritrovo/scadenze/stati; nessun salvataggio, pubblicazione o invio automatico. Endpoint autenticato protetto da nonce/autorizzazione sul singolo Evento; accesso online nascosto non esportato. Anteprima testuale sicura, senza HTML interpolato o esecuzione dei contenuti.

Test PHP/JS dedicati e regressioni editor/ritrovo/carosello superati, incluse generazione read-only e autorizzazioni. Prova browser locale su bozza dedicata: copia con marcatori, modifica/rigenerazione, Escape/focus, desktop 1280, tablet 768 e mobile 390 px senza overflow; console senza errori osservati. HTTP regolare, richieste anonime negate. Bozze temporanee rimosse, nessuna modifica ai contenuti reali. Solo locale, Aruba invariato; esempi originali conservati senza modifiche.

## 26 ottobre 2026 — Correzione WhatsApp pubblicata su Aruba, plugin 1.3.34

Aggiornato nativamente il solo plugin nell’anteprima. Versione attiva e asset JS/CSS verificati; editor Evento aperto e ricaricato senza salvarlo: un solo contatto compilato, nessun riquadro vuoto aggiuntivo. Console senza errori osservati; Home e root identiche prima/dopo, noindex conservato. Tema, contenuti e database non trasferiti. Pacchetto 1.3.33 verificato e conservato per rollback.

## 26 ottobre 2026 — WhatsApp senza riquadro vuoto aggiuntivo, plugin 1.3.34 locale

Negli editor Eventi e Corsi compaiono solo i contatti già salvati; un altro riquadro si apre tramite “Aggiungi contatto WhatsApp”. La riga vuota di fallback è riservata a `noscript`, fuori dall’elenco interattivo, e l’indice dei nuovi contatti parte dal numero di quelli esistenti. Nessuna modifica ai dati o alle regole di salvataggio. Test d’integrazione per zero/uno/più contatti nei due editor, prova browser di aggiunta/rimozione (anche dell’ultimo) e refresh senza salvare contenuti reali; bozze di collaudo rimosse. Sintassi PHP/JS regolare. Solo locale, nessun deploy Aruba.

## 26 ottobre 2026 — Editor pubblicati su Aruba, plugin 1.3.33 e tema 1.6.107

Aggiornamento nativo dei due componenti nell’anteprima, inclusi i miglioramenti Eventi della 1.3.32. Conservati export editoriale e pacchetti precedenti verificati per rollback. Versione plugin attiva e nuovi pannelli Corsi/Articoli verificati nell’admin remoto senza salvare i contenuti aperti; CSS remoti identici ai locali. Home invariata salvo versione delle risorse, root pubblica identica, noindex HTTP/meta conservati; archivi e font HTTP 200. Nessun import DB, reimport demo, modifica a contenuti/media/menu o aggiornamento del core.

## 26 ottobre 2026 — Editor Corsi e Articoli, plugin 1.3.33 e tema 1.6.107 locale

Corsi con sezioni distinte per percorso/direzione, durata, quota, dotazione, tesseramento e promozione; date compatte affiancate, note d’iscrizione separate e calendario diviso fra import facoltativo e tabella modificabile. Nomi dei campi, meta, importazione e salvataggi invariati. Articoli con un riquadro per breve descrizione nativa e indicazioni su immagine principale e Galleria, senza nuovi campi duplicati. Scala del canvas condivisa da Eventi, Corsi e Articoli in `editor-content.css`, eliminando le due copie specifiche recuperabili da Git.

Test d’integrazione su bozze dedicate e test del collegamento al Riassunto superati; provati inserimento, ricaricamento e svuotamento nell’editor con blocchi del testo invariati. Pannelli verificati a 1280, 768 e 390 px, date a capo su mobile, calendario con scorrimento interno e nessun overflow della pagina. Il browser integrato non rendeva il canvas Gutenberg (già vuoto prima dell’intervento): verifiche visive limitate ai pannelli, non certificano il rendering del testo nel canvas. Sintassi PHP/JS, test carosello e HTTP di sito/CSS/font regolari. Bozze di prova rimosse, contenuti esistenti non salvati; nessun deploy Aruba.

## 26 ottobre 2026 — Editor Eventi più leggibile, plugin 1.3.32 locale

Orari spostati sotto il luogo effettivo; ritrovo subito dopo il relativo riquadro anche con ordine personale già memorizzato, tramite filtro di sola lettura. Titoli dei riquadri più grandi delle sottosezioni e dei campi. Separati WhatsApp, altri canali, scadenza e note; data compatta prima del tipo di termine nella stessa riga adattiva. Aiuti senza gergo tecnico e breve descrizione dichiarata facoltativa, con comportamento degli estratti corretto. Campi e salvataggi invariati; nessuna modifica al sito pubblico o ad Aruba. I miglioramenti ai gruppi condivisi e ai titoli si applicano anche all’editor Corsi.

Collaudo nell’editor locale autenticato a 1280, 822 e 390 px, senza salvare l’evento: titoli principali 20 px, sottosezioni 16 px, data 192 px affiancata al tipo su desktop e a capo su mobile, nessun overflow. Regola dei titoli resa specifica per prevalere sugli stili nativi WordPress. Test d’integrazione su ordine, gruppi, aiuti e salvataggi superati.

## 26 ottobre 2026 — Ritrovo Eventi pubblicato su Aruba

Aggiornamento nativo del plugin Core a 1.3.31 e del tema a 1.6.106 nell’anteprima. Verificati versioni attive, nuovo riquadro e inizio facoltativo nell’editor remoto senza salvare l’evento aperto. Home, archivio e dettaglio esistente invariati salvo versione asset/spaziatura del markup; root identica, CSS remoto uguale al locale, noindex conservato. Pacchetti precedenti conservati; nessun import DB, reimport demo o modifica a contenuti/media/menu. I tre eventi creati per il collaudo sono rimasti solo locali e sono stati rimossi.

## 26 ottobre 2026 — Ritrovo Eventi, plugin 1.3.31 e tema 1.6.106 locale

Gruppo editoriale facoltativo per ora, luogo/indirizzo/località/Maps del ritrovo e indicazioni sugli spostamenti, indipendente dai dati effettivi dell’evento. Riepilogo e informazioni pratiche distinguono il ritrovo da luogo/orari dell’evento; card con “Ritrovo:” quando disponibile. Ora inizio non obbligatoria se ignota; nessuna duplicazione o migrazione degli eventi esistenti. Nuovi meta sanitizzati, salvataggi protetti da nonce/autorizzazioni e marker; ritrovo nascosto negli eventi solo online senza cancellarne i dati. Test d’integrazione locale e responsive; pubblicazione Aruba documentata separatamente a rilascio completato.

## 26 ottobre 2026 — Tema 1.6.105 pubblicato su Aruba

Aggiornato il solo tema dell’anteprima con l’indice Valori senza separatori e frecce, disposto in due colonne indipendenti (1–4 e 5–7), una sola su mobile. Pacchetto precedente 1.6.102 verificato contro il CSS remoto e conservato per rollback. Verificati CSS identico al locale, responsive desktop/tablet/mobile senza overflow e noindex preservato; resto della Home e sito alla radice identici prima/dopo. Nessun import DB o aggiornamento di contenuti, media e plugin.

## 26 ottobre 2026 — Colonne Valori indipendenti, tema 1.6.105 locale

Separati i due gruppi dell’indice Home: il ritorno a capo del punto 2 non aumenta più la distanza tra i punti 6 e 7. Invariati contenuti, link e ordine verticale; su mobile i gruppi formano una colonna continua. Solo locale, nessuna modifica ad Aruba o al database.

## 26 ottobre 2026 — Indice Valori per colonne, tema 1.6.104 locale

Rimosse le frecce decorative dai sette link della Home. Lettura verticale: punti 1–4 nella prima colonna e 5–7 nella seconda; colonna unica 1–7 sui formati stretti. Conservati ordine DOM/tastiera, icone, sottolineature e destinazioni. Solo locale, nessuna modifica ad Aruba o al database.

## 26 ottobre 2026 — Indice Valori senza separatori, tema 1.6.103 locale

Rimosse le linee tra i sette punti della Carta dei Valori nella Home. Icone, numerazione, link sottolineati e spaziature conservati. Solo locale, nessuna modifica ad Aruba o al database.

## 26 ottobre 2026 — Tema 1.6.102 pubblicato su Aruba

Aggiornamento nativo del solo tema nell’anteprima: nuova immagine della Carta, fascia Home compatta con sette titoli numerati e link ai capitoli. Conservati export editoriale e pacchetto rollback 1.6.99, verificato sul CSS remoto prima del rilascio. Asset pubblicati identici ai locali; confronti HTML confermano il resto della Home, il testo della Carta e il vecchio sito alla radice invariati. Noindex HTTP/meta preservati; nessun import DB, reimport demo o aggiornamento plugin.

## 25 ottobre 2026 — Carta in Home come indice, tema 1.6.102 locale

Fascia Valori accorciata: una frase introduttiva e sette titoli con icone, numerati da 1 a 7 come nella Carta, senza descrizioni. Ogni titolo è un link nativo all’ancora del capitolo nella Carta; aggiunto “Chi siamo”, titoli allineati al documento e CTA “Leggi la Carta dei Valori completa”. Link riconoscibili senza hover, righe cliccabili e griglia responsive compatta. Immagine generata approvata conservata; documento completo e database invariati. Solo locale, nessun deploy Aruba.

## 25 ottobre 2026 — Immagine Carta dei Valori, tema 1.6.100 locale

Nuova illustrazione fotografica generata: filari al tramonto e mano che raccoglie uva, comune alla fascia Valori della Home e al fallback della pagina Carta. WebP ottimizzato, sorgente PNG e prompt conservati; precedente asset mantenuto. Overlay e punto focale adeguati ai due contesti, senza cambiare testi o struttura. L’immagine in evidenza della pagina resta prioritaria se impostata nell’editor. Applicazione solo locale, nessun deploy Aruba o modifica al database.

## 25 ottobre 2026 — Documentazione compatta

- Accorpate specifiche e istruzioni in guide di stato; eliminati roadmap completata, assunzioni/decisioni superate e mockup/provini dalla versione corrente. Nessun documento “Attività aperte”; verifiche di lancio nella guida Aruba.
- Conservati Carta V2 e testo privacy come sorgenti distinte dai contenuti correnti. Originali fotografici, manifest e WebP raggruppati in `assets/photography/sito-home/`; rigenerazione senza dipendenze dai mockup.
- Nessuna modifica al frontend del tema, ai dati WordPress o ad Aruba; nessun reimport o commit automatico. Materiale rimosso recuperabile in Git, senza riscrittura della storia.

## 25 ottobre 2026 — Quarto collage

Sei foto scelte dall’utente aggiunte alla galleria Home locale e Aruba: ora 24 scatti distinti/quattro gruppi. Primi tre gruppi invariati, quarto pallino automatico, tema 1.6.99 senza nuovo deploy. Originali conservati.

## 24 ottobre 2026 — Hero approvata, tema 1.6.99

Carosello di gruppi da sei fotografie reali con calice illustrativo naturale, pallini e pausa discreti; nascosto sotto 1024 px, movimento ridotto e controlli da tastiera. Import nella Home locale e aggiornamento nativo di tema/media/sola galleria Aruba, senza import DB locale. Archiviate tutte le 27 immagini originali fornite.

## 22 ottobre 2026 — Anteprima Aruba e core locale

Backup del sito precedente e trasferimento iniziale in `/anteprima/` con DB separato; frontend aperto senza password ma con noindex e protezioni su config/archivi, admin con credenziali cambiate dall’utente. Aruba diventa fonte editoriale. Vecchio sito, DNS/email e PHP globale preservati.

Core locale aggiornato a WordPress 7.1.3 con backup e confronto dati/file, tag Docker allineato. Le copie di build del trasferimento non sono basi per aggiornamenti editoriali successivi.

## Baseline funzionale consolidata

- CPT Eventi/Corsi, backend nativo, calendario strutturato/import TSV, titoli automatici Corsi, stati temporali, scadenze, sold-out/lista d’attesa, quote flessibili, contatti WhatsApp multipli e link Maps. Accesso online Eventi pubblicabile solo esplicitamente.
- Home, archivi/dettagli e pagine associative coerenti; Poppins/Cormorant self-hosted, immagini principali quadrate, focus e responsive, canvas editoriale Eventi/Corsi allineato. Blog su Articoli e gallerie lightbox native.
- Contatti e dati associativi condivisi, Seguici/Newsletter Mailchimp, link brevi, privacy unica, Carta V2 e contenuti associativi in pagine native. Bootstrap demo separato dai dati pubblicati.

## 10 novembre 2026 — Archivio storico dei corsi

- Gestione esplicita delle edizioni concluse senza data finale e del solo anno quando l’inizio non è documentato, con iscrizioni/offerte chiuse e campi storici facoltativi.
- Distinzione fra percorso Sommelier, minicorsi di avvicinamento e corsi tematici. Nessuna migrazione dei corsi esistenti.
- Importate su Aruba 36 edizioni 2020–2026 con testi originali, dati verificati e 17 locandine per 24 schede; corso attivo Falconara 2026 conservato. Copertine ancora mancanti e un annuncio 2020 dubbio registrati separatamente.

- Completato il corso Ancona febbraio 2026 dalla pagina originale: presentazione e 14 lezioni, quote, materiale e tesseramento; due date corrette a marzo su conferma dell’utente e fine al 21 aprile. Relatori storici non documentati mostrati con un trattino.
