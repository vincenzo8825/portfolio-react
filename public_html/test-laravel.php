<?php
header('Content-Type: application/json');

// Test per verificare se Laravel è accessibile
$tests = [
    'laravel_test' => [
        'url' => 'https://vincenzorocca.com/api/v1/test',
        'method' => 'GET'
    ],
    'laravel_auth_me' => [
        'url' => 'https://vincenzorocca.com/api/v1/auth/me',
        'method' => 'GET',
        'token' => 'test_token'
    ],
    'laravel_user' => [
        'url' => 'https://vincenzorocca.com/api/v1/user',
        'method' => 'GET',
        'token' => 'test_token'
    ]
];

$results = [];

foreach ($tests as $testName => $test) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $test['url']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $test['method']);
    
    $headers = ['Accept: application/json'];
    if (isset($test['token'])) {
        $headers[] = 'Authorization: Bearer ' . $test['token'];
    }
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
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