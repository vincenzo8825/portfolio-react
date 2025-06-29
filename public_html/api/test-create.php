<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, PATCH, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$host = 'localhost';
$dbname = 'u336414084_portfolioVince';
$username = 'u336414084_vincenzorocca8';
$password = 'Ciaociao52.?';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // Show table structure
        $stmt = $pdo->prepare("DESCRIBE projects");
        $stmt->execute();
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $columnNames = array_map(function($col) { return $col['Field']; }, $columns);
        
        echo json_encode([
            'success' => true,
            'message' => 'Table structure',
            'columns' => $columnNames,
            'total_columns' => count($columnNames)
        ]);
        exit();
    }
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true) ?: [];
        
        // Test with minimal fields
        $title = $input['title'] ?? 'Test Project';
        $description = $input['description'] ?? 'Test description';
        $slug = strtolower(str_replace(' ', '-', $title));
        
        // Try insert with only basic fields
        $stmt = $pdo->prepare("
            INSERT INTO projects (title, slug, description, created_at, updated_at) 
            VALUES (?, ?, ?, NOW(), NOW())
        ");
        
        $stmt->execute([$title, $slug, $description]);
        $id = $pdo->lastInsertId();
        
        // Get the created project
        $stmt = $pdo->prepare("SELECT * FROM projects WHERE id = ?");
        $stmt->execute([$id]);
        $project = $stmt->fetch(PDO::FETCH_ASSOC);
        
        echo json_encode([
            'success' => true,
            'message' => 'Project created successfully',
            'id' => $id,
            'project' => $project
        ]);
        exit();
    }
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?> 