# News

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
