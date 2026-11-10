# Guida editoriale

## Dove modificare

Lavorare nell’[admin Aruba](https://www.fisarcastellidijesi.it/anteprima/wp-admin/) per i contenuti destinati al sito. Il [locale](http://localhost:8090/wp-admin/) è separato: modificare una pagina lì non aggiorna Aruba. Tema e plugin generano parte della presentazione; non tutto quello che si vede è testo della pagina nell’editor.

| Contenuto | Punto di gestione |
|---|---|
| Eventi / Corsi | Menu omonimo, testo a blocchi e metabox dedicati |
| Blog | Articoli WordPress, categorie/tag nativi se utili |
| Home e pagine associative | Pagine; nella Home il tema usa solo i blocchi fotografici riconosciuti |
| Menu | Aspetto → Menu, posizioni principale/footer/social |
| Favicon | Impostazioni → Generali → Icona del sito; usare il PNG da 512 × 512 in `assets/brand/favicon/` |
| Foto e alt | Media; la singola galleria si gestisce invece nella pagina che la contiene |
| Dati associativi condivisi / Mailchimp / destinazioni alias | Configurazione nel plugin, con intervento sul codice |

Titolo della pagina già H1: nel corpo iniziare con H2, H3 per sottosezioni. Eventi, Corsi e Articoli usano nel canvas font e scala del frontend, ma riepiloghi, quote e calendari sono generati dai campi. La **Breve descrizione** nel metabox è il Riassunto nativo; non duplicarla nel testo.

## Ricostruzione e importazione dei contenuti storici dai social

Queste regole si applicano alla selezione e al caricamento di eventi, racconti, servizi e altri articoli storici. Le sezioni successive spiegano come gestirli nell’editor.

- **Un solo evento per appuntamento**, riunendo annuncio, promemoria e altri post collegati. Prima controllare che non sia già presente sul sito. Per periodi continuativi e più date distinte seguire le indicazioni del calendario degli Eventi.
- **Testo originale di Facebook**, senza riscriverlo né aggiungere un summary. Togliere dal corpo il titolo ripetuto e spostare le informazioni pratiche nei campi dedicati.
- **Veri elenchi puntati o numerati**: convertire le liste scritte su Facebook con trattini o numeri nei corrispondenti elenchi del sito, mantenendo invariati testo, ordine ed eventuali sottolivelli.
- **Metadati solo quando documentati**: data, accoglienza, inizio, luogo, indirizzo, quote e contatti. Se un’informazione manca, lasciarla vuota; non interpretare una quota assente come gratuità.
- **Copertina dell’evento presa dall’annuncio**, preferendo la locandina quadrata originale del post Facebook o Instagram, oppure il file originale fornito dalla Delegazione. Se non si trova una locandina quadrata corretta nei post, usare la copertina della scheda Evento Facebook, se esiste, anche quando è rettangolare. Verificare titolo, anno, edizione, data e dimensioni effettive del file; non usare una foto del resoconto. Controllare che il file non contenga bordi o spazi vuoti aggiunti. Se nessuna delle due fonti offre una copertina adatta, segnalare l’evento e chiedere l’originale, proseguendo con gli altri contenuti verificati. Seguire la [procedura per le copertine](#copertine-degli-eventi-ricostruiti-dai-social).
- **Resoconti nel Blog**, separati dagli eventi, con testo originale e data di pubblicazione del post Facebook, inclusa l’ora quando disponibile.
- **Anche servizi e altri contenuti associativi possono diventare articoli**, senza un evento collegato. Assegnare la categoria appropriata: Racconti degli eventi, Servizi, Corsi, Vita della Delegazione o Ricordi.
- **Foto alla migliore risoluzione recuperabile**, cercandole anche nel visualizzatore Facebook quando l’esportazione contiene miniature, senza interpolazione. Se il post rimanda a un articolo esterno, cercare lì l’immagine originale e registrarne provenienza e crediti.
- **Gallerie uniformi con ingrandimento**: miniature regolari e immagini intere nel lightbox. Come copertina del racconto usare una foto rappresentativa, generalmente la prima della galleria. Se non si recuperano immagini adatte, usare il fallback con il grappolo previsto per il Blog.
- **Collegamento evento–racconto inserito nell’articolo**: il banner sull’evento compare automaticamente. Sono possibili più racconti dello stesso evento.
- **Link Facebook e Instagram nei metadati del racconto**, con i testi “Guarda il racconto su Facebook” e “Guarda il racconto su Instagram”; niente link social aggiunti nel corpo dell’evento.
- **Selezione editoriale prima del caricamento**: niente duplicati di corsi già presenti; segnalare partecipazioni esterne e casi di organizzazione dubbia. Le esclusioni decise per singoli appuntamenti o annualità non si estendono automaticamente agli altri anni: per esempio, quelle del 2026 non si applicano automaticamente al 2024.
- **Commit e push solo su richiesta**, con messaggi in italiano e prefisso dell’area nel formato `area: descrizione`, come `eventi: aggiorna le copertine originali`. Seguire la [convenzione Git del progetto](../README.md#da-dove-partire).

## Racconti degli eventi nel Blog

In **Articoli → Aggiungi articolo**, il riquadro **Racconto dell’evento** contiene **Evento collegato** e i due URL facoltativi **Racconto su Facebook** / **Racconto su Instagram**. Scegliere l’evento dall’elenco (titolo e data), poi incollare i link ai singoli post social disponibili. Per un normale articolo lasciare Nessun evento e i link vuoti.

Il racconto mostra in fondo il rimando all’evento e i collegamenti **Guarda il racconto su Facebook** / **Guarda il racconto su Instagram**. L’evento mostra automaticamente **Leggi il racconto** appena l’articolo è pubblicato; se i racconti sono più di uno, mostra **Leggi i racconti** e l’elenco. Non occorre modificare anche l’evento. Cambiare evento, scollegare un articolo o riportarlo in bozza aggiorna automaticamente i rimandi. Articoli privati, programmati o protetti da password non compaiono nell’evento pubblico.

Per ricostruire i racconti storici usare il testo originale del resoconto Facebook, senza riscriverlo, senza ripetere il titolo nel corpo e senza compilare un summary aggiuntivo. Foto originali nella Libreria media, copertina e Galleria nativa con ingrandimento. I link social appartengono ai metadati, non vanno aggiunti manualmente al corpo. Preparare il primo racconto in bozza per la revisione prima di procedere con gli altri.

Impostare la data di pubblicazione dell’articolo alla data del resoconto originale su Facebook, con l’ora quando disponibile, anziché al giorno del caricamento sul sito. Per il racconto dei vini giapponesi: 12 ottobre 2026, ore 20:39.

L’esportazione Facebook può contenere foto ridotte: prima di caricarle, confrontarne le dimensioni con le versioni disponibili nel visualizzatore Facebook e preferire quelle più grandi, senza interpolazione. Per le gallerie dei racconti conservare le proporzioni originali (ritaglio disattivato) e verificare anche l’immagine ingrandita: il ritaglio delle miniature può essere ripreso dal lightbox nativo. Per il racconto sui vini giapponesi sono state recuperate e sostituite tutte e dieci le foto, fino a 2048 px sul lato lungo, inclusa la copertina.

Per una composizione regolare scegliere lo stile della Galleria **Griglia uniforme**: miniature quadrate su tre colonne da 38rem, due sotto questa soglia; anche l’ultima riga mantiene le stesse dimensioni. Il ritaglio è solo della cornice, con priorità alla parte alta delle foto verticali per conservare i volti. Le immagini ingrandite restano intere. Lasciare disattivato il ritaglio nativo; lo stile uniforme gestisce la presentazione senza modificare gli originali. Applicarlo ai racconti storici importati dai social; per le altre gallerie resta facoltativo.

## Eventi

Compilare data, orari (accoglienza, inizio, eventuale fine), modalità, luogo/indirizzo/città/provincia e, se disponibile, link Maps. Il nome della sede e l’indirizzo sono distinti; non incollare tutto nel solo nome. Per gli ibridi il luogo evidenzia anche la partecipazione online.

Nel riquadro **Dettagli dell’evento** ci sono la data e la **Breve descrizione (facoltativa)**. Quest’ultima compare nelle liste e sotto il titolo del dettaglio solo se compilata: per gli Eventi non c’è un estratto automatico dal testo. Gli orari effettivi sono sotto il luogo nel riquadro **Modalità, luogo e orari dell’evento**. Il riquadro facoltativo **Ritrovo e spostamenti** segue quello del luogo anche quando WordPress ha già memorizzato un ordine personale dei riquadri; non vengono riscritte le preferenze salvate.

### Copertine degli eventi ricostruiti dai social

**Preferire la locandina quadrata originale del post di annuncio, relativa all’edizione e alla data corrette. Se manca, usare la copertina della scheda Evento Facebook, anche rettangolare.** Le fotografie del resoconto appartengono invece al Blog.

1. Se la Delegazione fornisce il file originale, usare quello. Altrimenti cercare nei post di annuncio e nei promemoria sia su Facebook sia su Instagram, controllando anche tutte le immagini degli album e dei caroselli. Non limitarsi all’esportazione Facebook o alla scheda Evento.
2. Confrontare titolo, anno, edizione, data e dettagli stampati sulla locandina con l’evento. In caso di rinvio o correzione, cercare la versione aggiornata: una locandina quadrata con la vecchia data non è una sostituzione valida.
3. Verificare le dimensioni effettive del file aperto: la miniatura quadrata nella griglia di un social non dimostra che l’originale sia quadrato. Fra le versioni corrette preferire quella quadrata alla copertina orizzontale, poi scegliere la risoluzione maggiore disponibile, idealmente 1080 × 1080 px o superiore.
4. Se non si trova una locandina quadrata corretta nei post, aprire la scheda Evento Facebook e recuperare la sua copertina originale, anche se rettangolare, verificandone data, edizione e dettagli. Se manca anche questa o non è adatta, segnalare l’evento e le fonti controllate e chiedere il file originale alla Delegazione. Non usare una foto del resoconto o una versione con data errata. Proseguire con gli altri eventi verificati.
5. Conservare il file senza interpolazione, ritagli o margini aggiunti per renderlo quadrato. Registrare la provenienza (link al singolo post oppure file fornito dalla Delegazione) e controllare la copertina pubblicata sia nell’elenco sia nel dettaglio dell’evento.
6. Nelle importazioni multiple, verificare ogni abbinamento evento–copertina contro il piano delle fonti approvato, indipendentemente dal file generato dallo script d’importazione. Controllare ID del media, nome del file e immagine effettivamente mostrata sul sito. Copertine identiche assegnate a eventi diversi richiedono una verifica esplicita: la sola corrispondenza fra file d’importazione ed esportazione WordPress non basta.

### Una giornata, un periodo o più date

In **Dettagli dell’evento**, scegliere **Svolgimento**:

- **Una sola giornata**: compilare Data evento, come prima.
- **Periodo continuativo**: compilare Data evento e Data finale (inclusa), per esempio dal 23 al 24 novembre.
- **Più date distinte**: inserire la prima data, poi **Aggiungi data** per le altre giornate. Ogni data può avere una nota pubblica, per esempio “Uscita in giornata con Francesco”. **Rimuovi** elimina la riga; senza JavaScript svuotare data e nota e salvare per ottenere una nuova riga vuota.

Due uscite alternative al FIVI usano date distinte; una permanenza continuativa di due giorni usa un periodo. Appuntamenti con programmi o iscrizioni autonomi possono rimanere eventi separati. Luogo e orari generali valgono per tutte le giornate: le note precisano eventuali differenze. Il calendario viene riordinato cronologicamente, portando con sé le note. Date duplicate, righe con nota ma senza data e periodi invertiti vanno corretti prima di salvare. Il server conserva il calendario precedente se riceve un calendario non valido.

L’evento rimane in programma fino all’ultima giornata, anche negli intervalli fra due appuntamenti. Le card evidenziano la prossima data o il periodo in corso; il dettaglio e WhatsApp elencano tutte le giornate. I filtri anno/mese trovano anche gli eventi che attraversano mesi o anni, senza far coincidere un intervallo vuoto con un evento continuativo. Gli eventi già presenti rimangono di una giornata; nessuna modifica automatica ai loro dati.

### Ritrovo e spostamenti

Se prima dell’attività ci si incontra altrove, compilare il riquadro facoltativo **Ritrovo e spostamenti**: ora, punto di ritrovo, indirizzo, città/provincia, Maps e indicazioni organizzative. Queste ultime sono pubbliche e compaiono nel riepilogo iniziale e nelle informazioni pratiche: usarle per auto condivise, arrivi successivi o coordinate da comunicare separatamente, non per dati riservati agli iscritti.

Si può indicare solo il ritrovo, solo sede/orari dell’evento oppure entrambi. Se è noto solo il ritrovo, lasciare vuoti sede e ora d’inizio effettivi, senza duplicare i dati. Nel dettaglio il blocco Ritrovo è distinto da Orario evento e Luogo evento; le righe vuote non compaiono. Nelle card si dà precedenza al punto di ritrovo con l’etichetta “Ritrovo:”. Gli eventi ordinari restano invariati; negli eventi solo online il ritrovo non viene pubblicato, ma eventuali dati già inseriti restano salvati.

Il link di partecipazione online è nascosto per default. Attivare **Mostra le informazioni per partecipare online** soltanto se deve essere pubblico; altrimenti comunicarlo agli iscritti separatamente, senza inserirlo nel corpo pubblico. Non esiste invio automatico del link.

### Partecipazione e quote

- Selezionare gratuità e necessità di prenotazione separatamente: un evento gratuito può richiederla.
- Quote Soci/Non soci facoltative. Altre opzioni tramite **Aggiungi quota**: etichetta, importo/testo breve e nota facoltativa. Per quota unica si possono lasciare vuote le prime due e usare una riga personalizzata.
- Un numero come `25` mostra `25 €`; testi come `Da 25 €` o `Offerta libera` restano testi. Per supplementi specificare chiaramente il significato e la valuta, per esempio `+10 €`. Nessuna somma automatica.
- Righe senza etichetta o importo ignorate; `0` valido. Una nota generale chiarisce condizioni comuni. Gratuito nasconde quote e note senza cancellarle.

### Prenotazioni

Inserire recapiti e termine **Tassativo** o **Flessibile**. Il termine tassativo include la data indicata: iscrizioni chiuse dal giorno successivo nel fuso del sito. Quello flessibile lascia i canali disponibili con la nota sulla disponibilità non garantita. Le quote restano leggibili anche a iscrizioni chiuse.

L’editor separa **Contatti WhatsApp**, **Altri canali di contatto**, **Termine delle prenotazioni** e **Altre informazioni sulle prenotazioni**. Data e tipo di termine sono affiancati, con la data per prima in un campo compatto; sui formati stretti vanno a capo mantenendo lo stesso ordine.

Disponibilità: Ordinaria, Sold-out, Sold-out con lista d’attesa. Sold-out semplice nasconde contatti operativi e termine; lista d’attesa usa gli stessi contatti con messaggi dedicati. La scadenza tassativa superata e l’evento concluso impediscono di riproporre un’iscrizione ordinaria. Tornare a Ordinaria non cancella i recapiti. L’avviso posti limitati non conta o prenota posti.

Più contatti WhatsApp con nome facoltativo e numero: accettati `335 1234567` e `+39 335 1234567`; per esteri indicare il prefisso. Nomi e numeri saranno pubblici, anche come testo oltre ai pulsanti. All’apertura compaiono solo i contatti salvati: usare **Aggiungi contatto WhatsApp** per un altro riquadro, anche nei Corsi. Le righe senza numero sono ignorate. Solo senza JavaScript compare una riga vuota da compilare e salvare per aggiungerne una; svuotare il numero per rimuoverla. I recapiti personali non sono il gruppo WhatsApp pubblico.

Nel dettaglio c’è un solo pannello finale **Iscrizione**, raggiungibile dall’alto. Scadenza e nota restano anche nel riepilogo iniziale; non aggiungere manualmente un secondo pannello nel testo. Un Evento può essere collegato a un Corso dal campo apposito.

### Messaggio da incollare in WhatsApp

Nel riquadro autonomo **Messaggio WhatsApp**, in fondo ai campi dell’evento, usare **Genera messaggio WhatsApp**. La generazione legge la versione salvata: con modifiche non salvate, evento nuovo o salvataggio in corso la finestra mostra un avviso e non genera/copia dati precedenti. Chiudere, salvare l’evento e riaprire; non occorre ricaricare la pagina dopo un salvataggio riuscito. Sono controllati titolo/testo WordPress e campi aggiuntivi, inclusi contatti, quote e note. Non salva, pubblica o invia nulla.

La finestra presenta il testo modificabile e un’anteprima della formattazione. **Copia messaggio** copia il testo con `*grassetto*` e `_corsivo_`, non HTML o Markdown con doppi asterischi. Se il browser nega la copia, seleziona il testo per la copia manuale. Per generare nuovamente chiudere e riaprire la finestra: le modifiche fatte solo nell’anteprima vengono sostituite. Chiudendola, il contenuto dell’evento resta invariato.

Ordine standard: titolo in MAIUSCOLO → descrizione → data/ritrovo/luogo/orari → partecipazione → quote → scadenza/disponibilità → contatti → note → “🌐 TROVI I DETTAGLI ANCHE SUL SITO” con link alla pagina, solo se pubblicata senza password. I titoli di sezione del corpo, anche in paragrafi in grassetto con emoji, precedono la riga successiva senza riga vuota. Gli avvisi su bozze, data mancante e messaggi lunghi sono fuori dal testo da copiare.

Il messaggio ignora le date trascorse: anche un evento passato o con termine tassativo già superato mantiene dati, scadenza e contatti per prenotare. Restano invece rispettati gratuità, sold-out/lista d’attesa scelti nell’editor e la visibilità dell’accesso online. È una regola del solo generatore: il sito continua a chiudere le iscrizioni e a nascondere i link online quando previsto. Controllare le date prima di condividere un evento vecchio.

Scadenza nel formato **PRENOTAZIONI ENTRO DOMENICA 4 OTTOBRE**, tutto maiuscolo e senza anno; la data dell’evento resta completa. Per la lista d’attesa: **SOLD-OUT - LISTA D’ATTESA** e scadenza con suffisso **(LISTA DI ATTESA)**. I contatti sono sempre introdotti da **INFO E PRENOTAZIONI**, anche con lista d’attesa.

Presentazione, vini in degustazione, menu e altri dettagli editoriali vengono dal testo dell’evento: titoli, paragrafi, grassetti, corsivi, elenchi e link sono convertiti, senza inventare o riassumere contenuti. Immagini/gallerie non sono copiate: allegare separatamente la locandina. Non vengono eseguiti shortcode o contenuti incorporati. Il numero esatto di posti non è un campo strutturato: se necessario, indicarlo nella descrizione o correggere l’anteprima. Controllare eventuali ripetizioni se informazioni pratiche già compilate nei campi sono state scritte anche nel corpo.

## Corsi

Compilare tipo di corso (Percorso Sommelier, Minicorso di avvicinamento al vino o Corso tematico), eventuale livello, città e provincia, sede/indirizzo/Maps, Direttore e date documentate. Il corso è attivo in base alla data di fine, non alla possibilità di iscriversi: può essere in svolgimento e avere iscrizioni chiuse. Senza data fine non risulta attivo.

Per un’edizione storica già terminata, selezionare **Corso storico concluso**, anche se la data finale è sconosciuta: compare nei Corsi conclusi e non accetta iscrizioni né mostra offerte. Le date mancanti rimangono vuote. Se non si conosce l’inizio esatto, compilare **Anno dell’edizione** con il solo anno documentato: card e dettaglio mostrano “Edizione 2023”, senza inventare giorno o mese. Una data di inizio conosciuta ha precedenza sull’anno. L’archivio è ordinato per inizio, oppure anno quando l’inizio manca; in assenza di entrambi usa la fine o la data editoriale. Non selezionare Corso storico concluso per corsi ancora in svolgimento. Minicorsi e corsi tematici usano titolo personalizzato e livello vuoto se non previsto.

L’editor distingue **Percorso e direzione** da **Durata del corso**: inizio e fine sono campi compatti affiancati, separati dal termine delle iscrizioni. Recapiti, termine e informazioni aggiuntive hanno sottosezioni distinte, come negli Eventi.

**Titolo Automatico** compone “Corso Sommelier [livello] – [città (provincia)]” e lo aggiorna al salvataggio. **Personalizzato** mantiene il testo libero. La città va compilata nel campo, non viene letta dal titolo; lo slug esistente non cambia automaticamente. Nell’archivio la data visibile è quella di inizio; la sede sostituisce la sola città quando disponibile.

Il pannello finale è **Informazioni e iscrizioni**: invito a contattare la Delegazione, non iscrizione automatica. Disponibilità, scadenze e WhatsApp multipli funzionano come per gli Eventi, ma senza il flag “Iscrizione richiesta”. Non ci sono quote Soci/Non soci imposte né modalità online/ibrida dei Corsi.

Separare **Quota di partecipazione**, **Cosa comprende il corso** e **Tesseramento FISAR**, in questo ordine nell’editor e nel dettaglio. Nell’editor segue **Offerta promozionale**, con data compatta facoltativa. I testi liberi possono spiegare rate, condizioni e dotazioni. Un numero isolato riceve `€`; non servono duplicazioni del riepilogo pratico nel corpo.

**Corso in offerta** rende visibile la fascia gialla. Fine offerta facoltativa e inclusiva; senza data va disattivata manualmente. Sold-out, lista, chiusura o corso concluso nascondono il banner. Prezzi e termine iscrizioni sono indipendenti: il flag non li modifica.

### Calendario lezioni

Si può compilare direttamente **Lezioni del corso**; la sezione **Importa da un foglio di calcolo** è un’alternativa facoltativa.

1. Da Excel/Sheets copiare celle tabulate: **Numero lezione, Data, Orario, Titolo, Relatore, Note**.
2. Incollare nel campo di importazione e controllare l’anteprima; poi salvare il Corso.
3. Il numero è manuale/facoltativo (`03`, `3 bis`), non rinumerato dal sito. L’ordine segue data e orario.
4. Date accettate: `GG/MM/AAAA`, `GG-MM-AAAA`, `GG.MM.AAAA`, `AAAA-MM-GG`. Anche il precedente formato senza numero è supportato.

Data e titolo sono necessari; non incollare una tabella HTML. Dopo import riuscito il testo sorgente si svuota, così le correzioni alle righe non vengono sovrascritte al salvataggio. Righe senza numero mostrano `—`; il giorno della settimana è automatico. La colonna Note pubblica compare solo se almeno una nota è presente. Se un relatore storico non è documentato, lasciare vuoto il campo: nei corsi conclusi compare `—`; nei corsi attivi resta “Da definire”. L’avviso sulle possibili variazioni resta sotto la tabella. Eventi collegati compaiono automaticamente nella pagina Corso.

## Blog e fotografie

Il Blog raccoglie racconti, approfondimenti e annunci negli **Articoli**, mantenendo `/news/`. Categorie e tag sono quelli nativi, senza una tassonomia custom obbligatoria.

I filtri pubblici usano le categorie **Racconti degli eventi**, **Servizi**, **Corsi**, **Vita della Delegazione**, **Ricordi**. Si assegnano dal pannello Categorie dell’articolo o dalla modifica di gruppo. **Altro** è un filtro automatico, non una categoria da assegnare: mostra gli articoli fuori da tutte le categorie principali, inclusi quelli ancora da classificare. **Tutte le categorie** mostra invece l’intero archivio. Le categorie principali sono riconosciute tramite gli slug documentati nell’architettura; nuove categorie non principali confluiscono in Altro.

Anno/mese nel Blog riguardano la data di pubblicazione, negli Eventi la data dell’evento. La ricerca controlla titolo e corpo; i filtri possono combinarsi, si applicano all’intero elenco e rimangono nei collegamenti alle altre pagine. Negli Eventi il form agisce soltanto sugli **Eventi conclusi**, lasciando sempre visibili i prossimi appuntamenti. Un cambio di filtri riparte dalla prima pagina, **Azzera filtri** torna all’elenco completo conservando il numero di elementi per pagina; negli Eventi entrambi riportano direttamente alla sezione dei conclusi. Il numero totale dei risultati compare sempre sotto i controlli, a destra accanto ad Azzera filtri. **Risultati per pagina** offre 12, 24, 36 e 48 e rimane nell’URL condivisibile. Le selezioni aggiornano subito l’elenco; il testo dopo una breve pausa (650 ms), mantenendo il cursore nel campo. **Cerca** e Invio restano disponibili. Con JavaScript anche **Azzera filtri**, la paginazione e i tentativi dopo un errore aggiornano soltanto l’elenco, senza ricaricare la pagina. Il reset mantiene la posizione e riporta il focus alla ricerca; cambiando pagina la vista passa ai nuovi risultati. L’URL può cambiare senza provocare ricaricamenti e usa nomi semplici: `search`, `anno`, `mese`, `categoria`, `per_pagina`. Anche i precedenti URL con prefisso `fisar_` continuano a funzionare. Senza JavaScript rimane la navigazione GET ordinaria. Le opzioni brevi **Tutti** sono identificate dalle etichette accessibili Anno/Mese.

Nel riquadro **Descrizione e immagini dell’articolo** si trova la **Breve descrizione (facoltativa)**: compare nelle liste e sotto il titolo del dettaglio. Se vuota, le liste ricavano un estratto dal testo, mentre sotto il titolo non compare. È un unico campo nativo, non una copia del Riassunto. Le indicazioni successive distinguono l’immagine principale dalla galleria nel testo; l’immagine si imposta ancora tramite **Immagine in evidenza** nelle impostazioni dell’articolo.

Per le immagini principali di Eventi, Corsi e Blog usare preferibilmente **1080 × 1080 px**. Le locandine di Eventi/Corsi rimangono intere su bianco; le foto Blog non quadrate possono essere ritagliate nelle card, ma sono intere nel dettaglio. Evitare testi essenziali ai bordi.

Nel corpo degli Articoli inserire blocchi Galleria nativi. Le nuove gallerie hanno l’ingrandimento al clic predefinito; si disattiva con Collegamento → Nessuno. Gallerie già esistenti/copiate e immagini singole mantengono le impostazioni proprie. Aggiungere alt informativi; un’immagine decorativa può avere alt vuoto.

## Home e carosello

La Home mostra sezioni generate: Hero → Eventi/Corsi → Blog → Carta dei Valori → Come seguirci → footer. Copy della hero, limiti e indice Valori sono nel tema, non nel normale corpo della pagina. La fascia Valori presenta una frase introduttiva e sette titoli numerati da 1 a 7, senza descrizioni: ogni titolo porta al capitolo corrispondente della Carta tramite la sua ancora. Il pulsante apre il documento completo. Le Quattro Porte e la fascia istituzionale sotto la hero non ci sono.

Per la galleria: Pagine → Home “FISAR Castelli di Jesi” → Modifica → Riepilogo del documento → **Galleria**. È la Galleria top-level con classe `hero-collage`, senza link/didascalie. Non è un album separato in Media.

- Foto singole in ordine fisso, non casuale: 1–6 primo collage, 7–12 secondo, ecc. Ogni sei foto un altro gruppo/pallino; attualmente 24 foto/quattro gruppi. Preferire gruppi completi e scatti distinti.
- Le prime due di ciascun gruppo occupano i riquadri grandi, le altre quattro i dettagli. Controllare i ritagli, soprattutto con volti e foto verticali; il calice può coprire una parte del primo dettaglio.
- Le classi Immagine `hero-focus-X-Y` indicano il punto focale percentuale. Conservarle o aggiornarle con chi cura il layout quando si sostituisce uno scatto; non sono un filtro applicato all’originale.
- Il calice è un blocco Immagine top-level separato, classe `hero-glass`: lasciarlo fuori dalla Galleria. È illustrativo, non una fotografia di un oggetto reale.
- Album, calice e controlli spariscono sotto 1024 px; testo e CTA restano. Niente opzioni di test nel frontend.

L’[archivio fotografico](../assets/photography/sito-home/README.md) conserva tutti i 27 originali, selezione e derivati. Non caricare automaticamente ogni originale nella Libreria media: pubblicare solo ciò che serve, ottimizzato. Il manifest del repository documenta la selezione applicata, non si sincronizza da solo con una galleria modificata su Aruba.

## Pagine associative, menu e contatti

Menu principale: Chi siamo, Eventi, Corsi, Blog, Seguici, Contatti; CTA **Diventa socio**. Nel sottomenu: La FISAR, La nostra delegazione, Consiglio e incarichi, Carta dei Valori, Statuto, Diventa socio. Conservare URL e ancore dei menu, anche cambiando titoli visibili.

| Pagina | URL / contratto da conservare |
|---|---|
| La FISAR | `/la-fisar/`; testo nazionale distinto dalla delegazione locale |
| La nostra delegazione | `/la-nostra-delegazione/`; classi `delegation-*`, sezioni `valori`, `consiglio`, `incarichi`, `squadra`, `statuto`, `storia` |
| Carta dei Valori | `/carta-dei-valori/`; primo Paragrafo `values-scope-note` per la nota locale, payoff `lead` nel corpo |
| Diventa socio | `/unisciti-a-noi/`; vantaggi, quote e modalità di adesione da ricontrollare ai rinnovi |
| Seguici | `/seguici/`; canali/modulo condivisi con Home, ancora `newsletter-page` |
| Contatti | `/contatti/`; introduzione editoriale, dati comuni generati dal plugin |
| Privacy | Pagina scelta nelle impostazioni Privacy; conservare `navigazione-e-cookie` |

Nella Delegazione la storia è ultima, per distinguere i fondatori dal direttivo attuale; nota introduttiva sul 2010. Il documento storico del 2010 non è lo statuto vigente. Nel riquadro Statuto usare `/statuto/`, alias al documento Drive fornito: non è un download diretto. Incarichi non elettivi distinti dal Consiglio; Eventi dopo Organizzazione Corsi e Didattica.

Le icone della Carta sono aggiunte dal tema in base alle ancore H2: `chi-siamo`, `persone-e-modo-di-lavorare`, `curiosita-e-apertura`, `vino-con-consapevolezza`, `semplicita-e-informalita`, `inclusione-e-accoglienza`, `ognuno-puo-contribuire`. Non rinominarle insieme ai titoli. La Carta è locale e non rappresenta necessariamente i principi della FISAR nazionale; la trascrizione V2 di riferimento è nelle [sorgenti](sources/README.md).

Footer unico bianco. Dati/recapiti ufficiali, SDI e RUNTS provengono da `fisar_cdj_get_delegation_details()` nel plugin; non duplicarli nei testi di Contatti. Le due mail sono alternative equivalenti; il telefono del Delegato non è automaticamente un contatto WhatsApp. La sede associativa non è necessariamente la sede delle attività.

Menu social comune per barra/footer/card. Destinazioni brevi: `/whatsapp/` (gruppo, non canale), `/facebook/`, `/instagram/`, `/newsletter/`. Aggiornare destinazioni/configurazione nel plugin e non creare pagine che occupino questi alias. Il modulo newsletter è condiviso: modificare le descrizioni nei componenti comuni, non in copie separate dei template.

## Newsletter e privacy

Nome, cognome ed email inviati direttamente a Mailchimp, non archiviati in WordPress. Consenso obbligatorio e link all’informativa; conferma email, tracking e messaggi dipendono dall’Audience Mailchimp. Non dedurre che siano configurati dal solo funzionamento del modulo.

Nessun analytics o pixel custom previsto nel progetto; informativa unica, Cookie Policy precedente in bozza e alias verso la sezione privacy. I testi legali pubblicati si modificano su Aruba; la copia nelle sorgenti è storica e non certifica la configurazione futura. Le verifiche prima del lancio sono nella [guida di pubblicazione](deployment-aruba.md), non in un documento separato di attività.

Nell’archivio pubblico Corsi la ricerca comprende titolo, testo, città, sede e indirizzo. Anno, tipologia e città si possono combinare; l’anno segue l’inizio documentato oppure l’anno dell’edizione se manca una data precisa. Il mese non viene proposto. I menu anno/città comprendono solo i corsi conclusi pubblicati; mantenere città e sede nei rispettivi campi per consentire il ritrovamento. I filtri non modificano i corsi attivi.

Per i corsi Sommelier senza una locandina documentata il tema mostra automaticamente la fotografia generica fornita dalla Delegazione; non viene presentata come foto di quella specifica edizione. Lasciare vuota l’immagine in evidenza finché non si trova l’originale: assegnandola, questa sostituirà automaticamente il fallback. Minicorsi e corsi tematici mantengono le proprie immagini o l’assenza di copertina.
