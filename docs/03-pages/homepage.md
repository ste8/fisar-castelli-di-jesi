# Homepage
## Struttura
1. Top bar + Header
2. Hero
3. Prossimi Eventi
4. I nostri Corsi
5. Ultime News
6. Carta dei Valori
7. Come seguirci
8. Footer

## Hero
Aggiornamento approvato il 4 ottobre 2026; prevale sul concept iniziale per copy e gerarchia della hero.

- H1 ben visibile: **FISAR / Delegazione / Castelli di Jesi**, su tre righe, in Poppins `400`.
- Payoff secondario, più piccolo e in Cormorant Garamond `600`: **Il vino come punto di partenza, le persone al centro.**
- Descrizione: **Corsi per sommelier, degustazioni e incontri per conoscere il mondo del vino.**
- Payoff e descrizione hanno più respiro orizzontale: larghezza massima rispettivamente `34rem` e `40rem`, sempre limitata allo spazio disponibile. I testi occupano una riga quando possibile; `text-wrap: balance` equilibra le righe alle larghezze inferiori senza interruzioni forzate o `nowrap`. H1, fotografia e CTA restano invariati.
- Le due frasi del payoff sono raggruppate per favorire l’andata a capo dopo la virgola; ogni gruppo può comunque andare a capo internamente se lo spazio o lo zoom lo richiedono.
- CTA: **Scopri i corsi** · **Scopri gli eventi**.
- Fotografia calda, preferibilmente reale, con overlay scuro per la leggibilità. Conservata l’immagine attuale sostituibile dall’editor.
- Nessuna fascia istituzionale sotto la hero: seguono direttamente le proposte di Eventi e Corsi. Nome esteso della FISAR, qualifica APS e rapporto con la Delegazione sono raccontati nella sezione `Chi siamo`, senza duplicare il testo in homepage.

Non compare “autonoma” nel titolo. Le altre sezioni della homepage restano invariate.

## Titoli — Poppins Medium

Approvati il 4 ottobre 2026 dopo la prova sul sito: i titoli di sezione Eventi, Corsi, News, Valori e Come seguirci usano Poppins `500` con `clamp(2rem, 4vw, 2.5rem)`, massimo 40 px. Conservati interlinea, tracking, colori e linee decorative.

La successiva prova estende Poppins `500` ai titoli delle card Eventi, Corsi e News, ai canali, al pannello newsletter e alle quattro voci della fascia Valori. Le card conservano la dimensione di `1.375rem`, con interlinea `1.3` per dare respiro al sans-serif; nessun troncamento dei titoli. Hero e payoff restano invariati. La tipografia dei componenti condivisi è uniforme anche negli archivi e nella pagina Seguici; gli heading editoriali delle pagine interne non cambiano. Il font Medium è già self-hosted nel tema; nessun nuovo asset o font remoto.

Per contenere le voci Valori con il nuovo font, la loro griglia resta su una colonna sotto `38rem`, due colonne alle larghezze intermedie e quattro da `80rem`. Padding orizzontale più compatto nei quattro elementi affiancati; wrapping di sicurezza per parole lunghe, senza testo nascosto.

## Percorsi di accesso

Dal 4 ottobre 2026 le Quattro Porte sono rimosse, senza un blocco sostitutivo: duplicavano percorsi già presenti nella hero, nella navigazione e nelle sezioni con contenuti concreti. La decisione prevale sul concept iniziale. Restano le CTA verso Eventi e Corsi, `Unisciti a noi` nell’header e la fascia Carta dei Valori.

Prossimi Eventi: futuri ASC. Corsi: attivi. News: ultime pubblicazioni.

## Come seguirci

Sezione dedicata dopo la Carta dei Valori e prima del footer, come approvato il 4 ottobre 2026. L’intero blocco, incluso il form newsletter, è spostato senza modificarne contenuto o stile: le attività concrete precedono l’invito a mantenere il contatto. La voce `Seguici` nel menu e i collegamenti social in top bar e footer restano invariati. Presenta quattro canali con CTA esplicite:

- Canale WhatsApp;
- Instagram;
- Facebook;
- Newsletter Mailchimp.

Le CTA social usano gli URL del menu WordPress `social`. La newsletter usa un form HTML integrato nello stile del tema e invia nome, cognome ed email direttamente a Mailchimp, senza salvare i dati in WordPress e senza caricare CSS o JavaScript remoti. La sezione rimanda anche alla pagina dedicata `Come seguirci` all’URL `/seguici/`.
