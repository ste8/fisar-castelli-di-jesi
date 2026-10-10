# Blog

## Immagini principali quadrate — tema v1.6.70, 10 ottobre 2026

Approvato il formato **1:1**, consigliato **1080 × 1080 px**, per riusare la stessa immagine principale su sito e post social. Non è un requisito di Instagram né un vincolo al caricamento. Questa revisione prevale sulle precedenti card 16:10 e sul riquadro a proporzioni naturali del dettaglio.

- Home ed elenco: media quadrato sopra il testo, con `cover` conservato. Immagini preparate quadrate riempiono il riquadro; quelle precedenti con altre proporzioni sono adattate mediante ritaglio visivo, senza cambiare il file originale.
- Dettaglio: riquadro quadrato fino a 352 px, ancora a destra su desktop e sotto l’introduzione sui formati piccoli. `contain` conserva intere anche le vecchie foto non quadrate, con spazio bianco quando necessario.
- Formato di foto/gallerie nel corpo libero e lightbox invariata. Media assente omesso; numero articoli, griglie, CTA accessibili, query e dati preservati. Nessuna rigenerazione o modifica degli allegati.

## Immagine del dettaglio contenuta — tema v1.6.69, 9 ottobre 2026

L’immagine in evidenza è nella hero chiara, a destra di titolo, metadata e abstract da `52rem`; sotto tale soglia segue il testo ed è centrata. Riutilizzata la griglia dei dettagli Eventi/Corsi, con contenitore fino a `68rem` solo quando c’è l’immagine. Riquadro bianco largo al massimo `22rem` (352 px), padding `.5rem`, bordo/angoli leggeri e ombra già usata nelle card. Foto intera con `object-fit: contain`, altezza massima `22rem`, senza formato quadrato forzato o ritagli.

Senza immagine, hero testuale centrata entro `48rem`, senza colonna o segnaposto vuoto. Corpo e navigazione restano entro `48rem`, senza affiancamento del testo lungo. Immagine una sola volta, caricamento eager e testo alternativo informativo conservati. Gallerie, lightbox, immagini nel corpo, home, elenco e contenuti salvati invariati. Questa revisione sostituisce l’immagine grande separata sotto la hero delle specifiche precedenti.

## Nome della sezione — tema v1.6.68 / plugin v1.3.23, 9 ottobre 2026

La sezione si chiama **Blog**, per raccogliere approfondimenti, racconti degli eventi e comunicazioni associative. Questa revisione prevale sui nomi News e notizie riportati nelle revisioni storiche sotto.

- Menu principale/footer e pagina articoli nativa: `Blog`. URL `/news/` e permalink degli articoli conservati; nessun nuovo CPT, migrazione, categoria o redirect.
- Hero elenco: `Blog`, con `Approfondimenti, racconti e novità dalla nostra Delegazione.`. Sezione `Ultimi articoli`, eyebrow `Dal nostro blog`.
- Home: `Dal nostro blog`, link desktop/mobile `Tutti gli articoli`, stato vuoto `Non ci sono articoli pubblicati al momento.`. Quattro articoli e ordine invariati.
- Dettaglio: ritorno `Tutti gli articoli`, eyebrow `Dal nostro blog`, navigazione `Articolo precedente` / `Articolo successivo`, nome accessibile `Altri articoli`. Paginazione `Articoli più recenti` / `Articoli precedenti`, nome accessibile `Paginazione del blog`.
- CTA card `Leggi l’articolo` e suffisso accessibile completo conservati. Stili, galleria/lightbox e gestione nativa **Articoli** invariati.
- Seed con titolo e menu Blog; alias legacy `News` riusa le voci esistenti, chiave demo e slug `news` conservati. Nell’ambiente locale rinominati soltanto pagina articoli e due voci di menu, senza reimportare i dati demo.

## Gallerie native — plugin v1.3.22, 8 ottobre 2026

- Nell’editor a blocchi delle News, il normale inserimento del blocco `Galleria` usa una variante predefinita di `core/gallery` con `linkTo: lightbox`. WordPress applica l’ingrandimento alle nuove immagini della galleria e gestisce apertura, chiusura e navigazione tra foto.
- Il blocco mantiene nome e comandi nativi: da **Collegamento → Nessuno** è possibile disattivare l’ingrandimento. Gli editor già aperti vanno ricaricati.
- Solo un default all’inserimento: gallerie salvate, copiate/incollate o ottenute mediante trasformazioni non sono riscritte; le immagini singole restano invariate. Non è un’impostazione globale del sito.
- Script del plugin caricato soltanto nell’editor degli articoli, con dipendenze WordPress già disponibili. Nessuna nuova libreria, CPT, campo, filtro di salvataggio/rendering o script frontend; editor Eventi, Corsi e pagine esclusi.

## Allineamento a Eventi e Corsi — v1.6.67, 8 ottobre 2026

Le News restano articoli WordPress nativi: nessun CPT, campo aggiuntivo o modifica ai contenuti salvati. Le regole seguenti prevalgono sul concept iniziale e sulle scale generiche precedenti.

### Elenco

- Fascia bordeaux scuro compatta, senza fotografia di sfondo, come negli archivi Eventi/Corsi. Titolo `News` Poppins 500 da 32–44 px con breve accento oro; testo introduttivo e copy delle sezioni conservati.
- Sezione nominata `Ultime notizie dalla Delegazione`, Poppins 500 da 32–40 px. Griglia a una colonna su mobile, due da 38 rem, tre da 52 rem.
- Card bianche con bordo tenue, angoli contenuti e ombra leggera. Immagine fotografica orizzontale 16:10, distinta dalle locandine quadrate; omessa quando non inserita, senza segnaposto vuoto.
- Ordine: immagine, titolo, linea oro tenue, data con icona calendario decorativa, estratto, `Leggi l’articolo`. Titoli Poppins 500 da 20–24 px, mai troncati; estratti completi nell’elenco.
- Data di pubblicazione nativa, mese per esteso e `time` con `datetime`; non è una data evento. CTA con titolo completo nel suffisso `.screen-reader-text`.
- Query principale e paginazione native conservate. Una sola landmark di paginazione, con nome `Paginazione delle news`; stato vuoto e link Contatti conservati.

### Dettaglio

- Intestazione chiara, senza immagine di sfondo, coerente con i dettagli Eventi/Corsi: ritorno `Tutte le news`, indicazione `News dalla Delegazione`, H1 Poppins 500 da 32–44 px e accento oro, data e tempo di lettura, abstract editoriale se compilato.
- Immagine in evidenza separata dall’intestazione, a proporzioni naturali, fino a 60 rem; bordo/angoli leggeri. Nessuna immagine o abstract vuoto generato.
- Titolo, corpo e navigazione centrati entro 48 rem. Corpo Poppins 16–17 px / 1.7; heading 500 / 1.35: H2 20–24 px, H3 20 px, H4 18 px, H5/H6 17 px; grassetti 600. Nessuna conversione degli heading delle altre pagine o degli stili dell’editor News in questo intervento.
- Link nativi alla notizia precedente/successiva, titoli Poppins e una sola landmark `Altre news`, senza contenitori `nav` annidati.

### Home

Quattro ultime pubblicazioni, stesso ordine e stessi dati. Fotografia sopra il testo anche su desktop: quattro colonne da 64 rem, due su tablet, una su mobile. Titoli a 22 px, senza troncamento, stesso separatore e data dell’elenco; estratto visivo limitato a due righe solo in home. Link `Tutte le news` sempre disponibile. Se non ci sono articoli, messaggio `Non ci sono notizie pubblicate al momento.`.

Nessuna nuova famiglia tipografica, dipendenza o immagine; Poppins e icone sono già locali. Focus visibile, navigazione da tastiera, preferenze di movimento ridotto e nomi accessibili completi conservati.

## Requisiti comuni

- Accessibile.
- Responsive.
- Coerente con la Carta dei Valori.
- Tono umano e non burocratico.
