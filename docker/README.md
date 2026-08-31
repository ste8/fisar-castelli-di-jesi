# Ambiente Docker

Lo stack locale è definito in `compose.yaml` e comprende:

- WordPress 7.1.0 su PHP 8.3 e Apache;
- MariaDB 11.4;
- WP-CLI 2.12 one-shot per installazione, lingua italiana, attivazione e dati demo;
- volumi persistenti per database e core/uploads WordPress;
- bind mount read-only di plugin e tema dal repository.

Configurazione: copia `.env.example` in `.env`. Per avvio, log, stop e reset usa i comandi nel README principale.
