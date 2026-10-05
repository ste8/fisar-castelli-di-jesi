# Archivio Eventi

## Revisione visiva — 5 ottobre 2026

L’archivio riprende la gerarchia della homepage e della Carta dei Valori: intestazione compatta su fondo chiaro caldo, H1 Poppins Medium 32–44 px e linea oro. Introduzione conservata, Poppins 16–18 px. Su successivo riscontro dell’utente, rimossa la dicitura `FISAR · Delegazione Castelli di Jesi` sopra il titolo: l’identità è già presente nell’header. La dicitura resta nella Carta per chiarire l’ambito locale del documento.

`Prossimi eventi` ed `Eventi conclusi` restano sezioni separate, con titoli Poppins Medium 32–40 px e accento oro. La query del plugin mostra tutti gli eventi, senza applicare il limite della home.

Affinamento v1.6.19: padding verticale della sola hero dell’archivio ridotto a `clamp(2rem, 4vw, 3rem)` (32–48 px per lato), rispetto ai precedenti 40–64 px. Titolo, testo, griglia e spaziature delle sezioni invariati; la hero del singolo evento conserva il proprio padding.

Card a una colonna su mobile, due da `38rem`, tre da `52rem`. Locandine quadrate su bianco, immagine intera con `contain`, bordo sottile e ombra leggera; titoli Poppins Medium 20–24 px. Data compatta con icona calendario, data estesa per screen reader, luogo, abstract completo quando presente e CTA `Dettagli evento` con nome accessibile completo. Gli eventi conclusi conservano la lieve desaturazione delle locandine.

Stili circoscritti a `events-archive`; nessuna modifica a homepage, Corsi, News, dati editoriali o regole temporali. Nessuna nuova dipendenza o immagine.

## Requisiti comuni

- Accessibile.
- Responsive.
- Coerente con la Carta dei Valori.
- Tono umano e non burocratico.
