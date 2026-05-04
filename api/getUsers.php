<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: https://davalos.cs3680.com');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

require 'db.php';

try {
    $stmt = $pdo->prepare('SELECT user_id, username FROM users');
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($users);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error']);
}
?>