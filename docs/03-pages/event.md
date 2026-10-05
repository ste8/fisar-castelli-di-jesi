# Pagina Evento

## Revisione visiva — 5 ottobre 2026

La pagina riprende lo stile della homepage e della Carta dei Valori, senza riscrivere i contenuti editoriali.

- Hero chiara e compatta; ritorno all’archivio, stato `Evento in programma`/`Evento concluso` (non più `Prossimo evento`), H1 Poppins Medium 32–44 px con linea oro, eventuale estratto Poppins 16–18 px e riepilogo di data, orari e luogo. Stato temporale sempre fornito dal plugin, senza confrontare la posizione nel calendario.
- Locandina protagonista: cornice quadrata bianca, bordo sottile, ombra leggera e immagine intera con `contain`, mai ritagliata. Due colonne da `52rem`, testo e poi locandina su mobile.
- Corpo integrale WordPress in Poppins 16–17 px, interlinea 1.7; heading Poppins Medium gerarchici, grassetti 600. Nessuna modifica a testo, elenchi, enfasi o livelli degli heading salvati nell’editor.
- Informazioni pratiche a righe con separatori sottili: etichetta sopra il valore su mobile, due colonne da `38rem`. Dati, quote, luogo, piattaforma e partecipazione restano quelli del plugin.
- Unico riquadro finale con fondo caldo e bordo oro, titolo Poppins Medium 20–24 px: `Iscrizione`, oppure `Partecipazione` quando non è richiesta prenotazione (v1.6.32). Dopo le informazioni pratiche, prima dell’eventuale corso collegato, senza sticky o duplicazione laterale (v1.6.29). Conservati note, posti limitati, condizioni di partecipazione e stato concluso.
- Corso collegato in riquadro leggero con accento oro; relazione invariata.

Stili circoscritti a `single-event`, senza cambiare template Corsi, News, homepage o Carta. Nessuna nuova dipendenza, campo o scrittura nel database.

## Posizione Google Maps — v1.6.33

- Nuovo campo facoltativo del plugin `_fisar_event_maps_url`, compilato nel box `Modalità e luogo`. Il dettaglio mostra `Apri in Google Maps ↗` sotto il luogo, sia nel riepilogo iniziale sia nelle informazioni pratiche, tramite un unico componente `event-map-link.php`.
- Link nativo nella stessa scheda, freccia decorativa, stile delle CTA testuali esistenti e font da 15 px. Nessuna mappa, iframe, SDK, chiave API o richiesta a Google durante il caricamento della pagina.
- Campo vuoto o protocollo non ammesso: nessun link. Online: nessun link al luogo fisico, anche se un vecchio valore rimane salvato. Presenza/ibrido: link disponibile anche con il solo pin e negli eventi conclusi; indirizzo e informazioni esistenti non cambiano.
- Nessuna modifica a home, archivi o Corsi; URL inserito editorialmente, senza ricerca o deduzione della posizione. I test usano dati in memoria: nessuna posizione di prova salvata negli eventi locali.

## Semplificazione delle informazioni — v1.6.32

- Nelle informazioni pratiche, la riga `Modalità` non compare per gli eventi `presence`; resta per `online` e `hybrid`. Il riepilogo iniziale già usa il luogo per gli eventi in presenza. Nessuna modifica ai dati o alle modalità salvate, all’indirizzo o al fallback ibrido.
- Etichette Accoglienza/Inizio/Fine del componente orari da 13 a 14 px (`0.875rem`), nelle due posizioni. Conservati peso 500, interlinea 1.35, orari da 16 px/600, allineamento delle ore e distanza orizzontale 24 px.
- Nota del termine flessibile nel colore del corpo `--color-ink` (`#282332`), invece di `--color-muted`; testo, dimensione 14 px e posizione dentro i due box invariati.
- Titolo del pannello finale semplificato in `Iscrizione` se è richiesta prenotazione, `Partecipazione` altrimenti. `Evento concluso`, quote, recapiti, pulsanti, condizioni, ID e scorciatoie rimangono invariati. Questa revisione prevale sulle precedenti diciture `Quote e prenotazioni`/`Quote e partecipazione`.
- Modifiche di presentazione circoscritte al dettaglio Evento; Corsi e plugin invariati, nessun nuovo script o dato salvato.

## Scadenza ed etichette dei riepiloghi — v1.6.31

- `Prenotazioni entro` e data sono una frase continua, con lo stesso font, peso 600 e dimensione: 18 px nel riepilogo iniziale, 22 px nel pannello finale. Nessun ritorno a capo imposto tra etichetta e data; su mobile il testo può andare a capo naturalmente, senza ridurre il font o nascondere parti della scadenza. Spazio esplicito nel markup e attributo `datetime` conservato.
- Nota flessibile aggiornata nel plugin `1.3.3`: `Dopo tale termine sarà comunque possibile contattarci per iscriversi, ma non potremo garantire la disponibilità.` Rimane dentro entrambi i box; termine tassativo, data assente ed evento concluso invariati.
- Etichette principali dei riepiloghi (`Data`, `Orario`, `Luogo`, `Modalità`, ecc.) uniformate a Poppins 600 da 16 px, maiuscolo e tracking 0.05 em. Selettori limitati ai figli diretti della hero e ai `dt` del dettaglio Evento: nomi delle sedi, valori, Accoglienza/Inizio/Fine e Corsi non cambiano.
- Stesso componente condiviso della scadenza e nessuna modifica ai dati salvati, nessuna dipendenza o nuovo script. Queste indicazioni prevalgono sulla v1.6.30 per stile di etichetta/data e testo della nota.

## Affinamenti della prenotazione — v1.6.30

- Componente condiviso `event-deadline.php` per riepilogo iniziale e pannello finale: `Prenotazioni entro`, giorno della settimana e data completa localizzati tramite il formatter esistente del tema. Data da 18 px nel riepilogo e 22 px nel pannello; il termine flessibile, quando configurato, compare **dentro entrambi i riquadri**. Le regole e il testo della nota restano nel plugin.
- `Come prenotare ↓` dentro il riquadro iniziale delle prenotazioni. Se non esiste una scadenza valida, resta la scorciatoia autonoma nella hero. Partecipazione libera ed eventi conclusi mantengono i comportamenti v1.6.29.
- `Quota di partecipazione` e `Come prenotare` a 20 px, peso 600. Soci/Non soci e importi allineati a sinistra, colonne da 5 rem e spazio di 0.75 rem: niente distribuzione agli estremi del pannello. Condizioni di partecipazione evidenziate con grassetto semantico.
- Sezione `Come prenotare` con intestazioni dei canali a 18 px, peso 600, recapiti selezionabili e pulsanti `Prenota via WhatsApp`/`Prenota via mail`. Etichette aggiornate dal plugin soltanto per gli Eventi; Corsi invariati. Nessun canale o pulsante presunto se mancano i dati.
- Per rendere operativa la prenotazione richiesta dall’utente, completato editorialmente il solo campo WhatsApp dell’evento locale `alla-scoperta-dei-vini-giapponesi` (ID 108): da `http://3357882629` a `+39 3357882629`, che genera `https://wa.me/393357882629`. Nessuna aggiunta automatica di prefissi agli altri eventi e nessuna modifica agli altri recapiti.
- Conservati unico pannello finale, quote con `€`, contenuto integrale, ancore e focus; nessuna dipendenza o nuovo script. Questo affinamento prevale sulle indicazioni precedenti relative a posizione della nota e del pulsante.

## Unico punto di prenotazione — v1.6.29

Questa revisione, approvata dall’utente dopo la valutazione UX/information architecture, prevale sul doppio pannello v1.6.28:

- Un solo riquadro `event-registration.php` in fondo, con ancora `event-registration` e titolo `registration-title`. Link nativo nella hero `Come prenotare ↓` (oppure `Come partecipare ↓` per gli eventi senza prenotazione), senza nuovi script. La destinazione riceve il focus e ha un margine di scorrimento di 2 rem; animazione esistente disattivata con `prefers-reduced-motion`.
- Corpo e riquadro in una colonna centrale larga al massimo 48 rem, mantenendo la misura di lettura e la gerarchia tipografica esistenti. Nessuna sidebar; contatti affiancati da 38 rem e impilati su mobile.
- Quote visibili una sola volta: nel riquadro degli eventi in programma, oppure nella tabella di quelli conclusi. Rimosse quote e scadenza dalla tabella degli eventi in programma, perché sono immediatamente nel riquadro successivo. La scadenza resta evidenziata nella hero e nel riquadro, con avviso flessibile conservato.
- Su richiesta dell’utente, importi numerici senza valuta mostrati con spazio non separabile e `€` (`50 €`, `25,50 €`); importi già con valuta e testi editoriali completi conservati senza duplicazioni. Formatter di presentazione nel tema, nessuna conversione numerica o modifica ai meta. Gratuità invariata.
- Gli eventi conclusi mostrano un solo riquadro di stato in fondo, senza scorciatoia di prenotazione, scadenza o canali operativi. Relazione al Corso e contenuto integrale invariati.

## Quote, recapiti e scadenza — v1.6.28

- Un solo componente `event-registration.php` presenta gli stessi dati nei due riquadri, con ID distinti e titoli accessibili. Un evento concluso conserva soltanto il riquadro di stato, senza recapiti di prenotazione, scadenze o secondo invito.
- Quote di partecipazione dentro entrambi i riquadri, vicine ai canali d’iscrizione, oltre alla tabella pratica. Valori editoriali integrali, senza aggiungere valuta presunta; gratuità e campi mancanti rispettati. `event-fees.php` condivide la presentazione nei tre punti.
- Scadenza in un blocco bianco con bordo oro, calendario decorativo e data completa Poppins `600` da 22 px; ripetuta in una riga evidenziata sia nella hero sia nella tabella, a 18 px. Data e avviso sul termine flessibile provengono dal plugin; nessuna chiusura automatica delle prenotazioni introdotta.
- I canali configurati mostrano un riferimento testuale selezionabile (numero WhatsApp, email, telefono o URL) oltre all’eventuale pulsante. Nessun recapito sostituito con dati generici della Delegazione; se un link a gruppo/canale WhatsApp non contiene un numero, resta visibile il link originale.
- Il campo WhatsApp accetta numero o link completo. I numeri con prefisso internazionale generano `wa.me`; i numeri nazionali rimangono leggibili senza un link presunto. Riconosciuti anche i vecchi valori numerici salvati come `http://numero`. L’editor di Eventi e Corsi conserva ora i numeri come testo, senza convertirli in URL; nessuna migrazione dei dati esistenti.

## Riepilogo e orari — v1.6.22

- Accoglienza e inizio affiancati nello stesso gruppo `Orario` (dicitura aggiornata su richiesta dell’utente), con etichette sopra e valori in grassetto sulla stessa riga, anche su mobile. La stessa presentazione compare nel riepilogo e nella tabella pratica, tramite `template-parts/event-schedule.php`, così non possono divergere.
- Affinamento v1.6.23: le due colonne seguono la larghezza del testo, senza distribuire gli orari su tutto lo spazio disponibile; distanza orizzontale `1.5rem` (24 px alla dimensione standard).
- L’eventuale `Fine` resta visibile sotto quando sono presenti tutti e tre gli orari. Campi mancanti omessi, senza valori presunti o etichette vuote; con un solo orario il valore occupa il gruppo intero. Numeri tabulari e nessuna nuova logica JavaScript.
- Nel riepilogo degli eventi in presenza, `Luogo` con sede e città/provincia al posto di `Modalità: In presenza`. L’indirizzo completo resta nella tabella pratica; se sede e città mancano, l’indirizzo disponibile è usato anche nel riepilogo.
- Solo gli eventi online hanno nel riepilogo `Modalità: Online`, senza luogo fisico. Gli ibridi mostrano sede e `Anche online`; se manca ogni dato di luogo, fallback `Modalità: In presenza e online`, per non perdere l’informazione.
- Modalità, piattaforma, accesso online e altri dati della tabella finale restano conservati, con i precedenti vincoli per eventi conclusi.

## Gerarchia del luogo — v1.6.24

Nome della sede in Poppins `600` da 16 px, su una riga dedicata (con ritorno a capo naturale se lungo). Nel riepilogo segue la città/provincia; nelle informazioni pratiche seguono via e città/provincia, ciascuna su una riga distinta con spazio di 4 px. Nessuna concatenazione di sede e indirizzo. Dati mancanti omessi, senza righe vuote, mantenendo il fallback all’indirizzo nel riepilogo quando sede e città non sono presenti. `Online` e `Anche online` restano invariati. Nessun uso di `address`: non sono recapiti dell’autore della pagina.

Aggiornamento v1.6.25: anche il riepilogo iniziale mostra sempre la via disponibile, tra nome della sede e città/provincia, con la stessa formattazione della tabella. Questa indicazione prevale sulla precedente sintesi senza via; campi mancanti ancora omessi e modalità online invariata.

## Anteprima nell’editor — v1.6.26

Il canvas dell’editor Eventi usa gli stessi font locali e la scala di lettura desktop della pagina: corpo Poppins 17 px, interlinea 1.7, H2 24 px, H3 20 px, H4 18 px (aggiornamento v1.6.27, anche nel frontend), H5–H6 17 px, titoli a peso 500 e grassetti 600. Margini dei titoli, elenchi, citazioni e misura di lettura `68ch` coerenti con `.single-event .prose`. Il titolo principale rimane distinto dal contenuto; la sua scala è 44 px. Le dimensioni non usano `vw`, perché la larghezza del canvas è diversa dal viewport della pagina pubblica.

Stili nativi caricati soltanto nel contesto di modifica Evento; niente modifiche a dati, formattazioni esplicite salvate, metabox o interfaccia amministrativa. È una rappresentazione del testo, non una replica della hero, del riepilogo o delle iscrizioni, generati dal template. La pagina pubblica mantiene le proprie scale responsive.

## Requisiti comuni

- Accessibile.
- Responsive.
- Coerente con la Carta dei Valori.
- Tono umano e non burocratico.
