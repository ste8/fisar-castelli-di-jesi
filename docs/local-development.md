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
node assets/photography/sito-home/rebuild.mjs --check
node --check theme/fisar-cdj/assets/js/hero-collage.js
docker compose -f docker/compose.yaml exec -T wordpress \
  php -l wp-content/themes/fisar-cdj/front-page.php
curl -I http://localhost:8090/
```

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
