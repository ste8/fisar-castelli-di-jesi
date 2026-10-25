# Fotografie per la hero

Archivio completo delle **27 immagini** ricevute da `Desktop/Desk 2/FISAR/sito-home`, copiato il 24 ottobre 2026. File e nomi originali conservati, senza ritocchi o ricompressione; circa 12,33 MB complessivi. `Thumbs.db`, cache di Windows e non immagine, escluso.

## Contenuto

- `originals/`: tutte le fotografie ricevute, comprese quelle non pubblicate.
- [inventory.json](inventory.json): dimensioni in byte e SHA-256 di ogni originale. Conservate anche le due copie identiche `492712664_…jpg` e `492712664_… (1).jpg`: 27 file, 26 contenuti unici.
- [selection.json](selection.json): selezione approvata, ordine, nomi originali, testi alternativi e punti focali. La chiave storica `E` è conservata per compatibilità con l’importatore; non ci sono più le varianti di prova.
- `optimized/`: i 24 WebP selezionati per WordPress.
- [rebuild.mjs](rebuild.mjs): verifica dell’inventario e rigenerazione dei derivati, senza dipendenze dai vecchi mockup.

## Collage approvato

Dal 25 ottobre la selezione comprende **24 foto differenti, in quattro gruppi da sei**. L’ordine di `variants[0].photos` determina i gruppi; `photos[chiave].original` identifica l’originale.

Il sito non legge questo manifest: usa la Galleria nativa `hero-collage` salvata nella Home del rispettivo WordPress. Il calice è un’immagine separata. Aggiornare l’archivio o rigenerare i WebP **non modifica automaticamente la galleria online**. Per l’editor vedere la [guida editoriale](../../../docs/editorial-guide.md#home-e-carosello), per l’import esplicito la [guida locale](../../../docs/local-development.md#foto-della-hero).

## Verifica e rigenerazione

Dalla radice del repository:

```sh
node assets/photography/sito-home/rebuild.mjs --check
node assets/photography/sito-home/rebuild.mjs
```

Il primo comando verifica inventario, impronte degli originali, presenza dei derivati e assenza di foto duplicate nella selezione, senza scrivere. Il secondo richiede ImageMagick (`magick`) e sovrascrive soltanto i derivati selezionati in `optimized/`: auto-orientamento, lato massimo 1200 px senza ingrandimento, qualità WebP 86. Gli originali non vengono modificati. I ritagli e le lievi regolazioni tonali restano nel CSS della hero, non nei file.

Per aggiungere foto:

1. Copiare gli originali in `originals/`, senza sostituire i precedenti.
2. Aggiornare `inventory.json` con nome, byte e SHA-256, mantenendone il formato e indicando eventuali duplicati. Lo script non aggiorna l’inventario da solo.
3. Aggiornare `selection.json`: chiave leggibile, nome originale, alt e punto focale; gruppi completi da sei senza ripetizioni dello stesso contenuto.
4. Verificare, rigenerare e controllare visivamente ritagli e resa responsive prima di caricare i soli WebP necessari nella Libreria media.

Aruba rimane la fonte editoriale: niente import del database locale. Questo è un archivio di progetto, **non una cartella da caricare integralmente sullo spazio web**. L’approvazione della selezione non certifica liberatorie o diritti di utilizzo: verificarli prima del lancio pubblico.
