# Assunzioni di implementazione V1

Data: 29 agosto 2026.

## Precedenza delle specifiche

Non sono emersi conflitti sostanziali. Quando un riepilogo omette un dettaglio, prevale il documento più specifico:

- `docs/04-plugin/plugin-spec.md` prevale sui due riepiloghi CPT; per questo l'Evento include anche l'ora di fine e i canali di iscrizione dettagliati.
- `docs/00-foundation/decisions.md` e `docs/03-pages/homepage.md` prevalgono sul mockup per struttura, copy delle CTA e presenza di News.
- il mockup è una direzione visiva, non un riferimento pixel-perfect, come dichiarato in `docs/06-mockups/homepage-concept-01.md`.
- Il riscontro del 31 agosto 2026 rende il mockup autorevole anche per gerarchia, proporzioni e composizione della homepage: hero compatto, icone social, Quattro Porte illustrate su fondo caldo, Eventi e Corsi affiancati e immagini evento quadrate. Rimane esclusa la riproduzione pixel-perfect.

## Scelte per dettagli aperti

- Tipografia: l’approvazione del 4 ottobre 2026 aggiorna `docs/01-brand/typography.md`: Poppins `400–700` sostituisce Inter per corpo e UI ed è usato a peso `400` per il nome della Delegazione nella hero. Cormorant Garamond `600–700` resta per titoli editoriali e payoff. Due sole famiglie, WOFF2 self-hosted con subset latino e nessun servizio font remoto a runtime.
- Hero: usa l'immagine in evidenza della homepage quando presente e un'illustrazione locale come fallback. I dati demo importano una fotografia generata, calda e conviviale, dichiaratamente sostituibile con una fotografia reale della Delegazione; il mockup non viene ritagliato né riutilizzato come sorgente.
- Immagini demo: il paesaggio della fascia Valori è un asset decorativo generato. Le locandine demo degli Eventi sono quadrate; le locandine reali non vengono ritagliate aggressivamente perché il tema conserva `object-fit: contain`.
- Iconografia: social, Quattro Porte e Valori usano SVG minimali inclusi nel tema, decorativi e accompagnati da label testuali accessibili.
- News: usa i post WordPress nativi; non viene creato un terzo CPT.
- Social: usa un menu WordPress dedicato. I link demo sono esplicitamente sostituibili, evitando campi custom non previsti.
- Newsletter: dal 23 settembre 2026 il provider indicato è Mailchimp. Il tema usa il form embedded fornito, ripulito da CSS, JavaScript e badge remoti; nome, cognome ed email vengono trasmessi direttamente a Mailchimp e non memorizzati in WordPress. Il metodo di opt-in è gestito nell’Audience Mailchimp; prima della pubblicazione vanno verificati double opt-in, messaggi di conferma e informativa privacy.
- Pagine legali: contenuti demo chiaramente indicati come bozze, da validare prima della pubblicazione.

## Navigazione associativa — 4 ottobre 2026

Il riscontro dell’utente prevale sulla precedente navigazione piatta: `Chi siamo` è la prima voce, con sottomenu come descritto in `docs/02-information-architecture/navigation.md`. Consiglio e incarichi e Statuto sono sezioni della pagina locale, non nuove pagine. Non sono stati forniti nomi ufficiali, mandato o PDF della Delegazione: restano indicazioni editoriali esplicite da completare prima della pubblicazione. Il testo nazionale descrive la FISAR APS, senza attribuire alla Delegazione una qualifica giuridica locale non verificata.

## Identità in homepage — 4 ottobre 2026

La scelta della proposta Poppins e la successiva conferma “al posto di Inter” autorizzano la sostituzione globale del sans-serif, non l’aggiunta di una terza famiglia. Il nuovo H1 identifica FISAR e la Delegazione Castelli di Jesi; il payoff rimane secondario. Copy e CTA sono descritti in `docs/03-pages/homepage.md` e prevalgono sul vecchio titolo manifesto del mockup. Su successiva richiesta dell’utente, la fascia istituzionale sotto la hero è rimossa: le informazioni associative restano nelle pagine della sezione `Chi siamo`. Nessuna modifica ai loro contenuti editoriali o alla sequenza delle altre sezioni.
