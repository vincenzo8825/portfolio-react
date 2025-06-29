<?php
// Nuovo index.php per API - Reindirizza a Laravel senza intercettare

// Ottieni l'URI richiesto
$requestUri = $_SERVER['REQUEST_URI'] ?? '';
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

// Log della richiesta per debug
error_log("API Request: $method $requestUri");

// Se la richiesta è per /api/v1/*, reindirizza a Laravel
if (strpos($requestUri, '/api/v1/') === 0) {
    // Rimuovi /api dal percorso per Laravel
    $laravelPath = substr($requestUri, 4); // Rimuove "/api"
    
    // Reindirizza a Laravel public/index.php
    $_SERVER['REQUEST_URI'] = $laravelPath;
    $_SERVER['SCRIPT_NAME'] = '/api/public/index.php';
    $_SERVER['PHP_SELF'] = '/api/public/index.php';
    
    // Includi Laravel
    require_once __DIR__ . '/public/index.php';
    exit;
}

// Per altre richieste, restituisci 404
http_response_code(404);
echo json_encode([
    'error' => 'Endpoint not found',
    'message' => 'The requested API endpoint does not exist',
    'request_uri' => $requestUri,
    'method' => $method
]);
?> 