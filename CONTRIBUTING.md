# Come Contribuire a WP Vet Plugin

Grazie per il tuo interesse nel contribuire a WP Vet Plugin! Ogni contributo è benvenuto e apprezzato. Di seguito trovi le linee guida per partecipare al progetto.

---

## Tipi di Contributi

*   **Segnalazione di Bug:** Se trovi un problema, per favore, apri una issue dettagliata.
*   **Suggerimenti di Funzionalità:** Hai un'idea per migliorare il plugin? Apri una issue e descrivila.
*   **Pull Request:** Se vuoi contribuire direttamente con del codice, sei nel posto giusto.

---

## Linee Guida per i Contributi

### Segnalare un Bug

Prima di aprire una issue, per favore, controlla che non ne esista già una simile.

Quando segnali un bug, includi:

1.  **Versione di WordPress e del Plugin.**
2.  **Descrizione del Bug:** Cosa ti aspettavi che succedesse e cosa è successo invece?
3.  **Passi per Riprodurlo:** Una lista chiara dei passaggi per replicare il problema.
4.  **Eventuali Errori:** Screenshot o copia/incolla di qualsiasi errore visualizzato o presente nei log.

### Inviare una Pull Request (PR)

1.  **Fork del Repository:** Crea un fork del progetto sul tuo account GitHub.
2.  **Clona il Fork:** Clona il tuo fork in locale: `git clone https://github.com/TUO_USERNAME/wp-vet-plugin.git`
3.  **Crea un Branch:** Crea un nuovo branch per le tue modifiche: `git checkout -b feature/la-tua-nuova-funzionalita` o `fix/un-bug-specifico`.
4.  **Sviluppa:** Apporta le tue modifiche al codice. Assicurati di seguire gli standard di codifica di WordPress e di documentare il tuo codice con commenti PHPDoc/JSDoc.
5.  **Commit:** Esegui commit chiari e significativi.
    ```bash
    git commit -m "FEAT: Aggiunge una nuova e fantastica funzionalità"
    ```
6.  **Push:** Carica il tuo branch sul tuo fork: `git push origin NOME_DEL_TUO_BRANCH`.
7.  **Apri la Pull Request:** Apri una Pull Request dal tuo fork al repository principale.

---

## Standard di Codice

*   **PHP:** Segui i [WordPress PHP Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/php/).
*   **JavaScript:** Segui i [WordPress JavaScript Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/javascript/).
*   **Documentazione:** Commenta il tuo codice in modo appropriato per spiegare le parti complesse.

Grazie ancora per il tuo contributo!
