# FISAR Castelli di Jesi — sito WordPress

Sito della Delegazione FISAR Castelli di Jesi APS: Eventi, Corsi, Blog e pagine associative, con plugin e tema custom, editor nativo e ambiente Docker.

## Da dove partire

Questa è la documentazione corrente: descrive le regole da preservare, non la cronologia di tutte le prove grafiche. Per l’aspetto effettivo il riferimento è il sito approvato; in caso di discrepanze con le guide, segnalarle prima di reinterpretare o modificare il comportamento.

| Guida | Quando leggerla |
|---|---|
| [Architettura](docs/architecture.md) | Responsabilità, modello dati, API e regole funzionali |
| [Guida editoriale](docs/editorial-guide.md) | Gestione di pagine, Eventi, Corsi, Blog, gallerie e canali |
| [Sistema visivo](docs/design-system.md) | Stile approvato, tipografia, componenti, responsive e accessibilità |
| [Sviluppo locale](docs/local-development.md) | Docker, verifiche, import espliciti e backup |
| [Pubblicazione Aruba](docs/deployment-aruba.md) | Rilasci, protezione dei contenuti, rollback e checklist di lancio |
| [Decisioni](docs/decisions.md) | Scelte durevoli e motivazioni |
| [Changelog](docs/changelog.md) | Cambiamenti sostanziali, senza diario delle micro-revisioni |

Prima di intervenire: leggere questa pagina, controllare Git e gli ultimi commit, consultare le guide pertinenti ed esaminare l’implementazione. Preservare le modifiche esistenti, verificare in proporzione al rischio e aggiornare le guide quando cambia una regola. Nessun commit automatico.

## Codice e contenuti

```text
plugin/fisar-cdj-core/  CPT, campi, relazioni, import e logica funzionale
theme/fisar-cdj/        presentazione, template, componenti e frontend
docker/                 solo ambiente locale
docs/                   guide correnti e sorgenti testuali
assets/                 brand e archivio fotografico di progetto
tests/                  test automatici
```

Soluzioni WordPress native, senza page builder o framework frontend. La logica del plugin non va duplicata nel tema.

L’[anteprima Aruba](https://www.fisarcastellidijesi.it/anteprima/) è la **fonte principale dei contenuti**: non sovrascriverne il database con quello locale. È accessibile senza password frontend e impostata noindex, non privata; l’admin richiede login. Un aggiornamento del codice non deve alterare pagine, media o menu.

I frammenti in `plugin/fisar-cdj-core/content/` servono al seed, non sincronizzano il sito pubblicato. Anche le [sorgenti testuali](docs/sources/README.md) sono distinte dai contenuti WordPress.

## Avvio locale

Per l’ambiente già installato, con Docker attivo:

```sh
docker compose -f docker/compose.yaml up -d database wordpress
```

- [Sito locale](http://localhost:8090/)
- [Amministrazione locale](http://localhost:8090/wp-admin/)

Se si usa `docker/.env`, aggiungere `--env-file docker/.env` prima di `-f`. Per una **nuova installazione**, configurazione, credenziali e bootstrap demo seguire [Sviluppo locale](docs/local-development.md). Non reimportare il seed o eliminare volumi per aggiornare un ambiente esistente.

## Fotografie e materiali

L’[archivio della hero](assets/photography/sito-home/README.md) contiene tutti i **27 originali**, inventario, selezione corrente e **24 WebP** per quattro collage da sei foto. Non dipende più dai mockup: lo script di rigenerazione lavora all’interno dell’archivio. Il sito legge la galleria nativa della Home, non questi file direttamente.

I vecchi mockup e le specifiche superate sono stati rimossi dal worktree; restano recuperabili nella storia Git, prima della pulizia al commit `6ec7e27`. Conservati brand, sorgenti fotografiche e documenti istituzionali utili. Non caricare l’intero archivio sorgente sul server.

Backup, esportazioni e credenziali restano fuori da Git e dallo spazio web pubblico, nella cartella privata `.local-deployment/`. La checklist di lancio è nella guida Aruba: nessun documento separato di “Attività aperte”.
