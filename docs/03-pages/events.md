# Archivio Eventi

## Separatore delle card — v1.6.53

Nell’archivio, titolo prima delle informazioni pratiche: linea decorativa subito sotto, poi data, eventuale stato Iscrizioni chiuse, luogo e abstract. Stessa regola delle card Corsi: 1 px oro FISAR al 40%, larghezza del contenuto, `.65rem` sopra la linea e `.8rem` sotto. Ordine reale del markup, non riordinamento CSS; un solo titolo e una sola data per card. Home mantiene la data sopra il titolo, senza separatore; dettaglio, query, stati e dati invariati.

## Luogo con pin e provincia — v1.6.50

Card di home/archivio: `📍 Sede · Città (Provincia)`, campi mancanti omessi e provincia mostrata solo con città presente. Pin decorativo con `aria-hidden="true"`, testo completo e ritorno a capo naturale. Online: soltanto `Online`, senza pin né luogo fisico obsoleto. Ibrido: luogo fisico seguito da `+ online`; senza luogo fisico, `In presenza e online`, senza pin. Nessuna mappa o link esterno aggiunto; dettaglio e accesso online invariati.

Affinamento delle etichette del 7 ottobre 2026: `Archivio` sopra `Eventi conclusi`, in sostituzione di `Il nostro percorso`. `In programma` sopra `Prossimi eventi` invariato; nessuna modifica a selezione, ordine o stile degli eventi.

## Revisione visiva — 5 ottobre 2026

L’archivio riprende la gerarchia della homepage e della Carta dei Valori: intestazione compatta, H1 Poppins Medium 32–44 px e linea oro. Variante v1.6.20 richiesta e successivamente approvata dall’utente: fondo bordeaux scuro uniforme (`--color-bordeaux-dark`), titolo bianco e introduzione chiara (`--color-line`), senza fotografia. Introduzione Poppins 16–18 px: `Degustazioni, visite e incontri per scoprire e condividere.` La formulazione più recente, scelta dall’utente, non limita gli argomenti al vino ed evita la ripetizione fra conoscere e scoprire. Una riga su desktop, normale ritorno a capo sugli schermi piccoli, senza `nowrap` o riduzione del font. La variante sostituisce il precedente fondo chiaro soltanto nell’archivio Eventi; il singolo evento resta chiaro. Su precedente riscontro dell’utente, rimossa la dicitura `FISAR · Delegazione Castelli di Jesi` sopra il titolo: l’identità è già presente nell’header. La dicitura resta nella Carta per chiarire l’ambito locale del documento.

`Prossimi eventi` ed `Eventi conclusi` restano sezioni separate, con titoli Poppins Medium 32–40 px e accento oro. La query del plugin mostra tutti gli eventi, senza applicare il limite della home.

Affinamento v1.6.19: padding verticale della sola hero dell’archivio ridotto a `clamp(2rem, 4vw, 3rem)` (32–48 px per lato), rispetto ai precedenti 40–64 px. Titolo, testo, griglia e spaziature delle sezioni invariati; la hero del singolo evento conserva il proprio padding.

Card a una colonna su mobile, due da `38rem`, tre da `52rem`. Locandine quadrate su bianco, immagine intera con `contain`, bordo sottile e ombra leggera; titoli Poppins Medium 20–24 px. Data compatta con icona calendario, data estesa per screen reader, luogo, abstract completo quando presente e CTA `Dettagli evento` con nome accessibile completo. Gli eventi conclusi conservano la lieve desaturazione delle locandine.

Stili circoscritti a `events-archive`; nessuna modifica a homepage, Corsi, News, dati editoriali o regole temporali. Nessuna nuova dipendenza o immagine.

Prova date v1.6.21: nella card condivisa con la home, mese per esteso quando entra su una riga, abbreviato soltanto quando lo spazio effettivo non basta. Giorno della settimana ancora abbreviato; calendario, font, data completa per screen reader e `datetime` conservati. Il comportamento si aggiorna dopo font e ridimensionamento, senza breakpoint dedicati; fallback naturale su più righe senza JS o quando neppure la forma breve entra a forte ingrandimento. Dettagli in `docs/05-theme/components.md`. Le date del singolo evento non cambiano.

## Requisiti comuni

- Accessibile.
- Responsive.
- Coerente con la Carta dei Valori.
- Tono umano e non burocratico.
