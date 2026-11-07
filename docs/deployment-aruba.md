# Pubblicazione e manutenzione Aruba

## Ambiente attuale e confini

Anteprima: [www.fisarcastellidijesi.it/anteprima/](https://www.fisarcastellidijesi.it/anteprima/), WordPress indipendente nella sottocartella Linux con database dedicato. Frontend senza password, admin con login; noindex sia HTTP sia meta. Conoscere l’URL non rende i contenuti privati.

**Aruba è la fonte principale dei contenuti dal trasferimento iniziale. Non importare più il database locale per un aggiornamento di codice.** Editor, gallerie, menu e configurazione del database devono essere preservati. Root pubblica precedente, DNS, email e impostazioni PHP globali non sono parte di un normale aggiornamento dell’anteprima.

Ultimo rilascio: plugin Core 1.3.40 e tema 1.6.108, il 6 novembre 2026: metadati dei racconti nel Blog, collegamento inverso automatico dagli Eventi e rimandi Facebook/Instagram. Aggiornamento nativo dei soli componenti, nessun import DB o migrazione dei contenuti. Primo resoconto sui vini giapponesi dell’8 ottobre preparato in bozza e poi pubblicato su richiesta dell’utente, con testo originale Facebook e dieci foto originali. Instagram lasciato vuoto perché l’accesso richiede una verifica sul telefono di un’altra persona. Versioni attive, galleria/ingrandimento, CTA automatica e noindex verificati; 155 contenuti preesistenti invariati nel confronto WXR prima/dopo la preparazione. Core remoto osservato 7.1.3; PHP remoto osservato in precedenza 8.5/MySQL 8, distinto da PHP 8.3/MariaDB locale. Ricontrollare l’ambiente prima di un rilascio; non chiedere password in chat né salvarle nella documentazione.

Aggiornamento successivo del 7 novembre 2026: tema 1.6.110 per lo stile facoltativo Griglia uniforme dei racconti; plugin invariato a 1.3.40. Stile applicato al solo articolo sui vini giapponesi, con foto ad alta risoluzione e prima foto della galleria come copertina. Data dell’articolo corretta al 12 ottobre 2026, ore 20:39; recuperato e aggiunto anche il link Instagram tramite accesso personale, con rimando pubblico verificato. Backup del tema 1.6.108 e contenuto precedente conservati; native gallerie/immagini originali e metadati preservati, nessun import DB. Prove e pacchetti in `release/photo-grid-2026-11-07/`.

Ultimo aggiornamento del 7 novembre 2026: tema 1.6.111 per allineare titoli, date, estratti e collegamenti nelle righe dell’archivio Blog, mantenendo i titoli completi. Plugin invariato a 1.3.40. Collaudo locale e remoto a 1280/768/390 px, nessun overflow; HTML del contenuto Blog identico prima/dopo e noindex conservato. Pacchetto, rollback 1.6.110 e prove in `release/blog-card-alignment-2026-11-07/`. Nessun import DB o modifica ai contenuti editoriali.

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
