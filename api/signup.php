<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: https://davalos.cs3680.com'); // i changed http://localhost:5173
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: POST');

require 'db.php';

$data = json_decode(file_get_contents('php://input'), true);

$firstName = $data['firstName'] ?? '';
$lastName = $data['lastName'] ?? '';
$username = $data['username'] ?? '';
$email = $data['email'] ?? '';
$password = $data['password'] ?? '';

if (!$firstName || !$lastName || !$username || !$email || !$password) {
    http_response_code(400);
    echo json_encode(['error' => 'All fields are required']);
    exit;
}

$passwordHash = password_hash($password, PASSWORD_BCRYPT);

try {
    $stmt = $pdo->prepare(
        'INSERT INTO users (first_name, last_name, username, email, password_hash)
        VALUES (?, ?, ?, ?, ?)'
    );
    $stmt->execute([$firstName, $lastName, $username, $email, $passwordHash]);

    http_response_code(201);
    echo json_encode(['message' => 'User created']);
} catch (PDOException $e) {
    if ($e->getCode() === '23000') {
        http_response_code(409);
        echo json_encode(['error' => 'Username or email already taken']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Server error']);
    }
}

?>