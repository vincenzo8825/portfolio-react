<?php
// Script per aggiungere campi mancanti alla tabella projects

header('Content-Type: text/plain');

$host = 'localhost';
$dbname = 'u336414084_portfolioVince';
$username = 'u336414084_vincenzorocca8';
$password = 'Ciaociao52.?';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== VERIFICA E AGGIUNTA CAMPI MANCANTI ===\n\n";
    
    // 1. Verifica struttura attuale
    echo "1. Struttura attuale della tabella 'projects':\n";
    $stmt = $pdo->prepare("DESCRIBE projects");
    $stmt->execute();
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $existingColumns = [];
    foreach ($columns as $column) {
        $existingColumns[] = $column['Field'];
        echo "   - " . $column['Field'] . " (" . $column['Type'] . ")\n";
    }
    echo "\n";
    
    // 2. Lista campi che dovrebbero esistere
    $requiredColumns = [
        'long_description' => 'TEXT',
        'client' => 'VARCHAR(255)',
        'duration' => 'VARCHAR(255)',
        'category' => 'VARCHAR(255)',
        'features' => 'JSON',
        'challenges' => 'JSON',
        'results' => 'JSON',
        'testimonial' => 'TEXT',
        'testimonial_author' => 'VARCHAR(255)',
        'testimonial_role' => 'VARCHAR(255)',
        'gallery' => 'JSON',
        'linkedin_url' => 'VARCHAR(255)',
        'video_url' => 'VARCHAR(255)',
        'additional_links' => 'JSON',
        'technologies' => 'JSON',
        'project_date' => 'DATE',
        'sort_order' => 'INT DEFAULT 0',
        'featured' => 'TINYINT(1) DEFAULT 0'
    ];
    
    echo "2. Verifica campi richiesti:\n";
    $missingColumns = [];
    foreach ($requiredColumns as $column => $type) {
        if (in_array($column, $existingColumns)) {
            echo "   ✅ $column - EXISTS\n";
        } else {
            echo "   ❌ $column - MISSING\n";
            $missingColumns[$column] = $type;
        }
    }
    echo "\n";
    
    // 3. Aggiungi campi mancanti
    if (!empty($missingColumns)) {
        echo "3. Aggiunta campi mancanti:\n";
        foreach ($missingColumns as $column => $type) {
            try {
                $sql = "ALTER TABLE projects ADD COLUMN `$column` $type";
                echo "   Aggiungendo: $column ($type)... ";
                $pdo->exec($sql);
                echo "✅ SUCCESS\n";
            } catch (Exception $e) {
                echo "❌ ERROR: " . $e->getMessage() . "\n";
            }
        }
    } else {
        echo "3. Tutti i campi richiesti sono già presenti!\n";
    }
    
    echo "\n=== COMPLETATO ===\n";
    echo "La tabella projects è ora aggiornata con tutti i campi necessari.\n";
    
} catch (Exception $e) {
    echo "ERRORE: " . $e->getMessage() . "\n";
}
?> 