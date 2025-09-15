terraform {
  required_providers {
    # In questo esempio useremo un provider "null" per dimostrare il concetto.
    # Per un caso d'uso reale, dovresti usare il provider del tuo cloud:
    # ad esempio "aws", "google", "azurerm", etc.
    null = {
      source  = "hashicorp/null"
      version = "3.2.1"
    }
  }
}

# Esempio di risorsa: una risorsa "null" che esegue un provisioner locale.
# In un caso reale, questa sarebbe una risorsa come "aws_instance",
# "google_compute_instance", etc.
resource "null_resource" "server" {

  # Il provisioner "local-exec" esegue un comando sulla macchina dove viene eseguito Tofu.
  # Qui lo usiamo per simulare il passaggio dei dati utente a un'istanza.
  provisioner "local-exec" {
    command = "echo 'Risorsa creata. In un caso d'uso reale, il contenuto di user-data.yaml verrebbe passato all\'istanza cloud.'"
  }

  # In un provider cloud reale, la configurazione sarebbe simile a questa:
  /*
  user_data = file("${path.module}/../cloud-init/user-data.yaml")
  */
}

output "simulated_instance_id" {
  value = "Simulazione per la risorsa: ${null_resource.server.id}"
  description = "Un ID di esempio per mostrare come funzionano gli output."
}
