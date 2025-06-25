<?php
header('Content-Type: text/html; charset=utf-8');

// Database connection
$host = 'localhost';
$dbname = 'u336414084_portfolioVince';
$username = 'u336414084_vincenzorocca8';
$password = 'Ciaociao52.?';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>🔍 Analisi Constraint Tabella Projects</h2>";
    
    // 1. Struttura tabella projects
    echo "<h3>📋 Struttura Tabella Projects:</h3>";
    $stmt = $pdo->query("DESCRIBE projects");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>Campo</th><th>Tipo</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    foreach ($columns as $col) {
        echo "<tr>";
        echo "<td>{$col['Field']}</td>";
        echo "<td>{$col['Type']}</td>";
        echo "<td>{$col['Null']}</td>";
        echo "<td>{$col['Key']}</td>";
        echo "<td>{$col['Default']}</td>";
        echo "<td>{$col['Extra']}</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // 2. Check constraints
    echo "<h3>🔒 Check Constraints:</h3>";
    $stmt = $pdo->query("
        SELECT CONSTRAINT_NAME, CHECK_CLAUSE 
        FROM INFORMATION_SCHEMA.CHECK_CONSTRAINTS 
        WHERE CONSTRAINT_SCHEMA = '$dbname' 
        AND TABLE_NAME = 'projects'
    ");
    $constraints = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($constraints)) {
        echo "<p>❌ Nessun constraint CHECK trovato (o versione MySQL non supporta questa query)</p>";
        
        // Try alternative method
        echo "<h3>🔍 Show Create Table:</h3>";
        $stmt = $pdo->query("SHOW CREATE TABLE projects");
        $createTable = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "<pre>" . htmlspecialchars($createTable['Create Table']) . "</pre>";
    } else {
        foreach ($constraints as $constraint) {
            echo "<p><strong>{$constraint['CONSTRAINT_NAME']}:</strong> {$constraint['CHECK_CLAUSE']}</p>";
        }
    }
    
    // 3. Test inserimento con dati diversi
    echo "<h3>🧪 Test Inserimento:</h3>";
    
    $testData = [
        ['technologies' => 'React, Laravel, MySQL, PHP', 'description' => 'Test 1'],
        ['technologies' => 'JavaScript', 'description' => 'Test 2'],
        ['technologies' => '', 'description' => 'Test 3 - Vuoto'],
        ['technologies' => null, 'description' => 'Test 4 - NULL'],
        ['technologies' => 'React', 'description' => 'Test 5 - Semplice']
    ];
    
    foreach ($testData as $i => $data) {
        try {
            $stmt = $pdo->prepare("INSERT INTO projects (title, description, technologies, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())");
            $stmt->execute([
                'Test Project ' . ($i + 1),
                $data['description'],
                $data['technologies']
            ]);
            
            $id = $pdo->lastInsertId();
            echo "<p>✅ Test " . ($i + 1) . " OK - ID: $id</p>";
            
            // Rimuovi il test
            $pdo->prepare("DELETE FROM projects WHERE id = ?")->execute([$id]);
            
        } catch (Exception $e) {
            echo "<p>❌ Test " . ($i + 1) . " FAILED: " . $e->getMessage() . "</p>";
        }
    }
    
    // 4. Progetti esistenti
    echo "<h3>📊 Progetti Esistenti:</h3>";
    $stmt = $pdo->query("SELECT id, title, technologies FROM projects LIMIT 5");
    $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($projects)) {
        echo "<p>📭 Nessun progetto trovato</p>";
    } else {
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>ID</th><th>Titolo</th><th>Tecnologie</th></tr>";
        foreach ($projects as $project) {
            echo "<tr>";
            echo "<td>{$project['id']}</td>";
            echo "<td>{$project['title']}</td>";
            echo "<td>" . htmlspecialchars($project['technologies']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
} catch(PDOException $e) {
    echo "<p style='color: red;'>❌ Errore database: " . $e->getMessage() . "</p>";
}
?> 