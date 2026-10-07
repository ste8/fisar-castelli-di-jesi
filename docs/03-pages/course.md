# Pagina Corso

## Un solo riepilogo pratico — v1.6.57

Rimossa la sezione generata `Informazioni pratiche` dal corpo: ripeteva livello, direttore, inizio/fine e luogo già presenti nella hero. Questi dati restano nell’editor e nel riepilogo iniziale, con livello nella fascia sopra il titolo, indirizzo/città/provincia e Maps facoltativo. Descrizione completa seguita direttamente dal calendario, se presente; Eventi collegati e pannello `Informazioni e iscrizioni` conservati. Intervento sui soli Corsi attivi/conclusi: Eventi, home e archivio invariati. Prevale sui riferimenti precedenti al riepilogo inferiore.

## Contatto per informazioni e iscrizioni — v1.6.56 / plugin v1.3.17

Il corso non è presentato come un’iscrizione immediata tramite i pulsanti: pannello finale e scorciatoia ordinaria `Informazioni e iscrizioni`. Con iscrizioni disponibili e contatti compilati, introduzione `Contattaci per conoscere meglio il corso e ricevere tutte le informazioni per iscriverti.` Sezione canali `Contattaci`, pulsanti `Contattaci via WhatsApp` / `Contattaci via mail`, recapiti e nominativi completi; telefono per informazioni invariato. L’eventuale modulo editoriale conserva il suo URL, con CTA neutra `Apri il modulo`: non si presume una specifica procedura esterna né una conferma automatica.

Avviso posti limitati nei due box invita a contattarci, non a prenotare. Quote, tesseramento, dotazione, note editoriali, scadenze e stati restano invariati: con termine tassativo superato/sold-out senza lista/corso concluso nessun canale operativo riaperto; lista d’attesa conserva le etichette dedicate. Introduzione ordinaria omessa in questi stati o senza contatti. ID `course-registration`, focus dell’ancora, home/archivio e comportamento Eventi invariati. Questa revisione prevale sui testi `Iscrizione`, `Come iscriversi` e `Iscriviti via …` della v1.6.48 sotto.

## Breve descrizione nell’editor — plugin v1.3.14

Il Riassunto nativo si compila come **Breve descrizione** all’inizio del box **Dettagli del corso**, non in un pannello separato. Il testo già presente è conservato. Compare sotto il titolo del dettaglio e nelle card dell’archivio; se vuoto, nessun sottotitolo nel dettaglio e estratto automatico dalla descrizione nell’archivio. Home compatta invariata. Non è un nuovo dato né una modifica allo stile pubblico.

## Dettaglio allineato agli Eventi — v1.6.48

Questa revisione prevale sull’anteprima serif v1.6.26 riportata sotto.

- Hero chiara, titolo Poppins 500 da 32–44 px, accento oro, estratto integrale e immagine quadrata su bianco con `object-fit: contain`. Livello e stato attivo/concluso rimangono espliciti.
- Riepilogo con inizio/fine (giorno e mese per esteso), sede in grassetto, via, città/provincia su righe separate e link Maps facoltativo. Luogo condiviso con il riepilogo inferiore; nessuna mappa incorporata.
- Termine delle iscrizioni evidenziato, con nota flessibile nello stesso box e scorciatoia verso `#course-registration`, destinazione raggiungibile anche da tastiera. Avviso posti limitati se abilitato.
- Corpo in una colonna da massimo 48 rem; Poppins 16–17 px, H2 20–24 px, H3 20 px, H4 18 px, H5/H6 17 px. Editor nativo alla corrispondente scala desktop, senza cambiare il contenuto.
- Un unico pannello finale `Iscrizione`: quota libera, tesseramento e dotazione separati, poi `Come iscriversi`. Recapiti leggibili senza pulsanti; più destinatari WhatsApp con nome e numero sulla stessa linea, prefisso italiano omesso solo nella visualizzazione e suffissi accessibili completi.
- `€` aggiunto solo all’importo numerico isolato, anche se racchiuso nel solo paragrafo dell’editor. Condizioni standard/Early Bird/Under 25/gruppi, rate e altri testi formattati restano integrali.
- Sold-out sotto l’immagine, senza coprirla. Senza lista d’attesa: niente scadenza o canali operativi. Con lista: stessi canali, etichette dedicate, avviso che non garantisce la partecipazione. Termine tassativo superato: iscrizioni chiuse, scadenza consultabile, nessun canale. Corso concluso prioritario.
- `Corso attivo` dipende dalla data di fine, non dall’apertura delle iscrizioni: può essere in svolgimento e avere iscrizioni chiuse. Nessuna modalità online/ibrida introdotta o quote Soci/Non soci imposte al modello Corsi.
- Calendario, import tabulato, relatori/note, avviso variazioni e relazione con Eventi conservati. La tabella scorre nel proprio contenitore focusabile, non nell’intera pagina.

## Anteprima nell’editor — v1.6.26

Il canvas dell’editor Corsi rispecchia il template attuale, senza modificarne lo stile pubblico: corpo Poppins 18 px, interlinea 1.7; H1 84 px, H2 52 px e H3 32 px in Cormorant Garamond, come nella scala desktop esistente. Gli heading dei Corsi non sono ancora quelli Poppins del dettaglio Eventi. Font locali, margini dei titoli, elenchi, citazioni e misura `68ch` coerenti con la `.prose` pubblica. Scala desktop fissa nell’editor per evitare che il canvas più stretto riduca i titoli tramite `vw`; frontend ancora responsive.

Intervento di sola presentazione nel tema, tramite stili nativi dell’editor: contenuti, campi, calendario, iscrizioni e relazioni restano nel plugin. I metabox non vengono trasformati in anteprima del template e gli altri editor non cambiano.

## Requisiti comuni

- Accessibile.
- Responsive.
- Coerente con la Carta dei Valori.
- Tono umano e non burocratico.
