<?php

require_once 'database.php';

// Imposta l'header per tutte le risposte
header('Content-Type: application/json');

// Funzione centrale per inviare risposte JSON e terminare lo script
function send_response($data, $statusCode = 200)
{
    http_response_code($statusCode);
    echo json_encode($data);
    exit();
}

// Funzione per validare una data in formato ISO 8601
function validate_iso_date($date_string)
{
    return (bool)preg_match('/^(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2}):(\d{2})$/', $date_string);
}

// Ottieni l'istanza del database
$db = getDB();

// Gestisci la richiesta in base al metodo HTTP
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        handleGet($db);
        break;
    case 'POST':
        handlePost($db);
        break;
    case 'PUT':
        handlePut($db);
        break;
    case 'DELETE':
        handleDelete($db);
        break;
    default:
        send_response(['status' => 'error', 'message' => 'Metodo non supportato'], 405);
        break;
}

/**
 * Gestisce le richieste GET per recuperare tutti gli appuntamenti.
 */
function handleGet(PDO $db)
{
    try {
        $stmt = $db->query('SELECT id, title, start, end FROM appointments');
        $appointments = $stmt->fetchAll();
        send_response($appointments);
    } catch (PDOException $e) {
        send_response(['status' => 'error', 'message' => 'Errore nel recupero degli appuntamenti.'], 500);
    }
}

/**
 * Gestisce le richieste POST per creare un nuovo appuntamento.
 */
function handlePost(PDO $db)
{
    $data = json_decode(file_get_contents('php://input'), true);

    // Validazione e Sanitizzazione
    $title = isset($data['title']) ? htmlspecialchars(trim($data['title']), ENT_QUOTES, 'UTF-8') : null;
    $start = isset($data['start']) && validate_iso_date($data['start']) ? $data['start'] : null;
    $end = isset($data['end']) && validate_iso_date($data['end']) ? $data['end'] : null;

    if (empty($title) || !$start || !$end) {
        send_response(['status' => 'error', 'message' => 'Dati non validi. Assicurati che titolo, inizio e fine siano corretti.'], 400);
    }

    try {
        $stmt = $db->prepare('INSERT INTO appointments (title, start, end) VALUES (?, ?, ?)');
        $stmt->execute([$title, $start, $end]);
        $newId = $db->lastInsertId();
        send_response(['status' => 'success', 'id' => $newId], 201);
    } catch (PDOException $e) {
        send_response(['status' => 'error', 'message' => 'Errore nella creazione dell\'appuntamento.'], 500);
    }
}

/**
 * Gestisce le richieste PUT per aggiornare un appuntamento esistente.
 */
function handlePut(PDO $db)
{
    $data = json_decode(file_get_contents('php://input'), true);

    // Validazione e Sanitizzazione
    $id = isset($data['id']) ? filter_var($data['id'], FILTER_VALIDATE_INT) : null;
    $title = isset($data['title']) ? htmlspecialchars(trim($data['title']), ENT_QUOTES, 'UTF-8') : null;
    $start = isset($data['start']) && validate_iso_date($data['start']) ? $data['start'] : null;
    $end = isset($data['end']) && validate_iso_date($data['end']) ? $data['end'] : null;

    if (!$id || empty($title) || !$start || !$end) {
        send_response(['status' => 'error', 'message' => 'Dati non validi per l'aggiornamento.'], 400);
    }

    try {
        $stmt = $db->prepare('UPDATE appointments SET title = ?, start = ?, end = ? WHERE id = ?');
        $stmt->execute([$title, $start, $end, $id]);
        send_response(['status' => 'success', 'message' => 'Appuntamento aggiornato.']);
    } catch (PDOException $e) {
        send_response(['status' => 'error', 'message' => 'Errore nell\'aggiornamento dell\'appuntamento.'], 500);
    }
}

/**
 * Gestisce le richieste DELETE per eliminare un appuntamento.
 */
function handleDelete(PDO $db)
{
    $data = json_decode(file_get_contents('php://input'), true);

    // Validazione
    $id = isset($data['id']) ? filter_var($data['id'], FILTER_VALIDATE_INT) : null;

    if (!$id) {
        send_response(['status' => 'error', 'message' => 'ID appuntamento non valido o mancante.'], 400);
    }

    try {
        $stmt = $db->prepare('DELETE FROM appointments WHERE id = ?');
        $stmt->execute([$id]);
        if ($stmt->rowCount() === 0) {
            send_response(['status' => 'error', 'message' => 'Appuntamento non trovato.'], 404);
        }
        send_response(['status' => 'success', 'message' => 'Appuntamento eliminato.']);
    } catch (PDOException $e) {
        send_response(['status' => 'error', 'message' => 'Errore nell\'eliminazione dell\'appuntamento.'], 500);
    }
}
