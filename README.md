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

Gli eventi in programma hanno un unico riquadro finale `Iscrizione` (titolo semplificato dal v1.6.32), dopo le informazioni pratiche: quote, scadenza evidenziata e recapiti testuali oltre ai pulsanti. `Come prenotare` nella hero porta direttamente al riquadro; per gli eventi senza prenotazione, `Come partecipare` e pannello `Partecipazione`. Corpo in una colonna centrale, senza sidebar. La scadenza resta anche nel riepilogo iniziale, mentre le quote compaiono una sola volta; gli importi numerici senza valuta mostrano `€`, senza alterare i dati salvati o duplicare la valuta già presente. La scadenza include il giorno della settimana e l’eventuale nota flessibile dentro entrambi i box; la scorciatoia è raccolta nel box iniziale. Quote, condizioni e modalità di prenotazione hanno una gerarchia più evidente, con pulsanti `Prenota via WhatsApp`/`Prenota via mail`. Le informazioni pratiche omettono la modalità per gli eventi in presenza, conservandola per online e ibridi. Per WhatsApp negli Eventi basta il numero italiano, con o senza `+39`: il link alla chat aggiunge il prefisso quando manca. Nessuna modifica automatica ai dati esistenti.

Gli editor di Eventi e Corsi hanno stili del canvas dedicati: stessi font locali, gerarchia dei titoli Poppins, spaziature e larghezza di lettura delle rispettive pagine, alla scala desktop (H2 24 px, H3 20 px, H4 18 px). Non è un’anteprima del template completo; calendari, riepiloghi e iscrizioni restano generati dal frontend. Nessuna modifica ai contenuti salvati o agli editor delle altre pagine.

Dal plugin `1.3.14`, **Breve descrizione** è il primo campo nel box **Dettagli dell’evento** o **Dettagli del corso**, insieme agli altri dati. È lo stesso Riassunto nativo di WordPress, senza un secondo campo da compilare nella barra laterale. Compare nelle card dell’archivio e sotto il titolo del dettaglio; se vuoto, l’archivio ricava un estratto dalla descrizione completa e il dettaglio non mostra il sottotitolo. Le card compatte della homepage continuano a ometterlo. I testi esistenti sono conservati.

Dal menu **Eventi** del backend puoi gestire data e orari, modalità, luogo o piattaforma, partecipazione, gratuità, quote, iscrizioni, deadline, avviso posti limitati e Corso collegato. I campi non pertinenti vengono nascosti in base a modalità, gratuità e richiesta di iscrizione.

Nel box **Partecipazione e costi**, oltre alle quote Soci/Non soci, puoi compilare una **Nota generale sulle quote** e aggiungere altre opzioni con **Aggiungi quota**: etichetta, importo e nota facoltativa. Per esempio `Menu senza vini`, con una nota che chiarisca cosa comprende e per chi vale. Le righe senza etichetta o importo sono ignorate; `0` è un importo valido. Per una quota unica puoi lasciare vuoti Soci/Non soci e usare una voce personalizzata. Un importo numerico mostra automaticamente `€`; per un supplemento specificare esplicitamente etichetta e valuta, per esempio `Supplemento …` / `+10 €`. Non viene calcolato né sommato alcun importo. `Evento gratuito` nasconde tutte le quote e le relative note, senza cancellarle. Senza JavaScript rimane una riga vuota compilabile: salva per aggiungerne un’altra, oppure svuota etichetta e importo per rimuoverla. Gli eventi già inseriti non richiedono migrazioni.

Un Evento passa automaticamente tra futuro e concluso confrontando la data evento con la data corrente del sito.

Dal plugin `1.3.10`, con **Iscrizione richiesta** e **Tipo di termine data prenotazione → Tassativo**, una scadenza valida superata mostra **Iscrizioni chiuse** nei due riepiloghi dell’Evento e nasconde canali, pulsanti e inviti a prenotare. Le quote restano consultabili. La data indicata è inclusa: chiusura dal giorno successivo nel fuso WordPress. Il termine **Flessibile** non chiude automaticamente; i dati salvati non vengono cancellati. Corsi invariati.

Dal plugin `1.3.11`, nello stesso box **Iscrizioni**, con **Iscrizione richiesta** attivo, il menu **Disponibilità dell’evento** offre **Ordinaria (non sold-out)**, **Sold-out** e **Sold-out con lista d’attesa**. Sold-out nasconde i canali; la lista d’attesa riusa tutti i contatti già inseriti, con etichette dedicate e avviso che la richiesta non garantisce la partecipazione. Lo stato compare nel dettaglio e nelle card home/archivio. Evento concluso e termine tassativo superato prevalgono sulla lista d’attesa. Tornando a Ordinaria si ripristina il comportamento usuale, senza cancellare contatti o quote. Le richieste sono gestite manualmente tramite i canali: nessuna lista viene salvata nel sito.

Dal tema `1.6.44`, il sold-out è evidenziato da una fascia sotto la locandina, senza coprirla, nelle card e nel dettaglio. Con lista d’attesa la fascia lo specifica; senza lista d’attesa non viene mostrata la scadenza delle prenotazioni, che resta salvata nell’editor.

Nel box **Modalità e luogo** puoi compilare il campo facoltativo **Link Google Maps**, incollando il collegamento condiviso da Maps (anche abbreviato, completo di `https://`). Per presenza e ibrido compare `Apri in Google Maps` nei riepiloghi del luogo. Il campo non incorpora una mappa, non richiede una chiave API e non viene compilato automaticamente dall’indirizzo; se vuoto, il sito resta invariato.

Dal plugin `1.3.12`, per Eventi online e ibridi nello stesso box trovi **Mostra le informazioni per partecipare online**, disattivato per default anche per gli eventi esistenti. Piattaforma e link restano compilabili e salvati, ma sono pubblicati solo attivando questa casella e salvando l’evento. I canali per prenotare non cambiano. Non è un invio automatico agli iscritti né una pubblicazione programmata: se vuoi riservare l’accesso agli iscritti, tieni la casella disattivata e comunica loro il link separatamente, senza inserirlo nel testo pubblico dell’evento. Negli eventi conclusi non viene mostrato il link di accesso.

La homepage mostra fino a quattro eventi futuri in ordine cronologico: due card per riga su desktop/tablet e una su mobile, senza ridurre le locandine. Il link all’archivio resta sempre accanto al titolo su desktop e sotto le card su mobile: `Tutti gli eventi`, oppure `Tutti gli eventi (N)` quando il totale supera quattro. L’archivio conserva l’elenco completo e gli eventi passati.

Nelle card Eventi di home e archivio il mese è per esteso quando entra su una riga, abbreviato soltanto quando non basta lo spazio. Il testo per screen reader resta completo; senza JavaScript o a forte ingrandimento è consentito il ritorno a capo. Controllo leggero nel tema, senza dipendenze.

Dal plugin `1.3.9`, nel box **Iscrizioni** degli Eventi trovi **Contatti WhatsApp per le prenotazioni**: ogni voce ha **Nominativo (facoltativo)** e **Numero WhatsApp**, con pulsanti **Aggiungi contatto WhatsApp** e **Rimuovi contatto**. Il numero esistente compare automaticamente come prima voce. Le righe senza numero sono ignorate; nomi e numeri inseriti saranno pubblici nel dettaglio evento, con un pulsante per destinatario. Puoi scrivere `335 1234567` oppure `+39 335 1234567`: entrambi generano la stessa chat, senza duplicare il prefisso. Spazi, parentesi e trattini sono accettati; per numeri esteri indica il prefisso internazionale. Il dato salvato resta quello inserito e i vecchi link rimangono compatibili. Senza JavaScript compila la riga vuota e salva per aggiungerne un’altra, oppure svuota il numero per rimuoverla. Dal plugin `1.3.13` lo stesso elenco è disponibile anche nei Corsi, come descritto sotto.

### Corsi

Dal plugin `1.3.19` / tema `1.6.60`, nel box **Quota e dotazione** puoi attivare **Corso in offerta** e compilare **Fine offerta (facoltativa)**. La fascia sotto la locandina compare in home, elenco e dettaglio: **In offerta**, oppure **In offerta — Fino al [data]**. La data è inclusa; dal giorno successivo il banner sparisce, senza cancellare i dati. Senza data disattiva manualmente la spunta. Sold-out, lista d’attesa, iscrizioni chiuse e corso concluso nascondono il banner. Importi e condizioni restano nel campo **Quota di partecipazione** e non vengono modificati automaticamente; il termine d’iscrizione è separato. Corsi esistenti non attivati automaticamente.

Dal menu **Corsi** puoi gestire Direttore, livello, date, sede, canali e termine di iscrizione, quota, tesseramento, dotazione e calendario. Un Corso con data di fine compilata è attivo finché questa non è precedente a oggi; questo stato è distinto dalla possibilità di iscriversi.

Dal plugin `1.3.13`, il box **Sede** include **Link Google Maps**, facoltativo. Nel box **Iscrizioni** trovi **Disponibilità del corso** (Ordinaria, Sold-out, Sold-out con lista d’attesa), **Mostra avviso posti limitati** e più contatti WhatsApp, ciascuno con nominativo facoltativo e numero con o senza `+39`. Il contatto precedente resta disponibile senza migrazioni; il nuovo elenco viene salvato soltanto quando aggiorni il corso. Numero e mail restano leggibili accanto ai pulsanti.

**Tipo di termine data iscrizione → Tassativo** chiude le iscrizioni dal giorno successivo alla scadenza nel fuso WordPress, anche se il corso è ancora attivo. Con **Flessibile** i contatti rimangono disponibili e compare la nota sulla disponibilità non garantita. Il termine tassativo superato prevale sulla lista d’attesa; un corso concluso non accetta iscrizioni. Sold-out senza lista nasconde i canali e la data di scadenza, senza cancellarli. La lista d’attesa usa gli stessi contatti e non memorizza richieste nel sito.

Tema `1.6.48`: home, archivio e dettaglio sono allineati agli Eventi, con Poppins, immagini quadrate non ritagliate e stati visibili. Nel dettaglio c’è un solo pannello finale **Iscrizione**, raggiungibile dall’alto; quota, tesseramento e dotazione restano distinti. La quota conserva l’editor libero per condizioni personalizzate: `€` viene aggiunto solo a un importo numerico isolato. La home conserva fino a tre corsi attivi e mostra il totale nel link **Tutti i corsi (N)** se sono di più.

Dal tema `1.6.56` / plugin `1.3.17`, il pannello e la scorciatoia ordinaria del dettaglio Corsi diventano **Informazioni e iscrizioni**: invito a contattarci per conoscere meglio il corso e sapere come iscriversi, pulsanti **Contattaci via WhatsApp** / **Contattaci via mail**. Non è un’iscrizione automatica. Scadenze e disponibilità conservano le regole precedenti; Eventi invariati.

Nell’archivio Corsi, le card mostrano la data di inizio e il nome della **Sede**, con ripiego sulla città se non compilata. La data di fine non compare nelle card dell’elenco, ma resta nell’editor e nel dettaglio e continua a determinare lo stato del corso. Homepage invariata.

Dal tema `1.6.50`, le card standard dell’archivio separano **Corso Sommelier** da **1° livello · Città (Provincia)**, senza ripetere il livello sopra. Sede con pin decorativo `📍`; quando la sede manca, la città è già nella riga del livello e non viene duplicata. Le card Eventi di home e archivio mostrano `📍 Sede · Città (Provincia)`; dal tema `1.6.55` anche gli eventi solo online mostrano `📍 Online`. Provincia omessa se vuota.

Dal tema `1.6.51`, livello e città sono più evidenti di `Corso Sommelier`, che diventa una piccola introduzione; provincia più discreta accanto alla città. Solo card automatiche dell’archivio, senza modificare home, dettaglio, titoli personalizzati o dati salvati.

Dal plugin `1.3.15`, nel box **Dettagli del corso** scegli **Titolo del corso → Automatico / Personalizzato**. Automatico genera il titolo nativo WordPress dai campi Livello, Città e Provincia, anche se lasci il titolo vuoto, e lo ricompone al salvataggio se cambi questi dati. Personalizzato conserva il titolo libero WordPress. Anteprima nel metabox; modificare i campi in Automatico aggiorna anche il titolo nell’editor. I corsi esistenti con livello valido usano la card strutturata senza migrazioni: il titolo nel database resta quello attuale finché non salvi in Automatico. I corsi senza livello valido restano personalizzati. Per mantenere un nome particolare scegli Personalizzato; gli URL esistenti non vengono rinominati. La città non viene ricavata dal testo del titolo: controlla il campo Città.

Gli Eventi associati dal campo **Corso collegato** compaiono automaticamente nella pagina del Corso; quelli passati sono marcati “(concluso)”.

### Import calendario

Nel box **Calendario lezioni** di un Corso:

1. copia da Excel o Google Sheets le colonne `Numero lezione`, `Data`, `Orario`, `Titolo lezione`, `Relatore`, `Note`;
2. incollale nel campo tabulato;
3. usa **Importa e mostra anteprima** per controllare le righe;
4. salva o aggiorna il Corso.

Sono accettate date `GG/MM/AAAA`, `GG-MM-AAAA`, `GG.MM.AAAA` e `AAAA-MM-GG`. I dati vengono salvati come righe strutturate, non come HTML.

Dal plugin `1.3.20`, **Numero lezione** è il primo campo, manuale e facoltativo (per esempio `1`, `03`, `3 bis`); è anche la prima colonna pubblica. Nessuna rinumerazione automatica: le righe seguono data e orario. I calendari esistenti e gli import nel precedente formato senza numero sono conservati, con numero vuoto (`—` sul sito). Dopo un’anteprima riuscita il testo incollato viene svuotato: puoi modificare le righe e i numeri prima di salvare, senza che il testo originale li sovrascriva.

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
