<?php

//keys here
$secretKey  = "ham12345678900987654321";
$serverURL  = "https://davalos.cs3680.com/messages/get_messages.php?key=" . $secretKey;
$summaryURL  = "https://davalos.cs3680.com/messages/saving_summary.php?key=" . $secretKey;
$lmStudio   = "http://localhost:1234/v1/chat/completions";

//fetch messages using curl
while(true){
$ch0 = curl_init($serverURL);
curl_setopt($ch0, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch0, CURLOPT_SSL_VERIFYPEER, false);
$rawMessages = curl_exec($ch0);
$messages = json_decode($rawMessages, true);

//Format into a readable chat log
$chatLog = implode("\n", array_map(function($m) {
    return "[User {$m['user_id']} at {$m['sent_at']}]: {$m['content']}";
}, $messages));

//Only takes JSON format, so the request is built here and then sent to LM Studio
$payload = json_encode([
    "model"    => "qwen/qwen3-4b",  //model that I have on Gael's laptop
    "thinking" => false, //takes too long and not needed
    "messages" => [
        [
            "role"    => "system", //Larp
            "content" => "You are a helpful assistant that summarizes chat conversations concisely."
        ],
        [
            "role"    => "user",  //second Larp
            "content" => "Please summarize the following chat messages:\n\n" . $chatLog
        ]
    ],
    "temperature" => 0.5,
    "max_tokens" => 150
    //how random do you want the answer
    //ranges from 0 to 1
]);

//this Sends it to LMStudio, using CURL because that is what is supported.
$ch = curl_init($lmStudio);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);

$response = curl_exec($ch);

//pprint summary
$result  = json_decode($response, true);
if (isset($result['choices'][0]['message']['content'])) {
    $summary = $result['choices'][0]['message']['content'];
} else {
    $summary = "No summary returned.";
}

//will need to catch summary and display it to the right side of the website
$ch2 = curl_init($summaryURL);
curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch2, CURLOPT_POST, true);
curl_setopt($ch2, CURLOPT_POSTFIELDS, json_encode(["summary" => $summary]));
curl_setopt($ch2, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
curl_exec($ch2);

//final variable to present to the TLDR ai
echo "Summary Sent to main";

$response = curl_exec($ch);

// Add this temporarily
echo "Raw LM Studio response: " . $response . "\n";

$result = json_decode($response, true);

//if no sleep is enabled then it continoulsy runs, so we add sleep
//sleeps for 15+ minutes
sleep(1000);
}