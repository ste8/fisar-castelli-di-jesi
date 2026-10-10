# Informativa newsletter

## Stile — tema v1.6.72

La pagina privacy nativa adotta la fascia bordeaux compatta degli archivi Eventi/Corsi/Blog, senza fotografia o eyebrow associativa. H1 Poppins 500 da 32–44 px con accento oro; testo centrale largo al massimo 48rem, corpo 16–17 px / 1.7, introduzione Poppins 400, H2 20–24 px / 500 e grassetti 600. Spaziatura più contenuta tra le sezioni, link e focus visibile preservati.

La classe di presentazione `privacy-page` viene assegnata da `page.php` tramite `is_privacy_policy()`, non tramite slug o ID cablato. Riutilizzate le regole tipografiche esistenti con pochi stili circoscritti: Carta dei Valori, Cookie Policy e altre pagine non cambiano. Contenuto editoriale, titolo, URL, database e configurazione Mailchimp invariati; nessun nuovo asset o script.

## Pubblicazione locale — 10 ottobre 2026

Su richiesta esplicita dell’utente, la pagina nativa `Privacy Policy` all’URL `/privacy-policy/` pubblica il testo proposto nella conversazione con ragione sociale, abbreviazione, sede, C.F., P.IVA ed email forniti dall’utente. Testo iniziale versionato in `privacy-newsletter.html`, con blocchi WordPress nativi. Dopo la pubblicazione, eventuali modifiche editoriali si gestiscono dalla pagina WordPress; non esiste una sincronizzazione automatica con questo file.

Ambito: newsletter, non informativa completa su ogni trattamento del sito/associazione. Titolo, slug, ID della pagina, menu, opzione privacy e link nei due moduli restano invariati. Sostituito soltanto il corpo dimostrativo, conservabile tramite revisioni native; nessun reimport demo o modifica al tema/plugin. Il seed generico conserva intenzionalmente la bozza per ambienti demo: non usare un reimport `--force` per aggiornare questa pagina reale.

## Verifiche ancora necessarie prima del lancio pubblico

La pubblicazione su localhost non equivale a una certificazione di conformità. Nessun accesso o modifica effettuato nell’account Mailchimp. Confermare:

- dati tecnici effettivamente raccolti all’iscrizione e prova del consenso;
- opt-in e messaggi di conferma dell’Audience;
- tracciamento aperture/clic e relativa descrizione, se utilizzato;
- attuazione dei criteri di conservazione/disiscrizione indicati e rapporto con il fornitore.

Non inserite dichiarazioni non verificate sull’assenza di tracking, su tempi fissi di cancellazione o sul trattamento esclusivo in UE. Cookie Policy e altri contenuti dimostrativi restano fuori da questo intervento.

## Riferimenti consultati

- [Indicazioni del Garante](https://www.garanteprivacy.it/home/principi-fondamentali-del-trattamento).
- [Mailchimp: trasferimenti europei](https://mailchimp.com/help/mailchimp-european-data-transfers/).
- [Mailchimp: accordo sul trattamento](https://mailchimp.com/legal/data-processing-addendum/).
