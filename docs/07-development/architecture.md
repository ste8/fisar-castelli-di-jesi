# Architettura V1

## Sintesi

La V1 usa WordPress nativo, un plugin di dominio e un tema classico custom. Docker Compose orchestra WordPress, MariaDB e un container WP-CLI one-shot che installa il sito, attiva plugin e tema e carica i dati demo in modo idempotente.

```text
Browser
  -> WordPress + tema fisar-cdj (presentazione)
       -> API pubblica del plugin fisar-cdj-core
            -> CPT, post meta, relazioni, regole temporali e import calendario
                 -> MariaDB

Docker Compose
  -> database (persistente)
  -> wordpress (core persistente, plugin/tema bind-mounted)
  -> wordpress_cli (bootstrap e dati demo, termina dopo l'avvio)
```

## Responsabilità

- `plugin/fisar-cdj-core/`: registra CPT e meta, gestisce metabox, sanitizzazione, relazione Evento → Corso, stati attivo/concluso, query di dominio, parsing TSV e dati demo.
- `theme/fisar-cdj/`: HTML semantico, template, componenti, design token, asset ufficiali, responsive e interazioni progressive-enhancement.
- `docker/`: stack locale e bootstrap riproducibile; nessuna logica applicativa.
- `docs/`: specifiche e decisioni, inclusa questa descrizione dell'implementazione.

Il tema usa funzioni pubbliche del plugin per le regole di stato e le query: non ricalcola date, relazioni o copy funzionale.

La homepage richiede quattro eventi tramite `fisar_cdj_get_upcoming_events(4)` e usa `WP_Query::found_posts` della stessa query per il collegamento con il totale quando ci sono ulteriori eventi. Limite, disposizione delle card e testo del link sono presentazione del tema; criteri di selezione e ordinamento rimangono nel plugin. Nessuna seconda query di conteggio o nuova configurazione editoriale.

Le pagine La FISAR e La nostra delegazione usano il template standard e l’editor nativo, senza CPT o campi aggiuntivi. Consiglio e Statuto sono ancore editoriali sulla pagina locale. Il plugin mantiene seed e redirect degli URL precedenti; il tema gestisce soltanto aspetto e interazione del menu WordPress a due livelli.

La Carta dei Valori conserva il testo integrale V2 in blocchi WordPress nativi. Il plugin contiene la copia per il seed in `content/carta-dei-valori.html`; il tema decora soltanto i sette H2 riconosciuti dalle loro ancore con il filtro `render_block_core/heading`, circoscritto alla pagina e al loop principale. Nessun SVG viene salvato nei contenuti editoriali e nessuna nuova logica di dominio viene aggiunta al tema.

Il template standard usa il componente `template-parts/values-hero.php` soltanto per la Carta. Il tema separa il primo Paragrafo `values-scope-note` con `parse_blocks`/`serialize_blocks`, mostrandolo una sola volta in un `aside` informativo sotto la hero e applicando i filtri nativi `the_content` al corpo rimanente. Il riquadro e l’icona decorativa `info` sono presentazione del tema, senza SVG nel database o semantica di allarme. Il payoff `lead` rimane nel corpo sopra `Chi siamo`; se il blocco iniziale non corrisponde, il corpo rimane integrale e il riquadro non viene creato. Titolo, estratto e nota sono dati editoriali nativi. L’immagine usa la funzionalità WordPress nativa delle immagini in evidenza, con fallback diretto all’asset di vigneti nel tema, non alla homepage; nessuna duplicazione di logica del plugin.

Il restyling dell’archivio e del dettaglio Eventi usa selettori circoscritti a `events-archive` e `single-event`. La card condivisa riceve il contesto `archive` per la data compatta accessibile, senza eliminare l’estratto come avviene in home. Query, contenuto WordPress, canali di iscrizione e relazioni del plugin restano invariati; nessuna scrittura nel database o nuova dipendenza.

Le date adattive sono progressive enhancement del tema: il formatter PHP produce le due etichette tramite la localizzazione WordPress, la card rende la forma estesa e il testo screen-reader invariato. `assets/js/event-dates.js`, caricato soltanto in home e nell’archivio Eventi, misura la larghezza del testo e abbrevia il mese solo se necessario, con aggiornamenti coalescenti tramite `requestAnimationFrame`, `ResizeObserver`, resize e caricamento dei font. Nessuna regola temporale, query o dato viene modificato; senza JS la forma estesa resta leggibile su più righe.

Il componente `template-parts/event-schedule.php` presenta gli stessi orari in due punti del singolo evento, evitando divergenze fra riepilogo e tabella. Riceve soltanto la raccolta di etichette e orari già letti dal template; campi, validazione e stato restano nel plugin. Sede e indirizzo completo, dal v1.6.25 presenti sia in hero sia nella tabella, sono presentazione degli stessi meta, senza nuove relazioni o query.

## Stili editoriali nel backend

Dal v1.6.26 il tema aggiunge `editor-content.css` e la variante `editor-event.css` oppure `editor-course.css` con `add_editor_style` durante `enqueue_block_editor_assets`, solo sugli schermi di modifica dei due CPT. WordPress legge i file locali e ne porta CSS e URL base nel canvas, anche iframed; `main.css` conserva font e stili generali già registrati. Nessun nuovo script, dato o API amministrativa. Le varianti rispecchiano le scale desktop dei template rispettivi; non caricano stili nel frontend o negli editor di pagine/News. Le future modifiche alla tipografia del corpo dei template devono aggiornare la variante corrispondente.

## Dipendenze

- WordPress 7.1.0 con PHP 8.3 (immagine ufficiale Apache).
- MariaDB 11.4 LTS.
- WP-CLI 2.12 per il bootstrap.
- Nessun framework frontend, page builder, libreria JavaScript, font remoto o plugin di campi esterno.
- La newsletter invia i dati direttamente al form Mailchimp tramite HTTPS: configurazione e identificativi pubblici appartengono al plugin, mentre markup e presentazione restano nel tema. Non sono usate API key né librerie Mailchimp a runtime.

## Alternative considerate

- ACF o framework custom fields: esclusi, perché i metabox nativi coprono i requisiti con meno dipendenze.
- Tema a blocchi completo: escluso per la V1; i template PHP classici rendono più esplicito il contratto con il plugin e riducono la complessità editoriale iniziale.
- Dati demo in dump SQL: esclusi; il comando WP-CLI idempotente è leggibile, versionabile e indipendente dagli ID del database.

## Rischi e mitigazioni

- Le immagini reali della Delegazione non sono ancora fornite: fallback illustrati locali e immagine in evidenza della homepage sostituibile dal backend.
- I profili social reali non sono ancora forniti: gli URL demo restano sostituibili dal menu WordPress dedicato. Per la newsletter è invece autorevole il form Mailchimp consegnato il 23 settembre 2026.
- Privacy e cookie policy demo non sono consulenza legale: sono marcate come bozze da sostituire prima della pubblicazione.
