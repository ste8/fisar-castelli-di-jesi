# Pubblicazione e manutenzione Aruba

## Ambiente attuale e confini

Anteprima: [www.fisarcastellidijesi.it/anteprima/](https://www.fisarcastellidijesi.it/anteprima/), WordPress indipendente nella sottocartella Linux con database dedicato. Frontend senza password, admin con login; noindex sia HTTP sia meta. Conoscere l’URL non rende i contenuti privati.

**Aruba è la fonte principale dei contenuti dal trasferimento iniziale. Non importare più il database locale per un aggiornamento di codice.** Editor, gallerie, menu e configurazione del database devono essere preservati. Root pubblica precedente, DNS, email e impostazioni PHP globali non sono parte di un normale aggiornamento dell’anteprima.

Ultimo rilascio tema: 1.6.99; quattro collage/24 foto aggiornati dalla Libreria media Aruba. Core trasferito 7.1.3; PHP remoto osservato 8.5/MySQL 8, distinto da PHP 8.3/MariaDB locale. Ricontrollare l’ambiente prima di un rilascio, senza presumere che le versioni rimangano immutate. Login/editor remoto verificati con l’utente; non chiedere la password in chat o inserirla nella documentazione.

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
| `backups/2026-10-22/public-files.tgz` e `public-database.sql` | Backup del vecchio sito: archivio e SQL verificati strutturalmente, non un ripristino completo provato |
| `backups/2026-10-22/wp-before-7.1.3/` | Database/file locali prima dell’aggiornamento core |
| `backups/2026-10-24/` | WXR Aruba prima/dopo hero, copie Home e ZIP rollback tema 1.6.98 |
| `backups/2026-10-25/`, `release/hero-2026-10-25/` | Copie Home e mapping dei sei media del quarto collage |

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
