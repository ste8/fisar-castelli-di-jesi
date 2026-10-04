# Logo

## Stato

Approvato come direzione brand v1.0.

## File di riferimento

```text
assets/brand/brand-guideline-v1.png
assets/logo-original/logo-fisar-cdj-originale.png
```

## Decisioni approvate

- Mantenere il grappolo FISAR.
- Mantenere la linea tricolore.
- Rendere più leggibile “Castelli di Jesi”.
- Non includere nel logo la dicitura estesa “Federazione Italiana Sommelier Albergatori Ristoratori”.
- Prevedere varianti verticale, orizzontale, compatta e solo simbolo.

## Uso previsto

- Logo verticale: documenti, footer, usi istituzionali.
- Logo orizzontale: header del sito e layout orizzontali.
- Logo compatto: spazi ridotti.
- Solo simbolo: favicon, icone, avatar.

## Regole

- Non deformare.
- Non cambiare colori.
- Non ruotare.
- Non aggiungere effetti.
- Non rimuovere la linea tricolore dalle versioni complete.

## Variante per l’header — 4 ottobre 2026

Su richiesta dell’utente, il sito prova una variante orizzontale con il blocco testuale più grande rispetto al grappolo, dando particolare rilievo a “Castelli di Jesi”. Non cambia l’altezza dell’header o del logo visualizzato: aumenta moderatamente la larghezza occupata.

Asset: `theme/fisar-cdj/assets/images/logo-orizzontale-header.svg`, derivato dal precedente SVG senza rigenerazione AI. Geometria del grappolo, caratteri, pesi e colori restano quelli del logo esistente; testi e tricolore vengono ricomposti senza deformare i glifi. Non viene usato Poppins nel marchio.

La dicitura locale è leggermente accorciata per condividere i margini di FISAR e tricolore: larghezza di riferimento `1700` unità SVG, inizio `x=994`. Il testo usa `textLength` con `lengthAdjust="spacing"`, che regola gli spazi senza comprimere i glifi; dimensione appena ridotta da `205` a `195`. Simbolo, scritta FISAR e ingombro complessivo invariati.

L’originale `logo-orizzontale.svg` e gli asset in `assets/brand/` restano invariati, così come il logo nel footer. Un logo personalizzato impostato in WordPress mantiene la precedenza su entrambi i fallback del tema.
