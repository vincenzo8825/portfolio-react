<?php
// Check table structure
$host = 'localhost';
$dbname = 'u336414084_portfolioVince';
$username = 'u336414084_vincenzorocca8';
$password = 'Ciaociao52.?';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Database connection: OK\n";
    
    // Check table structure
    $stmt = $pdo->prepare("DESCRIBE projects");
    $stmt->execute();
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Table 'projects' columns:\n";
    foreach ($columns as $column) {
        echo "- " . $column['Field'] . " (" . $column['Type'] . ")\n";
    }
    
    // Test basic insert
    echo "\nTesting basic insert...\n";
    $stmt = $pdo->prepare("INSERT INTO projects (title, slug, description, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())");
    $stmt->execute(['Test Basic', 'test-basic', 'Test description']);
    
    $id = $pdo->lastInsertId();
    echo "Insert successful, ID: $id\n";
    
    // Clean up
    $stmt = $pdo->prepare("DELETE FROM projects WHERE id = ?");
    $stmt->execute([$id]);
    echo "Cleanup successful\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?> 