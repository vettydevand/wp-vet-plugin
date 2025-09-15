<?php
require_once 'database.php';

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $stmt = $db->query('SELECT * FROM appointments');
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        break;

    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        $stmt = $db->prepare('INSERT INTO appointments (title, start, end) VALUES (?, ?, ?)');
        $stmt->execute([$data['title'], $data['start'], $data['end']]);
        echo json_encode(['id' => $db->lastInsertId()]);
        break;

    case 'PUT':
        $data = json_decode(file_get_contents('php://input'), true);
        $stmt = $db->prepare('UPDATE appointments SET title = ?, start = ?, end = ? WHERE id = ?');
        $stmt->execute([$data['title'], $data['start'], $data['end'], $data['id']]);
        echo json_encode(['status' => 'success']);
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents('php://input'), true);
        $stmt = $db->prepare('DELETE FROM appointments WHERE id = ?');
        $stmt->execute([$data['id']]);
        echo json_encode(['status' => 'success']);
        break;
}
