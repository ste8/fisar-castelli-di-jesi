# Prova del percorso sommelier

Tre illustrazioni originali a tratto: degustazione, territori, abbinamento. Le stesse geometrie generano il documento Clayzo e il fallback SVG completo; nessun marchio modificato o nuovo font.

```sh
cd tools/training-animation
npm install --ignore-scripts --no-package-lock
npm run build
```

Node >=22.13, authoring pubblico `@clayzo/animation@0.3.21`, compatibile con il runtime originale già nel tema. La generazione valida il documento e aggiorna `assets/animations/training-journey.json` e `assets/images/training-journey.svg`. Nessun account necessario per authoring/validazione; nessun servizio remoto a playback.

La sorgente vive nel tema: `/diventare-sommelier/#tre-livelli`. Il filtro del blocco lista inserisce il componente prima di `training-levels`, senza cambiare contenuto o database. Gli asset vengono caricati soltanto sulla pagina; il runtime viene importato quando l’illustrazione entra in viewport, salvo movimento ridotto. Una riproduzione di 4,5 secondi, poi fermo; pulsante per rivederla. Scheda nascosta o uscita dalla viewport completano il disegno. Fallback completo con movimento ridotto, JavaScript/WebGL assenti o errore del runtime.

Non distribuire strumenti o node_modules nel tema. Pagina pubblicata su Aruba il 14 novembre 2026, accessibile a `/anteprima/diventare-sommelier/`; resta senza richiami da menu/Home/Corsi durante la revisione.
