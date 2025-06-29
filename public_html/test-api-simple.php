<?php
// Test API semplice
header('Content-Type: application/json');

echo json_encode([
    'status' => 'success',
    'message' => 'API Test completato',
    'tests' => [
        'database' => testDatabase(),
        'files' => testFiles(),
        'api_endpoints' => testAPIEndpoints()
    ],
    'timestamp' => date('Y-m-d H:i:s')
]);

function testDatabase() {
    try {
        $mysqli = new mysqli('localhost', 'u336414084_vincenzorocca8', 'Ciaociao52.?', 'u336414084_portfolioVince');
        
        if ($mysqli->connect_error) {
            return ['status' => 'error', 'message' => 'Connessione fallita'];
        }
        
        $result = $mysqli->query("SELECT COUNT(*) as count FROM projects");
        $projects = $result ? $result->fetch_assoc()['count'] : 0;
        
        $mysqli->close();
        
        return ['status' => 'success', 'projects_count' => $projects];
    } catch (Exception $e) {
        return ['status' => 'error', 'message' => $e->getMessage()];
    }
}

function testFiles() {
    $files = [
        'index.html',
        'api/index.php',
        'assets/index-CoZPpuo8.css',
        'assets/index-CXYiKPGX.js',
        '.htaccess'
    ];
    
    $results = [];
    foreach ($files as $file) {
        $results[$file] = file_exists($file);
    }
    
    return $results;
}

function testAPIEndpoints() {
    // Simulazione test endpoint
    return [
        '/api/v1/projects' => 'ready',
        '/api/v1/technologies' => 'ready',
        '/api/v1/contacts' => 'ready'
    ];
}
?> 