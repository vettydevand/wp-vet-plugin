# Provisioning con OpenTofu (Terraform)

Questo documento fornisce una guida introduttiva su come utilizzare [OpenTofu](https://opentofu.org/) (un fork open-source di Terraform) per automatizzare il provisioning dell'infrastruttura necessaria per WP Vet Plugin.

L'esempio in questa cartella è volutamente generico per illustrare i concetti di base senza legarsi a un provider cloud specifico.

## Contenuto

- `main.tf`: Il file di configurazione principale di OpenTofu.
- `README.md`: Questo file.

## Concetto di Base

L'idea è di definire l'infrastruttura (macchine virtuali, reti, regole firewall, etc.) come codice. OpenTofu legge questi file di configurazione e interagisce con le API del provider cloud per creare le risorse desiderate.

In un scenario reale, il file `main.tf` dovrebbe contenere:

1.  **La configurazione del Provider:** Le credenziali e le impostazioni per connettersi al tuo account cloud (AWS, GCP, Azure, etc.).
2.  **La definizione delle Risorse:** Ad esempio, una risorsa `aws_instance` per creare una VM su AWS.
3.  **L'integrazione con `cloud-init`:** Durante la creazione della VM, è possibile passare lo script `user-data.yaml` (dalla cartella `iac/cloud-init`) per configurare automaticamente il software all'interno della macchina.

### Esempio di codice per un provider reale (es. AWS)

```terraform
# Esempio puramente illustrativo
provider "aws" {
  region = "eu-west-1"
}

resource "aws_instance" "web_server" {
  ami           = "ami-0abcdef1234567890" # ID di una AMI Ubuntu
  instance_type = "t2.micro"

  # Qui passiamo lo script cloud-init per l'installazione automatica!
  user_data = file("${path.module}/../cloud-init/user-data.yaml")

  tags = {
    Name = "WP-Vet-Plugin-Server"
  }
}
```

## Come si usa (Esempio Simulata)

L'esempio fornito in `main.tf` non crea vere risorse cloud, ma simula il processo per aiutarti a capire il workflow di OpenTofu/Terraform.

1.  **Installa OpenTofu:** Segui la [guida ufficiale di OpenTofu](https://opentofu.org/docs/intro/install/) per installare l'eseguibile `tofu`.

2.  **Inizializza il progetto:** Apri un terminale nella cartella `iac/opentofu` ed esegui:
    ```bash
    tofu init
    ```
    Questo comando scarica i provider necessari (in questo caso, il provider `null`).

3.  **Visualizza il piano di esecuzione:** Esegui:
    ```bash
    tofu plan
    ```
    Tofu ti mostrerà quali azioni intende intraprendere (in questo caso, creare una `null_resource`).

4.  **Applica la configurazione:** Per creare effettivamente le risorse, esegui:
    ```bash
    tofu apply
    ```
    Ti verrà chiesta una conferma. Scrivi `yes` e premi Invio.

5.  **Distruggi le risorse:** Per eliminare le risorse create da Tofu, esegui:
    ```bash
    tofu destroy
    ```

Questo workflow di base (`init`, `plan`, `apply`) è lo stesso che useresti per gestire infrastrutture complesse su qualsiasi provider cloud.
