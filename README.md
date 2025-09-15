# WP Vet Plugin

**Contributors:** Gemini
**Tags:** calendar, appointments, events, telegram, fullcalendar
**Requires at least:** 5.0
**Tested up to:** 6.5
**Stable tag:** 1.0.0
**License:** GPLv2 or later
**License URI:** https://www.gnu.org/licenses/gpl-2.0.html

Un plugin per WordPress per la gestione di appuntamenti veterinari, visualizzati su un calendario interattivo e con notifiche in tempo reale tramite Telegram.

---

## Descrizione

**WP Vet Plugin** fornisce una soluzione completa e sicura per la gestione degli appuntamenti. Creato pensando agli standard moderni di WordPress, questo plugin permette di creare, modificare e cancellare appuntamenti direttamente da un calendario interattivo sul front-end del tuo sito.

Ogni modifica (creazione, aggiornamento, cancellazione) viene notificata in tempo reale su un canale Telegram a scelta, garantendo che lo staff sia sempre informato.

Il plugin è stato sviluppato con un'attenzione particolare alla **sicurezza**, all'**efficienza** e alla **facilità d'uso**, seguendo le best practice di sviluppo per WordPress.

---

## Funzionalità Principali

*   **Calendario Interattivo:** Basato sulla potente libreria [FullCalendar](https://fullcalendar.io/), permette di visualizzare e gestire gli appuntamenti con un'interfaccia drag-and-drop.
*   **Gestione Appuntamenti (CRUD):**
    *   **Crea:** Clicca su un giorno per aggiungere un nuovo appuntamento.
    *   **Aggiorna:** Trascina un appuntamento per cambiarne la data.
    *   **Modifica/Cancella:** Clicca su un appuntamento esistente per modificarne il titolo o per eliminarlo.
*   **Notifiche Telegram:** Integrazione nativa con Telegram per inviare notifiche immediate per ogni creazione, modifica o cancellazione di un appuntamento.
*   **Shortcode Semplice:** Inserisci il calendario in qualsiasi pagina o articolo con il semplice shortcode `[wp_vet_calendar]`.
*   **Sicuro e Affidabile:** Tutte le operazioni sono protette con nonce di WordPress, sanitizzazione dei dati e controlli sui permessi utente.
*   **Pagina Impostazioni:** Una pagina dedicata nella bacheca di WordPress per configurare facilmente il token del bot e l'ID della chat di Telegram.
*   **Custom Post Type:** Gli appuntamenti vengono salvati in un custom post type `appointment`, perfettamente integrato con l'ecosistema di WordPress.

---

## Installazione

1.  **Download:** Scarica il file `.zip` del plugin dalla pagina di release.
2.  **Caricamento:** Dalla bacheca di WordPress, vai su `Plugin > Aggiungi nuovo > Carica plugin`.
3.  **Selezione:** Seleziona il file `.zip` scaricato e clicca su `Installa ora`.
4.  **Attivazione:** Una volta completata l'installazione, clicca su `Attiva plugin`.

---

## Utilizzo

### 1. Configurare le Notifiche Telegram

Dopo l'attivazione, è necessario configurare il plugin per inviare le notifiche:

1.  Vai su `Impostazioni > WP Vet Plugin` nella bacheca di WordPress.
2.  Inserisci il **Token del Bot Telegram** e l'**ID della Chat** nei rispettivi campi.
    *   Per ottenere un **Token**, parla con [BotFather](https://t.me/botfather) su Telegram.
    *   Per ottenere un **Chat ID**, puoi usare un bot come [userinfobot](https://t.me/userinfobot).
3.  Clicca su `Salva le modifiche`.

### 2. Visualizzare il Calendario

Per mostrare il calendario degli appuntamenti sul tuo sito:

1.  Crea una nuova pagina (o modifica una esistente) andando su `Pagine > Aggiungi nuova`.
2.  Inserisci in un blocco di testo il seguente shortcode:
    ```
    [wp_vet_calendar]
    ```
3.  Pubblica o aggiorna la pagina. Il calendario interattivo sarà visibile visitando quella pagina.

---

## Sicurezza

La sicurezza è una priorità per questo plugin. Sono state implementate le seguenti misure:

*   **Nonce (Number used once):** Tutte le azioni AJAX (creazione, aggiornamento, cancellazione) sono protette da nonce per prevenire attacchi di tipo Cross-Site Request Forgery (CSRF).
*   **Sanitizzazione dell'Input e dell'Output:** Tutti i dati inviati e ricevuti vengono scrupolosamente sanitizzati e validati per prevenire attacchi XSS e SQL Injection.
*   **Controllo dei Permessi:** Le azioni sensibili (come la gestione degli appuntamenti) possono essere eseguite solo da utenti con i permessi adeguati (es. `edit_posts`).

---

## Struttura del Codice

Il plugin segue la struttura standard raccomandata per i plugin WordPress, con una chiara separazione delle responsabilità:

*   `/public`: Gestisce la parte pubblica del plugin, inclusi shortcode, stili e script del frontend.
*   `/admin`: Gestisce la bacheca, la pagina delle impostazioni e le funzionalità amministrative.
*   `/includes`: Contiene la logica di base del plugin, le classi per l'attivazione/disattivazione e il loader per la gestione centralizzata degli hook.
*   `/assets`: Contiene le librerie di terze parti come FullCalendar, Toastr, ecc.
