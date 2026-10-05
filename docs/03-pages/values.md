# Carta dei Valori

## Revisione del 5 ottobre 2026

Su richiesta dell’utente, la pagina riporta integralmente la bozza `Carta dei Valori - v2.docx`, già trascritta in `docs/00-foundation/carta-dei-valori.md`. Conservare payoff, sette punti numerati, titoli originali, testo e grassetti; nessuna sostituzione con le sintesi della homepage. La bozza resta modificabile e non è dichiarata definitiva.

Il titolo della pagina è `Carta dei Valori della nostra Delegazione`, con identificazione `FISAR · Delegazione Castelli di Jesi` nell’intestazione. URL `/carta-dei-valori/` e voce di menu breve `Carta dei Valori` restano invariati. Una nota editoriale separata dal documento chiarisce: “Nota bene: questo documento esprime i principi e i valori che guidano la nostra Delegazione Castelli di Jesi e non rappresenta necessariamente quelli della FISAR nazionale.” Il testo esprime l’ambito e l’autonomia della Carta locale, senza suggerire una contrapposizione o principi dettati dalla nazionale.

La successiva richiesta di maggiore coerenza con la home aggiorna lo stile della sola Carta: H1 Poppins `500` da 32–44 px e titoli dei capitoli Poppins `500` da 20–24 px, senza i grandi heading serif del template standard. Cormorant rimane per il payoff. La prima intestazione compatta su fondo chiaro è sostituita dalla hero fotografica descritta sotto, su ulteriore richiesta dell’utente.

## Hero fotografica

Paesaggio caldo di vigneti a tutta larghezza, diverso dalla foto conviviale della hero della home, con overlay scuro e gradiente più uniforme su mobile per proteggere la leggibilità. Identificazione della Delegazione in oro chiaro, H1 bianco Poppins `500` da 32–44 px e linea oro. La descrizione “I principi che guidano ogni nostra attività e scelta.” usa Poppins `500` da 18–24 px. Altezza determinata dai contenuti, minimo `20rem`, senza testo nascosto o animazioni; il testo si allinea alla colonna del corpo. La nota sull’ambito locale non compare più sopra l’immagine.

La fotografia usa prima l’immagine in evidenza della Carta, modificabile dal backend, altrimenti l’asset già disponibile `valori-vigneti-demo.webp`. Non usa più l’immagine in evidenza della homepage come fallback. Nessun nuovo asset, font o servizio esterno; l’immagine è decorativa (`alt=""`), caricata con priorità alta e, per gli allegati WordPress, `srcset` nativo.

Titolo e descrizione usano titolo ed estratto nativi della pagina.

## Nota informativa sotto la hero

Su successiva richiesta dell’utente, la precisazione sull’ambito locale viene mostrata sotto l’immagine, prima del payoff, in un riquadro allineato alla colonna di lettura. Fondo chiaro caldo, bordo oro da 1 px con lato sinistro da 3 px, angoli appena arrotondati e icona `info` lineare in oro scuro. Testo Poppins `400` da 16 px, interlinea `1.6`, con `Nota bene:`, `Delegazione Castelli di Jesi` e `non rappresenta necessariamente quelli della FISAR nazionale.` in grassetto semantico `600`, senza heading aggiuntivo. La frase finale non è sottolineata per distinguerla dai link. L’icona è decorativa; il riquadro è un `aside` con nome accessibile `Nota sulla Carta dei Valori`, senza `role="alert"` o live region: è una precisazione, non un avvertimento.

Il primo blocco Paragrafo con classe `values-scope-note` contiene la nota: rimane modificabile nell’editor, ma viene mostrato soltanto nel riquadro dal tema. La separazione è solo di presentazione e non modifica il database. Se il blocco iniziale non corrisponde, il corpo è mostrato integralmente e non viene creato un riquadro vuoto.

## Corpo della Carta

Il payoff originale “Il vino come punto di partenza, le persone al centro.” rimane nel corpo, immediatamente sopra `1. Chi siamo`, in Cormorant `600` da 20–24 px. Tutto il testo originale del documento, inclusi i sette punti e i grassetti, è conservato.

Impaginazione a colonna singola, larga al massimo `50rem`, con allineamento comune fra intestazione e corpo; testo Poppins da 16–17 px, interlinea `1.7` e grassetti semantici a peso `600`. Separazioni sottili e spaziature regolari fra i capitoli; da `48rem` i paragrafi si allineano al testo dei titoli, lasciando alle icone una colonna dedicata. Su mobile i paragrafi sfruttano tutta la larghezza disponibile. Nessuna card, colonna di testo parallela, sintesi aggiuntiva o contenuto nascosto.

Ogni capitolo ha una piccola icona SVG lineare oro. I punti 2–7 riutilizzano esattamente le icone della home (germoglio, bussola, scudo, fumetto, persone, lampadina); `Chi siamo` usa il cuore già presente nel tema. Tutte le icone sono decorative, nascoste alle tecnologie assistive e non focalizzabili.

## Gestione editoriale

Pagina WordPress nativa, template standard e blocchi Paragrafo/Titolo modificabili dall’editor. Le icone vengono aggiunte dal tema solo in frontend, in base alle ancore dei titoli H2, senza inserire SVG nel contenuto salvato. Conservare le ancore quando si modifica il copy:

- `chi-siamo`
- `persone-e-modo-di-lavorare`
- `curiosita-e-apertura`
- `vino-con-consapevolezza`
- `semplicita-e-informalita`
- `inclusione-e-accoglienza`
- `ognuno-puo-contribuire`

Il seed del plugin usa `content/carta-dei-valori.html` per le nuove installazioni demo. L’ambiente locale esistente viene aggiornato soltanto per questa pagina, con revisione WordPress del testo precedente; non vengono reimportati gli altri contenuti e non viene cambiata la versione del seed per forzarne la reinstallazione.

## Requisiti comuni

- Accessibile.
- Responsive.
- Coerente con la Carta dei Valori.
- Tono umano e non burocratico.
