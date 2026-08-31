# Assunzioni di implementazione V1

Data: 29 agosto 2026.

## Precedenza delle specifiche

Non sono emersi conflitti sostanziali. Quando un riepilogo omette un dettaglio, prevale il documento più specifico:

- `docs/04-plugin/plugin-spec.md` prevale sui due riepiloghi CPT; per questo l'Evento include anche l'ora di fine e i canali di iscrizione dettagliati.
- `docs/00-foundation/decisions.md` e `docs/03-pages/homepage.md` prevalgono sul mockup per struttura, copy delle CTA e presenza di News.
- il mockup è una direzione visiva, non un riferimento pixel-perfect, come dichiarato in `docs/06-mockups/homepage-concept-01.md`.

## Scelte per dettagli aperti

- Tipografia: Georgia per i titoli e stack system sans-serif per il testo. La specifica la lascia da definire; la soluzione evita download esterni ed è leggibile e performante.
- Hero: usa l'immagine in evidenza della homepage quando presente e un'illustrazione locale come fallback. Non viene ritagliato il mockup e non viene simulata una fotografia ufficiale inesistente.
- News: usa i post WordPress nativi; non viene creato un terzo CPT.
- Social: usa un menu WordPress dedicato. I link demo sono esplicitamente sostituibili, evitando campi custom non previsti.
- Newsletter: nella V1 è una CTA accessibile verso i Contatti; non vengono memorizzati indirizzi né simulate integrazioni con provider non specificati.
- Pagine legali: contenuti demo chiaramente indicati come bozze, da validare prima della pubblicazione.

