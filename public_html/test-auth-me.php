<?php
header('Content-Type: application/json');

// Test endpoint /auth/me
$token = $_GET['token'] ?? '';

if (empty($token)) {
    echo json_encode([
        'error' => 'Token richiesto. Usa: ?token=YOUR_TOKEN'
    ]);
    exit;
}

$url = 'https://vincenzorocca.com/api/v1/auth/me';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: application/json',
    'Content-Type: application/json',
    'Authorization: Bearer ' . $token
]);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

echo json_encode([
    'http_code' => $httpCode,
    'curl_error' => $error,
    'raw_response' => $response,
    'parsed_response' => json_decode($response, true),
    'test_info' => [
        'url' => $url,
        'token_provided' => !empty($token),
        'token_length' => strlen($token)
    ]
]);
?> 