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

**Fase 2: Miglioramento dell'Esperienza Utente con AJAX**

1.  **Implementazione Base di AJAX:**
    *   Creare gli endpoint AJAX in WordPress per creare, modificare ed eliminare gli appuntamenti.
    *   Aggiungere i nonce AJAX per la sicurezza.
    *   Creare un file JavaScript per gestire le chiamate AJAX dal calendario.
    *   Localizzare lo script per passare in modo sicuro l'URL di AJAX e i nonce.
    *   **Commit:** `feat: Implement AJAX for appointment creation, update and deletion`

2.  **Migliorare l'Interfaccia del Calendario:**
    *   Al click su un evento, aprire un modal con i dettagli dell'appuntamento e i pulsanti per modificare o eliminare.
    *   Implementare la funzionalità di drag & drop per modificare la data e l'ora di un appuntamento.
    *   **Commit:** `feat: Add modal for event details and drag-and-drop rescheduling`

**Fase 3: Estensioni Funzionali**

1.  **Aggiungere lo Stato dell'Appuntamento:**
    *   Registrare una tassonomia personalizzata "Stato Appuntamento".
    *   Aggiungere un selettore per lo stato nel metabox.
    *   Visualizzare gli appuntamenti con colori diversi sul calendario in base allo stato.
    *   **Commit:** `feat: Add appointment status taxonomy and color-coded events`

2.  **Aggiungere Campi Cliente e Animale:**
    *   Aggiungere nuovi campi al metabox per il nome del cliente, l'email, il telefono e per il nome e la specie dell'animale.
    *   Salvare questi dati come metadati del post.
    *   Visualizzare queste informazioni nel modal dei dettagli dell'appuntamento.
    *   **Commit:** `feat: Add client and pet information fields to appointments`

**Fase 4: Finalizzazione e Documentazione**

1.  **Revisione Finale e Pulizia:**
    *   Rivedere l'intero codebase per coerenza, leggibilità e aderenza agli standard di WordPress.
    *   Testare a fondo tutte le funzionalità.
    *   **Commit:** `chore: Final code review and cleanup`

2.  **Aggiornamento Documentazione:**
    *   Aggiornare il file `README.md` con le istruzioni dettagliate sull'installazione, la configurazione e l'uso di tutte le nuove funzionalità.
    *   Aggiungere una sezione "Changelog".
    *   **Commit:** `docs: Update README.md with complete documentation`
