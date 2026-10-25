# Ambiente Docker

Lo stack locale è definito in `compose.yaml` e comprende:

- WordPress 7.1.3 su PHP 8.3 e Apache;
- MariaDB 11.4;
- WP-CLI 2.12 one-shot per installazione, lingua italiana, attivazione e dati demo;
- volumi persistenti per database e core/uploads WordPress;
- bind mount read-only di plugin e tema dal repository.

Per configurazione, avvio di ambienti nuovi o esistenti, comandi e verifiche seguire [Sviluppo locale](../docs/local-development.md). Non reimportare il seed per avviare l’ambiente esistente.

Il core installato vive nel volume persistente: cambiare il tag dell’immagine non aggiorna da solo un’installazione esistente. Dopo un backup completo, aggiornare il core con WordPress/WP-CLI e allineare il tag Compose alla versione verificata; non usare il reset dei volumi o il reimport demo per aggiornare.
