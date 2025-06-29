<?php
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>🔍 Test Database Credenziali - Vincenzo Rocca Portfolio</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .success { color: #28a745; background: #d4edda; padding: 10px; border-radius: 5px; margin: 10px 0; }
        .error { color: #dc3545; background: #f8d7da; padding: 10px; border-radius: 5px; margin: 10px 0; }
        .info { color: #17a2b8; background: #d1ecf1; padding: 10px; border-radius: 5px; margin: 10px 0; }
        pre { background: #f8f9fa; padding: 10px; border-radius: 5px; overflow-x: auto; }
        .credential { background: #fff3cd; padding: 5px; border-radius: 3px; font-family: monospace; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Test Database Credenziali</h1>
        <p><strong>Data/Ora:</strong> <?= date('Y-m-d H:i:s') ?></p>

        <?php
        // Credenziali dal file .env
        $host = 'localhost';
        $dbname = 'u336414084_portfolioVince';
        $username = 'u336414084_vincenzorocca8';
        $password = 'Ciaociao52.?';
        
        echo "<div class='info'>";
        echo "<h2>📋 Credenziali utilizzate:</h2>";
        echo "<p><strong>Host:</strong> <span class='credential'>$host</span></p>";
        echo "<p><strong>Database:</strong> <span class='credential'>$dbname</span></p>";
        echo "<p><strong>Username:</strong> <span class='credential'>$username</span></p>";
        echo "<p><strong>Password:</strong> <span class='credential'>" . str_repeat('*', strlen($password)) . "</span></p>";
        echo "</div>";

        // Test connessione
        echo "<h2>🔌 Test Connessione Database</h2>";
        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
            
            echo "<div class='success'>✅ <strong>Connessione riuscita!</strong></div>";
            
            // Test query
            echo "<h3>📊 Informazioni Database:</h3>";
            $version = $pdo->query("SELECT VERSION() as version")->fetch();
            echo "<p><strong>MySQL Version:</strong> " . $version['version'] . "</p>";
            
            // Lista tabelle
            echo "<h3>📋 Tabelle disponibili:</h3>";
            $tables = $pdo->query("SHOW TABLES")->fetchAll();
            if (count($tables) > 0) {
                echo "<ul>";
                foreach ($tables as $table) {
                    $tableName = array_values($table)[0];
                    echo "<li>$tableName</li>";
                }
                echo "</ul>";
            } else {
                echo "<div class='error'>⚠️ Nessuna tabella trovata nel database</div>";
            }
            
            // Test tabella projects
            echo "<h3>🎯 Test Tabella Projects:</h3>";
            try {
                $stmt = $pdo->query("SELECT COUNT(*) as count FROM projects");
                $count = $stmt->fetch();
                echo "<div class='success'>✅ Tabella 'projects' trovata con {$count['count']} progetti</div>";
                
                // Mostra struttura tabella
                $columns = $pdo->query("SHOW COLUMNS FROM projects")->fetchAll();
                echo "<h4>Struttura tabella projects:</h4>";
                echo "<pre>";
                foreach ($columns as $col) {
                    echo "{$col['Field']} - {$col['Type']} - {$col['Null']} - {$col['Key']}\n";
                }
                echo "</pre>";
                
            } catch (Exception $e) {
                echo "<div class='error'>❌ Errore accesso tabella projects: " . $e->getMessage() . "</div>";
            }
            
            // Test tabella users
            echo "<h3>👤 Test Tabella Users:</h3>";
            try {
                $stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
                $count = $stmt->fetch();
                echo "<div class='success'>✅ Tabella 'users' trovata con {$count['count']} utenti</div>";
                
                // Verifica admin user
                $admin = $pdo->prepare("SELECT id, email, role FROM users WHERE email = ?");
                $admin->execute(['vincenzorocca88@gmail.com']);
                $adminUser = $admin->fetch();
                
                if ($adminUser) {
                    echo "<div class='success'>✅ Admin user trovato: {$adminUser['email']} (ID: {$adminUser['id']}, Role: {$adminUser['role']})</div>";
                } else {
                    echo "<div class='error'>❌ Admin user non trovato</div>";
                }
                
            } catch (Exception $e) {
                echo "<div class='error'>❌ Errore accesso tabella users: " . $e->getMessage() . "</div>";
            }
            
        } catch (Exception $e) {
            echo "<div class='error'>❌ <strong>Errore connessione:</strong><br>";
            echo "Messaggio: " . $e->getMessage() . "<br>";
            echo "Codice: " . $e->getCode() . "</div>";
            
            echo "<div class='info'>";
            echo "<h3>🔧 Possibili soluzioni:</h3>";
            echo "<ul>";
            echo "<li>Verifica che le credenziali nel file .env siano corrette</li>";
            echo "<li>Controlla che il database sia attivo su Hostinger</li>";
            echo "<li>Verifica i permessi dell'utente database</li>";
            echo "</ul>";
            echo "</div>";
        }
        ?>
        
        <hr>
        <div class="info">
            <h3>🚀 Prossimi passi:</h3>
            <ol>
                <li>Se la connessione funziona, testa: <a href="fix-login-immediato.php">fix-login-immediato.php</a></li>
                <li>Poi testa l'API: <a href="api/">api/index.php</a></li>
                <li>Infine testa il frontend del portfolio</li>
            </ol>
        </div>
    </div>
</body>
</html> 