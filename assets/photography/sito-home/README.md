# Fotografie per la hero

Archivio completo delle **27 immagini** ricevute da `Desktop/Desk 2/FISAR/sito-home`, copiato il 24 ottobre 2026. File e nomi originali conservati, senza ritocchi o ricompressione; circa 12,33 MB complessivi. `Thumbs.db`, cache di Windows e non immagine, escluso.

`inventory.json` registra dimensioni in byte e SHA-256 di ogni file. Sono conservate anche le due copie identiche `492712664_…jpg` e `492712664_… (1).jpg`, per avere tutte le immagini della cartella fornita: 27 file, 26 contenuti unici. La duplicazione è esplicitata nell'indice.

## Collage approvato

La selezione E usa 18 foto differenti, divise in tre gruppi da sei. Selezione, ordine, testi alternativi e punti focali sono in [`photos.json`](../../../docs/06-mockups/hero-2026-10-23/photos.json); il campo `original` di ogni voce identifica il file in `originals/`. Non tutte le foto archiviate sono pubblicate nella hero.

Per aggiungere foto in futuro:

1. Copiare gli originali in questa cartella `originals/`, senza sostituire i precedenti.
2. Aggiungere o aggiornare le voci della selezione in `photos.json`, con nome originale, chiave leggibile, testo alternativo e punto focale. Ogni collage E richiede sei foto, senza ripetizioni tra i gruppi.
3. Rigenerare i derivati WebP con il comando sotto e verificare ritagli, leggibilità e dimensioni del collage nell'anteprima. Aggiornare l'inventario per i nuovi originali.

```sh
node assets/photography/sito-home/rebuild.mjs
```

Richiede ImageMagick (`magick`); non è una dipendenza runtime del sito. Con `--check` verifica originali, inventario e derivati senza modificarli. I WebP sono generati a qualità 86, lato massimo 1200 px senza ingrandimento, nella cartella `images/` dell'anteprima; gli originali non vengono modificati. Le lievi correzioni tonali e i ritagli sono CSS, non incorporati nei derivati.

Archivio sorgente del progetto, **non una cartella da caricare integralmente sullo spazio web**. Per WordPress distribuire soltanto le immagini selezionate/ottimizzate tramite la Libreria media. Aruba rimane la fonte editoriale; niente import del database locale. L'approvazione della selezione non certifica liberatorie o diritti di utilizzo: verificarli prima del lancio pubblico.
