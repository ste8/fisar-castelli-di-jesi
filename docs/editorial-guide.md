# Guida editoriale

## Dove modificare

Lavorare nell’[admin Aruba](https://www.fisarcastellidijesi.it/anteprima/wp-admin/) per i contenuti destinati al sito. Il [locale](http://localhost:8090/wp-admin/) è separato: modificare una pagina lì non aggiorna Aruba. Tema e plugin generano parte della presentazione; non tutto quello che si vede è testo della pagina nell’editor.

| Contenuto | Punto di gestione |
|---|---|
| Eventi / Corsi | Menu omonimo, testo a blocchi e metabox dedicati |
| Blog | Articoli WordPress, categorie/tag nativi se utili |
| Home e pagine associative | Pagine; nella Home il tema usa solo i blocchi fotografici riconosciuti |
| Menu | Aspetto → Menu, posizioni principale/footer/social |
| Foto e alt | Media; la singola galleria si gestisce invece nella pagina che la contiene |
| Dati associativi condivisi / Mailchimp / destinazioni alias | Configurazione nel plugin, con intervento sul codice |

Titolo della pagina già H1: nel corpo iniziare con H2, H3 per sottosezioni. Eventi, Corsi e Articoli usano nel canvas font e scala del frontend, ma riepiloghi, quote e calendari sono generati dai campi. La **Breve descrizione** nel metabox è il Riassunto nativo; non duplicarla nel testo.

## Eventi

Compilare data, orari (accoglienza, inizio, eventuale fine), modalità, luogo/indirizzo/città/provincia e, se disponibile, link Maps. Il nome della sede e l’indirizzo sono distinti; non incollare tutto nel solo nome. Per gli ibridi il luogo evidenzia anche la partecipazione online.

Nel riquadro **Dettagli dell’evento** ci sono la data e la **Breve descrizione (facoltativa)**. Quest’ultima compare nelle liste e sotto il titolo del dettaglio solo se compilata: per gli Eventi non c’è un estratto automatico dal testo. Gli orari effettivi sono sotto il luogo nel riquadro **Modalità, luogo e orari dell’evento**. Il riquadro facoltativo **Ritrovo e spostamenti** segue quello del luogo anche quando WordPress ha già memorizzato un ordine personale dei riquadri; non vengono riscritte le preferenze salvate.

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

## Corsi

Compilare livello, città e provincia, sede/indirizzo/Maps, Direttore e date di inizio/fine. Il corso è attivo in base alla data di fine, non alla possibilità di iscriversi: può essere in svolgimento e avere iscrizioni chiuse. Senza data fine non risulta attivo.

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

Data e titolo sono necessari; non incollare una tabella HTML. Dopo import riuscito il testo sorgente si svuota, così le correzioni alle righe non vengono sovrascritte al salvataggio. Righe senza numero mostrano `—`; il giorno della settimana è automatico. La colonna Note pubblica compare solo se almeno una nota è presente. L’avviso sulle possibili variazioni resta sotto la tabella. Eventi collegati compaiono automaticamente nella pagina Corso.

## Blog e fotografie

Il Blog raccoglie racconti, approfondimenti e annunci negli **Articoli**, mantenendo `/news/`. Categorie e tag sono quelli nativi, senza una tassonomia custom obbligatoria.

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
