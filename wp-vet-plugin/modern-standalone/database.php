<?php

/**
 * Gestisce la connessione al database SQLite e ne restituisce l'istanza.
 */

// Carica la configurazione personalizzata se esiste, altrimenti usa valori di default.
if (file_exists(__DIR__ . '/config.php')) {
    require_once __DIR__ . '/config.php';
}

// Definisce un percorso di default per il database se non è specificato in config.php
if (!defined('DB_PATH')) {
    define('DB_PATH', __DIR__ . '/database.sqlite');
}

/**
 * Inizializza e restituisce l'istanza del database PDO.
 *
 * @return PDO L'oggetto PDO per interagire con il database.
 */
function getDB()
{
    static $db = null; // Mantiene la connessione (singleton)

    if ($db === null) {
        try {
            // Connessione al database utilizzando il percorso definito
            $db = new PDO('sqlite:' . DB_PATH);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            // Crea la tabella degli appuntamenti se non esiste già
            $db->exec("CREATE TABLE IF NOT EXISTS appointments (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                title TEXT NOT NULL,
                start TEXT NOT NULL, -- Formato ISO 8601 YYYY-MM-DD HH:MM:SS
                end TEXT NOT NULL    -- Formato ISO 8601 YYYY-MM-DD HH:MM:SS
            )");

        } catch (PDOException $e) {
            // Gestione degli errori di connessione
            header('HTTP/1.1 500 Internal Server Error');
            echo json_encode(['status' => 'error', 'message' => 'Errore di connessione al database.']);
            exit();
        }
    }

    return $db;
}
