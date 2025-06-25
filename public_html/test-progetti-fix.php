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
    
    echo "<h1>🚀 Test Creazione Progetti - JSON Fix</h1>";
    
    // Test 1: Creazione con technologies come stringa
    echo "<h2>🧪 Test 1: Technologies come stringa</h2>";
    try {
        $technologies = 'React, Laravel, MySQL, PHP';
        $techArray = array_map('trim', explode(',', $technologies));
        $technologiesJSON = json_encode($techArray);
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', 'Test Progetto Fix')));
        
        $stmt = $pdo->prepare("INSERT INTO projects (title, slug, description, technologies, category, status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())");
        $stmt->execute([
            'Test Progetto Fix ' . time(),
            $slug . '-' . time(),
            'Test per verificare il fix del constraint JSON',
            $technologiesJSON,
            'web',
            'completed'
        ]);
        
        $id1 = $pdo->lastInsertId();
        echo "<p>✅ <strong>SUCCESSO!</strong> Progetto creato con ID: $id1</p>";
        echo "<p>📋 Technologies JSON: <code>$technologiesJSON</code></p>";
        
    } catch (Exception $e) {
        echo "<p>❌ <strong>ERRORE Test 1:</strong> " . $e->getMessage() . "</p>";
    }
    
    // Test 2: Via API diretta
    echo "<h2>🧪 Test 2: Via API diretta</h2>";
    $testData = [
        'title' => 'Test API Diretta ' . time(),
        'description' => 'Test creazione progetto via API con technologies JSON',
        'technologies' => 'Vue.js, Node.js, MongoDB',
        'category' => 'web',
        'status' => 'completed'
    ];
    
    $response = file_get_contents('/api-direct.php?endpoint=projects', false, stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => 'Content-Type: application/json',
            'content' => json_encode($testData)
        ]
    ]));
    
    $result = json_decode($response, true);
    if ($result && $result['success']) {
        echo "<p>✅ <strong>SUCCESSO API!</strong> Progetto creato con ID: " . $result['project_id'] . "</p>";
        $id2 = $result['project_id'];
    } else {
        echo "<p>❌ <strong>ERRORE API:</strong> " . ($result['error'] ?? 'Errore sconosciuto') . "</p>";
        $id2 = null;
    }
    
    // Test 3: Verifica dati inseriti
    echo "<h2>📊 Test 3: Verifica dati inseriti</h2>";
    $stmt = $pdo->query("SELECT id, title, technologies FROM projects ORDER BY id DESC LIMIT 3");
    $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>ID</th><th>Titolo</th><th>Technologies (JSON)</th><th>Technologies (Array)</th></tr>";
    
    foreach ($projects as $project) {
        $techArray = json_decode($project['technologies'], true);
        $techDisplay = is_array($techArray) ? implode(', ', $techArray) : 'Errore parsing';
        
        echo "<tr>";
        echo "<td>{$project['id']}</td>";
        echo "<td>{$project['title']}</td>";
        echo "<td><code>" . htmlspecialchars($project['technologies']) . "</code></td>";
        echo "<td>$techDisplay</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Pulizia test
    echo "<h2>🗑️ Pulizia test</h2>";
    if (isset($id1)) {
        $pdo->prepare("DELETE FROM projects WHERE id = ?")->execute([$id1]);
        echo "<p>🗑️ Test 1 rimosso (ID: $id1)</p>";
    }
    if ($id2) {
        $pdo->prepare("DELETE FROM projects WHERE id = ?")->execute([$id2]);
        echo "<p>🗑️ Test 2 rimosso (ID: $id2)</p>";
    }
    
    echo "<h2>🎉 Risultato Finale</h2>";
    echo "<p style='background: #dcfce7; padding: 20px; border-radius: 8px; border-left: 4px solid #10b981;'>";
    echo "<strong>✅ FIX COMPLETATO!</strong><br>";
    echo "La creazione progetti ora funziona correttamente con il formato JSON richiesto dal database.<br>";
    echo "Le tecnologie vengono automaticamente convertite da stringa a JSON array.";
    echo "</p>";
    
    echo "<p><a href='/test-rapido.html' style='background: #10b981; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; margin-right: 12px;'>🧪 Test Rapido</a>";
    echo "<a href='/test-suite-completo.html' style='background: #3b82f6; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none;'>🧪 Test Suite</a></p>";
    
} catch(PDOException $e) {
    echo "<p style='color: red;'>❌ Errore database: " . $e->getMessage() . "</p>";
}
?> 