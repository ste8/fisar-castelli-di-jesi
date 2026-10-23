# Changelog

## Apertura anteprima Aruba — 22 ottobre 2026

- Su conferma esplicita dell’utente, sostituito il blocco temporaneo del solo `/anteprima/.htaccess` con le regole predisposte: frontend senza password, noindex via header/meta, divieto di listing e accesso a config/archivi/dump/log. Homepage anteprima e sito pubblico HTTP 200; nessuna modifica al sito pubblico, email, PHP globale o DNS.
- Collaudati archivi, pagine istituzionali e dettagli campione (HTTP 200), asset locali e alias 302; admin anonimo reindirizzato al login dell’anteprima, config e ZIP protetti con 403. Homepage a 1440/768/390/320 px senza overflow, Poppins e collage caricati, un H1 e nessun link/src localhost nei campioni. Lightbox Blog aperta/chiusa con foto visibile; console senza errori rilevati, viewport ripristinato e screenshot salvati. Nessun modulo inviato, login autenticato/editor remoto da verificare con l’utente.
- Skill `clean-code-engineer`: sola configurazione di accesso e documentazione, nessun cambio a tema/plugin o contenuti. Da ora Aruba è la fonte editoriale; vietato sovrascriverne il database con futuri deploy locali. ZIP temporaneo ancora protetto, eliminazione da confermare separatamente; copia privata locale conservata. Nessun commit automatico.

## Trasferimento anteprima protetta — 22 ottobre 2026

- Backup pubblico TGZ individuato nei Download e verificato (`gzip -t`, indice completo: 8.906 elementi, config/core/uploads presenti, nessun traversal); copia privata da 1.185.963.396 byte con hash corrispondente, permessi 600 anche sull’originale. Test di ripristino completo non eseguito.
- Skill `software-architect`/`clean-code-engineer`: copia locale DB isolata, sostituzione URL nativa serializzazione-safe, noindex e pulizia di sessioni/cache/lock/cron solo sulla copia. Conteggio 170 post/un utente e password conservati; fonte locale ancora `http://localhost:8090`. Configurazione staging privata generata dai soli valori DB letterali del backup, senza eseguirlo o esporre segreti; salt nuovi e controlli PHP superati. Hash e percorsi documentati nel runbook, niente dipendenze o modifiche a tema/plugin.
- Creata soltanto `/anteprima/` sullo spazio web, con blocco di installazione HTTP 403; sito pubblico controllato HTTP 200. Dopo il riaccesso MySQL dell’utente, secondo database ricontrollato vuoto e import riuscito: 159 query. SQL mai caricato sul web. ZIP codice/media trasferito ed estratto, configurazione propria caricata nella sola anteprima; protezione temporanea preservata.
- Anteprima **non ancora aperta o collaudata**: richiesta conferma per il frontend senza password/noindex e la rimozione del solo ZIP temporaneo, la cui copia locale resta conservata. Screenshot di preparazione salvato; dati/editoria non ancora trasferiti operativamente ad Aruba. Sito pubblico, email, DNS e PHP globale invariati; nessun commit automatico.

## Preparazione file anteprima e stato backup — 22 ottobre 2026

- L’utente conferma il backup completo dopo il collegamento FTPS; percorso e integrità non ancora verificati, nessun nuovo archivio nei download recenti del Mac controllati. Distinto il completamento dichiarato dalla verifica tecnica; installazione remota non avviata.
- Skill `software-architect`/`clean-code-engineer`: preparazione locale senza toccare database o sito pubblico. ZIP dei soli file WordPress 7.1.3, tema/plugin custom, lingue e uploads generato con allowlist; esclusi configurazioni Docker e reali, `.htaccess`, `.env`, log, SQL e archivi. 3.772 file, 63.544.874 byte, integrità ZIP e indice superati; SHA-256 e limiti documentati in `deployment-aruba.md`, permessi 600 e cartella ignorata da Git. Non contiene l’export DB o la configurazione remota, non è ancora pronto da installare.
- Script temporaneo PHP verificato, documentazione allineata; nessuna dipendenza, modifica al tema/plugin o commit automatico. Il sito pubblico e l’assenza dell’anteprima restano invariati.

## Manutenzione — WordPress locale 7.1.3, 22 ottobre 2026

- Dopo il cambio password eseguito dall’utente, acquisiti backup SQL e archivio completo dei file locali (core, configurazione, uploads, tema/plugin; escluso il log), con permessi privati nella cartella ignorata `.local-deployment/backups/2026-10-22/wp-before-7.1.3/`. Baseline di impronte per contenuti, meta, tassonomie, utenti, opzioni principali e file.
- Aggiornamento nativo WP-CLI da 7.1 a **7.1.3 it_IT**; schema DB già all’ultima versione. Checksum ufficiali superati, con la sola segnalazione attesa del file extra `wp-config-docker.php` dell’immagine ufficiale. Allineato Compose a `wordpress:7.1.3-php8.3-apache`, ricreato soltanto il servizio WordPress; database e volumi conservati, servizi healthy.
- Confronto esatto pre/post superato prima degli smoke test editor: contenuti, meta, tassonomie, utenti, opzioni principali, immagini e file custom invariati. Nuova password conservata e password demo non valida; nessun seed, reset o aggiornamento del codice tema/plugin. Gli editor di Corsi, Eventi e Articoli si aprono senza errori JavaScript rilevati; nessun contenuto salvato durante le verifiche (possibili soli lock di modifica WordPress).
- Lint di tutti i PHP custom e dei sei JS superato; undici URL frontend, inclusi archivi e dettagli, HTTP 200. Homepage controllata a 320/390/768/1440 px: un H1, Poppins, immagini caricate e nessun overflow. Dettagli Evento/Corso controllati a desktop, console senza errori rilevati e log Apache senza errori PHP recenti. Conferma visiva della versione in bacheca salvata; viewport ripristinato.
- Skill `clean-code-engineer`: intervento limitato a core e infrastruttura locale, documentazione allineata senza dipendenze né bump del tema/plugin. Il precedente archivio di migrazione è ora **obsoleto**, da rigenerare escludendo anche `wp-config-docker.php`; corretta la precedente descrizione troppo ampia dell’esclusione dei file Docker. Aruba, email e DNS non toccati, nessun commit automatico. Il backup dei file pubblici rimane necessario prima del trasferimento.

## Preparazione pubblicazione Aruba — 22 ottobre 2026

- Autorizzati backup del sito pubblico e futura installazione separata in `/anteprima/`, senza password frontend ma con noindex, usando un database vuoto. Ricognizione in sola lettura: Linux, MySQL 8.0, PHP 8.5; configurazione e database pubblico identificati, secondo database senza tabelle, copie file presenti ma nessun backup DB disponibile nel pannello.
- Acquisito backup SQL pubblico in cartella locale protetta e ignorata da Git: verificati 30 CREATE TABLE, 44 INSERT, COMMIT finale e SHA-256; non ancora provato il ripristino. Tentativo di download file completo bloccato durante la preparazione dell’archivio nel File Manager: copia file non confermata, non dichiarata riuscita.
- Preparato solo localmente un archivio preliminare di 3.763 file/63.432.744 byte, privo di configurazione Docker, credenziali, log e dump. Core, contenuti e password locale invariati; patch core segnalata e credenziali demo ancora da risolvere prima del rilascio.
- Skill `software-architect` e `clean-code-engineer`: percorso reversibile documentato in `deployment-aruba.md`, separazione dati/codice, nessun plugin di migrazione aggiunto e nessun import del seed. Aggiunta l’esclusione `.local-deployment/` da Git; nessuna modifica a tema/plugin, sito pubblico, email o DNS e nessun commit automatico. Anteprima **non ancora creata né pubblicata**; servono backup file affidabile e cambio password eseguito dall’utente.

## v1.6.98 — Calice naturale e leggermente più a sinistra, 15 ottobre 2026

- Corretto il solo calice illustrativo con `imagegen` integrato: vetro incolore e riflessi neutri, vino giallo paglierino, niente dominante dorata. Forma e marchio conservati; fotografie documentarie inalterate. PNG v2 con alpha e WebP 640 × 960/qualità 85 da 67.560 byte; prompt e provenienza nel JSON dedicato.
- Livello spostato a sinistra di 1.5rem desktop e 1rem sui formati piccoli. Dimensioni, altezza della hero, testi/CTA e galleria dell’opzione 2 invariati. Skill `clean-code-engineer`: riusato il blocco immagine separato, senza nuova logica, script o dipendenze. Prima prova conservata.
- Aggiornato solo il blocco `hero-glass` della Home con revisione e guardie, nuovo allegato 190; resto della home, altri contenuti e thumbnail verificati invariati. Round-trip e idempotenza superati; seed allineato senza reimport o bump plugin/demo. Tema 1.6.98, nessun commit.
- Verifica browser a 320/390/768/1024/1440/2560 px: nessun overflow, immagini caricate e altezza invariata (448 px desktop); focus CTA 3 px, console senza errori. Sintassi PHP/JS, JSON e whitespace superati; home/calice/CSS HTTP 200. Alpha PNG/WebP verificato, anteprime desktop/mobile salvate; viewport ripristinato e scheda temporanea chiusa.

## v1.6.97 — Variante del collage con calice, 15 ottobre 2026

- Conservata la composizione a sei foto come **opzione 2**, con anteprima stabile nel repository. Nuova opzione 3: stesso mosaico con calice illustrativo FISAR Castelli di Jesi scontornato in primo piano; foto reali, ordine, cornici, copy e CTA invariati. Varnelli sempre esclusa.
- Skill `imagegen`, strumento integrato: estrazione illustrativa dalla precedente scena v4 approvata, PNG 1024 × 1536 con alpha e WebP locale 640 × 960/qualità 85 da 92.488 byte. Trasparenza verificata; prompt e provenienza nel JSON dedicato. Non è la foto di un prodotto esistente e non modifica i soggetti delle foto documentarie.
- Skill `clean-code-engineer`: livello separato da un blocco Immagine nativo `hero-glass`, rendering/srcset WordPress e CSS condizionale, senza campi, script o dipendenze nuovi. Togliendo solo il blocco torna l’opzione 2; nessun asset precedente cancellato. Calice decorativo escluso dall’albero accessibile. Tema 1.6.97, plugin/demo invariati, nessun reimport o commit.
- Aggiornata soltanto Home 4 con revisione e guardie; allegato 188. Introduzione preesistente, galleria a sei foto, thumbnail e tutti gli altri contenuti verificati invariati. Round-trip dei blocchi e idempotenza del nuovo helper superati senza duplicazioni.
- Browser a 320/390/768/1024/1440/2560 px: nessun overflow, sette immagini caricate; altezza della hero invariata rispetto all’opzione 2 (448 px desktop, circa 698 px a 390 px e 682 px a 768 px). Calice limitato a 288 px e mosaico a 864 px sui monitor larghi. Un H1, font caricati, focus CTA visibile 3 px e console senza errori. Lint PHP/JS, JSON, controllo whitespace e risposte HTTP 200 per home, calice e CSS; anteprime desktop/mobile salvate.

## v1.6.96 — Hero panoramica più densa e meno alta, 15 ottobre 2026

- Ripensata la prova rifiutata dall’utente: sei fotografie reali in una parete inclinata, due scene principali e quattro dettagli; cornici più sottili, niente nastro o grande margine Polaroid. Esclusa Varnelli dalla galleria corrente e dal seed, senza cancellare il vecchio allegato. Calice AI non utilizzato. Copy e CTA invariati.
- Fotografie indipendenti dalla misura della fascia su desktop: hero 448 px a 1024/1440/2560 px, rispetto ai circa 669 px precedenti a 1440 px (−33%). Min-height accessibile, non altezza massima bloccata; maschera laterale, ritagli decorativi e focali delle due scene principali. Mobile/tablet con mosaico orizzontale compatto sotto alle CTA: circa 698 px di hero a 390 px e 682 px a 768 px, senza composizione quasi quadrata.
- Quattro nuovi WebP locali da 82.392/49.466/113.926/52.808 byte. JPEG originale della sala con botti e tre acquisizioni di anteprima dal browser con provenienza/dimensioni esplicite: non dichiarate originali ad alta risoluzione. Nessuna generazione, ricostruzione o ritocco dei volti; nessuna foto della Libreria media selezionata per la nuova variante.
- Skill `clean-code-engineer`: galleria e rendering nativi riusati, CSS circoscritto alla classe condizionale della hero; nessun nuovo campo, script o dipendenza. Seed idempotente allineato senza reimport/bump demo/plugin; tema 1.6.96. Sostituita solo la galleria della Home con revisione/guardie, resto della home, altri contenuti e thumbnail invariati. Nuovi allegati 183–186; precedenti conservati. Nessun commit automatico.
- Lint PHP, sintassi dei due JS, JSON e `git diff --check` superati; home/CSS/quattro asset HTTP 200. Round-trip dei blocchi, selezione a sei immagini, esclusione di 180 (Varnelli) e idempotenza verificati senza ulteriori scritture. Browser a 320/390/768/1024/1440/2560 px: nessun overflow, sei immagini caricate, un H1 e font pronti; foto limitate a 864 px sui desktop larghi. Focus CTA 3 px, console senza errori. Screenshot finali desktop/mobile salvati, viewport ripristinato e schede temporanee chiuse.

## v1.6.95 — Collage di fotografie reali nella hero, 15 ottobre 2026

- Su richiesta, tre momenti reali della Delegazione in una composizione tipo album: degustazione nella sala in pietra, due sommelier e visita alla Distilleria Varnelli. Cornici avorio, rotazioni statiche leggere, ombre e nastro decorativo CSS su fondo caldo. Testi/CTA invariati; calice AI escluso da questa prova, non eliminato. Sul mobile collage compatto sotto alle CTA, senza caroselli o animazioni.
- Due JPEG originali e provenienza conservati, nuovi WebP 750 × 1000/qualità 80 da 127.186 e 54.630 byte. Nessun ritocco o ricostruzione di persone/scene. Fotografie separate, native e locali; nessuna dipendenza o richiesta a Facebook a runtime.
- Skill `clean-code-engineer`: dati in un blocco Gallery nativo della Home con classe `hero-collage`, rendering core nel tema e sola decorazione CSS, senza ID hardcodificati o nuova UI. Fallback alla foto in evidenza conservato. Seed idempotente e helper immagini riusati, niente reimport/bump demo/plugin; tema 1.6.95, nessun commit automatico.
- Aggiornata solo Home 4 con revisione recuperabile e guardie; testo introduttivo, tutti i thumbnail e altre pagine/contenuti verificati invariati. Nuovi allegati 179/180; 178 e le precedenti hero preservati. Round-trip dei blocchi, tre immagini selezionate e idempotenza del seed verificati, senza duplicazioni o ulteriori scritture.
- Lint PHP, sintassi dei due JS esistenti, JSON e `git diff --check` superati. Home, nuove immagini e CSS HTTP 200. Verifica a 320/390/768/1024/1440/2560 px: nessun overflow, immagini caricate, un H1 e font locali; composizione limitata a 616 px sui desktop larghi, nessuna interferenza con il testo o le sezioni successive. Focus CTA da tastiera 3 px, console senza errori. Screenshot desktop/mobile salvati, viewport ripristinato a fine verifica.

## v1.6.94 — Prova hero con foto reale della Delegazione, 15 ottobre 2026

- Applicata l’opzione 1 scelta dall’utente: degustazione nella sala in pietra dalla galleria Facebook. Testo a sinistra e foto intera a destra da 64rem; sotto, immagine dopo testo e CTA. Nessun overlay, maschera, taglio delle persone o ingrandimento continuo sui desktop larghi. Identità, payoff, descrizione, CTA e altre sezioni invariati.
- JPEG 2048 × 1536 e JSON di provenienza conservati in `assets/photography/`; WebP 1600 × 1200, qualità 80, 414.464 byte generato con l’editor immagini WordPress. Nessuna generazione AI o modifica ai volti. Variante illustrativa v4 e allegati precedenti conservati.
- Skill `clean-code-engineer`: renderer/srcset e helper di import riusati, nuovo `sizes` e alt nativo dalla Libreria media. Solo thumbnail locale della home 4 sostituito da 177 a 178; altri post, contenuti e thumbnail verificati invariati, precedente file preservato. Seed allineato senza reimport o bump demo/plugin; tema 1.6.94, nessuna dipendenza o commit automatico.
- Lint PHP, sintassi dei due JS esistenti, JSON e `git diff --check` superati; home, immagine/derivato e CSS HTTP 200. Verifica responsive a 320/390/768/1024/1440/2560 px: nessun overflow, immagine intera e caricata, scala massima 616 × 462 px sia a 1440 sia a 2560 px, un H1 e font caricati. Focus da tastiera 3 px e console senza errori. Screenshot desktop/mobile salvati; viewport ripristinato a fine verifica.

## Manutenzione — Pulizia delle prove hero, 15 ottobre 2026

- Su approvazione dell’utente, tolti dal repository i nove file delle prove v1–v3 (tre PNG, tre WebP, tre JSON), per 5.608.123 byte. Conservati soltanto originale, WebP e provenienza della v4; vecchio asset demo conviviale già versionato non toccato. Prove spostate fuori repository in cartella temporanea recuperabile, non eliminate definitivamente.
- Skill `clean-code-engineer`: verificato il riferimento unico alla v4 nel seed, aggiornati README/specifiche e provenienza senza riferimenti a file intermedi mancanti. I paragrafi sulle iterazioni restano storici; nessun cambio a codice, versioni, frontend, contenuti o Libreria media, nessun reimport o commit automatico.
- Verificati nove file nell’archivio temporaneo, JSON valido e percorsi della v4 esistenti; PNG selezionato identico all’output approvato e WebP attuale invariato. Nessun riferimento ai vecchi nomi file rimasto nel repository e `git diff --check` superato. Home e hero HTTP 200; thumbnail 177 e hash degli allegati 85/174/175/176/177 invariati dopo la pulizia. Nessun test visivo ripetuto perché frontend e immagini servite non cambiano.

## v1.6.93 — Calice più leggibile sui desktop, 15 ottobre 2026

- Corretto l’overlay che oscurava il calice più centrale nella nuova scena, segnalato sul portatile. Da 64rem il gradiente resta intenso a sinistra ma scende al 4% dal 58% della fascia; ombra verticale inferiore leggera per descrizione e CTA. Immagine v4, maschera, limite 90rem, punti focali, testi e mobile/tablet invariati.
- Skill `clean-code-engineer`: sola regola di presentazione nel tema, nessun nuovo asset, script, dipendenza o dato. Versione tema 1.6.93, nessun commit automatico. Lint PHP finale e `git diff --check` superati; home, v4 e CSS finale HTTP 200. Controllo visivo a 1709 px, coerente con lo screenshot del portatile, e a 1024/1440 px: marchio leggibile e testo protetto, senza overflow.
- Focus da tastiera sulla CTA 3 px, console senza errori e overlay precedente confermato a 768 px. Screenshot finale salvato, viewport ripristinato e scheda temporanea chiusa.

## v1.6.92 — Applicata la scena con sommelier e partecipanti, 15 ottobre 2026

- Nuova immagine in evidenza della home con sommelier più definita, due ospiti sulla destra, senza tovagliolo e ciuffo vegetale. PNG 2110 × 745 px e provenienza/prompt nel progetto; WebP da 126.976 byte con derivati nativi. Conservati vino limpido, versioni precedenti e limite 90rem. Punti focali 45% smartphone/42% tablet per privilegiare il calice; ospiti ritagliabili.
- Skill `clean-code-engineer`: nessuna nuova generazione o dipendenza; helper/renderer riusati, aggiornamento locale mirato con guardie e verifica degli altri contenuti/thumbnail invariati (177 al posto di 176). Seed allineato senza reimport o bump demo, plugin invariato; tema 1.6.92. Testi, CTA, altezza, overlay e maschera conservati; nessun commit automatico.
- Lint dei tre PHP, sintassi dei due JS, JSON con entrambi i prompt e `git diff --check` superati. Browser a 1887/2560/1440/1024/768/390/320 px: caricamento corretto e nessun overflow; limite 1440 px mantenuto su desktop largo, un H1 e font locali caricati. La successiva segnalazione sul portatile porta alla correzione della sola velatura descritta sopra; verifiche HTTP eseguite sulla versione finale. Versione precedente e altri contenuti verificati invariati.

## v1.6.91 — Vino più naturale e raccordo destro della hero, 15 ottobre 2026

- Risolti i riflessi a cellette nell’originale con una correzione mirata image_gen; attenuata la zona luminosa dietro la sommelier all’estrema destra e ampliata la sfumatura CSS desktop. Conservati limite 90rem, testi, CTA e punti focali. Nuovo PNG v3, prompt esatto/provenienza e WebP da 104.746 byte nel progetto; vecchie versioni conservate.
- Skill `imagegen`: modifica integrata sul PNG v2, output versionato e conversione nativa WordPress. Skill `clean-code-engineer`: helper e renderer esistenti riusati, cambio del solo thumbnail con guardie e verifica degli altri post/immagini invariati (176 al posto di 175); seed aggiornato senza reimport, plugin/demo invariati, tema 1.6.91. Nessuna nuova dipendenza o commit automatico.
- Lint dei tre PHP, sintassi dei due JS, JSON di provenienza e `git diff --check` superati; home/immagine/derivato/CSS HTTP 200. Browser a 1887/2560/768/390 px: vino e raccordo controllati visivamente, limite 1440 px conservato sui desktop larghi, nessun overflow o immagine fallita, un H1 e font caricati. Focus CTA 3 px e console senza errori. Screenshot confrontabile con quello dell’utente salvato; viewport ripristinato e scheda di test chiusa.

## v1.6.90 — Hero senza ingrandimento continuo sui desktop larghi, 15 ottobre 2026

- Foto centrata e limitata a 90rem da quella stessa soglia, con bordi sfumati su fondo scuro caldo. A 1920 e 2560 px il box mantiene la stessa larghezza e altezza invece di ingrandire i soggetti. Composizione sotto 90rem, testi, CTA, altezza e overlay conservati.
- Skill `clean-code-engineer`: sola presentazione nel tema, media query circoscritta e `sizes` allineato alla larghezza massima nel renderer nativo. Nessun JS aggiuntivo, asset rigenerato, scrittura nel database, reimport, dipendenza o modifica al plugin. Tema 1.6.90; nessun commit automatico.
- Lint dei due PHP, sintassi dei due JS del tema e `git diff --check` superati; home/CSS/hero HTTP 200. Thumbnail 175 e hash del corpo home invariati. Browser a 2560/1920/1440/768/390 px: scala del box identica (1440 × 441 px) a 1920 e 2560, sfumatura visivamente controllata, nessun overflow o immagine fallita; tablet/mobile senza maschera e con punto focale precedente. Un H1, font caricati, focus CTA 3 px e console senza errori. Screenshot desktop largo salvato, viewport ripristinato e scheda temporanea chiusa.

## v1.6.89 — Applicata la hero con sommelier, 15 ottobre 2026

- Su approvazione dell’utente, nuova immagine in evidenza della home con calice personalizzato, sommelier in smoking/tastevin e bottiglia. Originale PNG e provenienza/prompt nel progetto; WebP 1942 × 809 px da 105.760 byte, con derivati responsive nativi. Le immagini precedenti restano disponibili. Nessuna nuova generazione in questa applicazione.
- Skill `clean-code-engineer`: riusati allegato, renderer e helper di import esistenti; cambio locale mirato con guardie e verifica di altri post/thumbnail invariati (allegato 175 al posto di 174). Solo punto focale tablet affinato per non tagliare il viso; testi, overlay, layout e resto della home conservati. Seed aggiornato, plugin/demo invariati, tema 1.6.89; nessun reimport, dipendenza o commit automatico.
- Lint PHP di seed/template/funzioni, sintassi dei due JS del tema e `git diff --check` superati. Home, immagine completa/derivato responsive e CSS HTTP 200. Browser a 1440/1024/768/390/320 px: nessun overflow o immagine fallita, un H1 e font caricati; focus 3 px sulla CTA via Tab, console senza errori. Ritaglio tablet controllato dopo il cambio; screenshot salvati, viewport ripristinato e scheda temporanea chiusa.

## v1.6.88 — Prova hero con calice FISAR, 15 ottobre 2026

- Applicata alla home l’immagine illustrativa generata del calice personalizzato, mantenendo identità, testi, CTA e altre sezioni. Originale PNG e provenienza in `assets/photography/`, WebP locale 1942 × 809 px da 99.182 byte nel plugin. Vecchia immagine conservata; anteprime successive con sommelier in smoking/tastevin non applicate.
- Skill `imagegen`: generazione integrata con logo locale di riferimento, originale conservato e derivato WebP tramite editor immagini WordPress. Skill `clean-code-engineer`: renderer immagini nativo con srcset, eager/high priority, alt decorativo e punto focale responsive, senza nuove dipendenze. Aggiornato solo il riferimento asset del seed; nessun reimport, bump demo o commit automatico. Nell’ambiente locale allegato 174 al posto di 85, vecchio allegato e tutti gli altri post/immagini in evidenza verificati invariati.
- Lint dei tre PHP interessati, sintassi dei due JS del tema e `git diff --check` superati; home, WebP e CSS HTTP 200. Verifica visiva desktop/tablet/mobile (1440/1024/768/390/320 px): immagine caricata, un H1 e nessun overflow orizzontale. Viewport ripristinato e scheda di test chiusa.

## v1.6.87 — Diventa socio, 15 ottobre 2026

- Rinominata la pagina ID 9 e aggiornato il pulsante dell’header a Diventa socio, coerente con il sottomenu esistente. URL `/unisciti-a-noi/` e tutte le destinazioni conservati.
- Sostituito il testo demo con contenuti rielaborati dalla pagina Vantaggi FISAR: introduzione, vantaggi in elenco, quote ordinari 70 €/giovani 40 € (meno di 25 anni), modalità di adesione con rimando ai Contatti. Fonte nazionale e locali convenzionati collegati; nessuna quota aggiuntiva, procedura di pagamento o annualità dedotta.
- Skill `clean-code-engineer`: blocchi nativi e stili associativi esistenti, contenuto del seed in file separato con errore esplicito e link interno portabile. Aggiornamento locale via API/hash/revisione, confronto degli altri post invariati; nessun reimport, nuova dipendenza o CSS/JS. Tema `1.6.87` per header, plugin/demo invariati; documentazione aggiornata, nessun commit automatico.
- Lint dei tre PHP interessati, sintassi dei due JS esistenti, round-trip dei blocchi e `git diff --check` superati. Pagina, Contatti, altre pagine associative e CSS HTTP 200. Browser a 1440/1024/768/390/320 px: un H1, tre sezioni, importi e requisito under 25 corretti, font locali caricati, nessun overflow, immagine fallita o errore console. Tab→Invio sul rimando Contatti con focus 3 px e destinazione verificata; menu/sottomenu mobile Diventa socio corretti. Screenshot desktop integrale e di dettaglio controllati, viewport ripristinato e scheda temporanea chiusa.

## Contenuti — Statuto nella pagina della delegazione, 14 ottobre 2026

- Sostituito il testo `Documento da aggiungere` con `Apri lo statuto (PDF) →` verso il link breve `/statuto/`; descrizione, titolo/ancora, menu e resto della pagina invariati. Nessun download diretto, incorporamento o copia del PDF.
- Skill `clean-code-engineer`: paragrafo nativo e frammento del seed allineati, URL derivato da `home_url` nel lettore esistente. API WordPress con guardia hash, controllo della singola sostituzione, confronto degli altri post e revisione precedente recuperabile. Nessun CSS/JS, dipendenza, reimport, incremento di versione o commit automatico.
- Lint PHP del seed, sintassi JS della navigazione, round-trip dei blocchi e `git diff --check` superati; pagina HTTP 200, alias HEAD 302/no-store alla destinazione prevista. Browser a 1440/768/390/320 px: link unico nel riquadro, placeholder assente, font caricati e nessun overflow o errore console. Clic verificato fino al visualizzatore Drive; screenshot controllato, viewport ripristinato e scheda temporanea chiusa.

## Plugin v1.3.30 — Link breve Statuto

- Creato `/statuto` (anche `/statuto/`) verso il documento Google Drive indicato dall’utente. Redirect 302 senza cache, destinazione fissa e parametri del visitatore ignorati; solo GET/HEAD e path esatto su 404, senza intercettare pagine reali.
- Skill `clean-code-engineer`: estesa la mappa generale e riusato un solo handler nativo, conservando il getter dei quattro canali. Nessun tema, menu, contenuto, PDF locale, dipendenza, rewrite/flush o reimport modificato. Plugin `1.3.30`, demo invariata; nessun commit automatico.
- Lint dei due PHP e sintassi JS della navigazione superati, `git diff --check` pulito. GET con/senza slash e parametro `redirect_to`, HEAD: 302 alla destinazione fissa e header no-cache/no-store; POST e sottopercorsi 404. Quattro redirect esistenti invariati. Browser dal link locale arriva al file indicato su Drive: dopo un errore transitorio di caricamento, anteprima della prima pagina e indicatore di 16 pagine visibili senza login. Screenshot salvato, scheda temporanea chiusa. Nessuna analisi giuridica del contenuto.

## v1.6.86 — Introduzione, indice e valori della delegazione

- Conservato `un luogo` nell’introduzione, con finale `in un ambiente informale e accogliente`. Nota sulla nascita nel 2010 come associazione senza scopo di lucro, senza attribuire lo status APS al 2010. Sottotitolo approvato invariato.
- Indice con H2 dello stesso stile delle sezioni; nuova prima voce I nostri valori e relativa sezione subito dopo, con breve paragrafo e rimando alla Carta completa. Rimossa la precedente nota prima dell’indice. Consiglio/incarichi/Squadra/Statuto/Storia e altri post conservati integralmente.
- Skill `clean-code-engineer`: blocchi nativi e frammento introduttivo del seed allineati, regola heading riusata nel CSS senza duplicarla, nessun nuovo JS, dipendenza o logica funzionale nel tema. Aggiornamento ID 7 via API con hash e revisione recuperabile; titolo/estratto/URL/stato invariati, nessun reimport o incremento versione demo. Tema/asset `1.6.86`, documentazione aggiornata, nessun commit automatico.
- Lint PHP del tema/seed, sintassi dei due JS esistenti, round-trip dei quattro frammenti e `git diff --check` superati; pagina/CSS/Carta HTTP 200. Browser a 1440/768/390/320 px: H2 indice e sezioni entrambi 28 px desktop / 24 px agli altri formati, sei link da 44 px con target presenti, un H1, cinque card e 22 componenti squadra conservati; nessun overflow, immagine fallita o errore console, font caricati. Indice porta a Valori con margine superiore 32 px, link alla Carta verificato, focus da tastiera 3 px. Screenshot controllato, viewport ripristinato e scheda temporanea chiusa.

## Contenuti — Sottotitolo della delegazione, 14 ottobre 2026

- Estratto sostituito con la formulazione scelta: `FISAR Castelli di Jesi APS · Passione e cultura del vino dal 2010`, senza punto finale. Pagina ID 7 e seed allineati, nessun reimport o modifica al corpo, all’indice, alle sezioni e allo stile. Aggiornamento tramite API nativa con guardia sui valori precedenti e revisione recuperabile; nessun commit automatico.
- Lint PHP e `git diff --check` superati; pagina HTTP 200. Sottotitolo verificato nel browser a 1440/768/390/320 px senza overflow, font caricati. Anteprima controllata, viewport ripristinato e scheda temporanea chiusa.

## Contenuti — Introduzione della delegazione più essenziale, 14 ottobre 2026

- Rimossa La delegazione con Una Delegazione aperta, Il territorio e Le persone. Subito dopo il link alla storia, aggiunta una sola nota sui principi con link `Carta dei Valori della nostra delegazione`. Introduzione iniziale, indice e tutte le altre sezioni conservati integralmente.
- Skill `clean-code-engineer`: blocchi nativi e stili esistenti riusati; seed allineato e URL interno derivato da `home_url`, senza nuova logica nel tema, CSS/JS, dipendenze, incrementi di versione o reimport. Pagina ID 7 aggiornata con hash, revisione recuperabile e confronto esatto delle sole modifiche richieste; altri post, titolo/estratto/URL/stato invariati. Nessun commit automatico.
- Lint PHP del seed, sintassi JS della navigazione, round-trip dei blocchi e `git diff --check` superati; Delegazione e Carta HTTP 200. Browser a 1440/768/390/320 px: nota subito dopo il rimando alla storia, sezione rimossa, cinque sezioni e target dell’indice corretti, cinque card e 22 componenti squadra conservati, nessun overflow o immagine fallita, font caricati e console senza errori. Tab evidenzia il nuovo link con focus di 3 px; clic verificato fino alla Carta locale. Anteprima salvata e controllata, viewport ripristinato e scheda temporanea chiusa.

## Contenuti — Storia separata dal Consiglio attuale, 14 ottobre 2026

- Spostata La nostra storia in una sezione H2 finale dopo Statuto, mantenendo i due paragrafi integrali. In alto solo nota sul 2010 con link `Scopri la nostra storia →`; voce Storia ultima nell’indice. Nominativi attuali e fondatori restano in sezioni distinte; tutti gli altri contenuti e dati conservati.
- Skill `clean-code-engineer`: frammento storico separato e lettore seed esteso con errore esplicito, guardie sulle ancore invariate. Blocchi e stile esistenti riusati, senza nuovo CSS/JS, dipendenze, reimport o incrementi di versione. Aggiornamento mirato ID 7 con hash, revisione recuperabile e confronti esatti del testo storico, delle altre sezioni e degli altri post; titolo/estratto/slug/stato invariati. Nessun PDF pubblicato o commit automatico.
- Lint PHP del seed, sintassi JS della navigazione, round-trip dei quattro frammenti e corrispondenza del lettore seed superati; pagina HTTP 200 e `git diff --check` pulito. Browser a 1440/768/390/320 px: sei sezioni nell’ordine corretto, storia unica, cinque link nell’indice con target validi e altezza 44 px, cinque card e 22 componenti squadra conservati, font caricati; nessun overflow, immagine fallita o errore console. Link dalla nota arriva alla storia; Tab passa al Consiglio con focus visibile di 3 px. Anteprime controllate, viewport ripristinato e scheda temporanea chiusa.

## Contenuti — La nostra storia, 14 ottobre 2026

- Pubblicato il testo concordato nella sottosezione H3 La nostra storia (`#storia`), prima di Una Delegazione aperta: costituzione come associazione il 21 gennaio 2010, denominazione Associazione Culturale Castelli di Jesi, finalità condivise con FISAR, sette fondatori, prima sede Maiolati Spontini e primo presidente Giovanni Elce Fabbretti. Conservate le due formulazioni approvate dall’utente. Aggiunta solo la voce Storia all’indice interno.
- Aggiornati il frammento `delegazione-introduzione.html` per il seed, la pagina locale ID 7 e la documentazione. Guardie sul precedente hash e verifica esatta che rimuovendo i tre blocchi nuovi e il link si ottenga l’introduzione precedente; Consiglio/incarichi/Squadra/Statuto, altri post, titolo/estratto/URL/stato invariati. Revisione precedente recuperabile, nessun reimport, nuovo codice frontend o incremento versione. PDF storico non pubblicato o presentato come statuto vigente, nessun codice fiscale o firma trascritti. Nessun commit automatico.
- Lint PHP dello script, sintassi JS della navigazione, round-trip dei blocchi e `git diff --check` superati; pagina HTTP 200. Browser a 1440/768/390/320 px: testo concordato presente una sola volta, H3 e ancora unici, cinque link dell’indice con target Storia valido e altezza 44 px, cinque card Consiglio e 22 componenti squadra conservati; nessun overflow, immagine fallita o errore console, font caricati. Link Storia verificato, screenshot salvato e controllato, viewport ripristinato e scheda temporanea chiusa.

## v1.6.85 — Gerarchia e leggibilità della delegazione

- Applicata la proposta approvata alla sola pagina La nostra delegazione: cinque sezioni H2 riconoscibili (La delegazione, Consiglio, Incarichi, Squadra Sommelier, Statuto), indice nativo sotto l’introduzione, cinque riquadri con ruolo/nome/email separati, fondo caldo per incarichi e Statuto. Squadra autonoma su due colonne da 608 px, una su mobile; anche i Direttori adottano l’elenco su colonne. Eventi resta in sequenza verticale subito dopo Didattica. Nessun accordion o contenuto nascosto, nessun PDF inventato.
- Skill `clean-code-engineer`: template standard con classe `.delegation-page`, CSS circoscritto e gruppi/heading/paragrafi/liste WordPress nativi, senza nuovo template o JavaScript. Seed in tre frammenti editoriali, lettura con guardie e conservazione delle ancore per le pagine esistenti; nessun reimport, incremento versione demo, nuovo modello persone o dipendenza. Tema/asset `1.6.85`, altre pagine invariate; documentazione aggiornata, nessun commit automatico.
- Aggiornamento locale ID 7 tramite API native, hash precedente e revisione recuperabile. Controlli DOM prima del salvataggio: tutti i paragrafi originali conservati, medesimi cinque ruoli/nomi/email e medesimi elenchi Direttori/Squadra nello stesso ordine; ancore uniche, Eventi al secondo posto tra gli incarichi. Titolo/estratto/slug/stato e altri post invariati. Cambiati raggruppamenti e livelli dei titoli; solo indice ed etichetta La delegazione aggiunti. Corrette prima del salvataggio le sequenze `--` degli attributi JSON secondo la serializzazione nativa WordPress.
- Lint dei tre PHP e dello script, sintassi dei due JS esistenti, round-trip dei blocchi, corrispondenza pagina/file/lettore seed e `git diff --check` superati. Delegazione, altre pagine associative, Eventi/Corsi e CSS HTTP 200. Browser a 1440/1024/768/608/390/320 px: cinque card senza overflow, cinque sezioni, 5 Direttori e 22 componenti squadra, indice con target presenti e link alti almeno 44 px, font caricati, un H1, nessun ID duplicato, immagine fallita o errore console. Verifiche visive desktop/mobile, regressione FISAR/Unisciti/Carta/Eventi/Corsi e sette icone Carta conservate.
- Indice testato con clic a Consiglio/Squadra e Tab→Invio a Incarichi, focus visibile; arrivo visibile a Consiglio, Incarichi e Statuto, ancore legacy del menu conservate. Screenshot salvati e controllati, viewport ripristinato e scheda temporanea chiusa. Storia e PDF restano da completare; supporto reduced-motion esistente conservato.

## Contenuti — Consiglio e incarichi della delegazione, 14 ottobre 2026

- Popolata la sola sezione `#consiglio` della pagina La nostra delegazione ID 7 con i contenuti della pagina del sito attuale indicata dall’utente: cinque componenti del Consiglio eletto il 26 novembre 2022, incarichi non elettivi, cinque Direttori di Corso e 22 componenti della Squadra Sommelier. Nomi resi Nome Cognome, ordine e grafie della fonte conservati. Responsabile Eventi — Stefano Ottaviani subito dopo Organizzazione Corsi e Didattica — Corrado De Michelis; rimossa l’attribuzione Eventi al Segretario. Nicola Merli resta consigliere e Responsabile Servizi in gruppi distinti. Nessuna durata mandato o storia inventata.
- Cinque recapiti completi della tabella resi link email. `eventi@fisarcastellidijesi` è incompleto nella fonte: omesso senza dedurre il dominio o attribuire un recapito nuovo al Responsabile Eventi. Statuto/PDF e testi introduttivi restano invariati; storia da completare quando saranno disponibili le informazioni.
- Skill `clean-code-engineer`: sezione in 16 blocchi nativi, copia del seed in `content/consiglio-e-incarichi.html`, lettura con errore esplicito propagato. Conservata la guardia del seed sulle ancore esistenti, nessun reimport o incremento della versione demo. Aggiornamento locale tramite API WordPress con hash del precedente testo, revisione recuperabile e verifica del resto della pagina e degli altri post invariati. Titolo, estratto, slug, stato, menu e tema conservati; documentazione aggiornata, nessun commit automatico.
- Lint PHP del seed e dello script, sintassi JS della navigazione, round-trip dei blocchi, conteggi 5/5/22, corrispondenza file/lettore del seed e `git diff --check` superati. Pagina locale HTTP 200. Browser a 1440/768/390/320 px: ruoli/nominativi e ordine richiesto corretti, cinque link email, un H1, ancore uniche, nessun overflow, immagine fallita o errore console; focus email visibile da tastiera. Screenshot degli incarichi salvato e controllato; viewport ripristinato e schede temporanee chiuse. Fonte letta con il browser perché non accessibile allo strumento di ricerca web.

## v1.6.84 — Chi siamo allineata alle altre sezioni

- Affinamento successivo del sottotitolo La FISAR: `Federazione Italiana Sommelier Albergatori Ristoratori (APS)`, senza punto finale. Solo estratto nativo ID 92 e seed allineati; revisione recuperabile e corpo verificato invariato. Nessun incremento di versione o reimport.

- La FISAR, La nostra delegazione (incluse Consiglio e incarichi / Statuto) e Diventa socio adottano fascia bordeaux compatta, H1 Poppins 32–44 px con accento oro, introduzione Poppins, scala del corpo e heading di Eventi/Corsi/Blog. Colonna centrale massimo 48rem, spaziature più contenute e separatori sottili tra i capitoli; rimossa soltanto la dicitura ridondante sopra l’H1. Carta dei Valori conserva hero fotografica, nota, sette icone e payoff già approvati.
- Skill `clean-code-engineer`: template standard riusato con la sola classe `.association-page` sulle tre destinazioni, estensione dei selettori condivisi senza duplicare template, dipendenze, font o logica di dominio. Nessun nuovo campo, script, reimport o scrittura editoriale; confronto prima/dopo conferma identici i contenuti e gli estratti di tutte le pagine. Menu, link e ancore nativi invariati. Tema/asset `1.6.84`; README, tipografia, specifica, architettura e assunzioni aggiornati. Nessun commit automatico.
- Lint PHP dei due file e sintassi dei due JS esistenti superati; `git diff --check` pulito. Le quattro pagine associative, Eventi/Corsi, CSS aggiornato e font locali HTTP 200. Browser sulle tre pagine a 1440/768/390/320 px: un H1, font/scale previsti, nessun overflow, ID duplicato, immagine fallita o errore console. Regressione Carta/Eventi/Corsi/Privacy a 1440/320 px: nessun overflow; Carta con immagine, nota e sette icone conservate. Le tre immagini Eventi inizialmente non caricate erano lazy fuori viewport: asset HTTP 200, non errori di caricamento.
- Verificati menu Chi siamo, arrivo visibile alle ancore Consiglio e Statuto e focus da tastiera sui link della FISAR. Screenshot desktop e mobile salvati e controllati, viewport ripristinato e scheda temporanea chiusa. Supporto reduced-motion e stili di focus esistenti conservati; contenuti demo ancora da completare nelle pagine locali, senza inventare nomi o statuto.

## Contenuti — La FISAR, 14 ottobre 2026

- Pubblicato il testo concordato sulla sola pagina La FISAR ID 92: identità APS, anno di nascita, oltre 70 delegazioni e oltre 15.000 soci; formazione in tre livelli e approfondimenti successivi; collaborazione con Slow Food/Slow Wine, FISAR in Rosa e rivista il Sommelier. Il paragrafo sui principi locali identifica esplicitamente la nostra Delegazione, evita la ripetizione `riferimento` e collega la Carta dei Valori nella frase, senza pulsante. Chiusura con rimandi distinti al sito nazionale e alla nostra delegazione.
- Testo in 16 blocchi WordPress nativi, modificabile nell’editor. Copia versionata in `plugin/fisar-cdj-core/content/la-fisar.html`, letta dal seed con URL interni derivati da `home_url`; nessun reimport o incremento della versione demo. Skill `clean-code-engineer`: riuso del flusso nativo, nessuna duplicazione nel tema o nuova dipendenza. Aggiornamento locale tramite API con guardia hash, revisione precedente recuperabile, verifica di titolo/estratto/slug/stato invariati e confronto degli altri post prima/dopo. Specifica `about.md` aggiornata; nessun commit automatico.
- Lint PHP del seed e dello script di aggiornamento, round-trip dei blocchi e `git diff --check` superati. La FISAR e le due destinazioni interne HTTP 200; browser a 1440/768/390/320 px senza overflow, immagini fallite o errori console, un H1 e tre H2, font caricati e quattro link editoriali corretti. Navigazione Tab e focus visibile verificati tra Carta e sito nazionale; screenshot salvati, viewport ripristinato e scheda temporanea chiusa. Stile esistente conservato: intervento sui contenuti, non restyling della pagina.

## Contenuti — Navigazione e cookie più sintetica, 12 ottobre 2026

- Su richiesta, mantenuti solo i primi due paragrafi della sezione, fino a `Questi dati non sono cookie e non vengono utilizzati dal sito per profilare i visitatori.`. Rimossi i tre successivi senza testo sostitutivo. Titolo/ancora, titolare, introduzione e intera informativa newsletter invariati; nessuna modifica a codice, form, menu o redirect.
- File documentale e pagina ID 10 allineati tramite API native, guardia hash e verifica che il cambiamento corrisponda esclusivamente alla rimozione richiesta; cinque revisioni, precedente testo recuperabile. Tema/plugin invariati e nessun commit automatico.
- Sintassi PHP dello script di aggiornamento e `git diff --check` superati; privacy HTTP 200 e Cookie legacy 301 alla stessa ancora. Browser: esattamente due paragrafi prima della sezione newsletter, frase finale corretta e nessun overflow a 1440/768/390/320 px o errore console. Screenshot salvato, viewport ripristinato e scheda temporanea chiusa.

## Plugin v1.3.29 — Privacy unica per navigazione, cookie e newsletter

- Estesa l’introduzione della privacy e inserita `Navigazione e cookie` dopo titolare/recapito: assenza di analytics/pixel nella configurazione attuale, dati tecnici di navigazione distinti dai cookie, cookie WordPress per login/preferenze/lingua e servizi esterni. Newsletter conservata integralmente, con controllo del suffisso originale; nessuna modifica a consenso, form o Mailchimp. Testo in blocchi nativi, opzione privacy/titolo/slug invariati e revisione precedente conservata.
- Cookie Policy ID 11 in bozza, contenuto intatto; voce ID 35 scollegata dal menu footer 3 senza eliminare il post, quindi recuperabile. Vecchio URL con 301 alla pagina privacy nativa e all’ancora `#navigazione-e-cookie`, soltanto GET/HEAD e path esatto. Seed senza pagina/link Cookie separati, privacy demo con sezione generica coerente, nessun reimport. Tema `1.6.83` invariato, plugin `1.3.29`, documentazione aggiornata; nessun commit automatico.
- Skill `clean-code-engineer`: testo editoriale nella pagina, redirect nel plugin e rimozioni recuperabili tramite API native con guardie hash. Consultati FAQ del Garante e documentazione cookie WordPress; verificate configurazione locale e parti del core. Nessun banner, analytics, dipendenza o nuovo trattamento introdotto. Restano da verificare hosting/log/conservazione e configurazione effettiva Mailchimp prima del lancio: non certificata la futura conformità del sito in produzione.
- Lint dei tre PHP, sintassi JS e `git diff --check` superati. Hash privacy uguale al file documentale, quattro revisioni, testo Cookie invariato, post-menu conservato e sei voci residue. HTTP Cookie GET con slash/HEAD senza slash: 301 alla nuova sezione; POST e sottopercorso 404. Privacy 200, Newsletter 302 e Chi siamo 301 invariati.
- Browser dalla vecchia Cookie Policy alla privacy con ancora corretta, un H1, font locali e nessun link Cookie nel footer; 1440/768/390/320 px senza overflow. Su Seguici un form, consenso obbligatorio e link privacy conservati, console senza errori. Screenshot della sezione salvato, viewport ripristinato e scheda temporanea chiusa; nessun invio di prova o accesso ai servizi esterni.

## v1.6.83 / plugin v1.3.28 — Redirect Facebook, Instagram e Newsletter

- Creati `/facebook/`, `/instagram/` e `/newsletter/`, anche senza slash finale, con 302/no-cache/no-store. Mappa di destinazioni fisse e gestore comune nel plugin, estendendo WhatsApp senza duplicare handler. Newsletter verso la pagina Seguici pubblicata e il modulo esistente; in sua assenza non redirige. Confermate guardie GET/HEAD, path esatto, 404 e nessun inoltro di query o destinazione da input.
- Skill `software-architect` e `clean-code-engineer`: riuso della soluzione nativa, nessuna pagina vuota, rewrite/flush, dipendenza o configurazione server. Seed social con alias; nel database locale aggiornati soltanto gli URL delle voci ID 37/38 dopo controllo esatto, preservando impostazioni/ordine. Newsletter globale in header/footer con alias, ancore locali delle card conservate. Nessuna modifica a CSS, form, consenso, privacy o Mailchimp; documentazione aggiornata e nessun commit automatico.
- Lint dei sei PHP, sintassi JS, HTTP home/CSS 200 e `git diff --check` superati. Tutti e quattro gli alias testati GET con/senza slash e HEAD con `redirect_to`: 302 alla destinazione prevista e no-store; POST e sottopercorsi restano 404. Legacy Chi siamo 301 conservato. Nessun redirect seguito verso i social esterni.
- Browser home/Seguici: URL brevi nei componenti condivisi, Newsletter globale arriva al modulo con un solo form; ancore locali, consenso richiesto e privacy conservati. Seguici a 1440/768/390/320 px senza overflow o immagini fallite; Tab Email→Nome con focus visibile e console senza errori. Screenshot dell’arrivo al modulo salvato, viewport ripristinato e scheda temporanea chiusa; nessun invio di prova.

## Plugin v1.3.27 — Link breve pubblico WhatsApp

- Realizzato `/whatsapp/`, anche senza slash finale: redirect esterno 302 all’invito configurato, con header no-cache/no-store. La destinazione è unica nel getter del plugin; query ignorate, percorso esatto e soli GET/HEAD su 404, prima della canonicalizzazione. Nessuna pagina vuota, regola di rewrite, flush, nuovo plugin o dipendenza.
- Skill `software-architect` e `clean-code-engineer`: logica funzionale nel plugin, menu sociale condiviso come fonte dei link pubblici, destinazione fissa per evitare open redirect. Seed aggiornato; nell’ambiente locale modificato solo l’URL della voce ID 36 tramite API native e controllo dei valori precedenti. Facebook/Instagram/Newsletter invariati: gli altri alias sono proposti, non ancora autorizzati. Tema `1.6.82` invariato; documentazione aggiornata, nessun commit automatico.
- Lint dei tre PHP, sintassi JS e `git diff --check` superati. HTTP GET con/senza slash e HEAD con parametro `redirect_to`: 302 all’invito fisso e `Cache-Control` no-cache/no-store verificati senza seguire il redirect. POST e percorso `/whatsapp/altro/` restano 404; Seguici 200 e legacy Chi siamo 301 conservati. Browser: tre link brevi su home e Seguici, profili e Newsletter invariati; Seguici a 1440/768/390/320 px senza overflow, console senza errori. Screenshot salvato, viewport ripristinato e scheda temporanea chiusa; nessun accesso al gruppo esterno.

## v1.6.82 / plugin v1.3.26 — Link social della Delegazione

- Inseriti gli URL Facebook, Instagram e WhatsApp forniti dall’utente nel menu social comune: si aggiornano top bar, footer e card di home/Seguici. WhatsApp punta a un gruppo: voce `Gruppo WhatsApp`, CTA `Unisciti al gruppo WhatsApp` e label mobile `WhatsApp`. Newsletter e recapiti personali per Eventi/Corsi invariati.
- Skill `clean-code-engineer`: mantenuta la fonte comune nel menu WordPress, seed allineato con alias legacy per evitare duplicati. Nell’ambiente locale aggiornate tramite API native solo le voci ID 36/37/38 del menu 4, dopo controllo esatto di titoli/URL precedenti; conservati ID, ordine, parent e impostazioni. Nessun reimport demo o accesso/iscrizione ai servizi esterni. Tema/asset `1.6.82`, plugin `1.3.26`; documentazione aggiornata, nessun commit automatico.
- Lint dei tre PHP, sintassi JS, HTTP home/Seguici/CSS 200 e `git diff --check` superati. Verificati tutti e tre gli URL nei tre contesti di entrambe le pagine, modulo Mailchimp invariato. Browser Seguici a 1440/768/390/320 px: etichette corrette, nessun overflow, immagine fallita o errore console. Screenshot desktop salvato; viewport ripristinato e scheda temporanea chiusa. Verificati i collegamenti configurati, non l’accesso agli account o la validità dell’invito esterno.

## v1.6.81 — Top bar dedicata ai canali per seguirci

- Tolto il payoff dalla barra, conservandolo nella hero e nella Carta dei Valori. Aggiunto `Newsletter` con busta e link a `/seguici/#newsletter-page`, accanto ai tre social del menu nativo. Newsletter presente anche senza menu social; nessuna voce aggiunta ai menu salvati e footer invariato.
- Quattro canali visibili su mobile, con icona sopra il nome e barra da 52 px; da 608 px icone affiancate, gruppo a destra e altezza da 44 px. `Canale` abbreviato solo nella label WhatsApp della top bar mobile. Focus oro da 3 px; SVG decorativi, nomi testuali sempre visibili, wrapping consentito e nessun nuovo JavaScript.
- Skill `clean-code-engineer`: riusati menu, formatter e icone locali; variazione della label circoscritta al contesto top bar e rimossi gli stili del payoff. Tema/asset `1.6.81`, specifiche navigazione/tipografia aggiornate; nessuna dipendenza, modifica al plugin, ai contenuti o a Mailchimp. Nessun commit automatico.
- Lint dei due PHP, sintassi JS, HTTP home/Seguici/CSS 200 e `git diff --check` superati. Browser a 1440/1024/768/608/607/390/320 px: quattro link visibili, etichette corrette, payoff nella hero conservato e nessun overflow o immagine fallita. Click Newsletter porta al pannello con un solo modulo, senza invii; Tab da Facebook a Newsletter con focus oro verificato. Console senza errori, screenshot desktop/mobile salvati; viewport ripristinato e schede temporanee chiuse.

## v1.6.80 — Titoli dei pannelli Contatti con icone coerenti

- Busta affiancata a `Scrivici`, persona a `Il Delegato` ed edificio istituzionale a `Dati dell’associazione`. Titoli con identico allineamento, icone bordeaux da 24 px e distanza `.65rem`; testo libero di andare a capo su mobile. ID e nomi accessibili conservati; SVG decorativi e non focalizzabili.
- Skill `clean-code-engineer`: riusato il renderer SVG locale, aggiunte soltanto due icone lineari e sostituito lo stile della busta separata con una regola comune ai tre titoli. Nessuna dipendenza, modifica ai dati, al plugin o al footer. Tema/asset `1.6.80`, specifica aggiornata; nessun commit automatico.
- Lint dei due PHP, sintassi JS, HTTP Contatti/CSS 200 e `git diff --check` superati. Browser a 1440/768/390/320 px: icone da 24 px sempre a sinistra del testo, allineamento verticale desktop, heading accessibili invariati e nessun overflow o errore console. Screenshot desktop salvato; viewport ripristinato e scheda temporanea chiusa.

## v1.6.79 — Blog e Contatti più essenziali

- Rimossa dalla sola hero dell’elenco Blog la dicitura `Vita di Delegazione`. Titolo, descrizione, articoli, home e dettagli invariati.
- Contatti: eliminati il riquadro `La sede` e la sottosezione `Dati per la fatturazione`; indirizzo conservato nei dati dell’associazione e SDI come riga distinta subito dopo RUNTS. Due riquadri di contatto affiancati da 608 px, uno per riga su mobile. Introduzione editoriale invariata: il ritorno a capo isolato dipendeva dal contenitore limitato a 768 px; ora usa la larghezza del corpo e `text-wrap: pretty` sui formati ridotti.
- Skill `clean-code-engineer`: semplificati i template nativi e rimossi i selettori ormai inutilizzati, mantenendo i dati nel getter comune del plugin. Nessuna modifica al database, ai recapiti, al footer o al plugin; documentazione aggiornata e tema/asset `1.6.79`. Nessun commit automatico.
- Lint dei tre PHP, sintassi JS, HTTP Contatti/Blog/CSS 200 e `git diff --check` superati. Browser sulle due pagine a 1440/768/390/320 px: eyebrow rimosso, due card, SDI dopo RUNTS, introduzione su una riga desktop, layout responsive e nessun overflow, immagine fallita o errore console. Screenshot Contatti salvato; viewport ripristinato e scheda temporanea chiusa.

## v1.6.78 — Fondo uniforme su Seguici

- Esteso alla sezione dei canali lo stesso fondo chiaro caldo della newsletter (`--color-tint`), eliminando lo stacco bianco tra le due sezioni. Card bianche, pannello bordeaux, hero, footer e distanze conservati. Skill `clean-code-engineer`: riusato il token esistente nel selettore circoscritto a `.follow-page`, senza alterare home, componenti, plugin o dati. Nessun commit automatico.
- Lint PHP, sintassi JS, HTTP pagina/CSS 200 e `git diff --check` superati. Browser a 1440/768/390/320 px: fondo identico nelle due sezioni e nessun overflow; card bianche e modulo unico conservati su desktop, console senza errori. Screenshot desktop salvato, viewport ripristinato e scheda temporanea chiusa.

## v1.6.77 — Canali e newsletter più vicini su Seguici

- Ridotto il solo raccordo tra le quattro card e il pannello newsletter: distanza da 128 a 64 px su desktop e da 80 a 48 px su tablet/mobile. Selettori circoscritti a `.follow-page`; home, contenuti e modulo Mailchimp invariati. Tema/asset aggiornati, nessun commit automatico.
- Lint PHP, sintassi JS, HTTP pagina/CSS 200 e `git diff --check` superati. Browser a 1440/768/390/320 px: distanze misurate come previsto, un solo modulo, nessun overflow o errore console. Screenshot desktop salvato; viewport ripristinato e scheda temporanea chiusa.

## v1.6.76 / plugin v1.3.25 — Seguici allineata alle altre pagine

- Hero bordeaux compatta, H1 Poppins con accento oro e senza eyebrow associativa. Rimossi il titolo `Segui la vita della Delegazione`, il relativo eyebrow e il paragrafo richiesto: dopo la hero si vedono direttamente i canali. Titolo ed estratto nativi conservati, card H2 da 20–24 px, titolo newsletter 24–32 px e spaziature più contenute, solo in `.follow-page`.
- Skill `clean-code-engineer`: riusati stili hero e componenti comuni, nome accessibile della sezione tramite `aria-label`, gerarchia corretta senza heading vuoti. Solo il paragrafo della pagina ID 86 rimosso tramite API WordPress con confronto esatto e revisione recuperabile; future introduzioni restano native/editoriali. Seed del plugin allineato senza reimport; form, consenso, privacy, ancore, URL social/menu e home invariati. Nessuna modifica all’account Mailchimp o nuova dipendenza; nessun commit automatico.
- Lint dei quattro PHP, sintassi JS, HTTP home/Seguici/Contatti/Eventi/Corsi/CSS 200 e `git diff --check` superati. Verificati metadati/revisioni e hash privacy invariato. Browser Seguici a 1440/768/390/320 px: testi rimossi assenti, un H1, quattro card H2 e un form, ID unici, font caricati e nessun overflow, immagine fallita o errore console. Ancora newsletter e Tab al campo Email con focus visibile, senza invii; home conserva card H3/28 px e modulo/ancora originali. Screenshot desktop/mobile, viewport ripristinato e scheda temporanea chiusa.

## v1.6.75 — Footer più essenziale

- Rimossi dal solo footer il copyright, il payoff e il testo descrittivo sotto Seguici, come richiesto. Eliminati la riga finale ormai vuota e i relativi stili; payoff della top bar/home conservato. Dati associativi, recapiti, menu, social e CTA newsletter invariati; nessuna modifica al plugin o al database.
- Tema/asset `1.6.75`, specifica Contatti aggiornata; nessun commit automatico.
- Lint PHP/JS, HTTP home/Contatti/CSS 200 e `git diff --check` superati. Browser a 1440/768/390/320 px: i tre testi e la riga finale assenti dal solo footer, payoff della top bar conservato, recapiti/dati fiscali/CTA invariati, nessun overflow o errore console. Screenshot desktop, viewport ripristinato e scheda temporanea chiusa.

## v1.6.74 — Unico footer bianco

- Su richiesta esplicita, eliminata la fascia bordeaux separata. C.F. e P.IVA spostati sotto la sede nel blocco associativo; copyright e payoff nell’ultima riga della stessa griglia bianca. Nessuna informazione rimossa, nessuna modifica alla pagina Contatti o ai dati del plugin.
- Skill `clean-code-engineer`: semplificato il markup, rimossi wrapper e selettori `footer-bottom`, riusati griglia e breakpoint esistenti. Tema/asset `1.6.74`, documentazione aggiornata; nessun commit automatico.
- Lint PHP, HTTP home/Contatti 200 e `git diff --check` superati. Browser a 1440/768/390/320 px: un solo footer, fascia precedente assente, fondo bianco, dati/email conservati, nessun overflow o errore console. Verifica visiva desktop e screenshot; viewport ripristinato e schede temporanee chiuse.

## v1.6.73 / plugin v1.3.24 — Footer e Contatti con i dati della Delegazione

- Inseriti denominazione breve, sede, due email alternative e dati fiscali nel footer; rimossi telefono e avviso demo. Numero del Delegato, denominazione completa, SDI e RUNTS nella pagina Contatti, con iscrizione RUNTS separata dai dati per la fatturazione. Conservati menu, social e CTA newsletter.
- Contatti con fascia bordeaux compatta e titoli Poppins, riquadro Scrivici, Delegato/sede e dati associativi. Layout mobile a una colonna, due su tablet; footer a quattro colonne solo da 1120 px per preservare leggibilità dei recapiti. Telefono con link `tel:`, senza attribuirgli automaticamente WhatsApp.
- Skill `clean-code-engineer`: dati pubblici centralizzati nel getter del plugin e presentazione nel tema; componente email condiviso senza copie da mantenere. Nessun nuovo CPT, schermata backend, script, asset remoto o dipendenza. Introdotto `page-contatti.php`; solo il corpo demo della pagina ID 8 aggiornato con confronto esatto e revisione recuperabile, introduzione ancora editoriale. Seed allineato senza reimport; altri contenuti, menu, privacy e Mailchimp invariati. Nessun commit automatico.
- Lint dei sette PHP e sintassi JS, HTTP home/Contatti/Eventi/Corsi/Blog/Privacy/Seguici/CSS 200 e `git diff --check` superati. Verificati blocco nativo dell’introduzione, revisioni e hash privacy invariato. Browser Contatti a 1440/1024/768/390/320 px e footer home a 1440/320 px: nessun overflow, ID duplicato, immagine fallita o errore console; un solo H1, email/telefono corretti e focus visibile nei due contesti. Screenshot desktop/mobile/footer, viewport ripristinato e scheda temporanea chiusa.

## v1.6.72 — Privacy coerente con Eventi, Corsi e Blog

- Fascia del titolo bordeaux compatta con accento oro, senza fotografia o eyebrow ridondante; H1 e sezioni Poppins alla scala dei dettagli Eventi/Corsi/Blog. Corpo 16–17 px, introduzione non più serif, colonna centrale massimo 48rem e spaziature ridotte.
- Skill `clean-code-engineer`: riutilizzati i selettori comuni; classe `privacy-page` assegnata con la condizione nativa `is_privacy_policy()` e poche regole specifiche, senza duplicare template o logica nel plugin. Tema/asset `1.6.72`; contenuto della policy, titolo/URL, database, menu, Mailchimp e altre pagine invariati. Nessun nuovo asset, dipendenza o commit automatico.
- Lint PHP e sintassi JS, HTTP privacy/Eventi/Corsi/Blog/Carta dei Valori/Cookie Policy/CSS 200 e `git diff --check` superati. Hash del contenuto privacy identico prima/dopo. Browser a 1440/768/390/320 px: un H1 e cinque H2, font caricati, nessun overflow o immagine fallita; link con focus visibile e console senza errori. Screenshot desktop/mobile e controllo di regressione dei template esistenti; viewport ripristinato e scheda temporanea chiusa.

## 10 ottobre 2026 — Pubblicata l’informativa newsletter locale

- Su autorizzazione esplicita, sostituito il corpo dimostrativo della pagina WordPress `Privacy Policy` ID 10 con il testo newsletter proposto e i dati associativi forniti: denominazione completa/abbreviata, sede, C.F., P.IVA ed email. Blocco Paragrafo/Titolo nativo, cinque sezioni; nessuna certificazione legale o dichiarazione non verificata di assenza del tracking.
- Aggiornamento mirato tramite API WordPress, con confronto esatto del testo precedente e revisione recuperabile. Titolo, slug `/privacy-policy/`, stato pubblicato, opzione privacy, menu e link dei due moduli invariati; nessun reimport demo, cambio di versione, modifica al tema/plugin o all’account Mailchimp. Testo iniziale versionato in `docs/03-pages/privacy-newsletter.html`, specifica/controlli ancora necessari in `privacy.md`. Nessun commit automatico.
- Rilettura identica al file, revisione precedente disponibile, parsing dei blocchi nativi e HTTP privacy/home/Seguici 200. Browser a 1440/768/390/320 px: unico H1, cinque H2, dati reali e mailto corretti, testo dimostrativo assente e nessun overflow; font caricati e console senza errori. Verificata navigazione alla pagina dai link privacy di entrambi i moduli, senza iscrizioni o invii. Review visiva/screenshot desktop, viewport ripristinato e scheda temporanea chiusa; `git diff --check` superato.

## v1.6.71 — Come seguirci: contenuti comuni condivisi

- Mantenuta la pagina autonoma `/seguici/` nel menu/footer e la sezione completa in home. Rimosso soltanto il rimando `Scopri tutti i canali` della home, ridondante con le quattro card già presenti. URL, ancore newsletter, posizione delle sezioni e introduzioni specifiche conservati.
- Skill `clean-code-engineer`: nuovo componente `newsletter-signup.php` per titolo/testo del pannello e chiamata al modulo esistente, con contesto per gli ID e H3/H2 secondo la pagina. Riutilizzate card, menu social e configurazione Mailchimp già condivisi; nessun CSS/JS nuovo, campo editoriale, dipendenza, modifica al plugin o salvataggio nel database. Tema/asset `1.6.71`, documentazione aggiornata; nessun commit automatico.
- Lint dei quattro PHP interessati, sintassi JS invariati, sette rendering WordPress in memoria (home/pagina, fallback heading e configurazione assente), HTTP home/Seguici/CSS 200 e `git diff --check` superati. Verificati testo comune identico, ID unici, label/input, destinazione Mailchimp e campi/consenso obbligatori. Nessun invio del modulo.
- Browser su home e Seguici a 1440/768/390/320 px: quattro card, un solo form per pagina, heading corretti, ancore risolte, nessun overflow pagina, immagine fallita o errore console. Tastiera: dal link newsletter al campo Email con focus visibile in entrambi i contesti; review visiva e screenshot desktop/mobile. Viewport ripristinato e scheda temporanea chiusa.

## v1.6.70 — Immagini principali Blog quadrate

- Formato 1:1 nelle card di home/elenco e nel riquadro contenuto del dettaglio, consigliato 1080 × 1080 px per semplificare il riuso editoriale. Card con cover conservato; dettaglio con contain per non tagliare le foto precedenti non quadrate. Gallerie e immagini nel corpo libere nelle proporzioni, file originali e dati invariati.
- Skill `clean-code-engineer`: sole regole CSS dei media Blog, separando il selettore dalla regola Corsi invece di modificare quest’ultima. Nessun nuovo campo, script, dipendenza o rigenerazione degli allegati. Numero/griglie delle card, contenuti, CTA e nomi accessibili conservati; tema/asset `1.6.70`, plugin invariato e nessun commit automatico.
- Verificati lint PHP del bootstrap tema, sintassi JS invariati, HTTP home/elenco/dettaglio/CSS, regressioni native del dettaglio e `git diff --check`. Browser sui tre contesti a 1440/768/390/320 px: riquadri 1:1, immagini principali caricate, dettaglio entro 352 px e nessun overflow pagina. Sette trigger lightbox interni conservati, console senza errori; review visiva e screenshot elenco. Scheda temporanea chiusa e viewport ripristinato.

## v1.6.69 — Immagine in evidenza contenuta nel dettaglio Blog

- Foto spostata nella hero chiara, a destra del testo da 832 px, sotto l’introduzione sui formati piccoli. Riquadro bianco fino a 352 px, bordo/ombra leggeri e foto intera con altezza limitata, senza ritaglio quadrato. Rimossa la grande immagine separata sotto il titolo. Senza foto conservata la hero testuale entro 768 px, senza colonna vuota; corpo e navigazione invariati.
- Skill `clean-code-engineer`: riusati griglia dei dettagli e renderer immagini esistenti, stili circoscritti al Blog. Nessun nuovo script, campo o dipendenza; plugin, dati editoriali, gallerie/lightbox, home/elenco e altri dettagli invariati. Tema/asset `1.6.69`, specifiche aggiornate; nessun commit automatico.
- Superati lint dei due PHP, sintassi JS invariati, HTTP dettaglio/elenco/home/CSS e `git diff --check`. Sei casi nativi in memoria: foto verticale/orizzontale, assenza di immagine, assenza di abstract, titolo lungo, entrambe le assenze; immagine una sola volta nella hero, alt informativo/eager, unico H1, nessun abstract/colonna vuota e galleria nativa conservata. Nessun salvataggio editoriale.
- Browser con foto verticale a 1440/1024/768/390/320 px e orizzontale a 1440/390 px: posizione prevista, foto caricata/contain, larghezza fino a 352 px e nessun overflow pagina. Colonna del corpo invariata, apertura/chiusura della lightbox interna funzionante, console senza errori. Review visiva desktop/mobile e screenshot desktop; scheda chiusa e viewport ripristinato.

## v1.6.68 / plugin v1.3.23 — News diventa Blog

- Approvato il nome pubblico `Blog`: menu principale/footer e pagina articoli nativa rinominati, hero elenco con il testo `Approfondimenti, racconti e novità dalla nostra Delegazione.` e sezione `Ultimi articoli`. Home `Dal nostro blog`, link `Tutti gli articoli`, stato vuoto riferito agli articoli. Dettaglio e paginazione aggiornati anche nei nomi accessibili; CTA `Leggi l’articolo` e titolo completo conservati.
- Skill `clean-code-engineer`: mantenuti Articoli, query e componenti nativi, classi/identificatori interni `news`, stile, lightbox e permalink. Nessun nuovo CPT, categoria, dipendenza o redirect. URL `/news/` conservato. Seed con titolo/menu Blog e alias legacy News, senza duplicare le voci. Tema/asset `1.6.68`, plugin `1.3.23`; specifiche aggiornate, nessun commit automatico.
- Nell’ambiente locale aggiornati soltanto i titoli della pagina articoli ID 5 e delle voci menu ID 26/32, tramite API native, senza reimport demo. Verificati URL, contenuto/estratto della pagina, posizione/parent dei menu e assenza di duplicati. Hash dei titoli, slug, contenuti, estratti e stati di Articoli, media, Eventi e Corsi invariato prima/dopo la rinomina.
- Superati lint dei sei PHP modificati, sintassi JS della galleria invariata, HTTP home/Blog/articolo/CSS 200 e `git diff --check`. Test nativi in memoria: paginazione di due pagine con etichette nuove e stato archivio vuoto senza paginazione; nessun articolo salvato per i test.
- Browser reale: elenco a 1440/768/390 px, home/dettaglio a 1440/390 px, etichette Blog e ritorno corretto, nessuna News residua nei testi visibili né overflow pagina. Immagini delle card caricate e console senza errori; review visiva e screenshot elenco. Scheda di test chiusa e viewport ripristinato.

## Plugin v1.3.22 — Ingrandimento predefinito delle nuove gallerie News

- Il normale inserimento di `Galleria` nell’editor degli articoli usa una variante nativa con `linkTo: lightbox`. Ingrandimento e navigazione gestiti da WordPress; disattivazione disponibile da `Collegamento → Nessuno`. Ricaricare gli editor già aperti. Blocchi salvati/copiati e immagini singole non riscritti; editor Eventi/Corsi/pagine esclusi.
- Skill `clean-code-engineer`: default editoriale nel plugin, riusando il blocco e le dipendenze native senza store subscriber, filtri di salvataggio/rendering, nuove librerie o script frontend. Plugin/asset `1.3.22`, tema `1.6.67` invariato; specifiche aggiornate, nessun commit automatico.
- Superati lint PHP, test JS della variante e dell’helper immagini del core (default, disattivazione e collegamenti alternativi), controlli nativi sul caricamento nei soli articoli e sul default globale delle immagini singole. HTTP home/News/articolo/asset 200 e `git diff --check`.
- Inserimento reale dall’editor verificato su una bozza temporanea: attributo lightbox salvato, galleria preesistente e immagine singola lasciate disattivate. Il browser di test non mostra il canvas dell’editor: selezione delle foto non verificata end-to-end dalla Libreria media; per il test frontend popolata soltanto la bozza QA con attributi verificati dell’helper nativo.
- Lightbox verificata su desktop e mobile: apertura, foto successiva, chiusura e ritorno al pulsante; nessun overflow a 1280/768/390/320 px, immagini della lightbox caricate e console senza errori. Screenshot desktop. Bozza QA ID 155 spostata nel cestino, recuperabile; nessuna modifica effettuata ai contenuti reali. Confronto globale del database non conclusivo durante la sessione dell’editor attivo (lock variabili).

## v1.6.67 — News coerenti con Eventi e Corsi

- Elenco News con fascia bordeaux compatta, titolo e sezioni Poppins alla scala degli altri archivi. Card con bordo/ombra leggeri, titolo prima della data, separatore oro tenue, calendario decorativo e CTA dal nome accessibile completo. Fotografie orizzontali 16:10, non locandine quadrate; media assente e abstract vuoto omessi.
- In home conservate quattro ultime pubblicazioni e ordine: fotografia sopra il testo anche su desktop, eliminando le colonne testuali strette delle card orizzontali. Titoli completi, estratto visivo a due righe, CTA allineate; stato senza notizie esplicito e link archivio conservato.
- Dettaglio con intestazione chiara senza fotografia di sfondo, H1 Poppins e accento oro, metadata e abstract, immagine separata a proporzioni naturali. Colonna di lettura/navigazione entro 48 rem; corpo e heading alla scala Eventi/Corsi. Navigazione e paginazione WordPress con una sola landmark ciascuna, senza `nav` annidati.
- Skill `clean-code-engineer`: riusate le regole condivise e le icone locali, senza nuovi CPT/campi, dipendenze, modifiche al plugin o agli stili dell’editor News. Articoli, immagini, URL e query preservati; tema/asset `1.6.67`, specifiche aggiornate e nessun commit automatico.
- Verificati lint dei cinque PHP modificati, sintassi dei JS invariati, HTTP home/elenco/dettaglio/CSS e quattro pesi Poppins, `git diff --check`. Test nativi in memoria: card e nomi accessibili, dettaglio ordinario/senza media/titolo lungo, paginazione reale su due pagine, archivio/home vuoti e quattro notizie in home; contenuti/meta degli articoli invariati.
- Browser reale sui tre contesti a 1440/1024/768/390/320 px: griglie previste, nessun overflow pagina/card o immagine fallita. Titolo lungo e stati vuoti controllati anche a 1440/320 px, H2–H6 verificati, focus visibile su CTA e navigazione, console senza errori. Review visiva desktop/mobile e screenshot; anteprima temporanea rimossa, nessun salvataggio editoriale.

## v1.6.66 — Nota sulla storia dei Corsi nell’archivio

- Affinamento del testo approvato: “Questo archivio raccoglie solo le edizioni…” in un paragrafo distinto dalla frase sui 15 anni, riusando la spaziatura del box. Verificati lint del template, test nativi vuoto/popolato aggiornati e browser desktop/mobile senza overflow; screenshot aggiornato. Versione e stili invariati, modifica precedente non committata preservata.
- Sotto `Corsi conclusi`, box sempre visibile `Nota sull’archivio`, con il testo approvato sui 15 anni di corsi “in città come Jesi, Ancona, Senigallia e Falconara” e sull’archivio non completo del nuovo sito. Presente sia con elenco vuoto sia popolato, prima delle card o dello stato vuoto. Nessun corso storico creato, query o dato modificato.
- Riquadro bianco sulla sezione tenue, bordo oro e lato sinistro più marcato, icona `info` decorativa e nome accessibile del contenuto complementare. Riutilizzate le regole della nota della Carta dei Valori, conservandone aspetto e comportamento; nessuna semantica di allarme.
- Skill `clean-code-engineer`: sola presentazione nel tema, API/query del plugin e contenuti reali preservati. Tema/asset `1.6.66`, plugin invariato, specifica aggiornata e nessun commit automatico.
- Verificati lint dei due PHP, sintassi JS invariati, HTTP archivio/CSS e `git diff --check`. Test nativi su archivio vuoto e popolato: nota una sola volta, posizione prima dell’elenco/stato vuoto, icona decorativa e dati invariati. Browser reale a 1440/768/390/320 px: nota leggibile senza overflow pagina/box, immagini caricate e console senza errori; review visiva desktop/mobile e screenshot desktop. Verificata anche la nota della Carta dei Valori su mobile, con fondo tenue e bordo da 3 px conservati. Nessun salvataggio editoriale.

## Plugin v1.3.21 — Orario compatto nell’editor del calendario

- Ridotta la larghezza della terza colonna `Orario` a 5,5 rem (input circa 88 px), liberando spazio per le colonne testuali. Stessa regola per righe salvate, aggiunte e importate; campo testuale, valori, label e tabella pubblica invariati.
- Skill `clean-code-engineer`: sola regola CSS circoscritta alla tabella nativa dell’editor, senza modificare PHP/JS del calendario o aggiungere dipendenze. Versione plugin aggiornata per la cache degli asset; tema invariato e nessun commit automatico.
- Verificati lint PHP del bootstrap, sintassi JS invariata, HTTP CSS/dettaglio e `git diff --check`. Regressioni del calendario/import e sei salvataggi intercettati superate senza modificare dati. Browser su anteprima nativa senza salvataggi: valori `20:45` e `21:00` leggibili, larghezza 88 px sulle righe salvate/aggiunte/importate a 1200/768/390 px, console senza errori. Review visiva e screenshot desktop; anteprima temporanea rimossa.

## v1.6.65 — Colonna Note solo quando compilata

- Il calendario pubblico omette l’intestazione `Note` e tutte le relative celle se nessuna lezione contiene una nota. Con almeno una nota, colonna presente per tutte le righe e celle allineate. Spazi soli considerati vuoti, testo `0` conservato. Avviso fisso sotto la tabella, editor, import e dati invariati.
- Skill `clean-code-engineer`: condizione di sola presentazione calcolata una volta nel template, nessun nuovo campo o dipendenza. Righe del markup rese più leggibili; tema/asset `1.6.65`, plugin invariato e nessun commit automatico.
- Verificati lint dei due PHP, sintassi JS invariati, HTTP pagina/CSS e `git diff --check`. Sette casi nativi (note vuote, spazi, una o più note, zero, caratteri speciali, calendario vuoto): intestazioni/celle corrette, data in evidenza e campo editor conservati. Regressioni numero lezione/import e sei salvataggi intercettati superate, senza modificare dati dei corsi.
- Browser senza note a 1440/1024/768/390/320 px: sei colonne, nessun overflow pagina o immagine fallita, desktop senza scrollbar e scorrimento interno sui formati piccoli. Console senza errori, review visiva e screenshot. Corso reale con note: sette colonne e celle allineate. Anteprima temporanea rimossa.

## v1.6.64 — Data del calendario in evidenza

- Spostata l’evidenza dal giorno della settimana alla data completa: data in grassetto/700 e bordeaux, giorno ordinario/400. La data è l’intestazione semantica di riga, con `time` conservato. Numero lezione, ordine, contenuti, plugin e layout invariati.
- Skill `clean-code-engineer`: riusati gli stili delle intestazioni esistenti, intervenendo sul solo markup del calendario. Tema/asset `1.6.64`, specifica aggiornata; nessun commit automatico.
- Verificati lint dei due PHP, sintassi JS invariati, HTTP pagina/CSS e `git diff --check`. Test del calendario (sanitizzazione/import, sei salvataggi intercettati e template) superati senza modificare dati dei corsi. Browser a 1440/1024/768/390/320 px: pesi e colori corretti, cinque righe conservate, nessun overflow pagina o immagine fallita, scorrimento locale sui formati piccoli e console senza errori. Review visiva e screenshot desktop.

## v1.6.63 / plugin v1.3.20 — Numero manuale delle lezioni

- `Numero lezione` come primo campo del calendario nell’editor e prima colonna pubblica (`N°`, nome accessibile completo). Numero manuale facoltativo, anche `03`, `0` o `3 bis`; nessuna rinumerazione né ordinamento numerico. Righe ancora cronologiche, calendari esistenti preservati con `—` per i numeri non inseriti. Nessuna migrazione o modifica ai contenuti reali.
- Import tabulato con numero iniziale e compatibilità con il formato precedente. Conservata la cella iniziale vuota; sanitizzazione nel plugin, nessuna logica duplicata nel tema. Indici monotoni nell’editor dopo rimozioni/aggiunte. Anteprima riuscita resa modificabile senza sovrascrittura dal testo originale al salvataggio; date/titoli invalidi segnalati prima di sostituire le righe, feedback accessibile.
- Skill `software-architect` e `clean-code-engineer`: esteso il modello delle righe esistente, senza nuovi meta, dipendenze o numerazione implicita. Aggiornati versioni, istruzioni e specifiche; nessun commit automatico.
- Verificati lint dei cinque PHP modificati, sintassi JS, HTTP e `git diff --check`. Test nativi: sanitizzazione, numeri speciali/vuoti/legacy, ordine cronologico, sette import vecchi/nuovi, data invalida, sei salvataggi intercettati con nonce/capability, editor e template pubblico. Regressioni su 21 varianti Corsi e 16 offerte, con otto e sette salvataggi intercettati rispettivamente. Nessun dato dei corsi modificato.
- Browser: import nuovo con prima cella vuota, legacy, modifica manuale, rimozione/aggiunta senza indici duplicati e errore senza sostituzione delle righe. Dettaglio con numeri simulati a 1920/1440/1024/768/390/320 px: sette colonne, desktop senza scrollbar e scorrimento solo interno sotto 1024 px, nessun overflow pagina o immagine fallita. Tastiera/focus verificati, console senza errori e review visiva desktop. Pagina reale conserva le cinque lezioni senza numeri; anteprima temporanea rimossa.

## v1.6.62 — Calendario Corsi più largo su desktop

- Risolta la scrollbar forzata dalla colonna da 768 px, più stretta del minimo tabella di 928 px. Calendario separato dalla colonna di lettura e contenitore fino a 1.216 px; descrizione/Eventi collegati/iscrizioni ancora centrati a 768 px. Tabella adattiva da viewport 1.024 px, larghezza minima leggibile e scorrimento interno sotto tale soglia. Spaziatura dei blocchi autonomi coordinata, dati/intestazioni e focus conservati.
- Skill `clean-code-engineer`: intervento circoscritto a template e CSS del dettaglio Corsi, senza dipendenze, modifica della tabella nel plugin o dati editoriali. Tema/asset `1.6.62`, plugin invariato; offerta e precedenti modifiche preservate, nessun commit automatico.
- Verificati lint dei due PHP, sintassi JS invariati, HTTP dettaglio/CSS e `git diff --check`. Regressioni native Corsi su 21 varianti (anche calendario vuoto), otto salvataggi intercettati; offerte su 16 varianti e relative guardie, nessuna persistenza. Browser sul corso reale a 1920/1440/1024 px: tutte le colonne visibili senza scorrimento; 768/390/320 px: scorrimento confinato al calendario, nessun overflow pagina. Misura della descrizione e del pannello conservata, cinque lezioni integre, immagini caricate e console senza errori. Scorrimento da tastiera verificato con focus sul riquadro; review visiva e screenshot desktop/mobile.

## v1.6.61 — Offerte Corsi: giallo pieno e cornice

- Su richiesta esplicita, sostituita la fascia discreta con giallo pieno `#ffda3d`, titolo maiuscolo più grande/700 e data separata/600. Aggiunta cornice coordinata da 4 px a immagine e fascia nei tre contesti; adattamento compatto per la home, senza overlay o animazioni. Contrasto testo bordeaux scuro/fondo 11,9:1; oro istituzionale invariato.
- Skill `clean-code-engineer`: sola presentazione nel tema, classe condizionale derivata dalla API dell’offerta già esistente e stato letto una volta per template. Dati, algoritmo, salvataggi e plugin `1.3.19` invariati; tema/asset `1.6.61`, documentazione aggiornata e precedente lavoro non committato preservato. Nessun commit automatico.
- Verificati lint dei tre PHP modificati, sintassi JS invariati, HTTP dettaglio/CSS e `git diff --check`. Test nativi su 16 varianti dell’offerta: fascia e cornice presenti solo con offerta attiva in home/archivio/dettaglio, guardie dei sette salvataggi intercettati; regressioni Corsi su 20 varianti, senza persistenza. Browser con offerta simulata nei tre template a 1440/768/390/320 px: colore, bordo e dimensioni corretti, nessun overflow pagina/fascia/cornice, immagine fallita o errore console. Review visiva e screenshot desktop/mobile; pagina temporanea rimossa e nessun dato editoriale modificato.

## v1.6.60 / plugin v1.3.19 — Banner offerta dei Corsi

- Nel box `Quota e dotazione`, spunta `Corso in offerta` e `Fine offerta (facoltativa)`. Fascia oro sotto la locandina di home/archivio/dettaglio, senza overlay: `In offerta` e data `Fino al …` se presente. Quote e condizioni editoriali non ricalcolate né riscritte.
- Stato nel plugin: scadenza inclusiva nel fuso WordPress, durata manuale senza data, nessun banner con iscrizioni chiuse/sold-out/lista d’attesa o corso concluso. Meta privati validati, marker per preservare dati in editor precedenti, date invalide senza attivazione involontaria. Nessuna migrazione, attivazione dei corsi reali, cron o dipendenza.
- Skill `software-architect` e `clean-code-engineer`: preferita attivazione esplicita con termine opzionale alla deduzione dal testo libero delle quote; componente condiviso nel tema e regole nel plugin. Versioni e documentazione aggiornate; modifiche precedenti preservate e nessun commit automatico.
- Verificati lint degli otto PHP interessati, sintassi JS invariati, HTTP dettaglio/CSS e `git diff --check`. Test nativi su 16 varianti in home/archivio/dettaglio, data inclusiva/scaduta/invalida, stati chiusi e campi privati; sette salvataggi intercettati con guardie/marker e rendering dei controlli dell’editor. Regressioni Corsi su 20 varianti e ordine/dicitura dell’offerta su otto combinazioni, senza scritture persistenti.
- Browser con offerta simulata nei tre template reali a 1440/768/390/320 px: un banner con data semantica, nessun overflow pagina/fascia, immagine fallita o errore console; review visiva e screenshot di home/archivio/dettaglio. Nessun salvataggio autenticato nell’editor. Confermata l’assenza dei nuovi meta nei quattro corsi locali; pagina temporanea di anteprima rimossa, nessun dato editoriale modificato.

## v1.6.59 / plugin v1.3.18 — Contenuti del corso prima del tesseramento

- Spostato `Cosa comprende il corso` prima di `Tesseramento FISAR`, sia nell’editor sia nel dettaglio. Titolo pubblico completato con `il corso`, evitando l’ambiguità con la tessera; quota ancora per prima e campi vuoti omessi.
- Skill `clean-code-engineer`: riordinate soltanto le chiamate dei campi e la mappa dei titoli esistenti, senza modificare dati, CSS, salvataggi o stati. Tema `1.6.59`, plugin `1.3.18`; documentazione aggiornata, modifiche precedenti preservate e nessun commit automatico.
- Verificati lint PHP, sintassi JS invariati, HTTP dettaglio/CSS e `git diff --check`. Rendering nativo dell’editor: ordine dei tre campi corretto; otto combinazioni di campi nel pannello: ordine e omissione dei vuoti corretti, titolo completo, dati invariati. Regressioni Corsi su 20 varianti e otto salvataggi intercettati, senza persistenza. Browser del dettaglio a 1440/768/390/320 px: titoli ordinati, nessun overflow, immagine fallita o errore console; review visiva e screenshot. Nessun salvataggio autenticato nell’editor.

## 7 ottobre 2026 — Rimossa la nota sul colloquio risalvata

- La frase sul colloquio era nuovamente presente nel campo `Informazioni aggiuntive` del corso locale ID 12, non nel template né nel seed demo. Rimossa soltanto quella frase tramite API WordPress con confronto del valore precedente; conservato il testo aggiunto nel frattempo (`Queste sono delle note aggiuntive:`). Nessuna prova sulla causa del nuovo salvataggio; nessun filtro o blocco sul contenuto editoriale.
- Skill `clean-code-engineer`: intervento sui soli dati interessati, codice/versioni invariati. Verificati rilettura del campo, HTTP e browser del dettaglio: frase assente e pannello/canali conservati; screenshot e `git diff --check`. Nessun commit automatico.

## v1.6.58 — Separatore nei Corsi con titolo personalizzato

- Corretta l’assenza della linea nelle card Personalizzato dell’archivio Corsi: stessa regola decorativa, sotto il titolo libero anziché sotto la riga identitaria delle card automatiche. Testo, nomi accessibili, home/dettaglio e dati conservati.
- Skill `clean-code-engineer`: aggiunto un solo selettore alla regola condivisa, senza cambiare markup, plugin o dipendenze. Tema/asset `1.6.58`; nessun commit automatico.
- Verificati lint PHP, sintassi JS invariati, HTTP archivio/CSS e `git diff --check`; regressioni native dell’identità Corsi senza scritture nel database. Browser a 1440/768/390/320 px: un solo separatore nelle card automatiche e personalizzate, home invariata, nessun overflow o immagine fallita; console senza errori. Review visiva e screenshot dell’archivio.

## v1.6.57 — Eliminato il riepilogo duplicato dei Corsi

- Rimossa dal dettaglio Corsi la sezione generata `Informazioni pratiche`, senza toccare il contenuto editoriale. Livello, direttore, date, sede/indirizzo/città/provincia e Maps rimangono in alto; calendario, Eventi collegati e pannello finale `Informazioni e iscrizioni` conservati.
- Skill `clean-code-engineer`: eliminato soltanto il markup duplicato nel template Corsi, senza modificare CSS condiviso, campi, dati o regole del plugin. Tema/asset `1.6.57`, plugin invariato; Eventi, home/archivio e modifiche precedenti preservati. Nessun commit automatico.
- Verificati lint PHP, sintassi JS invariati, HTTP dettaglio/CSS e `git diff --check`. Test nativi su 20 varianti Corsi: riepilogo inferiore assente, livello/direttore/date e Maps ancora nella hero, calendario/relazioni/pannello conservati; otto salvataggi intercettati, nessuna scrittura persistente. Browser corso attivo a 1440/768/390/320 px e concluso a 320 px: dati iniziali presenti, nessun riepilogo duplicato o overflow; immagini caricate e console senza errori sul corso attivo. Review visiva e screenshot della descrizione seguita dal calendario.

## 7 ottobre 2026 — Nota sul colloquio rimossa

- Su richiesta dell’utente, rimossa dal campo Note iscrizioni del corso locale ID 12 la frase `È possibile richiedere un colloquio informativo prima dell’iscrizione.`; dato demo corrispondente svuotato, secondo la skill `clean-code-engineer`, per non riproporla nelle nuove installazioni. Campo e visualizzazione delle altre note conservati; nessuna modifica al template, alle iscrizioni o agli altri corsi. Nessun reimport o commit automatico.

## v1.6.56 / plugin v1.3.17 — Corsi: informazioni e iscrizioni

- Dettaglio Corsi orientato al contatto: pannello e scorciatoia ordinaria `Informazioni e iscrizioni`, introduzione esplicativa quando disponibili i canali, sezione `Contattaci`. CTA WhatsApp/mail `Contattaci via …`; modulo opzionale conservato e invito posti limitati rivolto al contatto. Recapiti testuali e nomi accessibili completi mantenuti.
- Scadenze, stati chiusi, sold-out/lista d’attesa, quote/tesseramento/dotazione, note editoriali, anchor/focus, home/archivio e Eventi invariati. Nessuna nuova procedura di iscrizione o modifica ai dati.
- Skill `clean-code-engineer`: copy dei canali distinto nel plugin esistente, presentazione nel componente Corsi; tema/asset `1.6.56`, plugin `1.3.17`, nessun CSS, dipendenza o commit automatico.
- Verificati lint PHP, sintassi JS invariati, HTTP dettaglio/CSS e `git diff --check`. Test nativi su 20 varianti Corsi: copy/introduzione ordinari, assenza di contatti, stati chiusi e lista d’attesa, quota/dotazione, calendario, metabox e otto salvataggi intercettati; regressioni Eventi su 17 varianti e relativi salvataggi intercettati, senza persistenza. Browser sulla pagina reale a 1440/768/390/320 px: titolo, scorciatoia e canali corretti, link conservati, nessun overflow pagina/pulsanti, immagine fallita o errore console. Scorciatoia verificata con focus su `course-registration`; nessun canale esterno aperto. Review visiva e screenshot di introduzione e contatti.

## v1.6.55 — Pin anche per eventi online

- Card Eventi di home e archivio: `📍 Online`, con lo stesso pin decorativo delle sedi fisiche, nascosto agli screen reader. Luogo fisico obsoleto ancora omesso; nessuna modifica a eventi in presenza/ibridi, dettaglio o regole di pubblicazione dell’accesso online.
- Skill `clean-code-engineer`: estesa una sola condizione nel componente condiviso, senza nuovo markup, CSS, dipendenze o dati. Tema/asset `1.6.55`, plugin invariato; nessun commit automatico.
- Verificati lint PHP, sintassi JS invariati, HTTP home/archivio/CSS e `git diff --check`. Test di sola lettura su sette località in entrambi i contesti, aggiornata l’aspettativa del pin online; nessuna scrittura nel database. Browser home/archivio a 1440/768/390/320 px: un pin con `aria-hidden="true"` davanti a Online, nessun overflow, immagine fallita o errore console. Review visiva e screenshot desktop.

## v1.6.54 — Separatore Eventi anche nella home

- Card Eventi della home allineate all’archivio: titolo, linea oro tenue, data e luogo, con ordine del markup coerente. Riutilizzata la stessa regola decorativa da 1 px e spaziature compatte. Locandine, fascia sold-out, date adattive, CTA e nomi accessibili conservati; nessun abstract aggiunto o modifica a query, dati, dettaglio, Corsi e News.
- Skill `clean-code-engineer`: estesi soltanto il contesto del componente e il selettore condiviso; tema/asset `1.6.54`, plugin invariato. Nessuna dipendenza o commit automatico.
- Verificati lint PHP, sintassi JS invariati, HTTP home/CSS e `git diff --check`; regressioni native su sette località Eventi in home/archivio senza scritture. Browser home a 1440/768/390/320 px: un solo titolo/data, ordine titolo→data, bordo da 1 px alla larghezza del contenuto e nomi CTA completi; nessun overflow, immagine fallita o errore console. Card Corsi/News senza nuovi separatori. Review visiva e screenshot desktop.

## v1.6.53 — Separatore nelle card Eventi

- Estesa alle card dell’archivio Eventi la linea oro tenue delle card Corsi, con una sola regola CSS condivisa. Titolo e separatore prima di data e luogo, ordine del markup coerente con la resa visiva; home mantiene l’ordine precedente e nessuna linea. Dettaglio, contenuti, stati di iscrizione, query e date adattive invariati.
- Skill `clean-code-engineer`: modifica circoscritta al componente e ai selettori dei due archivi, senza logica di dominio o dipendenze; tema/asset `1.6.53`, plugin invariato. Modifiche precedenti preservate, nessun commit automatico.
- Verificati lint PHP, sintassi dei JS frontend invariati, HTTP pagina/CSS/immagini e `git diff --check`; regressioni native su sette località Eventi in home e archivio senza scritture. Browser Eventi a 1440/768/390/320 px: un solo titolo/data per card, ordine titolo→data, bordo e larghezza corretti, nessun overflow o errore console. Immagini dell’archivio passato inizialmente differite dal lazy-load, disponibili via HTTP. Home senza separatore e con data iniziale; card Corsi invariate. Review visiva e screenshot desktop.

## v1.6.52 — Separatore nelle card Corsi

- Linea decorativa di 1 px oro tenue sotto livello e città nelle card automatiche dell’archivio, prima di data e sede. Larghezza del contenuto, spaziature compatte; home, dettaglio, titoli personalizzati, dati e nomi accessibili invariati. Skill `clean-code-engineer`: solo CSS del componente, senza nuovo markup o dipendenze; tema/asset `1.6.52`, plugin invariato. Modifiche precedenti preservate, nessun commit automatico.
- Verificati lint PHP, sintassi dei due JS frontend invariati, HTTP pagina/CSS e `git diff --check`. Browser a 1440/768/390/320 px: bordo da 1 px con opacità corretta, larghezza del contenuto e posizione tra identità e data, nessun overflow o immagine rotta; console senza errori. Review visiva e screenshot desktop.

## v1.6.51 / plugin v1.3.16 — Livello e città in primo piano

- Nelle sole card automatiche dell’archivio Corsi, `Corso Sommelier` ridotto a introduzione 16–18 px; livello e città a 20–24 px, provincia discreta a 16–18 px. Livello indivisibile, località flessibile e nome accessibile completo. Home, dettaglio, titoli personalizzati, query, date, sede e abstract invariati.
- Skill `clean-code-engineer` e `software-architect`: parti della località normalizzate nel plugin ed esposte senza rimuovere la località completa dalla API esistente; presentazione nel tema, nessuna analisi del titolo libero, migrazione, scrittura editoriale o dipendenza aggiunta. Tema/asset `1.6.51`, plugin `1.3.16`; nessun commit automatico.
- Verificati lint PHP, sintassi dei due JS frontend invariati, HTTP pagina/CSS e `git diff --check`. Test nativi di sola lettura: sei normalizzazioni della località, quattro card strutturate (anche senza città/provincia), nomi accessibili completi e home/titoli personalizzati conservati; regressioni su sette località Eventi in home e archivio. Browser a 1440/768/390/320 px: scala tipografica corretta, livello indivisibile, nessun overflow, immagine rotta o errore console. Review visiva e screenshot; nessuna scrittura nel database.

## v1.6.50 / plugin v1.3.15 — Identità dei Corsi, titolo automatico e pin

- Card standard dell’archivio Corsi: `Corso Sommelier` e seconda riga livello/località con provincia tra parentesi, senza duplicare il livello nella fascia superiore. Livello indivisibile, badge separato e nomi accessibili completi. Sede con `📍` decorativo; città non duplicata quando la sede manca. Titolo personalizzato conservato come eccezione. Date iniziali, abstract e CTA invariati; data finale ancora solo nel dettaglio.
- Scelta Automatico/Personalizzato nel metabox Corsi e anteprima. Unico titolo nativo `post_title`, composizione nel plugin da Livello/Città/Provincia, senza analizzare il titolo libero. Default di sola lettura Automatico per livelli validi e nuovi auto-draft; titolo nel database preservato fino al salvataggio autorizzato. Niente migrazione o rinomina degli URL. Preparazione nel classico e sincronizzazione dopo il salvataggio metabox di Gutenberg, con hook ripristinato in `finally`; editor precedenti senza nuova scelta non sovrascrivono il titolo. Script nativo limitato ai Corsi, nessuna modifica del titolo alla sola visita dell’editor.
- Luogo delle card Eventi in home/archivio con pin, sede e città/provincia, campi vuoti omessi. Online senza pin; ibrido con `+ online`, oppure `In presenza e online` se manca il luogo. Componente del pin condiviso, emoji nascosta agli screen reader, wrapping dei nomi lunghi. Accesso online, Maps e pagine di dettaglio invariati. Home Corsi conserva il layout compatto e il titolo nativo.
- Skill `software-architect` e `clean-code-engineer`: scelta esplicita per le eccezioni, API/identità nel plugin e presentazione nel tema, nessun dato titolo duplicato, dipendenza o filtro globale. Documentazione aggiornata; precedente lavoro preservato e nessun commit automatico. Discrepanza locale ID 12 (Falconara nel titolo libero, Jesi nel campo Città) segnalata e non corretta automaticamente.
- Verifiche: lint di nove PHP, sintassi JS e `git diff --check`; HTTP degli archivi e asset. Test reali nel runtime WordPress di creazione/aggiornamento e salvataggio a due fasi su bozze, titolo vuoto/personalizzato, ricomposizione, slug stabile, nonce/target/campo assente e enum privato. Transazione con rollback finale verificato sui conteggi di post/meta, senza contenuti di prova persistenti. Test JS con store simulato per blocchi/classico: nessuna modifica iniziale, dati mancanti, aggiornamento titolo e passaggio di modalità. Sette varianti Eventi in home/archivio per località, pin, presenza/online/ibrido e escaping; regressioni Corsi (20 varianti), sold-out Eventi e Riassunto superate.
- Browser: archivi Corsi/Eventi e anteprima dei metabox a 1440/768/390/320 px, nessun overflow o immagine rotta, livello unito e nomi accessibili verificati. Preview classica con script/core `dom-ready` reali: modifiche di città, titolo personalizzato e ritorno ad Automatico; nessun errore console nei controlli Corsi/editor. Home mobile verificata e screenshot dell’archivio Corsi. Nessun salvataggio autenticato via browser: il test dei blocchi è unitario, quello del server usa il runtime WordPress.

## 7 ottobre 2026 — Sede e data nelle card dell’archivio Corsi

- Rimossa la data finale dalle card dei corsi attivi/conclusi; luogo con nome della Sede anziché sola città, con ripiego sulla città se manca la sede e nessuna riga se mancano entrambe. Nome escapato, senza troncamenti o modifiche al titolo. Card compatte della home invariate. Data di fine conservata nel dettaglio, nell’editor e nelle regole temporali del plugin.
- Skill `clean-code-engineer`: modifica circoscritta al componente esistente del tema, nessun nuovo campo o duplicazione della logica di stato. README e specifica aggiornati; CSS/JS, dati salvati e modifiche precedenti preservati. Nessun commit automatico.
- Verificati lint PHP, HTTP archivio/dettaglio e `git diff --check`. Test nel runtime WordPress: sette sedi (normali, vuote, spazi, caratteri HTML e nome lungo), due date finali e contesti archivio/home; ripiego, escaping, data iniziale, stato derivato dalla fine e CTA accessibile corretti, senza scritture nel database. Browser archivio a 1440/768/390/320 px: sede corretta, nessuna data finale, overflow o immagine rotta; nessun errore console. Review visiva/screenshot e controllo che la home mantenga la città.

## 7 ottobre 2026 — Etichette degli archivi Eventi e Corsi

- `Archivio` sopra Eventi conclusi e Corsi conclusi, al posto di `Il nostro percorso` e `Archivio formativo`; `In programma` sopra Corsi attivi, al posto di `Iscrizioni e lezioni`. Intervento sui soli testi dei due template, secondo la skill `clean-code-engineer`: titoli, query, ordine, card, asset e contenuti editoriali invariati. Specifiche aggiornate e modifiche precedenti preservate; nessun commit automatico.
- Verificati lint dei due PHP, risposta HTTP con le nuove etichette e `git diff --check`. Browser a 1440/768/390 px su entrambi gli archivi: testi corretti, nessun overflow, immagine rotta o errore console. Screenshot dell’archivio Corsi.

## Plugin v1.3.14 — Breve descrizione nei dettagli di Eventi e Corsi

- Riassunto nativo spostato all’inizio dei metabox principali, con label `Breve descrizione` e istruzioni sulla sua pubblicazione. Box Eventi rinominato `Dettagli dell’evento`, ID conservato; box Corsi `Dettagli del corso`. Metabox classico/pannello laterale separati rimossi soltanto per questi CPT, senza togliere il supporto nativo all’estratto.
- Unico dato `post_excerpt`: lettura raw escapata, `id`/`name` nativi per il classico e sincronizzazione bidirezionale con lo stato nativo nell’editor a blocchi, tramite API WordPress. Nessun nuovo meta o handler di salvataggio; valori vuoti e inizializzazione differita gestiti, salvataggio/autosalvataggio affidati al core. Testi esistenti, frontend e altri editor invariati. Tema ancora `1.6.49`.
- Skill `clean-code-engineer`: helper condiviso fra Eventi e Corsi, responsabilità nel plugin, nessun dato duplicato o dipendenza esterna. README, specifiche e architettura aggiornati; nessuna modifica ai contenuti nel database o commit automatico.
- Verificati lint PHP, sintassi JS, HTTP archivio/nuovo asset e `git diff --check`. Test PHP nel runtime WordPress su entrambi i metabox: testo esistente, quattro casi di escaping e quattro traduzioni native excerpt→post_excerpt, campo assente, supporto CPT, rimozione duplicato e caricamento script limitato agli editor a blocchi pertinenti. Test JS con store simulato: inizializzazione differita, sei valori/testo vuoto, sincronizzazione, annulla/ripristina, assenza di feedback ricorsivo e pulizia della sottoscrizione. Nessun salvataggio amministrativo autenticato effettuato.
- Anteprime dei metabox generate dal PHP reale e controllate nel browser a 1440/768/390/320 px: campo unico, label e istruzioni associate, nessun overflow, asset caricati e nessun errore console; inserimento multilinea/caratteri speciali verificato senza salvataggio. Screenshot Corsi e review visiva. Le anteprime non riproducono l’intero editor Gutenberg.
- Regressioni native Corsi (20 varianti) ed Eventi sold-out/scadenze superate, inclusi salvataggi intercettati e metabox: nessuna scrittura persistente.

## v1.6.49 — Dimensioni delle card nell’archivio Corsi

- Allineata la griglia Corsi a quella Eventi: tre colonne da 832 px, due da 608 px e una su mobile. Riutilizzata la stessa regola desktop e rimosso l’override a due colonne sulle larghezze maggiori, secondo la skill `clean-code-engineer`. Immagini quadrate, contenuti e tipografia invariati; home e dettaglio non modificati.
- Tema/asset `1.6.49`, specifica aggiornata. Plugin, dati e precedenti modifiche preservati; nessun commit automatico.
- Verificati lint PHP, sintassi JS, HTTP pagina/CSS e `git diff --check`. Confronto browser Corsi/Eventi/home a 1440/1024/832/831/768/608/607/390/320 px: larghezze delle card dei due archivi identiche, transizioni dei breakpoint corrette, home invariata, nessun overflow, immagine rotta o errore console. Review visiva e screenshot della griglia desktop.

## v1.6.48 — Corsi allineati agli Eventi

- Tema `1.6.48`: archivio con fascia compatta scura, dettaglio con hero chiara, titoli/corpo/editor Poppins e immagini quadrate intere su bianco. Sede, indirizzo, città/provincia e Maps nei due riepiloghi; un solo pannello finale Iscrizione con scorciatoia e focus da tastiera. Quota, tesseramento e dotazione distinti; condizioni editoriali libere conservate, € automatico solo per importi isolati. Calendario/import, relatori/note e relazioni con Eventi invariati.
- Plugin `1.3.13`: disponibilità ordinaria/sold-out/lista d’attesa, posti limitati, Maps opzionale e contatti WhatsApp ripetibili con nominativo/numero con o senza +39. Meta privati, sanitizzatori condivisi, marker e protezioni native del salvataggio; fallback dal numero precedente senza migrazioni o scritture automatiche. Termine tassativo superato chiude le iscrizioni; corso concluso prioritario. Corso attivo distinto da iscrizioni aperte. Stessi canali per la lista d’attesa, senza raccolta delle richieste nel sito.
- Home: card compatte con livello, inizio e disponibilità, fino a tre corsi; link Tutti i corsi sempre presente, totale quando eccede tre e stato vuoto funzionante. Componenti contatti, luogo, scadenza, posti limitati e sold-out riusati con gli Eventi; date adattive anche nell’archivio Corsi. Nessuna modalità online/ibrida o quote Soci/Non soci imposte ai Corsi.
- Skill `software-architect` e `clean-code-engineer`: separate regole di dominio e presentazione, preservate specificità Corsi e API Eventi, condivisi soltanto componenti/regole pertinenti. README e specifiche aggiornati; nessuna dipendenza, contenuto reale modificato o commit automatico.
- Verifiche native: lint PHP, sintassi JS e `git diff --check`; 20 varianti Corsi (scadenze, stati, sedi parziali/assenti/lunghe, canali, quota zero), sanitizzatori/meta, sei formati quota, fallback legacy/elenco vuoto e otto salvataggi intercettati. Home con 0/1/3/4/7 corsi e import calendario; regressioni Eventi su sold-out, scadenze, accesso online e luogo. Vecchia aspettativa “Corsi invariati” del test scadenze aggiornata alla nuova specifica. Nessuna scrittura persistente durante i test.
- Browser: home, archivio, dettaglio attivo/concluso e cinque anteprime native a 1440/768/390/320 px; nessun overflow della pagina, font e immagini non ritagliate, tabella contenuta. Card home sold-out/lista d’attesa, repeater WhatsApp da tastiera, ID univoci e focus della scorciatoia verificati. Controlli HTTP/asset e review visiva desktop/mobile. Nessun login, salvataggio amministrativo o apertura di canali esterni; anteprime con dati di esempio.

## v1.6.47 — Evento ibrido prima della sede

- Raffinato il componente condiviso Luogo: riquadro `EVENTO IBRIDO` / `In presenza e online` sopra la sede, in hero e informazioni pratiche, anziché Anche online sotto. Titolo 14 px/700 e testo 16 px/600, su due righe, con accento oro e bordeaux; nessuna ripetizione In presenza se manca la sede. `Online` a 16 px/600 in grassetto semantico in entrambi i riepiloghi. Regole di accesso, Maps, plugin/dati e Corsi invariati.
- Skill `clean-code-engineer` applicata intervenendo nel componente condiviso senza duplicazioni o dipendenze. Tema/asset `1.6.47`, specifica aggiornata; nessuna scrittura nel database o commit automatico.
- Verifiche: lint dei due PHP, sintassi JS, HTTP e `git diff --check`; dodici varianti native di Luogo, ordine del riquadro prima dei dati fisici, grassetto Online e regressioni della pubblicazione facoltativa dell’accesso. Browser su ibrido e online a 1440/768/390/320 px: posizione, pesi font, asset e ritorni a capo verificati, nessun overflow o errore console. Review visiva e screenshot con dati di esempio; nessun login o collegamento esterno aperto.

## v1.6.46 — Luogo uniforme per online e ibrido

- `LUOGO: Online` nei due riepiloghi, senza riga Modalità. Ibridi con `Anche online` ben visibile dentro Luogo in entrambe le posizioni: etichetta su riga propria, Poppins 16 px/600, bordeaux e fondo caldo. Sede/indirizzo/città/Maps conservati; senza sede fallback In presenza + Anche online. Dati fisici obsoleti non mostrati per online.
- Skill `clean-code-engineer` applicata riusando `event-location.php` nei due riepiloghi invece di mantenere markup divergente. Intervento nel tema/asset `1.6.46`, nessuna modifica a plugin, dati, Corsi o regola di pubblicazione facoltativa dell’accesso online. Specifica e architettura aggiornate, nessuna dipendenza o commit automatico.
- Verifiche: lint dei tre PHP, sintassi JS, `git diff --check` e HTTP. Dodici varianti native in memoria: online/ibrido/presenza, sede assente, solo Maps o indirizzo, nomi lunghi, testo da escapare, evento concluso e accesso pubblico/nascosto. Riepiloghi identici, nessuna riga Modalità o perdita della modalità ibrida. Regressioni accesso online, prenotazioni e sold-out superate senza scritture persistenti.
- Browser su quattro anteprime native, desktop/tablet/mobile a 1440/768/390/320 px: etichette, font, asset e ritorni a capo corretti, nessun overflow o errore console. Review visiva e screenshot del recap inferiore ibrido con dati di esempio; nessun login, salvataggio amministrativo o link esterno aperto.

## v1.6.45 — Pubblicazione facoltativa dell’accesso online

- Plugin `1.3.12`: checkbox `Mostra le informazioni per partecipare online` nel box Modalità e luogo, solo online/ibrido, default disattivato anche per gli eventi esistenti. Meta booleano privato, sanitizzazione conservativa e salvataggio protetto con marker. Piattaforma/link rimangono compilabili e salvati; nessuna migrazione, scrittura automatica o modifica ai Corsi.
- API del plugin per i soli dati pubblici, usata dal tema `1.6.45`: nessuna piattaforma/URL nell’HTML se nascosti, accesso visibile solo quando abilitato. Supportato anche URL senza nome della piattaforma; eventi conclusi ancora senza link. Modalità, luogo fisico/Maps e canali di prenotazione invariati. Skill `clean-code-engineer` applicata mantenendo regola nel plugin e presentazione nel tema, riusando metabox/script nativi senza dipendenze. Documentazione aggiornata; nessun commit automatico.
- Verifiche: lint dei sei PHP modificati, sintassi JS, `git diff --check` e HTTP. Sedici varianti native con dati in memoria (default, online/ibrido/presenza, dato malformato, evento concluso, URL senza piattaforma, protocolli non ammessi, sold-out/lista d’attesa), dieci input del sanitizzatore, meta/default/editor e sette salvataggi intercettati. Regressioni sold-out, scadenze e prenotazioni senza scritture persistenti.
- Browser su editor e tre anteprime native a 1440/768/390/320 px: default non selezionato, toggle preservato cambiando modalità, campi/URL conservati, assenza dell’accesso nell’HTML quando nascosto, contatti disponibili e dati pubblici solo quando abilitati. Nessun overflow, asset rotto o errore console; nessun login, salvataggio amministrativo o collegamento esterno aperto. Screenshot editor con dati di esempio.

## v1.6.44 — Fascia sold-out sotto la locandina

- Componente testuale condiviso `event-sold-out.php`, sotto l’immagine nelle card home/archivio e nel dettaglio: bordeaux, bianco, accento oro, Poppins 700 e indicazione della lista d’attesa quando disponibile. Nessuna sovrapposizione all’immagine, che conserva proporzioni quadrate e `contain`. Badge ordinario nel corpo della card conservato solo per iscrizioni chiuse; eventi conclusi e ordinari invariati.
- Sold-out senza lista d’attesa: data del termine nascosta nei due box, conservata nei dati. Lista d’attesa e chiusura per termine tassativo mantengono la scadenza; regole del plugin e priorità invariate. Skill `clean-code-engineer` applicata riusando un unico componente per le tre viste. Solo presentazione, tema/asset `1.6.44`; nessuna dipendenza, scrittura nel database o commit automatico.
- Verifiche: lint dei cinque PHP, sintassi JavaScript e `git diff --check`; diciassette varianti native in memoria, inclusa scadenza futura del sold-out e regressioni su chiusura/lista d’attesa, meta/editor e salvataggi intercettati. HTTP e browser desktop/tablet/mobile a 1440/768/390/320 px: fascia sotto l’immagine, font caricati, locandina intera, nessun overflow o asset rotto. Anteprime con dati simulati, senza modificare eventi reali.

## v1.6.43 — Sold-out e lista d’attesa

- Plugin `1.3.11`: meta privato enum `_fisar_event_booking_status`, sanitizzazione condivisa e menu a tre opzioni nel box Iscrizioni. Stato assente ordinario, salvataggio solo con campo nel POST e protezioni esistenti. API effettiva con precedenza della scadenza tassativa; helper chiusura compatibile anche con sold-out senza lista. Nessun conteggio o richiesta persistita, migrazione o modifica ai Corsi.
- Lista d’attesa tramite gli stessi contatti/URL e nominativi, con etichette dedicate; sold-out semplice senza canali. Copy e posti limitati coerenti con lo stato. Tema/asset `1.6.43`: avviso nei due box anche senza data, scorciatoia e titoli dedicati, note operative ordinarie nascoste e quote conservate. Badge nelle card di home/archivio, nessuno sugli eventi conclusi. Stato concluso e termine tassativo superato prioritari.
- Skill `software-architect` applicata scegliendo un enum reversibile invece di booleani indipendenti; `clean-code-engineer` centralizza regole/copy nel plugin e riusa componenti/frontend. Documentazione aggiornata, nessuna dipendenza, dato reale modificato o commit automatico.
- Verifiche: lint dei nove PHP modificati, sintassi dei tre JS e `git diff --check`; sedici varianti di disponibilità, otto normalizzazioni enum, registrazione/meta privato, editor/default e otto salvataggi intercettati. Regressioni scadenze, prenotazioni, quote e contatti senza scritture persistenti. Browser su anteprime native: lista d’attesa, sold-out semplice, editor e card verificati su desktop/tablet/mobile, pulsanti/URL corretti e overflow assente; selezione preservata disattivando/riattivando Iscrizione richiesta. Nessun login, salvataggio amministrativo o link esterno aperto; server temporaneo arrestato al termine.

## v1.6.42 — Iscrizioni Eventi chiuse dopo il termine tassativo

- Plugin `1.3.10`: nuova regola derivata per iscrizione richiesta, termine tassativo e data canonica valida precedente al giorno corrente WordPress. Scadenza inclusa; termini flessibili/assenti/invalidi non chiudono. API strutturata con `closed`/`closed_notice`, API testuale aggiornata, canali Eventi vuoti e copy operativo/posti limitati soppressi quando chiuso. Nessun meta riscritto, flag manuale o cron; Corsi invariati.
- Tema/asset `1.6.42`: `Iscrizioni chiuse` sia in hero sia nel pannello, scadenza conservata come informazione. Scorciatoia `Informazioni sulle iscrizioni` con destinazione/focus invariati; quote visibili, canali e note operative nascosti. Evento concluso prioritario. Skill `clean-code-engineer` applicata centralizzando la regola nel plugin e riusando il componente della scadenza senza nuovi stili/dipendenze. README, specifiche e assunzioni aggiornati; nessuna modifica agli eventi reali o commit automatico.
- Verifiche: lint dei sei PHP modificati, sintassi dei tre JS e `git diff --check`; evento/CSS HTTP 200. Quattordici varianti dedicate (ieri, oggi, domani, flessibile superato, senza prenotazione/data/tipo valido, date impossibili/non bisestili/relative, gratuità, evento concluso), più regressioni prenotazione, quote e contatti WhatsApp, salvataggi intercettati senza persistenza. Fixture WhatsApp resa indipendente dalla scadenza reale modificata editorialmente dall’utente.
- Browser sull’evento reale con scadenza tassativa al 4 ottobre ed evento all’8 ottobre: stato chiuso in entrambi i box, nessun pulsante operativo, quote conservate. Desktop/tablet/mobile a 1440/768/390/320 px senza overflow o immagini rotte, font caricati e console senza errori. Scorciatoia da tastiera con focus sul pannello; review visiva e screenshot. Nessun login, modifica al database o link esterno aperto.

## 6 ottobre 2026 — Istruzioni dei campi quota

- Sostituito il vecchio esempio `€ 25` nel testo di aiuto con la distinzione tra importo senza valuta (`25`, con euro aggiunto automaticamente) e testo libero (`Da 25 €`, `Offerta libera`). Istruzione condivisa fra Quota soci e Quota non soci, secondo la skill `clean-code-engineer`; specifica aggiornata. Versioni, dati, salvataggio e frontend invariati; nessun commit.
- Verificati lint PHP, `git diff --check`, rendering nativo delle due descrizioni ed esempi nel formatter. Rieseguite normalizzazione quote, salvataggi intercettati e varianti del dettaglio, senza scritture persistenti.

## Plugin v1.3.9 — Numero WhatsApp con o senza +39

- Campo Eventi semplificato in `Numero WhatsApp`, tipo `tel` e `inputmode=tel`, esempio e istruzioni per il numero con o senza prefisso italiano. Aggiornati anche feedback screen-reader e fallback senza JS del repeater; nominativi, elenco e protezioni del salvataggio invariati.
- Il normalizzatore condiviso accetta un codice paese di default facoltativo: soltanto i canali Eventi passano `39`. Prefisso aggiunto al link dei numeri nazionali validi, mai al dato salvato o al riferimento mostrato; rispettati `+39`, `0039`, prefissi esteri e zero iniziale dei fissi. Vecchi URL, messaggi e parametri preservati. Skill `clean-code-engineer` applicata tenendo la regola nel plugin e conservando il comportamento predefinito per i Corsi. Plugin/asset admin `1.3.9`, tema `1.6.41` invariato; README e specifiche aggiornati. Nessuna migrazione, modifica ai dati reali, nuova dipendenza o commit automatico.
- Verifiche: lint dei tre PHP modificati, sintassi JS e `git diff --check`; evento e asset admin versionati HTTP 200. Quattordici casi di numeri (nazionali formattati, internazionali espliciti, fissi, invalidi, vecchi URL/messaggi), sette normalizzazioni dell’elenco, dieci salvataggi intercettati incluso un numero senza prefisso, otto formattazioni visuali e rendering frontend/metabox. Rieseguite quattordici varianti iscrizione, undici normalizzazioni condivise, sei salvataggi contatti e dodici casi valuta. Tutti senza scritture persistenti.
- Browser su anteprime native con dati simulati: input telefonici, label, istruzioni, aggiunta/rimozione e focus verificati; editor e frontend a 1440/768/390/320 px senza overflow. Numero italiano senza prefisso visibile e link `wa.me` con `39`; nessuna immagine rotta o errore console frontend. Nessun login, salvataggio amministrativo o link esterno aperto; server di anteprima arrestato al termine.

## v1.6.41 — Nome e numero WhatsApp sulla stessa riga

- Nome e numero/link del destinatario nello stesso paragrafo, separati da `·`, senza a capo imposto e con ritorno naturale quando lo spazio non basta. Rimossa la classe della riga nome separata. Gli altri canali restano invariati.
- Prefisso italiano esplicito `+39` o `0039` omesso solo nel riferimento visuale degli Eventi; il formatter del tema lavora sul riferimento già normalizzato dal plugin. Prefissi esteri, numeri senza prefisso e URL di gruppi/canali non alterati. Dati editoriali, pulsanti, URL e nomi accessibili completi conservati. Skill `clean-code-engineer` applicata mantenendo questa scelta di presentazione nel tema, senza duplicare la normalizzazione del plugin. Tema/asset `1.6.41`, plugin `1.3.8` invariato; nessuna scrittura nel database o commit automatico.
- Verifiche: lint dei due PHP modificati, sintassi dei tre script JS e `git diff --check`; pagina, CSS versionato e font HTTP 200. Otto casi del formatter (numeri italiani formattati/compatti, `0039`, prefisso estero, numeri nazionali e URL), sette normalizzazioni dei contatti e dieci salvataggi intercettati, rendering nativo aggiornato e quattordici regressioni di prenotazione, senza persistenza. Il caso legacy è simulato anche nell’esistenza del meta, indipendentemente dai nuovi contatti inseriti dall’utente.
- Browser sul contatto reale già inserito a 1440/768/390/320 px: nome e numero sulla stessa linea, riferimento senza `+39`, URL internazionale invariato, font caricati e nessun overflow, immagine rotta o errore console. Review visiva desktop/mobile; nessuna modifica ai contatti reali o apertura del link WhatsApp.

## v1.6.40 — Nominativi e più contatti WhatsApp per gli Eventi

- Plugin `1.3.8`: elenco privato ordinato `_fisar_event_whatsapp_contacts` con `name` facoltativo e `value` (numero o link). Sanitizzazione condivisa, righe vuote/malformate ignorate, URL e messaggi codificati conservati. Fallback di sola lettura dal contatto precedente quando il nuovo meta non esiste; elenco esplicitamente vuoto senza ricomparsa del legacy. Primo destinatario sincronizzato nel vecchio campo dopo un salvataggio del nuovo metabox.
- Editor Eventi, box `Iscrizioni`: due campi distinti per contatto, `Aggiungi contatto WhatsApp`/`Rimuovi contatto`, fieldset/label, indici monotoni, focus e feedback screen-reader. Riga vuota per uso senza JS; istruzione sulla pubblicazione dei nominativi/recapiti. Nonce/capability/autosave/revision esistenti, marker di presenza e gestione slash/apostrofi; editor precedente senza marker non azzera il nuovo elenco o il riferimento legacy sincronizzato. Corsi invariati.
- Tema/asset `1.6.40`: nome su riga separata da numero/link e un pulsante per destinatario, nella griglia dei canali esistente. Suffisso accessibile con nome o riferimento; numero leggibile anche senza link. Riutilizzata la normalizzazione WhatsApp, nessun prefisso dedotto o dipendenza aggiunta. Skill `clean-code-engineer` applicata mantenendo dati e salvataggio nel plugin, presentazione nel tema; README e specifiche aggiornati. Nessun dato reale modificato o commit automatico.
- Verifiche: lint dei sei PHP modificati, sintassi dei tre JS e `git diff --check`; evento, Corso, CSS tema/admin, JS admin e font HTTP 200. Sette normalizzazioni del nuovo elenco, registrazione meta, fallback legacy/elenco vuoto, ordine, link con messaggio e assenza di prefisso; dieci salvataggi intercettati (compresi rimozione totale, marker assente, input malformato, slash, vecchio editor, nonce/capability negati), rendering nativo frontend/metabox. Rieseguite quattordici varianti di prenotazione, dodici casi valuta, undici WhatsApp, sei salvataggi contatti, nove normalizzazioni quote, otto salvataggi quote e undici varianti complete del dettaglio, senza persistenza.
- Browser su anteprime native con due contatti di esempio a 1440/768/390/320 px: campi associati, nomi/numeri/pulsanti corretti, font caricati e nessun overflow, immagine rotta o errore console. Aggiunta/rimozione anche da tastiera, cancellazione totale e nuova aggiunta, focus, ID univoci e valori preservati. Review visiva desktop/mobile; evento reale con contatto precedente ancora presente e corretto. Nessun login o salvataggio amministrativo autenticato: i test di editor/salvataggio sono anteprime e intercettazioni in memoria. Nessun link di prenotazione aperto; server temporaneo arrestato al termine.

## v1.6.39 — Posti limitati anche nel riepilogo iniziale

- Successiva correzione richiesta dall’utente: rimosso `si`; il copy definitivo nei due box è `Posti limitati. Ti consigliamo di prenotare prima che esauriscano.` Nessun cambiamento a markup, stili o condizioni di visualizzazione. Rieseguiti lint PHP, quattordici varianti e controllo HTTP del testo in entrambe le posizioni.

- Sostituito `Prenota appena possibile.` con `Ti consigliamo di prenotare prima che si esauriscano.` dopo `Posti limitati.` in grassetto. Avviso ripetuto nel box iniziale prima della scorciatoia e nel pannello finale dopo la scadenza, solo con flag attivo e per eventi non conclusi. Testo 16 px, interlinea 1.6, fondo bianco e bordo bordeaux, senza ereditare il maiuscolo delle etichette del riepilogo.
- Componente condiviso `event-limited-seats.php`; copy fornito dai dettagli di prenotazione del plugin tramite `limited_seats_notice`. Se manca una scadenza valida, il box iniziale contiene solo avviso e scorciatoia; se mancano entrambi resta il link autonomo. Tema/asset `1.6.39`, plugin `1.3.7`. Skill `clean-code-engineer` applicata evitando duplicazioni di copy e markup. Specifiche aggiornate; nessuna scrittura nel database, dipendenza o commit automatico.
- Verifiche: lint dei sei file PHP modificati/aggiunti, sintassi dei due script frontend e `git diff --check`; evento, CSS versionato e font HTTP 200. Quattordici varianti native del dettaglio, comprese scadenze assenti/non valide, flag spento, gratuità ed evento concluso; asserzioni su testo aggiornato, avvisi dentro entrambi i box e singola scorciatoia. Dodici casi valuta, undici WhatsApp e sei salvataggi intercettati senza persistenza.
- Browser sull’evento Verdicchio a 1440/768/390/320 px: due note identiche, testo/etichetta 16 px, nessun overflow o immagine rotta, font caricati e console senza errori. Review visiva desktop/mobile e link interno funzionante con focus sul pannello. Evento giapponese senza flag: avviso assente; evento ibrido con flag: presente nelle due posizioni. Nessun login, prenotazione o link esterno aperto.

## v1.6.38 — Avviso di partecipazione libera più leggibile

- Aumentata soltanto la frase `La partecipazione è libera, non è richiesta la prenotazione.` da 16 a 18 px, mantenendo Poppins e grassetto 600; interlinea 1.6 e ritorno a capo naturale. Altri avvisi, quote, Corsi e contenuti invariati.
- Plugin `1.3.6`: aggiunto `open_participation_notice` ai dettagli di prenotazione, riusando il testo esistente e conservando `lines` e l’API testuale. Tema/asset `1.6.38`: classe dedicata al solo messaggio. Skill `clean-code-engineer` applicata mantenendo copy/regole nel plugin e presentazione nel tema. Specifiche aggiornate; nessun dato modificato o commit automatico.
- Verifiche: lint dei quattro file PHP modificati, sintassi dei due script frontend e `git diff --check`; evento, CSS e font HTTP 200. Tredici regressioni del pannello con nuove asserzioni sulla classe dedicata, dodici casi valuta, undici normalizzazioni WhatsApp e sei salvataggi intercettati, senza scritture persistenti.
- Browser sull’evento gratuito con partecipazione libera a 1440/768/390/320 px: frase a 18 px/600, altro avviso a 16 px, font caricati e nessun overflow, immagine rotta o errore console. Review visiva desktop/mobile; evento concluso senza la nuova classe. Nessun login, link esterno aperto o prenotazione inviata.

## v1.6.37 — Un solo separatore tra quote e prenotazione

- Corretto il doppio bordo nel pannello dell’evento `in-cantina-con-il-vignaiolo`: quando `registration-fees` è immediatamente seguito da `registration-methods`, rimane il solo bordo inferiore delle quote. Rimossi bordo e padding superiore aggiuntivi dai metodi soltanto in questo caso; separazione invariata quando c’è una nota di partecipazione intermedia o mancano le quote.
- Regola CSS condivisa e circoscritta agli Eventi, secondo la skill `clean-code-engineer`. Tema/asset `1.6.37`, specifica Evento aggiornata; template, plugin, contenuti, quote, contatti e focus invariati. Nessuna scrittura nel database o commit automatico.
- Verifiche: lint PHP del file modificato, sintassi dei due script frontend e `git diff --check`; evento, CSS versionato e font HTTP 200. Rieseguite tredici varianti del pannello, dodici casi valuta, undici normalizzazioni WhatsApp e sei salvataggi intercettati senza persistenza. Browser sull’evento indicato a 1440/768/390/320 px: bordo quote 1 px, bordo metodi e padding superiore 0 px, font caricati, nessun overflow, immagine rotta o errore console; review visiva desktop/mobile. Caso con nota intermedia verificato a 1440/320 px: bordo metodi 1 px e padding 20 px conservati.

## v1.6.36 — Quote uniformi e importi allineati

- Quote personalizzate presentate come Soci/Non soci nello stesso elenco: rimossi wrapper di categoria, separatore interno, margini/padding aggiuntivi e peso distinto delle etichette. Una sola griglia condivisa dimensiona la colonna delle etichette e allinea tutti gli importi, con gap verticale 8 px e orizzontale 12 px. Note mantenute a tutta larghezza, testi lunghi liberi di andare a capo e spazio minimo per gli importi su mobile.
- Componente `event-fees.php` condiviso fra pannello finale ed eventi conclusi; template compatibile con quote legacy. Skill `clean-code-engineer` applicata eliminando gli stili separati delle opzioni. Tema/asset `1.6.36`, specifica Evento aggiornata; plugin `1.3.5`, editor, dati e formattazione euro invariati. Preservate le modifiche non ancora committate della v1.6.35 e le quote inserite dall’utente; nessun commit automatico.
- Verifiche: lint dei due file PHP modificati, sintassi dei due script frontend e `git diff --check`; evento, CSS versionato e font HTTP 200. Rieseguiti nove casi di normalizzazione, otto salvataggi intercettati, undici varianti complete del dettaglio e rendering metabox, senza scritture persistenti.
- Browser: evento reale con quattro quote a 1440/768/390/320 px, importi alla stessa coordinata orizzontale, etichette a peso 400, spaziatura uniforme e nessun separatore interno. Review visiva desktop/mobile, font caricati, nessun overflow, immagine rotta o errore console. Anteprima WordPress con note e un’etichetta molto lunga alle stesse quattro larghezze: importi allineati, note a tutta larghezza e nessun overflow. Nessun login o link esterno aperto; server temporaneo arrestato al termine.

## v1.6.35 — Quote Evento personalizzabili e plugin v1.3.5

- Conservati campi e dati Soci/Non soci. Aggiunti meta singoli privati `_fisar_event_fee_options` (righe ordinate etichetta/importo/nota) e `_fisar_event_fee_note` (nota generale multilinea). Sanitizzazione centralizzata nel plugin: input malformati e righe incomplete scartati, `0` valido, nessun importo presunto o calcolo. Nuova API `fisar_cdj_get_event_fees()`; gratuità ancora prioritaria, senza cancellare quote salvate.
- Metabox nativo `Partecipazione e costi`: nota generale, altre opzioni e pulsanti `Aggiungi quota`/`Rimuovi quota`. Template PHP condiviso fra righe salvate, riga vuota e aggiunta JS; indici monotoni senza collisioni dopo rimozioni, label associate, fieldset, focus gestito e feedback screen-reader. Fallback senza JS tramite riga vuota e rimozione svuotando i campi. Salvataggio con nonce/capability esistenti, marker per proteggere i nuovi dati se il box manca dal POST, gestione corretta di slash e apostrofi.
- Componente pubblico `event-fees.php` esteso senza duplicazioni: nota generale sopra, Soci/Non soci ravvicinati come prima, opzioni con etichetta/importo e nota sotto. Separatore sottile soltanto in presenza di entrambe le categorie. Stile Poppins del dettaglio, ritorno a capo naturale, formatter euro esistente. Quote una sola volta nel pannello finale o, per eventi conclusi, nelle informazioni pratiche. Supportate quote uniche senza Soci/Non soci; nessuna dicitura che confonda alternative con supplementi.
- Tema/asset `1.6.35`, plugin/asset amministrativi `1.3.5`. Skill `clean-code-engineer` applicata mantenendo dati/regole nel plugin e un unico componente di presentazione nel tema. README, specifiche e architettura/assunzioni aggiornati. Nessuna dipendenza, migrazione, modifica ai Corsi, dati di prova persistiti o commit automatico.
- Verifiche: lint PHP di plugin e tema, sintassi dei tre script JS e `git diff --check`; evento, CSS tema/admin, JS admin e font HTTP 200. Nove normalizzazioni delle righe, note malformate/multilinea, otto salvataggi intercettati (anche nonce/capability negati, metabox assente, rimozione totale e slash), undici varianti complete del template e rendering nativo del metabox, senza scritture persistenti. Rieseguite nove regressioni di orari/modalità, tredici di prenotazione, dodici valuta, undici WhatsApp e sei salvataggi dei contatti.
- Anteprime generate da WordPress con dati simulati: frontend a 1440/768/390/320 px senza overflow o immagini rotte, font caricati e quote una sola volta; review visiva desktop/mobile, console senza errori. Metabox provato a desktop/320 px: aggiunta/rimozione anche da tastiera, eliminazione totale e nuova aggiunta, focus, ID univoci e valori conservati passando a gratuito e tornando indietro. Sito reale con quote esistenti verificato a 1440/320 px; scorciatoia al pannello ancora funzionante da tastiera. Nessun login amministrativo o apertura di link esterni. Anteprime temporanee, non un salvataggio nell’editor autenticato.

## v1.6.34 — Tutti gli orari dell’Evento affiancati

- Accoglienza, Inizio e l’eventuale Fine sulla stessa riga, sia nel riepilogo iniziale sia nelle informazioni pratiche. Colonne automatiche per gli orari presenti, senza colonne vuote; rimosso il posizionamento forzato di Fine sotto gli altri due valori.
- Conservati distanza orizzontale 24 px, etichette da 14 px/500 e ore da 16 px/600 con numeri tabulari. Griglia esplicitamente isolata dalle colonne generiche della lista di dettagli. Skill `clean-code-engineer` applicata aggiornando soltanto lo stile del componente condiviso, senza duplicare markup o introdurre script.
- Tema/asset `1.6.34`; specifica Evento aggiornata. Plugin, Corsi, dati e contenuti invariati; nessuna scrittura nel database o commit automatico.
- Verifiche: lint PHP del file modificato, sintassi dei due script JS frontend e `git diff --check`; pagina, CSS versionato e font HTTP 200. Nove regressioni WordPress con meta simulati in memoria, comprese modalità e orari mancanti, tutte riuscite.
- Browser: evento con tre orari a 1440/768/390/320 px e con due orari a 1440/320 px. In entrambi i riepiloghi ore allineate, gap 24 px e font invariati; nessun overflow, immagine rotta o errore console nell’evento con tre orari. Review visiva desktop/mobile e scorciatoia di prenotazione da tastiera con focus sulla destinazione conservato. Nessun link esterno aperto né prenotazione inviata.

## v1.6.33 — Link Google Maps nell’Evento e plugin v1.3.4

- Aggiunto meta facoltativo `_fisar_event_maps_url`: stringa singola, non esposta in REST, autorizzazioni esistenti e sanitizzazione WordPress limitata a HTTP/HTTPS. Campo URL `Link Google Maps` nel box `Modalità e luogo`, nella sezione presenza/ibrido; salvataggio con nonce e capability esistenti. Link abbreviati supportati senza risoluzione remota o deduzione della posizione. I valori non stringa nei campi URL vengono scartati senza errori.
- Componente condiviso `event-map-link.php`: `Apri in Google Maps ↗` sotto il luogo nei riepiloghi iniziale e finale. Link nativo nella stessa scheda, freccia decorativa, stile testuale esistente da 15 px. Disponibile anche con il solo pin e negli eventi conclusi; nascosto se il campo è vuoto, non sicuro o l’evento è online.
- Nessuna mappa incorporata, API, chiave Google, nuovo script o dipendenza. Campi e template Corsi invariati. Nessuna posizione di prova salvata negli eventi, nessuna migrazione, login amministrativo o apertura di Google Maps.
- Tema/asset `1.6.33`, plugin `1.3.4`; README, specifiche CPT/Plugin/Evento e architettura aggiornati. Skill `clean-code-engineer` applicata riusando registrazione e salvataggio nativi e condividendo il markup dei due link. Nessun commit automatico.
- Verifiche: lint PHP, sintassi dei tre script JS e `git diff --check`; evento, CSS versionato e font HTTP 200. Dieci URL e input malformati, salvataggi intercettati senza persistenza, registrazione del campo, rendering del metabox e otto varianti del dettaglio (presenza, ibrido, online, assente, non sicuro, abbreviato, solo pin, concluso), tutti riusciti. Rieseguite nove regressioni del luogo, tredici varianti di iscrizione, dodici casi valuta, undici normalizzazioni WhatsApp e sei salvataggi dei contatti.
- Anteprima temporanea generata dal template WordPress con meta simulato e asset locali same-origin: desktop/tablet/mobile a 1440/768/390/320 px, due link corretti, font caricati, nessun overflow, immagine rotta, iframe o errore console; review visiva dei due riepiloghi e focus tastiera visibile senza seguire il link esterno. Controllato anche il sito reale con campo ancora vuoto. Server dell’anteprima arrestato a fine test; WordPress locale rimane disponibile.

## v1.6.32 — Iscrizione e informazioni essenziali dell’Evento

- Omettere la riga `Modalità` nelle informazioni pratiche per gli eventi in presenza; mantenerla per online e ibridi. Luogo, indirizzo, accesso alla piattaforma e fallback del riepilogo invariati, senza modificare i meta salvati.
- Etichette Accoglienza/Inizio/Fine del componente condiviso aumentate da 13 a 14 px in entrambi i riepiloghi. Peso 500, ore da 16 px/600, distanza di 24 px e allineamento accoglienza/inizio conservati.
- Nota flessibile resa più scura con `--color-ink` (`#282332`), nelle due posizioni; testo, dimensione 14 px e condizioni di visualizzazione invariati.
- Titolo finale semplificato da `Quote e prenotazioni` a `Iscrizione`; per eventi senza prenotazione `Partecipazione`, per eventi passati ancora `Evento concluso`. Nessuna modifica a quote, recapiti, canali, note, ID o scorciatoie.
- Tema e asset `1.6.32`, plugin `1.3.3` invariato. README, specifica Evento e tipografia aggiornati. Skill `clean-code-engineer` applicata mantenendo gli stessi componenti e selettori circoscritti agli Eventi. Nessuna dipendenza, script, scrittura nel database o commit automatico.
- Verifiche: lint PHP, sintassi dei due script JS e `git diff --check`; evento, CSS versionato e font HTTP 200. Nove casi di modalità/orari, tredici varianti di iscrizione con titolo verificato per stato, dodici casi valuta, undici normalizzazioni WhatsApp e sei salvataggi intercettati, tutti riusciti senza persistenza.
- Browser: evento giapponese a 1440/768/390/320 px e altre otto combinazioni su ibrido, partecipazione libera, concluso e Corso a 1440/320 px. Modalità ibrida conservata, nuovi titoli corretti, sottoetichette misurate a 14 px, note nel colore scuro e ore allineate. Nessun overflow, immagine rotta o errore console; review visiva desktop/mobile, focus della scorciatoia da tastiera ancora sul pannello. Nessun login o invio di prenotazioni.

## v1.6.31 — Scadenza esplicita e riepiloghi Evento coerenti

- `Prenotazioni entro` e data ora formano una frase continua con spazio esplicito, stesso peso 600 e dimensione (18 px nella hero, 22 px nel pannello finale). Ritorno a capo naturale quando lo spazio non basta, senza riduzione del font o clipping; calendario e `datetime` conservati.
- Nota flessibile chiarita nel plugin `1.3.3`: `Dopo tale termine sarà comunque possibile contattarci per iscriversi, ma non potremo garantire la disponibilità.` Il componente condiviso mantiene la nota dentro entrambi i box; API testuale e strutturata allineate, nessuna nuova regola di chiusura.
- Etichette principali del riepilogo iniziale aumentate da 13 a 16 px; etichette finali uniformate a 16 px e maiuscolo, peso 600 e tracking 0.05 em. Selettori limitati ai figli diretti e ai `dt`: nomi delle sedi, valori e sottoetichette Accoglienza/Inizio/Fine invariati.
- Tema/asset `1.6.31`, plugin `1.3.3`; specifiche Evento, tipografia, plugin e architettura aggiornate. Skill `clean-code-engineer` applicata riusando il componente della scadenza e mantenendo il copy funzionale nel plugin. Nessuna dipendenza, nuovo script, scrittura nel database o commit automatico.
- Verifiche: lint PHP, sintassi dei due script JS e `git diff --check`; HTTP 200 per evento, CSS versionato e font Poppins 600 effettivamente dichiarato nel CSS. Tredici varianti di prenotazione/template, dodici casi valuta, undici normalizzazioni WhatsApp e sei salvataggi intercettati senza persistenza, tutti riusciti con il nuovo testo atteso.
- Browser: sei larghezze 320–1440 px sull’evento giapponese e altre otto combinazioni su gratuito con prenotazione, partecipazione libera, concluso e Corso a 1440/320 px. Etichetta e data misurate alla stessa dimensione in entrambe le posizioni, riepiloghi a 16 px/maiuscolo e Corsi invariati. Nessun overflow, immagine rotta o errore console; review visiva desktop/mobile e ancora provata con tastiera, focus sul pannello. Nessun login o invio di prenotazioni.

## v1.6.30 — Gerarchia delle prenotazioni e plugin v1.3.2

- Scadenza con giorno della settimana, usando la localizzazione WordPress esistente. Nuovo componente di presentazione `event-deadline.php` condiviso: nota sul termine flessibile dentro il box sia nella hero sia nel pannello finale; nessuna nuova regola di chiusura e API testuale del plugin invariata.
- Scorciatoia `Come prenotare` raccolta nel box iniziale delle prenotazioni; fallback autonomo nella hero quando manca una scadenza valida. Conservati un solo pannello finale, focus della destinazione, partecipazione libera ed eventi conclusi.
- `Quota di partecipazione` più grande (20 px/600), quote ravvicinate con colonne allineate a sinistra e gap 12 px; condizioni di partecipazione in grassetto semantico. Nuova sezione `Come prenotare` con titolo da 20 px/600, intestazioni dei canali da 18 px/600 e recapiti selezionabili.
- Pulsanti Eventi `Prenota via WhatsApp` e `Prenota via mail`; il plugin applica le etichette soltanto al prefisso Evento, senza cambiare etichette Corsi, URL, validazione o altri canali.
- Per attivare il pulsante richiesto, completato il solo meta `_fisar_event_whatsapp` dell’evento locale ID 108 (`alla-scoperta-dei-vini-giapponesi`): `http://3357882629` → `+39 3357882629`, link generato `https://wa.me/393357882629`. Nessun altro recapito o contenuto modificato, nessun prefisso dedotto globalmente, nessuna migrazione o messaggio inviato.
- Tema e asset `1.6.30`, plugin `1.3.2`; README, specifica Evento, API del plugin e architettura aggiornati. Skill `clean-code-engineer` applicata mantenendo un componente condiviso e separando presentazione e regole funzionali. Nessuna dipendenza, nuovo script o commit automatico.
- Verifiche: lint PHP, sintassi dei due script JS, `git diff --check`; HTTP 200 per pagina, CSS versionato e font locale. Dodici casi valuta, tredici varianti di prenotazione/template (inclusi giorno, note dentro i box, posizione della scorciatoia, grassetto semantico e condizioni dei canali), undici normalizzazioni WhatsApp e sei salvataggi intercettati senza persistenza, tutti riusciti.
- Browser: evento giapponese a sei larghezze 320–1440 px e altre dodici combinazioni su evento gratuito con prenotazione, partecipazione libera, concluso e Corso a 1440/768/320 px. Nessun overflow, immagine rotta o errore console; Corsi invariati, URL dei pulsanti verificati senza aprire chat/email. Review visiva desktop/mobile e link interno provato con mouse e tastiera: focus sul pannello e Tab successivo su WhatsApp. Nessun login amministrativo.

## v1.6.29 — Unico riquadro Quote e prenotazioni

- Applicata la proposta approvata dopo il confronto UX/information architecture: rimosso il pannello laterale e conservato un unico riquadro finale dopo le informazioni pratiche, prima del Corso collegato. Corpo in una colonna centrale, massimo 48 rem, senza sticky o barre fisse. Contatti affiancati da 38 rem, impilati su mobile.
- Link nativo `Come prenotare ↓` nella hero verso `#event-registration`, con focus sulla destinazione e margine di scorrimento. Riquadro `Quote e prenotazioni`; per partecipazione libera, `Come partecipare`/`Quote e partecipazione`. Eventi conclusi: un solo blocco di stato in fondo, nessun invito a prenotare.
- Quote mostrate una sola volta nel riquadro (nella tabella per gli eventi conclusi); eliminata la duplicazione di quote e scadenza nella tabella immediatamente precedente. Scadenza ancora evidente in hero e riquadro, nota flessibile, recapiti copiabili, pulsanti e condizioni invariati.
- Su richiesta dell’utente, importi numerici senza valuta resi con spazio non separabile e `€`, per esempio `50 €`/`60 €`. Conservati valori già con valuta e testi editoriali come `€ 20 per accompagnatore`, senza euro duplicato o conversione dei dati. Formatter di presentazione nel tema; nessun intervento sul plugin, sui meta o sui contenuti salvati.
- Riusati i componenti esistenti, eliminando le varianti laterale/fondo non più necessarie. Tema e asset `1.6.29`, README, specifica Evento, architettura e assunzioni aggiornati. Nessun nuovo script, dipendenza, scrittura nel database o commit automatico.
- Verifiche: lint PHP, sintassi dei due script JS, `git diff --check`; pagina, CSS versionato e font locale HTTP 200. Dodici casi di valuta e tredici varianti di iscrizione con meta simulati in memoria, API/copy preservati e corpo integrale, escaping e ID univoci verificati. Rieseguite 18 regressioni di orari/luogo, 11 normalizzazioni WhatsApp e 6 salvataggi intercettati senza persistenza.
- Browser: evento giapponese su sei larghezze 320–1440 px; altre 15 combinazioni su eventi gratuito con prenotazione, ibrido, partecipazione libera, concluso e Corso a 1440/768/320 px. Un solo pannello, quote/recapiti leggibili, nessun overflow o immagine rotta e stili Corsi invariati. Review visiva desktop/mobile; link interno provato con mouse e tastiera, focus sulla destinazione e poi sul contatto, console senza errori. Supporto reduced-motion esistente conservato; nessun login o invio di prenotazioni.

## v1.6.28 — Iscrizioni Evento e plugin v1.3.1

- Riquadro d’iscrizione con scadenza in primo piano (calendario decorativo, bordo oro, data Poppins 600 da 22 px), quote e recapiti testuali selezionabili oltre ai pulsanti. Numero WhatsApp, email, telefono e altri canali provengono soltanto dai campi configurati per l’evento.
- Riquadro affiancato alla descrizione su desktop, prima del corpo su mobile, ripetuto dopo le informazioni pratiche e prima dell’eventuale corso collegato. Componenti condivisi per iscrizioni e quote, dati raccolti una volta e ID univoci. Scadenza evidenziata anche nei riepiloghi iniziale e finale. Gli eventi conclusi conservano un solo riquadro di stato, senza inviti a prenotare.
- Il plugin fornisce i dettagli strutturati della prenotazione, mantenendo la precedente API testuale e le regole su gratuità, partecipazione e termine flessibile. Nessuna chiusura automatica dedotta dalla scadenza, nessuna valuta aggiunta alle quote editoriali.
- WhatsApp accetta numeri o URL nei metabox condivisi Eventi/Corsi: testo preservato al salvataggio, chat generata solo con numero internazionale completo, numero estratto dai link diretti noti. Gruppi/canali mostrano l’URL; numeri nazionali, anche se precedentemente salvati come `http://numero`, sono leggibili senza link presunto. Corretto il collegamento malformato nella presentazione dell’evento giapponese; numero privo di prefisso lasciato invariato nel database, da completare editorialmente per avere il pulsante.
- Tema/asset `1.6.28`, plugin `1.3.1`; README, specifica Evento, API del plugin e architettura aggiornati. Nessuna dipendenza, migrazione, scrittura nel database o commit automatico.
- Verifiche: lint PHP, sintassi JS e `git diff --check`; HTTP 200 per pagina, CSS versionato e font locale. Test in memoria: 11 normalizzazioni WhatsApp, 6 salvataggi intercettati senza persistenza, 13 varianti di iscrizione (quote assenti/parziali/gratuità, scadenza assente/non valida/tassativa/flessibile, canali assenti, evento concluso, escaping, ID e corpo integrale), più 18 regressioni di orari e luogo.
- Browser: sei larghezze 320–1440 px per l’evento giapponese e dodici combinazioni di tre altri eventi/Corso a 1440/390/320 px. Nessun overflow, immagine rotta o ID duplicato; orari allineati, link diretti conservati nei dati validi, stili Corsi invariati. Review visiva desktop/mobile, focus da tastiera visibile e console senza errori. Nessun login amministrativo né invio di prenotazioni.

## Etichetta Orario nel dettaglio Evento — 5 ottobre 2026

- Sostituito `Orari` con `Orario` nel riepilogo iniziale e nelle informazioni pratiche, su richiesta dell’utente. Accoglienza, inizio, eventuale fine e layout invariati; versione `1.6.27` conservata, nessun dato o asset modificato.
- Verifiche: lint PHP, `git diff --check` e risposta HTTP locale riuscita con entrambe le nuove etichette presenti. Nessun commit automatico.

## v1.6.27

- Aumentato H4 del corpo Evento da 17 a 18 px (`1.125rem`), sia nel frontend sia nel CSS dell’editor Eventi. Conservati Poppins `500`, interlinea `1.35`, H3 da 20 px e H5–H6 da 17 px. Nessun intervento su contenuti, livelli salvati, Corsi o altri template.
- Tema e asset `1.6.27`, specifiche tipografiche aggiornate; nessun commit automatico.
- Verifiche: lint PHP, sintassi JS e `git diff --check`; HTTP 200 per evento, CSS principale versionato e CSS editor Eventi. Test WordPress in sola lettura del caricamento degli stili per tipo di editor, senza nuove scritture nel database. Browser a 1440/768/390/320 px: tre H4 reali misurati a 18 px, peso 500 e interlinea 24.3 px, nessun overflow o immagine rotta; review visiva desktop e console senza errori. Verifica nell’editor autenticato ancora subordinata all’autorizzazione di accesso già richiesta.

## v1.6.26

- Configurati stili nativi del canvas per gli editor Eventi e Corsi: font locali già presenti nel tema, larghezza di lettura `68ch`, margini dei titoli, elenchi e citazioni coerenti con il corpo delle pagine pubbliche. Nessun cambiamento alla toolbar, ai metabox o ai contenuti salvati.
- Eventi: scala desktop Poppins, corpo 17 px, H2 24 px, H3 20 px, H4–H6 17 px, heading 500 e grassetti 600. Corsi: mantenuto lo stile pubblico attuale, corpo Poppins 18 px e titoli Cormorant con H2 52 px/H3 32 px. Scale fisse nel canvas, senza `vw`, perché l’editor è più stretto del viewport pubblico; frontend responsive invariato.
- CSS aggiunti tramite `add_editor_style` durante `enqueue_block_editor_assets`, soltanto sugli schermi di modifica dei due CPT. Nessun nuovo script, plugin, font remoto o scrittura nel database. Tema `1.6.26`, README e specifiche aggiornati; nessun commit automatico.
- Verifiche completate: lint PHP, sintassi JS, `git diff --check`; HTTP 200 per Evento, Corso e tre CSS editoriali. Test WordPress in sola lettura: tre fogli di stile per ciascuno dei due editor, solo il precedente per News e pagine, nessuna aggiunta negli elenchi amministrativi. Controllate anche le registrazioni native e gli URL base dei CSS.
- Frontend: otto combinazioni di Evento/Corso e larghezze 1440/768/390/320 px; tipografia pubblica conservata, nessun CSS editoriale caricato, overflow o immagine rotta; console senza errori.
- Verifica visiva nell’editor reale ancora da completare: il controllo delle autorizzazioni ha bloccato il login con l’account demo e sono stati segnalati timeout iniziali. Richiesta all’utente l’autorizzazione specifica all’accesso al backend locale; nessun tentativo di aggirare il blocco e nessun contenuto modificato tramite editor.

## v1.6.25

- Aggiunta la via anche al riepilogo iniziale del dettaglio evento, tra il nome della sede in grassetto e la città/provincia. Conservati righe separate e stile v1.6.24, campi mancanti omessi e comportamento degli eventi online/ibridi.
- Nessuna modifica a dati, plugin, tabella finale o CSS; tema e asset `1.6.25`, specifica e architettura aggiornate. Nessun commit automatico.
- Verifiche: lint PHP, sintassi JS e `git diff --check`; HTTP 200 per evento e CSS versionato. Nove casi WordPress in sola lettura per via nel riepilogo, sede parziale o assente, online/ibrido, escaping e conservazione del corpo. Browser a 1440/768/390/320 px: via su riga dedicata, nessun overflow o immagine rotta, console senza errori e review visiva desktop.

## v1.6.24

- Migliorata la gerarchia del luogo nel dettaglio evento: nome della sede in grassetto semantico Poppins `600` da 16 px, città/provincia su una riga distinta nel riepilogo. Nelle informazioni pratiche, nome, via e città/provincia su righe separate con spazio di 4 px, senza concatenazioni.
- Testi lunghi liberi di andare a capo, dati mancanti omessi e fallback all’indirizzo conservato. `Online`, `Anche online`, orari ravvicinati, indirizzo completo e altri dati invariati. Il nome non eredita il maiuscolo e il colore delle etichette del riepilogo.
- Intervento circoscritto a markup e CSS del singolo evento; nessuna modifica al plugin, al database o agli altri template, nessuna dipendenza aggiunta. Tema e asset `1.6.24`, specifica aggiornata; nessun commit automatico.
- Verifiche: lint PHP, sintassi dei due script JS e `git diff --check`; HTTP 200 per pagina evento, CSS versionato e font 600. Diciotto casi WordPress in sola lettura con filtri in memoria: stati/modalità/orari conservati, luogo completo o parziale, dati assenti, fallback, escaping e corpo editoriale integrale.
- Browser desktop/tablet/mobile a 1440/1024/768/390/320 px: nome a peso 600, righe separate, orari ancora allineati e nessun overflow o immagine rotta. Review visiva del riepilogo desktop e dell’indirizzo completo desktop/mobile; console senza errori.

## v1.6.23

- Ravvicinati accoglienza e inizio nel riepilogo e nelle informazioni pratiche: colonne dimensionate sul testo, allineate a sinistra e distanti `1.5rem` (24 px), anziché espanse sull’intera larghezza disponibile. Conservati etichette, valori, allineamento delle ore e disposizione dell’eventuale fine sotto.
- Modifica circoscritta al CSS del dettaglio evento; componente, contenuti e plugin invariati. Tema e asset `1.6.23`, specifiche aggiornate; nessun commit automatico.
- Verifiche: lint PHP, sintassi dei due script JS e `git diff --check`; pagina evento e CSS versionato HTTP 200. Otto combinazioni browser su due eventi con due/tre orari, da 320 a 1440 px: gap effettivo 24 px nelle due viste, ore allineate, fine sotto e nessun overflow. Asset caricati, review visiva desktop e console senza errori.

## v1.6.22

- Nel dettaglio degli eventi futuri, sostituito `Prossimo evento` con `Evento in programma`: la dicitura non implica che sia il primo in ordine cronologico. Conservato `Evento concluso` per gli eventi passati.
- Accoglienza e inizio affiancati nello stesso gruppo `Orari`, sia nel riepilogo iniziale sia nelle informazioni pratiche, anche su mobile: etichette sopra e ore sulla stessa riga, a peso 600 e con numeri tabulari. L’eventuale fine resta sotto quando ci sono tre orari; dati facoltativi mancanti omessi. Un componente condiviso del tema evita divergenze fra le due viste.
- Il riepilogo iniziale mostra il luogo anziché `In presenza`. Per gli eventi online mostra `Modalità: Online`; per quelli ibridi mostra il luogo con `Anche online`, oppure la modalità se manca la sede. Indirizzo completo e modalità restano disponibili nelle informazioni pratiche.
- Nessuna modifica a campi, validazione, query, contenuti editoriali, iscrizioni o relazioni del plugin, nessuna scrittura nel database e nessuna nuova dipendenza. Tema e asset `1.6.22`, specifiche aggiornate; nessun commit automatico.
- Verifiche: lint PHP, sintassi dei due script JS e `git diff --check`; HTTP 200 per home, archivio, dettagli, CSS/JS versionati e font. Test WordPress in sola lettura su sei eventi per conservazione di contenuti, iscrizioni, stati e corsi collegati; nove casi con meta simulati in memoria per presenza, online, ibrido, orari incompleti, sede mancante ed evento passato.
- Browser: 24 combinazioni di quattro eventi reali e sei larghezze da 320 a 1440 px, con accoglienza e inizio allineati in entrambe le viste, nessun overflow o immagine rotta. Review visiva desktop/mobile e delle informazioni pratiche; console senza errori.

## v1.6.21

- Prova richiesta dall’utente: mese per esteso nelle date delle card Eventi di home e archivio, abbreviato soltanto quando non entra su una riga. Giorno della settimana ancora breve; icona, font e `datetime` conservati, data completa per screen reader invariata.
- Formatter del tema esteso con parametro opzionale per il mese, mantenendo il comportamento precedente come default. La card rende la forma estesa e fornisce quella breve al nuovo script nativo `event-dates.js`, caricato solo nelle due viste interessate. Misurazione del testo nello spazio reale rimasto dopo calendario e gap, aggiornata dopo font e ridimensionamento con `ResizeObserver`/resize e callback coalescenti; ripristino della forma estesa allargando la card.
- Senza JavaScript rimane leggibile la data estesa su più righe. Se a forte ingrandimento neppure la forma breve entra, è consentito il ritorno a capo anche con JS, senza clipping o riduzione del font. Nessuna dipendenza, logica di dominio o scrittura nel database; tema `1.6.21`, specifiche e README aggiornati. Nessun commit automatico.
- Verifiche: lint PHP, sintassi di entrambi gli script e `git diff --check`; HTTP 200 per home, archivio, singolo evento, CSS/JS versionati e font. Test WordPress in sola lettura: sei eventi, contenuti integrali, abstract, iscrizioni, stati e corsi collegati conservati; formattazione di tutti i mesi e fallback vuoto/non valido verificati.
- Browser: 14 varianti delle pagine reali fra 320 e 1440 px, inclusi restringimento e riallargamento; 20 varianti di fixture native per 12 mesi più `MER 30 SETTEMBRE 2026`, home/archivio, script assente e testo al 200%. Nessun overflow, clipping o errore console, nomi accessibili e date complete conservati; abbreviazione e ripristino corretti. Review visiva desktop; singolo evento e Carta non caricano il nuovo script. Fixture generate con filtri in memoria, nessun dato editoriale modificato; server temporaneo arrestato a fine test.

## Introduzione aperta dell’archivio Eventi — 5 ottobre 2026

- Applicata la frase scelta dall’utente: `Degustazioni, visite e incontri per scoprire e condividere.` Sostituisce il riferimento limitato al vino, evitando la ripetizione fra conoscere e scoprire.
- Conservati stile, padding, dimensioni dei font, card e query; modifica circoscritta al testo dell’archivio. Specifica aggiornata, tema `1.6.20` invariato; nessuna modifica al database e nessun commit automatico.
- Verifiche: lint PHP, sintassi JS e `git diff --check`; HTTP 200 per archivio, CSS e font. Browser a 1440/768/390/320 px: testo su una riga su desktop/tablet e due su mobile, ritorno a capo naturale, sei card conservate e nessun overflow, immagine rotta o errore console. Review visiva desktop/mobile.

## Introduzione breve dell’archivio Eventi — 5 ottobre 2026

- Accorciata l’introduzione della hero in `Degustazioni, visite in cantina e incontri per conoscere il vino.`, per avere una riga su desktop. Su mobile il testo va naturalmente a capo, senza `nowrap`, clipping o riduzione della tipografia.
- Registrata nella specifica l’approvazione della fascia scura. Modifica limitata al testo del template dell’archivio; stile, padding, card, nomi accessibili, query e altri contenuti invariati. Nessuna modifica al database o nuova dipendenza; tema `1.6.20` invariato e nessun commit automatico.
- Verifiche: lint PHP, sintassi JS e `git diff --check`; HTTP 200 per archivio, CSS e font. Browser a 1440/1024/768/390/320 px: una riga alle prime tre larghezze, due su mobile; font 16–18 px invariato, sei card conservate, nessun overflow, immagine rotta o errore console. Review visiva desktop/mobile.

## v1.6.20

- Su richiesta dell’utente, applicata una prova scura alla sola hero dell’archivio Eventi: fondo bordeaux scuro uniforme del brand, titolo bianco, introduzione chiara e linea oro; nessuna fotografia o nuova dicitura. Conservati padding compatto 32–48 px, Poppins, testo e griglia.
- Selettori circoscritti all’archivio: singolo evento ancora chiaro, Carta fotografica e resto del sito invariati. Nessuna modifica a plugin, dati o funzioni e nessuna nuova dipendenza. Tema e asset `1.6.20`, specifica aggiornata; nessun commit automatico.
- Verifiche: lint PHP, sintassi JS e `git diff --check`; HTTP 200 per archivio, singolo evento, Carta, CSS versionato e font. Browser a 1440/1024/768/390/320 px senza overflow o immagini rotte: sei card e padding invariati, background senza immagine. Contrasti misurati sui colori renderizzati: titolo 16.28:1, introduzione 12.45:1 e linea oro 6.93:1. Review visiva desktop/mobile, singolo evento conservato chiaro e console senza errori.

## Intestazione essenziale dell’archivio Eventi — 5 ottobre 2026

- Rimossa la dicitura `FISAR · Delegazione Castelli di Jesi` sopra il titolo dell’archivio Eventi, su richiesta dell’utente; l’identità resta nel logo/header e nella hero della Carta dei Valori, dove chiarisce l’ambito locale del documento.
- Conservati titolo, introduzione, padding 32–48 px, card, nomi accessibili e query. Modifica circoscritta al template dell’archivio; nessun intervento su plugin, database, CSS o altri template. Tema `1.6.19` invariato, specifica aggiornata; nessun commit automatico.
- Verifiche: lint PHP, sintassi JS e `git diff --check`; HTTP 200 per Eventi, Carta, CSS e font. Browser a 1440/768/390/320 px senza overflow o immagini rotte, sei card e padding conservati, dicitura assente dalla hero Eventi e presente nella Carta; sette capitoli della Carta e console senza errori. Review visiva desktop/mobile.

## v1.6.19

- Ridotto soltanto il padding verticale della hero dell’archivio Eventi da 40–64 px a 32–48 px per lato, con `clamp(2rem, 4vw, 3rem)`. Titolo, testo, accento oro, card e spaziature delle sezioni invariati; la hero del singolo evento conserva i precedenti valori.
- Aggiornati versione degli asset del tema e specifica dell’archivio. Nessuna modifica a plugin, database o contenuti; nessuna nuova dipendenza e nessun commit automatico.
- Verifiche: lint PHP, sintassi JS e `git diff --check`; HTTP 200 per archivio, singolo evento, CSS versionato e font. Browser a 1440/1024/768/390/320 px: padding corretto, sei card conservate, nessun overflow o immagine rotta; review visiva desktop/mobile e console senza errori. Confermato padding di 64 px invariato nel singolo evento su desktop.

## v1.6.18

- Riallineati archivio e dettaglio Eventi allo stile approvato della home e della Carta: heading Poppins Medium, intestazioni più compatte, fondo chiaro caldo, linee oro, bordi sottili e ombre leggere. Identità della Delegazione esplicita nell’intestazione dell’archivio.
- Locandine su bianco, sempre intere con `contain`; cornice quadrata anche nel dettaglio. Archivio con data compatta e icona calendario, data estesa per screen reader, abstract conservati e CTA con titolo completo nel nome accessibile. Tutti i cinque eventi futuri e quello concluso rimangono elencati.
- Corpo del dettaglio e heading editoriali resi coerenti con la Carta, senza cambiare testi o livelli salvati. Informazioni pratiche più leggibili su mobile; riquadro iscrizioni con bordo oro e fondo caldo, in normale flusso senza sticky. Conservati quote, canali, scadenze, posti limitati, note, stati e corsi collegati.
- Intervento di sola presentazione nel tema, con selettori circoscritti a `events-archive` e `single-event` e contesto `archive` nel componente card condiviso. Nessuna modifica al plugin/database o nuova dipendenza; tema `1.6.18`, specifiche e documentazione aggiornate.
- Verifiche: lint PHP, sintassi JS, `git diff --check`; HTTP 200 per pagine, CSS/JS versionati, font e locandina reale. Test WordPress in sola lettura sui sei eventi: contenuto nativo integrale, abstract dell’archivio, iscrizioni, condizioni funzionali e corsi collegati conservati. 36 controlli browser a 1440/1024/832/768/390/320 px senza overflow, immagini rotte o ID duplicati; H1 unico, heading Poppins, locandine intere, nomi accessibili e focus tastiera visibile. Review visiva desktop/tablet/mobile e caricamento di tutte le sei locandine; home e Carta controllate senza regressioni, console senza errori.
- Segnalato all’utente un URL WhatsApp editoriale preesistente non valido nell’evento sui vini giapponesi; dato lasciato invariato perché fuori dal restyling richiesto. Nessun invio ai canali esterni e nessun commit automatico.

## v1.6.17

- Reso coerente il link all’archivio Eventi in homepage: sempre `Tutti gli eventi`, con suffisso `(N)` soltanto quando il totale supera quattro. Posizione indipendente dal numero di eventi: accanto al titolo su desktop, sotto le card su mobile. Un solo collegamento visibile per ogni larghezza.
- Rimossi condizione e stile dedicati alla precedente CTA lunga sotto la griglia; conservati massimo quattro card, griglia, locandine, nomi accessibili, ordine cronologico e query del plugin. Nessuna modifica a dati editoriali o altre sezioni; tema `1.6.17` e documentazione aggiornata.
- Verifiche: lint PHP, sintassi JS e `git diff --check`; HTTP 200 per home, archivio, CSS e font. Test WordPress di rendering da zero a cinque eventi e 18 varianti browser a 1440/768/320 px: etichetta, totale, posizione e unico link visibile corretti, senza overflow. Homepage reale controllata anche a 1024/832/390 px, link all’archivio funzionante e console senza errori; review visiva desktop. Fixture temporanee senza scritture nel database.

## v1.6.16

- Homepage estesa a un massimo di quattro eventi futuri, in ordine cronologico, riutilizzando la query del plugin e il suo `found_posts`. Oltre quattro, collegamento esplicito `Vedi tutti i N eventi in programma` sotto la griglia su desktop e mobile; fino a quattro conservati testo e posizionamento precedenti del link all’archivio.
- Griglia della sola home mantenuta a due colonne da `38rem`, anche alle larghezze intermedie precedentemente influenzate dalle tre colonne degli archivi. Una colonna su mobile; locandine quadrate, dimensioni, fondo bianco, `contain` e nomi accessibili completi invariati. Messaggio esplicito quando non ci sono eventi futuri.
- Corsi, resto della homepage, archivi, pagine interne, dati editoriali e plugin invariati. Nessuna nuova dipendenza o asset; tema `1.6.16` e documentazione aggiornata.
- Verifiche: lint PHP, sintassi JS e `git diff --check`; test WordPress di rendering per 0–6 eventi, numero di card, ordine e identità, totale nei link e CTA accessibili. Varianti browser a 1440/768/320 px senza overflow; homepage reale controllata a 1440/1024/832/768/390/320 px. Review visiva desktop/mobile, test HTTP e caricamento degli asset; nessun errore console. Fixture e filtri temporanei senza modifiche al database, nessun invio a Mailchimp.

## Affinamento della nota — 5 ottobre 2026

- Riformulata la nota della Carta: `Nota bene: questo documento esprime i principi e i valori che guidano la nostra Delegazione Castelli di Jesi e non rappresenta necessariamente quelli della FISAR nazionale.` Incipit e, su successive richieste, `Delegazione Castelli di Jesi` e `non rappresenta necessariamente quelli della FISAR nazionale.` in grassetto semantico. Non aggiunta la sottolineatura, per non richiamare i link; chiarito l’ambito locale senza attribuire i principi alla nazionale o suggerire contrapposizioni.
- Aggiornati il primo blocco nativo del seed e soltanto la nota della pagina locale ID 6, con revisione WordPress preventiva e controllo che il resto del corpo fosse identico. Conservati riquadro, icona, sette capitoli, 16 grassetti originali e tutte le precedenti modifiche; nessun reimport generale o variazione del tema `1.6.15`.
- Verifiche: integrità del corpo e corrispondenza fra seed e contenuto salvato; HTTP 200 e `git diff --check`; controllo della sintassi PHP/JS. Test browser a 1440/768/390/320 px con un’unica nota, incipit a peso `600`, sette capitoli, nessun overflow, immagine rotta o errore console; review visiva desktop/mobile.

## v1.6.15

- Spostata la nota sull’ambito locale della Carta fuori dalla hero, in un riquadro sotto l’immagine e prima del payoff, allineato alla colonna di lettura. Fondo chiaro caldo, bordo oro sottile con lato sinistro più marcato, testo Poppins da 16 px e spaziatura responsive.
- Aggiunta un’icona SVG `info` al sistema del tema, decorativa e non focalizzabile; riquadro `aside` con nome accessibile `Nota sulla Carta dei Valori`, senza semantica di allarme o live region. Rimossi parametro e stili della precedente nota nella hero.
- Testo, blocchi nativi, seed del plugin e database invariati: la nota resta modificabile nell’editor ed è mostrata una sola volta. Payoff, sette capitoli, 16 grassetti, immagine, URL e navigazione conservati; nessuna nuova dipendenza. Tema `1.6.15` e documentazione aggiornata.
- Verifiche: lint PHP, sintassi JS e `git diff --check`; HTTP 200 per Carta, home, Contatti, CSS versionato, foto e font. Test WordPress confermano contenuto identico al seed e fallback conservativi. Browser a 1440/1024/768/390/320 px: nota sotto la hero e prima del payoff, sette capitoli e icone, unico H1, nessun overflow, elemento fuori dal viewport, ID duplicato, immagine rotta o errore console; review visiva desktop/mobile.

## v1.6.14

- Reso esplicito l’ambito locale della Carta: titolo nativo `Carta dei Valori della nostra Delegazione`, identificazione `FISAR · Delegazione Castelli di Jesi` e nota “Il documento si riferisce alla Delegazione Castelli di Jesi, non alla FISAR nazionale.” URL e voce di menu breve invariati.
- Nota conservata come primo blocco Paragrafo `values-scope-note`, modificabile nell’editor e separata dal tema per mostrarla una sola volta nella hero. Payoff originale riportato nel corpo sopra `Chi siamo`; frase sui principi ingrandita a Poppins `500` da 18–24 px e nota a 15 px. Overlay adattato per la leggibilità del titolo più lungo.
- Sostituito il fallback alla foto conviviale della home con il paesaggio di vigneti già incluso nel tema; resta disponibile l’immagine in evidenza nativa della Carta. Nessun nuovo asset o dipendenza, homepage e altre pagine invariate.
- Allineati titolo e nota nel seed del plugin, senza incrementare la versione demo o reimportare gli altri dati. Aggiornata soltanto la pagina locale ID 6, con revisione WordPress preventiva; testo originale del documento byte-identico alla versione precedente, esclusa la nota aggiunta. Tema `1.6.14`; aggiornata la documentazione.
- Verifiche: lint PHP, sintassi JS e `git diff --check`; HTTP 200 per Carta, home, Contatti e asset. Test WordPress per contenuto, fallback conservativo e integrità del documento (sette capitoli e 16 grassetti). Browser a 1440/1024/768/390/320 px: payoff soltanto nel corpo, nota soltanto nella hero, immagine corretta, sette icone, nessun overflow, testo fuori dal viewport, ID duplicato, immagine rotta o errore console; review visiva desktop/mobile.

## v1.6.13

- Arricchita la sola hero della Carta dei Valori con fotografia conviviale già disponibile nella homepage, overlay scuro, nome FISAR oro chiaro, titolo Poppins bianco e linea oro. Precedenza all’immagine in evidenza della Carta, fallback alla home e infine al paesaggio locale; immagine decorativa, priorità alta e `srcset` WordPress nativo.
- Spostato il payoff originale nella hero, in Cormorant `600` da 22–28 px, raggruppando le due frasi senza impedire il wrapping su mobile. Mantenuto l’estratto editoriale sotto il payoff e l’allineamento con la colonna di lettura; overlay più uniforme sotto `48rem` per il contrasto.
- Introdotto il componente `values-hero.php` e una separazione conservativa dei blocchi per mostrare il primo Paragrafo `lead` soltanto nella hero. Nessun cambiamento al database, al seed o al testo: tutti i sette capitoli e i 16 grassetti restano presenti; se l’apertura non corrisponde, il corpo non viene separato. Primo capitolo senza separatore o spazio aggiuntivo rimasto dal payoff spostato.
- Homepage, altri template, contenuti, icone e impaginazione dei capitoli invariati. Nessun nuovo asset, font, dipendenza o servizio esterno. Tema `1.6.13`; aggiornati specifica della Carta, tipografia, architettura, assunzioni e README.
- Verifiche: lint PHP di template, componente e funzioni; sintassi JS e `git diff --check`; HTTP 200 per Carta, foto e CSS versionato. Test WordPress confermano contenuto salvato invariato, testo preservato dalla separazione, nessuna duplicazione del payoff, grassetti e fallback conservati. Browser a 1440/1024/768/390/320 px: sette capitoli e icone, unico H1, un solo payoff nella pagina, nessun overflow, testo fuori dal viewport, ID duplicato, immagine rotta o errore console; review visiva desktop/mobile.

## v1.6.12

- Riallineato lo stile della pagina completa Carta dei Valori alla homepage, su richiesta dell’utente: H1 e sette H2 in Poppins Medium, rispettivamente 32–44 px e 20–24 px, al posto dei grandi titoli editoriali serif. Cormorant conservato per il payoff.
- Intestazione più compatta, colonna di lettura comune da massimo `50rem`, separatori sottili e spaziature regolari fra i capitoli. Icone oro da 28 px allineate ai titoli; paragrafi rientrati nella colonna del testo da `48rem`, a tutta larghezza su mobile. Corpo 16–17 px e grassetti `600`, senza alterare l’enfasi semantica.
- Modifica di sola presentazione, circoscritta alla classe `values-page` nel template standard: contenuto integrale, numerazione, grassetti, ancore, icone, homepage e altre pagine invariati. Nessuna modifica al plugin o al database e nessun nuovo asset o dipendenza. Tema `1.6.12`; aggiornati specifica pagina, tipografia, assunzioni e README.
- Verifiche: lint PHP di template e funzioni, sintassi JS e `git diff --check`; HTTP 200 per Carta, homepage, Contatti, CSS versionato e font Medium. Confronto del contenuto salvato con il seed conferma testo e 16 grassetti invariati. Review visiva completa e mobile; controlli a 1440/1024/768/390/320 px senza overflow, testi fuori dal viewport, ID duplicati, immagini rotte o errori console. Confermati sette capitoli, sette icone e unico H1; skip link da tastiera con focus visibile e destinazione corretta.

## v1.6.11

- Sostituita la versione abbreviata della pagina Carta dei Valori con il testo integrale della bozza Word V2 fornita dall’utente: payoff, sette punti numerati, titoli originali e tutti i 16 grassetti. Conservati anche gli approfondimenti su Slow Food/Slow Wine e i passaggi omessi dal vecchio seed; homepage e documento sorgente invariati.
- Riutilizzate le sei icone SVG della home per i punti 2–7; aggiunto il cuore già disponibile nel tema al punto `Chi siamo`. Icone oro da 28 px, decorative e non focalizzabili; impaginazione editoriale responsive e tipografia delle pagine interne conservate.
- Contenuto salvato in blocchi WordPress nativi Paragrafo/Titolo, modificabili dal backend. Il tema aggiunge gli SVG solo al rendering dei sette H2 identificati dalle ancore, nella sola pagina Carta e nel loop principale; nessun SVG nel database e nessuna dipendenza aggiunta.
- Allineato il seed del plugin tramite `content/carta-dei-valori.html`. Aggiornata soltanto la pagina locale ID 6, mantenendo il testo precedente nelle revisioni WordPress; nessun reimport generale o incremento della versione demo che sovrascriva i contenuti all’avvio. Tema `1.6.11`, specifica della pagina, architettura, assunzioni e README aggiornati.
- Verifiche: confronto automatico del testo e dei grassetti con il DOCX; contenuto nel database identico al seed e sette blocchi Titolo. Test WordPress confermano il rendering delle icone e l’assenza di decorazioni su H3, ancore sconosciute e altre pagine. Lint PHP dei file modificati, sintassi JS e `git diff --check`; HTTP 200 per pagina, home e CSS versionato. Controllo visivo dei sette capitoli e responsive a 1440/1024/768/390/320 px senza overflow, ID duplicati, immagini rotte o errori console.

## v1.6.10

- Resi esplicitamente esemplificativi i riferimenti alle altre bevande nel punto `Curiosità e apertura`, introducendoli con `come`.
- Precisata su richiesta dell’utente la dicitura `Consiglio Direttivo` nel punto `Ognuno può contribuire`.
- Applicato il copy approvato per la sintesi Carta dei Valori in homepage: passione, conoscenza e condivisione nell’apertura; corsi ed eventi per coltivare competenza e professionalità in un ambiente informale; approccio basato su conoscenza e consapevolezza; apertura anche a chi non ha mai frequentato corsi da sommelier; vino come punto di partenza verso cibo e altre bevande, incluse tè e caffè.
- Titolo abbreviato in `Informalità`; testo finale `Seguiamo le regole del servizio e della degustazione, senza eccessivi formalismi.`, evitando ripetizioni con l’introduzione. Conservati icone, layout, CTA e pagina completa della Carta; nessuna modifica al plugin o ai dati WordPress.
- Aggiornata la specifica homepage; tema `1.6.10`.
- Verifiche: lint PHP di template e funzioni, sintassi JS e `git diff --check`; HTTP 200 di homepage, Carta completa e CSS versionato. Controllo visivo desktop/mobile e responsive a 1440/1024/768/390/320 px senza overflow o testi tagliati; sei icone decorative conservate, console senza errori.

## v1.6.9

- Aggiunta su richiesta dell’utente una piccola icona lineare oro a ciascuna delle sei voci della Carta dei Valori in home: germoglio, bussola, scudo, fumetto, persone e lampadina. Riutilizzato il sistema SVG del tema; aggiunte solo bussola, fumetto e lampadina.
- Icone decorative da `1.75rem` nella colonna sinistra, nascoste alle tecnologie assistive e non focalizzabili. Conservati copy, heading, CTA, griglia responsive, sfondo e resto della homepage; nessuna dipendenza o richiesta di rete aggiuntiva.
- Tema `1.6.9` per invalidare la cache; plugin e pagina completa della Carta invariati. Documentata la prova nelle specifiche.
- Verifiche: lint PHP di template e funzioni, sintassi JS e `git diff --check`; HTTP 200 di home, Carta completa e CSS versionato. Browser a 1440/1024/768/390/320 px senza overflow o testo tagliato; sei SVG da 28 px, tutti `aria-hidden` e non focalizzabili, console senza errori. Confronto automatico conferma copy e resto del template invariati.

## v1.6.8

- Riprogettata la fascia Carta dei Valori della homepage dalla bozza Word V2 fornita dall’utente, coerente con la working draft già nel repository: introduzione per il punto 1 e sei sintesi dei punti 2–7, nell’ordine originale.
- Nuovo titolo `La nostra Carta dei Valori`; rimossi `condivisione autentica`, `Percorsi seri` e il richiamo generico al territorio. Esplicitati modo di lavorare dei produttori, curiosità oltre il vino, consapevolezza/moderazione, informalità, accoglienza e contributo di tutti.
- Sostituite le quattro icone/categorie con una lista di H3 e paragrafi a sinistra: Poppins `500`, due colonne da `38rem` e una su mobile. Conservati fondo fotografico, overlay, CTA, ordine delle sezioni e gerarchia accessibile.
- Nessuna modifica alla pagina completa della Carta, al Word sorgente, al plugin o ai dati WordPress. Nessuna dipendenza o asset nuovo; tema `1.6.8`, documentazione aggiornata.
- Verifiche: lint PHP di template e funzioni, sintassi JS, `git diff --check`; HTTP 200 di home, Carta completa, CSS versionato, fondo fotografico e font Medium. Browser a 1440/1280/1024/832/768/608/390/320 px senza overflow o testo tagliato; sei voci con H3 Poppins 500, un solo H1 e ID univoci. Verificati focus visibile e CTA alla Carta, console senza errori; confronto automatico conferma il resto del template homepage invariato. Nessun invio a Mailchimp.

## v1.6.7

- Confermata dall’utente la scelta Poppins Medium per i cinque titoli di sezione della homepage.
- Estesa la prova Poppins `500` ai titoli delle card Eventi, Corsi e News, ai canali, al pannello newsletter e alle quattro voci della fascia Valori. Componenti condivisi uniformi anche negli archivi e nella pagina Seguici; interlinea `1.3` per card, canali e newsletter, dimensioni esistenti conservate e nessun troncamento dei titoli.
- Conservati hero, payoff, heading editoriali delle pagine interne, markup, contenuti e nomi accessibili. Nessun nuovo asset o dipendenza, nessuna modifica al plugin; tema `1.6.7` per invalidare la cache.
- Aggiornate specifica tipografica, homepage, componenti, assunzioni e README.
- Adattata la griglia delle voci Valori al maggiore ingombro di Poppins: quattro colonne da `80rem`, due alle larghezze intermedie e una su mobile; padding compatto su desktop e wrapping di sicurezza, senza ritagli.
- Verifiche: lint PHP e JS, `git diff --check`, HTTP 200 per home, Eventi, Corsi, News, Seguici, CSS versionato e font Medium. Controlli browser home a 1440/1280/1120/1024/768/390/320 px senza overflow o elementi oltre il viewport; archivi e Seguici controllati a 1440 e 390 px. Confermati Poppins 500 nei componenti, H1 interni e payoff invariati, unico H1, ID univoci, CTA complete e ancoraggio newsletter; nessuna immagine rotta o errore console rilevato. Nessun invio a Mailchimp.

## v1.6.6

- Applicata su richiesta dell’utente la prova Poppins Medium ai cinque titoli di sezione della homepage: Eventi, Corsi, News, Valori e Come seguirci. Peso `500`, dimensione responsive 32–40 px a scala base, come nell’anteprima.
- Conservati hero, payoff, card, heading interni alle sezioni, archivi e pagine interne. Regola CSS circoscritta alla homepage, senza nuovi asset, dipendenze o modifiche al plugin; tema `1.6.6` per invalidare la cache degli stili.
- Aggiornate specifica tipografica, homepage, assunzioni e README per distinguere la prova attiva dalla scelta generale del display.
- Verifiche: lint PHP e JavaScript, `git diff --check`, HTTP 200 per homepage, archivio Corsi, CSS versionato e Poppins 500; controllo browser a 1440, 1024, 768, 390 e 320 px senza overflow. Confermati i cinque H2 Poppins 500, hero Poppins 400, payoff/card Cormorant e H1 dell’archivio Corsi invariato; un solo H1 in home, nessun ID duplicato, immagine rotta o errore console rilevato. Nessun invio del form Mailchimp.

## v1.6.5

- Spostata l’intera sezione `Come seguirci`, con quattro canali e form Mailchimp, dopo la Carta dei Valori e prima del footer. Eventi e Corsi seguono direttamente la hero, poi News e Valori.
- Conservati markup interno, copy, stile, ancore newsletter, menu, social e configurazione Mailchimp. Spostamento nel template PHP, senza riordino CSS né modifiche al plugin; tema `1.6.5`.
- Aggiornate specifica homepage, componenti, decisioni e assunzioni.
- Verificati lint PHP e sintassi JS, HTTP 200 di homepage/pagina Seguici/asset, ordine DOM e resa a 1440/1024/768/390/320 px senza overflow. Confermati blocco spostato identico e resto del template invariato, unico H1, nessun ID duplicato, immagini caricate, ancoraggio newsletter e focus visibile sui campi; console senza errori. Nessun invio a Mailchimp.

## v1.6.4

- Rimosse le Quattro Porte dalla homepage senza un blocco sostitutivo: `Come seguirci` segue direttamente la hero. Eventi, Corsi, News, Valori, navigazione e newsletter invariati.
- Eliminati markup e stili responsive esclusivi del componente; conservate le icone condivise del tema e tutta la logica del plugin. Tema `1.6.4` per invalidare la cache degli stili.
- Aggiornate specifiche homepage, percorsi utente, componenti, decisioni e assunzioni; il concept iniziale resta un riferimento visivo storico.
- Verificati lint PHP e sintassi JS, HTTP 200 di homepage, destinazioni e asset, resa a 1440/1024/768/390/320 px senza overflow. Confermati assenza delle Quattro Porte e di spazio residuo fra hero e `Come seguirci`, unico H1, nessun ID duplicato, nomi accessibili completi delle card e immagini caricate dopo lo scorrimento. Menu mobile, Escape e focus visibile verificati; console senza errori. Nessun invio del form Mailchimp.

## v1.6.3

- Rimossa dalla homepage la fascia istituzionale sotto la hero: le Quattro Porte seguono direttamente, senza spazio residuo. Le informazioni su FISAR, qualifica APS e Delegazione restano nelle pagine di `Chi siamo`, i cui contenuti sono invariati.
- Eliminati markup e stili CSS del componente non più utilizzato; aggiornate specifica homepage e assunzioni. Tema `1.6.3` per invalidare la cache degli stili, plugin invariato.
- Verificati lint PHP e sintassi JS, HTTP 200 di homepage, pagine associative e asset, resa a 1440/1024/390/320 px senza overflow o immagini mancanti. Confermati unico H1 e contiguità fra hero e Quattro Porte; console senza errori.

## v1.6.2

- Aggiunta una variante SVG orizzontale per il solo header, con “FISAR” più grande e “CASTELLI DI JESI” maggiormente leggibile rispetto al grappolo, come richiesto dall’utente. Conservati forma e dimensioni del simbolo, famiglie/pesi dei caratteri, colori e tricolore; nessuna rigenerazione AI.
- Rifinita la larghezza di “CASTELLI DI JESI” e del tricolore per condividere i margini di FISAR: stessa larghezza di riferimento, spaziatura più compatta e dimensione della dicitura appena ridotta, senza schiacciare i glifi. Ingombro del logo e scritta FISAR invariati.
- Conservati il logo originale, gli asset brand e il logo del footer. La funzione del tema seleziona la variante nel contesto header e mantiene la precedenza dell’eventuale logo personalizzato WordPress.
- Nessuna modifica CSS all’altezza dell’header: la variante occupa circa 227 px di larghezza a desktop e 164 px su mobile. Hero, menu, plugin e contenuti invariati; versione tema `1.6.2`.
- Verificati lint PHP e sintassi JS, validità XML dell’SVG, HTTP 200 di homepage/asset, caricamento e nome accessibile del logo, footer invariato, resa a 1440/1120/1024/768/390/320 px senza overflow. Verificati menu mobile, sottomenu, Escape e focus visibile; console senza errori.

## v1.6.1

- Allargati payoff e descrizione della hero a un massimo di `34rem` e `40rem`, mantenendo il nome della Delegazione su tre righe. Su desktop entrambi i testi occupano una sola riga e la hero risulta più compatta.
- Raggruppate le due frasi del payoff per favorire l’andata a capo dopo la virgola su mobile; mantenuto il wrapping interno quando lo spazio è insufficiente, senza `nowrap` o interruzioni rigide. Descrizione con `text-wrap: balance`.
- Fotografia, overlay, dimensioni dei font, CTA e altre sezioni invariati. Solo presentazione nel tema, senza nuove dipendenze o modifiche al plugin; versione tema `1.6.1` per invalidare la cache degli asset.
- Verificati lint PHP dei file interessati e sintassi JS, HTTP 200 di homepage/CSS/font, resa e assenza di overflow a 1440, 1024, 768, 540, 390 e 320 px, testo accessibile del payoff completo e console senza errori.

## v1.6

- Sostituito Inter con Poppins `400/500/600/700` per corpo, menu, CTA, metadata e form, anche nella configurazione dell’editor WordPress. Cormorant Garamond resta per titoli editoriali e payoff; due sole famiglie utilizzate.
- Aggiunti quattro WOFF2 Poppins con subset latino e licenza OFL. Preload del solo Regular e del file Cormorant esistente, `font-display: swap`, nessun font o asset remoto a runtime. Gli asset Inter precedenti restano inutilizzati.
- Aggiornata la hero secondo la proposta approvata: H1 “FISAR / Delegazione / Castelli di Jesi” in Poppins Regular, payoff più piccolo e descrizione “Corsi per sommelier, degustazioni e incontri per conoscere il mondo del vino.” CTA Scopri i corsi e Scopri gli eventi; fotografia esistente conservata.
- Aggiunta sotto la hero la fascia che esplicita il nome esteso e la qualifica APS della FISAR nazionale. Nessuna attribuzione giuridica locale e nessuna modifica alle altre sezioni, ai contenuti associativi o alla logica del plugin.
- Aggiornate specifiche tipografiche, homepage, assunzioni e README; tema `1.6.0`, plugin invariato.
- Verificati lint PHP del tema, sintassi JS/JSON, HTTP 200 di pagine, CSS, JS e quattro WOFF2; controlli visuali/overflow a 1440, 1120, 1024, 768, 390 e 320 px. Menu mobile, sottomenu, Escape e focus visibile verificati; unico H1, nessun ID duplicato, nomi accessibili delle card mantenuti, immagini caricate e console senza errori. Controllati anche archivio Corsi e pagina Come seguirci su mobile; nessun form inviato a Mailchimp.

## v1.5

- Portato `Chi siamo` alla prima posizione del menu, con sottomenu La FISAR, La nostra delegazione, Consiglio e incarichi, Carta dei Valori, Statuto e Diventa socio.
- Creata la pagina nazionale La FISAR; riusata la pagina locale mantenendo il testo e rinominandola La nostra delegazione, con redirect 301 dal vecchio `/chi-siamo/`.
- Aggiunte le ancore Consiglio e incarichi e Statuto alla pagina locale, distinguendo Consiglio di Delegazione e quattro categorie di incarichi non elettivi. Nomi, mandato e PDF rimangono contenuti editoriali da fornire.
- Aggiunto un sottomenu responsive con progressive enhancement, focus visibile, Escape, chiusura fuori dal menu e sblocco del menu mobile sui collegamenti ad ancora nella stessa pagina.
- Aggiunto il comando incrementale `wp fisar-cdj demo association`: aggiorna solo pagine associative e navigazione, conserva i testi già presenti e non reimporta Eventi, Corsi, News o social.
- Tema `1.5.0`, plugin e demo `1.3.0`; homepage invariata salvo la navigazione.
- Verificati lint PHP completo e sintassi JavaScript, HTTP 200 delle pagine/asset e redirect 301, menu a 1440/1024/390 px senza overflow, tastiera e collegamento ad ancora nella stessa pagina con chiusura del menu mobile e trasferimento del focus. Il comando incrementale ripetuto non duplica sezioni o voci.

## v1.4.1

- Rinominata la voce di navigazione `Resta aggiornato` in `Seguici` e la relativa pagina/sezione in `Come seguirci`.
- Semplificato lo slug pubblico da `/resta-aggiornato/` a `/seguici/`, mantenendo il reindirizzamento WordPress dal precedente URL.

## v1.4

- Aggiunta in homepage la sezione ad alta visibilità `Resta aggiornato`, con accesso diretto a WhatsApp, Instagram, Facebook e Newsletter.
- Creata la pagina dedicata `Resta aggiornato` e aggiunti i relativi collegamenti a menu principale e footer.
- Integrato il form Mailchimp fornito con markup accessibile, validazione nativa, honeypot anti-bot e senza dipendenze frontend remote.
- Centralizzata nel plugin la configurazione pubblica del form, mantenendo markup e stile nel tema.
- Adeguato il breakpoint del menu e delle card canale per mantenere spaziatura e leggibilità anche a 1024 px.
- Aggiornata la versione del tema a `1.4.0` anche nei metadati WordPress.

## v1.3

- Portati a due gli Eventi mostrati in homepage, con locandine più grandi, fondo bianco e data compatta su una riga.
- Rese quadrate le immagini dei Corsi in homepage e rimosso il relativo abstract dalla card compatta.
- Rafforzati i titoli delle card Eventi, Corsi e News.
- Accorciate le CTA visibili delle card, mantenendo il titolo della risorsa come contesto aggiuntivo per screen reader.

## v1.2

- Allineata la tipografia alla specifica approvata: Cormorant Garamond per titoli/display e Inter per corpo e UI.
- Aggiunti font WOFF2 self-hosted, licenze OFL, `font-display: swap` e preload dei due file indispensabili above-the-fold.
- Uniformati scala, pesi, interlinee e larghezza dei testi editoriali nei componenti frontend e nell'editor WordPress.

## v1.1

- Riallineata la homepage al concept approvato dopo confronto visuale a 1024 px.
- Ridotte altezza e tipografia dell'hero; aggiunta una fotografia demo calda e conviviale.
- Aggiunte icone SVG a social, Quattro Porte, CTA di adesione e fascia Valori.
- Affiancate le sezioni Eventi e Corsi su desktop e rese quadrate le immagini evento demo.
- Compattate News, fascia Valori e footer secondo la gerarchia del mockup.
- Aggiunti due corsi attivi ai dati demo per verificare la composizione completa.
- Verificato il responsive mobile a 390 px, incluso menu, focus e assenza di overflow.

## v0.1

- Creata struttura documentale.
- Aggiunta homepage concept 01.
- Documentate decisioni su header, menu, News, Quattro Porte e CTA.
