<?php
header("Content-Type: application/json");

// we actually need this so we prevent access to our sites
$secretKey = "ham12345678900987654321";
if ($_GET['key'] !== $secretKey) {
    http_response_code(403);
    echo json_encode(["error" => "Unauthorized"]);
    exit;
}

// reading the JSON from Gael Computer
$data    = json_decode(file_get_contents("php://input"), true);
$summary = $data['summary'];

if (!$summary) {
    http_response_code(400);
    echo json_encode(["error" => "No summary provided"]);
    exit;
}

//saved in a txt file. 
file_put_contents(__DIR__ . '/summary.txt', $summary);

echo json_encode(["success" => true, "summary" => $summary]);