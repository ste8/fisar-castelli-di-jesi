# Pagina Evento

## Revisione visiva — 5 ottobre 2026

La pagina riprende lo stile della homepage e della Carta dei Valori, senza riscrivere i contenuti editoriali.

- Hero chiara e compatta; ritorno all’archivio, stato `Evento in programma`/`Evento concluso` (non più `Prossimo evento`), H1 Poppins Medium 32–44 px con linea oro, eventuale estratto Poppins 16–18 px e riepilogo di data, orari e luogo. Stato temporale sempre fornito dal plugin, senza confrontare la posizione nel calendario.
- Locandina protagonista: cornice quadrata bianca, bordo sottile, ombra leggera e immagine intera con `contain`, mai ritagliata. Due colonne da `52rem`, testo e poi locandina su mobile.
- Corpo integrale WordPress in Poppins 16–17 px, interlinea 1.7; heading Poppins Medium gerarchici, grassetti 600. Nessuna modifica a testo, elenchi, enfasi o livelli degli heading salvati nell’editor.
- Informazioni pratiche a righe con separatori sottili: etichetta sopra il valore su mobile, due colonne da `38rem`. Dati, quote, luogo, piattaforma e partecipazione restano quelli del plugin.
- Riquadro iscrizioni con fondo caldo e bordo oro, titolo Poppins Medium 20–24 px. Affiancato al corpo su desktop, in normale flusso senza sticky; prima della descrizione su mobile. Per gli eventi in programma, ripetuto dopo le informazioni pratiche e prima dell’eventuale corso collegato (v1.6.28). Conservati note, posti limitati, condizioni di partecipazione e stato concluso.
- Corso collegato in riquadro leggero con accento oro; relazione invariata.

Stili circoscritti a `single-event`, senza cambiare template Corsi, News, homepage o Carta. Nessuna nuova dipendenza, campo o scrittura nel database.

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
