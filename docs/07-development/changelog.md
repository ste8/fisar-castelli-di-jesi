# Changelog

## v1.6.9

- Aggiunta su richiesta dell’utente una piccola icona lineare oro a ciascuna delle sei voci della Carta dei Valori in home: germoglio, bussola, scudo, fumetto, persone e lampadina. Riutilizzato il sistema SVG del tema; aggiunte solo bussola, fumetto e lampadina.
- Icone decorative da `1.75rem` nella colonna sinistra, nascoste alle tecnologie assistive e non focalizzabili. Conservati copy, heading, CTA, griglia responsive, sfondo e resto della homepage; nessuna dipendenza o richiesta di rete aggiuntiva.
- Tema `1.6.9` per invalidare la cache; plugin e pagina completa della Carta invariati. Documentata la prova nelle specifiche.
- Verifiche: lint PHP di template e funzioni, sintassi JS e `git diff --check`; HTTP 200 di home, Carta completa e CSS versionato. Browser a 1440/1024/768/390/320 px senza overflow o testo tagliato; sei SVG da 28 px, tutti `aria-hidden` e non focalizzabili, console senza errori. Confronto automatico conferma copy e resto del template invariati.

## v1.6.8

- Riprogettata la fascia Carta dei Valori della homepage dalla bozza Word V2 fornita dall’utente, coerente con la working draft già nel repository: introduzione per il punto 1 e sei sintesi dei punti 2–7, nell’ordine originale.
- Nuovo titolo `La nostra Carta dei Valori`; rimossi `condivisione autentica`, `Percorsi seri` e il richiamo generico al territorio. Esplicitati modo di lavorare dei produttori, curiosità oltre il vino, consapevolezza/moderazione, informalità, accoglienza e contributo di tutti.
- Sostituite le quattro icone/categorie con una lista di H3 e paragrafi a sinistra: Poppins `500`, due colonne da `38rem` e una su mobile. Conservati fondo fotografico, overlay, CTA, ordine delle sezioni e gerarchia accessibile.
- Nessuna modifica alla pagina completa della Carta, al Word sorgente, al plugin o ai dati WordPress. Nessuna dipendenza o asset nuovo; tema `1.6.8`, documentazione aggiornata.
- Verifiche: lint PHP di template e funzioni, sintassi JS, `git diff --check`; HTTP 200 di home, Carta completa, CSS versionato, fondo fotografico e font Medium. Browser a 1440/1280/1024/832/768/608/390/320 px senza overflow o testo tagliato; sei voci con H3 Poppins 500, un solo H1 e ID univoci. Verificati focus visibile e CTA alla Carta, console senza errori; confronto automatico conferma il resto del template homepage invariato. Nessun invio a Mailchimp.

## v1.6.7

- Confermata dall’utente la scelta Poppins Medium per i cinque titoli di sezione della homepage.
- Estesa la prova Poppins `500` ai titoli delle card Eventi, Corsi e News, ai canali, al pannello newsletter e alle quattro voci della fascia Valori. Componenti condivisi uniformi anche negli archivi e nella pagina Seguici; interlinea `1.3` per card, canali e newsletter, dimensioni esistenti conservate e nessun troncamento dei titoli.
- Conservati hero, payoff, heading editoriali delle pagine interne, markup, contenuti e nomi accessibili. Nessun nuovo asset o dipendenza, nessuna modifica al plugin; tema `1.6.7` per invalidare la cache.
- Aggiornate specifica tipografica, homepage, componenti, assunzioni e README.
- Adattata la griglia delle voci Valori al maggiore ingombro di Poppins: quattro colonne da `80rem`, due alle larghezze intermedie e una su mobile; padding compatto su desktop e wrapping di sicurezza, senza ritagli.
- Verifiche: lint PHP e JS, `git diff --check`, HTTP 200 per home, Eventi, Corsi, News, Seguici, CSS versionato e font Medium. Controlli browser home a 1440/1280/1120/1024/768/390/320 px senza overflow o elementi oltre il viewport; archivi e Seguici controllati a 1440 e 390 px. Confermati Poppins 500 nei componenti, H1 interni e payoff invariati, unico H1, ID univoci, CTA complete e ancoraggio newsletter; nessuna immagine rotta o errore console rilevato. Nessun invio a Mailchimp.

## v1.6.6

- Applicata su richiesta dell’utente la prova Poppins Medium ai cinque titoli di sezione della homepage: Eventi, Corsi, News, Valori e Come seguirci. Peso `500`, dimensione responsive 32–40 px a scala base, come nell’anteprima.
- Conservati hero, payoff, card, heading interni alle sezioni, archivi e pagine interne. Regola CSS circoscritta alla homepage, senza nuovi asset, dipendenze o modifiche al plugin; tema `1.6.6` per invalidare la cache degli stili.
- Aggiornate specifica tipografica, homepage, assunzioni e README per distinguere la prova attiva dalla scelta generale del display.
- Verifiche: lint PHP e JavaScript, `git diff --check`, HTTP 200 per homepage, archivio Corsi, CSS versionato e Poppins 500; controllo browser a 1440, 1024, 768, 390 e 320 px senza overflow. Confermati i cinque H2 Poppins 500, hero Poppins 400, payoff/card Cormorant e H1 dell’archivio Corsi invariato; un solo H1 in home, nessun ID duplicato, immagine rotta o errore console rilevato. Nessun invio del form Mailchimp.

## v1.6.5

- Spostata l’intera sezione `Come seguirci`, con quattro canali e form Mailchimp, dopo la Carta dei Valori e prima del footer. Eventi e Corsi seguono direttamente la hero, poi News e Valori.
- Conservati markup interno, copy, stile, ancore newsletter, menu, social e configurazione Mailchimp. Spostamento nel template PHP, senza riordino CSS né modifiche al plugin; tema `1.6.5`.
- Aggiornate specifica homepage, componenti, decisioni e assunzioni.
- Verificati lint PHP e sintassi JS, HTTP 200 di homepage/pagina Seguici/asset, ordine DOM e resa a 1440/1024/768/390/320 px senza overflow. Confermati blocco spostato identico e resto del template invariato, unico H1, nessun ID duplicato, immagini caricate, ancoraggio newsletter e focus visibile sui campi; console senza errori. Nessun invio a Mailchimp.

## v1.6.4

- Rimosse le Quattro Porte dalla homepage senza un blocco sostitutivo: `Come seguirci` segue direttamente la hero. Eventi, Corsi, News, Valori, navigazione e newsletter invariati.
- Eliminati markup e stili responsive esclusivi del componente; conservate le icone condivise del tema e tutta la logica del plugin. Tema `1.6.4` per invalidare la cache degli stili.
- Aggiornate specifiche homepage, percorsi utente, componenti, decisioni e assunzioni; il concept iniziale resta un riferimento visivo storico.
- Verificati lint PHP e sintassi JS, HTTP 200 di homepage, destinazioni e asset, resa a 1440/1024/768/390/320 px senza overflow. Confermati assenza delle Quattro Porte e di spazio residuo fra hero e `Come seguirci`, unico H1, nessun ID duplicato, nomi accessibili completi delle card e immagini caricate dopo lo scorrimento. Menu mobile, Escape e focus visibile verificati; console senza errori. Nessun invio del form Mailchimp.

## v1.6.3

- Rimossa dalla homepage la fascia istituzionale sotto la hero: le Quattro Porte seguono direttamente, senza spazio residuo. Le informazioni su FISAR, qualifica APS e Delegazione restano nelle pagine di `Chi siamo`, i cui contenuti sono invariati.
- Eliminati markup e stili CSS del componente non più utilizzato; aggiornate specifica homepage e assunzioni. Tema `1.6.3` per invalidare la cache degli stili, plugin invariato.
- Verificati lint PHP e sintassi JS, HTTP 200 di homepage, pagine associative e asset, resa a 1440/1024/390/320 px senza overflow o immagini mancanti. Confermati unico H1 e contiguità fra hero e Quattro Porte; console senza errori.

## v1.6.2

- Aggiunta una variante SVG orizzontale per il solo header, con “FISAR” più grande e “CASTELLI DI JESI” maggiormente leggibile rispetto al grappolo, come richiesto dall’utente. Conservati forma e dimensioni del simbolo, famiglie/pesi dei caratteri, colori e tricolore; nessuna rigenerazione AI.
- Rifinita la larghezza di “CASTELLI DI JESI” e del tricolore per condividere i margini di FISAR: stessa larghezza di riferimento, spaziatura più compatta e dimensione della dicitura appena ridotta, senza schiacciare i glifi. Ingombro del logo e scritta FISAR invariati.
- Conservati il logo originale, gli asset brand e il logo del footer. La funzione del tema seleziona la variante nel contesto header e mantiene la precedenza dell’eventuale logo personalizzato WordPress.
- Nessuna modifica CSS all’altezza dell’header: la variante occupa circa 227 px di larghezza a desktop e 164 px su mobile. Hero, menu, plugin e contenuti invariati; versione tema `1.6.2`.
- Verificati lint PHP e sintassi JS, validità XML dell’SVG, HTTP 200 di homepage/asset, caricamento e nome accessibile del logo, footer invariato, resa a 1440/1120/1024/768/390/320 px senza overflow. Verificati menu mobile, sottomenu, Escape e focus visibile; console senza errori.

## v1.6.1

- Allargati payoff e descrizione della hero a un massimo di `34rem` e `40rem`, mantenendo il nome della Delegazione su tre righe. Su desktop entrambi i testi occupano una sola riga e la hero risulta più compatta.
- Raggruppate le due frasi del payoff per favorire l’andata a capo dopo la virgola su mobile; mantenuto il wrapping interno quando lo spazio è insufficiente, senza `nowrap` o interruzioni rigide. Descrizione con `text-wrap: balance`.
- Fotografia, overlay, dimensioni dei font, CTA e altre sezioni invariati. Solo presentazione nel tema, senza nuove dipendenze o modifiche al plugin; versione tema `1.6.1` per invalidare la cache degli asset.
- Verificati lint PHP dei file interessati e sintassi JS, HTTP 200 di homepage/CSS/font, resa e assenza di overflow a 1440, 1024, 768, 540, 390 e 320 px, testo accessibile del payoff completo e console senza errori.

## v1.6

- Sostituito Inter con Poppins `400/500/600/700` per corpo, menu, CTA, metadata e form, anche nella configurazione dell’editor WordPress. Cormorant Garamond resta per titoli editoriali e payoff; due sole famiglie utilizzate.
- Aggiunti quattro WOFF2 Poppins con subset latino e licenza OFL. Preload del solo Regular e del file Cormorant esistente, `font-display: swap`, nessun font o asset remoto a runtime. Gli asset Inter precedenti restano inutilizzati.
- Aggiornata la hero secondo la proposta approvata: H1 “FISAR / Delegazione / Castelli di Jesi” in Poppins Regular, payoff più piccolo e descrizione “Corsi per sommelier, degustazioni e incontri per conoscere il mondo del vino.” CTA Scopri i corsi e Scopri gli eventi; fotografia esistente conservata.
- Aggiunta sotto la hero la fascia che esplicita il nome esteso e la qualifica APS della FISAR nazionale. Nessuna attribuzione giuridica locale e nessuna modifica alle altre sezioni, ai contenuti associativi o alla logica del plugin.
- Aggiornate specifiche tipografiche, homepage, assunzioni e README; tema `1.6.0`, plugin invariato.
- Verificati lint PHP del tema, sintassi JS/JSON, HTTP 200 di pagine, CSS, JS e quattro WOFF2; controlli visuali/overflow a 1440, 1120, 1024, 768, 390 e 320 px. Menu mobile, sottomenu, Escape e focus visibile verificati; unico H1, nessun ID duplicato, nomi accessibili delle card mantenuti, immagini caricate e console senza errori. Controllati anche archivio Corsi e pagina Come seguirci su mobile; nessun form inviato a Mailchimp.

## v1.5

- Portato `Chi siamo` alla prima posizione del menu, con sottomenu La FISAR, La nostra delegazione, Consiglio e incarichi, Carta dei Valori, Statuto e Diventa socio.
- Creata la pagina nazionale La FISAR; riusata la pagina locale mantenendo il testo e rinominandola La nostra delegazione, con redirect 301 dal vecchio `/chi-siamo/`.
- Aggiunte le ancore Consiglio e incarichi e Statuto alla pagina locale, distinguendo Consiglio di Delegazione e quattro categorie di incarichi non elettivi. Nomi, mandato e PDF rimangono contenuti editoriali da fornire.
- Aggiunto un sottomenu responsive con progressive enhancement, focus visibile, Escape, chiusura fuori dal menu e sblocco del menu mobile sui collegamenti ad ancora nella stessa pagina.
- Aggiunto il comando incrementale `wp fisar-cdj demo association`: aggiorna solo pagine associative e navigazione, conserva i testi già presenti e non reimporta Eventi, Corsi, News o social.
- Tema `1.5.0`, plugin e demo `1.3.0`; homepage invariata salvo la navigazione.
- Verificati lint PHP completo e sintassi JavaScript, HTTP 200 delle pagine/asset e redirect 301, menu a 1440/1024/390 px senza overflow, tastiera e collegamento ad ancora nella stessa pagina con chiusura del menu mobile e trasferimento del focus. Il comando incrementale ripetuto non duplica sezioni o voci.

## v1.4.1

- Rinominata la voce di navigazione `Resta aggiornato` in `Seguici` e la relativa pagina/sezione in `Come seguirci`.
- Semplificato lo slug pubblico da `/resta-aggiornato/` a `/seguici/`, mantenendo il reindirizzamento WordPress dal precedente URL.

## v1.4

- Aggiunta in homepage la sezione ad alta visibilità `Resta aggiornato`, con accesso diretto a WhatsApp, Instagram, Facebook e Newsletter.
- Creata la pagina dedicata `Resta aggiornato` e aggiunti i relativi collegamenti a menu principale e footer.
- Integrato il form Mailchimp fornito con markup accessibile, validazione nativa, honeypot anti-bot e senza dipendenze frontend remote.
- Centralizzata nel plugin la configurazione pubblica del form, mantenendo markup e stile nel tema.
- Adeguato il breakpoint del menu e delle card canale per mantenere spaziatura e leggibilità anche a 1024 px.
- Aggiornata la versione del tema a `1.4.0` anche nei metadati WordPress.

## v1.3

- Portati a due gli Eventi mostrati in homepage, con locandine più grandi, fondo bianco e data compatta su una riga.
- Rese quadrate le immagini dei Corsi in homepage e rimosso il relativo abstract dalla card compatta.
- Rafforzati i titoli delle card Eventi, Corsi e News.
- Accorciate le CTA visibili delle card, mantenendo il titolo della risorsa come contesto aggiuntivo per screen reader.

## v1.2

- Allineata la tipografia alla specifica approvata: Cormorant Garamond per titoli/display e Inter per corpo e UI.
- Aggiunti font WOFF2 self-hosted, licenze OFL, `font-display: swap` e preload dei due file indispensabili above-the-fold.
- Uniformati scala, pesi, interlinee e larghezza dei testi editoriali nei componenti frontend e nell'editor WordPress.

## v1.1

- Riallineata la homepage al concept approvato dopo confronto visuale a 1024 px.
- Ridotte altezza e tipografia dell'hero; aggiunta una fotografia demo calda e conviviale.
- Aggiunte icone SVG a social, Quattro Porte, CTA di adesione e fascia Valori.
- Affiancate le sezioni Eventi e Corsi su desktop e rese quadrate le immagini evento demo.
- Compattate News, fascia Valori e footer secondo la gerarchia del mockup.
- Aggiunti due corsi attivi ai dati demo per verificare la composizione completa.
- Verificato il responsive mobile a 390 px, incluso menu, focus e assenza di overflow.

## v0.1

- Creata struttura documentale.
- Aggiunta homepage concept 01.
- Documentate decisioni su header, menu, News, Quattro Porte e CTA.
