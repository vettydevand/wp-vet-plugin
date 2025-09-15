# Dockerfile per WP Vet Plugin (Standalone)

# --- Fase 1: Build ---
# Utilizziamo un'immagine PHP ufficiale con le dipendenze di build.
# In questo caso, il nostro progetto non ha dipendenze complesse (come Composer),
# quindi questa fase è molto semplice. Se avessimo dipendenze da installare,
# questo sarebbe il posto giusto per farlo.
FROM php:8.2-fpm-alpine as builder

# Copiamo i file dell'applicazione standalone.
WORKDIR /app
COPY ./wp-vet-plugin/modern-standalone/ .

# --- Fase 2: Prod ---
# Utilizziamo un'immagine Nginx leggera per la produzione.
FROM nginx:1.25-alpine

# Copiamo la configurazione personalizzata di Nginx.
# Questa configurazione instrada le richieste PHP al servizio PHP-FPM.
COPY --from=builder /app/nginx.conf /etc/nginx/nginx.conf

# Copiamo i file dell'applicazione buildati nella fase precedente.
COPY --from=builder /app /usr/share/nginx/html

# Creiamo un container separato per PHP-FPM, che è una pratica migliore
# rispetto a installare PHP e Nginx nello stesso container.
# Questo Dockerfile si concentra solo sulla parte Nginx.
# Il servizio PHP-FPM verrà eseguito in un container separato,
# come definito nel file docker-compose.yml.

EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]
