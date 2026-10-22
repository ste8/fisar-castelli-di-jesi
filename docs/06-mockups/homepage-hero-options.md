# Alternative della hero — 15 ottobre 2026

## Opzione 2 — Collage panoramico di foto reali

Conservata su richiesta dell’utente come seconda opzione: sei fotografie, senza calice illustrativo. La galleria corrente `hero-collage`, il suo ordine e tutte le regole CSS di base sono invariati.

![Opzione 2, desktop](homepage-hero-option-02.png)

Provenienza: `assets/photography/hero-collage-panoramico-fisar-cdj.json`. Varnelli esclusa. Tre fotografie sono acquisizioni di anteprima del browser, non originali ad alta risoluzione.

## Opzione 3 — Stesso collage con calice illustrativo

Variante in prova su localhost: calice scontornato in primo piano sul margine sinistro del mosaico desktop; a destra nella fascia fotografica mobile/tablet. Non modifica le foto reali e non aggiunge altezza alla hero. Calice sintetico, non fotografia di un prodotto esistente; prompt e provenienza correnti nel JSON `assets/photography/hero-calice-scontornato-fisar-cdj-v2.json`.

Correzione v1.6.98: vetro naturale con riflessi neutri e vino più chiaro, calice leggermente più a sinistra. Prompt e asset correnti in `assets/photography/hero-calice-scontornato-fisar-cdj-v2.json`. Prima prova dorata conservata nell’anteprima `homepage-hero-option-03.png` e negli asset v1, non più attiva.

![Opzione 3 corretta, desktop](homepage-hero-option-03-natural.png)

Per tornare all’opzione 2, nell’editor della Home eliminare **soltanto il blocco Immagine top-level con classe aggiuntiva `hero-glass`**, lasciando intatta la Galleria `hero-collage`. Senza quel blocco il template non emette il calice né la classe della variante, ripristinando la composizione precedente. Non cancellare l’allegato dalla Libreria media: rimane disponibile per altri confronti. Nessun selettore pubblico o parametro URL di prova.

La precedente scena illustrativa panoramica resta negli asset `hero-calice-degustazione-fisar-cdj-v4`, indipendente da queste due alternative.
