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
