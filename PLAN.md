### Piano di Lavoro Dettagliato

Sulla base dell'analisi precedente, ho definito il seguente piano di lavoro, che seguirò passo passo, effettuando un commit Git dopo ogni passaggio significativo.

**Fase 1: Sicurezza e Refactoring del Codice**

1.  **Messa in Sicurezza del Salvataggio dei Dati:**
    *   Aggiungere un campo nonce al metabox dell'appuntamento.
    *   Verificare il nonce durante il salvataggio.
    *   Sanificare e validare tutti i dati in input.
    *   Aggiungere un controllo sulle autorizzazioni dell'utente (`current_user_can`).
    *   **Commit:** `secure: Add nonce verification and data sanitization to appointment metabox`

2.  **Refactoring della Logica del Calendario:**
    *   Spostare la logica di recupero degli appuntamenti in un metodo dedicato per poterla riutilizzare.
    *   Migliorare la leggibilità del codice che genera l'output JSON per FullCalendar.
    *   **Commit:** `refactor: Improve appointments data fetching for FullCalendar`

**Fase 2: Implementazione delle Funzionalità AJAX**

1.  **Creazione degli Endpoint AJAX:**
    *   Creare le azioni AJAX per creare, aggiornare ed eliminare gli appuntamenti.
    *   Implementare la logica di gestione dei dati nel backend.
    *   **Commit:** `feat: Add AJAX endpoints for appointment management`

2.  **Integrazione AJAX nel Frontend:**
    *   Modificare il file JavaScript per utilizzare gli endpoint AJAX invece di ricaricare la pagina.
    *   Assicurare che il calendario si aggiorni dinamicamente dopo ogni azione.
    *   **Commit:** `feat: Implement full AJAX functionality for calendar events`

**Fase 3: Ottimizzazione dell'Esperienza Utente e Feedback Visivo**

1.  **Aggiungere Notifiche e Feedback Visivo.**
    *   Sostituire gli `alert()` con notifiche più discrete (es. `toastr.js`).
    *   Introdurre un indicatore di caricamento durante le chiamate AJAX.
    *   **Commit:** `feat: Add visual feedback and notifications for calendar actions`

2.  **Migliorare l'Interfaccia Utente.**
    *   Affinare lo stile del calendario e aggiungere una legenda.
    *   **Commit:** `feat: Improve calendar UI and add legend`

**Fase 4: Miglioramento dell'Interfaccia e Ottimizzazione Mobile**

1.  **Sostituire `prompt` e `confirm` con un Modal.**
    *   Implementare un modal per la creazione, modifica ed eliminazione degli appuntamenti.
    *   **Commit:** `feat: Implement modal for creating and editing appointments`

2.  **Ottimizzazione per Dispositivi Mobili.**
    *   Migliorare la visualizzazione del calendario su schermi di piccole dimensioni.
    *   **Commit:** `fix: Improve calendar responsiveness for mobile devices`

**Fase 5: Integrazione con Servizi Esterni (Telegram)**

1.  **Pagina di Impostazioni per il Bot Telegram.**
    *   Creare una pagina di amministrazione per inserire il token API del bot e l'ID della chat.
    *   **Commit:** `feat: Add admin page for Telegram Bot settings`

2.  **Implementazione delle Notifiche Telegram.**
    *   Creare una funzione per inviare messaggi tramite l'API di Telegram.
    *   Inviare una notifica per ogni creazione, modifica o eliminazione di un appuntamento.
    *   **Commit:** `feat: Implement Telegram notifications for appointment changes`

**Fase 6: Finalizzazione e Documentazione**

1.  **Revisione Finale e Pulizia:**
    *   Rivedere l'intero codebase per coerenza e leggibilità.
    *   Testare a fondo tutte le funzionalità.
    *   **Commit:** `chore: Final code review and cleanup`

2.  **Aggiornamento Documentazione:**
    *   Aggiornare il file `README.md` con le istruzioni dettagliate.
    *   Aggiungere una sezione "Changelog".
    *   **Commit:** `docs: Update README.md with complete documentation`
