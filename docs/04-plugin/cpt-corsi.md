# CPT Corsi

## Scopo

Rappresentare corsi FISAR di 1°, 2° e 3° livello.

## Campi principali

- Titolo
- Descrizione
- Immagine in evidenza
- Direttore del Corso
- Livello
- Data inizio
- Data fine
- Luogo
- Link Google Maps facoltativo
- Calendario lezioni
- Iscrizioni
- Disponibilità: ordinaria, sold-out, sold-out con lista d’attesa
- Avviso posti limitati facoltativo
- Contatti WhatsApp ripetibili: nominativo facoltativo e numero
- Quota di partecipazione
- Tesseramento FISAR
- Cosa comprende il corso

## Corsi attivi

```txt
data_fine >= oggi
```

## Corsi conclusi

```txt
data_fine < oggi
```

## Disponibilità delle iscrizioni — plugin v1.3.13

Distinta dallo stato attivo/concluso. Ordine di precedenza: corso concluso → termine tassativo valido superato → disponibilità editoriale (ordinaria per default). La scadenza è inclusa nel fuso WordPress; termini flessibili, assenti o invalidi non chiudono automaticamente. Un corso attivo può quindi avere iscrizioni chiuse. La lista d’attesa usa i canali esistenti senza raccogliere dati nel sito; nessuna migrazione dei corsi già inseriti.
