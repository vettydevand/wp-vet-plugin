# Deployment con Ansible

Questo documento spiega come utilizzare il playbook Ansible fornito per configurare automaticamente un server per ospitare l'applicazione WP Vet Plugin (modalità standalone).

## Prerequisiti

1.  **Ansible Installato:** Devi avere Ansible installato sulla tua macchina locale. Consulta la [guida ufficiale di Ansible](https://docs.ansible.com/ansible/latest/installation_guide/intro_installation.html) per le istruzioni.
2.  **Server di Destinazione:** Un server (o una VM) con un sistema operativo basato su Debian/Ubuntu e accesso SSH tramite chiave pubblica. Il playbook è stato testato su Ubuntu 20.04.

## Contenuto

- `playbook.yml`: Il playbook principale che esegue tutte le operazioni di configurazione.
- `inventory.ini`: Un file di inventario di esempio dove devi specificare l'indirizzo IP del tuo server.
- `templates/nginx.conf.j2`: Un template Jinja2 per la configurazione del virtual host di Nginx.

## Come si usa

1.  **Configura l'inventario:**
    Apri il file `inventory.ini` e sostituisci `_REPLACE_WITH_YOUR_SERVER_IP_` con l'indirizzo IP pubblico del tuo server. Assicurati che l'utente specificato (es. `ansible_user=root`) abbia i permessi necessari per installare pacchetti e gestire servizi.

2.  **Esegui il Playbook:**
    Apri un terminale nella cartella `iac/ansible` e lancia il playbook con il seguente comando:

    ```bash
    ansible-playbook -i inventory.ini playbook.yml
    ```

3.  **Verifica il risultato:**
    Ansible si connetterà al server ed eseguirà tutti i task definiti nel playbook. Se tutto va a buon fine, al termine del processo l'applicazione sarà installata, configurata e accessibile tramite l'indirizzo IP del server.

## Cosa fa il playbook?

Il playbook esegue i seguenti passaggi:

1.  **Installa le dipendenze:** Aggiorna il sistema e installa `nginx`, `php-fpm`, `php-sqlite3` e `git`.
2.  **Clona il repository:** Scarica l'ultima versione del codice sorgente da GitHub nella cartella `/var/www/wp-vet-plugin`.
3.  **Configura Nginx:** Utilizza il template `nginx.conf.j2` per creare un virtual host che punti alla cartella `modern-standalone` dell'applicazione.
4.  **Abilita il sito** e rimuove la configurazione di default.
5.  **Imposta i permessi** corretti sulla cartella del progetto.
6.  **Riavvia Nginx** per applicare le modifiche.
