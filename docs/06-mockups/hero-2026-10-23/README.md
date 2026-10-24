# Collage con le fotografie fornite — 23 ottobre 2026

**Versione E approvata e applicata a WordPress locale/Aruba il 24 ottobre 2026, tema 1.6.99.** Questo indirizzo resta un confronto isolato con la stessa composizione, senza selettori delle alternative, simulatore, opzioni calice/luminosità o note. Restano i tre pallini e pausa/riprendi approvati. Il piccolo header contestualizza l’anteprima; non è il menu del sito. Per il risultato reale usare localhost:8090 o l’anteprima Aruba.

Le cinque prove precedenti restano conservate nella pagina separata [confronto.html](http://127.0.0.1:8092/docs/06-mockups/hero-2026-10-23/confronto.html#E), con gli strumenti di test. Entrambe le pagine riusano il CSS del tema e lo stesso controller E, che non richiede selettori o simulazioni.

Avvio dalla radice del repository:

```sh
python3 docs/06-mockups/hero-2026-10-23/serve.py
```

Aprire [il confronto locale](http://127.0.0.1:8092/docs/06-mockups/hero-2026-10-23/). Il server ascolta solo sul loopback ed espone la cartella del provino, gli asset del tema e il solo WebP del calice, non configurazioni o database. Interrompere con Ctrl-C. Non aprire `index.html` via `file://`: il caricamento JSON richiede HTTP.

- **A — Persone e convivialità**, consigliata: brindisi e sommelier come scene principali; vigna, cantina, tè e calici nei dettagli.
- **B — Imparare e condividere**: gruppo con attestati e degustazione di tè; maggiore enfasi su formazione e curiosità anche oltre il vino.
- **C — Cantina e incontri**: botti e tavolata; atmosfera più calda, dettagli di servizio e identità FISAR.
- **D — Album in movimento**, aggiunta su richiesta: le dodici foto selezionate compaiono una volta ciascuna in una striscia continua, senza cloni. Testi e calice restano fermi. [Apri direttamente D](http://127.0.0.1:8092/docs/06-mockups/hero-2026-10-23/confronto.html#D).
- **E — Carosello di collage**, controlli aggiornati il 24 ottobre: tre collage di sei foto, diciotto scatti unici; dissolvenza di 500 ms ogni sei secondi. Pallini e pausa compatti e allineati a destra sotto alle foto, in oro-grigio smorzato, senza box o frecce. [Apri direttamente E](http://127.0.0.1:8092/docs/06-mockups/hero-2026-10-23/#E).

Le proposte statiche usano sei fotografie, D dodici ed E diciotto, in gruppi di sei. Tutte conservano due scene grandi sopra/quattro dettagli sotto su desktop; A–D mantengono tre colonne/due righe su mobile/tablet. **E mostra collage, calice e controlli soltanto da 64rem (1024 px) in su**, affiancati al titolo: sotto questa soglia restano testi e CTA, senza fascia fotografica o spazio vuoto. Titoli, Poppins/Cormorant, CTA e altezza desktop della hero sono invariati. Calice e lievi correzioni tonali si possono disattivare nel provino. Le foto complete sono consultabili aprendo «Fotografie di questa proposta, senza ritaglio».

## Movimento della proposta D

- Scorrimento orizzontale continuo a 14 px/secondo. La porzione uscita interamente viene spostata in coda, conservando gli stessi nodi: dodici foto uniche nel DOM, senza duplicati o dissolvenze tra le proposte A/B/C. Dopo il giro la sequenza si ripete naturalmente.
- Pulsante Pausa/Riprendi e frecce native, con nomi accessibili. Le frecce avanzano di sei fotografie senza animazione e fermano l’automatismo; annuncio testuale del gruppo mostrato solo dopo la navigazione manuale.
- Sospensione temporanea al passaggio del mouse, a scheda nascosta e a hero fuori viewport. L’ingresso del focus nella hero ferma il movimento fino a una ripresa esplicita. Gli observer e gli ascoltatori vengono rimossi passando a un’altra proposta.
- `prefers-reduced-motion: reduce` impedisce lo scorrimento automatico; restano le frecce. La casella «Simula movimento ridotto» esercita lo stesso ramo logico senza cambiare le preferenze del sistema. Verificata la simulazione, non modificata l’impostazione del Mac.
- Nessuna libreria, nuovo asset fotografico, richiesta remota o modifica al frontend WordPress: JavaScript e CSS circoscritti al provino.

## Carosello di collage E — correzione della prova classica

**Regola responsive del 24 ottobre, prevalente sulle precedenti prove mobile sotto:** E nasconde l'intero album sotto 64rem, lo stesso breakpoint che nel tema permette l'affiancamento al titolo. Nessun collage sotto il testo; altezza determinata dai soli contenuti. Il cambio automatico è sospeso mentre l'album è nascosto e torna disponibile allargando la finestra, rispettando pausa manuale e movimento ridotto. Le immagini rimangono presenti nel provino: non è un'ottimizzazione dei download. Verificate larghezze 320/390/768/1023/1024/1280/1440 px senza overflow; desktop ancora 448 px. Screenshot mobile aggiornato; A–D e WordPress/Aruba invariati.

**Ultima rifinitura del 24 ottobre, prevalente sulle misure iniziali sotto:** controlli a destra con margine di 4 px; tre bersagli da 32 × 32 px e pausa da 32 × 32 px, senza sovrapposizioni, ingombro totale 130 px e solo 2 px tra gruppo pallini e pausa. Pallini visivi sempre 6/8 px e icona 16 px. Inattivi `#8f8979`, attivo `#aca078`, pausa `#9b937f` tramite `currentColor`, senza bianco; contrasto sul fondo della fascia superiore a 5:1, focus visibile invariato. Foto, calice, fascia e tempi non cambiano. Verificati clic/Tab/Spazio e viewport 320/390/768/1440 px, senza overflow o sovrapposizioni al calice; screenshot aggiornati.

Il chiarimento dell’utente supera la precedente interpretazione con una sola foto per slide. Dopo la richiesta di 3–4 collage, E mostra **tre collage**, ciascuno di sei fotografie. Primo: brindisi, sommelier, uva, cantina, tè e calici. Secondo: attestati, cena, tastevin, servizio, sommelier in divisa e tavolo FISAR. Terzo: gruppo nella sala in pietra, masterclass, due sommelier al mare, servizio con bottiglia, panorama al tramonto e attestati nella sala con archi. Diciotto sorgenti uniche, senza ripetizioni tra i tre gruppi; sei derivati WebP aggiunti senza modificare gli originali. Composizione, cornici, inclinazioni e dimensioni del calice delle prove iniziali conservate. Ritagli per riquadro come A/D, non foto singole in `contain`.

Cambio dell’intero collage ogni sei secondi con dissolvenza di 500 ms; nessuno scorrimento continuo all’interno della slide. Su richiesta del 24 ottobre, ripristinati **tre pallini cliccabili**, centrati esattamente sotto il collage; poi resi più piccoli e delicati: attivo pieno oro da 8 px (prima 12), altri vuoti da 6 px (prima 8) con contorno avorio meno acceso, ciascuno con bersaglio 44 × 44 px, nome accessibile, tooltip, `aria-current` e focus visibile. Pausa/riprendi a icona da 16 px (prima 20) accanto; nessun box e frecce sempre nascoste. Ordine di tabulazione coerente: tre pallini, poi pausa. La scelta di un pallino ferma l’automatismo senza spostare il focus; si riprende esplicitamente con la pausa/ripresa. Riservata una fascia inferiore alle foto (64/80/96 px secondo il breakpoint), senza aumentare l’altezza della hero; testi, asset del calice e schema a sei riquadri invariati. Nella sola E sotto 64rem calice ridotto e alzato per non sovrapporre piede e controlli: 7,67 × 11,5 rem mobile e 10 × 15 rem tablet, bottom 3,25 rem; desktop invariato.

Nel solo pannello di confronto resta il selettore «Collage da confrontare», sincronizzato con pallini e cambio automatico. Restano supportate frecce sinistra/destra sui controlli. Solo il collage attivo (sei foto) è esposto alle tecnologie assistive; annuncio testuale solo dei cambi manuali, senza annunci automatici ripetuti. Anche con movimento ridotto i pallini rimangono utilizzabili.

Pausa con mouse sopra l’album, scheda nascosta o hero fuori viewport. Con movimento ridotto, niente automatismo né dissolvenza: restano i comandi manuali. La simulazione locale verifica lo stesso ramo, senza modificare il Mac. Timer, observer e ascoltatori rimossi al cambio proposta. Codice separato da D: nessuna libreria e nessun cambiamento al suo scorrimento.

Verificati tre gruppi da sei, assenza di ripetizioni tra le diciotto sorgenti, cambio automatico, pausa, selettore manuale, passaggio ultimo/primo, tastiera, simulazione movimento ridotto (transizione 0 s) e un solo collage esposto. Dopo l’aggiornamento dei pallini: clic e Tab/Spazio, focus conservato, scelta manuale anche con movimento ridotto; a 320/390/768/1024/1440/2560 px centratura esatta, quattro bersagli da 44 × 44 px entro la fascia, nessun overflow, asset caricati e desktop ancora 448 px. PNG E desktop/mobile aggiornati, più screenshot del secondo e terzo gruppo; la vecchia prova con foto singola è sostituita. Aprire il provino HTTP per giudicare i tempi. Nessun cambio al sito WordPress o Aruba.

## Provenienza e trattamento

27 immagini ricevute dalla cartella `Desktop/Desk 2/FISAR/sito-home`, 26 contenuti unici: le due copie `492712664_…` sono identiche. Tutti i 27 originali sono ora conservati nel repository, in [`assets/photography/sito-home/originals/`](../../../assets/photography/sito-home/originals/), con indice SHA-256 e istruzioni/script di rigenerazione nella cartella superiore. Escluso soltanto `Thumbs.db`, cache non fotografica. Selezionati inizialmente 12 scatti, poi ampliati a 18 per il terzo collage; nomi e punti focali in `photos.json`. Le foto non selezionate sono comunque archiviate integralmente.

WebP qualità 86, lato massimo 1200 px senza ingrandire le foto piccole: circa 2,51 MB complessivi per i diciotto derivati. Originali dell'utente e copie nel repository intatti, verificati byte per byte (12.332.695 byte totali). Nessuna immagine di persone generata o ricostruita. Luminosità +3,5% e contrasto +2,5% via CSS, attivi nella versione approvata e disattivabili solo nel confronto. Non è una correzione completa delle dominanti colore o della grana degli originali.

Il calice è l’illustrazione AI v2 già approvata, non un oggetto reale. Le fotografie non sono state inviate a servizi di generazione. I soli 18 WebP selezionati sono ora pubblicati nella Home dell’anteprima Aruba su richiesta esplicita; l’archivio completo originale resta nel repository, non sullo spazio web. Verificare l'utilizzabilità pubblica degli scatti prima del lancio definitivo. Nessuna cancellazione delle alternative precedenti.

## Verifiche

- Tutte e tre le varianti a 320, 390, 768, 1024, 1440 e 2560 px: nessun overflow orizzontale; immagini caricate; hero desktop sempre 448 px alla scala normale.
- Controllo visivo desktop, notebook e mobile; le foto sono ritagliate intenzionalmente nei riquadri: non ogni persona di una foto di gruppo resta visibile a ogni larghezza.
- JavaScript verificato con `node --check`; font locali caricati, nessun PHP modificato. Controlli e alternative testuali presenti; nessuna animazione.
- PNG `proposta-A/B/C-desktop.png` e `proposta-A/B/C-mobile.png` conservati qui per confronto anche a server spento.
- D verificata alle stesse sei larghezze: dodici sorgenti uniche, nessun overflow, controlli dentro la fascia, desktop 448 px; pausa stabile, frecce avanti/indietro, ripresa con spazio, simulazione movimento ridotto e caricamento asset verificati. Sintassi dei due JavaScript e test della geometria di riciclo superati. PNG D desktop/mobile aggiunti; per giudicare il movimento usare il provino HTTP, non lo screenshot.
- Tema, plugin, database, seed e sito Aruba invariati. Nessun commit automatico.

La proposta E è stata importata nella Libreria media **Aruba**, fonte editoriale principale, aggiornando soltanto la Galleria nativa `hero-collage`; tema 1.6.99 con punti focali e carosello. Non è stato trasferito il database locale. Le verifiche storiche sopra descrivono le prove isolate; per il rilascio vedere il changelog del 24 ottobre.
