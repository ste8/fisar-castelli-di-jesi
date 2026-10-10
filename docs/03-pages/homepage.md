# Homepage

## Come seguirci condiviso — v1.6.71

Il 10 ottobre 2026 confermate home e pagina autonoma `/seguici/`, senza rimandare la voce del menu a un’ancora della homepage. La home non mostra più `Scopri tutti i canali`, perché tutte e quattro le opzioni sono già presenti. Card dei canali e contenuto del pannello newsletter condivisi: medesimi titolo, descrizione e modulo, con gerarchia H3 in home/H2 nella pagina e ID distinti. Testo comune del pannello: `Ricevi un riepilogo delle iniziative più importanti della Delegazione.`. Introduzioni specifiche dei contesti conservate, come le ancore, la posizione dopo la Carta e i link del footer. Questa decisione prevale sul rimando alla pagina descritto sotto.

## Immagini Blog quadrate — v1.6.70

Dal 10 ottobre 2026 anche le immagini delle card Blog sono **1:1**, per uniformare la preparazione delle immagini principali a Eventi/Corsi. Consigliato 1080 × 1080 px. Foto quadrate senza adattamento di proporzioni; quelle già presenti con altri formati continuano a riempire il riquadro con ritaglio solo visivo. Restano quattro articoli, griglia quattro/due/una colonna, titolo, estratto e CTA invariati. Questa revisione prevale sul 16:10 riportato sotto. Nessun allegato modificato; dettagli in `docs/03-pages/news.md`.

## Blog — v1.6.68

Dal 9 ottobre 2026, su approvazione dell’utente, `Dal nostro blog` sostituisce `Ultime notizie dalla Delegazione`. Link sempre disponibile `Tutti gli articoli`, stato vuoto `Non ci sono articoli pubblicati al momento.`. Quattro articoli nativi, ordine, card, stile e CTA `Leggi l’articolo` invariati. La nuova denominazione prevale sui riferimenti storici a News sotto; URL esistente conservato. Specifica in `docs/03-pages/news.md`.

## Card News — v1.6.67

Allineate l’8 ottobre 2026 a Eventi e Corsi: fotografia orizzontale 16:10 sopra il testo a tutte le larghezze, titolo Poppins 500 / 22 px senza troncamento, linea oro tenue, data con calendario decorativo, estratto a due righe e CTA con nome completo. Quattro notizie e ordine invariati; griglia quattro colonne desktop, due tablet, una mobile. Media assente omesso, senza spazio vuoto; stato senza notizie esplicito e link archivio sempre disponibile. Questa revisione prevale sulla composizione orizzontale delle singole card del mockup. Dettagli in `docs/03-pages/news.md`.

## Offerte Corsi più evidenti — v1.6.61

Solo con offerta attiva, cornice gialla da 4 px intorno a immagine/fascia e fondo giallo pieno `#ffda3d`, con `IN OFFERTA` da 16 px/700 e data da 13 px/600 su riga separata, bordeaux scuro. Il gruppo si allinea in alto nella card compatta, senza spazio vuoto dentro la cornice. Nessun overlay, animazione o cambiamento delle card ordinarie; prevale sulla precedente fascia oro tenue.

## Offerte Corsi — v1.6.60 / plugin v1.3.19

Nelle card compatte dei Corsi, fascia oro tenue sotto l’immagine senza coprirla, testo `In offerta` e data `Fino al …` facoltativa. Dimensioni e ritorno a capo adattati alla colonna della locandina; nessun troncamento della data o del nome accessibile. Stato identico a elenco/dettaglio, fornito dal plugin; numero, query e ordine dei corsi invariati.

Affinamento v1.6.55: anche gli eventi solo online mostrano `📍 Online` nelle card, con pin decorativo nascosto agli screen reader. Nessuna pubblicazione di piattaforme/link o modifica al dettaglio.

## Card Eventi — separatore v1.6.54

Su approvazione del 7 ottobre 2026, le card Eventi della home adottano l’ordine dell’archivio: titolo, linea oro tenue, data, eventuale avviso Iscrizioni chiuse, luogo e CTA. Bordo decorativo da 1 px oro FISAR al 40%, largo quanto il contenuto; `.65rem` sopra e `.8rem` sotto. Ordine anche nel markup, senza riordino CSS. Locandine, sold-out, date adattive, limite di quattro eventi e nomi accessibili completi invariati. Nessun abstract aggiunto; card Corsi/News e resto della home invariati. Questa revisione prevale sulle precedenti indicazioni della data sopra il titolo e dell’assenza del separatore in home.

## Struttura
1. Top bar + Header
2. Hero
3. Prossimi Eventi
4. I nostri Corsi
5. Dal nostro blog
6. Carta dei Valori
7. Come seguirci
8. Footer

## Hero
Aggiornamento approvato il 4 ottobre 2026; prevale sul concept iniziale per copy e gerarchia della hero.

- H1 ben visibile: **FISAR / Delegazione / Castelli di Jesi**, su tre righe, in Poppins `400`.
- Payoff secondario, più piccolo e in Cormorant Garamond `600`: **Il vino come punto di partenza, le persone al centro.**
- Descrizione: **Corsi per sommelier, degustazioni e incontri per conoscere il mondo del vino.**
- Payoff e descrizione hanno più respiro orizzontale: larghezza massima rispettivamente `34rem` e `40rem`, sempre limitata allo spazio disponibile. I testi occupano una riga quando possibile; `text-wrap: balance` equilibra le righe alle larghezze inferiori senza interruzioni forzate o `nowrap`. H1, fotografia e CTA restano invariati.
- Le due frasi del payoff sono raggruppate per favorire l’andata a capo dopo la virgola; ogni gruppo può comunque andare a capo internamente se lo spazio o lo zoom lo richiedono.
- CTA: **Scopri i corsi** · **Scopri gli eventi**.
- Fotografia calda, preferibilmente reale, con overlay scuro per la leggibilità. Conservata l’immagine attuale sostituibile dall’editor.
- Nessuna fascia istituzionale sotto la hero: seguono direttamente le proposte di Eventi e Corsi. Nome esteso della FISAR, qualifica APS e rapporto con la Delegazione sono raccontati nella sezione `Chi siamo`, senza duplicare il testo in homepage.

Non compare “autonoma” nel titolo. Le altre sezioni della homepage restano invariate.

## Prossimi eventi — revisione del 5 ottobre 2026

Su richiesta dell’utente, il limite della homepage passa da due a quattro eventi futuri, sempre in ordine cronologico crescente. Questa revisione prevale sulla precedente scelta di mostrare soltanto due locandine. Il tema usa la query pubblica del plugin e il totale `found_posts`, senza duplicare criteri temporali o eseguire una seconda query di conteggio.

- Con uno o due eventi si conserva la presentazione attuale; con tre o quattro sono tutti visibili. Nessun carosello o elemento nascosto dietro un’interazione.
- Griglia da due card per riga da `38rem`, anche nelle larghezze intermedie dove gli archivi usano tre colonne; una colonna sotto `38rem`. Dimensioni, formato quadrato, fondo bianco e comportamento `contain` delle locandine conservati.
- Oltre quattro eventi si mostrano i primi quattro. Su successiva approvazione dell’utente, il link conserva sempre testo e posizione coerenti: `Tutti gli eventi` fino a quattro, `Tutti gli eventi (N)` oltre quattro per rendere esplicito il totale. È sempre accanto al titolo su desktop e sotto le card su mobile; questa scelta sostituisce la precedente CTA lunga sotto la griglia su tutte le larghezze.
- Senza eventi futuri compare `Non ci sono eventi in programma al momento.` Il collegamento all’archivio resta disponibile, anche per consultare quelli passati.

Restano invariati Corsi, News, Carta, Come seguirci, ordine delle sezioni, archivi e pagine interne. Le CTA delle card mantengono il titolo completo nel nome accessibile.

Prova date v1.6.21: le card Eventi mostrano il mese per esteso se entra su una riga, abbreviandolo solo quando manca spazio. Il controllo è condiviso con l’archivio, misura la card reale e si aggiorna con font e ridimensionamento. Data accessibile completa e fallback su più righe senza JS; dettagli in `docs/05-theme/components.md`. Nessuna variazione a numero, ordine o contenuto degli eventi.

## Titoli — Poppins Medium

Approvati il 4 ottobre 2026 dopo la prova sul sito: i titoli di sezione Eventi, Corsi, News, Valori e Come seguirci usano Poppins `500` con `clamp(2rem, 4vw, 2.5rem)`, massimo 40 px. Conservati interlinea, tracking, colori e linee decorative.

La successiva prova estende Poppins `500` ai titoli delle card Eventi, Corsi e News, ai canali, al pannello newsletter e alle voci della fascia Valori. Le card conservano la dimensione di `1.375rem`, con interlinea `1.3` per dare respiro al sans-serif; nessun troncamento dei titoli. Hero e payoff restano invariati. La tipografia dei componenti condivisi è uniforme anche negli archivi e nella pagina Seguici; gli heading editoriali delle pagine interne non cambiano. Il font Medium è già self-hosted nel tema; nessun nuovo asset o font remoto.

Per la composizione e i contenuti aggiornati della fascia Valori prevale la revisione del 5 ottobre 2026 descritta sotto.

## Carta dei Valori — revisione del 5 ottobre 2026

La bozza Word `Carta dei Valori - v2.docx` fornita dall’utente coincide nei contenuti con `docs/00-foundation/carta-dei-valori.md`, che resta una working draft e non viene riscritta. La homepage presenta una sintesi editoriale, non un nuovo testo ufficiale; la pagina completa della Carta e il seed del plugin rimangono invariati.

- Titolo: **La nostra Carta dei Valori**.
- Introduzione, corrispondente al punto 1: **Ci uniscono la passione per il vino, la voglia di conoscerlo e il piacere di condividerlo.** Segue: **Attraverso corsi ed eventi coltiviamo competenza e professionalità, in un ambiente informale.**
- Sei voci, nello stesso ordine dei punti 2–7 della Carta:
  - **Il modo di lavorare dei produttori**: Privilegiamo chi ha cura della terra, dell’ambiente e delle persone, e cerca la qualità in vigna.
  - **Curiosità e apertura**: Partiamo dal vino per esplorare anche il mondo del cibo e delle altre bevande, come birra, distillati, tè e caffè.
  - **Il vino con consapevolezza**: Un approccio al vino basato sulla conoscenza e sulla consapevolezza.
  - **Informalità**: Seguiamo le regole del servizio e della degustazione, senza eccessivi formalismi.
  - **Inclusione e accoglienza**: Le nostre attività sono aperte a tutti, anche a chi non ha mai frequentato un corso da sommelier e a chi fa parte di altre associazioni.
  - **Ognuno può contribuire**: Idee, proposte e iniziative possono arrivare da tutti, non solo dal Consiglio Direttivo.
- CTA conservata: **Leggi la Carta dei Valori**, verso la pagina completa, dove restano gli approfondimenti (anche Slow Food/Slow Wine).

Il copy sopra include l’affinamento approvato dall’utente il 5 ottobre: condivisione citata una sola volta nell’introduzione, “corsi ed eventi” e ambiente informale; consapevolezza al posto di “scegliere con moderazione”; accesso anche senza precedenti corsi da sommelier; vino come punto di partenza; titolo “Informalità” e testo “Seguiamo le regole…” per evitare di ripetere competenza e professionalità. Restano invariati layout, icone e testo completo della Carta; la moderazione resta trattata nella pagina completa.

Sostituite le quattro categorie generiche e le icone con una lista semantica di sei brevi testi, H3 Poppins `500` da `1.25rem`, interlinea `1.3`, allineamento a sinistra e separatori sottili. Fondo fotografico caldo, overlay scuro, H2 Poppins e accenti oro restano coerenti con il sito. Una colonna sotto `38rem`, due colonne per le sei voci da `38rem`; introduzione e lista affiancate da `52rem`, senza carosello, testo nascosto o animazioni. Nessuna promessa sanitaria nel testo sintetico.

Esperimento successivo del 5 ottobre: ciascuna delle sei voci ha una piccola icona SVG lineare oro, a sinistra di titolo e testo, in una colonna da `1.75rem` con gap `.75rem`. Associazioni: germoglio → produttori; bussola → curiosità; scudo → consapevolezza; fumetto → informalità; persone → accoglienza; lampadina → contributo. Riutilizzato il sistema icone del tema e aggiunte solo le tre forme mancanti. Le icone sono decorative (`aria-hidden="true"`, `focusable="false"`): il significato resta nei testi, invariati. Nessuna immagine raster, dipendenza o richiesta di rete aggiuntiva.

## Percorsi di accesso

### Card Corsi — v1.6.48

Restano fino a tre corsi attivi e la composizione orizzontale compatta, senza abstract. Immagine quadrata su bianco senza ritaglio, titolo Poppins 500, livello e stato visibili; data d’inizio con icona calendario, mese per esteso quando entra su una riga e abbreviato solo quando necessario. Testo completo per screen reader e CTA accessibile invariati. Sold-out sotto l’immagine/lista d’attesa o badge iscrizioni chiuse se pertinente; corso attivo e iscrizioni aperte sono distinti.

Collegamento `Tutti i corsi` sempre disponibile, con totale `(N)` quando supera tre, ricavato da `found_posts` della stessa query. Stato vuoto quando non ci sono corsi attivi. Ordine delle sezioni e altre card invariati.

Dal 4 ottobre 2026 le Quattro Porte sono rimosse, senza un blocco sostitutivo: duplicavano percorsi già presenti nella hero, nella navigazione e nelle sezioni con contenuti concreti. La decisione prevale sul concept iniziale. Restano le CTA verso Eventi e Corsi, `Unisciti a noi` nell’header e la fascia Carta dei Valori.

Prossimi Eventi: futuri ASC. Corsi: attivi. News: ultime pubblicazioni.

## Come seguirci

Sezione dedicata dopo la Carta dei Valori e prima del footer, come approvato il 4 ottobre 2026. L’intero blocco, incluso il form newsletter, è spostato senza modificarne contenuto o stile: le attività concrete precedono l’invito a mantenere il contatto. La voce `Seguici` nel menu e i collegamenti social in top bar e footer restano invariati. Presenta quattro canali con CTA esplicite:

- Canale WhatsApp;
- Instagram;
- Facebook;
- Newsletter Mailchimp.

Le CTA social usano gli URL del menu WordPress `social`. La newsletter usa un form HTML integrato nello stile del tema e invia nome, cognome ed email direttamente a Mailchimp, senza salvare i dati in WordPress e senza caricare CSS o JavaScript remoti. La sezione rimanda anche alla pagina dedicata `Come seguirci` all’URL `/seguici/`.
