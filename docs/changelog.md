# Changelog sintetico

Solo cambiamenti significativi. Dettagli, prove e versioni precedenti sono nella cronologia Git; il diario pre-pulizia è nel commit `6ec7e27`, al percorso storico `docs/07-development/changelog.md`. Le guide correnti non richiedono di leggere quel diario.

## 26 ottobre 2026 — Ritrovo Eventi pubblicato su Aruba

Aggiornamento nativo del plugin Core a 1.3.31 e del tema a 1.6.106 nell’anteprima. Verificati versioni attive, nuovo riquadro e inizio facoltativo nell’editor remoto senza salvare l’evento aperto. Home, archivio e dettaglio esistente invariati salvo versione asset/spaziatura del markup; root identica, CSS remoto uguale al locale, noindex conservato. Pacchetti precedenti conservati; nessun import DB, reimport demo o modifica a contenuti/media/menu. I tre eventi creati per il collaudo sono rimasti solo locali e sono stati rimossi.

## 26 ottobre 2026 — Ritrovo Eventi, plugin 1.3.31 e tema 1.6.106 locale

Gruppo editoriale facoltativo per ora, luogo/indirizzo/località/Maps del ritrovo e indicazioni sugli spostamenti, indipendente dai dati effettivi dell’evento. Riepilogo e informazioni pratiche distinguono il ritrovo da luogo/orari dell’evento; card con “Ritrovo:” quando disponibile. Ora inizio non obbligatoria se ignota; nessuna duplicazione o migrazione degli eventi esistenti. Nuovi meta sanitizzati, salvataggi protetti da nonce/autorizzazioni e marker; ritrovo nascosto negli eventi solo online senza cancellarne i dati. Test d’integrazione locale e responsive; pubblicazione Aruba documentata separatamente a rilascio completato.

## 26 ottobre 2026 — Tema 1.6.105 pubblicato su Aruba

Aggiornato il solo tema dell’anteprima con l’indice Valori senza separatori e frecce, disposto in due colonne indipendenti (1–4 e 5–7), una sola su mobile. Pacchetto precedente 1.6.102 verificato contro il CSS remoto e conservato per rollback. Verificati CSS identico al locale, responsive desktop/tablet/mobile senza overflow e noindex preservato; resto della Home e sito alla radice identici prima/dopo. Nessun import DB o aggiornamento di contenuti, media e plugin.

## 26 ottobre 2026 — Colonne Valori indipendenti, tema 1.6.105 locale

Separati i due gruppi dell’indice Home: il ritorno a capo del punto 2 non aumenta più la distanza tra i punti 6 e 7. Invariati contenuti, link e ordine verticale; su mobile i gruppi formano una colonna continua. Solo locale, nessuna modifica ad Aruba o al database.

## 26 ottobre 2026 — Indice Valori per colonne, tema 1.6.104 locale

Rimosse le frecce decorative dai sette link della Home. Lettura verticale: punti 1–4 nella prima colonna e 5–7 nella seconda; colonna unica 1–7 sui formati stretti. Conservati ordine DOM/tastiera, icone, sottolineature e destinazioni. Solo locale, nessuna modifica ad Aruba o al database.

## 26 ottobre 2026 — Indice Valori senza separatori, tema 1.6.103 locale

Rimosse le linee tra i sette punti della Carta dei Valori nella Home. Icone, numerazione, link sottolineati e spaziature conservati. Solo locale, nessuna modifica ad Aruba o al database.

## 26 ottobre 2026 — Tema 1.6.102 pubblicato su Aruba

Aggiornamento nativo del solo tema nell’anteprima: nuova immagine della Carta, fascia Home compatta con sette titoli numerati e link ai capitoli. Conservati export editoriale e pacchetto rollback 1.6.99, verificato sul CSS remoto prima del rilascio. Asset pubblicati identici ai locali; confronti HTML confermano il resto della Home, il testo della Carta e il vecchio sito alla radice invariati. Noindex HTTP/meta preservati; nessun import DB, reimport demo o aggiornamento plugin.

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
