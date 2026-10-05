# Pagina Evento

## Revisione visiva — 5 ottobre 2026

La pagina riprende lo stile della homepage e della Carta dei Valori, senza riscrivere i contenuti editoriali.

- Hero chiara e compatta; ritorno all’archivio, stato `Evento in programma`/`Evento concluso` (non più `Prossimo evento`), H1 Poppins Medium 32–44 px con linea oro, eventuale estratto Poppins 16–18 px e riepilogo di data, orari e luogo. Stato temporale sempre fornito dal plugin, senza confrontare la posizione nel calendario.
- Locandina protagonista: cornice quadrata bianca, bordo sottile, ombra leggera e immagine intera con `contain`, mai ritagliata. Due colonne da `52rem`, testo e poi locandina su mobile.
- Corpo integrale WordPress in Poppins 16–17 px, interlinea 1.7; heading Poppins Medium gerarchici, grassetti 600. Nessuna modifica a testo, elenchi, enfasi o livelli degli heading salvati nell’editor.
- Informazioni pratiche a righe con separatori sottili: etichetta sopra il valore su mobile, due colonne da `38rem`. Dati, quote, luogo, piattaforma e partecipazione restano quelli del plugin.
- Riquadro iscrizioni con fondo caldo e bordo oro, titolo Poppins Medium 20–24 px. Affiancato al corpo su desktop, in normale flusso senza sticky; sotto il corpo su mobile. Conservati canali, scadenze, note, posti limitati, condizioni di partecipazione e stato concluso.
- Corso collegato in riquadro leggero con accento oro; relazione invariata.

Stili circoscritti a `single-event`, senza cambiare template Corsi, News, homepage o Carta. Nessuna nuova dipendenza, campo o scrittura nel database.

## Riepilogo e orari — v1.6.22

- Accoglienza e inizio affiancati nello stesso gruppo `Orari`, con etichette sopra e valori in grassetto sulla stessa riga, anche su mobile. La stessa presentazione compare nel riepilogo e nella tabella pratica, tramite `template-parts/event-schedule.php`, così non possono divergere.
- Affinamento v1.6.23: le due colonne seguono la larghezza del testo, senza distribuire gli orari su tutto lo spazio disponibile; distanza orizzontale `1.5rem` (24 px alla dimensione standard).
- L’eventuale `Fine` resta visibile sotto quando sono presenti tutti e tre gli orari. Campi mancanti omessi, senza valori presunti o etichette vuote; con un solo orario il valore occupa il gruppo intero. Numeri tabulari e nessuna nuova logica JavaScript.
- Nel riepilogo degli eventi in presenza, `Luogo` con sede e città/provincia al posto di `Modalità: In presenza`. L’indirizzo completo resta nella tabella pratica; se sede e città mancano, l’indirizzo disponibile è usato anche nel riepilogo.
- Solo gli eventi online hanno nel riepilogo `Modalità: Online`, senza luogo fisico. Gli ibridi mostrano sede e `Anche online`; se manca ogni dato di luogo, fallback `Modalità: In presenza e online`, per non perdere l’informazione.
- Modalità, piattaforma, accesso online e altri dati della tabella finale restano conservati, con i precedenti vincoli per eventi conclusi.

## Requisiti comuni

- Accessibile.
- Responsive.
- Coerente con la Carta dei Valori.
- Tono umano e non burocratico.
