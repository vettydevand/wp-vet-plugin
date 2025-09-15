# Deployment con cloud-init

Questo documento spiega come utilizzare lo script `user-data.yaml` per automatizzare il deployment dell'applicazione WP Vet Plugin (in modalità standalone) su una nuova istanza di macchina virtuale presso un provider cloud che supporta cloud-init (es. AWS, Google Cloud, DigitalOcean, etc.).

## Che cos'è `user-data.yaml`?

`cloud-init` è lo standard de-facto per la personalizzazione di istanze cloud nelle fasi iniziali del boot. Il file `user-data.yaml` contiene le istruzioni che `cloud-init` eseguirà al primo avvio della macchina.

Questo specifico script esegue le seguenti azioni:

1.  **Aggiorna il sistema** e installa i pacchetti necessari: `nginx`, `php-fpm`, `php-sqlite3`, e `git`.
2.  **Clona il repository** del progetto dalla sua origine (attualmente punta a un esempio generico, da modificare con l'URL del tuo repository).
3.  **Configura il web server Nginx** per servire la cartella `modern-standalone` dell'applicazione.
4.  **Imposta i permessi** corretti sulla cartella del progetto per permettere al server web di operare.
5.  **Riavvia i servizi** `nginx` e `php-fpm` per rendere l'applicazione attiva.

## Come si usa

La procedura esatta varia a seconda del provider cloud, ma il concetto di base rimane lo stesso.

1.  **Crea una nuova macchina virtuale:** Durante il processo di creazione della VM (ad esempio, una EC2 su AWS o una Compute Engine su GCP), cerca una sezione chiamata "Dati utente" (User Data), "Configurazione avanzata" o simile.

2.  **Copia e incolla il contenuto:** Copia l'intero contenuto del file `user-data.yaml` e incollalo nel campo "Dati utente".

3.  **Avvia la VM:** Completa la configurazione della macchina (scegliendo un sistema operativo come Ubuntu 20.04 o Debian 10) e avviala.

4.  **Attendi il setup:** Al primo avvio, `cloud-init` eseguirà automaticamente tutti i comandi dello script. L'operazione potrebbe richiedere qualche minuto.

5.  **Accedi all'applicazione:** Una volta completato, potrai accedere al calendario semplicemente visitando l'indirizzo IP pubblico della macchina virtuale con il tuo browser.

### Nota Importante

Lo script `user-data.yaml` è configurato per clonare da un repository pubblico. Se il tuo repository è privato, dovrai modificare lo script per includere le credenziali di accesso o utilizzare un metodo di autenticazione più sicuro (come le chiavi di deploy).
