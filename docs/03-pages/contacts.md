# Contatti

## Icone dei pannelli — v1.6.80, 12 ottobre 2026

Titoli dei tre pannelli con icona e testo affiancati: busta per `Scrivici`, persona per `Il Delegato`, edificio istituzionale per `Dati dell’associazione`. Stessa dimensione di 24 px, colore bordeaux e distanza di `.65rem`. SVG locali nel renderer condiviso, decorativi (`aria-hidden` e non focalizzabili); nomi accessibili e ID degli H2 conservati. Il testo può andare a capo su schermi stretti, senza forzare `nowrap`.

## Semplificazione — v1.6.79, 12 ottobre 2026

Rimossi il riquadro separato `La sede` e la sottosezione `Dati per la fatturazione`, comprese le note. Indirizzo conservato nei dati dell’associazione e nel footer; `Codice SDI` (`M5UXCR1`) ora subito dopo `Iscrizione RUNTS`, come riga distinta della stessa lista. Questa revisione prevale sulla precedente struttura sotto.

Restano due riquadri, `Scrivici` e `Il Delegato`, affiancati da 608 px e impilati su mobile, senza colonne vuote. Introduzione nativa invariata: rimosso il limite di 768 px del contenitore e quello generico del paragrafo, così usa la larghezza disponibile del corpo. `text-wrap: pretty` limita le parole isolate a fine frase sui browser compatibili, senza `nowrap`, interruzioni forzate o modifiche al database.

## Revisione approvata — 11 ottobre 2026

La pagina `/contatti/` adotta lo stile di Eventi/Corsi/Blog: fascia bordeaux compatta, titolo Poppins con accento oro e riquadri chiari, senza fotografia. Gerarchia:

- **Scrivici:** `info@fisarcastellidijesi.it` e `jesi@fisar.com`, con uguale rilievo e `oppure`; basta scrivere a uno dei due, senza attribuire funzioni diverse.
- **Il Delegato:** Corrado De Michelis, cellulare `3388510934`, link `tel:+393388510934`. Non presunto l’uso di WhatsApp.
- **La sede:** Via dei Faggi, 9 — 60019 Montemarciano (AN). Distinta dai luoghi delle attività indicati nei singoli Eventi/Corsi; nessuna indicazione non verificata di orari, accesso libero o appuntamenti.
- **Dati dell’associazione:** denominazione completa e abbreviata, sede, C.F. `92024530427`, P.IVA `02460560424`, iscrizione RUNTS n. `147380`. Sottosezione fatturazione con SDI `M5UXCR1`, separato dal RUNTS; quest’ultimo non va comunicato per la fatturazione.

Denominazione completa: **Delegazione F.I.S.A.R. Federazione Italiana Sommelier Albergatori Ristoratori Castelli di Jesi A.P.S.** Abbreviazione: **Delegazione F.I.S.A.R. Castelli di Jesi A.P.S.** Dati forniti dall’utente, senza verifica autonoma dei registri.

## Footer

Logo e denominazione abbreviata, sede, entrambe le email come alternative, link `Tutti i contatti`. Dalla revisione v1.6.74, tutto è integrato in un unico footer bianco, senza fascia bordeaux separata: C.F. e P.IVA sotto la sede. La revisione v1.6.75 rimuove dal solo footer copyright, payoff e descrizione `Eventi, corsi e racconti della Delegazione, senza rumore.`, insieme alla riga finale ormai vuota. Denominazione completa, numero del Delegato, SDI e RUNTS soltanto in Contatti. Conservati navigazione, canali social e CTA newsletter. Rimosso il numero dimostrativo e la nota demo dal footer.

Una colonna su mobile, due su tablet, quattro da 1120 px: si evita di stringere eccessivamente indirizzi e CTA. Nessuna nuova mappa incorporata o richiesta esterna.

## Implementazione e contenuti editoriali

Plugin `1.3.24`: `fisar_cdj_get_delegation_details()` è l’unico punto per dati e recapiti condivisi tra footer e Contatti. Configurazione esplicita in `plugin/fisar-cdj-core/src/functions.php`, non nuova schermata amministrativa. Tema `1.6.73`: template nativo `page-contatti.php`, componente `contact-emails.php` condiviso e CSS circoscritto; titoli e impaginazione nel tema, dati nel plugin.

L’introduzione rimane nel contenuto nativo della pagina WordPress, modificabile nell’editor; i dati generati vanno aggiornati nella configurazione comune, non copiati nell’introduzione. Aggiornato solo il corpo dimostrativo di Contatti ID 8 dopo confronto esatto, conservando la revisione precedente. Titolo, slug, estratto, stato e menu invariati; nessun reimport demo. Seed allineato per le nuove installazioni. Le altre pagine, compresa l’informativa privacy editoriale, non vengono riscritte automaticamente.

## Requisiti comuni

- Accessibile.
- Responsive.
- Coerente con la Carta dei Valori.
- Tono umano e non burocratico.
