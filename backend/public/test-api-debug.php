<?php
/**
 * 🔧 DEBUG API LARAVEL HOSTINGER
 *
 * Test specifico per verificare le API Laravel su Hostinger
 * ELIMINA DOPO I TEST!
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Gestisci OPTIONS per CORS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$results = [];

// 1. Test connessione database
try {
    $host = 'localhost';
    $dbname = 'u336414084_portfolioVince';
    $username = 'u336414084_vincenzorocca8';
    $password = 'Ciaociao52.?';

    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $results['database'] = [
        'status' => 'OK',
        'message' => 'Connessione database riuscita'
    ];

    // Test tabelle
    $stmt = $pdo->query('SHOW TABLES');
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $results['tables'] = $tables;

    // Test dati
    if (in_array('technologies', $tables)) {
        $stmt = $pdo->query('SELECT COUNT(*) as count FROM technologies');
        $count = $stmt->fetch();
        $results['technologies_count'] = $count['count'];
    }

    if (in_array('projects', $tables)) {
        $stmt = $pdo->query('SELECT COUNT(*) as count FROM projects');
        $count = $stmt->fetch();
        $results['projects_count'] = $count['count'];
    }

} catch (Exception $e) {
    $results['database'] = [
        'status' => 'ERROR',
        'message' => $e->getMessage()
    ];
}

// 2. Test Laravel bootstrap
try {
    $laravelPath = dirname(__DIR__);
    $results['laravel_path'] = $laravelPath;
    $results['laravel_files'] = [
        'bootstrap_exists' => file_exists($laravelPath . '/bootstrap/app.php'),
        'vendor_exists' => file_exists($laravelPath . '/vendor/autoload.php'),
        'env_exists' => file_exists($laravelPath . '/.env'),
        'artisan_exists' => file_exists($laravelPath . '/artisan')
    ];

    // Test .env
    if (file_exists($laravelPath . '/.env')) {
        $envContent = file_get_contents($laravelPath . '/.env');
        $results['env_check'] = [
            'has_app_key' => strpos($envContent, 'APP_KEY=') !== false,
            'has_db_config' => strpos($envContent, 'DB_DATABASE=') !== false,
            'has_mail_config' => strpos($envContent, 'MAIL_MAILER=') !== false
        ];
    }

} catch (Exception $e) {
    $results['laravel_bootstrap'] = [
        'status' => 'ERROR',
        'message' => $e->getMessage()
    ];
}

// 3. Test API endpoints diretti
$results['api_tests'] = [];

// Test diretto via curl interno
function testEndpoint($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Accept: application/json',
        'Content-Type: application/json'
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    return [
        'http_code' => $httpCode,
        'response' => $response,
        'error' => $error,
        'success' => $httpCode >= 200 && $httpCode < 300
    ];
}

$baseUrl = 'https://' . $_SERVER['HTTP_HOST'] . '/api/v1';

$endpoints = [
    'test' => $baseUrl . '/test',
    'technologies' => $baseUrl . '/technologies',
    'projects' => $baseUrl . '/projects'
];

foreach ($endpoints as $name => $url) {
    $results['api_tests'][$name] = testEndpoint($url);
}

// 4. Test sistema files
$results['file_system'] = [
    'upload_dir_exists' => is_dir($laravelPath . '/storage/app/public'),
    'upload_dir_writable' => is_writable($laravelPath . '/storage/app/public'),
    'logs_dir_writable' => is_writable($laravelPath . '/storage/logs'),
    'cache_dir_writable' => is_writable($laravelPath . '/bootstrap/cache')
];

// 5. Test PHP environment
$results['php_environment'] = [
    'version' => PHP_VERSION,
    'extensions' => [
        'pdo' => extension_loaded('pdo'),
        'pdo_mysql' => extension_loaded('pdo_mysql'),
        'curl' => extension_loaded('curl'),
        'gd' => extension_loaded('gd'),
        'openssl' => extension_loaded('openssl'),
        'mbstring' => extension_loaded('mbstring'),
        'zip' => extension_loaded('zip')
    ],
    'memory_limit' => ini_get('memory_limit'),
    'max_execution_time' => ini_get('max_execution_time'),
    'upload_max_filesize' => ini_get('upload_max_filesize')
];

// Timestamp e info server
$results['server_info'] = [
    'timestamp' => date('Y-m-d H:i:s'),
    'server' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
    'document_root' => $_SERVER['DOCUMENT_ROOT'] ?? 'Unknown',
    'script_name' => $_SERVER['SCRIPT_NAME'] ?? 'Unknown'
];

echo json_encode($results, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
?>
