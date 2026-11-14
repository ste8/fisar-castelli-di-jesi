# Ambiente locale e verifiche

## Avvio

Docker Desktop/Engine con Compose v2. Porta predefinita 8090; core/uploads e database in volumi persistenti, plugin/tema montati read-only dal repository. Versioni in `docker/compose.yaml`: WordPress 7.1.3/PHP 8.3 Apache, MariaDB 11.4, WP-CLI 2.12. Nessuna installazione locale di PHP/WordPress necessaria; Node serve per i test, ImageMagick solo per rigenerare fotografie.

Per l’ambiente già installato:

```sh
docker compose -f docker/compose.yaml up -d database wordpress
docker compose -f docker/compose.yaml ps -a
```

I comandi usano i default di Compose. Se si usa `docker/.env`, aggiungere `--env-file docker/.env` prima di `-f`; non passarlo se il file non esiste. Sito [localhost:8090](http://localhost:8090/), admin [wp-admin](http://localhost:8090/wp-admin/).

Per una nuova installazione, copiare `docker/.env.example` in `docker/.env`, scegliere credenziali locali e avviare lo stack completo:

```sh
cp docker/.env.example docker/.env
docker compose --env-file docker/.env -f docker/compose.yaml up -d
```

Il servizio `wordpress_cli` esegue bootstrap, lingua italiana, fuso, attivazioni, permalink e seed demo. Uscita `Exited (0)` attesa; WordPress/database devono essere healthy. Le credenziali demo di un ambiente nuovo non sono quelle già cambiate dall’utente sull’installazione esistente e non vanno portate su Aruba. `.env` è ignorato da Git.

## Uso quotidiano

```sh
docker compose -f docker/compose.yaml logs --tail 80 wordpress
docker compose -f docker/compose.yaml down
```

`down` ferma senza eliminare i volumi. **Non usare `down -v` per aggiornare o risolvere un problema generico:** elimina database e media locali. Analogamente `wp fisar-cdj demo install --force` può sovrascrivere i contenuti demo: richiede intenzione esplicita e backup, non è parte del deploy o di una prova grafica.

WP-CLI senza eseguire il bootstrap del servizio:

```sh
docker compose -f docker/compose.yaml run --rm --no-deps \
  --entrypoint wp wordpress_cli option get home
```

Il tag Docker non aggiorna da solo il core nel volume persistente. Per aggiornare: backup completo, aggiornamento nativo WordPress/WP-CLI, verifica schema/checksum e flussi principali, poi allineamento del tag Compose; mai reset dei volumi o reimport per aggiornare. Non alterare la password dell’utente durante le verifiche.

## Verifiche proporzionate

```sh
git diff --check
node tests/hero-collage.test.cjs
node tests/follow-motion.test.cjs
node --experimental-vm-modules tests/hero-wine.test.cjs
node --experimental-vm-modules tests/training-journey.test.cjs
node tests/editor-excerpt.test.cjs
node tests/event-whatsapp-message.test.cjs
node assets/photography/sito-home/rebuild.mjs --check
node --check theme/fisar-cdj/assets/js/hero-collage.js
docker compose -f docker/compose.yaml exec -T wordpress \
  php -l wp-content/themes/fisar-cdj/front-page.php
curl -I http://localhost:8090/
```

Il test `hero-collage.test.cjs` verifica ingresso e sostituzione delle foto native, aria dei gruppi, pause/ripresa, interruzioni e decodifica tardiva, gruppo singolo e fallback senza Web Animations. Il test `hero-wine.test.cjs` verifica presentazione persistente senza nascondere il canvas nei replay, movimento del mouse senza uscire dal liquido, assenza di riavvii durante oscillazione/pausa, touch, movimento ridotto e fallback su errore. Nel browser verificare anche il caricamento dinamico del vino, pausa, frame statico a riposo e assenza del runtime sotto 64rem; il calice deve restare visibile se WebGL non è disponibile.

Eseguire lint PHP/JS dei file effettivamente cambiati. Dopo modifiche frontend controllare Home e pagine coinvolte a desktop/tablet/mobile: immagini/font caricati, nessun overflow, CTA complete, focus, tastiera, movimento ridotto e fallback senza JS se pertinente. Per modifiche ai dati confrontare ciò che deve restare invariato; non inviare moduli newsletter o iscrizioni senza richiesta.

I test del carosello sono unitari senza dipendenze: timer, breakpoint, focus, tastiera, visibilità e preferenza di movimento ridotto. Non sostituiscono una verifica browser. Uno screenshot da solo non dimostra la pausa o il cambio automatico.

Il test d’integrazione del ritrovo Eventi usa WordPress locale e crea/rimuove soltanto eventi di prova dedicati:

```sh
docker compose -f docker/compose.yaml run --rm --no-deps \
  -v "$PWD/tests:/tmp/fisar-tests:ro" --entrypoint wp wordpress_cli \
  --exec='define("WP_ENVIRONMENT_TYPE", "local");' \
  eval-file /tmp/fisar-tests/event-meeting.test.php
```

Verifica eventi ordinari, solo ritrovo, entrambi, sanitizzazione, autorizzazioni/nonce, salvataggi da editor precedenti, svuotamento esplicito, online/ibridi e card nei diversi contesti. L’argomento esplicito `fixtures` conserva i tre eventi per il collaudo browser: rimuoverli al termine usando gli ID restituiti. Non eseguire su Aruba.

Con lo stesso comando, `eval-file /tmp/fisar-tests/editor-workflow.test.php` verifica gruppi, date, quote, calendario, descrizione nativa e riquadri WhatsApp con zero/uno/più contatti, senza riscrivere contenuti esistenti. Crea e rimuove tre bozze dedicate (Evento, Corso, Articolo); `fixtures` le conserva per il collaudo nell’interfaccia. Controllare inserimento e svuotamento della descrizione, aggiunta/rimozione dei contatti e refresh, poi rimuovere solo quelle bozze.

`eval-file /tmp/fisar-tests/event-whatsapp-message.test.php` verifica esportazione WhatsApp, titolo maiuscolo Unicode e sezioni compatte, ordine dei riquadri, quote personalizzate, ritrovo/evento, stati d’iscrizione, riservatezza link online, nonce/autorizzazioni e assenza di scritture durante la generazione. Verifica anche che le date trascorse siano ignorate nel solo messaggio, senza cambiare gli stati pubblici. Rimuove le proprie bozze; `fixtures` conserva i due contenuti creati (Evento e Articolo usato per negare il tipo sbagliato) per il collaudo browser. Il test JavaScript copre anteprima sicura, copia, riapertura, avviso essenziale senza istruzioni nascoste nella descrizione accessibile, modifiche non salvate di documento/campi/note/contatti, esito del salvataggio nativo, autosave, errori e modifiche durante il salvataggio. Nel browser salvare solo la bozza dedicata per verificare il ritorno allo stato pronto senza refresh.

`eval-file /tmp/fisar-tests/event-reports.test.php` con lo stesso comando WP-CLI verifica i racconti degli Eventi: salvataggi con nonce/autorizzazioni, campi omessi preservati, nessuna modifica al testo o all’Evento, esclusione dei contenuti non pubblici, più racconti, cambio Evento, svuotamento e diciture social. Crea/rimuove solo fixture locali; `fixtures` le conserva e stampa ID/URL per il controllo desktop/mobile. Non eseguire su Aruba.

`eval-file /tmp/fisar-tests/course-reports.test.php` verifica anche più Corsi per articolo, senza duplicati, e la coesistenza con un Evento; protegge i salvataggi parziali da editor precedenti, controlla riattribuzione/svuotamento, privacy e ordine cronologico dei racconti. Crea/rimuove solo fixture locali; `fixtures` le conserva e stampa gli ID da rimuovere dopo il collaudo browser. Il campo corso usa una riga di metadati intera per relazione, senza ricerche ambigue su array serializzati. Non eseguire su Aruba.

`eval-file /tmp/fisar-tests/blog-pagination.test.php` con lo stesso comando WP-CLI verifica 25 articoli temporanei su tre pagine (12/12/1), ordine completo senza duplicati, collegamenti avanti/indietro e indipendenza da ricerca/query secondarie. Rimuove sempre i propri articoli al termine, anche in caso di errore; non prevede fixture persistenti. Non eseguire su Aruba.

`eval-file /tmp/fisar-tests/event-pagination.test.php` verifica gli elenchi indipendenti di eventi conclusi (12/12/1) e futuri (12/1), inclusione di oggi tra i futuri, esclusione di bozze/eventi senza data, ordine stabile a parità di data e collegamenti con parametro/ancora corretti. Controlla anche le chiamate API senza limite e l’assenza del pannello per una sola pagina. Crea e rimuove sempre solo i propri eventi temporanei; non eseguire su Aruba.

`eval-file /tmp/fisar-tests/archive-filters.test.php` verifica anno/mese, ricerca combinata in titolo/corpo (escluso l’estratto), categorie native e discendenti, Altro come complemento, input malformati, risultati vuoti, conteggi e copertura paginata senza duplicati. Controlla nomi pubblici semplificati e alias precedenti (precedenza dei nuovi anche se vuoti, nuove paginazioni senza prefissi), conservazione dei filtri negli URL, query secondarie invariate e date Eventi indipendenti dalle date di pubblicazione. Il rendering dell’archivio verifica che il form sia solo nei conclusi, che i prossimi rimangano non filtrati e che il conteggio totale sia sotto il form anche senza criteri e che il reset conservi la dimensione pagina; gli anni della sezione conclusi escludono quelli contenenti solo eventi futuri. Crea/rimuove sempre i propri articoli/eventi/termini locali; termini già presenti sono riusati senza modificarli. Verifica anche whitelist delle dimensioni 12/24/36/48, rifiuto di limiti arbitrari e conteggio totale indipendente dalla dimensione. Nessuna fixture persistente, non eseguire su Aruba.

`node tests/archive-filters.test.cjs` verifica il miglioramento automatico senza dipendenze: focus, debounce, composizione IME, richieste obsolete, URL/cronologia, ripristino, reset/paginazione parziali con gestione focus/scorrimento, retry dopo errore senza navigazione completa e GET nativo solo in assenza di supporto. Verifica il ritorno con Indietro ai vecchi URL e l’emissione dei soli nomi semplificati dopo una modifica. Verifica che reset durante una ricerca annulli e ignori la risposta obsoleta e che link degli articoli, click modificati, download e nuove schede non vengano intercettati. `node tests/event-dates.test.cjs` verifica l’adattamento delle date anche dopo aggiornamenti parziali, partendo da una lista vuota e rimuovendo gli osservatori delle card sostituite. Collaudare i form a 1280/1024/768/390/320 px, con ricerca automatica e criteri attivi.

`eval-file /tmp/fisar-tests/event-calendar.test.php` verifica compatibilità di una giornata, periodi in corso/conclusi e date distinte, stato fino all’ultima giornata, ordinamento per prossima/ultima data, filtri che attraversano mesi/anni e rispetto dei vuoti, conteggi/paginazione senza duplicati, relazioni Corso, messaggio WhatsApp completo e senza scritture, salvataggi protetti e compatibilità con editor precedenti. Calendari non validi preservano i dati salvati; date e note vengono ordinate insieme. `fixtures` conserva tre esempi solo locali e restituisce ID/URL per controllare editor, aggiunta/rimozione, validazione, salvataggio/refresh e responsive. Rimuovere solo questi esempi al termine; non eseguire su Aruba.

## Foto della hero

Sorgenti, inventario, selezione e derivati in `assets/photography/sito-home/`; istruzioni nel suo [README](../assets/photography/sito-home/README.md). Nessuna dipendenza da mockup o server di prova. Il sito legge invece la galleria della Home nel proprio WordPress.

L’import locale esplicito accetta un manifest, la cartella WebP e l’impronta SHA-256 del contenuto Home letto immediatamente prima. Esempio dalla radice, sostituendo `SHA256_HOME_VERIFICATO`:

```sh
docker compose -f docker/compose.yaml run --rm --no-deps \
  -v "$PWD/assets/photography/sito-home:/tmp/fisar-hero:ro" \
  --entrypoint wp wordpress_cli --exec='define("WP_ENVIRONMENT_TYPE", "local");' \
  eval-file wp-content/plugins/fisar-cdj-core/tools/import-home-collage.php \
  /tmp/fisar-hero/selection.json /tmp/fisar-hero/optimized SHA256_HOME_VERIFICATO
```

Prima conservare la Home e verificare la selezione; lo script sostituisce la galleria, non aggiunge immagini arbitrariamente a quella corrente. La guardia si ferma se il contenuto è diverso. I media sono riusati e un secondo import identico non scrive. L’ambiente locale va esplicitato anche nel processo CLI, che non eredita necessariamente le costanti del servizio web. **Non eseguire su Aruba** e non trasferire il database locale per aggiornare i collage.

## Backup e dati privati

Prima di operazioni sui dati salvare database e file necessari, con verifica d’integrità e procedura di ripristino. I backup vanno in `.local-deployment/`, ignorata da Git, con permessi privati; mai nei documenti versionati o nella directory pubblica di un server. Un export WXR contiene contenuti/riferimenti, non sostituisce backup SQL e media.

Per lavori solo di codice/documentazione non modificare contenuti, utenti o volumi. Conservare le modifiche già presenti nel worktree; niente commit automatici. Le istruzioni di rilascio sono in [Pubblicazione Aruba](deployment-aruba.md).

`eval-file /tmp/fisar-tests/course-archive.test.php` verifica solo in WordPress locale le edizioni con fine sconosciuta, il solo anno senza date inventate, l’esclusione delle bozze, la chiusura di iscrizioni/offerte, l’ordine e il limite delle query e il salvataggio nativo con campi omessi. Crea e rimuove soltanto le proprie fixture. Non eseguire su Aruba.

`eval-file /tmp/fisar-tests/course-filters.test.php`, con il comando locale sopra, verifica anno dell’edizione anche senza date esatte, priorità inizio/anno/fine, ricerca titolo/corpo/città/sede/indirizzo (termini combinati, esclusioni, escaping LIKE), tipologia implicita Sommelier, città con apostrofi, input malformati, criteri combinati, conteggi, copertura paginata 12/12/1 e 24/1, menu dei soli conclusi pubblicati e isolamento dei corsi attivi. Verifica il template senza mese, reset e URL paginati con tutti i criteri. Crea/rimuove solo le proprie fixture; non eseguire su Aruba. Il test JavaScript condiviso copre anche selezioni, reset, paginazione e Indietro per tipologia/città.

`eval-file /tmp/fisar-tests/course-cancellation.test.php` verifica l’annullamento reversibile con salvataggi autorizzati e nonce, conservazione del flag con editor precedenti e dei dati nel form completo, inclusione nell’archivio e nelle ricerche pubbliche con conteggi e paginazione coerenti, filtri anno/città, amministrazione ancora consultabile, chiusura di iscrizioni/offerte anche con date future e dicitura neutra In archivio. Il flag non diventa un metadato REST pubblico e non modifica lo stato di pubblicazione o l’accesso al singolo URL. Crea/rimuove soltanto le proprie fixture nel locale; non eseguire su Aruba.

## Anteprime social

`eval-file /tmp/fisar-tests/social-metadata.test.php`, con il comando WP-CLI locale documentato sopra, verifica creazione e successive modifiche di un Evento, precedenza della copertina e del riassunto nativo, fallback istituzionale, elenchi/Unicode e escaping degli attributi. Verifica l’esclusione di gallerie, shortcode, blocchi dinamici, campi pratici riservati, bozze, privati, programmati, password, anteprime e ricerche. Controlla Home e URL degli archivi filtrati. Crea e rimuove solo fixture locali e una propria immagine temporanea; non eseguire su Aruba.

La prova locale del percorso animato è a `http://localhost:8090/diventare-sommelier/#tre-livelli`. `training-journey.test.cjs` verifica caricamento solo in viewport, riproduzione unica/ripetizione esplicita, scheda nascosta, movimento ridotto, uscita durante caricamento e fallback su errore. Nel browser controllare disegno progressivo dei tre simboli, stato finale fermo, pulsante Rivedi e impaginazione a 390 px. La stessa pagina è pubblicata su Aruba dal 14 novembre 2026, accessibile dal solo URL `/anteprima/diventare-sommelier/`.

Come seguirci: `http://localhost:8090/#follow-title`. Le icone sono complete al caricamento. Passare sulle card o raggiungere i link da tastiera per gli effetti specifici. `follow-motion.test.cjs` verifica assenza di ingresso automatico, quattro effetti finiti, touch, tastiera, movimento ridotto, pulizia su scheda nascosta e protezione da promesse annullate. Verificare nel browser tutti e quattro i canali e il comportamento su telefono; non inviare la newsletter per il collaudo.
