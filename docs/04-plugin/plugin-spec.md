# Plugin Spec
Il plugin contiene dati e logiche indipendenti dal tema.

## CPT Eventi
Base: titolo, editor, featured image.
Campi: data evento; ora accoglienza/inizio/fine; modalità (presenza/online/ibrido); sede, indirizzo, città, provincia, link Google Maps facoltativo; piattaforma/link online; partecipazione (aperto a tutti/solo soci/soci e accompagnatori); gratuito; quota soci/non soci; iscrizione richiesta; WhatsApp, email, telefono, modulo online, altro canale, info aggiuntive; deadline; chiusura tassativa/flessibile; **Mostra avviso posti limitati**; Corso collegato.

Dal plugin `1.3.4`, `_fisar_event_maps_url` è un meta stringa singolo, non esposto in REST, registrato con le stesse autorizzazioni degli altri campi. Campo URL `Link Google Maps` nel box `Modalità e luogo`, dentro la sezione esistente visibile per presenza/ibrido, non obbligatorio. Riusa salvataggio con nonce/capability e sanitizzazione WordPress; il callback del nuovo meta consente solo HTTP/HTTPS e scarta dati non stringa. Accetta anche link abbreviati condivisi da Google Maps; nessuna risoluzione remota, geocodifica, generazione dall’indirizzo o verifica della destinazione. Nessun nuovo campo Corsi, nessuna migrazione o valorizzazione automatica degli eventi esistenti.

Non memorizzare il numero di posti. Evento concluso se `data_evento < oggi`. Nessuna tassonomia Tipologia evento nella V1.

### Quote personalizzabili — plugin v1.3.5

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

## CPT Corsi
Base: titolo, editor, featured image, Direttore del Corso.
Livello: 1° / 2° / 3°.
Date inizio/fine. Attivo se `data_fine >= oggi`, concluso altrimenti.
Sede, indirizzo, città, provincia; inizialmente possono essere noti solo città/provincia.
Iscrizioni: stessi canali degli Eventi + deadline e chiusura tassativa/flessibile. Nessun flag iscrizioni aperte/chiuse nella V1.
**Quota di partecipazione**: editor libero (standard, Early Bird, Under 25, gruppi, pagamenti).
**Tesseramento FISAR**: editor libero separato.
**Cosa comprende il corso**: editor libero (kit, manuali, calici, degustazioni, software, attestato).

## Calendario lezioni
Righe strutturate: data, orario, titolo, relatore, note. Il giorno della settimana è automatico.
Nota fissa: “N.B.: il presente calendario potrebbe subire delle variazioni per motivi organizzativi e di disponibilità dei relatori.”
Import V1 da Excel/Google Sheets tramite copia-incolla tabulato. Niente HTML copiato.

## Relazione
Un Evento può avere un Corso collegato. La pagina Corso recupera automaticamente gli Eventi collegati e marca quelli passati come “(concluso)”.
