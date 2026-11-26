# Pubblicazione social dal Blog

Prima versione nel plugin Core 1.3.68, pubblicata su Aruba il 25 novembre 2026. Non richiede plugin aggiuntivi; usa l’API GraphQL corrente di Buffer. **La modalità iniziale è simulazione**: nessun post viene inviato al servizio, anche premendo “Simula invio”. Su Aruba l’utente ha configurato la chiave e verificato i canali Facebook/Instagram. Il collaudo reale su Facebook e Instagram è stato completato il 25 novembre 2026, con il clic finale di pubblicazione eseguito dall’utente. Il Core 1.3.73 prepara copie specifiche per canale, mantenendo un solo elenco ordinato, gli invii espliciti e il registro degli esiti.

## Preparazione e invio

Nel riquadro “Pubblica su Facebook e Instagram” compaiono il pulsante “Prepara post social” e i campi facoltativi “URL del post Facebook” / “URL del post Instagram”, utilizzabili anche per post pubblicati direttamente sui social. Come il messaggio WhatsApp degli Eventi, la preparazione si apre in una finestra di dialogo e carica i dati solo alla prima apertura. Si prepara un solo post con testo, selezione e ordine delle foto comuni; i file sono adattati separatamente per Facebook e Instagram dal Core 1.3.73; due checkbox scelgono i canali di destinazione. Il testo suggerito comprende titolo e corpo ed è modificabile. Dal Core 1.3.69 il link all’articolo non viene aggiunto automaticamente. I pulsanti “Usa testo dell’articolo” e “Usa foto dell’articolo” rileggono il documento salvato; non riscrivono il corpo o la galleria. L’anteprima delle foto è anche il controllo di selezione: trascinamento per riordinarle, × per escluderle e + per reinserire le immagini disponibili dell’articolo. Non viene aperta la libreria media: sia il browser sia il server consentono solo le foto già presenti nell’articolo salvato. Dal Core 1.3.84 non è presente la select della posizione; con la foto a fuoco si può riordinare anche tramite Alt + frecce. La selezione social non modifica la galleria originale. L’impaginazione finale dipende dal social. Gli esiti restano separati per canale.

La chiusura conserva la preparazione in memoria; per conservarla oltre il caricamento della pagina occorre “Salva preparazione”. Il pulsante nell’editor segnala eventuali modifiche social non salvate. La finestra usa il dialogo nativo, gestisce il focus e lo restituisce al pulsante di apertura. Dal Core 1.3.74 i pulsanti e lo stato dell’operazione restano in un’area fissa in fondo alla finestra, fuori dalla lista scorrevole delle foto. Durante l’attesa compaiono un indicatore e l’etichetta corrispondente sul pulsante; conferme ed errori restano nello stesso punto. Dal Core 1.3.75 anche il pulsante di invio mostra uno spinner durante la richiesta, resta disabilitato ma leggibile e ripristina il proprio stato al termine; l’animazione rispetta la preferenza di movimento ridotto. Lo stato live si trova fuori dal contenitore occupato, così viene annunciato anche durante la richiesta. Dal Core 1.3.76 la modalità viene indicata dall’azione (“Pubblica sui social” / “Programma sui social” oppure “Simula invio”), senza un avviso fisso in testa alla finestra. Durante un invio non si chiude con il pulsante o con Escape.

Dal Core 1.3.78 il pannello applica un massimo comune di 10 foto a tutti i canali, anche selezionando solo Instagram. Il contatore indica semplicemente “N foto selezionate · massimo 10”, senza mostrare il peso delle copie.

- Facebook: massimo 10 foto selezionate, 63.206 caratteri.
- Instagram: massimo 10 foto selezionate, 2.200 caratteri e almeno una foto.
- Quando si selezionano entrambi i canali, lo stesso post deve rispettare entrambi i limiti: massimo 10 foto e 2.200 caratteri.
- La selezione iniziale comprende tutte le foto dell’articolo, senza tagli automatici. Solo se il corpo non contiene foto viene suggerita la copertina.
- Una selezione eccessiva mostra un avviso e blocca l’invio al social selezionato. Si possono comunque salvare articolo e preparazione, oppure deselezionare quel social. La galleria del sito può contenere più immagini.
- Per Instagram il server genera copie JPEG sRGB 1080 × 1080 px, qualità 85, da file locali decodificabili dal motore immagini WordPress. La foto viene ridotta proporzionalmente senza ingrandire sorgenti piccole, centrata su fondo bianco e mai tagliata. Un formato comune conserva le inquadrature nei caroselli con foto verticali e orizzontali. Per Facebook genera invece JPEG di qualità 85, lato lungo massimo 1440 px, con le proporzioni originali e senza margini aggiunti o tagli. Il menu “Anteprima foto per” mostra il file effettivamente destinato al canale scelto, senza cambiare le checkbox di pubblicazione. Se si seleziona un solo canale, l’anteprima passa automaticamente a quel canale. Ordine e testo restano comuni; gli errori vengono controllati per tutti i canali selezionati, indipendentemente dall’anteprima visibile. Dimensioni, MIME e peso sono letti dal risultato effettivo, non dai metadati dell’originale; un errore di generazione blocca l’invio e compare nell’anteprima.
- Le copie vengono inviate come URL pubblici HTTPS. File locali, protetti o non raggiungibili da Buffer non possono essere pubblicati realmente. L’originale non viene usato come ripiego. Per Instagram vengono controllati larghezza 320–1440 px, proporzioni 3:4–1,91:1 e peso massimo 8 MB.
- Nessuna azione è agganciata a salvataggio, aggiornamento, importazione o pubblicazione nativa WordPress. La condivisione parte solo dal pulsante esplicito del pannello.
- La pubblicazione reale richiede un articolo pubblicato, elencato e senza password; i controlli di simulazione funzionano anche con una bozza.

Dal Core 1.3.84 il clic su “Pubblica sui social” o “Programma sui social” apre una conferma prima di qualunque richiesta di invio reale. Il riepilogo elenca soltanto le destinazioni ancora inviabili, con nome account, numero di foto e, per la programmazione, data/ora nel fuso del sito. “Annulla” ha il focus iniziale; Annulla ed Escape chiudono senza salvare o inviare. Occorre premere “Conferma pubblicazione” o “Conferma programmazione” per procedere. Durante la conferma i controlli sottostanti sono bloccati; alla chiusura i requisiti vengono ricontrollati. La simulazione non richiede conferma reale.

“Salva preparazione” conserva soltanto i dati social. “Pubblica sui social” usa `shareNow`; la programmazione usa `customScheduled` e converte l’ora del sito in UTC. Lo stato restituito da Buffer può essere ancora `sending` o `scheduled`: non viene presentato come avvenuta pubblicazione. “Aggiorna esiti” interroga i post già creati. Nessun polling o retry automatico.

## Tag nelle foto Instagram

Dal Core 1.3.81 ogni foto nell’anteprima Instagram offre **Tag (Instagram)** (etichetta compatta e pulsante grigio/nero dal Core 1.3.84). La finestra mostra la copia quadrata effettivamente destinata al social: scegliere il punto sulla foto, inserire lo username esatto di un profilo pubblico (con o senza @) e premere **Aggiungi tag**. Il punto può essere spostato anche da tastiera con le frecce. I profili inseriti hanno un link per verificarli, più i comandi Modifica e Rimuovi. Non c’è ricerca automatica degli account.

**Conferma tag** riporta le modifiche nella preparazione in memoria; **Salva preparazione** le conserva oltre il caricamento della pagina. Annulla ed Escape scartano le modifiche non confermate della finestra dei tag. Non parte alcun invio da queste azioni. I tag seguono l’ID della foto quando viene riordinata; escludere una foto rimuove anche i suoi tag dalla preparazione. Sono ammessi al massimo **20 tag complessivi nelle foto del post**; username non validi o duplicati nella stessa foto vengono segnalati. Un profilo privato, inesistente o non taggabile può essere rifiutato da Instagram al momento della pubblicazione.

I tag sono metadati delle singole foto, non vengono disegnati nei file né aggiunti alla didascalia. Posizioni decimali tra 0 e 1 riferite all’intera copia Instagram 1080 × 1080, inclusi i margini bianchi. Il payload Instagram li invia su `assets[].image.metadata.userTags`, conservando `altText`; Facebook non riceve questi metadati. La selezione dei canali, il testo e l’ordine delle foto restano comuni. L’anteprima Facebook non mostra i comandi per i tag. Le preparazioni già salvate senza tag restano compatibili, senza migrazioni del database. Le modifiche alla preparazione non aggiornano i post social già creati.

## Varianti per il sito e conservazione

Il Core prepara copie WebP di qualità 78 (JPEG se WebP non è disponibile), con larghezze fino a 480, 768, 1024, 1536 e 2048 px, senza superare la sorgente o 2560 px in altezza. Il tema 1.6.165 le usa nelle copertine e nella griglia uniforme del Blog con `srcset`, `sizes`, dimensioni esplicite e caricamento differito della galleria. Il lightbox conserva la sorgente grande nativa WordPress, caricata all’apertura. La griglia conserva la cornice quadrata già prevista dal tema; nessun file viene ritagliato.

Le copie web mancanti vengono accodate a un evento singolo WordPress durante l’aggiornamento dei metadati media o la prima lettura. Il rendering pubblico usa nel frattempo le varianti native senza elaborare immagini nella richiesta del visitatore. Le copie social si generano quando viene aperta la preparazione autenticata. Tutte le copie sono riutilizzate, senza modificare gli originali, gli ID media, i blocchi o le associazioni. La cache dipende da file, data di modifica e peso. Un lock per immagine evita elaborazioni concorrenti; le vecchie copie restano disponibili per eventuali post programmati in Buffer. La cancellazione esplicita di un allegato elimina anche le sue copie. Il tema mantiene le immagini native come ripiego se un’ottimizzazione web non riesce; la pubblicazione social invece viene bloccata.

## Configurazione

1. Creare l’account Buffer e collegare la pagina Facebook e il profilo Instagram dell’associazione. Il piano e le caratteristiche vanno verificati nell’account prima di attivare invii reali.
2. Creare una chiave API personale nelle impostazioni Buffer.
3. In WordPress, **Impostazioni → Pubblicazione social**, salvare la chiave e verificare i collegamenti. La scoperta legge le organizzazioni dell’account e mostra solo le pagine Facebook e i canali Instagram.
4. Selezionare esplicitamente gli account da usare e salvare. La pubblicazione reale rimane disattivata fino alla selezione dell’apposita opzione.

La chiave è accessibile solo al server, cifrata AES-256-GCM con una chiave derivata dal salt WordPress, e non è mai riempita nell’HTML, nei dati REST o nel JavaScript. Una chiave vuota nel form conserva quella attuale. Se si cambiano i salt occorre reinserirla. In alternativa è supportata la costante server `FISAR_CDJ_BUFFER_API_KEY` in `wp-config.php`, che prevale sulla chiave nel database. Non inserire mai chiavi nel repository o nei log.

## Autorizzazioni ed esiti

La configurazione richiede `manage_options`. Le route REST native richiedono autenticazione WordPress e il permesso di modificare l’articolo; invii e aggiornamento esiti richiedono anche `publish_posts`. Le richieste dal browser usano il nonce REST WordPress.

La preparazione e il registro degli invii sono metadati privati (`_fisar_social_composer`, `_fisar_social_delivery`), non esposti nelle raccolte REST pubbliche. Un controllo di versione rifiuta preparazioni basate su un articolo cambiato. Dal Core 1.3.77, solo in questo caso compare “Aggiorna dati articolo”: ricarica i dati salvati mantenendo testo, selezione e ordine già preparati. Il comando scompare dopo l’aggiornamento riuscito. L’editor richiede il salvataggio delle modifiche native prima dell’invio.

Un lock atomico per articolo impedisce che invii e aggiornamenti degli esiti sovrascrivano lo stesso registro. Per ciascun canale, un ID remoto già ottenuto impedisce la creazione di un duplicato. In caso di successo parziale viene ritentata soltanto la destinazione rifiutata prima della creazione. Non è ancora prevista la ripubblicazione intenzionale dello stesso articolo o la modifica/cancellazione dei post remoti dal pannello.

Timeout, risposte non valide e interruzioni vengono registrati come **esito da verificare** prima di poter generare nuovi invii. Se non si conosce l’ID remoto non è possibile risolvere automaticamente l’ambiguità: occorre controllare il contenuto in Buffer, senza reinviare. La prima versione non offre uno sblocco automatico di questi casi.

Solo dopo lo stato `sent`, se il link social è disponibile, viene compilato il metadato Facebook/Instagram del racconto. Un link già presente viene conservato. I campi nell’editor vengono aggiornati senza sovrascrivere modifiche manuali in corso; un successivo salvataggio nativo non cancella un URL compilato nel frattempo da Buffer.

## Verifiche locali

```sh
node tests/social-publishing.test.cjs
docker compose -f docker/compose.yaml run --rm --no-deps \
  -v "$PWD/tests:/tmp/fisar-tests:ro" --entrypoint wp wordpress_cli \
  --exec='define("WP_ENVIRONMENT_TYPE", "local");' \
  eval-file /tmp/fisar-tests/social-publishing.test.php
```

Il test PHP intercetta tutte le richieste HTTP: nessuna pubblicazione reale. Crea e rimuove solo le proprie fixture, ripristinando opzioni e utente iniziali. Verifica simulazione, limiti, didascalie, ordini, autorizzazioni, contenuti riservati, cifratura, invii parziali, duplicati, timeout ambigui, fusi orari e conservazione del documento.

Per una bozza persistente destinata al controllo UI usare lo stesso comando con `eval-file /tmp/fisar-tests/social-editor-fixture.php`. Riutilizza 12 JPG locali senza modificarli; conserva la sola bozza riconoscibile dal metadato `_fisar_social_ui_fixture`.

## Fonti tecniche

- [API e autenticazione](https://developers.buffer.com/guides/getting-started.html)
- [Riferimento GraphQL](https://developers.buffer.com/reference.md)
- [Media ospitati sul sito](https://developers.buffer.com/guides/hosting-media.html)
- [Limiti immagini per canale](https://support.buffer.com/articles/attaching-images-videos-and-other-media-to-your-posts-eudySt0TnS)
- [Tag nelle immagini tramite API](https://developers.buffer.com/examples/create-instagram-post-with-user-tags.html)
- [Funzionamento e limiti dei tag](https://support.buffer.com/en-us/articles/scheduling-instagram-posts-reels-stories-and-notifications-3XA98S9Q5p)
- [Requisiti immagini Instagram](https://support.buffer.com/articles/instagrams-accepted-aspect-ratio-ranges-Frc2Xqewbd)

Fonti sui tag verificate il 26 novembre 2026; altre fonti verificate il 24 novembre 2026. Il pannello mantiene il limite applicativo comune di 10 foto. I limiti configurati sono dichiarati in `Fisar_CDJ_Social_Publishing::NETWORKS`.
