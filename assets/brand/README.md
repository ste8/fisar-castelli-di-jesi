# Brand assets

Questa cartella contiene gli asset di riferimento approvati per il brand della Delegazione FISAR Castelli di Jesi.

## File inclusi

- `brand-guideline-v1.png`: guida del brand approvata.
- `blog-default-cover.svg`: copertina quadrata per le anteprime Blog prive di foto; grappolo vettoriale del logo, colori originali e fondo chiaro. Copia distribuita nel tema; nessuna immagine in evidenza viene assegnata nel database.
- `favicon/favicon.svg`: solo grappolo del logo, con triangolo e colori originali su fondo chiaro. `favicon-512.png` è il file quadrato da caricare nell’Icona del sito di WordPress; `favicon.ico` contiene le versioni da 16, 32, 48 e 64 px per eventuali altri usi.
- `logo/logo-fisar-cdj-originale.png`: logo originale; le due copie precedenti erano identiche e sono state riunite in questo file.
- `logo/logo-orizzontale.*` e `logo/logo-verticale.*`: varianti in SVG, PNG e JPG.
- `logo/logo-orizzontale-on-dark.*` e `logo/logo-verticale-on-dark.*`: varianti per fondi scuri in SVG e PNG.

## Nota

Tutti i loghi di riferimento sono raccolti in `assets/brand/logo/`. La brand guideline è il riferimento approvato per le varianti: eventuali nuovi asset devono essere esportati fedelmente da questo riferimento/originale, senza rigenerazioni AI.

Il sito usa i file distribuiti in `theme/fisar-cdj/assets/images/`, separati da questo archivio sorgente.

La favicon usa invece l’impostazione nativa **Impostazioni → Generali → Icona del sito** e i file della Libreria media. Non aggiungere collegamenti duplicati nell’header del tema. Il PNG si rigenera con `rsvg-convert --width 512 --height 512 --output assets/brand/favicon/favicon-512.png assets/brand/favicon/favicon.svg`; il file ICO con `magick assets/brand/favicon/favicon-512.png -define icon:auto-resize=64,48,32,16 assets/brand/favicon/favicon.ico`.
