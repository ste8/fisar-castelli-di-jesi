# Chi siamo

## Introduzione, indice e sezione valori — tema v1.6.86, 14 ottobre 2026

Applicate le restanti proposte approvate, conservando il sottotitolo scelto e la parola `luogo`:

- Introduzione: `Siamo la Delegazione FISAR Castelli di Jesi: un luogo in cui imparare, incontrarsi e condividere la cultura del vino, in un ambiente informale e accogliente.`
- Nota: `La nostra delegazione nasce nel 2010 come associazione senza scopo di lucro. Scopri la nostra storia →`. Non attribuisce la qualifica APS al 2010.
- `In questa pagina` diventa H2 con le stesse dimensioni, colore e accento oro dei titoli principali, senza piccolo maiuscolo. Landmark e nome accessibile dell’indice conservati.
- Prima voce `I nostri valori`, a `#valori`; sezione H2 subito dopo l’indice, prima del Consiglio. Breve paragrafo `La nostra Carta dei Valori raccoglie i principi che guidano ogni nostra attività e scelta.` e link `Leggi la Carta dei Valori della nostra delegazione →` al documento locale. Tolta la precedente nota prima dell’indice.

Sei sezioni principali: Valori, Consiglio, Incarichi, Squadra, Statuto e Storia. Blocchi WordPress nativi e quattro frammenti del seed, con Valori nell’introduzione; URL portabile già gestito dal plugin. CSS del titolo dell’indice riusa la regola dei titoli delle sezioni. Consiglio, incarichi, Squadra, Statuto e storia finale invariati; nessuna riscrittura della Carta, nuova dipendenza o reimport. Questa specifica supera le collocazioni e i testi precedenti sotto riportati.

## Sottotitolo della delegazione — 14 ottobre 2026

Estratto nativo approvato: `FISAR Castelli di Jesi APS · Passione e cultura del vino dal 2010`, senza punto finale. Aggiornati solo estratto della pagina locale e copia del seed. Corpo, indice, note e altre sezioni invariati; le ulteriori proposte su introduzione, indice e sezione valori non sono ancora applicate.

## Introduzione essenziale e Carta dei Valori — 14 ottobre 2026

Rimossa la sezione La delegazione (`#delegazione`) con Una Delegazione aperta, Il territorio e Le persone. Dopo la nota sul 2010 e il suo link alla storia, un paragrafo nativo: `La Carta dei Valori della nostra delegazione raccoglie i principi che guidano ogni nostra attività e scelta.` Il titolo del documento è il link alla Carta locale, senza pulsante o nuova sezione. Introduzione iniziale e indice conservati; seguono cinque sezioni: Consiglio, Incarichi, Squadra, Statuto, Storia. Questo aggiornamento supera i precedenti riferimenti a sei sezioni o alla sezione introduttiva.

Contenuto pubblicato e frammento del seed allineati; URL della Carta derivato da `home_url` per i nuovi ambienti. Storia e dati delle persone invariati, nessun restyling o reimport.

## Storia in fondo alla pagina — 14 ottobre 2026

La nostra storia è ora l’ultima sezione H2 (`#storia`), dopo Statuto, per distinguere soci fondatori e primo presidente dai componenti attuali. I due paragrafi approvati sono conservati integralmente. In alto, dopo l’introduzione, resta solo: `La nostra delegazione si costituisce come associazione nel 2010. Scopri la nostra storia →`, con anno in grassetto e link interno. Ordine dell’indice: Consiglio, Incarichi, Squadra Sommelier, Statuto, Storia.

Riutilizzato lo stile delle sezioni esistenti, senza nuovo CSS o JavaScript. Il frammento `delegazione-storia.html` è separato dall’introduzione e aggiunto dopo Statuto dal lettore del seed; guardie sulle ancore e nessun reimport delle pagine esistenti. Questa collocazione supera quella introduttiva descritta sotto; Consiglio, incarichi, Squadra, Statuto e altri paragrafi invariati.

## La nostra storia — testo approvato, 14 ottobre 2026

Aggiunta la sottosezione H3 `La nostra storia` (`#storia`) all’inizio di La delegazione, prima di Una Delegazione aperta; voce `Storia` aggiunta all’indice. Due paragrafi in blocchi WordPress nativi, con le formulazioni concordate `La nostra delegazione si costituisce come associazione` e `condividendo le finalità della FISAR`.

Fonte: documento locale fornito dall’utente, `Atto costitutivo e statuto F.C.J. - 2010.pdf`, letto integralmente come scansione. L’atto costitutivo (pagina 1) attesta il 21 gennaio 2010, la denominazione Associazione Culturale Castelli di Jesi, i sette fondatori e il primo presidente Giovanni Elce Fabbretti; l’articolo 2 del primo statuto (pagina 2) indica la sede iniziale a Maiolati Spontini. Non dedotti avvio delle prime attività, passaggio ad APS o successive tappe. Codici fiscali e firme non trascritti; PDF storico non caricato né presentato come statuto attuale.

Testo versionato in `plugin/fisar-cdj-core/content/delegazione-introduzione.html`, letto dal seed esistente. Aggiornamento locale della sola introduzione della pagina ID 7, con controllo esatto delle due aggiunte, guardia hash e revisione recuperabile. Tutti i precedenti paragrafi, Consiglio, incarichi, Squadra, Statuto, titolo/estratto/URL e menu globale conservati; tema e plugin senza incrementi di versione o reimport. La fonte copre le origini: altre tappe potranno essere aggiunte in seguito. Questo aggiornamento supera i riferimenti sotto alla storia ancora da fornire e porta a cinque le voci dell’indice.

## Gerarchia della delegazione — tema v1.6.85, 14 ottobre 2026

Approvata la prova di una composizione più leggibile per la sola pagina La nostra delegazione. Cinque sezioni H2: La delegazione (`#delegazione`), Consiglio di Delegazione (`#consiglio`), Incarichi non elettivi (`#incarichi`), Squadra Sommelier (`#squadra`) e Statuto (`#statuto`). Titoli principali Poppins 500 da 24–28 px con accento oro; sottosezioni introduttive H3 da 18 px/600. La fascia bordeaux e gli altri template non cambiano.

- Indice sotto il paragrafo introduttivo, con quattro link nativi a Consiglio, Incarichi, Squadra e Statuto, area cliccabile minima 44 px, wrapping su mobile e landmark `nav` con nome `In questa pagina`.
- Cinque riquadri del Consiglio: ruolo H3 da 16 px/600 bordeaux, nome da 20 px/500 ed email da 16 px, su righe distinte. Due colonne da 608 px, una sotto questa soglia; nomi e recapiti possono andare a capo senza troncamento.
- Incarichi su fondo chiaro caldo, distinti dal Consiglio. Didattica, Eventi e Servizi sono sottosezioni H3 da 18 px/600, in sequenza verticale; Eventi resta immediatamente dopo Didattica. Direttori di Corso nella propria sottosezione del medesimo riquadro.
- Squadra Sommelier come sezione autonoma, non sottovoce di un incarico. Elenco su due colonne da 608 px, una su mobile, ordine DOM e nomi conservati. Anche la lista dei Direttori usa questo trattamento; niente accordion.
- Statuto in un riquadro essenziale con descrizione e testo provvisorio preesistente. Non aggiunto alcun download fittizio o documento mancante.

Gruppi, titoli, paragrafi ed elenchi sono blocchi WordPress nativi. Conservare classi `delegation-*` e ancore quando si modificano i contenuti. Copie per il seed in `delegazione-introduzione.html`, `consiglio-e-incarichi.html` e `delegazione-statuto.html`, nella cartella `plugin/fisar-cdj-core/content/`, senza duplicare i nomi nel tema. I paragrafi originali, nominativi, ruoli, email e data sono conservati; cambiati raggruppamenti e livelli degli heading, aggiunti solo indice e titolo La delegazione. Storia ancora da fornire. Aggiornamento locale con revisione recuperabile e verifica degli altri post invariati; nessun reimport o sincronizzazione automatica delle pagine esistenti.

Questa specifica prevale sulle scale comuni v1.6.84 e sulla struttura del primo import qui sotto per la sola pagina locale.

## Consiglio e incarichi — contenuti del 14 ottobre 2026

Recuperati dalla [pagina del sito attuale](http://www.fisarcastellidijesi.it/il-consiglio-di-delegazione/) su autorizzazione dell’utente. Copia per il seed in `plugin/fisar-cdj-core/content/consiglio-e-incarichi.html`, in blocchi WordPress nativi. Aggiornata soltanto la sezione `#consiglio` della pagina locale ID 7; introduzione, territorio, persone e Statuto invariati. La storia sarà completata dopo che l’utente avrà recuperato le informazioni, senza inventare una data di fondazione.

- Consiglio eletto il 26 novembre 2022: Delegato Corrado De Michelis, Segretario Gianfrancesco Innocente, Tesoriere Carla Brutti, consiglieri Nicola Merli e Loretta Gamma. Data ripresa dalla fonte, senza dedurre inizio/fine mandato aggiuntivi.
- Incarichi non elettivi: Organizzazione Corsi e Didattica — Corrado De Michelis; subito sotto, Responsabile Eventi — Stefano Ottaviani, come richiesto; Responsabile Servizi — Nicola Merli; cinque Direttori di Corso e 22 componenti della Squadra Sommelier dalla fonte.
- Il ruolo Eventi non è più attribuito al Segretario. Responsabile Servizi è distinto dal ruolo elettivo di consigliere; Nicola Merli compare in entrambi i gruppi. Nomi resi come Nome Cognome, conservando le grafie della fonte.
- Conservati i cinque recapiti completi presenti nella tabella del Consiglio. L’indirizzo `eventi@fisarcastellidijesi` è incompleto nella fonte e non è importato né completato per deduzione; nessun nuovo recapito attribuito a Stefano Ottaviani.

Revisione precedente recuperabile; titolo, estratto, URL, ancore e menu invariati. Nessun reimport generale o sincronizzazione automatica del contenuto pubblicato; nomi e ruoli restano modificabili nell’editor. I placeholder sul Consiglio e sugli incarichi sotto riportati descrivono la baseline del 4 ottobre e sono superati da questo aggiornamento; il PDF dello Statuto resta da fornire.

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
