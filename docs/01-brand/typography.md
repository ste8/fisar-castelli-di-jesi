# Typography

## Card Corsi dell’archivio — v1.6.51

Per le sole card in modalità Automatico, `Corso Sommelier` Poppins 500 da 16–18 px come introduzione; livello e città più evidenti a 20–24 px con interlinea 1.3 (livello 600, città 500). Provincia 16–18 px/400 tra parentesi accanto alla città. Livello indivisibile e ritorni a capo naturali, senza troncamenti. Home, dettaglio e titoli personalizzati conservano la propria tipografia. Revisione approvata il 7 ottobre 2026, prevalente sulle scale generiche delle card sotto.

## Allineamento Corsi — v1.6.48

Il 6 ottobre 2026, su richiesta dell’utente, archivio e dettaglio Corsi adottano la gerarchia Poppins degli Eventi: H1 500 da 32–44 px, sezioni archivio 32–40 px; corpo dettaglio 16–17 px, H2 20–24 px, H3 20 px, H4 18 px, H5/H6 17 px, grassetti 600. Etichette del riepilogo 16 px/600 maiuscole, scadenza 18 px nel riepilogo e 22 px nel pannello finale. Editor Corsi alla scala desktop corrispondente. Font già self-hosted, nessun asset remoto aggiunto. Questa revisione prevale sui riferimenti successivi al mantenimento dei titoli serif o della tipografia invariata dei Corsi; altre pagine non cambiano.

## Stato

Approvato per la V1.

Aggiornato il 4 ottobre 2026: Poppins sostituisce Inter nell’intero sito, come approvato dall’utente dopo il confronto delle anteprime. Restano due sole famiglie. Questa revisione prevale sulle indicazioni tipografiche del concept iniziale.

Questa specifica definisce i font e le regole tipografiche del sito FISAR Castelli di Jesi. La direzione deve essere coerente con il principio di **eleganza calda** già definito nel design system: titoli autorevoli ed editoriali, testi molto leggibili, tono contemporaneo ma non freddo.

## Font family

### Display / headings

**Cormorant Garamond**

Uso:
- `h1`
- `h2`
- titoli di sezione
- payoff della hero
- eventuali citazioni o frasi manifesto

Pesi:
- `600` — SemiBold, uso principale
- `700` — Bold, solo per maggiore enfasi

Fallback:

```css
font-family: "Cormorant Garamond", Georgia, "Times New Roman", serif;
```

Non usare i pesi più sottili nel frontend.

Eccezione esplicita: l’H1 della homepage “FISAR / Delegazione / Castelli di Jesi” usa Poppins `400`, `clamp(2rem, 4.5vw, 3rem)` e interlinea `1.1`, come nella proposta approvata. Non è un nuovo logo e non modifica l’asset ufficiale. Gli altri H1 restano Cormorant Garamond.

Approvato il 4 ottobre 2026 dopo la prova sul sito: i cinque titoli di sezione della homepage (Prossimi eventi, I nostri corsi, Ultime notizie dalla Delegazione, La nostra Carta dei Valori e Come seguirci) usano Poppins `500`, `clamp(2rem, 4vw, 2.5rem)` e interlinea `1.08`. Il titolo della fascia Valori è aggiornato il 5 ottobre 2026.

Su successiva richiesta, Poppins Medium è in prova anche per i titoli delle card Eventi, Corsi e News, dei canali e del pannello newsletter, con peso `500` e interlinea `1.3`. Le voci della fascia Valori usano Poppins `500`, come dettagliato nella revisione del 5 ottobre sotto. Questi componenti condividono la stessa tipografia in homepage, archivi e pagina Seguici. Payoff e heading editoriali delle pagine interne restano Cormorant; nessuna conversione globale degli heading o modifica al default dell’editor.

### UI / body

Il 5 ottobre 2026 l’allineamento richiesto dall’utente si estende all’archivio e al dettaglio Eventi: H1 Poppins `500` 32–44 px, titoli di sezione dell’archivio 32–40 px, heading del corpo e del riquadro iscrizioni 20–24 px, con gerarchia ridotta per H3–H6. Corpo del dettaglio 16–17 px, interlinea `1.7`, grassetti `600`; estratto 16–18 px. Queste eccezioni prevalgono sul default serif, senza modificarlo per le altre pagine. Riferimenti: `docs/03-pages/events.md` e `docs/03-pages/event.md`.

Affinamento v1.6.27: H4 del corpo Evento a `1.125rem` (18 px), anche nell’editor, per distinguerlo dal testo normale. H3 resta a 20 px; H5 e H6 restano a 17 px. Poppins Medium `500` e interlinea `1.35` invariati. Nessuna modifica alla tipografia dei Corsi o di altre pagine.

Affinamento v1.6.31 dei riepiloghi Evento: etichette principali iniziali e finali Poppins `600`, 16 px, maiuscolo e tracking `0.05em`. Etichetta `Prenotazioni entro` e data sulla stessa linea di testo, entrambe Poppins `600` da 18 px nel riepilogo e 22 px nel pannello finale, con ritorno a capo naturale. Non riguarda i titoli editoriali, l’editor, le sottoetichette Accoglienza/Inizio/Fine o i Corsi.

Il successivo v1.6.32 aumenta soltanto le sottoetichette Accoglienza/Inizio/Fine del dettaglio Evento da 13 a 14 px, conservando peso 500, interlinea 1.35 e orari da 16 px/600. La nota flessibile da 14 px passa al colore del corpo `--color-ink`, senza modificare altre note o i Corsi.

Eccezione aggiunta il 5 ottobre 2026 su richiesta di omogeneità con la homepage: nella sola pagina Carta dei Valori, H1 e titoli dei sette capitoli usano Poppins `500`, rispettivamente 32–44 px e 20–24 px, con interlinea `1.2` e `1.35`. Payoff in Cormorant `600`; corpo 16–17 px e grassetti `600`. Le altre pagine interne conservano i propri heading editoriali. Per i dettagli prevale `docs/03-pages/values.md`.

Affinamento v1.6.38: nel pannello del dettaglio Evento, la sola frase di partecipazione libera senza prenotazione usa Poppins 18 px/600 e interlinea 1.6, invece di 16 px. Gli altri avvisi mantengono dimensioni e pesi precedenti; nessun cambiamento ai Corsi.

**Poppins**

La successiva revisione della hero fotografica della Carta conserva H1 e capitoli nelle stesse scale. La frase sui principi ha più rilievo: Poppins `500` da 18–24 px con interlinea `1.45`. La nota sull’ambito locale, successivamente spostata nel riquadro sotto l’immagine, usa Poppins `400` a 16 px con interlinea `1.6`. Il payoff torna nel corpo, sopra `Chi siamo`, in Cormorant `600` da 20–24 px, senza `nowrap` o clipping.

Uso:
- corpo testo
- nome della Delegazione nella hero (`400`)
- titoli di sezione della homepage (`500`, approvati)
- titoli di card, canali, newsletter e voci della fascia Valori (`500`, prova attiva)
- menu
- bottoni e CTA
- metadata
- date
- card
- form
- tabelle

Pesi:
- `400`
- `500`
- `600`
- `700`

Fallback:

```css
font-family: Poppins, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
```

## Perché questa combinazione

Cormorant Garamond porta il carattere editoriale, caldo ed elegante desiderato. Poppins garantisce ottima leggibilità, resa affidabile su mobile e chiarezza nelle informazioni pratiche.

Il contrasto tra serif nei titoli e sans-serif nel testo deve essere percepibile ma sobrio.

## Regole generali

- Non usare più di queste due font family nel sito.
- Il logo è un asset grafico e non deve essere ricostruito usando i font del sito.
- Non usare Cormorant Garamond per testi lunghi, tabelle, form o piccoli metadata.
- Evitare pesi `300` o inferiori.
- Body text minimo `16px`.
- Usare unità relative (`rem`) per le dimensioni principali.
- Non disabilitare lo zoom del browser.

## Design tokens

```css
:root {
  --font-display: "Cormorant Garamond", Georgia, "Times New Roman", serif;
  --font-body: Poppins, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;

  --font-size-xs: 0.8125rem;
  --font-size-sm: 0.9375rem;
  --font-size-base: 1rem;
  --font-size-md: 1.125rem;
  --font-size-lg: 1.25rem;
  --font-size-xl: 1.5rem;

  --line-height-tight: 1.1;
  --line-height-heading: 1.15;
  --line-height-body: 1.6;
  --line-height-ui: 1.35;
}
```

## Headings

### H1

```css
font-family: var(--font-display);
font-weight: 600;
font-size: clamp(2.75rem, 6vw, 5.25rem);
line-height: 1.02;
letter-spacing: -0.02em;
```

### H2

Per “Prossimi eventi”, “I nostri corsi”, “Ultime News” e sezioni equivalenti.

La regola seguente resta il default generale. Per i cinque titoli di sezione della homepage prevale Poppins come descritto sopra, con dimensione massima `2.5rem` (40 px a scala base), non `3.25rem`. Titoli dei canali e del pannello newsletter seguono la prova dei componenti anche quando sono H2.

```css
font-family: var(--font-display);
font-weight: 600;
font-size: clamp(2rem, 4vw, 3.25rem);
line-height: 1.08;
letter-spacing: -0.015em;
```

I titoli di sezione possono essere accompagnati dalla breve linea decorativa prevista dal design system.

### H3

```css
font-family: var(--font-display);
font-weight: 600;
font-size: clamp(1.5rem, 2.5vw, 2rem);
line-height: 1.15;
```

Per piccoli heading funzionali è ammesso Poppins `600`.

## Body text

```css
font-family: var(--font-body);
font-size: 1rem;
font-weight: 400;
line-height: 1.6;
```

Per testi editoriali lunghi, come Carta dei Valori o News:

```css
font-size: clamp(1rem, 1.3vw, 1.125rem);
line-height: 1.7;
max-width: 68ch;
```

Evitare colonne di testo troppo larghe su desktop.

## Navigation

```css
font-family: var(--font-body);
font-size: 0.9375rem;
font-weight: 500;
line-height: 1.2;
```

La CTA **Unisciti a noi** usa Poppins `600`.

## Buttons e CTA

```css
font-family: var(--font-body);
font-size: 0.9375rem;
font-weight: 600;
line-height: 1.2;
```

Non usare `text-transform: uppercase` come regola generale.

## Top bar

```css
font-family: var(--font-body);
font-size: 0.8125rem;
font-weight: 500;
line-height: 1.3;
```

Il payoff nella top bar resta discreto e usa Poppins.

## Card

### Titolo card
La prova v1.6.7 prevale sulla scelta v1.3: Poppins `500` per Eventi, Corsi e News, con interlinea `1.3`. Dimensioni conservate: `1.375rem` nelle card compatte della home, `1.55rem` negli altri contesti. Canali: `1.75rem`; pannello newsletter: scala esistente. Nessun titolo viene troncato.

### Metadata
Date, luogo, livello del corso e informazioni pratiche:

```css
font-family: var(--font-body);
font-size: 0.875rem;
font-weight: 500;
line-height: 1.4;
```

## Carta dei Valori

- titolo pagina: Cormorant Garamond `600`
- titoli dei sette punti: Cormorant Garamond `600`
- testo: Poppins `400`
- eventuali callout: Cormorant Garamond `600` o `700`

La priorità resta la leggibilità, non l'ornamento.

Nella sintesi della homepage, revisionata il 5 ottobre 2026, i sei titoli dei principi sono H3 Poppins `500`, `1.25rem`, interlinea `1.3`; l’H2 della fascia resta Poppins `500` come gli altri titoli di sezione. Questa revisione sostituisce le precedenti quattro voci da `1.5rem`, senza cambiare la pagina completa della Carta.

## Tabelle

Il calendario delle lezioni usa esclusivamente Poppins.

Header: `font-weight: 600`.

Celle:

```css
font-size: 0.9375rem;
line-height: 1.45;
```

## Form

Label: Poppins `600`.

Input, select e textarea:

```css
font-family: var(--font-body);
font-size: 1rem;
```

Il placeholder non sostituisce mai la label.

## Font loading

Per la V1 i font devono essere **self-hosted**, non caricati da Google Fonts nel browser.

Motivazioni:
- privacy
- performance prevedibile
- nessuna dipendenza esterna runtime
- migliore controllo del caching

Preferire `WOFF2`.

Usare:

```css
font-display: swap;
```

Caricare solo i pesi realmente utilizzati:

Cormorant Garamond:
- 600
- 700

Poppins:
- 400
- 500
- 600
- 700

## Performance

- Preload solo dei font indispensabili above-the-fold.
- Evitare preload di tutti i pesi.
- Precaricare il file variabile Cormorant Garamond `600–700` e Poppins Regular `400`; i quattro pesi Poppins sono file statici, richiesti dal browser solo quando necessari.
- Caching lungo per file versionati.
- Fallback stack sempre definito.
- Evitare layout shift significativo durante il font loading.

## Accessibilità

- body text minimo `16px`
- line-height body circa `1.6`
- nessun testo essenziale troppo sottile
- contrasto conforme alle specifiche accessibility
- nessun testo lungo in all caps
- nessun testo giustificato
- layout stabile almeno al `200%` di zoom
- righe non eccessivamente lunghe

## WordPress

Caricare i font centralmente nel tema.

Struttura suggerita:

```text
theme/
└── assets/
    └── fonts/
        ├── cormorant-garamond/
        └── poppins/
```

Non duplicare `@font-face` tra componenti. Usare i design tokens e non nomi di font hardcoded ripetuti.

## Indicazioni per Codex

Questa specifica è la source of truth per la tipografia della V1.

Usare:
- **Cormorant Garamond** per display/headings
- **Poppins** per body e UI

Non scegliere font alternativi e non dedurre il font dal logo o dai mockup.

Se un componente non è esplicitamente descritto, usare Poppins per gli elementi funzionali e Cormorant Garamond soltanto per elementi chiaramente editoriali/display.
