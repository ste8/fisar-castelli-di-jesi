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

## Titoli di sezione — prova Poppins Medium

Su richiesta del 4 ottobre 2026, i titoli di Eventi, Corsi, News, Valori e Come seguirci usano Poppins `500` con `clamp(2rem, 4vw, 2.5rem)`, massimo 40 px. Conservati interlinea, tracking, colori e linee decorative. La prova riguarda solo questi cinque titoli della homepage: hero, payoff, titoli delle card e delle pagine interne restano invariati. Il font Medium è già self-hosted nel tema; nessun nuovo asset o font remoto.

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
