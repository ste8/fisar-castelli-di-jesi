# Homepage

## Calice con vetro naturale — v1.6.98, 15 ottobre 2026

Corretto il calice dell’opzione 3 su riscontro dell’utente: vetro incolore con riflessi neutri al posto della dominante dorata, vino giallo paglierino chiaro; marchio e forma conservati. Asset illustrativo v2, PNG con alpha e WebP 640 × 960/qualità 85 da 67.560 byte, con prompt/provenienza nel JSON dedicato. Nessun ritocco alle foto reali.

Spostamento a sinistra del solo livello: 1.5rem da 64rem (left da −7rem a −8.5rem), 1rem sui formati più piccoli (right da 0 a 1rem). Dimensioni e altezza della hero invariate; testo e volti principali liberi. Opzione 2 e prima prova del calice conservate. Seed allineato alla v2, aggiornamento locale del solo blocco immagine senza reimport.

## Variante con calice, opzione 3 — v1.6.97, 15 ottobre 2026

L’utente conserva il collage panoramico come **opzione 2** e richiede una variante con il calice Castelli di Jesi. Galleria a sei immagini, ordine, cornici e layout di base invariati; il calice è un livello decorativo indipendente, derivato con imagegen dalla precedente scena illustrativa approvata. PNG con trasparenza e WebP 640 × 960, 92.488 byte; vino liscio, marchio leggibile, nessuno sfondo, persona o oggetto aggiunto. Non è una fotografia documentaria né un prodotto reale. Le sei fotografie restano inalterate; Varnelli esclusa.

Blocco Immagine nativo top-level con classe `hero-glass`, separato dalla Galleria `hero-collage`. Il template lo mostra soltanto in presenza della galleria: calice in primo piano sul suo margine sinistro da 64rem, nella colonna destra della fascia fotografica sotto tale soglia. Posizionamento assoluto: nessuna altezza aggiunta; desktop sempre circa 448 px. Larghezza massima del calice 18rem, niente zoom progressivo sui monitor grandi. Sfumatura locale dietro il calice e lieve ombra CSS; testo, CTA e volti principali liberi. Elemento decorativo escluso dall’albero accessibile, immagini documentarie con alt conservati; nessuna animazione.

Rimuovendo soltanto il blocco `hero-glass` si torna all’opzione 2, senza cambiare galleria o CSS di base. Anteprime stabili e istruzioni in `docs/06-mockups/homepage-hero-options.md`; prompt e provenienza in `assets/photography/hero-calice-scontornato-fisar-cdj-v1.json`. Questa prova supera solo l’esclusione del calice AI dalla variante precedente, non quella di Varnelli.

## Parete fotografica panoramica — v1.6.96, 15 ottobre 2026

Il feedback rifiuta la composizione a tre foto perché poco incisiva e troppo alta, escludendo espressamente la scena Varnelli. Nuova selezione di sei fotografie reali: sala di degustazione, due sommelier, sala con botti, calice/bottiglia in cantina, tavolata e tre calici. Nessun calice generato o foto di birre della Libreria media utilizzato. Varnelli non è più nel seed né nella galleria corrente; il vecchio allegato rimane conservato, non pubblicato nella hero.

Da 64rem la parete occupa il lato destro in posizione assoluta, senza determinare l’altezza della fascia. Griglia inclinata con due foto grandi sopra e quattro dettagli sotto, cornici sottili e ombre; niente nastro o ampio margine Polaroid inferiore. Il titolo resta separato e leggibile a sinistra; maschera laterale e clipping sui bordi della fascia raccordano le foto allo sfondo. Inquadrature `cover`, focale dedicato per degustazione/sommelier, dettagli periferici ritagliabili. Altezza minima 28rem, 448 px a 1440 px nella verifica, contro circa 669 px della prova precedente; contenuto libero di espandersi allo zoom, non `max-height` bloccante.

Sotto 64rem mosaico a sei riquadri su tre colonne/due righe, sotto alle CTA: fascia alta 14rem su smartphone e 18rem su tablet, non più una composizione quasi quadrata. Font, copy, link e altre sezioni invariati. La Galleria nativa `hero-collage` resta la fonte modificabile, ora prevista per sei immagini senza link/didascalie; fallback singolo conservato. Seed allineato senza reimport.

Originale JPEG della sala con botti e tre acquisizioni fotografiche dal browser (884 × 604, 498 × 664, 884 × 592) conservati con provenienza esplicita per la prova; non dichiarati originali ad alta risoluzione. WebP ottimizzati locali, nessuna generazione o ritocco dei volti. Questa revisione supera le indicazioni a tre foto e sui ritagli delle prove precedenti.

## Collage di momenti reali — v1.6.95, 15 ottobre 2026

Su richiesta, la fotografia singola lascia spazio a tre scatti reali, con cornici avorio tipo Polaroid, inclinazioni leggere e ombre. Degustazione nella sala in pietra più grande, due sommelier e visita alla Distilleria Varnelli più piccoli. Sovrapposizioni limitate ai margini e alle zone periferiche; nessun volto ritoccato, scena ricostruita, filtro o calice generato mescolato alle foto documentarie. Piccolo nastro decorativo CSS e fondo radiale caldo; testo e CTA invariati.

La sorgente è un blocco Galleria nativo in Home, classe aggiuntiva `hero-collage`, con tre immagini senza link/didascalie; selezione e ordine modificabili dall’editor. Il tema presenta la prima galleria top-level con quella classe nella hero, senza mostrare il restante corpo Home. La composizione corrente è progettata per tre immagini. Se il blocco viene rimosso o è vuoto, resta il fallback dell’immagine in evidenza della v1.6.94. Rendering e immagini responsive nativi; nessun carosello, animazione o richiesta a Facebook a runtime.

Collage a destra da 64rem, sotto il testo sui formati più piccoli; stessa composizione compatta ma cornici e rotazioni più lievi sotto 38rem. Originali delle due foto aggiunte conservati con provenienza; WebP 750 × 1000, qualità 80, da 127.186 e 54.630 byte. Seed aggiunge la galleria solo se manca, riusando gli allegati e conservando il testo introduttivo; nessun reimport. Precedenti foto/hero e revisioni preservate per confronto. Questa prova supera la composizione con singola foto descritta sotto.

## Prova con fotografia reale — v1.6.94, 15 ottobre 2026

Applicata su richiesta l’opzione 1: degustazione nella sala in pietra dalla galleria Facebook della Delegazione (foto `1515150007280198`). Identità, payoff, descrizione e CTA invariati. Da 64rem, due colonne nel contenitore massimo di 76rem: testo a sinistra, fotografia intera a destra; sotto 64rem foto dopo testo e CTA, larga al massimo 36rem. L’immagine conserva il rapporto 4:3, senza overlay, maschera, ingrandimento progressivo o taglio delle persone. Il nuovo layout supera le regole fotografiche/overlay delle iterazioni sotto; il mockup rimane riferimento di tono, non vincolo su questa composizione approvata in prova.

JPEG Facebook 2048 × 1536 conservato con provenienza in `assets/photography/`; WebP 1600 × 1200, qualità 80, 414.464 byte nel plugin. Nessun intervento generativo, ritocco dei volti o alterazione della scena. Immagine in evidenza e srcset nativi, caricamento prioritario, `sizes` adeguato al riquadro e testo alternativo dalla Libreria media. Seed allineato per i nuovi ambienti, senza reimport. Variante generata v4 e allegato precedente conservati per confronto; resto della homepage invariato.

## Asset conservati — 15 ottobre 2026

Per il commit restano soltanto PNG originale, JSON di provenienza e WebP della v4 attuale. Le prove v1–v3 (nove file PNG/WebP/JSON) sono rimosse dal repository; le sezioni storiche sotto descrivono le iterazioni, non ulteriori asset da distribuire. La Libreria media locale, compresi gli allegati precedenti, non è modificata. Il riferimento attuale del seed resta la v4.

## Velatura desktop meno invasiva — v1.6.93, 15 ottobre 2026

Il riscontro su portatile rileva che il calice, ora più centrale nell’inquadratura v4, cade sotto la parte troppo scura dell’overlay precedente. Da 64rem la sfumatura orizzontale passa da opacità 94% a sinistra a 80% al 28%, 28% al 43% e 4% dal 58%: il titolo conserva il fondo scuro, mentre marchio del calice e persone rimangono luminosi. Un secondo gradiente verticale leggero, trasparente a metà altezza e al 35% sul bordo inferiore, sostiene descrizione e pulsanti.

Sotto 64rem resta l’overlay precedente. Nessuna modifica all’immagine, alla maschera sui bordi, al limite 90rem, ai punti focali o ai testi. Questa revisione supera l’indicazione di overlay invariato della v1.6.92.

## Scena di degustazione con partecipanti — v1.6.92, 15 ottobre 2026

Applicata su approvazione l’anteprima con sommelier più definita e due partecipanti alla sua destra, che danno un destinatario al gesto di presentazione della bottiglia. Rimossi tovagliolo e ciuffo vegetale vicino al piatto destro. Calice con logo e vino limpido conservati; scena immaginaria, non foto di un evento della delegazione. La nuova inquadratura è più panoramica della v3 e la sommelier precede ora l’area di dissolvenza della maschera desktop.

Asset selezionato `assets/photography/hero-calice-degustazione-fisar-cdj-v4.png`, 2110 × 745 px; WebP qualità 85 da 126.976 byte in `plugin/fisar-cdj-core/assets/demo/hero-calice-degustazione-fisar-cdj-v4.webp`. Prompt delle due modifiche preparatorie e provenienza nel JSON omonimo. Le anteprime non selezionate non sono caricate nel sito; la v3 e gli allegati precedenti restano disponibili nella sola Libreria media locale.

Immagine in evidenza nativa, renderer/srcset e limite 90rem invariati. Punti focali adattati al calice ora più centrale nell’originale: 45% su smartphone, 42% da 38rem, `center 10%` da 64rem. Su tablet/mobile i partecipanti possono uscire dall’inquadratura, come richiesto; priorità a identità, testo e calice. Nessun cambiamento a testi, CTA, altezza, overlay o altre sezioni. Seed allineato, nessun reimport.

## Riflessi del vino e raccordo destro — v1.6.91, 15 ottobre 2026

Il riscontro sullo screenshot rileva riflessi geometrici già presenti nel PNG originale e un raccordo troppo brusco dopo la sommelier. Correzione mirata con image_gen: vino bianco limpido, senza trama a cellette, e sfondo all’estrema destra più scuro, al posto della finestra luminosa. Conservati composizione, calice personalizzato, sommelier in smoking con tastevin e bottiglia. Maschera desktop più graduale: opacità piena fino all’80%, 95% all’88%, 60% al 95% e trasparenza al bordo, invece del solo ultimo 6%.

La prova v3 usava un WebP 1942 × 809 px/qualità 85 da 104.746 byte, oggi sostituito dalla v4. I relativi file di prova non sono più distribuiti nel repository; l’allegato locale rimane nella Libreria media. All’epoca era stata sostituita solo l’immagine in evidenza della home via API, allineando il seed senza reimport. Limite 90rem, punti focali, testi, CTA, layout e altre pagine erano invariati.

## Scala stabile della hero sui desktop larghi — v1.6.90, 15 ottobre 2026

Il riempimento `cover` dell’intera finestra ingrandiva progressivamente calice e sommelier, tagliando la scena in verticale e rendendo più evidente la limitata risoluzione del file. Su richiesta dell’utente, da 90rem la foto occupa un box centrato di larghezza fissa 90rem (1440 px alla scala base); lo spazio aggiuntivo usa un fondo scuro caldo `#160f10`. Una maschera laterale sfuma l’immagine sul fondo senza duplicarla, deformarla o introdurre fasce nette.

Altezza della hero, testo, CTA e overlay conservati. Sotto 90rem il comportamento precedente non cambia, compresi punto focale tablet e ritaglio mobile. `sizes` aggiornato a `(min-width: 90rem) 90rem, 100vw`, coerente con il box e i derivati nativi. Questa revisione prevale sull’immagine a tutta larghezza dei paragrafi precedenti; non aumenta la risoluzione originale, ma ne limita l’ingrandimento. Nessun nuovo asset, JS o aggiornamento WordPress.

## Hero con sommelier sullo sfondo — v1.6.89, 15 ottobre 2026

Su approvazione dell’utente applicata la variante con una sommelier immaginaria in smoking, papillon, tastevin e bottiglia. Una foto fornita dall’utente è usata soltanto come riferimento per forma del tastevin e posizione della catena, non per riprodurre il soggetto. Il calice resta protagonista; testi, CTA, altezza e overlay invariati. Questa decisione supera lo stato di sola anteprima riportato sotto.

La prova v2 usava un WebP 1942 × 809 px/qualità 85 da 105.760 byte, oggi sostituito dalla v4. I relativi file di prova sono stati rimossi dal repository; gli allegati precedenti restano nella Libreria media locale. Il seed selezionava la variante senza modificare la versione demo o reimportare dati esistenti.

Conservato il renderer nativo con srcset. Su smartphone il ritaglio privilegia il calice e la leggibilità, senza promettere la sommelier interamente visibile; tra 38 e 64rem punto focale a destra per evitare il taglio del viso sul bordo. Da 64rem resta `center 10%`. Nessuna immagine separata o nuova richiesta remota.

## Hero con calice personalizzato — v1.6.88, 15 ottobre 2026

Applicata in prova l’immagine illustrativa generata di un calice con logo FISAR e scritta Castelli di Jesi, protagonista a destra, con zona scura libera a sinistra. Identità, payoff, descrizione, pulsanti e composizione della home invariati. Il calice non è un prodotto reale disponibile e la scena non documenta un evento della delegazione.

La prima prova usava un WebP 1942 × 809 px, qualità 85, da 99.182 byte, oggi sostituito dalla v4. PNG, WebP e JSON intermedi non fanno più parte del repository; l’allegato locale resta nella Libreria media. Il seed usava questo asset nei nuovi import; nell’ambiente esistente era cambiata soltanto l’immagine in evidenza della home, conservando la precedente in Libreria media, senza reimport o aggiornamento del testo.

Il tema usa il renderer nativo WordPress con dimensioni e `srcset`, caricamento eager e priorità alta; immagine decorativa con alt vuoto, perché l’identità è già nell’H1. Punto focale a destra sui formati stretti e ritaglio superiore sul desktop, senza cambiare altezza della fascia o overlay. Varianti con una sommelier, anche in smoking e tastevin, sono per ora anteprime non applicate.

## Come seguirci condiviso — v1.6.71

Il 10 ottobre 2026 confermate home e pagina autonoma `/seguici/`, senza rimandare la voce del menu a un’ancora della homepage. La home non mostra più `Scopri tutti i canali`, perché tutte e quattro le opzioni sono già presenti. Card dei canali e contenuto del pannello newsletter condivisi: medesimi titolo, descrizione e modulo, con gerarchia H3 in home/H2 nella pagina e ID distinti. Testo comune del pannello: `Ricevi un riepilogo delle notizie più importanti della Delegazione.`. Introduzioni specifiche dei contesti conservate, come le ancore, la posizione dopo la Carta e i link del footer. Questa decisione prevale sul rimando alla pagina descritto sotto.

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

- Gruppo WhatsApp (link e dicitura aggiornati il 12 ottobre 2026; dettagli in `docs/02-information-architecture/navigation.md`);
- Instagram;
- Facebook;
- Newsletter Mailchimp.

Le CTA social usano gli URL del menu WordPress `social`. La newsletter usa un form HTML integrato nello stile del tema e invia nome, cognome ed email direttamente a Mailchimp, senza salvare i dati in WordPress e senza caricare CSS o JavaScript remoti. La sezione rimanda anche alla pagina dedicata `Come seguirci` all’URL `/seguici/`.
