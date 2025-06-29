<?php
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>🚀 Deploy Completo - Vincenzo Rocca Portfolio</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 1000px; margin: 0 auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .success { color: #28a745; background: #d4edda; padding: 10px; border-radius: 5px; margin: 10px 0; }
        .error { color: #dc3545; background: #f8d7da; padding: 10px; border-radius: 5px; margin: 10px 0; }
        .warning { color: #856404; background: #fff3cd; padding: 10px; border-radius: 5px; margin: 10px 0; }
        .info { color: #17a2b8; background: #d1ecf1; padding: 10px; border-radius: 5px; margin: 10px 0; }
        .file-list { background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .file-item { padding: 5px 0; border-bottom: 1px solid #eee; }
        .file-item:last-child { border-bottom: none; }
        .file-exists { color: #28a745; }
        .file-missing { color: #dc3545; }
        pre { background: #f8f9fa; padding: 10px; border-radius: 5px; overflow-x: auto; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🚀 Deploy Completo - Vincenzo Rocca Portfolio</h1>
        <p><strong>Data/Ora:</strong> <?= date('Y-m-d H:i:s') ?></p>

        <?php
        // File essenziali per il deploy
        $requiredFiles = [
            // Frontend
            'index.html' => 'Frontend principale',
            'assets/index-CXYiKPGX.js' => 'JavaScript bundle principale',
            'assets/index-CoZPpuo8.css' => 'CSS bundle principale',
            'assets/vendor-dQk0gtQ5.js' => 'Vendor JavaScript',
            'assets/router-qtbhp7Me.js' => 'Router JavaScript',
            'assets/ui-KUd19APl.js' => 'UI JavaScript',
            
            // API
            'api/index.php' => 'API principale',
            
            // Test files
            'test-immediato.php' => 'Test sistema completo',
            'fix-login-immediato.php' => 'Test login',
            'test-database-credenziali.php' => 'Test credenziali database',
            
            // Database
            'database-hostinger-completo.sql' => 'Schema database completo',
            
            // Assets
            'favicon.ico' => 'Favicon',
            'android-chrome-192x192.png' => 'Icon Android 192',
            'android-chrome-512x512.png' => 'Icon Android 512',
            'apple-touch-icon.png' => 'Icon Apple Touch',
        ];

        echo "<h2>📋 Verifica File Deploy</h2>";
        
        $allFilesExist = true;
        $existingFiles = [];
        $missingFiles = [];
        
        echo "<div class='file-list'>";
        foreach ($requiredFiles as $file => $description) {
            $exists = file_exists($file);
            $size = $exists ? filesize($file) : 0;
            $sizeFormatted = $exists ? number_format($size) . ' bytes' : 'N/A';
            
            if ($exists) {
                $existingFiles[] = $file;
                echo "<div class='file-item'>";
                echo "<span class='file-exists'>✅ $file</span> - $description ($sizeFormatted)";
                echo "</div>";
            } else {
                $allFilesExist = false;
                $missingFiles[] = $file;
                echo "<div class='file-item'>";
                echo "<span class='file-missing'>❌ $file</span> - $description (MANCANTE)";
                echo "</div>";
            }
        }
        echo "</div>";

        // Riepilogo
        echo "<h2>📊 Riepilogo Deploy</h2>";
        echo "<div class='info'>";
        echo "<p><strong>File esistenti:</strong> " . count($existingFiles) . "/" . count($requiredFiles) . "</p>";
        echo "<p><strong>File mancanti:</strong> " . count($missingFiles) . "</p>";
        echo "</div>";

        if ($allFilesExist) {
            echo "<div class='success'>";
            echo "<h3>🎉 Tutti i file sono presenti!</h3>";
            echo "<p>Il deploy può procedere senza problemi.</p>";
            echo "</div>";
        } else {
            echo "<div class='warning'>";
            echo "<h3>⚠️ File mancanti rilevati</h3>";
            echo "<p>I seguenti file devono essere caricati:</p>";
            echo "<ul>";
            foreach ($missingFiles as $file) {
                echo "<li>$file</li>";
            }
            echo "</ul>";
            echo "</div>";
        }

        // Test connessione database
        echo "<h2>🗄️ Test Database</h2>";
        try {
            $host = 'localhost';
            $dbname = 'u336414084_portfolioVince';
            $username = 'u336414084_vincenzorocca8';
            $password = 'Ciaociao52.?';
            
            $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
            
            echo "<div class='success'>✅ Connessione database riuscita</div>";
            
            // Verifica tabelle essenziali
            $tables = ['users', 'projects', 'technologies', 'contacts'];
            $existingTables = [];
            
            foreach ($tables as $table) {
                try {
                    $stmt = $pdo->query("SELECT COUNT(*) FROM $table");
                    $count = $stmt->fetchColumn();
                    $existingTables[] = $table;
                    echo "<div class='success'>✅ Tabella '$table': $count record</div>";
                } catch (Exception $e) {
                    echo "<div class='error'>❌ Tabella '$table': non trovata</div>";
                }
            }
            
        } catch (Exception $e) {
            echo "<div class='error'>❌ Errore database: " . $e->getMessage() . "</div>";
        }

        // Istruzioni deploy
        echo "<h2>📋 Istruzioni Deploy</h2>";
        echo "<div class='info'>";
        echo "<ol>";
        echo "<li><strong>Elimina tutto</strong> dalla cartella public_html su Hostinger</li>";
        echo "<li><strong>Carica tutti i file</strong> dalla cartella public_html locale</li>";
        echo "<li><strong>Verifica permessi</strong> delle cartelle (755 per cartelle, 644 per file)</li>";
        echo "<li><strong>Importa database</strong> usando database-hostinger-completo.sql</li>";
        echo "<li><strong>Testa il sistema</strong> con i file di test</li>";
        echo "</ol>";
        echo "</div>";

        // Link test
        echo "<h2>🔗 Link Test (dopo deploy)</h2>";
        echo "<div class='info'>";
        echo "<ul>";
        echo "<li><a href='test-database-credenziali.php'>Test Database Credenziali</a></li>";
        echo "<li><a href='test-immediato.php'>Test Sistema Completo</a></li>";
        echo "<li><a href='fix-login-immediato.php'>Test Login</a></li>";
        echo "<li><a href='api/'>Test API</a></li>";
        echo "<li><a href='index.html'>Frontend Portfolio</a></li>";
        echo "</ul>";
        echo "</div>";
        ?>
        
        <div class="info">
            <h3>🎯 Credenziali Sistema</h3>
            <p><strong>Database:</strong> u336414084_portfolioVince</p>
            <p><strong>Username:</strong> u336414084_vincenzorocca8</p>
            <p><strong>Admin Email:</strong> vincenzorocca88@gmail.com</p>
            <p><strong>Admin Password:</strong> admin123</p>
        </div>
    </div>
</body>
</html> 