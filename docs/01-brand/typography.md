# Typography

## Stato

Approvato per la V1.

Questa specifica definisce i font e le regole tipografiche del sito FISAR Castelli di Jesi. La direzione deve essere coerente con il principio di **eleganza calda** già definito nel design system: titoli autorevoli ed editoriali, testi molto leggibili, tono contemporaneo ma non freddo.

## Font family

### Display / headings

**Cormorant Garamond**

Uso:
- `h1`
- `h2`
- titoli di sezione
- hero title
- eventuali citazioni o frasi manifesto

Pesi:
- `600` — SemiBold, uso principale
- `700` — Bold, solo per maggiore enfasi

Fallback:

```css
font-family: "Cormorant Garamond", Georgia, "Times New Roman", serif;
```

Non usare i pesi più sottili nel frontend.

### UI / body

**Inter**

Uso:
- corpo testo
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
font-family: Inter, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
```

## Perché questa combinazione

Cormorant Garamond porta il carattere editoriale, caldo ed elegante desiderato. Inter garantisce ottima leggibilità, resa affidabile su mobile e chiarezza nelle informazioni pratiche.

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
  --font-body: Inter, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;

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

Per piccoli heading funzionali è ammesso Inter `600`.

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

La CTA **Unisciti a noi** usa Inter `600`.

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

Il payoff nella top bar resta discreto e usa Inter.

## Card

### Titolo card
Cormorant Garamond `600`, dimensione minima indicativa `1.375rem`.

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
- testo: Inter `400`
- eventuali callout: Cormorant Garamond `600` o `700`

La priorità resta la leggibilità, non l'ornamento.

## Tabelle

Il calendario delle lezioni usa esclusivamente Inter.

Header: `font-weight: 600`.

Celle:

```css
font-size: 0.9375rem;
line-height: 1.45;
```

## Form

Label: Inter `600`.

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

Inter:
- 400
- 500
- 600
- 700

## Performance

- Preload solo dei font indispensabili above-the-fold.
- Evitare preload di tutti i pesi.
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
        └── inter/
```

Non duplicare `@font-face` tra componenti. Usare i design tokens e non nomi di font hardcoded ripetuti.

## Indicazioni per Codex

Questa specifica è la source of truth per la tipografia della V1.

Usare:
- **Cormorant Garamond** per display/headings
- **Inter** per body e UI

Non scegliere font alternativi e non dedurre il font dal logo o dai mockup.

Se un componente non è esplicitamente descritto, usare Inter per gli elementi funzionali e Cormorant Garamond soltanto per elementi chiaramente editoriali/display.
