# Pubblicazione su Aruba

## Quarto collage — 25 ottobre 2026

Aggiornamento solo editoriale, senza nuovo deploy del tema 1.6.99: aggiunti sei WebP nella Libreria media (223–228), alt salvati e soli sei blocchi aggiunti in fondo alla galleria Home dall’editor nativo. I primi 18 blocchi restano byte per byte invariati, così come il contenuto esterno alla galleria; nessun import di DB/seed locale. Copia HTML precedente, risultato e mapping dei nuovi media nell’area privata `.local-deployment/backups/2026-10-25/` e `release/hero-2026-10-25/`, escluse da Git. Collaudo del quarto gruppo, 24 sorgenti uniche, quattro pallini, tastiera e breakpoint superato; screenshot `docs/06-mockups/homepage-hero-collage-04-aruba.png`. Sito pubblico e foto precedenti invariati.

## Aggiornamento mirato hero — 24 ottobre 2026

Su richiesta esplicita, applicata la hero E a locale e alla sola anteprima. Tema remoto verificato 1.6.98, aggiornato a 1.6.99 dal caricamento nativo WordPress, senza FTP, modifica file dal backend o accesso DB. Pacchetto `release/hero-2026-10-24/fisar-cdj-1.6.99.zip` nella cartella privata `.local-deployment/`, SHA-256 `a29a5a02771e8ef63ad5bc66c78b2b3a75a2a937630c6261602ee45a4de77ac0`, 72 voci, privo di configurazioni/credenziali/database. Copia della precedente versione dal repository in `backups/2026-10-24/fisar-cdj-1.6.98-rollback.zip`, 69 voci, verificata; non è un nuovo backup completo del server.

L’utente ha completato il login WordPress; nessuna password letta o memorizzata. Esportazioni native WXR dei contenuti prima/dopo conservate privatamente in `backups/2026-10-24/aruba-before-hero.xml` e `aruba-after-hero.xml`; HTML Home precedente e mapping dei media salvati nella stessa area privata. Sono copie dei contenuti, non backup SQL o dei file media. I backup completi del 22 ottobre restano conservati.

Caricati soltanto 18 WebP selezionati nella Libreria media Aruba (204–221), tutti con alt; nel blocco Galleria `hero-collage` dell’editor Home aggiornati ordine, allegati e punti focali. Introduzione, calice, thumbnail e contenuti esterni alla galleria preservati. Confronto WXR: 102 → 120 elementi, 101 elementi preesistenti diversi dalla Home invariati nei campi titolo/contenuto/estratto/stato/slug/tipo. Vecchi media non cancellati. Nessun import del DB locale, seed, nuovo plugin o modifica alla root pubblica.

Collaudo browser locale/remoto responsive 320–1440 px e soglia 1023/1024, caricamento dei tre collage/18 foto uniche, tastiera, pausa/ripresa e assenza di overflow. Un H1 e noindex meta/HTTP conservati; home/CSS/JS HTTP 200, root pubblica HTTP 200. Il codice rispetta movimento ridotto verificato anche con test unitario, senza cambiare le preferenze del Mac. Rilasci futuri continuano a preservare il database editoriale di Aruba; nessun commit automatico.

## Sintesi e stato — 22 ottobre 2026

Obiettivo autorizzato: trasferire il sito locale in `/anteprima/` sullo spazio Linux esistente, usando un database separato già verificato vuoto. L’anteprima diventerà il sito definitivo senza reinserire i contenuti. Nessuna password sul frontend, ma richiesta di non indicizzazione; il link non rende privati i contenuti. Backend con credenziali sicure, mai quelle demo.

**Anteprima attiva:** [https://www.fisarcastellidijesi.it/anteprima/](https://www.fisarcastellidijesi.it/anteprima/). Dopo conferma esplicita dell’utente, sostituito il blocco temporaneo della sola anteprima con le regole finali: frontend senza password, noindex via header e meta, admin protetto dal login. Backup SQL e file pubblici verificati; file/configurazione trasferiti e secondo database importato. Collaudo frontend superato, login autenticato/editor remoto ancora da verificare con l’utente. Sito pubblico, email, PHP globale e DNS invariati. Da questo passaggio **Aruba è la fonte editoriale principale**: non sovrascriverne il database con quello locale.

## Requisiti e confini

- Conservare sito attuale e possibilità di ripristino; nessuna cancellazione o sovrascrittura della produzione.
- Portare contenuti e media attuali del locale, non rigenerarli dal seed demo.
- Dopo la conferma del trasferimento, Aruba diventa la fonte editoriale principale: aggiornamenti futuri di tema/plugin senza importare il database locale.
- Conservare la hero approvata con calice neutro; fotografie del collage da sostituire successivamente su scelta dell’utente.
- Nessun page builder, servizio aggiuntivo a pagamento o plugin di migrazione persistente necessario.

## Architettura proposta

Installazione WordPress indipendente in una sottocartella, con database dedicato e configurazione propria. File del core, tema, plugin, lingue e uploads trasferiti una volta. Configurazione Docker non distribuibile sul server Aruba: credenziali DB, salt, debug e URL vanno configurati per l’ambiente remoto.

Cambio URL da localhost tramite strumenti WordPress consapevoli della serializzazione, su una copia del database oppure durante un export, mai con una sostituzione testuale sul dump. Riferimento: [migrazione WordPress](https://developer.wordpress.org/advanced-administration/upgrade/migrating/). Escludere sessioni/transient del locale e log dall’artefatto destinato al server. Importare soltanto nel database preventivamente vuoto.

Al passaggio finale cambiare gli URL ed eventualmente spostare tecnicamente l’installazione, preservando il database aggiornato su Aruba. La pagina pubblica “lavori in corso” non è ancora stata applicata: rimane una fase successiva al backup verificato e all’anteprima funzionante.

## Alternative e compromessi

- Sottocartella e database separati: scelta corrente, niente modifiche DNS, isolamento dei contenuti e passaggio reversibile; richiede un successivo adeguamento degli URL.
- Sottodominio/staging gestito: non scelto, il servizio è Hosting Windows e Linux e non è stato acquistato un ambiente gestito aggiuntivo.
- Plugin di migrazione: non introdotto per ora; prima preferire strumenti nativi e trasferimenti FTPS, senza dipendenze persistenti o sincronizzazioni che sovrascrivano i contenuti.

La scelta privilegia affidabilità, sicurezza e mantenibilità rispetto al caricamento immediato. Prestazioni e costi restano quelli del piano esistente; nessuna necessità di scalabilità ulteriore emersa.

## Ricognizione e backup

- Puntamento `www` già su Linux; MySQL 8.0; PHP 8.5 sul server, PHP 8.3 locale. Nessuna modifica globale eseguita.
- Il database della produzione è identificato nella configurazione; un secondo database è verificato privo di tabelle. Parametri di accesso non riportati nei file versionati.
- Copie giornaliere e settimanali dei file presenti nel File Manager; non eseguito un ripristino di prova. Il pannello backup MySQL non mostrava copie disponibili.
- Esportazione SQL completa del database pubblico scaricata tramite phpMyAdmin: 30 tabelle CREATE, 44 blocchi INSERT, COMMIT finale presente. Copia protetta in `.local-deployment/backups/2026-10-22/public-database.sql`, esclusa da Git; SHA-256 `f5064068f6bb85ade4a2c89485ec146600e1987f140a71e36a1492cade73c6b1`. Questa è una verifica strutturale, non un test di ripristino.
- È rimasta anche la copia originaria del download in `Downloads/Sql753729_1.sql`; non pubblicarla o committarla.
- Download dei 23 elementi della root avviato dal File Manager, archivio indicato come `www.fisarcastellidijesi.it-23`. Preparazione rimasta bloccata e sessione di automazione terminata per timeout; nessun ZIP del backup confermato nei download. Non ripetere alla cieca la richiesta. Preferire FTPS per il backup di circa 1,2 GB, con credenziali fornite in modo sicuro dall’utente. [Indicazioni Aruba](https://guide.aruba.it/faq/hosting-e-domini/esportazione-sitoweb-database-email).
- Collegamento FileZilla funzionante dopo uso dell’host Linux `ftplnx.fisarcastellidijesi.it`, porta 21/TLS esplicito, e abilitazione dell’IP effettuata dall’utente. Backup richiesto soltanto della cartella `www.fisarcastellidijesi.it`, escluse le due copie automatiche. Dopo il trasferimento lento, proposta compressione ZIP fuori dalla directory pubblica. L’utente dichiara il backup completato; non ancora disponibile tra i download recenti del Mac controllati. Metodo finale e posizione da confermare, poi verificare archivio/cartella e assenza di ZIP sensibili accessibili via web.
- Successivamente individuato nei Download del Mac `www.fisarcastellidijesi.it.tgz`, **1.185.963.396 byte**. `gzip -t` e lettura completa dell’indice TAR superati: 8.906 elementi, un `wp-config.php`, due `.htaccess`, 611 elementi wp-admin, 3.123 wp-includes e 1.439 uploads, nessun percorso assoluto o con traversal. Copia protetta `.local-deployment/backups/2026-10-22/public-files.tgz`, SHA-256 `d60c96ae63400511371ac9c8f668dffa40fedd784878a7d11e218470d88b5945`; hash identico all’originale, entrambi con permessi 600. Verifica di leggibilità/contenuto, non prova di ripristino completo. Nessun archivio presente nell’indice della root pubblica osservata nel File Manager prima di creare l’anteprima.

## Preparazione locale

**Archivio preliminare obsoleto, non distribuire:** `.local-deployment/release/fisar-preview-files-2026-10-22.zip`, 3.763 file, 63.432.744 byte, creato prima dell’aggiornamento del core. Esclude `wp-config.php`, `.htaccess` locale, log e dump, ma contiene ancora il file standard dell’immagine `wp-config-docker.php`: il prossimo pacchetto deve escludere entrambi i file di configurazione Docker. Non caricato su Aruba. Integrità ZIP verificata con `unzip -t`; SHA-256 `47e8a07b2644e9f5d1ce36dcf5d02373e703ee22099cd98b1522b9415ef1f42a`. Cartelle private con permessi 700, dump SQL con permessi 600 anche nella copia originaria in Downloads; `.local-deployment/` esclusa da Git.

Core locale aggiornato a **7.1.3 it_IT**, con immagine Compose `wordpress:7.1.3-php8.3-apache`. Backup locale precedente all’aggiornamento in `.local-deployment/backups/2026-10-22/wp-before-7.1.3/`: database SQL, archivio completo dei file e impronte di confronto, protetti ed esclusi da Git. Conservati contenuti, account e nuova password, immagini, tema e plugin; verificati checksum ufficiali e schema DB. Nessun aggiornamento eseguito sul sito pubblico. Rigenerare il pacchetto di rilascio dal nuovo stato, senza distribuire configurazione Docker o backup.

Rigenerato il solo archivio dei file: `.local-deployment/release/fisar-preview-files-wp7.1.3-2026-10-22.zip`, **3.772 file / 63.544.874 byte**, SHA-256 `489cb0062460cac954b1a1650fe5893a84b50867fd1f00c88d29c95c4e5f7b36`. Allowlist di file core, cartelle core, tema/plugin custom, lingue e uploads; esclusi entrambi i `wp-config`, `.htaccess`, `.env`, SQL, log e archivi. `unzip -t` superato e nessuna voce vietata nell’indice; permessi 600, directory privata ignorata da Git. Nessuna modifica al database locale o upload remoto. Non è ancora una migrazione pronta: configurazione Aruba ed export DB con URL dell’anteprima vanno preparati separatamente dopo la verifica del backup pubblico.

Preparata una copia isolata locale `fisar_cdj_preview_build_20261022`, senza modificare il DB editoriale: sostituzione URL con WP-CLI consapevole della serializzazione (51 sostituzioni, GUID conservati), home/siteurl `https://www.fisarcastellidijesi.it/anteprima`, `blog_public=0`. Rimossi sessioni, application password, lock di modifica, transient e cron soltanto dalla copia; 170 post e un utente conservati, hash password identico al locale, vecchia password demo non valida. Export privato `fisar-preview-database-2026-10-22.sql`, 12 CREATE TABLE, nessun DROP TABLE/USE; SHA-256 `06c78b73b7655c4c7834e1315f6aefc36c7dccd07aa97ed5dad37ab330265f10`. Collation `utf8mb4_unicode_520_ci`; SQL e config con permessi 600. La copia temporanea locale resta presente per le verifiche, non è la fonte editoriale.

Configurazione propria preparata in `aruba-preview-config/wp-config.php` e caricata soltanto in `/anteprima/`, usando i soli valori DB letterali del backup senza eseguirlo e senza mostrarli; destinazione il secondo database, salt nuovi, ambiente staging, debug e modifica file disattivati. Sintassi PHP verificata. Cron e aggiornamenti automatici sospesi durante la preparazione, da rivedere al lancio. `.htaccess` finale applicato soltanto in `/anteprima/`: noindex anche HTTP, divieto di directory listing e accesso a config/archivi/dump/log.

## Installazione remota in corso

- Dopo il riaccesso MySQL effettuato dall’utente, ricontrollato il secondo database: `No tables found in database`. Importato esclusivamente lì l’SQL privato tramite phpMyAdmin: **Import has been successfully finished, 159 queries executed**. Nessun dump caricato nello spazio web.
- Creata `/anteprima/` tramite File Manager; caricata protezione temporanea `.htaccess` da 100 byte. Verificati sito pubblico HTTP 200 e anteprima HTTP 403.
- ZIP dei soli file caricato nella cartella protetta, dimensione visualizzata 60,60 MB; estrazione `Qui` completata e cartelle core/contenuti presenti. La prima scelta `No` al dialogo generico di sostituzione non ha estratto; confermata poi l’operazione dopo aver verificato che i soli elementi presenti fossero ZIP e protezione e che l’archivio non contenesse `.htaccess`. Caricata infine la sola configurazione dell’anteprima: 22 elementi nella sua root, comprendenti ZIP e protezione; nessun file pubblico sovrascritto.
- Il selettore file del browser ha richiesto recupero della sessione di automazione; caricamenti riusciti tramite l’input file effettivo e attese limitate. Non ripetere azioni senza verificare gli esiti: gli aggiornamenti dell’elenco sono asincroni.

## Apertura e collaudo — 22 ottobre 2026

- L’utente ha confermato la rimozione del blocco dopo aver osservato il Forbidden previsto. Modificato tramite editor testuale soltanto `/anteprima/.htaccess`, senza aprire o riscrivere la configurazione pubblica.
- Homepage, archivi Eventi/Corsi/Blog, Carta dei Valori, Seguici, Contatti, Privacy, Diventa socio, pagine La FISAR/La nostra delegazione e dettagli campione Evento/Corso/Articolo: HTTP 200. CSS, font Poppins e foto principale del collage: HTTP 200. Sito pubblico ancora HTTP 200, nessuna pagina manutenzione applicata.
- `X-Robots-Tag: noindex, nofollow, noarchive` e meta `noindex, nofollow` verificati. Config e ZIP temporaneo non accessibili (403). Admin anonimo reindirizzato al login corretto in `/anteprima/` (302), schermata login 200. Credenziali: account locale migrato e password già impostata dall’utente, mai riportata nei documenti. Login autenticato e salvataggio editor remoto non eseguiti: da verificare con l’utente, senza chiedergli la password in chat.
- Alias WhatsApp/Facebook/Instagram/Statuto verificati con 302 verso le destinazioni attese; Newsletter verso `/anteprima/seguici/#newsletter-page`. Nessun link/src localhost nei campioni browser. Nessun invio di newsletter o modulo di iscrizione durante il collaudo.
- Homepage a 1440/768/390/320 px: un H1, Poppins locale, collage/calice caricati e nessun overflow. Dettagli Corso/Evento, Blog, Valori e Seguici controllati; lightbox Blog aperta e chiusa da tastiera con foto visibile. I due `img` vuoti del template lightbox hanno dimensione zero quando chiusa: non sono immagini di contenuto mancanti. Console senza errori rilevati, viewport ripristinato e screenshot salvati.
- Il solo ZIP dei file nuovi rimane protetto nella cartella dell’anteprima e anche in copia privata locale. Eliminazione non eseguita in questo passaggio: l’ultima conferma era riferita all’apertura del frontend. Rimozione finale da confermare puntualmente, senza toccare backup pubblici o file del sito attuale.

## Rischi e condizioni di avanzamento

- Password admin sostituita personalmente dall’utente; verificato che quella demo non funzioni più e che l’account sia preservato dopo l’aggiornamento. Mai leggere, riportare o registrare la nuova password in chat/documentazione.
- Backup pubblico completo acquisito e verificato strutturalmente; nessuna prova di ripristino eseguita.
- FileZilla collegato dall’utente; non leggere o salvare la password in chat/repository. Se è stato creato un archivio remoto, verificarne la posizione non pubblica.
- Core 7.1.3 funzionante nei controlli frontend su PHP 8.5/MySQL 8 di Aruba, senza cambiare la configurazione condivisa; restano login/editor autenticati e flussi editoriali da provare con l’utente.
- Nessuna password frontend: rimuovere dati riservati e verificare `noindex`; `robots.txt` da solo non è un controllo di accesso.
- Prima del lancio pubblico confermare foto, contenuti demo, pagine legali, moduli e collegamenti esterni. Non inviare newsletter o iscrizioni di test senza richiesta specifica.

## Roadmap e punti aperti

1. Completato: backup file e database protetti, integrità e lettura controllate.
2. Completato: password dell’admin locale sostituita dall’utente e vecchia password demo non più valida; core locale aggiornato a 7.1.3.
3. Completato: archivio 7.1.3, configurazione propria e SQL con URL dell’anteprima, senza modifiche al database editoriale locale.
4. Completato trasferimento e apertura autorizzata: file/configurazione nella sola `/anteprima/`, SQL importato nel database separato, regole finali attive; nessuna SQL/credenziale/ZIP di backup accessibile via web. ZIP dei nuovi file protetto, pulizia ancora da confermare.
5. Completato collaudo frontend HTTPS/noindex/asset/permalink/redirect/responsive; Aruba diventa fonte editoriale. L’utente deve ancora verificare il login e l’editor remoto con le proprie credenziali.
6. Successivamente applicare la pagina di manutenzione pubblica se ancora desiderata, e pianificare il passaggio finale senza sovrascrivere i nuovi contenuti.

Restano verifica autenticata con l’utente, pulizia autorizzata del solo ZIP temporaneo e preparazione del lancio finale. Da ora lavorare sui contenuti di Aruba; nessun futuro deploy di codice deve importare il database locale. L’anteprima resta visibile a chi conosce il link, non privata. Prima del lancio rivedere noindex, cron/aggiornamenti, contenuti demo, fotografie e pagine legali; non sono richiesti acquisti o modifiche DNS per questa fase.
