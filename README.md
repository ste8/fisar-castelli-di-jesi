# FISAR Castelli di Jesi — sito WordPress V1

Implementazione completa e locale del sito della Delegazione FISAR Castelli di Jesi.

> Il vino come punto di partenza, le persone al centro.

La V1 comprende ambiente Docker, plugin e tema custom, backend editoriale, contenuti demo realistici e frontend responsive/accessibile. La documentazione in `docs/` resta la source of truth.

La homepage mette in primo piano `FISAR Delegazione Castelli di Jesi`. Il tema usa Poppins per corpo, interfaccia e identità nella hero; Poppins Medium è approvato per i titoli delle sezioni della homepage ed è in prova anche per card, canali, newsletter e voci della fascia Valori. La pagina Carta dei Valori usa anch’essa titoli Poppins per coerenza con la home. Cormorant Garamond resta per payoff e heading editoriali delle altre pagine interne; entrambi sono WOFF2 self-hosted senza font remoti a runtime.

## Requisiti

- Docker Desktop o Docker Engine con Docker Compose v2;
- porta locale `8090` libera, oppure una porta alternativa configurata in `docker/.env`.

Non servono PHP, Composer, Node o WordPress installati sul computer.

## Primo avvio

```bash
cp docker/.env.example docker/.env
docker compose --env-file docker/.env -f docker/compose.yaml up -d
```

Il primo avvio scarica le immagini, inizializza MariaDB e WordPress, installa la lingua italiana, attiva plugin e tema, configura i permalink e importa i dati demo. Può richiedere alcuni minuti.

Controlla il risultato:

```bash
docker compose --env-file docker/.env -f docker/compose.yaml ps -a
```

Lo stato atteso è:

- `database`: `Up (healthy)`;
- `wordpress`: `Up (healthy)`;
- `wordpress_cli`: `Exited (0)`; è un processo one-shot, quindi l’uscita con codice zero è corretta.

## Accesso

- sito: [http://localhost:8090](http://localhost:8090)
- backend: [http://localhost:8090/wp-admin/](http://localhost:8090/wp-admin/)
- utente demo: `admin`
- password demo: `admin_local_change_me`

Modifica credenziali, porta e URL in `docker/.env` prima del primo avvio. Il file è ignorato da Git. Se cambi la porta, aggiorna sia `WP_PORT` sia `WORDPRESS_SITE_URL`.

## Comandi quotidiani

Avvio:

```bash
docker compose --env-file docker/.env -f docker/compose.yaml up -d
```

Log:

```bash
docker compose --env-file docker/.env -f docker/compose.yaml logs -f wordpress
```

Arresto senza perdere i dati:

```bash
docker compose --env-file docker/.env -f docker/compose.yaml down
```

Reimport forzato dei dati demo:

```bash
docker compose --env-file docker/.env -f docker/compose.yaml run --rm --no-deps \
  --entrypoint /bin/sh wordpress_cli -c 'wp fisar-cdj demo install --force'
```

Reset completo dell’ambiente locale:

```bash
docker compose --env-file docker/.env -f docker/compose.yaml down -v
docker compose --env-file docker/.env -f docker/compose.yaml up -d
```

Attenzione: `down -v` elimina definitivamente database e media del solo progetto Docker locale.

## Struttura

```text
plugin/fisar-cdj-core/  CPT, meta, relazioni, import calendario, API di dominio, seed demo
theme/fisar-cdj/        template PHP, componenti, CSS, JavaScript e asset ufficiali
docker/                 Compose, variabili d’ambiente e bootstrap WP-CLI
docs/                   specifiche, decisioni, architettura e assunzioni
assets/                 brand guideline, logo originali e mockup approvato
```

Il tema non ricalcola gli stati temporali né le relazioni: usa le funzioni pubbliche del plugin. La descrizione completa è in [`docs/07-development/architecture.md`](docs/07-development/architecture.md); le assunzioni sono in [`docs/07-development/assumptions.md`](docs/07-development/assumptions.md).

## Gestione contenuti

### Eventi

Archivio e dettaglio Eventi riprendono lo stile Poppins della home e della Carta dei Valori: intestazioni compatte, locandine intere su bianco e accenti oro leggeri. Le card dell’archivio conservano gli abstract; il dettaglio mantiene il contenuto completo e presenta informazioni pratiche e iscrizioni in riquadri leggibili. Specifiche in `docs/03-pages/events.md` e `docs/03-pages/event.md`.

Dal menu **Eventi** del backend puoi gestire data e orari, modalità, luogo o piattaforma, partecipazione, gratuità, quote, iscrizioni, deadline, avviso posti limitati e Corso collegato. I campi non pertinenti vengono nascosti in base a modalità, gratuità e richiesta di iscrizione.

Un Evento passa automaticamente tra futuro e concluso confrontando la data evento con la data corrente del sito.

La homepage mostra fino a quattro eventi futuri in ordine cronologico: due card per riga su desktop/tablet e una su mobile, senza ridurre le locandine. Il link all’archivio resta sempre accanto al titolo su desktop e sotto le card su mobile: `Tutti gli eventi`, oppure `Tutti gli eventi (N)` quando il totale supera quattro. L’archivio conserva l’elenco completo e gli eventi passati.

Nelle card Eventi di home e archivio il mese è per esteso quando entra su una riga, abbreviato soltanto quando non basta lo spazio. Il testo per screen reader resta completo; senza JavaScript o a forte ingrandimento è consentito il ritorno a capo. Controllo leggero nel tema, senza dipendenze.

### Corsi

Dal menu **Corsi** puoi gestire Direttore, livello, date, sede, canali e termine di iscrizione, quota, tesseramento, dotazione e calendario. Un Corso è attivo finché la sua data di fine non è precedente a oggi.

Gli Eventi associati dal campo **Corso collegato** compaiono automaticamente nella pagina del Corso; quelli passati sono marcati “(concluso)”.

### Import calendario

Nel box **Calendario lezioni** di un Corso:

1. copia da Excel o Google Sheets le colonne `Data`, `Orario`, `Titolo lezione`, `Relatore`, `Note`;
2. incollale nel campo tabulato;
3. usa **Importa e mostra anteprima** per controllare le righe;
4. salva o aggiorna il Corso.

Sono accettate date `GG/MM/AAAA`, `GG-MM-AAAA`, `GG.MM.AAAA` e `AAAA-MM-GG`. I dati vengono salvati come righe strutturate, non come HTML.

### News, canali e pagine

Le News usano gli articoli WordPress nativi. Homepage, Carta dei Valori, La FISAR, La nostra delegazione, Contatti, Unisciti a noi e Come seguirci sono pagine native modificabili con l’editor.

La Carta dei Valori riporta il testo integrale della bozza V2 in blocchi Paragrafo/Titolo, con titoli Poppins e impaginazione coerente con la homepage. Le icone dei sette capitoli vengono aggiunte dal tema in frontend: conservare le ancore dei titoli descritte in [`docs/03-pages/values.md`](docs/03-pages/values.md), anche se si modifica il testo.

La hero della Carta usa la sua immagine in evidenza o, in mancanza, il paesaggio di vigneti incluso nel tema. Titolo ed estratto nativi identificano la Carta della nostra Delegazione; il primo Paragrafo con classe `values-scope-note` è la nota sull’ambito locale, modificabile nell’editor e mostrata una sola volta nel riquadro informativo sotto la hero, con bordo oro e icona decorativa. Il payoff con classe `lead` resta nel corpo, sopra `Chi siamo`. URL e voce di menu non cambiano.

Il menu `Chi siamo` si gestisce in **Aspetto → Menu**. Consiglio e incarichi e Statuto rimandano alle ancore `consiglio` e `statuto` della pagina La nostra delegazione: conservare questi ID quando si modificano i titoli nell’editor. Completare i nomi ufficiali e il mandato; caricare lo statuto nella Libreria media e aggiungere il collegamento al PDF nella stessa sezione.

Per aggiornare soltanto la struttura associativa su un ambiente demo già installato, senza reimportare gli altri contenuti:

```bash
docker compose -f docker/compose.yaml run --rm --no-deps \
  --entrypoint /bin/sh wordpress_cli -c 'wp fisar-cdj demo association'
```

I canali social sono un menu WordPress dedicato in **Aspetto → Menu**.

La newsletter usa il form Mailchimp fornito: nome, cognome ed email vengono inviati direttamente a Mailchimp e non salvati in WordPress. Il frontend non carica CSS o JavaScript Mailchimp.

## Dati demo

Il bootstrap crea in modo idempotente:

- cinque Eventi futuri e uno concluso;
- modalità in presenza, online e ibrida;
- eventi gratuiti con e senza prenotazione e un evento con posti limitati;
- un Corso attivo con cinque lezioni e un Corso concluso;
- una serata di presentazione collegata al Corso attivo;
- quattro News;
- Carta dei Valori e tutte le pagine istituzionali;
- menu principale, footer e social;
- immagini demo locali in PNG.

Email, telefono, profili social, link di iscrizione, immagini demo e bozze legali sono volutamente riconoscibili come contenuti da sostituire prima della pubblicazione.

## Verifiche eseguite sulla V1

- bootstrap Docker e healthcheck;
- attivazione plugin e tema;
- lint PHP di tutti i file e controllo sintattico JavaScript/JSON;
- CPT, query futuri/conclusi, relazione Evento → Corso e seed idempotente;
- parser TSV del calendario;
- risposte HTTP di homepage, archivi, singoli e pagine istituzionali;
- review visiva desktop e mobile con browser headless;
- review DOM di landmark, unico H1, gerarchia heading, `alt`, nomi accessibili, lingua e ID duplicati;
- verifica dei log PHP e Apache.
