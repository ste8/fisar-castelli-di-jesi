# Come seguirci

Dal v1.6.82 i tre social puntano ai profili e al gruppo forniti dalla Delegazione, tramite il menu comune. WhatsApp usa la CTA `Unisciti al gruppo WhatsApp`, senza cambiare la descrizione approvata o il modulo newsletter. URL in `docs/02-information-architecture/navigation.md`.

## Stile e contenuti — v1.6.76, 11 ottobre 2026

La pagina nativa `/seguici/` rimane autonoma nel menu e nel footer. Titolo `Come seguirci` ed estratto nativi conservati; fascia bordeaux compatta, senza fotografia o eyebrow associativa, H1 Poppins 500 da 32–44 px con accento oro, estratto 16–18 px / 1.7.

Rimossi il titolo `Segui la vita della Delegazione`, l’eyebrow `Scegli uno o più canali` e il paragrafo `WhatsApp, Instagram, Facebook e newsletter raccontano la Delegazione con tempi e formati diversi. Scegli uno o più canali per non perdere le prossime iniziative.`. Nessun titolo sostitutivo: dopo la hero compaiono direttamente i canali.

La sezione ha il nome accessibile `Canali per seguirci`. Le quattro card usano H2 Poppins 500 da 20–24 px, senza saltare H2 dopo la rimozione del titolo introduttivo. Restano griglia responsive, icone decorative, descrizioni condivise e URL dal menu social. La home mantiene heading e stile precedenti.

Modulo newsletter con H2 Poppins 500 da 24–32 px; padding delle sezioni 40–64 px e nessun margine aggiuntivo sopra il pannello. Dal v1.6.77, il solo raccordo tra canali e newsletter ha padding di 24–32 px per lato: distanza totale 48–64 px, anziché 80–128 px. Titolo, testo, campi e configurazione del form restano nel componente comune. Conservati `newsletter-page`, `newsletter-page-title`, ID del form/campi, consenso obbligatorio e link privacy. Nessuna modifica a Mailchimp o invio di prova.

Dal tema `1.6.78`, canali e newsletter condividono il fondo chiaro caldo `--color-tint` (`#f7f3ef`), senza stacco tra le sezioni. Le card restano bianche e il pannello newsletter bordeaux; hero, footer, spaziature e home invariati.

## Gestione editoriale

Rimosso solo il paragrafo dal corpo della pagina ID 86 tramite API WordPress, previo confronto esatto con il contenuto letto e conservazione della revisione precedente. Titolo, estratto, slug, stato e menu invariati. Il contenuto nativo ora è vuoto; se in futuro si aggiunge un’introduzione nell’editor, il template la mostra sopra le card. Nessun wrapper vuoto quando il corpo è assente.

Seed del plugin allineato per le nuove installazioni, senza reimport dei dati esistenti. Tema `1.6.76`, plugin `1.3.25` per la sola revisione del seed; nessuna nuova dipendenza, famiglia di font, logica di iscrizione o configurazione remota.
