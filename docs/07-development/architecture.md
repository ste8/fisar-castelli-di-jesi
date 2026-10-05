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

Le pagine La FISAR e La nostra delegazione usano il template standard e l’editor nativo, senza CPT o campi aggiuntivi. Consiglio e Statuto sono ancore editoriali sulla pagina locale. Il plugin mantiene seed e redirect degli URL precedenti; il tema gestisce soltanto aspetto e interazione del menu WordPress a due livelli.

La Carta dei Valori conserva il testo integrale V2 in blocchi WordPress nativi. Il plugin contiene la copia per il seed in `content/carta-dei-valori.html`; il tema decora soltanto i sette H2 riconosciuti dalle loro ancore con il filtro `render_block_core/heading`, circoscritto alla pagina e al loop principale. Nessun SVG viene salvato nei contenuti editoriali e nessuna nuova logica di dominio viene aggiunta al tema.

Il template standard usa il componente `template-parts/values-hero.php` soltanto per la Carta. Il tema separa il primo Paragrafo `values-scope-note` con `parse_blocks`/`serialize_blocks`, mostrandolo una sola volta in un `aside` informativo sotto la hero e applicando i filtri nativi `the_content` al corpo rimanente. Il riquadro e l’icona decorativa `info` sono presentazione del tema, senza SVG nel database o semantica di allarme. Il payoff `lead` rimane nel corpo sopra `Chi siamo`; se il blocco iniziale non corrisponde, il corpo rimane integrale e il riquadro non viene creato. Titolo, estratto e nota sono dati editoriali nativi. L’immagine usa la funzionalità WordPress nativa delle immagini in evidenza, con fallback diretto all’asset di vigneti nel tema, non alla homepage; nessuna duplicazione di logica del plugin.

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
