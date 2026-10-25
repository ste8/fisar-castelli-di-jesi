# Design system

## Identità e tono

Eleganza calda: molto bianco, spazio, fotografie reali e conviviali, bordeaux misurato e oro FISAR come accento. Identità chiaramente **FISAR Delegazione Castelli di Jesi**; niente “autonoma” nel titolo della hero. Cultura del vino aperta a tutti, non soltanto formazione professionale.

Voce competente, diretta, accogliente, curiosa e informale; evitare burocratese, tecnicismi inutili, toni elitari, commerciali aggressivi o formule artificiose. Riferimento istituzionale: [Carta V2](sources/carta-dei-valori-v2.md), distinta dalle sintesi editoriali della Home. Non reinterpretare il brand per uniformarlo a un modello grafico generico.

## Colori e logo

| Colore | Ruolo |
|---|---|
| Oro FISAR `#b9aa54` | Marchio, linee e accenti |
| Bordeaux `#3d101d` | Barra di servizio, hero interne, evidenze |
| Crimson `#b33c61` | Accenti selettivi |
| Testo `#282332` | Corpo e titoli |
| Bianco `#ffffff`, tinta calda `#f7f3ef` | Fondi principali e sezioni |
| Giallo `#ffda3d` | Solo offerte Corsi, non sostituisce l’oro del marchio |

Valori CSS e varianti effettive in `theme/fisar-cdj/assets/css/main.css`; modificare i token, non creare una seconda palette per ogni pagina. Contrasto da verificare per il contesto, non garantito dalla sola scelta della tinta.

Asset ufficiali in `assets/brand/`, originale in `assets/logo-original/`. Header usa il fallback `logo-orizzontale-header.svg`, con FISAR, tricolore e CASTELLI DI JESI allineati; logo personalizzato WordPress prioritario. Non deformare, ruotare, alterare i colori, eliminare il tricolore o rigenerare il marchio con AI. Il logo non è composto con Poppins.

## Tipografia attuale

- **Poppins 400/500/600/700**: corpo, UI, navigazione, hero Home e titoli delle sezioni/card; titoli di Eventi/Corsi/Blog, Carta e pagine associative allineati.
- **Cormorant Garamond 600–700**: payoff, citazioni e residui contesti editoriali previsti dal tema, non testo lungo o metadati.
- WOFF2 self-hosted, subset latino, `font-display: swap`; preload above-the-fold di Poppins 400 e Cormorant. Nessun font remoto, Inter non usato.

| Contesto | Scala di riferimento desktop |
|---|---|
| Identità Home | Poppins 400, 32–48 px, interlinea 1.1 |
| Sezioni Home/archivi | Poppins 500, 32–40 px |
| Titolo pagina interna | Poppins 500, 32–44 px |
| Card | Poppins 500, circa 20–24 px secondo contesto |
| Corpo interno | Poppins 400, 16–17 px / 1.7; UI almeno 16 px salvo metadati |
| H2/H3/H4 del corpo Eventi/Corsi | Poppins 500, 24/20/18 px alla scala desktop |
| Grassetti editoriali | Poppins 600 |

Scale fluide, niente riduzione del font per impedire un ritorno a capo. Le eccezioni dimensionali dei componenti vivono nel CSS. Il canvas degli editor Eventi/Corsi usa gli stessi font e gerarchia, non i grandi titoli predefiniti di WordPress.

## Composizione

- Header bianco e top bar bordeaux con gruppo WhatsApp/Instagram/Facebook/Newsletter, senza payoff. Menu a due livelli accessibile; CTA Diventa socio.
- Home: testo a sinistra, collage/calice a destra da 64rem; nessuna fascia fotografica sotto il testo sui formati stretti. Quattro gruppi attuali da sei foto, cornici avorio, rotazioni leggere, toni non distruttivi. Fotografie reali non ricostruite; calice v2 esplicitamente illustrativo.
- Segue Eventi/Corsi, Blog, Carta dei Valori, Come seguirci e footer. Niente Quattro Porte o fascia di affiliazione sotto la hero.
- Hero interne bordeaux compatte per archivi/pagine associative/privacy/Seguici; Carta dei Valori con propria foto di vigneti, overlay e nota locale sotto l’immagine. Non riciclare la foto Home per tutte le intestazioni.
- Dettagli Eventi/Corsi: locandina contenuta in alto a destra; corpo centrale circa 48rem. Calendario Corso autonomo fino al contenitore largo 76rem, non costretto alla larghezza del testo.
- Dettaglio Blog: immagine quadrata contenuta a destra del titolo, intera su bianco, non un’immagine enorme sopra il corpo.
- Footer unico bianco, distinto dal corpo a tinta calda delle pagine dove previsto. Nessuna seconda fascia bordeaux di footer.

## Componenti da preservare

Locandine Eventi/Corsi quadrate su bianco, immagine intera; immagini Blog quadrate nelle card, ritaglio soltanto visivo. Card con separatore leggero tra titolo e informazioni; pin decorativo anche per Online. Eventi mostrano mese esteso se entra, altrimenti abbreviato, senza cambiare il testo accessibile completo.

Card compatte Home senza abstract Eventi/Corsi; archivi con descrizioni previste. Titoli e link accessibili completi: “Dettagli evento”, “Dettagli corso”, “Leggi l’articolo” con suffisso `.screen-reader-text` del titolo. Titoli non troncati per mantenere artificialmente card uguali.

Date/orari/luogo leggibili: Accoglienza, Inizio ed eventuale Fine sulla stessa riga quando possibile, wrapping naturale ai forti ingrandimenti. Etichette principali maiuscole coerenti, nome sede distinto dall’indirizzo; ibrido indicato sopra il luogo, Online evidente. Scadenza con “Prenotazioni entro” e data di pari rilievo, nota dentro il box.

Un solo pannello finale di iscrizione/contatto, quote vicine ai canali, scorciatoia nel riepilogo iniziale. Fasce sold-out sotto la locandina, non sopra. Offerta Corsi con cornice e fondo gialli, scadenza facoltativa, senza cambiare prezzi o logiche del plugin.

Carta: testo integrale con sette capitoli e icone decorative oro; Home con introduzione e sei sintesi, non sostituti del documento. Nota locale informativa con icona info, non allarme. In Delegazione: indice, Consiglio distinto da incarichi non elettivi, Squadra, Statuto e Storia finale; no accordion o nominativi inventati.

Seguici: card bianche, fondo caldo comune e pannello newsletter bordeaux, spaziatura compatta. Stessi componenti e descrizioni della Home, non due versioni da mantenere separatamente.

## Responsive e accessibilità

Mobile-first. Breakpoint del componente, non un’imposizione globale: 38rem per varie griglie, 64rem per hero affiancata; footer quattro colonne solo quando c’è spazio. Testare almeno 320, 390, 768, 1023/1024 e 1440 px, più zoom e testi lunghi. Nessun overflow della pagina: la tabella lezioni può scorrere nel proprio riquadro focusabile.

HTML semantico, un H1 frontend, H2/H3 coerenti, label dei campi, intestazioni di tabella, recapiti testuali oltre ai pulsanti. Focus visibile e navigazione da tastiera di menu, sottomenu e carosello; Escape chiude il sottomenu e restituisce il focus. Non bloccare lo zoom e non affidare contenuti essenziali a colore, icona o hover.

Alt informativi per foto, alt vuoto/`aria-hidden` per decorazioni e icone. Carosello con nomi completi, indicatore attivo e pausa; movimento ridotto senza automatismo/dissolvenze, navigazione manuale disponibile. I controlli attuali sono discreti ma conservano aree cliccabili e focus. Non nasconderli soltanto per motivi estetici.

Il sito implementato è il riferimento visivo, non una giustificazione per trasformare un bug in requisito. Se codice, risultato e queste regole divergono, segnalare la discrepanza prima di introdurre una reinterpretazione. Vecchi mockup e screenshot intermedi sono nella storia Git, non specifiche correnti.
