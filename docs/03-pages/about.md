# Chi siamo

## Stile comune — tema v1.6.84, 14 ottobre 2026

La FISAR, La nostra delegazione (incluse le ancore Consiglio e incarichi / Statuto) e Diventa socio (`/unisciti-a-noi/`) adottano lo stile delle pagine Eventi/Corsi/Blog:

- fascia titolo bordeaux compatta, senza fotografia e senza la dicitura ridondante `FISAR Castelli di Jesi` sopra il titolo; titolo ed estratto nativi conservati;
- H1 Poppins 500 da 32–44 px con accento oro; estratto e introduzione Poppins 400 da 16–18 px;
- corpo 16–17 px / 1.7, colonna centrale massimo 48rem e spazio sopra/sotto da 40–64 px;
- H2 Poppins 500 da 20–24 px, H3 20 px, H4 18 px, H5/H6 17 px e grassetti 600;
- capitoli separati da un sottile bordo chiaro con spaziature contenute, senza aggiungere pannelli o nuove informazioni.

Il template standard assegna `.association-page` soltanto alle tre pagine elencate e riusa i selettori comuni; nessun nuovo template, script, font o campo editoriale. Carta dei Valori mantiene la sua hero fotografica scura, nota, icone e payoff già approvati. Menu, link, ancore, contenuti e struttura associativa restano invariati; nessuna scrittura nel database o migrazione.

## La FISAR — contenuti approvati, 14 ottobre 2026

Testo completo in `plugin/fisar-cdj-core/content/la-fisar.html`, mantenuto anche come seed dei nuovi ambienti. La pagina pubblicata resta nativa e modificabile nell’editor a blocchi: nessun testo generato dal tema o sincronizzazione automatica sui contenuti esistenti.

Sottotitolo (estratto nativo), affinamento del 14 ottobre: `Federazione Italiana Sommelier Albergatori Ristoratori (APS)`, senza punto finale. Aggiornati solo l’estratto locale e la definizione del seed; corpo della pagina e stile invariati.

- Identità nazionale: nome esteso, associazione di promozione sociale (APS), senza scopo di lucro, nascita nel 1972, oltre 70 delegazioni e oltre 15.000 soci.
- Formazione: tre livelli, destinatari appassionati/professionisti, C&D e tre esempi di formazione superiore; unico rimando didattico alla pagina nazionale dei corsi.
- Collaborazione FISAR–Slow Food per Slow Wine dal 2014; paragrafo successivo esplicitamente riferito alla nostra Delegazione Castelli di Jesi e alla sua scelta della guida. Link testuale alla Carta dei Valori locale, senza pulsante dedicato e senza duplicare `riferimento`.
- Altre attività: degustazioni, visite, incontri, FISAR in Rosa e rivista il Sommelier.
- Chiusura con due link distinti: sito nazionale FISAR per l’associazione in generale, pagina La nostra delegazione per storia e persone locali.

Fonti consultate per la stesura: [Statuto nazionale](https://www.fisar.org/associazione/fisar-aps/statuto/), [presentazione nazionale](https://www.fisar.org/associazione/fisar-aps/chi-siamo/), [corsi di base](https://www.fisar.org/formazione/base/i-corsi/), [Comunicazione e Degustazione](https://www.fisar.org/corsi/comunicazione-degustazione/) e Carta dei Valori locale. Lo statuto cita esplicitamente l’enogastronomia. Le fonti di verifica restano nella documentazione, non diventano ulteriori link nel testo pubblicato. Numeri della rete da verificare periodicamente.

Aggiornamento locale circoscritto alla pagina La FISAR ID 92, tramite API WordPress e revisione recuperabile. Titolo, estratto, URL, menu, tema e contenuti della pagina La nostra delegazione invariati; nessun reimport demo né incremento della versione demo.

## Struttura approvata — 4 ottobre 2026

La voce principale `Chi siamo` raccoglie due pagine native WordPress:

- **La FISAR**: identità nazionale, nome esteso Federazione Italiana Sommelier Albergatori Ristoratori APS, cultura del vino, formazione e rete territoriale. Collegamento al sito nazionale per gli approfondimenti ufficiali.
- **La nostra delegazione**: presentazione locale, territorio e persone, conservando il testo esistente.

La pagina della Delegazione contiene anche queste sezioni, raggiungibili direttamente dal menu:

### Consiglio e incarichi — `#consiglio`

Due gruppi distinti, senza presentare gli incarichi non elettivi come componenti del Consiglio:

- Consiglio di Delegazione: Delegato, consiglieri e periodo del mandato;
- Incarichi non elettivi: Responsabile Eventi, Responsabile Servizi, Direttori di Corso, Organizzazione Corsi e Didattica.

I nomi ufficiali non sono ancora forniti. I demo indicano esplicitamente i contenuti da completare, senza inventare persone o mandati.

### Statuto — `#statuto`

Breve contesto e download del PDF ufficiale, da caricare nella Libreria media. Il PDF non è ancora disponibile: nessun download fittizio. Indicare formato e dimensione nel nome del futuro collegamento. La voce `Statuto` rimane nel menu e porta alla sezione, evitando una pagina dedicata quasi vuota.

Carta dei Valori e Diventa socio rimandano alle pagine già esistenti, senza duplicarne il contenuto. Tutti i testi e le ancore rimangono modificabili nell’editor nativo.

## Requisiti comuni

- Accessibile.
- Responsive.
- Coerente con la Carta dei Valori.
- Tono umano e non burocratico.
