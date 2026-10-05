# Carta dei Valori

## Revisione del 5 ottobre 2026

Su richiesta dell’utente, la pagina riporta integralmente la bozza `Carta dei Valori - v2.docx`, già trascritta in `docs/00-foundation/carta-dei-valori.md`. Conservare payoff, sette punti numerati, titoli originali, testo e grassetti; nessuna sostituzione con le sintesi della homepage. La bozza resta modificabile e non è dichiarata definitiva.

Titolo della pagina e nome FISAR nell’intestazione corrispondono al titolo e al sottotitolo del documento, senza duplicarli nel corpo.

La successiva richiesta di maggiore coerenza con la home aggiorna lo stile della sola Carta: H1 Poppins `500` da 32–44 px e titoli dei capitoli Poppins `500` da 20–24 px, senza i grandi heading serif del template standard. Cormorant rimane per il payoff. Intestazione più compatta, fondo chiaro caldo e linea oro come nelle sezioni della homepage.

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
