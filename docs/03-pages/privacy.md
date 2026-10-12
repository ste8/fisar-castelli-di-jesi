# Informativa del sito e della newsletter

## Sintesi approvata — 12 ottobre 2026

Su richiesta esplicita, `Navigazione e cookie` mantiene soltanto i primi due paragrafi, fino a `Questi dati non sono cookie e non vengono utilizzati dal sito per profilare i visitatori.`. Rimossi i tre successivi su cookie dell’area riservata, impostazioni/durate e servizi esterni; nessun testo sostitutivo. Ancora, introduzione, titolare e intera informativa newsletter invariati. Modifica editoriale con confronto esatto del contenuto e revisione recuperabile, file documentale allineato; nessun cambio a tema/plugin, form o redirect. Questa sintesi prevale sul testo esteso descritto nella revisione precedente sotto; restano le verifiche prima del lancio.

## Navigazione e cookie — plugin v1.3.29, 12 ottobre 2026

Approvata l’integrazione nella pagina privacy nativa, senza Cookie Policy separata o analytics. Introduzione estesa al sito; inserita la sezione `Navigazione e cookie` con ancora `navigazione-e-cookie`, dopo titolare/recapito e prima dei contenuti newsletter, conservati integralmente. Testo versionato nel file storico `privacy-newsletter.html`; blocchi nativi e stile invariati.

Verifica locale: nessun analytics/pixel, unico plugin attivo Core, commenti chiusi in tutti i contenuti pubblicati, nessun embed esterno trovato, nessun codice custom che scriva cookie/storage. Richieste anonime home/Seguici/Contatti/Blog senza `Set-Cookie`; schermata login con `wordpress_test_cookie`. Descritti cookie tecnici nativi per accesso/preferenze e lingua, non una lista inventata di cookie pubblici. Distinti dati tecnici ricevuti dal server e cookie; nessuna promessa di assenza di dati di navigazione o di conservazione esclusiva in UE.

Pagina Cookie Policy ID 11 messa in bozza senza cancellarne contenuto; sola voce ID 35 scollegata dal footer, senza eliminare il post-menu recuperabile. Vecchio URL con 301 alla sezione cookie della pagina scelta nell’opzione privacy WordPress; GET/HEAD e path esatto come gli altri redirect legacy. Seed non crea più la pagina né il link separati; nessun reimport e seed privacy genericamente dimostrativo conservato. Aggiornamento della privacy ID 10 con controllo hash e revisione precedente; titolo, slug, opzione privacy, link e consenso newsletter invariati.

Prima della pubblicazione verificare nuovamente hosting, log e tempi di conservazione effettivi, eventuali servizi di sicurezza/CDN, nuovi plugin/embed e cookie. L’attuale testo non certifica la futura configurazione dell’hosting e non completa automaticamente le verifiche Mailchimp sotto.

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

- [Garante: FAQ cookie, inclusione nella privacy senza banner per soli cookie tecnici](https://www.garanteprivacy.it/faq/Cookie).
- [WordPress: cookie nativi](https://developer.wordpress.org/advanced-administration/wordpress/cookies/).

- [Indicazioni del Garante](https://www.garanteprivacy.it/home/principi-fondamentali-del-trattamento).
- [Mailchimp: trasferimenti europei](https://mailchimp.com/help/mailchimp-european-data-transfers/).
- [Mailchimp: accordo sul trattamento](https://mailchimp.com/legal/data-processing-addendum/).
