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

Dal tema `1.6.46`, `event-location.php` condivide la presentazione del Luogo tra hero e tabella: Online oppure sede fisica con etichetta Anche online per gli ibridi. Riceve i dati raccolti dal template, non introduce campi o regole di accesso; la pubblicazione di piattaforma/link resta nell’API del plugin. Nessuna riga Modalità duplicata, nessun dato modificato.

Dal tema `1.6.28`, `event-registration.php` è riusato a lato/inizio e dopo le informazioni pratiche degli eventi in programma, mentre `event-fees.php` condivide le quote in tre punti. Il template raccoglie i dati una sola volta. Il plugin `1.3.1` fornisce i dettagli strutturati della prenotazione, preservando l’API testuale, e ricava i riferimenti WhatsApp dai canali esistenti; gratuità, copy funzionale e termine flessibile non sono duplicati nel tema. Il metabox WhatsApp condiviso Eventi/Corsi accetta e salva testo (numero o URL), senza conversione dei numeri in indirizzi HTTP. Nessuna nuova dipendenza, scrittura o migrazione dei dati esistenti.

Il successivo riscontro v1.6.29 elimina il pannello laterale e conserva un’unica destinazione finale `event-registration`, raggiungibile dal link nativo nella hero. Corpo in una colonna centrale; quote una sola volta, scadenza nella hero e nel pannello. `event-registration.php` mantiene tutta la presentazione esistente, senza varianti laterale/fondo. `fisar_cdj_theme_format_event_fee()` aggiunge `€` ai soli importi numerici senza valuta, senza cambiare testi completi, meta o regole del plugin. Nessun JavaScript aggiuntivo; `tabindex=-1` permette il focus della destinazione senza aggiungere un passaggio alla sequenza Tab ordinaria.

Dal tema `1.6.30`, `event-deadline.php` condivide la presentazione della scadenza nelle due posizioni: giorno della settimana aggiunto dal formatter del tema, nota flessibile del plugin dentro il box e scorciatoia nella riga della hero. Nessuna duplicazione delle regole del termine; API testuale precedente preservata. Il plugin `1.3.2` aggiorna solo le etichette WhatsApp/email degli Eventi, senza cambiare Corsi, normalizzazione o salvataggio. La sola correzione editoriale autorizzata nei dati locali è il prefisso internazionale del contatto WhatsApp dell’evento ID 108, documentata nel changelog; nessuna migrazione globale.

Il v1.6.31 mantiene lo stesso componente della scadenza, presentando etichetta e data in linea con dimensione/peso identici e ritorno a capo naturale. Uniforma solo le etichette principali dei due riepiloghi Eventi, senza applicare il maiuscolo ai valori annidati. La nota flessibile esplicita `contattarci per iscriversi` nel plugin `1.3.3`, così API strutturata e testuale usano lo stesso testo; regole e dati non cambiano.

Dal tema `1.6.33` e plugin `1.3.4`, il link facoltativo alla posizione Maps è un meta dell’Evento (`_fisar_event_maps_url`): registrazione, autorizzazioni, metabox e sanitizzazione nel plugin. Il tema riusa `event-map-link.php` per la sola presentazione nei due riepiloghi del luogo, sopprimendo il link per gli eventi online. Nessuna geocodifica, iframe, integrazione remota a runtime o duplicazione del campo nel tema; nessuna migrazione dei dati esistenti.

## Stili editoriali nel backend

Dal tema `1.6.35` e plugin `1.3.5`, quote personalizzate e nota generale appartengono al plugin: meta privati, sanitizzazione centralizzata `fisar_cdj_sanitize_event_fee_options()`, metabox e API `fisar_cdj_get_event_fees()`. Il tema raccoglie questa API una volta e riusa `event-fees.php` per la presentazione, anche negli eventi conclusi; il formatter valuta resta nel tema. Il piccolo repeater estende lo script amministrativo esistente, senza framework o librerie; nessun JavaScript frontend aggiuntivo. Campi Soci/Non soci e dati già salvati restano compatibili, nessuna migrazione. Gratuità e validità delle righe non sono ricalcolate nel tema.

Dal v1.6.26 il tema aggiunge `editor-content.css` e la variante `editor-event.css` oppure `editor-course.css` con `add_editor_style` durante `enqueue_block_editor_assets`, solo sugli schermi di modifica dei due CPT. WordPress legge i file locali e ne porta CSS e URL base nel canvas, anche iframed; `main.css` conserva font e stili generali già registrati. Nessun nuovo script, dato o API amministrativa. Le varianti rispecchiano le scale desktop dei template rispettivi; non caricano stili nel frontend o negli editor di pagine/News. Le future modifiche alla tipografia del corpo dei template devono aggiornare la variante corrispondente.

## Chiusura iscrizioni Eventi

Dal plugin `1.3.12`, la visibilità dei dati online appartiene al dominio: meta booleano privato e API `fisar_cdj_get_event_online_access()`, default falso, modalità online/ibrido e accesso non pubblicato negli eventi conclusi. Il tema `1.6.45` non legge più direttamente piattaforma/URL e non ricalcola questa regola. Marker amministrativo e protezioni native preservano l’opzione in POST di editor precedenti. Nessun provider, controllo accessi o automazione aggiunto; le informazioni restano salvate, ma non emesse nell’HTML quando nascoste. I canali di prenotazione sono indipendenti.

Estensione plugin `1.3.11`: enum editoriale di disponibilità anziché due flag indipendenti (alternativa esclusa per evitare sold-out/lista d’attesa incoerenti). Un’unica API determina lo stato effettivo con precedenza del termine tassativo; meta e sanitizzazione/salvataggio appartengono al plugin, mentre dettaglio e badge delle card appartengono al tema `1.6.43`. Canali riusati, nessun sistema di liste o nuovo provider. Assenza del meta equivalente alla disponibilità ordinaria; POST senza nuovo campo preserva il dato. Un checkbox sold-out più un secondo checkbox lista d’attesa avrebbe richiesto gestire combinazioni invalide; l’enum è più semplice da modificare e verificare. Costi/dipendenze invariati, letture meta cache native e nessuna migrazione; limite deliberato: gestione delle richieste manuale.

Dal plugin `1.3.10` lo stato derivato `fisar_cdj_is_event_registration_closed()` confronta una data valida tassativa con il giorno corrente nel fuso WordPress, solo con prenotazione richiesta. La regola è riusata dai dettagli e dall’API canali, evitando calcoli nel tema. Nessun flag persistito o cron: lo stato è ricalcolato a ogni richiesta. Un’eventuale cache HTML in produzione dovrà scadere/essere invalidata dopo il cambio di giorno. Il tema `1.6.42` riusa `event-deadline.php` per i due avvisi e adatta solo presentazione/scorciatoia; quote conservate, inviti/note operative nascosti. Stato evento concluso prioritario; Corsi invariati.

## Contatti WhatsApp degli Eventi

Plugin v1.3.9: la normalizzazione condivisa riceve un codice paese di default facoltativo; solo i canali Eventi passano `39`. Il prefisso è applicato al link dei numeri nazionali, non ai meta o al testo visualizzato. Editor semplificato in numero `tel`, senza nuova logica nel tema. URL legacy e parametri preservati; Corsi e chiamate senza default invariati, nessuna migrazione.

Contatti WhatsApp Eventi v1.6.40/plugin v1.3.8: il plugin registra e sanitizza l’elenco privato, gestisce editor/salvataggio protetto e fallback di sola lettura dal contatto legacy; la API dei canali espone un destinatario per voce e il nominativo. Il tema aggiunge soltanto la riga del nome e il suffisso accessibile al pulsante. Nessuna nuova dipendenza, migrazione o modifica al modello dei Corsi. Un elenco salvato vuoto è distinto dall’assenza del nuovo meta, per evitare la ricomparsa di contatti eliminati.

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
