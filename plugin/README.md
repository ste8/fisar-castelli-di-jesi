# Plugin WordPress

Il plugin installabile è `fisar-cdj-core/`.

Responsabilità:

- CPT Eventi e Corsi;
- campi e metabox nativi;
- sanitizzazione e salvataggio;
- stati temporali e query pubbliche;
- relazione Evento → Corso;
- import TSV del calendario lezioni;
- preparazione e pubblicazione esplicita degli articoli su Facebook/Instagram tramite Buffer, con modalità prova iniziale;
- copie ottimizzate per il web e versioni JPEG social senza tagli, con cache e conservazione degli originali;
- comando WP-CLI idempotente `wp fisar-cdj demo install`.

Non contiene markup o regole di layout del frontend.

Regole correnti, API e confini del seed in [Architettura](../docs/architecture.md); compilazione dei campi nella [Guida editoriale](../docs/editorial-guide.md).

Configurazione, limiti e collaudo della [pubblicazione social](../docs/social-publishing.md).
