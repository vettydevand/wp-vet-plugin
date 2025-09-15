# WP Vet Plugin

**Contributors:** Gemini
**Tags:** calendar, appointments, events, telegram, fullcalendar, veterinarian
**Requires at least:** 5.0
**Tested up to:** 6.5
**Stable tag:** 1.0.0
**License:** GPLv2 or later
**License URI:** https://www.gnu.org/licenses/gpl-2.0.html

Un plugin WordPress sicuro e moderno per la gestione di appuntamenti veterinari, con un calendario interattivo e notifiche in tempo reale tramite Telegram.

---

## Descrizione

**WP Vet Plugin** fornisce una soluzione completa per la gestione degli appuntamenti. Sviluppato seguendo le best practice di WordPress, permette di creare, modificare e cancellare appuntamenti direttamente da un calendario interattivo sul front-end del tuo sito. Ogni modifica viene notificata in tempo reale su un canale Telegram a scelta, mantenendo lo staff sempre aggiornato.

L'interfaccia è pensata per essere intuitiva: un clic su un giorno per creare un nuovo appuntamento, un clic su un evento per modificarlo, e il drag-and-drop per riprogrammarlo. La sicurezza è garantita da nonce, sanitizzazione dei dati e controllo dei permessi utente.

---

## Funzionalità Principali

*   **Calendario Interattivo:** Basato su **FullCalendar.js**, permette di visualizzare e gestire gli appuntamenti con un'interfaccia moderna.
*   **Gestione Appuntamenti (CRUD):**
    *   **Crea:** Clicca su un giorno per aprire un modale e inserire i dettagli: nome proprietario, nome animale e motivo della visita.
    *   **Modifica:** Clicca su un appuntamento per aggiornarne i dettagli.
    *   **Riprogramma:** Trascina un appuntamento su un altro giorno per cambiarne la data.
    *   **Elimina:** Cancella un appuntamento direttamente dal modale di modifica.
*   **Notifiche Telegram:** Integrazione nativa per inviare notifiche immediate per ogni azione sugli appuntamenti.
*   **Shortcode Semplice:** Inserisci il calendario in qualsiasi pagina con `[wp_vet_calendar]`.
*   **Sicuro e Affidabile:** Protezione contro CSRF (nonce), XSS (sanitizzazione) e accessi non autorizzati.
*   **Pagina Impostazioni:** Configura facilmente il token del bot e l'ID della chat di Telegram dalla bacheca.
*   **Custom Post Type:** Gli appuntamenti sono salvati come `appointment`, integrandosi nell'ecosistema WordPress.

---

## Installazione

1.  **Download:** Scarica il file `.zip` del plugin.
2.  **Caricamento:** Dalla bacheca di WordPress, vai su `Plugin > Aggiungi nuovo > Carica plugin`.
3.  **Selezione:** Seleziona il file `.zip` e clicca su `Installa ora`.
4.  **Attivazione:** Al termine dell'installazione, clicca su `Attiva plugin`.

---

## Utilizzo

### 1. Configurare le Notifiche Telegram

Per abilitare le notifiche, è necessario fornire le credenziali del tuo bot Telegram:

1.  Vai su `Impostazioni > WP Vet Plugin` nella bacheca di WordPress.
2.  Inserisci il **Token del Bot Telegram** e l'**ID della Chat**.
    *   Per creare un bot e ottenere un **Token**, contatta [BotFather](https://t.me/botfather) su Telegram.
    *   Per trovare il tuo **Chat ID**, puoi usare un bot di servizio come [userinfobot](https://t.me/userinfobot).
3.  Clicca su `Salva le modifiche`.

### 2. Visualizzare il Calendario

1.  Crea o modifica una pagina (`Pagine > Aggiungi nuova`).
2.  Inserisci in un blocco di testo lo shortcode: `[wp_vet_calendar]`
3.  Pubblica la pagina. Il calendario interattivo sarà visibile.

---

## Dettagli Tecnici

*   **Struttura Plugin:** Boilerplate standard di WordPress per garantire manutenibilità e scalabilità.
*   **Gestione Hook:** Un `Loader` centralizzato per registrare tutte le azioni e i filtri.
*   **Backend:** Custom Post Type (`appointment`) e Settings API per la pagina di configurazione.
*   **Frontend:** **FullCalendar.js** per il calendario, **Toastr.js** per le notifiche non invadenti e un modale personalizzato per l'inserimento dati.
*   **Comunicazione:** Le interazioni avvengono tramite la **WordPress AJAX API**, con risposte in formato JSON.

---

## Contribuire

Le contribuzioni sono benvenute! Se vuoi migliorare il plugin, leggi le nostre linee guida in `CONTRIBUTING.md`.

---

## Changelog

### 1.0.0 - 2024-07-25
*   **Initial Release**
*   Refactoring completo del codice, documentazione e hardening di sicurezza.
*   Implementazione calendario interattivo con operazioni CRUD.
*   Integrazione notifiche Telegram.
*   Creazione pagina impostazioni e shortcode per la visualizzazione.
