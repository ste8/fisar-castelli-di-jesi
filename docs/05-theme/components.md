# Componenti

## Header

Top bar + logo + menu + CTA.

## Come seguirci

Quattro canali (WhatsApp, Instagram, Facebook e Newsletter) e form Mailchimp. In homepage segue la Carta dei Valori e precede il footer. Le precedenti Quattro Porte sono rimosse dal 4 ottobre 2026.

Prova v1.6.7: titoli dei canali e del pannello newsletter in Poppins Medium `500`, sia in home sia nella pagina Seguici.

## Card Evento

Mostra locandina, data, titolo, luogo, CTA.

Da v1.6.18 il contesto `archive` usa la stessa data compatta con calendario della home, mantenendo data estesa e titolo completo per screen reader. L’archivio conserva l’eventuale abstract; solo il contesto `home` lo omette. Cornice bianca quadrata con immagine intera, bordo e ombra leggeri; specifica in `docs/03-pages/events.md`.

## Card Corso

Mostra immagine, livello, titolo, descrizione, CTA.

## Tipografia dei titoli delle card

Prova v1.6.7: Eventi, Corsi e News usano Poppins `500`, interlinea `1.3`, mantenendo le dimensioni esistenti nei contesti compatti e completi. Titoli e nomi accessibili delle CTA rimangono completi.

## Fascia Carta dei Valori

Dal 5 ottobre 2026 presenta il punto 1 della Carta nell’introduzione e sei sintesi dei punti 2–7. Sostituisce le quattro categorie generiche con una lista di H3 e paragrafi allineati a sinistra, senza icone; Poppins `500` per i titoli. Una colonna su mobile e due da `38rem`, introduzione affiancata da `52rem`. Conservati fotografia di fondo, overlay e CTA alla pagina completa. Copy e corrispondenza con la bozza V2 in `docs/03-pages/homepage.md`.

La successiva prova richiesta dall’utente aggiunge a ogni voce un’icona SVG decorativa oro da `1.75rem`, nella colonna a sinistra del testo. Riutilizzato `fisar_cdj_theme_icon`: germoglio, bussola, scudo, fumetto, persone e lampadina; testo e responsive restano invariati.

## Newsletter

Presente nel footer e/o in sezioni dedicate.
