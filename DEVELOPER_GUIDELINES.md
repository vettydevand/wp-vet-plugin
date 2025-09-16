# Linee Guida per lo Sviluppo di Gemini

Questo file serve come un insieme di regole e best practice da seguire durante il ciclo di sviluppo per garantire coerenza, qualità e per evitare errori comuni.

1.  **Analizza Prima di Agire:** Non eseguire mai azioni distruttive (come eliminare file) senza prima aver analizzato il loro contenuto e il loro scopo. Usa i file esistenti come fonte di informazione.

2.  **Mantieni il Piano Aggiornato:** Il file `PLAN.md` deve essere lo specchio fedele dello stato attuale del lavoro. Aggiornalo *immediatamente* dopo ogni azione significativa, e soprattutto se un passo del piano si rivela errato o necessita di modifiche. Ogni deviazione dal piano deve essere registrata.

3.  **Documentazione Incrociata:** Mantieni la documentazione coerente. Se modifichi un documento (es. `PLAN.md`), aggiorna anche gli altri documenti rilevanti (es. `DEVELOPER_GUIDELINES.md`) e viceversa. La documentazione deve essere fruibile e consistente in tutto il repository.

4.  **Pensa in Modo Atomico:** Ogni commit deve rappresentare una piccola modifica logica e completa. Non mescolare refactoring, nuove funzionalità e correzioni di bug in un unico commit.

5.  **In caso di Fallimento, Spiega e Correggi:** Se un'API o un comando fallisce, non ignorarlo. Spiega perché è fallito e qual è il piano alternativo per raggiungere l'obiettivo.

6.  **Verifica i Comandi:** Prima di eseguire un comando, specialmente uno con conseguenze importanti (es. `rm`, `git restore`), rileggi attentamente per assicurarti che sia corretto e completo.

7.  **Usa gli Strumenti Definiti:** Utilizza gli script e gli strumenti definiti nel `package.json` (es. `npm run build`, `npm run package`) per le attività comuni. Questo garantisce che tutti gli sviluppatori seguano lo stesso processo e che gli output siano consistenti.

8.  **Commit Frequenti e Descrittivi:** Al termine di ogni attività logica completata, esegui un commit. I messaggi di commit devono essere chiari e descrittivi. Questo crea una cronologia pulita e permette di tracciare facilmente le modifiche. Non lasciare mai il lavoro in uno stato non committato alla fine di una sessione.

9.  **Il Piano come Guida Continua:** Il `PLAN.md` è il tuo strumento di navigazione principale. Consultalo prima di iniziare qualsiasi attività e aggiornalo *continuamente* man mano che procedi. L'uso attivo e costante del piano previene deviazioni e garantisce che il lavoro rimanga allineato agli obiettivi.

10. **Proattività e Miglioramento Continuo:** Non limitarti a eseguire i compiti assegnati. Cerca attivamente opportunità per migliorare il codice, la documentazione e i processi. Proponi nuove idee nel `PLAN.md` e prendi l'iniziativa per implementarle.
