<?php
header("Access-Control-Allow-Origin: https://www.cypherox.com");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$input = file_get_contents("php://input");

//$ch = curl_init("https://automation.cypherox.com/webhook/c4b3ac1d-802d-47e5-9713-b0eebc645295/chat");
$ch = curl_init("https://automation.cypherox.com/webhook/c4b3ac1d-802d-47e5-9713-b0eebc645295/chat");
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $input,
    CURLOPT_HTTPHEADER => [
        "Content-Type: application/json"
    ],
    CURLOPT_TIMEOUT => 30
]);

$response = curl_exec($ch);

if ($response === false) {
    http_response_code(500);
    echo json_encode([
        "error" => "Webhook connection failed",
        "details" => curl_error($ch)
    ]);
    exit;
}

curl_close($ch);
echo $response;
