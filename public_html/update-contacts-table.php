<?php
// Script per aggiornare la tabella contacts con i nuovi campi

// Configurazione database
$host = 'localhost';
$dbname = 'u336414084_portfolioVince';
$username = 'u336414084_vincenzorocca8';
$password = 'Ciaociao52.?';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    echo "<h2>🔧 Aggiornamento Tabella Contacts</h2>";

    // Controlla la struttura attuale della tabella
    echo "<h3>📋 Struttura attuale della tabella:</h3>";
    $stmt = $pdo->query("DESCRIBE contacts");
    $columns = $stmt->fetchAll();
    
    echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
    echo "<tr><th>Campo</th><th>Tipo</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    foreach ($columns as $column) {
        echo "<tr>";
        echo "<td>{$column['Field']}</td>";
        echo "<td>{$column['Type']}</td>";
        echo "<td>{$column['Null']}</td>";
        echo "<td>{$column['Key']}</td>";
        echo "<td>{$column['Default']}</td>";
        echo "<td>{$column['Extra']}</td>";
        echo "</tr>";
    }
    echo "</table>";

    // Lista dei campi che dovremmo avere
    $requiredColumns = ['budget', 'timeline', 'project_type'];
    $existingColumns = array_column($columns, 'Field');
    
    $missingColumns = array_diff($requiredColumns, $existingColumns);
    
    if (empty($missingColumns)) {
        echo "<p style='color: green;'>✅ Tutti i campi necessari sono già presenti!</p>";
    } else {
        echo "<h3>➕ Aggiunta campi mancanti:</h3>";
        
        foreach ($missingColumns as $column) {
            try {
                switch ($column) {
                    case 'budget':
                        $sql = "ALTER TABLE contacts ADD COLUMN budget VARCHAR(100) NULL AFTER message";
                        break;
                    case 'timeline':
                        $sql = "ALTER TABLE contacts ADD COLUMN timeline VARCHAR(100) NULL AFTER budget";
                        break;
                    case 'project_type':
                        $sql = "ALTER TABLE contacts ADD COLUMN project_type VARCHAR(100) NULL AFTER timeline";
                        break;
                }
                
                $pdo->exec($sql);
                echo "<p style='color: green;'>✅ Campo '$column' aggiunto con successo</p>";
                
            } catch (PDOException $e) {
                echo "<p style='color: red;'>❌ Errore aggiungendo '$column': " . $e->getMessage() . "</p>";
            }
        }
    }

    // Mostra la struttura finale
    echo "<h3>📋 Struttura finale della tabella:</h3>";
    $stmt = $pdo->query("DESCRIBE contacts");
    $finalColumns = $stmt->fetchAll();
    
    echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
    echo "<tr><th>Campo</th><th>Tipo</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    foreach ($finalColumns as $column) {
        $isNew = in_array($column['Field'], $requiredColumns) ? " style='background-color: #d4edda;'" : "";
        echo "<tr$isNew>";
        echo "<td>{$column['Field']}</td>";
        echo "<td>{$column['Type']}</td>";
        echo "<td>{$column['Null']}</td>";
        echo "<td>{$column['Key']}</td>";
        echo "<td>{$column['Default']}</td>";
        echo "<td>{$column['Extra']}</td>";
        echo "</tr>";
    }
    echo "</table>";

    // Test di inserimento
    echo "<h3>🧪 Test di inserimento:</h3>";
    try {
        $testData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'subject' => 'Test Subject',
            'message' => 'Test message for database update verification',
            'budget' => '1k-5k',
            'timeline' => '1-month',
            'project_type' => 'web-app',
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'Test Agent'
        ];

        $stmt = $pdo->prepare("
            INSERT INTO contacts (
                name, email, subject, message, budget, timeline, project_type, 
                ip_address, user_agent, created_at, updated_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
        ");

        $stmt->execute([
            $testData['name'],
            $testData['email'], 
            $testData['subject'],
            $testData['message'],
            $testData['budget'],
            $testData['timeline'],
            $testData['project_type'],
            $testData['ip_address'],
            $testData['user_agent']
        ]);

        $insertId = $pdo->lastInsertId();
        echo "<p style='color: green;'>✅ Test di inserimento riuscito! ID: $insertId</p>";

        // Recupera e mostra il record inserito
        $stmt = $pdo->prepare("SELECT * FROM contacts WHERE id = ?");
        $stmt->execute([$insertId]);
        $record = $stmt->fetch();

        echo "<h4>📄 Record inserito:</h4>";
        echo "<pre>" . print_r($record, true) . "</pre>";

        // Cancella il record di test
        $stmt = $pdo->prepare("DELETE FROM contacts WHERE id = ?");
        $stmt->execute([$insertId]);
        echo "<p style='color: blue;'>🧹 Record di test eliminato</p>";

    } catch (PDOException $e) {
        echo "<p style='color: red;'>❌ Errore nel test di inserimento: " . $e->getMessage() . "</p>";
    }

    echo "<h3>✅ Aggiornamento completato!</h3>";
    echo "<p>La tabella contacts è ora pronta per ricevere i nuovi campi del form di contatto.</p>";

} catch (PDOException $e) {
    echo "<h2 style='color: red;'>❌ Errore di connessione al database</h2>";
    echo "<p>Errore: " . $e->getMessage() . "</p>";
}
?>

<style>
    body {
        font-family: Arial, sans-serif;
        max-width: 1000px;
        margin: 0 auto;
        padding: 20px;
        background-color: #f5f5f5;
    }
    
    h2, h3 {
        color: #333;
        border-bottom: 2px solid #667eea;
        padding-bottom: 10px;
    }
    
    table {
        width: 100%;
        background: white;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    
    th {
        background: #667eea;
        color: white;
        padding: 10px;
        text-align: left;
    }
    
    td {
        padding: 8px 10px;
        border-bottom: 1px solid #eee;
    }
    
    pre {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 5px;
        border-left: 4px solid #667eea;
        overflow-x: auto;
    }
    
    p {
        padding: 10px;
        margin: 10px 0;
        border-radius: 5px;
    }
</style> 