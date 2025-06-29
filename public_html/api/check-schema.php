<?php
// Script per verificare la struttura del database

// Database configuration - Hostinger MySQL
$dbConfig = [
    'host' => 'localhost',
    'dbname' => 'u336414084_portfolioVince',
    'username' => 'u336414084_vincenzorocca8',
    'password' => 'Ciaociao52.?',
    'charset' => 'utf8mb4'
];

try {
    $dsn = "mysql:host={$dbConfig['host']};dbname={$dbConfig['dbname']};charset={$dbConfig['charset']}";
    $pdo = new PDO($dsn, $dbConfig['username'], $dbConfig['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ]);

    echo "✅ Connessione database riuscita\n\n";

    // Check projects table structure
    echo "📋 STRUTTURA TABELLA PROJECTS:\n";
    echo "==============================\n";
    $stmt = $pdo->prepare("DESCRIBE projects");
    $stmt->execute();
    $columns = $stmt->fetchAll();

    foreach ($columns as $column) {
        echo "- {$column['Field']} ({$column['Type']}) {$column['Null']} {$column['Key']}\n";
    }

    echo "\n📋 STRUTTURA TABELLA TECHNOLOGIES:\n";
    echo "==================================\n";
    $stmt = $pdo->prepare("DESCRIBE technologies");
    $stmt->execute();
    $columns = $stmt->fetchAll();

    foreach ($columns as $column) {
        echo "- {$column['Field']} ({$column['Type']}) {$column['Null']} {$column['Key']}\n";
    }

    echo "\n📊 CONTEGGIO RECORD:\n";
    echo "===================\n";

    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM projects");
    $stmt->execute();
    $count = $stmt->fetch();
    echo "- Progetti: {$count['count']}\n";

    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM technologies");
    $stmt->execute();
    $count = $stmt->fetch();
    echo "- Tecnologie: {$count['count']}\n";

    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM users");
    $stmt->execute();
    $count = $stmt->fetch();
    echo "- Utenti: {$count['count']}\n";

} catch (Exception $e) {
    echo "❌ Errore: " . $e->getMessage() . "\n";
}
?>
