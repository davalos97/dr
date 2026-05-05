php 
<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: https://davalos.cs3680.com");
header("Access-Control-Allow-Credentials: true");

$summaryFile = __DIR__ . '/summary.txt';

if (file_exists($summaryFile)) {
    $summary = file_get_contents($summaryFile);
    echo json_encode(["summary" => $summary]);
} else {
    echo json_encode(["summary" => "No summary yet."]);
}