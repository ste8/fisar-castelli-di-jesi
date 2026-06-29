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
- Modalità
- Partecipazione
- Costi
- Iscrizioni
- Termine prenotazioni
- Warning posti limitati
- Corso collegato

## Regola evento concluso

Un evento è concluso quando:

```txt
data_evento < oggi
```
