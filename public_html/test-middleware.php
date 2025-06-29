<?php
header('Content-Type: application/json');

// Test vari endpoint API
$tests = [
    'routes_check' => [
        'url' => 'https://vincenzorocca.com/api/v1/test',
        'method' => 'GET',
        'headers' => ['Accept: application/json']
    ],
    'auth_me_no_token' => [
        'url' => 'https://vincenzorocca.com/api/v1/auth/me',
        'method' => 'GET', 
        'headers' => ['Accept: application/json']
    ],
    'user_endpoint' => [
        'url' => 'https://vincenzorocca.com/api/v1/user',
        'method' => 'GET',
        'headers' => ['Accept: application/json']
    ]
];

$results = [];

foreach ($tests as $testName => $test) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $test['url']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $test['headers']);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $test['method']);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    $results[$testName] = [
        'url' => $test['url'],
        'http_code' => $httpCode,
        'curl_error' => $error,
        'raw_response' => $response,
        'parsed_response' => json_decode($response, true)
    ];
}

echo json_encode($results, JSON_PRETTY_PRINT);
?>
