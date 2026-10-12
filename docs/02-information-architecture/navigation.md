# Navigation

## Link social — v1.6.82 / plugin v1.3.26, 12 ottobre 2026

URL forniti dalla Delegazione, nel menu WordPress `social` condiviso da top bar, footer e card di home/Seguici:

- Facebook: `https://www.facebook.com/FISAR.Castelli.di.Jesi`;
- Instagram: `https://www.instagram.com/fisarcastellidijesi/`;
- WhatsApp: `https://chat.whatsapp.com/BYeE6Jg3LcYDvnmkr1X1xY`.

WhatsApp è un invito a un **gruppo**, non un canale: voce `Gruppo WhatsApp`, CTA delle card `Unisciti al gruppo WhatsApp`, label mobile compatta `WhatsApp`. Queste diciture prevalgono sui riferimenti a Canale nelle revisioni precedenti. Aggiornate solo le tre voci esistenti e il seed, senza reimport demo né modifiche ai numeri personali per iscriversi a Eventi/Corsi. Newsletter e relativo modulo invariati.

## Top bar

Revisione v1.6.81, 12 ottobre 2026: barra di servizio senza payoff. A destra su desktop/tablet: **Canale WhatsApp · Instagram · Facebook · Newsletter**, con icone decorative. Newsletter rimanda a `/seguici/#newsletter-page`, senza modulo o iscrizione nella barra.

Canali visibili anche su mobile: quattro elementi affiancati, icona sopra la label; `WhatsApp` abbreviato senza `Canale` soltanto sotto 608 px. Barra alta almeno 52 px su mobile e 44 px negli altri formati, con focus oro visibile e wrapping consentito. URL e ordine dei tre social dal menu WordPress, Newsletter aggiunta solo nella top bar dal template e presente anche se il menu social non è configurato. Nessuna modifica ai menu salvati o al footer.

Payoff conservato nella hero della home e nella Carta dei Valori. Questa revisione prevale sul concept originale.

## Header
Logo orizzontale linkato alla Home.
Menu: **Chi siamo · Eventi · Corsi · Blog · Seguici · Contatti**.
CTA separata: **Unisciti a noi**.
Nessuna voce Home.

`Chi siamo` apre un sottomenu di secondo livello:

- La FISAR → `/la-fisar/`;
- La nostra delegazione → `/la-nostra-delegazione/`;
- Consiglio e incarichi → `/la-nostra-delegazione/#consiglio`;
- Carta dei Valori → `/carta-dei-valori/`;
- Statuto → `/la-nostra-delegazione/#statuto`;
- Diventa socio → `/unisciti-a-noi/`.

Consiglio e Statuto hanno voci proprie nel menu ma non pagine separate. Il vecchio URL `/chi-siamo/` reindirizza con 301 alla pagina della Delegazione. Con JavaScript il genitore è un pulsante disclosure, utilizzabile con click, Invio e Spazio; Tab attraversa i link, Escape chiude il sottomenu e restituisce il focus. Senza JavaScript il genitore rimane un link alla Delegazione e i figli sono accessibili tramite focus/hover su desktop o in linea su mobile.

## Footer
Navigazione, contatti, WhatsApp, Instagram, Facebook, CTA Newsletter, privacy/cookie.

Dal 9 ottobre 2026 `Blog` sostituisce `News` nel menu principale e nel footer. Raccoglie gli Articoli nativi WordPress: approfondimenti, racconti e comunicazioni associative. Conservato l’URL `/news/` per non cambiare i collegamenti esistenti.

## Come seguirci

Pagina dedicata e condivisibile all’URL `/seguici/`, con spiegazione dei canali, link diretti ai social e modulo di iscrizione Mailchimp. La pagina è raggiungibile dalla voce `Seguici` del menu principale e dal footer. Dal 10 ottobre 2026 la sezione omonima in home offre direttamente gli stessi canali e modulo, senza il link ridondante `Scopri tutti i canali`; il menu non rimanda a un’ancora della homepage.
