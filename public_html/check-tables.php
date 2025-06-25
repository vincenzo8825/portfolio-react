<?php
header('Content-Type: application/json');

// Database connection
$host = 'localhost';
$dbname = 'u336414084_portfolioVince';
$username = 'u336414084_vincenzorocca8';
$password = 'Ciaociao52.?';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Get projects table structure
    $stmt = $pdo->query("DESCRIBE projects");
    $projects_columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Get contacts table structure  
    $stmt = $pdo->query("DESCRIBE contacts");
    $contacts_columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Get users table structure
    $stmt = $pdo->query("DESCRIBE users");
    $users_columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'projects' => $projects_columns,
        'contacts' => $contacts_columns,
        'users' => $users_columns
    ], JSON_PRETTY_PRINT);
    
} catch(PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?> 