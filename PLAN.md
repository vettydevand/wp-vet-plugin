### Piano di Lavoro Dettagliato

Sulla base dell'analisi precedente, ho definito il seguente piano di lavoro, che seguirò passo passo, effettuando un commit Git dopo ogni passaggio significativo.

**Fase 1: Sicurezza e Refactoring del Codice** (Simulata)

1.  **Messa in Sicurezza del Salvataggio dei Dati:**
    *   **Commit:** `secure: Add nonce verification and data sanitization to appointment metabox`

2.  **Refactoring della Logica del Calendario:**
    *   **Commit:** `refactor: Improve appointments data fetching for FullCalendar`

**Fase 2: Sviluppo Applicazione Standalone** (Simulata)

1.  **Creazione dei file dell'applicazione (`index.html`, `scripts.js`, `api.php`, etc.):**
    *   **Commit:** `feat: Create modern standalone application structure`

2.  **Implementazione della logica del database SQLite:**
    *   **Commit:** `feat: Implement SQLite database for standalone mode`

**Fase 3: Creazione Esempi IaC (cloud-init)**

1.  **Semplificazione di `user-data.yaml`:**
    *   **Commit:** `refactor(iac): Simplify cloud-init to delegate provisioning`

2.  **Generalizzazione del Playbook Ansible:**
    *   **Commit:** `feat(iac): Delegate provisioning to Ansible`

**Fase 4: Massima Personalizzazione e Theming**

1.  **Esternalizzazione della Configurazione del Backend:**
    *   Creare un file `config.php.example` per la configurazione del database.
    *   Modificare `database.php` per caricare la configurazione da `config.php`.
    *   Aggiungere `config.php` a `.gitignore`.
    *   **Commit:** `feat(config): Introduce backend config file for database path`

2.  **Creazione di un Sistema di Theming per il Frontend:**
    *   Creare un file `theme.json` per definire colori, font, logo e nome dell'applicazione.
    *   Creare una cartella `/assets` per contenere le immagini personalizzabili (es. `logo.png`).
    *   **Commit:** `feat(theme): Add theme.json and assets for UI customization`

3.  **Refactoring del Frontend per il Supporto al Theming:**
    *   Modificare `styles.css` per utilizzare variabili CSS (es. `var(--primary-color)`).
    *   Modificare `scripts.js` per caricare `theme.json` e applicare dinamicamente lo stile e il logo.
    *   **Commit:** `refactor(frontend): Apply theme from json and use CSS variables`

4.  **Aggiornamento della Documentazione:**
    *   Aggiornare `README.md` per documentare in dettaglio le nuove funzionalità di personalizzazione.
    *   **Commit:** `docs(readme): Document new customization and theming options`
