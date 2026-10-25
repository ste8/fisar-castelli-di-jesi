# Changelog sintetico

Solo cambiamenti significativi. Dettagli, prove e versioni precedenti sono nella cronologia Git; il diario pre-pulizia è nel commit `6ec7e27`, al percorso storico `docs/07-development/changelog.md`. Le guide correnti non richiedono di leggere quel diario.

## 25 ottobre 2026 — Carta in Home come indice, tema 1.6.102 locale

Fascia Valori accorciata: una frase introduttiva e sette titoli con icone, numerati da 1 a 7 come nella Carta, senza descrizioni. Ogni titolo è un link nativo all’ancora del capitolo nella Carta; aggiunto “Chi siamo”, titoli allineati al documento e CTA “Leggi la Carta dei Valori completa”. Link riconoscibili senza hover, righe cliccabili e griglia responsive compatta. Immagine generata approvata conservata; documento completo e database invariati. Solo locale, nessun deploy Aruba.

## 25 ottobre 2026 — Immagine Carta dei Valori, tema 1.6.100 locale

Nuova illustrazione fotografica generata: filari al tramonto e mano che raccoglie uva, comune alla fascia Valori della Home e al fallback della pagina Carta. WebP ottimizzato, sorgente PNG e prompt conservati; precedente asset mantenuto. Overlay e punto focale adeguati ai due contesti, senza cambiare testi o struttura. L’immagine in evidenza della pagina resta prioritaria se impostata nell’editor. Applicazione solo locale, nessun deploy Aruba o modifica al database.

## 25 ottobre 2026 — Documentazione compatta

- Accorpate specifiche e istruzioni in guide di stato; eliminati roadmap completata, assunzioni/decisioni superate e mockup/provini dalla versione corrente. Nessun documento “Attività aperte”; verifiche di lancio nella guida Aruba.
- Conservati Carta V2 e testo privacy come sorgenti distinte dai contenuti correnti. Originali fotografici, manifest e WebP raggruppati in `assets/photography/sito-home/`; rigenerazione senza dipendenze dai mockup.
- Nessuna modifica al frontend del tema, ai dati WordPress o ad Aruba; nessun reimport o commit automatico. Materiale rimosso recuperabile in Git, senza riscrittura della storia.

## 25 ottobre 2026 — Quarto collage

Sei foto scelte dall’utente aggiunte alla galleria Home locale e Aruba: ora 24 scatti distinti/quattro gruppi. Primi tre gruppi invariati, quarto pallino automatico, tema 1.6.99 senza nuovo deploy. Originali conservati.

## 24 ottobre 2026 — Hero approvata, tema 1.6.99

Carosello di gruppi da sei fotografie reali con calice illustrativo naturale, pallini e pausa discreti; nascosto sotto 1024 px, movimento ridotto e controlli da tastiera. Import nella Home locale e aggiornamento nativo di tema/media/sola galleria Aruba, senza import DB locale. Archiviate tutte le 27 immagini originali fornite.

## 22 ottobre 2026 — Anteprima Aruba e core locale

Backup del sito precedente e trasferimento iniziale in `/anteprima/` con DB separato; frontend aperto senza password ma con noindex e protezioni su config/archivi, admin con credenziali cambiate dall’utente. Aruba diventa fonte editoriale. Vecchio sito, DNS/email e PHP globale preservati.

Core locale aggiornato a WordPress 7.1.3 con backup e confronto dati/file, tag Docker allineato. Le copie di build del trasferimento non sono basi per aggiornamenti editoriali successivi.

## Baseline funzionale consolidata

- CPT Eventi/Corsi, backend nativo, calendario strutturato/import TSV, titoli automatici Corsi, stati temporali, scadenze, sold-out/lista d’attesa, quote flessibili, contatti WhatsApp multipli e link Maps. Accesso online Eventi pubblicabile solo esplicitamente.
- Home, archivi/dettagli e pagine associative coerenti; Poppins/Cormorant self-hosted, immagini principali quadrate, focus e responsive, canvas editoriale Eventi/Corsi allineato. Blog su Articoli e gallerie lightbox native.
- Contatti e dati associativi condivisi, Seguici/Newsletter Mailchimp, link brevi, privacy unica, Carta V2 e contenuti associativi in pagine native. Bootstrap demo separato dai dati pubblicati.
