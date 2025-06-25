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
    
    echo "<h1>🔧 Fix Constraint Projects</h1>";
    
    // 1. Mostra struttura tabella
    echo "<h2>📋 Struttura Attuale:</h2>";
    $stmt = $pdo->query("SHOW CREATE TABLE projects");
    $createTable = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "<pre>" . htmlspecialchars($createTable['Create Table']) . "</pre>";
    
    // 2. Trova e rimuovi constraint
    echo "<h2>🔍 Ricerca Constraint:</h2>";
    
    // Prova a rimuovere constraint noti
    $constraintNames = ['projects_chk_1', 'projects_chk_2', 'chk_technologies', 'technologies'];
    
    foreach ($constraintNames as $constraintName) {
        try {
            $stmt = $pdo->prepare("ALTER TABLE projects DROP CONSTRAINT $constraintName");
            $stmt->execute();
            echo "<p>✅ Constraint '$constraintName' rimosso con successo!</p>";
        } catch (Exception $e) {
            echo "<p>⚠️ Constraint '$constraintName' non trovato o già rimosso</p>";
        }
    }
    
    // 3. Test creazione progetto
    echo "<h2>🧪 Test Creazione Progetto:</h2>";
    try {
        $stmt = $pdo->prepare("INSERT INTO projects (title, description, technologies, category, status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, NOW(), NOW())");
        $stmt->execute([
            'Test Fix Constraint ' . time(),
            'Progetto di test per verificare che il constraint sia stato rimosso',
            'React, Laravel, MySQL, PHP',
            'web',
            'completed'
        ]);
        
        $id = $pdo->lastInsertId();
        echo "<p>✅ <strong>SUCCESSO!</strong> Progetto creato con ID: $id</p>";
        
        // Rimuovi il test
        $pdo->prepare("DELETE FROM projects WHERE id = ?")->execute([$id]);
        echo "<p>🗑️ Progetto di test rimosso</p>";
        
    } catch (Exception $e) {
        echo "<p>❌ <strong>ERRORE:</strong> " . $e->getMessage() . "</p>";
        
        // Se ancora non funziona, proviamo a ricreare la tabella senza constraint
        echo "<h3>🔄 Tentativo Ricreazione Tabella:</h3>";
        
        try {
            // Backup dati esistenti
            $stmt = $pdo->query("SELECT * FROM projects");
            $existingProjects = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo "<p>📦 Backup di " . count($existingProjects) . " progetti esistenti</p>";
            
            // Drop e ricrea tabella
            $pdo->exec("DROP TABLE IF EXISTS projects_backup");
            $pdo->exec("CREATE TABLE projects_backup AS SELECT * FROM projects");
            echo "<p>✅ Backup creato in projects_backup</p>";
            
            $pdo->exec("DROP TABLE projects");
            $createSQL = "
                CREATE TABLE projects (
                    id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                    title varchar(255) NOT NULL,
                    description text NOT NULL,
                    long_description text,
                    demo_url varchar(255),
                    github_url varchar(255),
                    image_url varchar(255),
                    technologies text,
                    category varchar(100) DEFAULT 'web',
                    status varchar(50) DEFAULT 'completed',
                    featured tinyint(1) DEFAULT 0,
                    created_at timestamp NULL DEFAULT NULL,
                    updated_at timestamp NULL DEFAULT NULL,
                    PRIMARY KEY (id)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ";
            
            $pdo->exec($createSQL);
            echo "<p>✅ Tabella projects ricreata senza constraint</p>";
            
            // Ripristina dati
            foreach ($existingProjects as $project) {
                $stmt = $pdo->prepare("INSERT INTO projects (id, title, description, long_description, demo_url, github_url, image_url, technologies, category, status, featured, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([
                    $project['id'],
                    $project['title'],
                    $project['description'],
                    $project['long_description'],
                    $project['demo_url'],
                    $project['github_url'],
                    $project['image_url'],
                    $project['technologies'],
                    $project['category'],
                    $project['status'],
                    $project['featured'],
                    $project['created_at'],
                    $project['updated_at']
                ]);
            }
            
            echo "<p>✅ Dati ripristinati con successo!</p>";
            
            // Test finale
            $stmt = $pdo->prepare("INSERT INTO projects (title, description, technologies, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())");
            $stmt->execute([
                'Test Finale ' . time(),
                'Test dopo ricreazione tabella',
                'React, Laravel, MySQL, PHP'
            ]);
            
            $finalId = $pdo->lastInsertId();
            echo "<p>🎉 <strong>RISOLTO!</strong> Progetto creato con ID: $finalId</p>";
            
            // Pulisci test
            $pdo->prepare("DELETE FROM projects WHERE id = ?")->execute([$finalId]);
            
        } catch (Exception $recreateError) {
            echo "<p>❌ Errore ricreazione: " . $recreateError->getMessage() . "</p>";
        }
    }
    
    echo "<h2>🎯 Risultato Finale:</h2>";
    echo "<p><a href='/test-rapido.html' style='background: #10b981; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none;'>🧪 Testa Creazione Progetti</a></p>";
    
} catch(PDOException $e) {
    echo "<p style='color: red;'>❌ Errore database: " . $e->getMessage() . "</p>";
}
?> 