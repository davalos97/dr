<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: http://davalos.cs3680.com');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: POST');

require_once __DIR__ . '/vendor/autoload.php'; //i added this
require 'config.php';
require 'db.php'; // this

use Firebase\JWT\JWT;

$tokenSecret = JWT_SECRET;

$data = json_decode(file_get_contents('php://input'), true);

$email = $data['email'] ?? '';
$password = $data['password'] ?? '';

if (!$email || !$password) {
    http_response_code(400);
    echo json_encode(['error' => 'Email and password are required!']);
    exit;
}

try {
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || !password_verify($password, $user['password_hash'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Invalid email or password']);
        exit;
    }
//This too
$payload = [
    'iss' => JWT_ISSUER,
    'aud' => JWT_AUDIENCE,
    'iat' => time(),
    'exp' => time() + JWT_EXPIRATION_SECONDS,
    'user_id' => $user['user_id'],
    'username' => $user['username'],
    'email' => $user['email']
];

    // Added this which generates JWT
    $token = JWT::encode($payload, $tokenSecret, 'HS256');

    echo json_encode([
        'message' => 'Login successful',
        'username' => $user['username'],
        'userId' => $user['user_id'],
        'token' => $token
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error']);
}
?>