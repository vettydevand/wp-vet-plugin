# WP Vet Plugin: Calendario per Appuntamenti Veterinari

WP Vet Plugin è un calendario di appuntamenti flessibile e facile da usare, progettato per studi veterinari ma adattabile a qualsiasi esigenza di pianificazione. Offre un'interfaccia intuitiva basata su FullCalendar per la gestione di eventi.

Questo plugin può funzionare in due modalità: come un tradizionale **plugin di WordPress** o come un'**applicazione web standalone**, senza richiedere un'installazione di WordPress.

## Indice

- [Caratteristiche](#caratteristiche)
- [Modalità di Utilizzo](#modalità-di-utilizzo)
- [Installazione (Plugin WordPress)](#installazione-plugin-wordpress)
- [Installazione (Standalone)](#installazione-standalone)
- [Guida Rapida all'Uso](#guida-rapida-alluso)
- [Contribuire](#contribuire)

## Caratteristiche

- **Visualizzazione Multipla:** Calendario mensile, settimanale e giornaliero.
- **Gestione Eventi Drag & Drop:** Crea, sposta e ridimensiona gli appuntamenti direttamente dal calendario.
- **Modale Intuitiva:** Inserisci, modifica o elimina i dettagli dell'appuntamento con un semplice clic.
- **Backend Leggero:** Utilizza AJAX per le operazioni nel plugin WordPress e un backend PHP con SQLite per la versione standalone.
- **Indipendente:** La modalità standalone non richiede dipendenze esterne complesse, solo PHP e SQLite.

## Modalità di Utilizzo

1.  **Plugin WordPress:** Si integra perfettamente con la tua installazione WordPress esistente. Ideale per chi ha già un sito e vuole aggiungere funzionalità di calendario.
2.  **Standalone:** Un'applicazione web completa e indipendente. Perfetta per un uso interno o per chi non utilizza WordPress. Richiede un ambiente con PHP e l'estensione SQLite.

---

## Installazione (Plugin WordPress)

Segui questi passaggi per installare il plugin sul tuo sito WordPress.

1.  **Scarica il Plugin:** Scarica l'archivio `.zip` di questo repository.
2.  **Carica su WordPress:**
    - Accedi alla tua bacheca di WordPress.
    - Vai su `Plugin > Aggiungi nuovo`.
    - Clicca su `Carica plugin` in alto.
    - Seleziona il file `.zip` e clicca su `Installa ora`.
3.  **Attiva il Plugin:** Una volta installato, clicca su `Attiva plugin`.
4.  **Fatto!** Il calendario sarà disponibile in una nuova voce di menu nella tua bacheca.

---

## Installazione (Standalone)

Questa modalità non richiede WordPress. È sufficiente un server web con supporto a PHP e SQLite.

### Prerequisiti

- [PHP](https://www.php.net/manual/en/install.php) (versione 7.4 o successiva)
- Estensione [PHP SQLite3](https://www.php.net/manual/en/book.sqlite3.php) (solitamente inclusa nelle installazioni standard di PHP).

### Guida Rapida all'Avvio

1.  **Clona o Scarica il Repository:**
    ```bash
    git clone https://github.com/tuo-utente/wp-vet-plugin.git
    ```
    Oppure scarica e decomprimi lo zip.

2.  **Avvia il Server PHP:**
    Apri il terminale, naviga fino alla cartella `modern-standalone` e avvia il server di sviluppo integrato di PHP.
    ```bash
    cd percorso/del/progetto/wp-vet-plugin/modern-standalone/
    php -S localhost:8000
    ```

3.  **Apri il Calendario:**
    Apri il tuo browser e visita [http://localhost:8000](http://localhost:8000). Il database `calendar.sqlite` verrà creato automaticamente al primo utilizzo.

### Struttura dei File Standalone

- `index.html`: La pagina principale che ospita il calendario.
- `styles.css`: Stili per l'interfaccia e il modale.
- `scripts.js`: Logica del frontend per interagire con FullCalendar e le API.
- `api.php`: Gestisce le richieste (lettura, creazione, aggiornamento, cancellazione) dal frontend.
- `database.php`: Gestisce la connessione e l'inizializzazione del database SQLite.

---

## Guida Rapida all'Uso

L'interfaccia del calendario è progettata per essere il più intuitiva possibile.

- **Aggiungere un appuntamento:** Clicca su una data o un orario vuoto. Si aprirà una finestra modale dove potrai inserire i dettagli.
- **Modificare un appuntamento:** Clicca su un appuntamento esistente. La stessa finestra modale ti permetterà di cambiarne i dettagli.
- **Eliminare un appuntamento:** Clicca su un appuntamento e usa il pulsante "Elimina" all'interno della modale.
- **Spostare o Ridimensionare:** Trascina un appuntamento per spostarlo in un'altra data/ora o trascina i suoi bordi per cambiarne la durata.

## Contribuire

I contributi sono benvenuti! Se hai idee, suggerimenti o vuoi correggere un bug, sentiti libero di aprire una issue o una pull request su GitHub.
