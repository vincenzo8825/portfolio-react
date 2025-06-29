<?php
// Test Laravel diretto senza interceptor
echo "<h1>🧪 TEST LARAVEL DIRETTO</h1>";
echo "<style>body{font-family:Arial;margin:20px;} .success{color:green;} .error{color:red;} .info{color:blue;}</style>";

$tests = [
    '/api/v1/test' => 'Test endpoint',
    '/api/v1/projects' => 'Lista progetti',
    '/api/v1/projects/featured' => 'Progetti featured', 
    '/api/v1/technologies' => 'Lista tecnologie',
    '/api/v1/auth/login' => 'Login endpoint (GET per test)',
];

foreach ($tests as $endpoint => $description) {
    echo "<h3>🔗 $description</h3>";
    echo "<div class='info'>URL: https://vincenzorocca.com$endpoint</div>";
    
    // Test tramite cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://vincenzorocca.com$endpoint");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_NOBODY, false);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Accept: application/json',
        'User-Agent: Laravel-Test-Client'
    ]);
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $content_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($error) {
        echo "<div class='error'>❌ cURL Error: $error</div>";
        continue;
    }
    
    $status = $http_code === 200 ? "✅" : "❌";
    echo "<div class='info'>$status HTTP $http_code - $content_type</div>";
    
    // Estrai il body dalla risposta
    $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $body = substr($response, $header_size);
    
    if ($http_code === 200 && strpos($content_type, 'application/json') !== false) {
        $json = json_decode($body, true);
        if ($json) {
            echo "<div class='success'>📝 JSON Response:</div>";
            echo "<pre style='background:#f0f0f0;padding:10px;font-size:12px;'>";
            echo htmlspecialchars(json_encode($json, JSON_PRETTY_PRINT));
            echo "</pre>";
        } else {
            echo "<div class='error'>❌ Invalid JSON Response</div>";
            echo "<pre style='background:#ffe6e6;padding:10px;font-size:12px;'>";
            echo htmlspecialchars(substr($body, 0, 500));
            echo "</pre>";
        }
    } else if ($http_code !== 200) {
        echo "<div class='error'>❌ Error Response:</div>";
        echo "<pre style='background:#ffe6e6;padding:10px;font-size:12px;'>";
        echo htmlspecialchars(substr($body, 0, 500));
        echo "</pre>";
    }
    
    echo "<br>";
}

// Test specifico progetti con debug
echo "<h2>🔍 DEBUG PROGETTI FEATURED</h2>";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://vincenzorocca.com/api/v1/projects/featured");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_NOBODY, false);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_VERBOSE, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: application/json',
    'Content-Type: application/json',
    'X-Requested-With: XMLHttpRequest'
]);

// Capture verbose output
$verbose = fopen('php://temp', 'w+');
curl_setopt($ch, CURLOPT_STDERR, $verbose);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

rewind($verbose);
$verbose_log = stream_get_contents($verbose);
fclose($verbose);
curl_close($ch);

echo "<div class='info'>🔗 Featured Projects Request:</div>";
echo "<div class='info'>HTTP Code: $http_code</div>";

if ($verbose_log) {
    echo "<details><summary>cURL Verbose Log</summary>";
    echo "<pre style='background:#f9f9f9;padding:10px;font-size:11px;'>";
    echo htmlspecialchars($verbose_log);
    echo "</pre></details>";
}

$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$body = substr($response, $header_size);

echo "<div class='info'>Response Body:</div>";
echo "<pre style='background:#f0f0f0;padding:10px;font-size:12px;'>";
echo htmlspecialchars(substr($body, 0, 1000));
echo "</pre>";

echo "<br><div class='success'>🎯 Se tutti i test sono ✅ allora Laravel funziona correttamente!</div>";
?> 