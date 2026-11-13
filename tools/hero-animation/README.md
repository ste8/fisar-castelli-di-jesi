# Animazione leggera del calice

Sorgente del documento Clayzo della prima prova approvata, con movimento del vino e riflesso neutro. Non contiene i prototipi di riempimento o oscillazione amplificata.

Node >=22.13, dipendenza di sola authoring `@clayzo/animation@0.3.21`, nessuna dipendenza npm nel sito PHP:

```sh
cd tools/hero-animation
npm install --ignore-scripts
npm run build
```

Il build valida e rigenera `theme/fisar-cdj/assets/animations/hero-wine.json`. La revisione approvata è `sha256:8088855b45183fb9b1ca08b6b6295a139153d7cf5189cd8e2dab7f6a8dcaa43a`. Il player riceve a runtime i byte dell’immagine nativa della Home; l’URI relativo nel documento non viene richiesto.

Le coordinate dell’effetto corrispondono al calice v2 approvato (640×960), con il vino nella parte inferiore della coppa. Se l’editor sostituisce l’illustrazione, verificare l’allineamento o disattivare con `fisar_cdj_hero_wine_enabled`. A/B restano indipendenti dal vino.

Player WebGL 0.3.21 originale in `assets/js/vendor/clayzo/`, con licenze e avvisi preservati. Non occorre rieseguire la generazione o installare la CLI sul server. Non distribuire questa cartella di strumenti nel tema.
