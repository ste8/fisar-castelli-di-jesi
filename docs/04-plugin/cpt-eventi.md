# CPT Eventi

## Scopo

Rappresentare eventi, degustazioni, visite, serate di presentazione e attività puntuali.

## Campi principali

- Titolo
- Descrizione
- Immagine in evidenza
- Data evento
- Ora accoglienza
- Ora inizio
- Luogo
- Link Google Maps (facoltativo, senza mappa incorporata)
- Modalità
- Partecipazione
- Costi: quote Soci/Non soci, nota generale facoltativa e altre quote personalizzabili (etichetta, importo, nota facoltativa)
- Iscrizioni
- Termine prenotazioni
- Warning posti limitati
- Corso collegato

## Regola evento concluso

Un evento è concluso quando:

```txt
data_evento < oggi
```
