# Archivio Corsi

## Identità strutturata e pin — v1.6.50 / plugin v1.3.15

- In modalità Automatico: badge Attivo/Concluso separato, titolo visibile `Corso Sommelier`, seconda riga `1° livello · Jesi (AN)` dai campi. Nessun livello ripetuto sopra il titolo; `1° livello` indivisibile, riga flessibile per città lunghe. Link del titolo e CTA conservano il nome accessibile completo, con livello e località.
- Data iniziale invariata, sede preceduta dall’emoji decorativa `📍`, abstract completo e CTA. Se manca la sede, nessun luogo duplicato: città e provincia sono già nella seconda riga. Se manca la città, nessuna provincia isolata o parentesi vuota. Personalizzato conserva il titolo WordPress e la precedente struttura, con pin e città/provincia come ripiego se manca la sede.
- Automatico predefinito per corsi con livello valido 1/2/3 e nuovi auto-draft; senza un livello valido e senza una scelta salvata, Personalizzato. Nessuna estrazione di livello/città dai titoli liberi, nessuna migrazione o riscrittura dei titoli esistenti. Il titolo nativo viene composto solo al salvataggio autorizzato del metabox in modalità Automatico.
- Home conserva la composizione compatta e il titolo nativo. Dettaglio invariato: leggerà il nuovo titolo nativo dopo il salvataggio. La città nella card usa il campo dedicato anche se differisce dalla città scritta nel titolo precedente. Il corso locale di prova ID 12 ha ancora Città `Jesi`, mentre il titolo libero cita Falconara: non corretto automaticamente.

Questa revisione prevale sul ripiego separato della città nella card standard previsto sotto.

Affinamento delle etichette del 7 ottobre 2026: `In programma` sopra `Corsi attivi` e `Archivio` sopra `Corsi conclusi`, in sostituzione di `Iscrizioni e lezioni` e `Archivio formativo`. Titoli, selezione dei corsi e impaginazione invariati.

## Informazioni nelle card — 7 ottobre 2026

Nell’elenco dei corsi attivi e conclusi, mostrata soltanto la data di inizio: rimossa la riga `Fino al …`. Il luogo mostra il nome della **Sede** anziché la sola città; se la sede è vuota, ripiego sulla città, e se mancano entrambe nessuna riga vuota. Non vengono modificati i titoli né dedotta la città dal titolo. Questa revisione prevale sulle indicazioni di data finale e città riportate sotto. Data di fine conservata nell’editor, nel dettaglio e nelle regole di stato/selezione del plugin. Card della home invariate, ancora con la città; nessuna modifica ai dati salvati.

## Dimensioni delle card — v1.6.49

Griglia allineata all’archivio Eventi: tre card per riga da `52rem` (832 px), due da `38rem` (608 px), una sotto tale soglia. Stessi contenitore e gap; larghezza delle card e immagini quadrate equivalenti. I corsi possono restare più alti per livello, stato e data finale, senza eliminare contenuti o imporre altezze fisse. Questa revisione prevale sulle due colonne desktop indicate sotto. Card della home e dettaglio invariati.

## Archivio — v1.6.48

Fascia iniziale compatta bordeaux scuro, senza fotografia: titolo `Corsi` Poppins 500, accento oro e sottotitolo `Corsi per sommelier per conoscere e approfondire il mondo del vino.` Nessuna firma istituzionale ripetuta.

Sezioni `Corsi attivi` e `Corsi conclusi` con query e ordinamento esistenti. Due card per riga da tablet/desktop, una su mobile. Immagini quadrate su bianco e non ritagliate, titoli Poppins 500, livello e stato; date d’inizio con icona calendario e mese adattivo per esteso/abbreviato, fine, città e abstract integrali. CTA `Dettagli corso` con titolo completo per screen reader, senza troncamenti.

Disponibilità delle iscrizioni visibile nelle card: sold-out sotto l’immagine, lista d’attesa se disponibile o badge `Iscrizioni chiuse` per termine tassativo superato. I corsi conclusi non mostrano stati operativi obsoleti. La data di fine continua a determinare in quale elenco compare il corso.

## Requisiti comuni

- Accessibile.
- Responsive.
- Coerente con la Carta dei Valori.
- Tono umano e non burocratico.
