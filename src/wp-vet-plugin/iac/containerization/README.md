# Containerizzazione con Docker e Podman

Questa guida spiega come eseguire l'applicazione standalone `wp-vet-plugin` in un ambiente containerizzato utilizzando Docker o Podman. La containerizzazione garantisce che l'applicazione venga eseguita in un ambiente isolato, coerente e riproducibile, indipendentemente dal sistema operativo host.

## Prerequisiti

Assicurati di avere installato uno dei seguenti strumenti:

- **Docker:** [Guida all'installazione di Docker](https://docs.docker.com/get-docker/)
- **Podman:** [Guida all'installazione di Podman](https://podman.io/getting-started/installation) (con `podman-compose`)

La sintassi dei comandi per Podman è quasi identica a quella di Docker, quindi gli esempi seguenti possono essere facilmente adattati. Dove ci sono differenze, verranno mostrati entrambi i comandi.

## Struttura dei File

- `Dockerfile`: Le istruzioni per costruire un'immagine Nginx che servirà i file della nostra applicazione.
- `docker-compose.yml`: Un file per orchestrare l'avvio di due container: uno per il server web (Nginx) e uno per l'interprete PHP (PHP-FPM). Questo è il metodo consigliato per l'uso in sviluppo.
- `README.md`: Questo file.

## Avvio Rapido con Docker Compose o Podman Compose

Questo è il metodo più semplice e consigliato.

1.  **Apri un terminale** nella cartella `iac/containerization`.

2.  **Avvia i container** in background:

    *   **Con Docker:**
        ```bash
        docker-compose up -d
        ```

    *   **Con Podman:**
        ```bash
        podman-compose up -d
        ```

3.  **Fatto!** L'applicazione sarà accessibile all'indirizzo [http://localhost:8080](http://localhost:8080).

    Il database `calendar.sqlite` verrà creato e salvato in un volume Docker/Podman, quindi i tuoi dati saranno persistenti anche se i container vengono rimossi e ricreati.

4.  **Per fermare i container:**

    *   **Con Docker:**
        ```bash
        docker-compose down
        ```

    *   **Con Podman:**
        ```bash
        podman-compose down
        ```

## Uso Avanzato: Build e Run Manuale

Se preferisci non usare `docker-compose`, puoi costruire l'immagine ed eseguire i container manualmente.

### 1. Costruisci l'Immagine

Naviga nella root del progetto (`wp-vet-plugin`) e lancia il comando di build:

*   **Con Docker:**
    ```bash
    docker build -t wp-vet-plugin-web -f iac/containerization/Dockerfile .
    ```

*   **Con Podman:**
    ```bash
    podman build -t wp-vet-plugin-web -f iac/containerization/Dockerfile .
    ```

### 2. Crea una Rete

I container Nginx e PHP-FPM devono comunicare tra loro. Creiamo una rete per questo scopo.

*   **Con Docker:**
    ```bash
    docker network create vet-plugin-net
    ```

*   **Con Podman:**
    *Podman gestisce le reti in modo leggermente diverso, ma per compatibilità possiamo crearne una esplicitamente.*
    ```bash
    podman network create vet-plugin-net
    ```

### 3. Avvia il Container PHP-FPM

Avviamo prima il container PHP-FPM in background.

*   **Con Docker:**
    ```bash
    docker run -d --name php-fpm --network vet-plugin-net -v "$(pwd)/modern-standalone:/usr/share/nginx/html" php:8.2-fpm-alpine
    ```

*   **Con Podman:**
    ```bash
    podman run -d --name php-fpm --network vet-plugin-net -v "$(pwd)/modern-standalone:/usr/share/nginx/html:z" php:8.2-fpm-alpine
    ```
    *(Nota: `:z` è un flag di SELinux spesso necessario su sistemi come Fedora/CentOS per permettere al container di scrivere sul volume montato).*

### 4. Avvia il Container Nginx

Infine, avviamo il container Nginx, collegandolo alla rete e al container PHP-FPM.

*   **Con Docker:**
    ```bash
    docker run -d --name web -p 8080:80 --network vet-plugin-net --rm wp-vet-plugin-web
    ```

*   **Con Podman:**
    ```bash
    podman run -d --name web -p 8080:80 --network vet-plugin-net --rm wp-vet-plugin-web
    ```

L'applicazione sarà ora accessibile su [http://localhost:8080](http://localhost:8080).
