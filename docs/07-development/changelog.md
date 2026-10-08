# Changelog

## v1.6.65 — Colonna Note solo quando compilata

- Il calendario pubblico omette l’intestazione `Note` e tutte le relative celle se nessuna lezione contiene una nota. Con almeno una nota, colonna presente per tutte le righe e celle allineate. Spazi soli considerati vuoti, testo `0` conservato. Avviso fisso sotto la tabella, editor, import e dati invariati.
- Skill `clean-code-engineer`: condizione di sola presentazione calcolata una volta nel template, nessun nuovo campo o dipendenza. Righe del markup rese più leggibili; tema/asset `1.6.65`, plugin invariato e nessun commit automatico.
- Verificati lint dei due PHP, sintassi JS invariati, HTTP pagina/CSS e `git diff --check`. Sette casi nativi (note vuote, spazi, una o più note, zero, caratteri speciali, calendario vuoto): intestazioni/celle corrette, data in evidenza e campo editor conservati. Regressioni numero lezione/import e sei salvataggi intercettati superate, senza modificare dati dei corsi.
- Browser senza note a 1440/1024/768/390/320 px: sei colonne, nessun overflow pagina o immagine fallita, desktop senza scrollbar e scorrimento interno sui formati piccoli. Console senza errori, review visiva e screenshot. Corso reale con note: sette colonne e celle allineate. Anteprima temporanea rimossa.

## v1.6.64 — Data del calendario in evidenza

- Spostata l’evidenza dal giorno della settimana alla data completa: data in grassetto/700 e bordeaux, giorno ordinario/400. La data è l’intestazione semantica di riga, con `time` conservato. Numero lezione, ordine, contenuti, plugin e layout invariati.
- Skill `clean-code-engineer`: riusati gli stili delle intestazioni esistenti, intervenendo sul solo markup del calendario. Tema/asset `1.6.64`, specifica aggiornata; nessun commit automatico.
- Verificati lint dei due PHP, sintassi JS invariati, HTTP pagina/CSS e `git diff --check`. Test del calendario (sanitizzazione/import, sei salvataggi intercettati e template) superati senza modificare dati dei corsi. Browser a 1440/1024/768/390/320 px: pesi e colori corretti, cinque righe conservate, nessun overflow pagina o immagine fallita, scorrimento locale sui formati piccoli e console senza errori. Review visiva e screenshot desktop.

## v1.6.63 / plugin v1.3.20 — Numero manuale delle lezioni

- `Numero lezione` come primo campo del calendario nell’editor e prima colonna pubblica (`N°`, nome accessibile completo). Numero manuale facoltativo, anche `03`, `0` o `3 bis`; nessuna rinumerazione né ordinamento numerico. Righe ancora cronologiche, calendari esistenti preservati con `—` per i numeri non inseriti. Nessuna migrazione o modifica ai contenuti reali.
- Import tabulato con numero iniziale e compatibilità con il formato precedente. Conservata la cella iniziale vuota; sanitizzazione nel plugin, nessuna logica duplicata nel tema. Indici monotoni nell’editor dopo rimozioni/aggiunte. Anteprima riuscita resa modificabile senza sovrascrittura dal testo originale al salvataggio; date/titoli invalidi segnalati prima di sostituire le righe, feedback accessibile.
- Skill `software-architect` e `clean-code-engineer`: esteso il modello delle righe esistente, senza nuovi meta, dipendenze o numerazione implicita. Aggiornati versioni, istruzioni e specifiche; nessun commit automatico.
- Verificati lint dei cinque PHP modificati, sintassi JS, HTTP e `git diff --check`. Test nativi: sanitizzazione, numeri speciali/vuoti/legacy, ordine cronologico, sette import vecchi/nuovi, data invalida, sei salvataggi intercettati con nonce/capability, editor e template pubblico. Regressioni su 21 varianti Corsi e 16 offerte, con otto e sette salvataggi intercettati rispettivamente. Nessun dato dei corsi modificato.
- Browser: import nuovo con prima cella vuota, legacy, modifica manuale, rimozione/aggiunta senza indici duplicati e errore senza sostituzione delle righe. Dettaglio con numeri simulati a 1920/1440/1024/768/390/320 px: sette colonne, desktop senza scrollbar e scorrimento solo interno sotto 1024 px, nessun overflow pagina o immagine fallita. Tastiera/focus verificati, console senza errori e review visiva desktop. Pagina reale conserva le cinque lezioni senza numeri; anteprima temporanea rimossa.

## v1.6.62 — Calendario Corsi più largo su desktop

- Risolta la scrollbar forzata dalla colonna da 768 px, più stretta del minimo tabella di 928 px. Calendario separato dalla colonna di lettura e contenitore fino a 1.216 px; descrizione/Eventi collegati/iscrizioni ancora centrati a 768 px. Tabella adattiva da viewport 1.024 px, larghezza minima leggibile e scorrimento interno sotto tale soglia. Spaziatura dei blocchi autonomi coordinata, dati/intestazioni e focus conservati.
- Skill `clean-code-engineer`: intervento circoscritto a template e CSS del dettaglio Corsi, senza dipendenze, modifica della tabella nel plugin o dati editoriali. Tema/asset `1.6.62`, plugin invariato; offerta e precedenti modifiche preservate, nessun commit automatico.
- Verificati lint dei due PHP, sintassi JS invariati, HTTP dettaglio/CSS e `git diff --check`. Regressioni native Corsi su 21 varianti (anche calendario vuoto), otto salvataggi intercettati; offerte su 16 varianti e relative guardie, nessuna persistenza. Browser sul corso reale a 1920/1440/1024 px: tutte le colonne visibili senza scorrimento; 768/390/320 px: scorrimento confinato al calendario, nessun overflow pagina. Misura della descrizione e del pannello conservata, cinque lezioni integre, immagini caricate e console senza errori. Scorrimento da tastiera verificato con focus sul riquadro; review visiva e screenshot desktop/mobile.

## v1.6.61 — Offerte Corsi: giallo pieno e cornice

- Su richiesta esplicita, sostituita la fascia discreta con giallo pieno `#ffda3d`, titolo maiuscolo più grande/700 e data separata/600. Aggiunta cornice coordinata da 4 px a immagine e fascia nei tre contesti; adattamento compatto per la home, senza overlay o animazioni. Contrasto testo bordeaux scuro/fondo 11,9:1; oro istituzionale invariato.
- Skill `clean-code-engineer`: sola presentazione nel tema, classe condizionale derivata dalla API dell’offerta già esistente e stato letto una volta per template. Dati, algoritmo, salvataggi e plugin `1.3.19` invariati; tema/asset `1.6.61`, documentazione aggiornata e precedente lavoro non committato preservato. Nessun commit automatico.
- Verificati lint dei tre PHP modificati, sintassi JS invariati, HTTP dettaglio/CSS e `git diff --check`. Test nativi su 16 varianti dell’offerta: fascia e cornice presenti solo con offerta attiva in home/archivio/dettaglio, guardie dei sette salvataggi intercettati; regressioni Corsi su 20 varianti, senza persistenza. Browser con offerta simulata nei tre template a 1440/768/390/320 px: colore, bordo e dimensioni corretti, nessun overflow pagina/fascia/cornice, immagine fallita o errore console. Review visiva e screenshot desktop/mobile; pagina temporanea rimossa e nessun dato editoriale modificato.

## v1.6.60 / plugin v1.3.19 — Banner offerta dei Corsi

- Nel box `Quota e dotazione`, spunta `Corso in offerta` e `Fine offerta (facoltativa)`. Fascia oro sotto la locandina di home/archivio/dettaglio, senza overlay: `In offerta` e data `Fino al …` se presente. Quote e condizioni editoriali non ricalcolate né riscritte.
- Stato nel plugin: scadenza inclusiva nel fuso WordPress, durata manuale senza data, nessun banner con iscrizioni chiuse/sold-out/lista d’attesa o corso concluso. Meta privati validati, marker per preservare dati in editor precedenti, date invalide senza attivazione involontaria. Nessuna migrazione, attivazione dei corsi reali, cron o dipendenza.
- Skill `software-architect` e `clean-code-engineer`: preferita attivazione esplicita con termine opzionale alla deduzione dal testo libero delle quote; componente condiviso nel tema e regole nel plugin. Versioni e documentazione aggiornate; modifiche precedenti preservate e nessun commit automatico.
- Verificati lint degli otto PHP interessati, sintassi JS invariati, HTTP dettaglio/CSS e `git diff --check`. Test nativi su 16 varianti in home/archivio/dettaglio, data inclusiva/scaduta/invalida, stati chiusi e campi privati; sette salvataggi intercettati con guardie/marker e rendering dei controlli dell’editor. Regressioni Corsi su 20 varianti e ordine/dicitura dell’offerta su otto combinazioni, senza scritture persistenti.
- Browser con offerta simulata nei tre template reali a 1440/768/390/320 px: un banner con data semantica, nessun overflow pagina/fascia, immagine fallita o errore console; review visiva e screenshot di home/archivio/dettaglio. Nessun salvataggio autenticato nell’editor. Confermata l’assenza dei nuovi meta nei quattro corsi locali; pagina temporanea di anteprima rimossa, nessun dato editoriale modificato.

## v1.6.59 / plugin v1.3.18 — Contenuti del corso prima del tesseramento

- Spostato `Cosa comprende il corso` prima di `Tesseramento FISAR`, sia nell’editor sia nel dettaglio. Titolo pubblico completato con `il corso`, evitando l’ambiguità con la tessera; quota ancora per prima e campi vuoti omessi.
- Skill `clean-code-engineer`: riordinate soltanto le chiamate dei campi e la mappa dei titoli esistenti, senza modificare dati, CSS, salvataggi o stati. Tema `1.6.59`, plugin `1.3.18`; documentazione aggiornata, modifiche precedenti preservate e nessun commit automatico.
- Verificati lint PHP, sintassi JS invariati, HTTP dettaglio/CSS e `git diff --check`. Rendering nativo dell’editor: ordine dei tre campi corretto; otto combinazioni di campi nel pannello: ordine e omissione dei vuoti corretti, titolo completo, dati invariati. Regressioni Corsi su 20 varianti e otto salvataggi intercettati, senza persistenza. Browser del dettaglio a 1440/768/390/320 px: titoli ordinati, nessun overflow, immagine fallita o errore console; review visiva e screenshot. Nessun salvataggio autenticato nell’editor.

## 7 ottobre 2026 — Rimossa la nota sul colloquio risalvata

- La frase sul colloquio era nuovamente presente nel campo `Informazioni aggiuntive` del corso locale ID 12, non nel template né nel seed demo. Rimossa soltanto quella frase tramite API WordPress con confronto del valore precedente; conservato il testo aggiunto nel frattempo (`Queste sono delle note aggiuntive:`). Nessuna prova sulla causa del nuovo salvataggio; nessun filtro o blocco sul contenuto editoriale.
- Skill `clean-code-engineer`: intervento sui soli dati interessati, codice/versioni invariati. Verificati rilettura del campo, HTTP e browser del dettaglio: frase assente e pannello/canali conservati; screenshot e `git diff --check`. Nessun commit automatico.

## v1.6.58 — Separatore nei Corsi con titolo personalizzato

- Corretta l’assenza della linea nelle card Personalizzato dell’archivio Corsi: stessa regola decorativa, sotto il titolo libero anziché sotto la riga identitaria delle card automatiche. Testo, nomi accessibili, home/dettaglio e dati conservati.
- Skill `clean-code-engineer`: aggiunto un solo selettore alla regola condivisa, senza cambiare markup, plugin o dipendenze. Tema/asset `1.6.58`; nessun commit automatico.
- Verificati lint PHP, sintassi JS invariati, HTTP archivio/CSS e `git diff --check`; regressioni native dell’identità Corsi senza scritture nel database. Browser a 1440/768/390/320 px: un solo separatore nelle card automatiche e personalizzate, home invariata, nessun overflow o immagine fallita; console senza errori. Review visiva e screenshot dell’archivio.

## v1.6.57 — Eliminato il riepilogo duplicato dei Corsi

- Rimossa dal dettaglio Corsi la sezione generata `Informazioni pratiche`, senza toccare il contenuto editoriale. Livello, direttore, date, sede/indirizzo/città/provincia e Maps rimangono in alto; calendario, Eventi collegati e pannello finale `Informazioni e iscrizioni` conservati.
- Skill `clean-code-engineer`: eliminato soltanto il markup duplicato nel template Corsi, senza modificare CSS condiviso, campi, dati o regole del plugin. Tema/asset `1.6.57`, plugin invariato; Eventi, home/archivio e modifiche precedenti preservati. Nessun commit automatico.
- Verificati lint PHP, sintassi JS invariati, HTTP dettaglio/CSS e `git diff --check`. Test nativi su 20 varianti Corsi: riepilogo inferiore assente, livello/direttore/date e Maps ancora nella hero, calendario/relazioni/pannello conservati; otto salvataggi intercettati, nessuna scrittura persistente. Browser corso attivo a 1440/768/390/320 px e concluso a 320 px: dati iniziali presenti, nessun riepilogo duplicato o overflow; immagini caricate e console senza errori sul corso attivo. Review visiva e screenshot della descrizione seguita dal calendario.

## 7 ottobre 2026 — Nota sul colloquio rimossa

- Su richiesta dell’utente, rimossa dal campo Note iscrizioni del corso locale ID 12 la frase `È possibile richiedere un colloquio informativo prima dell’iscrizione.`; dato demo corrispondente svuotato, secondo la skill `clean-code-engineer`, per non riproporla nelle nuove installazioni. Campo e visualizzazione delle altre note conservati; nessuna modifica al template, alle iscrizioni o agli altri corsi. Nessun reimport o commit automatico.

## v1.6.56 / plugin v1.3.17 — Corsi: informazioni e iscrizioni

- Dettaglio Corsi orientato al contatto: pannello e scorciatoia ordinaria `Informazioni e iscrizioni`, introduzione esplicativa quando disponibili i canali, sezione `Contattaci`. CTA WhatsApp/mail `Contattaci via …`; modulo opzionale conservato e invito posti limitati rivolto al contatto. Recapiti testuali e nomi accessibili completi mantenuti.
- Scadenze, stati chiusi, sold-out/lista d’attesa, quote/tesseramento/dotazione, note editoriali, anchor/focus, home/archivio e Eventi invariati. Nessuna nuova procedura di iscrizione o modifica ai dati.
- Skill `clean-code-engineer`: copy dei canali distinto nel plugin esistente, presentazione nel componente Corsi; tema/asset `1.6.56`, plugin `1.3.17`, nessun CSS, dipendenza o commit automatico.
- Verificati lint PHP, sintassi JS invariati, HTTP dettaglio/CSS e `git diff --check`. Test nativi su 20 varianti Corsi: copy/introduzione ordinari, assenza di contatti, stati chiusi e lista d’attesa, quota/dotazione, calendario, metabox e otto salvataggi intercettati; regressioni Eventi su 17 varianti e relativi salvataggi intercettati, senza persistenza. Browser sulla pagina reale a 1440/768/390/320 px: titolo, scorciatoia e canali corretti, link conservati, nessun overflow pagina/pulsanti, immagine fallita o errore console. Scorciatoia verificata con focus su `course-registration`; nessun canale esterno aperto. Review visiva e screenshot di introduzione e contatti.

## v1.6.55 — Pin anche per eventi online

- Card Eventi di home e archivio: `📍 Online`, con lo stesso pin decorativo delle sedi fisiche, nascosto agli screen reader. Luogo fisico obsoleto ancora omesso; nessuna modifica a eventi in presenza/ibridi, dettaglio o regole di pubblicazione dell’accesso online.
- Skill `clean-code-engineer`: estesa una sola condizione nel componente condiviso, senza nuovo markup, CSS, dipendenze o dati. Tema/asset `1.6.55`, plugin invariato; nessun commit automatico.
- Verificati lint PHP, sintassi JS invariati, HTTP home/archivio/CSS e `git diff --check`. Test di sola lettura su sette località in entrambi i contesti, aggiornata l’aspettativa del pin online; nessuna scrittura nel database. Browser home/archivio a 1440/768/390/320 px: un pin con `aria-hidden="true"` davanti a Online, nessun overflow, immagine fallita o errore console. Review visiva e screenshot desktop.

## v1.6.54 — Separatore Eventi anche nella home

- Card Eventi della home allineate all’archivio: titolo, linea oro tenue, data e luogo, con ordine del markup coerente. Riutilizzata la stessa regola decorativa da 1 px e spaziature compatte. Locandine, fascia sold-out, date adattive, CTA e nomi accessibili conservati; nessun abstract aggiunto o modifica a query, dati, dettaglio, Corsi e News.
- Skill `clean-code-engineer`: estesi soltanto il contesto del componente e il selettore condiviso; tema/asset `1.6.54`, plugin invariato. Nessuna dipendenza o commit automatico.
- Verificati lint PHP, sintassi JS invariati, HTTP home/CSS e `git diff --check`; regressioni native su sette località Eventi in home/archivio senza scritture. Browser home a 1440/768/390/320 px: un solo titolo/data, ordine titolo→data, bordo da 1 px alla larghezza del contenuto e nomi CTA completi; nessun overflow, immagine fallita o errore console. Card Corsi/News senza nuovi separatori. Review visiva e screenshot desktop.

## v1.6.53 — Separatore nelle card Eventi

- Estesa alle card dell’archivio Eventi la linea oro tenue delle card Corsi, con una sola regola CSS condivisa. Titolo e separatore prima di data e luogo, ordine del markup coerente con la resa visiva; home mantiene l’ordine precedente e nessuna linea. Dettaglio, contenuti, stati di iscrizione, query e date adattive invariati.
- Skill `clean-code-engineer`: modifica circoscritta al componente e ai selettori dei due archivi, senza logica di dominio o dipendenze; tema/asset `1.6.53`, plugin invariato. Modifiche precedenti preservate, nessun commit automatico.
- Verificati lint PHP, sintassi dei JS frontend invariati, HTTP pagina/CSS/immagini e `git diff --check`; regressioni native su sette località Eventi in home e archivio senza scritture. Browser Eventi a 1440/768/390/320 px: un solo titolo/data per card, ordine titolo→data, bordo e larghezza corretti, nessun overflow o errore console. Immagini dell’archivio passato inizialmente differite dal lazy-load, disponibili via HTTP. Home senza separatore e con data iniziale; card Corsi invariate. Review visiva e screenshot desktop.

## v1.6.52 — Separatore nelle card Corsi

- Linea decorativa di 1 px oro tenue sotto livello e città nelle card automatiche dell’archivio, prima di data e sede. Larghezza del contenuto, spaziature compatte; home, dettaglio, titoli personalizzati, dati e nomi accessibili invariati. Skill `clean-code-engineer`: solo CSS del componente, senza nuovo markup o dipendenze; tema/asset `1.6.52`, plugin invariato. Modifiche precedenti preservate, nessun commit automatico.
- Verificati lint PHP, sintassi dei due JS frontend invariati, HTTP pagina/CSS e `git diff --check`. Browser a 1440/768/390/320 px: bordo da 1 px con opacità corretta, larghezza del contenuto e posizione tra identità e data, nessun overflow o immagine rotta; console senza errori. Review visiva e screenshot desktop.

## v1.6.51 / plugin v1.3.16 — Livello e città in primo piano

- Nelle sole card automatiche dell’archivio Corsi, `Corso Sommelier` ridotto a introduzione 16–18 px; livello e città a 20–24 px, provincia discreta a 16–18 px. Livello indivisibile, località flessibile e nome accessibile completo. Home, dettaglio, titoli personalizzati, query, date, sede e abstract invariati.
- Skill `clean-code-engineer` e `software-architect`: parti della località normalizzate nel plugin ed esposte senza rimuovere la località completa dalla API esistente; presentazione nel tema, nessuna analisi del titolo libero, migrazione, scrittura editoriale o dipendenza aggiunta. Tema/asset `1.6.51`, plugin `1.3.16`; nessun commit automatico.
- Verificati lint PHP, sintassi dei due JS frontend invariati, HTTP pagina/CSS e `git diff --check`. Test nativi di sola lettura: sei normalizzazioni della località, quattro card strutturate (anche senza città/provincia), nomi accessibili completi e home/titoli personalizzati conservati; regressioni su sette località Eventi in home e archivio. Browser a 1440/768/390/320 px: scala tipografica corretta, livello indivisibile, nessun overflow, immagine rotta o errore console. Review visiva e screenshot; nessuna scrittura nel database.

## v1.6.50 / plugin v1.3.15 — Identità dei Corsi, titolo automatico e pin

- Card standard dell’archivio Corsi: `Corso Sommelier` e seconda riga livello/località con provincia tra parentesi, senza duplicare il livello nella fascia superiore. Livello indivisibile, badge separato e nomi accessibili completi. Sede con `📍` decorativo; città non duplicata quando la sede manca. Titolo personalizzato conservato come eccezione. Date iniziali, abstract e CTA invariati; data finale ancora solo nel dettaglio.
- Scelta Automatico/Personalizzato nel metabox Corsi e anteprima. Unico titolo nativo `post_title`, composizione nel plugin da Livello/Città/Provincia, senza analizzare il titolo libero. Default di sola lettura Automatico per livelli validi e nuovi auto-draft; titolo nel database preservato fino al salvataggio autorizzato. Niente migrazione o rinomina degli URL. Preparazione nel classico e sincronizzazione dopo il salvataggio metabox di Gutenberg, con hook ripristinato in `finally`; editor precedenti senza nuova scelta non sovrascrivono il titolo. Script nativo limitato ai Corsi, nessuna modifica del titolo alla sola visita dell’editor.
- Luogo delle card Eventi in home/archivio con pin, sede e città/provincia, campi vuoti omessi. Online senza pin; ibrido con `+ online`, oppure `In presenza e online` se manca il luogo. Componente del pin condiviso, emoji nascosta agli screen reader, wrapping dei nomi lunghi. Accesso online, Maps e pagine di dettaglio invariati. Home Corsi conserva il layout compatto e il titolo nativo.
- Skill `software-architect` e `clean-code-engineer`: scelta esplicita per le eccezioni, API/identità nel plugin e presentazione nel tema, nessun dato titolo duplicato, dipendenza o filtro globale. Documentazione aggiornata; precedente lavoro preservato e nessun commit automatico. Discrepanza locale ID 12 (Falconara nel titolo libero, Jesi nel campo Città) segnalata e non corretta automaticamente.
- Verifiche: lint di nove PHP, sintassi JS e `git diff --check`; HTTP degli archivi e asset. Test reali nel runtime WordPress di creazione/aggiornamento e salvataggio a due fasi su bozze, titolo vuoto/personalizzato, ricomposizione, slug stabile, nonce/target/campo assente e enum privato. Transazione con rollback finale verificato sui conteggi di post/meta, senza contenuti di prova persistenti. Test JS con store simulato per blocchi/classico: nessuna modifica iniziale, dati mancanti, aggiornamento titolo e passaggio di modalità. Sette varianti Eventi in home/archivio per località, pin, presenza/online/ibrido e escaping; regressioni Corsi (20 varianti), sold-out Eventi e Riassunto superate.
- Browser: archivi Corsi/Eventi e anteprima dei metabox a 1440/768/390/320 px, nessun overflow o immagine rotta, livello unito e nomi accessibili verificati. Preview classica con script/core `dom-ready` reali: modifiche di città, titolo personalizzato e ritorno ad Automatico; nessun errore console nei controlli Corsi/editor. Home mobile verificata e screenshot dell’archivio Corsi. Nessun salvataggio autenticato via browser: il test dei blocchi è unitario, quello del server usa il runtime WordPress.

## 7 ottobre 2026 — Sede e data nelle card dell’archivio Corsi

- Rimossa la data finale dalle card dei corsi attivi/conclusi; luogo con nome della Sede anziché sola città, con ripiego sulla città se manca la sede e nessuna riga se mancano entrambe. Nome escapato, senza troncamenti o modifiche al titolo. Card compatte della home invariate. Data di fine conservata nel dettaglio, nell’editor e nelle regole temporali del plugin.
- Skill `clean-code-engineer`: modifica circoscritta al componente esistente del tema, nessun nuovo campo o duplicazione della logica di stato. README e specifica aggiornati; CSS/JS, dati salvati e modifiche precedenti preservati. Nessun commit automatico.
- Verificati lint PHP, HTTP archivio/dettaglio e `git diff --check`. Test nel runtime WordPress: sette sedi (normali, vuote, spazi, caratteri HTML e nome lungo), due date finali e contesti archivio/home; ripiego, escaping, data iniziale, stato derivato dalla fine e CTA accessibile corretti, senza scritture nel database. Browser archivio a 1440/768/390/320 px: sede corretta, nessuna data finale, overflow o immagine rotta; nessun errore console. Review visiva/screenshot e controllo che la home mantenga la città.

## 7 ottobre 2026 — Etichette degli archivi Eventi e Corsi

- `Archivio` sopra Eventi conclusi e Corsi conclusi, al posto di `Il nostro percorso` e `Archivio formativo`; `In programma` sopra Corsi attivi, al posto di `Iscrizioni e lezioni`. Intervento sui soli testi dei due template, secondo la skill `clean-code-engineer`: titoli, query, ordine, card, asset e contenuti editoriali invariati. Specifiche aggiornate e modifiche precedenti preservate; nessun commit automatico.
- Verificati lint dei due PHP, risposta HTTP con le nuove etichette e `git diff --check`. Browser a 1440/768/390 px su entrambi gli archivi: testi corretti, nessun overflow, immagine rotta o errore console. Screenshot dell’archivio Corsi.

## Plugin v1.3.14 — Breve descrizione nei dettagli di Eventi e Corsi

- Riassunto nativo spostato all’inizio dei metabox principali, con label `Breve descrizione` e istruzioni sulla sua pubblicazione. Box Eventi rinominato `Dettagli dell’evento`, ID conservato; box Corsi `Dettagli del corso`. Metabox classico/pannello laterale separati rimossi soltanto per questi CPT, senza togliere il supporto nativo all’estratto.
- Unico dato `post_excerpt`: lettura raw escapata, `id`/`name` nativi per il classico e sincronizzazione bidirezionale con lo stato nativo nell’editor a blocchi, tramite API WordPress. Nessun nuovo meta o handler di salvataggio; valori vuoti e inizializzazione differita gestiti, salvataggio/autosalvataggio affidati al core. Testi esistenti, frontend e altri editor invariati. Tema ancora `1.6.49`.
- Skill `clean-code-engineer`: helper condiviso fra Eventi e Corsi, responsabilità nel plugin, nessun dato duplicato o dipendenza esterna. README, specifiche e architettura aggiornati; nessuna modifica ai contenuti nel database o commit automatico.
- Verificati lint PHP, sintassi JS, HTTP archivio/nuovo asset e `git diff --check`. Test PHP nel runtime WordPress su entrambi i metabox: testo esistente, quattro casi di escaping e quattro traduzioni native excerpt→post_excerpt, campo assente, supporto CPT, rimozione duplicato e caricamento script limitato agli editor a blocchi pertinenti. Test JS con store simulato: inizializzazione differita, sei valori/testo vuoto, sincronizzazione, annulla/ripristina, assenza di feedback ricorsivo e pulizia della sottoscrizione. Nessun salvataggio amministrativo autenticato effettuato.
- Anteprime dei metabox generate dal PHP reale e controllate nel browser a 1440/768/390/320 px: campo unico, label e istruzioni associate, nessun overflow, asset caricati e nessun errore console; inserimento multilinea/caratteri speciali verificato senza salvataggio. Screenshot Corsi e review visiva. Le anteprime non riproducono l’intero editor Gutenberg.
- Regressioni native Corsi (20 varianti) ed Eventi sold-out/scadenze superate, inclusi salvataggi intercettati e metabox: nessuna scrittura persistente.

## v1.6.49 — Dimensioni delle card nell’archivio Corsi

- Allineata la griglia Corsi a quella Eventi: tre colonne da 832 px, due da 608 px e una su mobile. Riutilizzata la stessa regola desktop e rimosso l’override a due colonne sulle larghezze maggiori, secondo la skill `clean-code-engineer`. Immagini quadrate, contenuti e tipografia invariati; home e dettaglio non modificati.
- Tema/asset `1.6.49`, specifica aggiornata. Plugin, dati e precedenti modifiche preservati; nessun commit automatico.
- Verificati lint PHP, sintassi JS, HTTP pagina/CSS e `git diff --check`. Confronto browser Corsi/Eventi/home a 1440/1024/832/831/768/608/607/390/320 px: larghezze delle card dei due archivi identiche, transizioni dei breakpoint corrette, home invariata, nessun overflow, immagine rotta o errore console. Review visiva e screenshot della griglia desktop.

## v1.6.48 — Corsi allineati agli Eventi

- Tema `1.6.48`: archivio con fascia compatta scura, dettaglio con hero chiara, titoli/corpo/editor Poppins e immagini quadrate intere su bianco. Sede, indirizzo, città/provincia e Maps nei due riepiloghi; un solo pannello finale Iscrizione con scorciatoia e focus da tastiera. Quota, tesseramento e dotazione distinti; condizioni editoriali libere conservate, € automatico solo per importi isolati. Calendario/import, relatori/note e relazioni con Eventi invariati.
- Plugin `1.3.13`: disponibilità ordinaria/sold-out/lista d’attesa, posti limitati, Maps opzionale e contatti WhatsApp ripetibili con nominativo/numero con o senza +39. Meta privati, sanitizzatori condivisi, marker e protezioni native del salvataggio; fallback dal numero precedente senza migrazioni o scritture automatiche. Termine tassativo superato chiude le iscrizioni; corso concluso prioritario. Corso attivo distinto da iscrizioni aperte. Stessi canali per la lista d’attesa, senza raccolta delle richieste nel sito.
- Home: card compatte con livello, inizio e disponibilità, fino a tre corsi; link Tutti i corsi sempre presente, totale quando eccede tre e stato vuoto funzionante. Componenti contatti, luogo, scadenza, posti limitati e sold-out riusati con gli Eventi; date adattive anche nell’archivio Corsi. Nessuna modalità online/ibrida o quote Soci/Non soci imposte ai Corsi.
- Skill `software-architect` e `clean-code-engineer`: separate regole di dominio e presentazione, preservate specificità Corsi e API Eventi, condivisi soltanto componenti/regole pertinenti. README e specifiche aggiornati; nessuna dipendenza, contenuto reale modificato o commit automatico.
- Verifiche native: lint PHP, sintassi JS e `git diff --check`; 20 varianti Corsi (scadenze, stati, sedi parziali/assenti/lunghe, canali, quota zero), sanitizzatori/meta, sei formati quota, fallback legacy/elenco vuoto e otto salvataggi intercettati. Home con 0/1/3/4/7 corsi e import calendario; regressioni Eventi su sold-out, scadenze, accesso online e luogo. Vecchia aspettativa “Corsi invariati” del test scadenze aggiornata alla nuova specifica. Nessuna scrittura persistente durante i test.
- Browser: home, archivio, dettaglio attivo/concluso e cinque anteprime native a 1440/768/390/320 px; nessun overflow della pagina, font e immagini non ritagliate, tabella contenuta. Card home sold-out/lista d’attesa, repeater WhatsApp da tastiera, ID univoci e focus della scorciatoia verificati. Controlli HTTP/asset e review visiva desktop/mobile. Nessun login, salvataggio amministrativo o apertura di canali esterni; anteprime con dati di esempio.

## v1.6.47 — Evento ibrido prima della sede

- Raffinato il componente condiviso Luogo: riquadro `EVENTO IBRIDO` / `In presenza e online` sopra la sede, in hero e informazioni pratiche, anziché Anche online sotto. Titolo 14 px/700 e testo 16 px/600, su due righe, con accento oro e bordeaux; nessuna ripetizione In presenza se manca la sede. `Online` a 16 px/600 in grassetto semantico in entrambi i riepiloghi. Regole di accesso, Maps, plugin/dati e Corsi invariati.
- Skill `clean-code-engineer` applicata intervenendo nel componente condiviso senza duplicazioni o dipendenze. Tema/asset `1.6.47`, specifica aggiornata; nessuna scrittura nel database o commit automatico.
- Verifiche: lint dei due PHP, sintassi JS, HTTP e `git diff --check`; dodici varianti native di Luogo, ordine del riquadro prima dei dati fisici, grassetto Online e regressioni della pubblicazione facoltativa dell’accesso. Browser su ibrido e online a 1440/768/390/320 px: posizione, pesi font, asset e ritorni a capo verificati, nessun overflow o errore console. Review visiva e screenshot con dati di esempio; nessun login o collegamento esterno aperto.

## v1.6.46 — Luogo uniforme per online e ibrido

- `LUOGO: Online` nei due riepiloghi, senza riga Modalità. Ibridi con `Anche online` ben visibile dentro Luogo in entrambe le posizioni: etichetta su riga propria, Poppins 16 px/600, bordeaux e fondo caldo. Sede/indirizzo/città/Maps conservati; senza sede fallback In presenza + Anche online. Dati fisici obsoleti non mostrati per online.
- Skill `clean-code-engineer` applicata riusando `event-location.php` nei due riepiloghi invece di mantenere markup divergente. Intervento nel tema/asset `1.6.46`, nessuna modifica a plugin, dati, Corsi o regola di pubblicazione facoltativa dell’accesso online. Specifica e architettura aggiornate, nessuna dipendenza o commit automatico.
- Verifiche: lint dei tre PHP, sintassi JS, `git diff --check` e HTTP. Dodici varianti native in memoria: online/ibrido/presenza, sede assente, solo Maps o indirizzo, nomi lunghi, testo da escapare, evento concluso e accesso pubblico/nascosto. Riepiloghi identici, nessuna riga Modalità o perdita della modalità ibrida. Regressioni accesso online, prenotazioni e sold-out superate senza scritture persistenti.
- Browser su quattro anteprime native, desktop/tablet/mobile a 1440/768/390/320 px: etichette, font, asset e ritorni a capo corretti, nessun overflow o errore console. Review visiva e screenshot del recap inferiore ibrido con dati di esempio; nessun login, salvataggio amministrativo o link esterno aperto.

## v1.6.45 — Pubblicazione facoltativa dell’accesso online

- Plugin `1.3.12`: checkbox `Mostra le informazioni per partecipare online` nel box Modalità e luogo, solo online/ibrido, default disattivato anche per gli eventi esistenti. Meta booleano privato, sanitizzazione conservativa e salvataggio protetto con marker. Piattaforma/link rimangono compilabili e salvati; nessuna migrazione, scrittura automatica o modifica ai Corsi.
- API del plugin per i soli dati pubblici, usata dal tema `1.6.45`: nessuna piattaforma/URL nell’HTML se nascosti, accesso visibile solo quando abilitato. Supportato anche URL senza nome della piattaforma; eventi conclusi ancora senza link. Modalità, luogo fisico/Maps e canali di prenotazione invariati. Skill `clean-code-engineer` applicata mantenendo regola nel plugin e presentazione nel tema, riusando metabox/script nativi senza dipendenze. Documentazione aggiornata; nessun commit automatico.
- Verifiche: lint dei sei PHP modificati, sintassi JS, `git diff --check` e HTTP. Sedici varianti native con dati in memoria (default, online/ibrido/presenza, dato malformato, evento concluso, URL senza piattaforma, protocolli non ammessi, sold-out/lista d’attesa), dieci input del sanitizzatore, meta/default/editor e sette salvataggi intercettati. Regressioni sold-out, scadenze e prenotazioni senza scritture persistenti.
- Browser su editor e tre anteprime native a 1440/768/390/320 px: default non selezionato, toggle preservato cambiando modalità, campi/URL conservati, assenza dell’accesso nell’HTML quando nascosto, contatti disponibili e dati pubblici solo quando abilitati. Nessun overflow, asset rotto o errore console; nessun login, salvataggio amministrativo o collegamento esterno aperto. Screenshot editor con dati di esempio.

## v1.6.44 — Fascia sold-out sotto la locandina

- Componente testuale condiviso `event-sold-out.php`, sotto l’immagine nelle card home/archivio e nel dettaglio: bordeaux, bianco, accento oro, Poppins 700 e indicazione della lista d’attesa quando disponibile. Nessuna sovrapposizione all’immagine, che conserva proporzioni quadrate e `contain`. Badge ordinario nel corpo della card conservato solo per iscrizioni chiuse; eventi conclusi e ordinari invariati.
- Sold-out senza lista d’attesa: data del termine nascosta nei due box, conservata nei dati. Lista d’attesa e chiusura per termine tassativo mantengono la scadenza; regole del plugin e priorità invariate. Skill `clean-code-engineer` applicata riusando un unico componente per le tre viste. Solo presentazione, tema/asset `1.6.44`; nessuna dipendenza, scrittura nel database o commit automatico.
- Verifiche: lint dei cinque PHP, sintassi JavaScript e `git diff --check`; diciassette varianti native in memoria, inclusa scadenza futura del sold-out e regressioni su chiusura/lista d’attesa, meta/editor e salvataggi intercettati. HTTP e browser desktop/tablet/mobile a 1440/768/390/320 px: fascia sotto l’immagine, font caricati, locandina intera, nessun overflow o asset rotto. Anteprime con dati simulati, senza modificare eventi reali.

## v1.6.43 — Sold-out e lista d’attesa

- Plugin `1.3.11`: meta privato enum `_fisar_event_booking_status`, sanitizzazione condivisa e menu a tre opzioni nel box Iscrizioni. Stato assente ordinario, salvataggio solo con campo nel POST e protezioni esistenti. API effettiva con precedenza della scadenza tassativa; helper chiusura compatibile anche con sold-out senza lista. Nessun conteggio o richiesta persistita, migrazione o modifica ai Corsi.
- Lista d’attesa tramite gli stessi contatti/URL e nominativi, con etichette dedicate; sold-out semplice senza canali. Copy e posti limitati coerenti con lo stato. Tema/asset `1.6.43`: avviso nei due box anche senza data, scorciatoia e titoli dedicati, note operative ordinarie nascoste e quote conservate. Badge nelle card di home/archivio, nessuno sugli eventi conclusi. Stato concluso e termine tassativo superato prioritari.
- Skill `software-architect` applicata scegliendo un enum reversibile invece di booleani indipendenti; `clean-code-engineer` centralizza regole/copy nel plugin e riusa componenti/frontend. Documentazione aggiornata, nessuna dipendenza, dato reale modificato o commit automatico.
- Verifiche: lint dei nove PHP modificati, sintassi dei tre JS e `git diff --check`; sedici varianti di disponibilità, otto normalizzazioni enum, registrazione/meta privato, editor/default e otto salvataggi intercettati. Regressioni scadenze, prenotazioni, quote e contatti senza scritture persistenti. Browser su anteprime native: lista d’attesa, sold-out semplice, editor e card verificati su desktop/tablet/mobile, pulsanti/URL corretti e overflow assente; selezione preservata disattivando/riattivando Iscrizione richiesta. Nessun login, salvataggio amministrativo o link esterno aperto; server temporaneo arrestato al termine.

## v1.6.42 — Iscrizioni Eventi chiuse dopo il termine tassativo

- Plugin `1.3.10`: nuova regola derivata per iscrizione richiesta, termine tassativo e data canonica valida precedente al giorno corrente WordPress. Scadenza inclusa; termini flessibili/assenti/invalidi non chiudono. API strutturata con `closed`/`closed_notice`, API testuale aggiornata, canali Eventi vuoti e copy operativo/posti limitati soppressi quando chiuso. Nessun meta riscritto, flag manuale o cron; Corsi invariati.
- Tema/asset `1.6.42`: `Iscrizioni chiuse` sia in hero sia nel pannello, scadenza conservata come informazione. Scorciatoia `Informazioni sulle iscrizioni` con destinazione/focus invariati; quote visibili, canali e note operative nascosti. Evento concluso prioritario. Skill `clean-code-engineer` applicata centralizzando la regola nel plugin e riusando il componente della scadenza senza nuovi stili/dipendenze. README, specifiche e assunzioni aggiornati; nessuna modifica agli eventi reali o commit automatico.
- Verifiche: lint dei sei PHP modificati, sintassi dei tre JS e `git diff --check`; evento/CSS HTTP 200. Quattordici varianti dedicate (ieri, oggi, domani, flessibile superato, senza prenotazione/data/tipo valido, date impossibili/non bisestili/relative, gratuità, evento concluso), più regressioni prenotazione, quote e contatti WhatsApp, salvataggi intercettati senza persistenza. Fixture WhatsApp resa indipendente dalla scadenza reale modificata editorialmente dall’utente.
- Browser sull’evento reale con scadenza tassativa al 4 ottobre ed evento all’8 ottobre: stato chiuso in entrambi i box, nessun pulsante operativo, quote conservate. Desktop/tablet/mobile a 1440/768/390/320 px senza overflow o immagini rotte, font caricati e console senza errori. Scorciatoia da tastiera con focus sul pannello; review visiva e screenshot. Nessun login, modifica al database o link esterno aperto.

## 6 ottobre 2026 — Istruzioni dei campi quota

- Sostituito il vecchio esempio `€ 25` nel testo di aiuto con la distinzione tra importo senza valuta (`25`, con euro aggiunto automaticamente) e testo libero (`Da 25 €`, `Offerta libera`). Istruzione condivisa fra Quota soci e Quota non soci, secondo la skill `clean-code-engineer`; specifica aggiornata. Versioni, dati, salvataggio e frontend invariati; nessun commit.
- Verificati lint PHP, `git diff --check`, rendering nativo delle due descrizioni ed esempi nel formatter. Rieseguite normalizzazione quote, salvataggi intercettati e varianti del dettaglio, senza scritture persistenti.

## Plugin v1.3.9 — Numero WhatsApp con o senza +39

- Campo Eventi semplificato in `Numero WhatsApp`, tipo `tel` e `inputmode=tel`, esempio e istruzioni per il numero con o senza prefisso italiano. Aggiornati anche feedback screen-reader e fallback senza JS del repeater; nominativi, elenco e protezioni del salvataggio invariati.
- Il normalizzatore condiviso accetta un codice paese di default facoltativo: soltanto i canali Eventi passano `39`. Prefisso aggiunto al link dei numeri nazionali validi, mai al dato salvato o al riferimento mostrato; rispettati `+39`, `0039`, prefissi esteri e zero iniziale dei fissi. Vecchi URL, messaggi e parametri preservati. Skill `clean-code-engineer` applicata tenendo la regola nel plugin e conservando il comportamento predefinito per i Corsi. Plugin/asset admin `1.3.9`, tema `1.6.41` invariato; README e specifiche aggiornati. Nessuna migrazione, modifica ai dati reali, nuova dipendenza o commit automatico.
- Verifiche: lint dei tre PHP modificati, sintassi JS e `git diff --check`; evento e asset admin versionati HTTP 200. Quattordici casi di numeri (nazionali formattati, internazionali espliciti, fissi, invalidi, vecchi URL/messaggi), sette normalizzazioni dell’elenco, dieci salvataggi intercettati incluso un numero senza prefisso, otto formattazioni visuali e rendering frontend/metabox. Rieseguite quattordici varianti iscrizione, undici normalizzazioni condivise, sei salvataggi contatti e dodici casi valuta. Tutti senza scritture persistenti.
- Browser su anteprime native con dati simulati: input telefonici, label, istruzioni, aggiunta/rimozione e focus verificati; editor e frontend a 1440/768/390/320 px senza overflow. Numero italiano senza prefisso visibile e link `wa.me` con `39`; nessuna immagine rotta o errore console frontend. Nessun login, salvataggio amministrativo o link esterno aperto; server di anteprima arrestato al termine.

## v1.6.41 — Nome e numero WhatsApp sulla stessa riga

- Nome e numero/link del destinatario nello stesso paragrafo, separati da `·`, senza a capo imposto e con ritorno naturale quando lo spazio non basta. Rimossa la classe della riga nome separata. Gli altri canali restano invariati.
- Prefisso italiano esplicito `+39` o `0039` omesso solo nel riferimento visuale degli Eventi; il formatter del tema lavora sul riferimento già normalizzato dal plugin. Prefissi esteri, numeri senza prefisso e URL di gruppi/canali non alterati. Dati editoriali, pulsanti, URL e nomi accessibili completi conservati. Skill `clean-code-engineer` applicata mantenendo questa scelta di presentazione nel tema, senza duplicare la normalizzazione del plugin. Tema/asset `1.6.41`, plugin `1.3.8` invariato; nessuna scrittura nel database o commit automatico.
- Verifiche: lint dei due PHP modificati, sintassi dei tre script JS e `git diff --check`; pagina, CSS versionato e font HTTP 200. Otto casi del formatter (numeri italiani formattati/compatti, `0039`, prefisso estero, numeri nazionali e URL), sette normalizzazioni dei contatti e dieci salvataggi intercettati, rendering nativo aggiornato e quattordici regressioni di prenotazione, senza persistenza. Il caso legacy è simulato anche nell’esistenza del meta, indipendentemente dai nuovi contatti inseriti dall’utente.
- Browser sul contatto reale già inserito a 1440/768/390/320 px: nome e numero sulla stessa linea, riferimento senza `+39`, URL internazionale invariato, font caricati e nessun overflow, immagine rotta o errore console. Review visiva desktop/mobile; nessuna modifica ai contatti reali o apertura del link WhatsApp.

## v1.6.40 — Nominativi e più contatti WhatsApp per gli Eventi

- Plugin `1.3.8`: elenco privato ordinato `_fisar_event_whatsapp_contacts` con `name` facoltativo e `value` (numero o link). Sanitizzazione condivisa, righe vuote/malformate ignorate, URL e messaggi codificati conservati. Fallback di sola lettura dal contatto precedente quando il nuovo meta non esiste; elenco esplicitamente vuoto senza ricomparsa del legacy. Primo destinatario sincronizzato nel vecchio campo dopo un salvataggio del nuovo metabox.
- Editor Eventi, box `Iscrizioni`: due campi distinti per contatto, `Aggiungi contatto WhatsApp`/`Rimuovi contatto`, fieldset/label, indici monotoni, focus e feedback screen-reader. Riga vuota per uso senza JS; istruzione sulla pubblicazione dei nominativi/recapiti. Nonce/capability/autosave/revision esistenti, marker di presenza e gestione slash/apostrofi; editor precedente senza marker non azzera il nuovo elenco o il riferimento legacy sincronizzato. Corsi invariati.
- Tema/asset `1.6.40`: nome su riga separata da numero/link e un pulsante per destinatario, nella griglia dei canali esistente. Suffisso accessibile con nome o riferimento; numero leggibile anche senza link. Riutilizzata la normalizzazione WhatsApp, nessun prefisso dedotto o dipendenza aggiunta. Skill `clean-code-engineer` applicata mantenendo dati e salvataggio nel plugin, presentazione nel tema; README e specifiche aggiornati. Nessun dato reale modificato o commit automatico.
- Verifiche: lint dei sei PHP modificati, sintassi dei tre JS e `git diff --check`; evento, Corso, CSS tema/admin, JS admin e font HTTP 200. Sette normalizzazioni del nuovo elenco, registrazione meta, fallback legacy/elenco vuoto, ordine, link con messaggio e assenza di prefisso; dieci salvataggi intercettati (compresi rimozione totale, marker assente, input malformato, slash, vecchio editor, nonce/capability negati), rendering nativo frontend/metabox. Rieseguite quattordici varianti di prenotazione, dodici casi valuta, undici WhatsApp, sei salvataggi contatti, nove normalizzazioni quote, otto salvataggi quote e undici varianti complete del dettaglio, senza persistenza.
- Browser su anteprime native con due contatti di esempio a 1440/768/390/320 px: campi associati, nomi/numeri/pulsanti corretti, font caricati e nessun overflow, immagine rotta o errore console. Aggiunta/rimozione anche da tastiera, cancellazione totale e nuova aggiunta, focus, ID univoci e valori preservati. Review visiva desktop/mobile; evento reale con contatto precedente ancora presente e corretto. Nessun login o salvataggio amministrativo autenticato: i test di editor/salvataggio sono anteprime e intercettazioni in memoria. Nessun link di prenotazione aperto; server temporaneo arrestato al termine.

## v1.6.39 — Posti limitati anche nel riepilogo iniziale

- Successiva correzione richiesta dall’utente: rimosso `si`; il copy definitivo nei due box è `Posti limitati. Ti consigliamo di prenotare prima che esauriscano.` Nessun cambiamento a markup, stili o condizioni di visualizzazione. Rieseguiti lint PHP, quattordici varianti e controllo HTTP del testo in entrambe le posizioni.

- Sostituito `Prenota appena possibile.` con `Ti consigliamo di prenotare prima che si esauriscano.` dopo `Posti limitati.` in grassetto. Avviso ripetuto nel box iniziale prima della scorciatoia e nel pannello finale dopo la scadenza, solo con flag attivo e per eventi non conclusi. Testo 16 px, interlinea 1.6, fondo bianco e bordo bordeaux, senza ereditare il maiuscolo delle etichette del riepilogo.
- Componente condiviso `event-limited-seats.php`; copy fornito dai dettagli di prenotazione del plugin tramite `limited_seats_notice`. Se manca una scadenza valida, il box iniziale contiene solo avviso e scorciatoia; se mancano entrambi resta il link autonomo. Tema/asset `1.6.39`, plugin `1.3.7`. Skill `clean-code-engineer` applicata evitando duplicazioni di copy e markup. Specifiche aggiornate; nessuna scrittura nel database, dipendenza o commit automatico.
- Verifiche: lint dei sei file PHP modificati/aggiunti, sintassi dei due script frontend e `git diff --check`; evento, CSS versionato e font HTTP 200. Quattordici varianti native del dettaglio, comprese scadenze assenti/non valide, flag spento, gratuità ed evento concluso; asserzioni su testo aggiornato, avvisi dentro entrambi i box e singola scorciatoia. Dodici casi valuta, undici WhatsApp e sei salvataggi intercettati senza persistenza.
- Browser sull’evento Verdicchio a 1440/768/390/320 px: due note identiche, testo/etichetta 16 px, nessun overflow o immagine rotta, font caricati e console senza errori. Review visiva desktop/mobile e link interno funzionante con focus sul pannello. Evento giapponese senza flag: avviso assente; evento ibrido con flag: presente nelle due posizioni. Nessun login, prenotazione o link esterno aperto.

## v1.6.38 — Avviso di partecipazione libera più leggibile

- Aumentata soltanto la frase `La partecipazione è libera, non è richiesta la prenotazione.` da 16 a 18 px, mantenendo Poppins e grassetto 600; interlinea 1.6 e ritorno a capo naturale. Altri avvisi, quote, Corsi e contenuti invariati.
- Plugin `1.3.6`: aggiunto `open_participation_notice` ai dettagli di prenotazione, riusando il testo esistente e conservando `lines` e l’API testuale. Tema/asset `1.6.38`: classe dedicata al solo messaggio. Skill `clean-code-engineer` applicata mantenendo copy/regole nel plugin e presentazione nel tema. Specifiche aggiornate; nessun dato modificato o commit automatico.
- Verifiche: lint dei quattro file PHP modificati, sintassi dei due script frontend e `git diff --check`; evento, CSS e font HTTP 200. Tredici regressioni del pannello con nuove asserzioni sulla classe dedicata, dodici casi valuta, undici normalizzazioni WhatsApp e sei salvataggi intercettati, senza scritture persistenti.
- Browser sull’evento gratuito con partecipazione libera a 1440/768/390/320 px: frase a 18 px/600, altro avviso a 16 px, font caricati e nessun overflow, immagine rotta o errore console. Review visiva desktop/mobile; evento concluso senza la nuova classe. Nessun login, link esterno aperto o prenotazione inviata.

## v1.6.37 — Un solo separatore tra quote e prenotazione

- Corretto il doppio bordo nel pannello dell’evento `in-cantina-con-il-vignaiolo`: quando `registration-fees` è immediatamente seguito da `registration-methods`, rimane il solo bordo inferiore delle quote. Rimossi bordo e padding superiore aggiuntivi dai metodi soltanto in questo caso; separazione invariata quando c’è una nota di partecipazione intermedia o mancano le quote.
- Regola CSS condivisa e circoscritta agli Eventi, secondo la skill `clean-code-engineer`. Tema/asset `1.6.37`, specifica Evento aggiornata; template, plugin, contenuti, quote, contatti e focus invariati. Nessuna scrittura nel database o commit automatico.
- Verifiche: lint PHP del file modificato, sintassi dei due script frontend e `git diff --check`; evento, CSS versionato e font HTTP 200. Rieseguite tredici varianti del pannello, dodici casi valuta, undici normalizzazioni WhatsApp e sei salvataggi intercettati senza persistenza. Browser sull’evento indicato a 1440/768/390/320 px: bordo quote 1 px, bordo metodi e padding superiore 0 px, font caricati, nessun overflow, immagine rotta o errore console; review visiva desktop/mobile. Caso con nota intermedia verificato a 1440/320 px: bordo metodi 1 px e padding 20 px conservati.

## v1.6.36 — Quote uniformi e importi allineati

- Quote personalizzate presentate come Soci/Non soci nello stesso elenco: rimossi wrapper di categoria, separatore interno, margini/padding aggiuntivi e peso distinto delle etichette. Una sola griglia condivisa dimensiona la colonna delle etichette e allinea tutti gli importi, con gap verticale 8 px e orizzontale 12 px. Note mantenute a tutta larghezza, testi lunghi liberi di andare a capo e spazio minimo per gli importi su mobile.
- Componente `event-fees.php` condiviso fra pannello finale ed eventi conclusi; template compatibile con quote legacy. Skill `clean-code-engineer` applicata eliminando gli stili separati delle opzioni. Tema/asset `1.6.36`, specifica Evento aggiornata; plugin `1.3.5`, editor, dati e formattazione euro invariati. Preservate le modifiche non ancora committate della v1.6.35 e le quote inserite dall’utente; nessun commit automatico.
- Verifiche: lint dei due file PHP modificati, sintassi dei due script frontend e `git diff --check`; evento, CSS versionato e font HTTP 200. Rieseguiti nove casi di normalizzazione, otto salvataggi intercettati, undici varianti complete del dettaglio e rendering metabox, senza scritture persistenti.
- Browser: evento reale con quattro quote a 1440/768/390/320 px, importi alla stessa coordinata orizzontale, etichette a peso 400, spaziatura uniforme e nessun separatore interno. Review visiva desktop/mobile, font caricati, nessun overflow, immagine rotta o errore console. Anteprima WordPress con note e un’etichetta molto lunga alle stesse quattro larghezze: importi allineati, note a tutta larghezza e nessun overflow. Nessun login o link esterno aperto; server temporaneo arrestato al termine.

## v1.6.35 — Quote Evento personalizzabili e plugin v1.3.5

- Conservati campi e dati Soci/Non soci. Aggiunti meta singoli privati `_fisar_event_fee_options` (righe ordinate etichetta/importo/nota) e `_fisar_event_fee_note` (nota generale multilinea). Sanitizzazione centralizzata nel plugin: input malformati e righe incomplete scartati, `0` valido, nessun importo presunto o calcolo. Nuova API `fisar_cdj_get_event_fees()`; gratuità ancora prioritaria, senza cancellare quote salvate.
- Metabox nativo `Partecipazione e costi`: nota generale, altre opzioni e pulsanti `Aggiungi quota`/`Rimuovi quota`. Template PHP condiviso fra righe salvate, riga vuota e aggiunta JS; indici monotoni senza collisioni dopo rimozioni, label associate, fieldset, focus gestito e feedback screen-reader. Fallback senza JS tramite riga vuota e rimozione svuotando i campi. Salvataggio con nonce/capability esistenti, marker per proteggere i nuovi dati se il box manca dal POST, gestione corretta di slash e apostrofi.
- Componente pubblico `event-fees.php` esteso senza duplicazioni: nota generale sopra, Soci/Non soci ravvicinati come prima, opzioni con etichetta/importo e nota sotto. Separatore sottile soltanto in presenza di entrambe le categorie. Stile Poppins del dettaglio, ritorno a capo naturale, formatter euro esistente. Quote una sola volta nel pannello finale o, per eventi conclusi, nelle informazioni pratiche. Supportate quote uniche senza Soci/Non soci; nessuna dicitura che confonda alternative con supplementi.
- Tema/asset `1.6.35`, plugin/asset amministrativi `1.3.5`. Skill `clean-code-engineer` applicata mantenendo dati/regole nel plugin e un unico componente di presentazione nel tema. README, specifiche e architettura/assunzioni aggiornati. Nessuna dipendenza, migrazione, modifica ai Corsi, dati di prova persistiti o commit automatico.
- Verifiche: lint PHP di plugin e tema, sintassi dei tre script JS e `git diff --check`; evento, CSS tema/admin, JS admin e font HTTP 200. Nove normalizzazioni delle righe, note malformate/multilinea, otto salvataggi intercettati (anche nonce/capability negati, metabox assente, rimozione totale e slash), undici varianti complete del template e rendering nativo del metabox, senza scritture persistenti. Rieseguite nove regressioni di orari/modalità, tredici di prenotazione, dodici valuta, undici WhatsApp e sei salvataggi dei contatti.
- Anteprime generate da WordPress con dati simulati: frontend a 1440/768/390/320 px senza overflow o immagini rotte, font caricati e quote una sola volta; review visiva desktop/mobile, console senza errori. Metabox provato a desktop/320 px: aggiunta/rimozione anche da tastiera, eliminazione totale e nuova aggiunta, focus, ID univoci e valori conservati passando a gratuito e tornando indietro. Sito reale con quote esistenti verificato a 1440/320 px; scorciatoia al pannello ancora funzionante da tastiera. Nessun login amministrativo o apertura di link esterni. Anteprime temporanee, non un salvataggio nell’editor autenticato.

## v1.6.34 — Tutti gli orari dell’Evento affiancati

- Accoglienza, Inizio e l’eventuale Fine sulla stessa riga, sia nel riepilogo iniziale sia nelle informazioni pratiche. Colonne automatiche per gli orari presenti, senza colonne vuote; rimosso il posizionamento forzato di Fine sotto gli altri due valori.
- Conservati distanza orizzontale 24 px, etichette da 14 px/500 e ore da 16 px/600 con numeri tabulari. Griglia esplicitamente isolata dalle colonne generiche della lista di dettagli. Skill `clean-code-engineer` applicata aggiornando soltanto lo stile del componente condiviso, senza duplicare markup o introdurre script.
- Tema/asset `1.6.34`; specifica Evento aggiornata. Plugin, Corsi, dati e contenuti invariati; nessuna scrittura nel database o commit automatico.
- Verifiche: lint PHP del file modificato, sintassi dei due script JS frontend e `git diff --check`; pagina, CSS versionato e font HTTP 200. Nove regressioni WordPress con meta simulati in memoria, comprese modalità e orari mancanti, tutte riuscite.
- Browser: evento con tre orari a 1440/768/390/320 px e con due orari a 1440/320 px. In entrambi i riepiloghi ore allineate, gap 24 px e font invariati; nessun overflow, immagine rotta o errore console nell’evento con tre orari. Review visiva desktop/mobile e scorciatoia di prenotazione da tastiera con focus sulla destinazione conservato. Nessun link esterno aperto né prenotazione inviata.

## v1.6.33 — Link Google Maps nell’Evento e plugin v1.3.4

- Aggiunto meta facoltativo `_fisar_event_maps_url`: stringa singola, non esposta in REST, autorizzazioni esistenti e sanitizzazione WordPress limitata a HTTP/HTTPS. Campo URL `Link Google Maps` nel box `Modalità e luogo`, nella sezione presenza/ibrido; salvataggio con nonce e capability esistenti. Link abbreviati supportati senza risoluzione remota o deduzione della posizione. I valori non stringa nei campi URL vengono scartati senza errori.
- Componente condiviso `event-map-link.php`: `Apri in Google Maps ↗` sotto il luogo nei riepiloghi iniziale e finale. Link nativo nella stessa scheda, freccia decorativa, stile testuale esistente da 15 px. Disponibile anche con il solo pin e negli eventi conclusi; nascosto se il campo è vuoto, non sicuro o l’evento è online.
- Nessuna mappa incorporata, API, chiave Google, nuovo script o dipendenza. Campi e template Corsi invariati. Nessuna posizione di prova salvata negli eventi, nessuna migrazione, login amministrativo o apertura di Google Maps.
- Tema/asset `1.6.33`, plugin `1.3.4`; README, specifiche CPT/Plugin/Evento e architettura aggiornati. Skill `clean-code-engineer` applicata riusando registrazione e salvataggio nativi e condividendo il markup dei due link. Nessun commit automatico.
- Verifiche: lint PHP, sintassi dei tre script JS e `git diff --check`; evento, CSS versionato e font HTTP 200. Dieci URL e input malformati, salvataggi intercettati senza persistenza, registrazione del campo, rendering del metabox e otto varianti del dettaglio (presenza, ibrido, online, assente, non sicuro, abbreviato, solo pin, concluso), tutti riusciti. Rieseguite nove regressioni del luogo, tredici varianti di iscrizione, dodici casi valuta, undici normalizzazioni WhatsApp e sei salvataggi dei contatti.
- Anteprima temporanea generata dal template WordPress con meta simulato e asset locali same-origin: desktop/tablet/mobile a 1440/768/390/320 px, due link corretti, font caricati, nessun overflow, immagine rotta, iframe o errore console; review visiva dei due riepiloghi e focus tastiera visibile senza seguire il link esterno. Controllato anche il sito reale con campo ancora vuoto. Server dell’anteprima arrestato a fine test; WordPress locale rimane disponibile.

## v1.6.32 — Iscrizione e informazioni essenziali dell’Evento

- Omettere la riga `Modalità` nelle informazioni pratiche per gli eventi in presenza; mantenerla per online e ibridi. Luogo, indirizzo, accesso alla piattaforma e fallback del riepilogo invariati, senza modificare i meta salvati.
- Etichette Accoglienza/Inizio/Fine del componente condiviso aumentate da 13 a 14 px in entrambi i riepiloghi. Peso 500, ore da 16 px/600, distanza di 24 px e allineamento accoglienza/inizio conservati.
- Nota flessibile resa più scura con `--color-ink` (`#282332`), nelle due posizioni; testo, dimensione 14 px e condizioni di visualizzazione invariati.
- Titolo finale semplificato da `Quote e prenotazioni` a `Iscrizione`; per eventi senza prenotazione `Partecipazione`, per eventi passati ancora `Evento concluso`. Nessuna modifica a quote, recapiti, canali, note, ID o scorciatoie.
- Tema e asset `1.6.32`, plugin `1.3.3` invariato. README, specifica Evento e tipografia aggiornati. Skill `clean-code-engineer` applicata mantenendo gli stessi componenti e selettori circoscritti agli Eventi. Nessuna dipendenza, script, scrittura nel database o commit automatico.
- Verifiche: lint PHP, sintassi dei due script JS e `git diff --check`; evento, CSS versionato e font HTTP 200. Nove casi di modalità/orari, tredici varianti di iscrizione con titolo verificato per stato, dodici casi valuta, undici normalizzazioni WhatsApp e sei salvataggi intercettati, tutti riusciti senza persistenza.
- Browser: evento giapponese a 1440/768/390/320 px e altre otto combinazioni su ibrido, partecipazione libera, concluso e Corso a 1440/320 px. Modalità ibrida conservata, nuovi titoli corretti, sottoetichette misurate a 14 px, note nel colore scuro e ore allineate. Nessun overflow, immagine rotta o errore console; review visiva desktop/mobile, focus della scorciatoia da tastiera ancora sul pannello. Nessun login o invio di prenotazioni.

## v1.6.31 — Scadenza esplicita e riepiloghi Evento coerenti

- `Prenotazioni entro` e data ora formano una frase continua con spazio esplicito, stesso peso 600 e dimensione (18 px nella hero, 22 px nel pannello finale). Ritorno a capo naturale quando lo spazio non basta, senza riduzione del font o clipping; calendario e `datetime` conservati.
- Nota flessibile chiarita nel plugin `1.3.3`: `Dopo tale termine sarà comunque possibile contattarci per iscriversi, ma non potremo garantire la disponibilità.` Il componente condiviso mantiene la nota dentro entrambi i box; API testuale e strutturata allineate, nessuna nuova regola di chiusura.
- Etichette principali del riepilogo iniziale aumentate da 13 a 16 px; etichette finali uniformate a 16 px e maiuscolo, peso 600 e tracking 0.05 em. Selettori limitati ai figli diretti e ai `dt`: nomi delle sedi, valori e sottoetichette Accoglienza/Inizio/Fine invariati.
- Tema/asset `1.6.31`, plugin `1.3.3`; specifiche Evento, tipografia, plugin e architettura aggiornate. Skill `clean-code-engineer` applicata riusando il componente della scadenza e mantenendo il copy funzionale nel plugin. Nessuna dipendenza, nuovo script, scrittura nel database o commit automatico.
- Verifiche: lint PHP, sintassi dei due script JS e `git diff --check`; HTTP 200 per evento, CSS versionato e font Poppins 600 effettivamente dichiarato nel CSS. Tredici varianti di prenotazione/template, dodici casi valuta, undici normalizzazioni WhatsApp e sei salvataggi intercettati senza persistenza, tutti riusciti con il nuovo testo atteso.
- Browser: sei larghezze 320–1440 px sull’evento giapponese e altre otto combinazioni su gratuito con prenotazione, partecipazione libera, concluso e Corso a 1440/320 px. Etichetta e data misurate alla stessa dimensione in entrambe le posizioni, riepiloghi a 16 px/maiuscolo e Corsi invariati. Nessun overflow, immagine rotta o errore console; review visiva desktop/mobile e ancora provata con tastiera, focus sul pannello. Nessun login o invio di prenotazioni.

## v1.6.30 — Gerarchia delle prenotazioni e plugin v1.3.2

- Scadenza con giorno della settimana, usando la localizzazione WordPress esistente. Nuovo componente di presentazione `event-deadline.php` condiviso: nota sul termine flessibile dentro il box sia nella hero sia nel pannello finale; nessuna nuova regola di chiusura e API testuale del plugin invariata.
- Scorciatoia `Come prenotare` raccolta nel box iniziale delle prenotazioni; fallback autonomo nella hero quando manca una scadenza valida. Conservati un solo pannello finale, focus della destinazione, partecipazione libera ed eventi conclusi.
- `Quota di partecipazione` più grande (20 px/600), quote ravvicinate con colonne allineate a sinistra e gap 12 px; condizioni di partecipazione in grassetto semantico. Nuova sezione `Come prenotare` con titolo da 20 px/600, intestazioni dei canali da 18 px/600 e recapiti selezionabili.
- Pulsanti Eventi `Prenota via WhatsApp` e `Prenota via mail`; il plugin applica le etichette soltanto al prefisso Evento, senza cambiare etichette Corsi, URL, validazione o altri canali.
- Per attivare il pulsante richiesto, completato il solo meta `_fisar_event_whatsapp` dell’evento locale ID 108 (`alla-scoperta-dei-vini-giapponesi`): `http://3357882629` → `+39 3357882629`, link generato `https://wa.me/393357882629`. Nessun altro recapito o contenuto modificato, nessun prefisso dedotto globalmente, nessuna migrazione o messaggio inviato.
- Tema e asset `1.6.30`, plugin `1.3.2`; README, specifica Evento, API del plugin e architettura aggiornati. Skill `clean-code-engineer` applicata mantenendo un componente condiviso e separando presentazione e regole funzionali. Nessuna dipendenza, nuovo script o commit automatico.
- Verifiche: lint PHP, sintassi dei due script JS, `git diff --check`; HTTP 200 per pagina, CSS versionato e font locale. Dodici casi valuta, tredici varianti di prenotazione/template (inclusi giorno, note dentro i box, posizione della scorciatoia, grassetto semantico e condizioni dei canali), undici normalizzazioni WhatsApp e sei salvataggi intercettati senza persistenza, tutti riusciti.
- Browser: evento giapponese a sei larghezze 320–1440 px e altre dodici combinazioni su evento gratuito con prenotazione, partecipazione libera, concluso e Corso a 1440/768/320 px. Nessun overflow, immagine rotta o errore console; Corsi invariati, URL dei pulsanti verificati senza aprire chat/email. Review visiva desktop/mobile e link interno provato con mouse e tastiera: focus sul pannello e Tab successivo su WhatsApp. Nessun login amministrativo.

## v1.6.29 — Unico riquadro Quote e prenotazioni

- Applicata la proposta approvata dopo il confronto UX/information architecture: rimosso il pannello laterale e conservato un unico riquadro finale dopo le informazioni pratiche, prima del Corso collegato. Corpo in una colonna centrale, massimo 48 rem, senza sticky o barre fisse. Contatti affiancati da 38 rem, impilati su mobile.
- Link nativo `Come prenotare ↓` nella hero verso `#event-registration`, con focus sulla destinazione e margine di scorrimento. Riquadro `Quote e prenotazioni`; per partecipazione libera, `Come partecipare`/`Quote e partecipazione`. Eventi conclusi: un solo blocco di stato in fondo, nessun invito a prenotare.
- Quote mostrate una sola volta nel riquadro (nella tabella per gli eventi conclusi); eliminata la duplicazione di quote e scadenza nella tabella immediatamente precedente. Scadenza ancora evidente in hero e riquadro, nota flessibile, recapiti copiabili, pulsanti e condizioni invariati.
- Su richiesta dell’utente, importi numerici senza valuta resi con spazio non separabile e `€`, per esempio `50 €`/`60 €`. Conservati valori già con valuta e testi editoriali come `€ 20 per accompagnatore`, senza euro duplicato o conversione dei dati. Formatter di presentazione nel tema; nessun intervento sul plugin, sui meta o sui contenuti salvati.
- Riusati i componenti esistenti, eliminando le varianti laterale/fondo non più necessarie. Tema e asset `1.6.29`, README, specifica Evento, architettura e assunzioni aggiornati. Nessun nuovo script, dipendenza, scrittura nel database o commit automatico.
- Verifiche: lint PHP, sintassi dei due script JS, `git diff --check`; pagina, CSS versionato e font locale HTTP 200. Dodici casi di valuta e tredici varianti di iscrizione con meta simulati in memoria, API/copy preservati e corpo integrale, escaping e ID univoci verificati. Rieseguite 18 regressioni di orari/luogo, 11 normalizzazioni WhatsApp e 6 salvataggi intercettati senza persistenza.
- Browser: evento giapponese su sei larghezze 320–1440 px; altre 15 combinazioni su eventi gratuito con prenotazione, ibrido, partecipazione libera, concluso e Corso a 1440/768/320 px. Un solo pannello, quote/recapiti leggibili, nessun overflow o immagine rotta e stili Corsi invariati. Review visiva desktop/mobile; link interno provato con mouse e tastiera, focus sulla destinazione e poi sul contatto, console senza errori. Supporto reduced-motion esistente conservato; nessun login o invio di prenotazioni.

## v1.6.28 — Iscrizioni Evento e plugin v1.3.1

- Riquadro d’iscrizione con scadenza in primo piano (calendario decorativo, bordo oro, data Poppins 600 da 22 px), quote e recapiti testuali selezionabili oltre ai pulsanti. Numero WhatsApp, email, telefono e altri canali provengono soltanto dai campi configurati per l’evento.
- Riquadro affiancato alla descrizione su desktop, prima del corpo su mobile, ripetuto dopo le informazioni pratiche e prima dell’eventuale corso collegato. Componenti condivisi per iscrizioni e quote, dati raccolti una volta e ID univoci. Scadenza evidenziata anche nei riepiloghi iniziale e finale. Gli eventi conclusi conservano un solo riquadro di stato, senza inviti a prenotare.
- Il plugin fornisce i dettagli strutturati della prenotazione, mantenendo la precedente API testuale e le regole su gratuità, partecipazione e termine flessibile. Nessuna chiusura automatica dedotta dalla scadenza, nessuna valuta aggiunta alle quote editoriali.
- WhatsApp accetta numeri o URL nei metabox condivisi Eventi/Corsi: testo preservato al salvataggio, chat generata solo con numero internazionale completo, numero estratto dai link diretti noti. Gruppi/canali mostrano l’URL; numeri nazionali, anche se precedentemente salvati come `http://numero`, sono leggibili senza link presunto. Corretto il collegamento malformato nella presentazione dell’evento giapponese; numero privo di prefisso lasciato invariato nel database, da completare editorialmente per avere il pulsante.
- Tema/asset `1.6.28`, plugin `1.3.1`; README, specifica Evento, API del plugin e architettura aggiornati. Nessuna dipendenza, migrazione, scrittura nel database o commit automatico.
- Verifiche: lint PHP, sintassi JS e `git diff --check`; HTTP 200 per pagina, CSS versionato e font locale. Test in memoria: 11 normalizzazioni WhatsApp, 6 salvataggi intercettati senza persistenza, 13 varianti di iscrizione (quote assenti/parziali/gratuità, scadenza assente/non valida/tassativa/flessibile, canali assenti, evento concluso, escaping, ID e corpo integrale), più 18 regressioni di orari e luogo.
- Browser: sei larghezze 320–1440 px per l’evento giapponese e dodici combinazioni di tre altri eventi/Corso a 1440/390/320 px. Nessun overflow, immagine rotta o ID duplicato; orari allineati, link diretti conservati nei dati validi, stili Corsi invariati. Review visiva desktop/mobile, focus da tastiera visibile e console senza errori. Nessun login amministrativo né invio di prenotazioni.

## Etichetta Orario nel dettaglio Evento — 5 ottobre 2026

- Sostituito `Orari` con `Orario` nel riepilogo iniziale e nelle informazioni pratiche, su richiesta dell’utente. Accoglienza, inizio, eventuale fine e layout invariati; versione `1.6.27` conservata, nessun dato o asset modificato.
- Verifiche: lint PHP, `git diff --check` e risposta HTTP locale riuscita con entrambe le nuove etichette presenti. Nessun commit automatico.

## v1.6.27

- Aumentato H4 del corpo Evento da 17 a 18 px (`1.125rem`), sia nel frontend sia nel CSS dell’editor Eventi. Conservati Poppins `500`, interlinea `1.35`, H3 da 20 px e H5–H6 da 17 px. Nessun intervento su contenuti, livelli salvati, Corsi o altri template.
- Tema e asset `1.6.27`, specifiche tipografiche aggiornate; nessun commit automatico.
- Verifiche: lint PHP, sintassi JS e `git diff --check`; HTTP 200 per evento, CSS principale versionato e CSS editor Eventi. Test WordPress in sola lettura del caricamento degli stili per tipo di editor, senza nuove scritture nel database. Browser a 1440/768/390/320 px: tre H4 reali misurati a 18 px, peso 500 e interlinea 24.3 px, nessun overflow o immagine rotta; review visiva desktop e console senza errori. Verifica nell’editor autenticato ancora subordinata all’autorizzazione di accesso già richiesta.

## v1.6.26

- Configurati stili nativi del canvas per gli editor Eventi e Corsi: font locali già presenti nel tema, larghezza di lettura `68ch`, margini dei titoli, elenchi e citazioni coerenti con il corpo delle pagine pubbliche. Nessun cambiamento alla toolbar, ai metabox o ai contenuti salvati.
- Eventi: scala desktop Poppins, corpo 17 px, H2 24 px, H3 20 px, H4–H6 17 px, heading 500 e grassetti 600. Corsi: mantenuto lo stile pubblico attuale, corpo Poppins 18 px e titoli Cormorant con H2 52 px/H3 32 px. Scale fisse nel canvas, senza `vw`, perché l’editor è più stretto del viewport pubblico; frontend responsive invariato.
- CSS aggiunti tramite `add_editor_style` durante `enqueue_block_editor_assets`, soltanto sugli schermi di modifica dei due CPT. Nessun nuovo script, plugin, font remoto o scrittura nel database. Tema `1.6.26`, README e specifiche aggiornati; nessun commit automatico.
- Verifiche completate: lint PHP, sintassi JS, `git diff --check`; HTTP 200 per Evento, Corso e tre CSS editoriali. Test WordPress in sola lettura: tre fogli di stile per ciascuno dei due editor, solo il precedente per News e pagine, nessuna aggiunta negli elenchi amministrativi. Controllate anche le registrazioni native e gli URL base dei CSS.
- Frontend: otto combinazioni di Evento/Corso e larghezze 1440/768/390/320 px; tipografia pubblica conservata, nessun CSS editoriale caricato, overflow o immagine rotta; console senza errori.
- Verifica visiva nell’editor reale ancora da completare: il controllo delle autorizzazioni ha bloccato il login con l’account demo e sono stati segnalati timeout iniziali. Richiesta all’utente l’autorizzazione specifica all’accesso al backend locale; nessun tentativo di aggirare il blocco e nessun contenuto modificato tramite editor.

## v1.6.25

- Aggiunta la via anche al riepilogo iniziale del dettaglio evento, tra il nome della sede in grassetto e la città/provincia. Conservati righe separate e stile v1.6.24, campi mancanti omessi e comportamento degli eventi online/ibridi.
- Nessuna modifica a dati, plugin, tabella finale o CSS; tema e asset `1.6.25`, specifica e architettura aggiornate. Nessun commit automatico.
- Verifiche: lint PHP, sintassi JS e `git diff --check`; HTTP 200 per evento e CSS versionato. Nove casi WordPress in sola lettura per via nel riepilogo, sede parziale o assente, online/ibrido, escaping e conservazione del corpo. Browser a 1440/768/390/320 px: via su riga dedicata, nessun overflow o immagine rotta, console senza errori e review visiva desktop.

## v1.6.24

- Migliorata la gerarchia del luogo nel dettaglio evento: nome della sede in grassetto semantico Poppins `600` da 16 px, città/provincia su una riga distinta nel riepilogo. Nelle informazioni pratiche, nome, via e città/provincia su righe separate con spazio di 4 px, senza concatenazioni.
- Testi lunghi liberi di andare a capo, dati mancanti omessi e fallback all’indirizzo conservato. `Online`, `Anche online`, orari ravvicinati, indirizzo completo e altri dati invariati. Il nome non eredita il maiuscolo e il colore delle etichette del riepilogo.
- Intervento circoscritto a markup e CSS del singolo evento; nessuna modifica al plugin, al database o agli altri template, nessuna dipendenza aggiunta. Tema e asset `1.6.24`, specifica aggiornata; nessun commit automatico.
- Verifiche: lint PHP, sintassi dei due script JS e `git diff --check`; HTTP 200 per pagina evento, CSS versionato e font 600. Diciotto casi WordPress in sola lettura con filtri in memoria: stati/modalità/orari conservati, luogo completo o parziale, dati assenti, fallback, escaping e corpo editoriale integrale.
- Browser desktop/tablet/mobile a 1440/1024/768/390/320 px: nome a peso 600, righe separate, orari ancora allineati e nessun overflow o immagine rotta. Review visiva del riepilogo desktop e dell’indirizzo completo desktop/mobile; console senza errori.

## v1.6.23

- Ravvicinati accoglienza e inizio nel riepilogo e nelle informazioni pratiche: colonne dimensionate sul testo, allineate a sinistra e distanti `1.5rem` (24 px), anziché espanse sull’intera larghezza disponibile. Conservati etichette, valori, allineamento delle ore e disposizione dell’eventuale fine sotto.
- Modifica circoscritta al CSS del dettaglio evento; componente, contenuti e plugin invariati. Tema e asset `1.6.23`, specifiche aggiornate; nessun commit automatico.
- Verifiche: lint PHP, sintassi dei due script JS e `git diff --check`; pagina evento e CSS versionato HTTP 200. Otto combinazioni browser su due eventi con due/tre orari, da 320 a 1440 px: gap effettivo 24 px nelle due viste, ore allineate, fine sotto e nessun overflow. Asset caricati, review visiva desktop e console senza errori.

## v1.6.22

- Nel dettaglio degli eventi futuri, sostituito `Prossimo evento` con `Evento in programma`: la dicitura non implica che sia il primo in ordine cronologico. Conservato `Evento concluso` per gli eventi passati.
- Accoglienza e inizio affiancati nello stesso gruppo `Orari`, sia nel riepilogo iniziale sia nelle informazioni pratiche, anche su mobile: etichette sopra e ore sulla stessa riga, a peso 600 e con numeri tabulari. L’eventuale fine resta sotto quando ci sono tre orari; dati facoltativi mancanti omessi. Un componente condiviso del tema evita divergenze fra le due viste.
- Il riepilogo iniziale mostra il luogo anziché `In presenza`. Per gli eventi online mostra `Modalità: Online`; per quelli ibridi mostra il luogo con `Anche online`, oppure la modalità se manca la sede. Indirizzo completo e modalità restano disponibili nelle informazioni pratiche.
- Nessuna modifica a campi, validazione, query, contenuti editoriali, iscrizioni o relazioni del plugin, nessuna scrittura nel database e nessuna nuova dipendenza. Tema e asset `1.6.22`, specifiche aggiornate; nessun commit automatico.
- Verifiche: lint PHP, sintassi dei due script JS e `git diff --check`; HTTP 200 per home, archivio, dettagli, CSS/JS versionati e font. Test WordPress in sola lettura su sei eventi per conservazione di contenuti, iscrizioni, stati e corsi collegati; nove casi con meta simulati in memoria per presenza, online, ibrido, orari incompleti, sede mancante ed evento passato.
- Browser: 24 combinazioni di quattro eventi reali e sei larghezze da 320 a 1440 px, con accoglienza e inizio allineati in entrambe le viste, nessun overflow o immagine rotta. Review visiva desktop/mobile e delle informazioni pratiche; console senza errori.

## v1.6.21

- Prova richiesta dall’utente: mese per esteso nelle date delle card Eventi di home e archivio, abbreviato soltanto quando non entra su una riga. Giorno della settimana ancora breve; icona, font e `datetime` conservati, data completa per screen reader invariata.
- Formatter del tema esteso con parametro opzionale per il mese, mantenendo il comportamento precedente come default. La card rende la forma estesa e fornisce quella breve al nuovo script nativo `event-dates.js`, caricato solo nelle due viste interessate. Misurazione del testo nello spazio reale rimasto dopo calendario e gap, aggiornata dopo font e ridimensionamento con `ResizeObserver`/resize e callback coalescenti; ripristino della forma estesa allargando la card.
- Senza JavaScript rimane leggibile la data estesa su più righe. Se a forte ingrandimento neppure la forma breve entra, è consentito il ritorno a capo anche con JS, senza clipping o riduzione del font. Nessuna dipendenza, logica di dominio o scrittura nel database; tema `1.6.21`, specifiche e README aggiornati. Nessun commit automatico.
- Verifiche: lint PHP, sintassi di entrambi gli script e `git diff --check`; HTTP 200 per home, archivio, singolo evento, CSS/JS versionati e font. Test WordPress in sola lettura: sei eventi, contenuti integrali, abstract, iscrizioni, stati e corsi collegati conservati; formattazione di tutti i mesi e fallback vuoto/non valido verificati.
- Browser: 14 varianti delle pagine reali fra 320 e 1440 px, inclusi restringimento e riallargamento; 20 varianti di fixture native per 12 mesi più `MER 30 SETTEMBRE 2026`, home/archivio, script assente e testo al 200%. Nessun overflow, clipping o errore console, nomi accessibili e date complete conservati; abbreviazione e ripristino corretti. Review visiva desktop; singolo evento e Carta non caricano il nuovo script. Fixture generate con filtri in memoria, nessun dato editoriale modificato; server temporaneo arrestato a fine test.

## Introduzione aperta dell’archivio Eventi — 5 ottobre 2026

- Applicata la frase scelta dall’utente: `Degustazioni, visite e incontri per scoprire e condividere.` Sostituisce il riferimento limitato al vino, evitando la ripetizione fra conoscere e scoprire.
- Conservati stile, padding, dimensioni dei font, card e query; modifica circoscritta al testo dell’archivio. Specifica aggiornata, tema `1.6.20` invariato; nessuna modifica al database e nessun commit automatico.
- Verifiche: lint PHP, sintassi JS e `git diff --check`; HTTP 200 per archivio, CSS e font. Browser a 1440/768/390/320 px: testo su una riga su desktop/tablet e due su mobile, ritorno a capo naturale, sei card conservate e nessun overflow, immagine rotta o errore console. Review visiva desktop/mobile.

## Introduzione breve dell’archivio Eventi — 5 ottobre 2026

- Accorciata l’introduzione della hero in `Degustazioni, visite in cantina e incontri per conoscere il vino.`, per avere una riga su desktop. Su mobile il testo va naturalmente a capo, senza `nowrap`, clipping o riduzione della tipografia.
- Registrata nella specifica l’approvazione della fascia scura. Modifica limitata al testo del template dell’archivio; stile, padding, card, nomi accessibili, query e altri contenuti invariati. Nessuna modifica al database o nuova dipendenza; tema `1.6.20` invariato e nessun commit automatico.
- Verifiche: lint PHP, sintassi JS e `git diff --check`; HTTP 200 per archivio, CSS e font. Browser a 1440/1024/768/390/320 px: una riga alle prime tre larghezze, due su mobile; font 16–18 px invariato, sei card conservate, nessun overflow, immagine rotta o errore console. Review visiva desktop/mobile.

## v1.6.20

- Su richiesta dell’utente, applicata una prova scura alla sola hero dell’archivio Eventi: fondo bordeaux scuro uniforme del brand, titolo bianco, introduzione chiara e linea oro; nessuna fotografia o nuova dicitura. Conservati padding compatto 32–48 px, Poppins, testo e griglia.
- Selettori circoscritti all’archivio: singolo evento ancora chiaro, Carta fotografica e resto del sito invariati. Nessuna modifica a plugin, dati o funzioni e nessuna nuova dipendenza. Tema e asset `1.6.20`, specifica aggiornata; nessun commit automatico.
- Verifiche: lint PHP, sintassi JS e `git diff --check`; HTTP 200 per archivio, singolo evento, Carta, CSS versionato e font. Browser a 1440/1024/768/390/320 px senza overflow o immagini rotte: sei card e padding invariati, background senza immagine. Contrasti misurati sui colori renderizzati: titolo 16.28:1, introduzione 12.45:1 e linea oro 6.93:1. Review visiva desktop/mobile, singolo evento conservato chiaro e console senza errori.

## Intestazione essenziale dell’archivio Eventi — 5 ottobre 2026

- Rimossa la dicitura `FISAR · Delegazione Castelli di Jesi` sopra il titolo dell’archivio Eventi, su richiesta dell’utente; l’identità resta nel logo/header e nella hero della Carta dei Valori, dove chiarisce l’ambito locale del documento.
- Conservati titolo, introduzione, padding 32–48 px, card, nomi accessibili e query. Modifica circoscritta al template dell’archivio; nessun intervento su plugin, database, CSS o altri template. Tema `1.6.19` invariato, specifica aggiornata; nessun commit automatico.
- Verifiche: lint PHP, sintassi JS e `git diff --check`; HTTP 200 per Eventi, Carta, CSS e font. Browser a 1440/768/390/320 px senza overflow o immagini rotte, sei card e padding conservati, dicitura assente dalla hero Eventi e presente nella Carta; sette capitoli della Carta e console senza errori. Review visiva desktop/mobile.

## v1.6.19

- Ridotto soltanto il padding verticale della hero dell’archivio Eventi da 40–64 px a 32–48 px per lato, con `clamp(2rem, 4vw, 3rem)`. Titolo, testo, accento oro, card e spaziature delle sezioni invariati; la hero del singolo evento conserva i precedenti valori.
- Aggiornati versione degli asset del tema e specifica dell’archivio. Nessuna modifica a plugin, database o contenuti; nessuna nuova dipendenza e nessun commit automatico.
- Verifiche: lint PHP, sintassi JS e `git diff --check`; HTTP 200 per archivio, singolo evento, CSS versionato e font. Browser a 1440/1024/768/390/320 px: padding corretto, sei card conservate, nessun overflow o immagine rotta; review visiva desktop/mobile e console senza errori. Confermato padding di 64 px invariato nel singolo evento su desktop.

## v1.6.18

- Riallineati archivio e dettaglio Eventi allo stile approvato della home e della Carta: heading Poppins Medium, intestazioni più compatte, fondo chiaro caldo, linee oro, bordi sottili e ombre leggere. Identità della Delegazione esplicita nell’intestazione dell’archivio.
- Locandine su bianco, sempre intere con `contain`; cornice quadrata anche nel dettaglio. Archivio con data compatta e icona calendario, data estesa per screen reader, abstract conservati e CTA con titolo completo nel nome accessibile. Tutti i cinque eventi futuri e quello concluso rimangono elencati.
- Corpo del dettaglio e heading editoriali resi coerenti con la Carta, senza cambiare testi o livelli salvati. Informazioni pratiche più leggibili su mobile; riquadro iscrizioni con bordo oro e fondo caldo, in normale flusso senza sticky. Conservati quote, canali, scadenze, posti limitati, note, stati e corsi collegati.
- Intervento di sola presentazione nel tema, con selettori circoscritti a `events-archive` e `single-event` e contesto `archive` nel componente card condiviso. Nessuna modifica al plugin/database o nuova dipendenza; tema `1.6.18`, specifiche e documentazione aggiornate.
- Verifiche: lint PHP, sintassi JS, `git diff --check`; HTTP 200 per pagine, CSS/JS versionati, font e locandina reale. Test WordPress in sola lettura sui sei eventi: contenuto nativo integrale, abstract dell’archivio, iscrizioni, condizioni funzionali e corsi collegati conservati. 36 controlli browser a 1440/1024/832/768/390/320 px senza overflow, immagini rotte o ID duplicati; H1 unico, heading Poppins, locandine intere, nomi accessibili e focus tastiera visibile. Review visiva desktop/tablet/mobile e caricamento di tutte le sei locandine; home e Carta controllate senza regressioni, console senza errori.
- Segnalato all’utente un URL WhatsApp editoriale preesistente non valido nell’evento sui vini giapponesi; dato lasciato invariato perché fuori dal restyling richiesto. Nessun invio ai canali esterni e nessun commit automatico.

## v1.6.17

- Reso coerente il link all’archivio Eventi in homepage: sempre `Tutti gli eventi`, con suffisso `(N)` soltanto quando il totale supera quattro. Posizione indipendente dal numero di eventi: accanto al titolo su desktop, sotto le card su mobile. Un solo collegamento visibile per ogni larghezza.
- Rimossi condizione e stile dedicati alla precedente CTA lunga sotto la griglia; conservati massimo quattro card, griglia, locandine, nomi accessibili, ordine cronologico e query del plugin. Nessuna modifica a dati editoriali o altre sezioni; tema `1.6.17` e documentazione aggiornata.
- Verifiche: lint PHP, sintassi JS e `git diff --check`; HTTP 200 per home, archivio, CSS e font. Test WordPress di rendering da zero a cinque eventi e 18 varianti browser a 1440/768/320 px: etichetta, totale, posizione e unico link visibile corretti, senza overflow. Homepage reale controllata anche a 1024/832/390 px, link all’archivio funzionante e console senza errori; review visiva desktop. Fixture temporanee senza scritture nel database.

## v1.6.16

- Homepage estesa a un massimo di quattro eventi futuri, in ordine cronologico, riutilizzando la query del plugin e il suo `found_posts`. Oltre quattro, collegamento esplicito `Vedi tutti i N eventi in programma` sotto la griglia su desktop e mobile; fino a quattro conservati testo e posizionamento precedenti del link all’archivio.
- Griglia della sola home mantenuta a due colonne da `38rem`, anche alle larghezze intermedie precedentemente influenzate dalle tre colonne degli archivi. Una colonna su mobile; locandine quadrate, dimensioni, fondo bianco, `contain` e nomi accessibili completi invariati. Messaggio esplicito quando non ci sono eventi futuri.
- Corsi, resto della homepage, archivi, pagine interne, dati editoriali e plugin invariati. Nessuna nuova dipendenza o asset; tema `1.6.16` e documentazione aggiornata.
- Verifiche: lint PHP, sintassi JS e `git diff --check`; test WordPress di rendering per 0–6 eventi, numero di card, ordine e identità, totale nei link e CTA accessibili. Varianti browser a 1440/768/320 px senza overflow; homepage reale controllata a 1440/1024/832/768/390/320 px. Review visiva desktop/mobile, test HTTP e caricamento degli asset; nessun errore console. Fixture e filtri temporanei senza modifiche al database, nessun invio a Mailchimp.

## Affinamento della nota — 5 ottobre 2026

- Riformulata la nota della Carta: `Nota bene: questo documento esprime i principi e i valori che guidano la nostra Delegazione Castelli di Jesi e non rappresenta necessariamente quelli della FISAR nazionale.` Incipit e, su successive richieste, `Delegazione Castelli di Jesi` e `non rappresenta necessariamente quelli della FISAR nazionale.` in grassetto semantico. Non aggiunta la sottolineatura, per non richiamare i link; chiarito l’ambito locale senza attribuire i principi alla nazionale o suggerire contrapposizioni.
- Aggiornati il primo blocco nativo del seed e soltanto la nota della pagina locale ID 6, con revisione WordPress preventiva e controllo che il resto del corpo fosse identico. Conservati riquadro, icona, sette capitoli, 16 grassetti originali e tutte le precedenti modifiche; nessun reimport generale o variazione del tema `1.6.15`.
- Verifiche: integrità del corpo e corrispondenza fra seed e contenuto salvato; HTTP 200 e `git diff --check`; controllo della sintassi PHP/JS. Test browser a 1440/768/390/320 px con un’unica nota, incipit a peso `600`, sette capitoli, nessun overflow, immagine rotta o errore console; review visiva desktop/mobile.

## v1.6.15

- Spostata la nota sull’ambito locale della Carta fuori dalla hero, in un riquadro sotto l’immagine e prima del payoff, allineato alla colonna di lettura. Fondo chiaro caldo, bordo oro sottile con lato sinistro più marcato, testo Poppins da 16 px e spaziatura responsive.
- Aggiunta un’icona SVG `info` al sistema del tema, decorativa e non focalizzabile; riquadro `aside` con nome accessibile `Nota sulla Carta dei Valori`, senza semantica di allarme o live region. Rimossi parametro e stili della precedente nota nella hero.
- Testo, blocchi nativi, seed del plugin e database invariati: la nota resta modificabile nell’editor ed è mostrata una sola volta. Payoff, sette capitoli, 16 grassetti, immagine, URL e navigazione conservati; nessuna nuova dipendenza. Tema `1.6.15` e documentazione aggiornata.
- Verifiche: lint PHP, sintassi JS e `git diff --check`; HTTP 200 per Carta, home, Contatti, CSS versionato, foto e font. Test WordPress confermano contenuto identico al seed e fallback conservativi. Browser a 1440/1024/768/390/320 px: nota sotto la hero e prima del payoff, sette capitoli e icone, unico H1, nessun overflow, elemento fuori dal viewport, ID duplicato, immagine rotta o errore console; review visiva desktop/mobile.

## v1.6.14

- Reso esplicito l’ambito locale della Carta: titolo nativo `Carta dei Valori della nostra Delegazione`, identificazione `FISAR · Delegazione Castelli di Jesi` e nota “Il documento si riferisce alla Delegazione Castelli di Jesi, non alla FISAR nazionale.” URL e voce di menu breve invariati.
- Nota conservata come primo blocco Paragrafo `values-scope-note`, modificabile nell’editor e separata dal tema per mostrarla una sola volta nella hero. Payoff originale riportato nel corpo sopra `Chi siamo`; frase sui principi ingrandita a Poppins `500` da 18–24 px e nota a 15 px. Overlay adattato per la leggibilità del titolo più lungo.
- Sostituito il fallback alla foto conviviale della home con il paesaggio di vigneti già incluso nel tema; resta disponibile l’immagine in evidenza nativa della Carta. Nessun nuovo asset o dipendenza, homepage e altre pagine invariate.
- Allineati titolo e nota nel seed del plugin, senza incrementare la versione demo o reimportare gli altri dati. Aggiornata soltanto la pagina locale ID 6, con revisione WordPress preventiva; testo originale del documento byte-identico alla versione precedente, esclusa la nota aggiunta. Tema `1.6.14`; aggiornata la documentazione.
- Verifiche: lint PHP, sintassi JS e `git diff --check`; HTTP 200 per Carta, home, Contatti e asset. Test WordPress per contenuto, fallback conservativo e integrità del documento (sette capitoli e 16 grassetti). Browser a 1440/1024/768/390/320 px: payoff soltanto nel corpo, nota soltanto nella hero, immagine corretta, sette icone, nessun overflow, testo fuori dal viewport, ID duplicato, immagine rotta o errore console; review visiva desktop/mobile.

## v1.6.13

- Arricchita la sola hero della Carta dei Valori con fotografia conviviale già disponibile nella homepage, overlay scuro, nome FISAR oro chiaro, titolo Poppins bianco e linea oro. Precedenza all’immagine in evidenza della Carta, fallback alla home e infine al paesaggio locale; immagine decorativa, priorità alta e `srcset` WordPress nativo.
- Spostato il payoff originale nella hero, in Cormorant `600` da 22–28 px, raggruppando le due frasi senza impedire il wrapping su mobile. Mantenuto l’estratto editoriale sotto il payoff e l’allineamento con la colonna di lettura; overlay più uniforme sotto `48rem` per il contrasto.
- Introdotto il componente `values-hero.php` e una separazione conservativa dei blocchi per mostrare il primo Paragrafo `lead` soltanto nella hero. Nessun cambiamento al database, al seed o al testo: tutti i sette capitoli e i 16 grassetti restano presenti; se l’apertura non corrisponde, il corpo non viene separato. Primo capitolo senza separatore o spazio aggiuntivo rimasto dal payoff spostato.
- Homepage, altri template, contenuti, icone e impaginazione dei capitoli invariati. Nessun nuovo asset, font, dipendenza o servizio esterno. Tema `1.6.13`; aggiornati specifica della Carta, tipografia, architettura, assunzioni e README.
- Verifiche: lint PHP di template, componente e funzioni; sintassi JS e `git diff --check`; HTTP 200 per Carta, foto e CSS versionato. Test WordPress confermano contenuto salvato invariato, testo preservato dalla separazione, nessuna duplicazione del payoff, grassetti e fallback conservati. Browser a 1440/1024/768/390/320 px: sette capitoli e icone, unico H1, un solo payoff nella pagina, nessun overflow, testo fuori dal viewport, ID duplicato, immagine rotta o errore console; review visiva desktop/mobile.

## v1.6.12

- Riallineato lo stile della pagina completa Carta dei Valori alla homepage, su richiesta dell’utente: H1 e sette H2 in Poppins Medium, rispettivamente 32–44 px e 20–24 px, al posto dei grandi titoli editoriali serif. Cormorant conservato per il payoff.
- Intestazione più compatta, colonna di lettura comune da massimo `50rem`, separatori sottili e spaziature regolari fra i capitoli. Icone oro da 28 px allineate ai titoli; paragrafi rientrati nella colonna del testo da `48rem`, a tutta larghezza su mobile. Corpo 16–17 px e grassetti `600`, senza alterare l’enfasi semantica.
- Modifica di sola presentazione, circoscritta alla classe `values-page` nel template standard: contenuto integrale, numerazione, grassetti, ancore, icone, homepage e altre pagine invariati. Nessuna modifica al plugin o al database e nessun nuovo asset o dipendenza. Tema `1.6.12`; aggiornati specifica pagina, tipografia, assunzioni e README.
- Verifiche: lint PHP di template e funzioni, sintassi JS e `git diff --check`; HTTP 200 per Carta, homepage, Contatti, CSS versionato e font Medium. Confronto del contenuto salvato con il seed conferma testo e 16 grassetti invariati. Review visiva completa e mobile; controlli a 1440/1024/768/390/320 px senza overflow, testi fuori dal viewport, ID duplicati, immagini rotte o errori console. Confermati sette capitoli, sette icone e unico H1; skip link da tastiera con focus visibile e destinazione corretta.

## v1.6.11

- Sostituita la versione abbreviata della pagina Carta dei Valori con il testo integrale della bozza Word V2 fornita dall’utente: payoff, sette punti numerati, titoli originali e tutti i 16 grassetti. Conservati anche gli approfondimenti su Slow Food/Slow Wine e i passaggi omessi dal vecchio seed; homepage e documento sorgente invariati.
- Riutilizzate le sei icone SVG della home per i punti 2–7; aggiunto il cuore già disponibile nel tema al punto `Chi siamo`. Icone oro da 28 px, decorative e non focalizzabili; impaginazione editoriale responsive e tipografia delle pagine interne conservate.
- Contenuto salvato in blocchi WordPress nativi Paragrafo/Titolo, modificabili dal backend. Il tema aggiunge gli SVG solo al rendering dei sette H2 identificati dalle ancore, nella sola pagina Carta e nel loop principale; nessun SVG nel database e nessuna dipendenza aggiunta.
- Allineato il seed del plugin tramite `content/carta-dei-valori.html`. Aggiornata soltanto la pagina locale ID 6, mantenendo il testo precedente nelle revisioni WordPress; nessun reimport generale o incremento della versione demo che sovrascriva i contenuti all’avvio. Tema `1.6.11`, specifica della pagina, architettura, assunzioni e README aggiornati.
- Verifiche: confronto automatico del testo e dei grassetti con il DOCX; contenuto nel database identico al seed e sette blocchi Titolo. Test WordPress confermano il rendering delle icone e l’assenza di decorazioni su H3, ancore sconosciute e altre pagine. Lint PHP dei file modificati, sintassi JS e `git diff --check`; HTTP 200 per pagina, home e CSS versionato. Controllo visivo dei sette capitoli e responsive a 1440/1024/768/390/320 px senza overflow, ID duplicati, immagini rotte o errori console.

## v1.6.10

- Resi esplicitamente esemplificativi i riferimenti alle altre bevande nel punto `Curiosità e apertura`, introducendoli con `come`.
- Precisata su richiesta dell’utente la dicitura `Consiglio Direttivo` nel punto `Ognuno può contribuire`.
- Applicato il copy approvato per la sintesi Carta dei Valori in homepage: passione, conoscenza e condivisione nell’apertura; corsi ed eventi per coltivare competenza e professionalità in un ambiente informale; approccio basato su conoscenza e consapevolezza; apertura anche a chi non ha mai frequentato corsi da sommelier; vino come punto di partenza verso cibo e altre bevande, incluse tè e caffè.
- Titolo abbreviato in `Informalità`; testo finale `Seguiamo le regole del servizio e della degustazione, senza eccessivi formalismi.`, evitando ripetizioni con l’introduzione. Conservati icone, layout, CTA e pagina completa della Carta; nessuna modifica al plugin o ai dati WordPress.
- Aggiornata la specifica homepage; tema `1.6.10`.
- Verifiche: lint PHP di template e funzioni, sintassi JS e `git diff --check`; HTTP 200 di homepage, Carta completa e CSS versionato. Controllo visivo desktop/mobile e responsive a 1440/1024/768/390/320 px senza overflow o testi tagliati; sei icone decorative conservate, console senza errori.

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
