<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: https://davalos.cs3680.com");
require_once(__DIR__ . '/loadENV.php');

//all cred
$servername = $_ENV['server'];
$username   = $_ENV['user'];
$password   = $_ENV['pass'];
$dbname     = $_ENV['name'];

$conn = mysqli_connect($servername, $username, $password, $dbname, 25060);

if (!$conn) {
    http_response_code(500);
    echo json_encode(["error" => mysqli_connect_error()]);
    exit;
}

$query = "SELECT content, user_id, sent_at FROM messages 
          WHERE chatroom_id = 1 
          ORDER BY message_id DESC 
          LIMIT 20";

$result = mysqli_query($conn, $query);
$messages = [];

while ($row = mysqli_fetch_assoc($result)) {
    $messages[] = $row;
}

// Reverse so oldest is first (more natural for summarization)
echo json_encode(array_reverse($messages));
mysqli_close($conn);