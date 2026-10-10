# Assunzioni di implementazione V1

## 9 ottobre 2026 — Immagine del dettaglio Blog

Richiesta una fotografia contenuta nella parte alta, a destra come nei dettagli Eventi. Interpretata come immagine in evidenza, non come tutte le immagini del corpo o della galleria. Foto intera, anche verticale, senza ritaglio quadrato; massimo 352 px di larghezza del riquadro e altezza limitata. Sotto 832 px passa sotto l’introduzione per conservare leggibilità. Senza immagine non si riserva una colonna vuota; titoli, abstract, font, contenuti e dati non cambiano.

## 9 ottobre 2026 — News diventa Blog

Approvata la denominazione Blog per approfondimenti, racconti di eventi e annunci associativi. Applicati i nomi proposti a menu, home, elenco e dettaglio; non introdotte le categorie suggerite come sviluppo successivo. La richiesta riguarda la denominazione: preservati `/news/` e URL degli articoli, senza redirect o migrazione. Nel backend restano **Articoli**; nessuna modifica ai loro contenuti, abstract, immagini o lightbox. La nuova denominazione prevale sui riferimenti storici a News.

## 8 ottobre 2026 — Ingrandimento predefinito delle gallerie News

Richiesto il default per le nuove gallerie, con possibilità di disattivarlo e senza alterare quelle già presenti. Applicato nel normale inserimento del blocco nativo nell’editor News, non come impostazione globale per tutte le immagini. Copia/incolla e trasformazioni preservano gli attributi dei blocchi originali; nessuna migrazione. La lightbox e la navigazione tra foto restano quelle native WordPress. Nessun carosello autonomo o libreria esterna: tema e dati editoriali reali invariati.

## 8 ottobre 2026 — Stile News

Richiesto l’allineamento di home, elenco e dettaglio alle migliorie Eventi/Corsi. Trasferite tipografia Poppins, fascia archivio bordeaux compatta, dettagli chiari, bordi leggeri e separatore delle card; non trasferiti dati di prenotazione o locandine quadrate, estranei agli articoli. Le fotografie restano orizzontali nelle card e naturali nel dettaglio. In home mantenute quattro notizie ma con immagine sopra il testo, per evitare colonne testuali troppo strette; copy degli articoli, query, URL e dati invariati. Nessun nuovo CPT, campo o editor custom: presentazione nel tema, font e icone già locali. Casi limite verificati tramite filtri temporanei in memoria, senza salvataggi editoriali.

## 7 ottobre 2026 — Numeri delle lezioni

Richiesto numero manuale come primo campo, per gestire anche cambi d’ordine. Interpretato come etichetta della lezione, non chiave di ordinamento: le righe seguono ancora data/orario e mantengono il numero assegnato. Testo breve anziché intero obbligatorio, per consentire eccezioni come `3 bis` e zeri iniziali; nessuna unicità imposta o rinumerazione. Campo facoltativo per preservare i calendari esistenti, senza attribuire numeri non concordati. La prima colonna pubblica mostra `—` per i valori non compilati. Import vecchio e nuovo supportati senza migrazione; dati reali non modificati durante i test.

## 7 ottobre 2026 — Offerta dei Corsi

Richiesta di opzione e banner automatico, con scadenza facoltativa. Scelta esplicita: spunta più data, anziché dedurre l’offerta dal testo Early Bird della quota o dalla presenza di una data. Senza data la durata è manuale; con data si include il giorno indicato nel fuso del sito. Non segnalare offerte sui corsi conclusi o con iscrizioni non ordinarie (chiuse, sold-out o lista d’attesa). Banner in home, elenco e dettaglio; nessun importo scontato fornito o calcolato. Regole e campi nel plugin, presentazione nel tema; test con dati simulati, corsi reali non marcati in offerta. In presenza di future cache di pagina, la loro scadenza dovrà rispettare i cambi di stato giornalieri, come per le iscrizioni.

## 7 ottobre 2026 — Titoli dei Corsi e località nelle card

- Approvata la separazione `Corso Sommelier` / `1° livello · Città (Provincia)` nell’elenco, con un unico livello visibile e pin davanti alla sede; pin/località estesi anche alle card Eventi. Provincia dal campo esistente, non da lookup geografico. Nessun pin per eventi solo online.
- Default Automatico per corsi di livello 1/2/3 e nuovi auto-draft, Personalizzato per gli altri; scelta esplicita disponibile nel metabox. I titoli esistenti rimangono salvati, senza migrazione: card standard strutturata subito, titolo nativo composto al prossimo salvataggio autorizzato in Automatico. Non si ricava la città dal titolo: il corso locale ID 12 cita Falconara nel titolo ma ha Città Jesi, discrepanza segnalata e non corretta senza richiesta editoriale.
- Niente rinomina degli URL, filtro globale sui titoli o riordino di home/dettaglio. La modalità Personalizzato serve per percorsi non standard. Test di salvataggio su bozze in transazione, rollback finale verificato; nessun contenuto reale modificato.

## 6 ottobre 2026 — Estensione delle migliorie Eventi ai Corsi

- Richiesta di allineare home, archivio e dettaglio applicando soltanto quanto pertinente. Conservati livelli, direttore, calendario/import, tesseramento, dotazione, condizioni libere della quota e relazioni con Eventi.
- Trasferiti scadenza evidenziata/chiusura tassativa, sold-out con eventuale lista d’attesa, avviso posti limitati, più contatti WhatsApp e Maps opzionale. Corso attivo e iscrizioni aperte sono stati diversi; conclusione e termine tassativo superato prevalgono sulla lista d’attesa. Data inclusa nel fuso WordPress, default ordinario, nessuna riscrittura dei dati esistenti.
- Non aggiunte modalità online/ibrida o quote Soci/Non soci: il modello Corsi attuale non le prevede. L’editor libero della quota resta adatto a Early Bird, Under 25, gruppi e rate; € automatico solo per importi isolati.
- Home fino a tre corsi con composizione compatta e link sempre disponibile; totale quando eccede il limite. Test su dati in memoria e salvataggi intercettati, non sugli inserimenti reali; nessun commit automatico.

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
- Iconografia: social e CTA usano SVG minimali inclusi nel tema, decorativi e accompagnati da label testuali accessibili. La sintesi Valori riprogettata il 5 ottobre 2026 è inizialmente testuale; su successiva richiesta dell’utente si prova una piccola icona decorativa accanto a ciascuna delle sei voci, usando lo stesso sistema SVG.
- News: usa i post WordPress nativi; non viene creato un terzo CPT.
- Social: usa un menu WordPress dedicato. I link demo sono esplicitamente sostituibili, evitando campi custom non previsti.
- Newsletter: dal 23 settembre 2026 il provider indicato è Mailchimp. Il tema usa il form embedded fornito, ripulito da CSS, JavaScript e badge remoti; nome, cognome ed email vengono trasmessi direttamente a Mailchimp e non memorizzati in WordPress. Il metodo di opt-in è gestito nell’Audience Mailchimp; prima della pubblicazione vanno verificati double opt-in, messaggi di conferma e informativa privacy.
- Pagine legali: contenuti demo chiaramente indicati come bozze, da validare prima della pubblicazione.

## Navigazione associativa — 4 ottobre 2026

Il riscontro dell’utente prevale sulla precedente navigazione piatta: `Chi siamo` è la prima voce, con sottomenu come descritto in `docs/02-information-architecture/navigation.md`. Consiglio e incarichi e Statuto sono sezioni della pagina locale, non nuove pagine. Non sono stati forniti nomi ufficiali, mandato o PDF della Delegazione: restano indicazioni editoriali esplicite da completare prima della pubblicazione. Il testo nazionale descrive la FISAR APS, senza attribuire alla Delegazione una qualifica giuridica locale non verificata.

## Identità in homepage — 4 ottobre 2026

La scelta della proposta Poppins e la successiva conferma “al posto di Inter” autorizzano la sostituzione globale del sans-serif, non l’aggiunta di una terza famiglia. Il nuovo H1 identifica FISAR e la Delegazione Castelli di Jesi; il payoff rimane secondario. Copy e CTA sono descritti in `docs/03-pages/homepage.md` e prevalgono sul vecchio titolo manifesto del mockup. Su successiva richiesta dell’utente, la fascia istituzionale sotto la hero è rimossa: le informazioni associative restano nelle pagine della sezione `Chi siamo`. Nessuna modifica ai loro contenuti editoriali o alla sequenza delle altre sezioni.

## Semplificazione della homepage — 4 ottobre 2026

Su richiesta dell’utente sono rimosse le Quattro Porte, perché i loro percorsi sono già disponibili in hero, menu e sezioni dedicate. `Come seguirci` segue direttamente la hero; non sono aggiunti blocchi sostitutivi né riordinate le altre sezioni. Questa revisione prevale sulla presenza delle Quattro Porte nel mockup e nelle decisioni iniziali. Nessuna modifica a contenuti, menu o logica del plugin.

Su successiva approvazione, il blocco completo `Come seguirci`, con canali e form Mailchimp, è spostato dopo la Carta dei Valori e prima del footer. Eventi e Corsi seguono direttamente la hero. Contenuti, stile, ID delle ancore, menu e link social restano invariati; nessuna modifica al plugin o alla configurazione Mailchimp.

## Prova dei titoli di sezione — 4 ottobre 2026

La prima richiesta di provare Poppins sul sito dopo il confronto riguarda i cinque titoli di sezione della homepage, con peso `500` e dimensione massima `2.5rem`. L’utente conferma questa scelta dopo averla vista sul sito.

La successiva richiesta estende la prova ai titoli dei componenti: card Eventi, Corsi e News, canali, pannello newsletter e voci della fascia Valori. Poppins Medium `500`, con interlinea `1.3` per card, canali e newsletter; dimensioni esistenti conservate. I componenti condivisi sono aggiornati anche negli archivi e nella pagina Seguici. Restano invariati payoff, heading editoriali delle pagine interne e default dell’editor: non è una conversione globale degli heading. Nessuna modifica a plugin, markup, contenuti o nomi accessibili.

## Stile archivio e dettaglio Eventi — 5 ottobre 2026

Su richiesta dell’utente, estesa la gerarchia Poppins della home e della Carta ai soli template Eventi: intestazioni compatte, locandine intere su bianco, bordi e accenti oro leggeri. Riquadro iscrizioni in normale flusso, senza sticky, per evitare che pannelli lunghi siano parzialmente fuori dal viewport. Conservati integralmente i contenuti e tutte le condizioni funzionali; nessuna riscrittura editoriale o modifica al plugin/database. Le specifiche più aggiornate sono `docs/03-pages/events.md` e `docs/03-pages/event.md`.

## Prenotazione nel dettaglio Evento — 5 ottobre 2026

Riscontro del 6 ottobre 2026, plugin `1.3.12`/tema `1.6.45`: informazioni per partecipare online pubbliche solo con consenso editoriale esplicito, default no anche per gli eventi già inseriti. L’opzione riguarda piattaforma e link, non la modalità né i recapiti di prenotazione. Non viene dedotto un momento di pubblicazione, creato un invio agli iscritti o cancellato il dato; i link eventualmente inseriti nei testi pubblici non vengono rimossi. Eventi conclusi sempre senza URL di accesso; Corsi invariati.

Riscontro del 6 ottobre 2026, v1.6.43/plugin v1.3.11: stato sold-out con o senza lista d’attesa, per eventi con iscrizione richiesta. Si riusano gli stessi canali senza cambiare i messaggi/URL salvati. Un termine tassativo superato chiude anche la lista d’attesa; evento concluso prioritario. Il sito raccoglie solo l’invito a contattare: non registra le persone in attesa né promette disponibilità. I test usano dati simulati, senza marcare sold-out gli eventi reali. Enum unico per evitare flag contraddittori, nessun default sold-out o modifica ai Corsi.

Riscontro del 6 ottobre 2026, v1.6.42/plugin v1.3.10: il termine tassativo superato chiude le iscrizioni richieste degli Eventi. La data è inclusa, dato che non è previsto un orario di scadenza: chiusura dal giorno successivo nel fuso del sito. Stato informativo in alto e in fondo, canali/inviti nascosti ma quote conservate. Date assenti/invalide non chiudono; termini flessibili e Corsi invariati. Nessuna scrittura nei contenuti o nei meta. Questa richiesta prevale sulla precedente assunzione di nessuna chiusura automatica per gli Eventi.


Riscontro successivo del 6 ottobre 2026, plugin v1.3.9: i numeri WhatsApp senza prefisso degli Eventi sono italiani; aggiungere `39` al solo link. Il campo propone solo il numero, con o senza `+39`. Non dedurre un prefisso estero: richiederlo esplicitamente. Conservare dati e vecchi URL per compatibilità; nessuna estensione automatica ai Corsi.

Riscontro del 6 ottobre 2026: nominativo facoltativo e più contatti WhatsApp negli Eventi, con un elenco ripetibile come le quote. Conservare il contatto esistente come prima voce senza migrazione automatica; rendere pubblici nomi, numeri e pulsanti per destinatario. Il campo dei Corsi resta singolo: questa richiesta riguarda gli Eventi. Le verifiche usano contatti simulati, senza pubblicare nuovi nominativi/recapiti negli eventi reali.

Successivo riscontro v1.6.35: per nuove esigenze come un menu senza vini, mantenere i campi Soci/Non soci e affiancare righe facoltative personalizzabili con etichetta, importo e nota, più una nota generale sulle quote. Non creare un campo fisso per ogni variante né usare un editor libero per sostituire l’intero elenco. Alternative e supplementi devono essere espliciti nei testi, senza calcoli presunti. Nessun importo o condizione del nuovo menu è stato fornito: i test usano dati simulati e gli eventi reali restano invariati.

Il riscontro successivo al doppio pannello v1.6.28 approva un unico riquadro finale `Quote e prenotazioni`, raggiungibile da `Come prenotare` nella hero. Quote e scadenza non sono duplicate nella tabella immediatamente precedente; la scadenza resta nella hero. Corpo in una colonna centrale, senza sticky, barra fissa o testo a tutta larghezza. Gli eventi senza prenotazione usano `Come partecipare`/`Quote e partecipazione`; quelli conclusi non hanno un invito operativo.

L’utente richiede esplicitamente l’euro nelle quote: il tema aggiunge `€` agli importi numerici privi di valuta, conservando testi completi e importi già con valuta. Nessuna modifica ai dati WordPress o alle regole del plugin.

## Visibilità degli eventi in homepage — 5 ottobre 2026

L’utente approva la visibilità diretta fino a quattro eventi, in sostituzione del precedente limite di due. Si mantengono due card per riga su desktop/tablet e una su mobile, senza diminuire le locandine o introdurre un carosello. Il successivo riscontro conserva sempre il link all’archivio nella stessa posizione: `Tutti gli eventi` fino a quattro, `Tutti gli eventi (N)` oltre quattro, accanto al titolo su desktop e sotto le card su mobile. Il totale è fornito dalla stessa query del plugin; a zero eventi compare un messaggio esplicito. Nessuna modifica a dati, seed, criteri temporali, Corsi, altri contenuti o archivi. I test dei diversi totali usano filtri in memoria e fixture temporanee, senza alterare il database locale.

## Sintesi Carta dei Valori — 5 ottobre 2026

Il nuovo riscontro sposta la precisazione sull’ambito locale fuori dalla hero, in un riquadro sotto l’immagine e prima del payoff. Si sceglie un’icona `info`, non `warning`, perché il testo è un chiarimento e non segnala un rischio o un errore. Il testo salvato e il seed rimangono invariati; il tema cambia soltanto markup, icona e stile della nota, senza live region o allarme.

L’ultimo riscontro sulla hero prevale sull’esperimento precedente: titolo `Carta dei Valori della nostra Delegazione`, identificazione esplicita della Delegazione Castelli di Jesi e nota editoriale che distingue l’ambito locale da quello nazionale. La frase sui principi acquista rilievo; il payoff torna nel corpo sopra `Chi siamo`. Si usa il paesaggio di vigneti già disponibile, non la foto conviviale della hero della home, con precedenza all’eventuale immagine in evidenza della Carta. L’asset resta demo e non documenta un luogo reale verificato. Il testo integrale del documento non cambia: la nota è un paratesto separato, modificabile nell’editor. Nessuna nuova immagine generata, promessa editoriale o modifica alle altre hero.

Il successivo riscontro sullo stile della pagina completa autorizza una revisione visiva circoscritta alla Carta: H1 e capitoli in Poppins come nella home, scala ridotta, intestazione compatta e separazioni leggere. Payoff serif e testo integrale sono conservati; nessuna modifica agli altri template editoriali, al plugin o ai dati WordPress. Specifica aggiornata in `docs/03-pages/values.md`.

La successiva richiesta dell’utente estende l’intervento alla pagina completa: riportare integralmente il documento Word V2 e aggiungere le stesse icone della home. Per questa pagina prevale `docs/03-pages/values.md`; il testo originale e la numerazione sono conservati, senza usare le sintesi riscritte per la home. Il paragrafo seguente descrive solo l’intervento iniziale alla homepage.

L’allegato Word V2 coincide nei contenuti con la working draft in `docs/00-foundation/carta-dei-valori.md`. La riprogettazione riguarda esclusivamente la sezione della home: introduzione per il punto 1 e sei voci per i punti 2–7, nell’ordine originale. Rimossi i testi generici contestati dall’utente; le sintesi non sostituiscono la Carta ufficiale e non introducono promesse sulla salute. La pagina completa, il documento sorgente e i dati WordPress non sono modificati. Restano il fondo fotografico e la posizione tra News e Come seguirci; la nuova composizione e il copy sono documentati nella specifica homepage.
