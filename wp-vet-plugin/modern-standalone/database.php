<?php

/**
 * Gestisce la connessione al database SQLite e ne restituisce l'istanza.
 *
 * @return PDO L'oggetto PDO per interagire con il database.
 */
function getDB()
{
    static $db = null; // Variabile statica per mantenere la connessione (singleton pattern)

    if ($db === null) {
        try {
            // Usa un percorso relativo per il file del database
            $db_path = __DIR__ . '/calendar.sqlite';
            
            $db = new PDO('sqlite:' . $db_path);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            // Crea la tabella solo se non esiste già
            $db->exec("CREATE TABLE IF NOT EXISTS appointments (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                title TEXT NOT NULL,
                start TEXT NOT NULL, -- Formato ISO 8601 YYYY-MM-DD HH:MM:SS
                end TEXT NOT NULL    -- Formato ISO 8601 YYYY-MM-DD HH:MM:SS
            )");

        } catch (PDOException $e) {
            // In un'applicazione reale, questo errore andrebbe loggato
            // e si dovrebbe mostrare un messaggio generico all'utente.
            header('HTTP/1.1 500 Internal Server Error');
            echo json_encode(['status' => 'error', 'message' => 'Errore di connessione al database: ' . $e->getMessage()]);
            exit(); // Termina lo script se la connessione fallisce
        }
    }

    return $db;
}
