<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

echo json_encode([
    'step' => 'Laravel Bootstrap Test',
    'timestamp' => date('Y-m-d H:i:s')
]);

// Test 1: Check if autoload exists
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    echo json_encode(['autoload' => 'EXISTS']);
} else {
    echo json_encode(['autoload' => 'MISSING']);
    exit();
}

// Test 2: Try to load autoload
try {
    require_once __DIR__ . '/vendor/autoload.php';
    echo json_encode(['autoload_require' => 'SUCCESS']);
} catch (Exception $e) {
    echo json_encode(['autoload_require' => 'FAILED', 'error' => $e->getMessage()]);
    exit();
}

// Test 3: Check if Laravel Application exists
if (class_exists('Illuminate\Foundation\Application')) {
    echo json_encode(['laravel_application' => 'EXISTS']);
} else {
    echo json_encode(['laravel_application' => 'MISSING']);
}

// Test 4: Check if bootstrap/app.php exists
if (file_exists(__DIR__ . '/bootstrap/app.php')) {
    echo json_encode(['bootstrap_app' => 'EXISTS']);
} else {
    echo json_encode(['bootstrap_app' => 'MISSING']);
}

// Test 5: Try to create Laravel app
try {
    $app = require_once __DIR__ . '/bootstrap/app.php';
    echo json_encode(['laravel_bootstrap' => 'SUCCESS', 'app_class' => get_class($app)]);
} catch (Exception $e) {
    echo json_encode(['laravel_bootstrap' => 'FAILED', 'error' => $e->getMessage()]);
}

echo json_encode(['test_complete' => true]);
?> 