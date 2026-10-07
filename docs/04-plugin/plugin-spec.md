# Plugin Spec
Il plugin contiene dati e logiche indipendenti dal tema.

## CPT Eventi

### Visibilità delle informazioni online — plugin v1.3.12

- Meta booleano singolo privato `_fisar_event_online_access_public`, default `false`, non esposto in REST. Sanitizzazione: solo `true`, `1`, `"1"` attivano l’opzione; input malformati restano falsi. Nessuna migrazione: assenza del dato nasconde piattaforma e link anche negli eventi esistenti.
- Nel metabox Modalità e luogo, per online/ibrido: checkbox `Mostra le informazioni per partecipare online` sotto Piattaforma e Link online. I due campi restano compilabili anche quando la casella non è selezionata. Marker `_fisar_event_online_access_present`: solo con metabox presente viene salvato il nuovo booleano, evitando azzeramenti da editor precedenti; checkbox assente equivale a falso. Nonce/capability/autosave/revision esistenti.
- API `fisar_cdj_get_event_online_access()` restituisce `{platform, url}` con stringhe vuote se non abilitato o modalità diversa da online/ibrido. Se abilitato restituisce la piattaforma e il link HTTP/HTTPS; negli eventi conclusi omette comunque l’URL. Regola nel plugin, markup nel tema. Nessuna modifica ai contatti di prenotazione, al luogo fisico, alle scadenze, agli stati sold-out o ai Corsi.
- Scelta editoriale manuale, non accesso autenticato, invio agli iscritti o pubblicazione temporizzata. L’URL nascosto non viene incluso nel markup pubblico del template; eventuali link inseriti nel corpo/estratto editoriale restano pubblici e non sono rimossi automaticamente.

### Disponibilità e lista d’attesa — plugin v1.3.11

- Meta privato singolo stringa `_fisar_event_booking_status`, valori `available`, `sold_out`, `waitlist`; assenza o valore non valido equivale ad `available`. Enum unico invece di due booleani contraddittori, senza migrare i contenuti esistenti. Sanitizzatore condiviso tra registrazione meta, lettura e salvataggio. Nel metabox Iscrizioni compare `Disponibilità dell’evento` con tre opzioni, solo con Iscrizione richiesta attivo. Salvataggio con protezioni esistenti e soltanto se il nuovo campo è presente nel POST; editor precedenti non azzerano lo stato.
- `fisar_cdj_get_event_booking_status()` restituisce lo stato effettivo: un termine tassativo valido superato prevale ed espone `closed`; senza prenotazione richiesta le nuove impostazioni non si applicano. Il controllo della sola scadenza è `fisar_cdj_has_event_registration_deadline_passed()`. `fisar_cdj_is_event_registration_closed()` resta compatibile e restituisce vero anche per `sold_out` senza lista d’attesa.
- Dettagli strutturati estesi con `status`, `status_notice`, `waiting_list`, `availability_notice`. Sold-out senza lista: niente canali. Lista d’attesa: stessi contatti, ordine, URL e nomi accessibili, soltanto etichette dedicate. Nessun messaggio precompilato modificato, contatto inventato o lista persistita; richiesta gestita tramite i canali già configurati. Senza contatti non vengono creati pulsanti.
- Stati sold-out/lista d’attesa sopprimono copy della prenotazione ordinaria, nota flessibile e posti limitati. Quote conservate; il tema mantiene prioritario Evento concluso. API testuale aggiornata, Corsi invariati. Questa revisione estende lo stato chiuso v1.3.10 senza eliminare la scadenza tassativa.

Revisione plugin `1.3.10`: `fisar_cdj_is_event_registration_closed()` restituisce vero solo con iscrizione richiesta, termine `strict`, data canonica `YYYY-MM-DD` valida e precedente a `fisar_cdj_today()`. Data inclusa e fuso WordPress; giorni impossibili, date relative e valori assenti non chiudono. `fisar_cdj_get_event_registration_details()` espone `closed` e `closed_notice`, sopprimendo copy operativo e posti limitati a chiusura avvenuta. API testuale restituisce `Iscrizioni chiuse`; API dei canali Eventi restituisce un elenco vuoto. Nessuna modifica ai meta, migrazione, cron o regola dei Corsi. Questa revisione prevale sulla precedente assenza di chiusura automatica per gli Eventi, senza introdurre un flag manuale o inferire disponibilità dai posti limitati.

Base: titolo, editor, featured image.
Campi: data evento; ora accoglienza/inizio/fine; modalità (presenza/online/ibrido); sede, indirizzo, città, provincia, link Google Maps facoltativo; piattaforma/link online; partecipazione (aperto a tutti/solo soci/soci e accompagnatori); gratuito; quota soci/non soci; iscrizione richiesta; WhatsApp, email, telefono, modulo online, altro canale, info aggiuntive; deadline; chiusura tassativa/flessibile; **Mostra avviso posti limitati**; Corso collegato.

Dal plugin `1.3.4`, `_fisar_event_maps_url` è un meta stringa singolo, non esposto in REST, registrato con le stesse autorizzazioni degli altri campi. Campo URL `Link Google Maps` nel box `Modalità e luogo`, dentro la sezione esistente visibile per presenza/ibrido, non obbligatorio. Riusa salvataggio con nonce/capability e sanitizzazione WordPress; il callback del nuovo meta consente solo HTTP/HTTPS e scarta dati non stringa. Accetta anche link abbreviati condivisi da Google Maps; nessuna risoluzione remota, geocodifica, generazione dall’indirizzo o verifica della destinazione. Nessun nuovo campo Corsi, nessuna migrazione o valorizzazione automatica degli eventi esistenti.

Non memorizzare il numero di posti. Evento concluso se `data_evento < oggi`. Nessuna tassonomia Tipologia evento nella V1.

Dal plugin `1.3.7`, `fisar_cdj_get_event_registration_details()` espone `limited_seats_notice`: `Ti consigliamo di prenotare prima che esauriscano.` se il meta `Mostra avviso posti limitati` è attivo, altrimenti stringa vuota. Il tema presenta l’avviso con `Posti limitati.` nei due box solo per eventi non conclusi. `lines` e l’API testuale precedente restano invariati. Nessun conteggio, disponibilità in tempo reale o chiusura automatica.

### Quote personalizzabili — plugin v1.3.5

- Affinamento editoriale del 6 ottobre 2026: i campi Quota soci/Quota non soci condividono l’istruzione `Inserisci un importo senza valuta, per esempio “25”: il simbolo € verrà aggiunto automaticamente. Oppure un testo breve, come “Da 25 €” o “Offerta libera”.` Nessun cambiamento alla formattazione o al salvataggio.

- Quote Soci/Non soci esistenti conservate. Nuovo meta singolo privato `_fisar_event_fee_options` di tipo array, con righe ordinate `{label, amount, note}`; etichetta e importo sono testo breve, nota è testo semplice multilinea facoltativo. Nessun campo fisso “senza vini”. `_fisar_event_fee_note` è una nota generale in testo semplice multilinea.
- `fisar_cdj_sanitize_event_fee_options()` sanitizza le righe, scarta input malformati e righe senza etichetta o importo, conserva `0`, ordine e importi editoriali senza calcoli. La stessa funzione è usata nella registrazione meta, nel salvataggio e nella lettura. Autorizzazioni, nonce e capability esistenti; nuovi campi salvati soltanto se il metabox è presente nel POST, così un editor aperto prima dell’aggiornamento non li azzera.
- Metabox `Partecipazione e costi`: Soci/Non soci, nota generale e `Altre opzioni di partecipazione`, con `Aggiungi quota`/`Rimuovi quota`, label associate, fieldset, focus esplicito e feedback per screen reader. Indici nuovi univoci anche dopo rimozioni. Senza JS: una riga vuota disponibile a ogni caricamento, eliminazione svuotando i due campi principali. Righe incomplete non persistite.
- `fisar_cdj_get_event_fees()` espone `free`, `member`, `nonmember`, `options`, `note` e `has_fees`. La gratuità nasconde tutte le quote e le loro note, senza cancellare i dati salvati. Una nota generale da sola è visualizzabile; una quota unica può usare soltanto le righe personalizzate. Nessuna distinzione alternativa/supplemento dedotta dal prezzo: va esplicitata editorialmente.
- Nessuna migrazione, valorizzazione demo o nuova dipendenza. Corsi e loro quota editoriale invariati. La valuta continua a essere formattata dal tema, senza alterare i valori memorizzati.

Copy aperto a tutti: “La partecipazione è aperta a tutti, anche a chi non è socio FISAR.”
Gratuito + prenotazione: “INGRESSO GRATUITO, PRENOTAZIONE OBBLIGATORIA”.
Gratuito libero: “La partecipazione è libera, non è richiesta la prenotazione.” Dal plugin `1.3.6`, `fisar_cdj_get_event_registration_details()` espone anche `open_participation_notice`: questo stesso messaggio per eventi gratuiti senza prenotazione, stringa vuota negli altri casi. `lines` e l’API testuale precedente rimangono invariati; il tema può distinguere semanticamente l’avviso senza duplicare copy o condizioni.
Deadline flessibile (testo chiarito dal plugin `1.3.3`): “Dopo tale termine sarà comunque possibile contattarci per iscriversi, ma non potremo garantire la disponibilità.”

Dal plugin `1.3.1`, `fisar_cdj_get_event_registration_details()` espone copy, scadenza grezza/localizzata e nota flessibile separatamente. `fisar_cdj_get_event_registration_copy()` conserva l’API testuale precedente per i dati validi. Una data assente o non interpretabile non genera una scadenza fittizia; nessuna chiusura automatica derivata dal termine.

L’API condivisa dei canali conserva `label`, `value`, `url` e aggiunge `reference_label`/`reference` per mostrare recapiti leggibili. WhatsApp accetta un numero come testo o un URL: con `+` o `00` e numero internazionale completo si genera la chat `wa.me`; senza prefisso il numero è visibile ma non cliccabile. Un link diretto `wa.me` o `/send?phone=…` mostra il numero ricavabile, mantenendo l’URL e l’eventuale messaggio; gruppi/canali mantengono il link. Nessun prefisso dedotto e nessuna conversione automatica dei dati salvati. Campo e salvataggio testuale sono condivisi con i Corsi; nessun nuovo meta.

Dal plugin `1.3.2`, le etichette dei canali Eventi sono `Prenota via WhatsApp` e `Prenota via mail`; quelle dei Corsi restano `Prenota su WhatsApp` e `Scrivi una email`. URL, riferimenti, validazione e campi condivisi invariati. La data con giorno della settimana è una variante di presentazione del tema, senza cambiare `deadline_label` o l’API testuale del plugin.

### Contatti WhatsApp multipli degli Eventi — plugin v1.3.8

- Revisione plugin v1.3.9: UI Eventi con solo `Numero WhatsApp` (`tel`, tastiera telefonica, esempio `335 1234567`), con o senza `+39`. `fisar_cdj_get_whatsapp_contact()` accetta un codice paese di default facoltativo; soltanto l’API dei canali Eventi passa `39` per generare il link dai numeri nazionali validi. Prefissi internazionali espliciti preservati, incluso `0039`; i fissi conservano lo zero iniziale. Valore memorizzato e riferimento non modificati. URL già presenti conservano comportamento e parametri per retrocompatibilità, senza proporli nelle istruzioni. Default dell’API condivisa e Corsi invariati. Nessuna migrazione; questa revisione prevale sulla precedente assenza di un prefisso dedotto negli Eventi.

- Nuovo meta privato singolo array `_fisar_event_whatsapp_contacts`, non esposto in REST, con voci ordinate `{name, value}`. Nominativo facoltativo; numero o link necessario per conservare la riga. Sanitizzazione centralizzata, righe malformate/vuote scartate; URL HTTP/HTTPS conservano i parametri codificati, incluso il messaggio precompilato. Nessun prefisso internazionale dedotto.
- `fisar_cdj_get_event_whatsapp_contacts()` legge il vecchio `_fisar_event_whatsapp` come prima voce senza scritture, soltanto quando il nuovo meta non esiste. Un elenco nuovo esplicitamente vuoto resta vuoto. `fisar_cdj_get_registration_channels()` emette una voce per contatto WhatsApp Eventi, con `name` aggiuntivo, riusando normalizzazione/riferimento leggibile/link esistenti. Email e altri canali mantengono ordine e comportamento; API e campo singolo dei Corsi invariati.
- Metabox nativo: due campi distinti, `Aggiungi contatto WhatsApp`/`Rimuovi contatto`, label associate, fieldset, indici monotoni, focus e feedback screen-reader. Una riga vuota consente l’inserimento senza JS. Nomi e recapiti sono destinati alla pubblicazione nella pagina dell’evento, come indicato nell’editor.
- Salvataggio con nonce/capability/autosave/revision esistenti e marker `_fisar_event_whatsapp_contacts_present`. Nuovo elenco salvato solo se il metabox è presente; gestione slash/apostrofi. Il primo numero/link è sincronizzato nel vecchio campo per i consumatori legacy, anche quando l’elenco viene svuotato. Un editor precedente senza marker non sovrascrive il nuovo elenco né il contatto legacy sincronizzato. Nessuna migrazione automatica o modifica ai Corsi.

## CPT Corsi
Revisione `1.3.13`, prevalente sulle precedenti indicazioni “Corsi invariati” per queste funzionalità:

- Meta privati singoli `_fisar_course_booking_status` (enum stringa `available`/`sold_out`/`waitlist`, default effettivo `available`), `_fisar_course_limited_seats` (booleano), `_fisar_course_maps_url` (HTTP/HTTPS) e `_fisar_course_whatsapp_contacts` (array ordinato `{name, value}`). Non esposti in REST, stesse capability dei meta esistenti.
- `fisar_cdj_get_course_booking_status()` distingue corso concluso, chiusura tassativa e disponibilità editoriale. `fisar_cdj_get_course_registration_details()` centralizza stato/copy, scadenza canonica, nota flessibile e posti limitati. `fisar_cdj_get_registration_channels()` omette i canali per corso concluso, iscrizioni chiuse e sold-out semplice; lista d’attesa con le stesse destinazioni e label dedicate.
- `fisar_cdj_has_registration_deadline_passed()` condiviso: termine tassativo, data canonica valida precedente a oggi, inclusività e fuso WordPress. Il wrapper Eventi mantiene il controllo `Iscrizione richiesta`; nei Corsi non viene aggiunto tale flag. Stato attivo/concluso basato sulla data di fine invariato.
- Sanitizzatori di disponibilità e contatti, e lettore dei contatti, condivisi; wrapper pubblici Eventi conservati. Numero nazionale con paese predefinito `39` anche nei Corsi, nessuna conversione del dato salvato. Fallback dal singolo contatto precedente solo quando il nuovo elenco non esiste; elenco esplicitamente vuoto autorevole. Primo contatto sincronizzato nel legacy solo al salvataggio del nuovo metabox.
- UI nativa con ripetitore WhatsApp, nominativo/numero distinti, aggiunta/rimozione, focus/feedback screen-reader e riga vuota senza JS. Maps e posti limitati protetti da marker; enum salvato solo se presente, elenco protetto dal proprio marker. Nonce/capability/autosave/revision esistenti. Nessuna migrazione o scrittura automatica nei dati reali.
- Etichette `Iscriviti via WhatsApp`, `Iscriviti via mail`, `Termine iscrizioni` e `Tipo di termine data iscrizione`. Quota editoriale libera, tesseramento, dotazione, calendario/import e relazioni preservati. Non introdotte modalità online/ibrida o quote Soci/Non soci per i Corsi.

Base: titolo, editor, featured image, Direttore del Corso.
Livello: 1° / 2° / 3°.
Date inizio/fine. Attivo se `data_fine >= oggi`, concluso altrimenti.
Sede, indirizzo, città, provincia; inizialmente possono essere noti solo città/provincia.
Iscrizioni: stessi canali degli Eventi + deadline tassativa/flessibile; chiusura derivata dalla scadenza e disponibilità editoriale come nella revisione sopra, senza flag manuale aperto/chiuso.
**Quota di partecipazione**: editor libero (standard, Early Bird, Under 25, gruppi, pagamenti).
**Tesseramento FISAR**: editor libero separato.
**Cosa comprende il corso**: editor libero (kit, manuali, calici, degustazioni, software, attestato).

## Calendario lezioni
Righe strutturate: data, orario, titolo, relatore, note. Il giorno della settimana è automatico.
Nota fissa: “N.B.: il presente calendario potrebbe subire delle variazioni per motivi organizzativi e di disponibilità dei relatori.”
Import V1 da Excel/Google Sheets tramite copia-incolla tabulato. Niente HTML copiato.

## Relazione
Un Evento può avere un Corso collegato. La pagina Corso recupera automaticamente gli Eventi collegati e marca quelli passati come “(concluso)”.
