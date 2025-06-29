<?php
/**
 * 🔍 TEST CREDENZIALI HOSTINGER
 * =============================
 * Trova le credenziali database corrette per Hostinger
 */

header('Content-Type: text/html; charset=utf-8');
ini_set('display_errors', 1);
error_reporting(E_ALL);

?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🔍 Test Credenziali Hostinger</title>
    <style>
        body { font-family: system-ui; margin: 20px; background: #dc2626; }
        .container { max-width: 1000px; margin: 0 auto; background: white; padding: 30px; border-radius: 15px; }
        .success { color: #10b981; background: #ecfdf5; padding: 15px; border-radius: 8px; margin: 10px 0; }
        .error { color: #ef4444; background: #fef2f2; padding: 15px; border-radius: 8px; margin: 10px 0; }
        .warning { color: #f59e0b; background: #fffbeb; padding: 15px; border-radius: 8px; margin: 10px 0; }
        .info { color: #3b82f6; background: #eff6ff; padding: 15px; border-radius: 8px; margin: 10px 0; }
        .critical { color: #dc2626; background: #fca5a5; padding: 20px; border-radius: 10px; margin: 15px 0; border: 3px solid #dc2626; }
        h1 { text-align: center; color: #dc2626; }
        .action { background: #dc2626; color: white; padding: 15px; border-radius: 8px; margin: 15px 0; }
        code { background: #f1f5f9; padding: 5px; border-radius: 4px; font-family: monospace; }
        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        th, td { padding: 10px; border: 1px solid #e5e7eb; text-align: left; }
        th { background: #f3f4f6; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 TEST CREDENZIALI HOSTINGER</h1>
        
        <div class="critical">
            <h3>🚨 PROBLEMA CREDENZIALI DATABASE</h3>
            <p>Anche se hai importato i dati in phpMyAdmin, Laravel non riesce a connettersi.</p>
            <p><strong>Testiamo diverse configurazioni per trovare quella corretta...</strong></p>
        </div>

        <?php
        // Configurazioni da testare
        $configurations = [
            [
                'name' => 'Config .env attuale',
                'host' => 'localhost',
                'database' => 'u336414084_portfolioVince',
                'username' => 'u336414084_portfolioVince',
                'password' => 'Ciaociao52.?'
            ],
            [
                'name' => 'Config IP locale',
                'host' => '127.0.0.1',
                'database' => 'u336414084_portfolioVince',
                'username' => 'u336414084_portfolioVince',
                'password' => 'Ciaociao52.?'
            ],
            [
                'name' => 'Config senza quotes password',
                'host' => 'localhost',
                'database' => 'u336414084_portfolioVince',
                'username' => 'u336414084_portfolioVince',
                'password' => 'Ciaociao52.?'
            ],
            [
                'name' => 'Config MySQL Host',
                'host' => 'mysql.hostinger.com',
                'database' => 'u336414084_portfolioVince',
                'username' => 'u336414084_portfolioVince',
                'password' => 'Ciaociao52.?'
            ],
            [
                'name' => 'Config database interno',
                'host' => 'localhost',
                'database' => 'u336414084_portfolioVince',
                'username' => 'u336414084_portfolioVince',
                'password' => 'Ciaociao52.?',
                'port' => 3306
            ]
        ];

        $workingConfig = null;
        $testResults = [];

        echo "<div class='action'><h3>🔗 Test Configurazioni Database</h3></div>";

        foreach ($configurations as $index => $config) {
            echo "<div class='info'>";
            echo "<h4>🔍 Test " . ($index + 1) . ": {$config['name']}</h4>";
            echo "<strong>Host:</strong> <code>{$config['host']}</code><br>";
            echo "<strong>Database:</strong> <code>{$config['database']}</code><br>";
            echo "<strong>Username:</strong> <code>{$config['username']}</code><br>";
            echo "<strong>Password:</strong> <code>" . str_repeat('*', strlen($config['password'])) . "</code>";
            echo "</div>";
            
            try {
                $port = isset($config['port']) ? $config['port'] : 3306;
                $dsn = "mysql:host={$config['host']};port={$port};dbname={$config['database']};charset=utf8mb4";
                
                $startTime = microtime(true);
                $pdo = new PDO(
                    $dsn,
                    $config['username'],
                    $config['password'],
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_TIMEOUT => 5
                    ]
                );
                $connectionTime = round((microtime(true) - $startTime) * 1000, 2);
                
                echo "<div class='success'>✅ CONNESSIONE RIUSCITA! (Tempo: {$connectionTime}ms)</div>";
                
                // Test query
                $stmt = $pdo->query("SELECT VERSION() as version, DATABASE() as current_db");
                $result = $stmt->fetch();
                echo "<div class='success'>📊 MySQL: {$result['version']}</div>";
                echo "<div class='success'>🗄️ Database attivo: {$result['current_db']}</div>";
                
                // Test tabelle
                $stmt = $pdo->query("SHOW TABLES");
                $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
                echo "<div class='success'>📋 Tabelle trovate: " . count($tables) . "</div>";
                
                if (count($tables) > 0) {
                    echo "<div class='info'>📝 Tabelle: " . implode(', ', array_slice($tables, 0, 5)) . 
                         (count($tables) > 5 ? '...' : '') . "</div>";
                }
                
                // Verifica tabelle portfolio
                $portfolioTables = ['users', 'projects', 'technologies'];
                $foundTables = array_intersect($portfolioTables, $tables);
                
                if (count($foundTables) >= 2) {
                    echo "<div class='success'>🎉 TABELLE PORTFOLIO TROVATE!</div>";
                    
                    // Test dati
                    foreach ($foundTables as $table) {
                        try {
                            $stmt = $pdo->query("SELECT COUNT(*) as count FROM `{$table}`");
                            $count = $stmt->fetch()['count'];
                            echo "<div class='success'>📊 {$table}: {$count} record</div>";
                        } catch (Exception $e) {
                            echo "<div class='warning'>⚠️ Errore conteggio {$table}</div>";
                        }
                    }
                }
                
                $workingConfig = $config;
                $workingConfig['connection_time'] = $connectionTime;
                $workingConfig['tables_count'] = count($tables);
                
                break; // Esci al primo successo
                
            } catch (Exception $e) {
                echo "<div class='error'>❌ Errore: " . $e->getMessage() . "</div>";
                $testResults[$index] = [
                    'config' => $config,
                    'error' => $e->getMessage()
                ];
            }
            
            echo "<hr style='margin: 20px 0; border: 1px solid #e5e7eb;'>";
        }
        ?>

        <!-- RISULTATO DIAGNOSI -->
        <div class="action"><h3>🎯 Risultato Diagnosi</h3></div>
        
        <?php if ($workingConfig): ?>
            <div class="success">
                <h3>🎉 CONFIGURAZIONE FUNZIONANTE TROVATA!</h3>
                <table>
                    <tr><th>Parametro</th><th>Valore</th></tr>
                    <tr><td>Host</td><td><?= $workingConfig['host'] ?></td></tr>
                    <tr><td>Database</td><td><?= $workingConfig['database'] ?></td></tr>
                    <tr><td>Username</td><td><?= $workingConfig['username'] ?></td></tr>
                    <tr><td>Password</td><td><?= str_repeat('*', strlen($workingConfig['password'])) ?></td></tr>
                    <tr><td>Tempo connessione</td><td><?= $workingConfig['connection_time'] ?>ms</td></tr>
                    <tr><td>Tabelle</td><td><?= $workingConfig['tables_count'] ?></td></tr>
                </table>
                
                <h4>🔧 Configurazione .env corretta:</h4>
                <pre style="background: #1f2937; color: #f3f4f6; padding: 15px; border-radius: 8px; font-size: 12px;">
DB_CONNECTION=mysql
DB_HOST=<?= $workingConfig['host'] ?>

DB_PORT=3306
DB_DATABASE=<?= $workingConfig['database'] ?>

DB_USERNAME=<?= $workingConfig['username'] ?>

DB_PASSWORD="<?= $workingConfig['password'] ?>"
</pre>
            </div>
            
            <?php
            // Aggiorna automaticamente il .env se possibile
            $envPath = 'api/.env';
            if (file_exists($envPath)) {
                $envContent = file_get_contents($envPath);
                
                // Verifica se la configurazione è già corretta
                $currentHost = preg_match('/DB_HOST=(.*)/', $envContent, $matches) ? trim($matches[1]) : '';
                
                if ($currentHost !== $workingConfig['host']) {
                    // Sostituisci le configurazioni database
                    $envContent = preg_replace('/DB_HOST=.*/', "DB_HOST={$workingConfig['host']}", $envContent);
                    $envContent = preg_replace('/DB_DATABASE=.*/', "DB_DATABASE={$workingConfig['database']}", $envContent);
                    $envContent = preg_replace('/DB_USERNAME=.*/', "DB_USERNAME={$workingConfig['username']}", $envContent);
                    $envContent = preg_replace('/DB_PASSWORD=.*/', "DB_PASSWORD=\"{$workingConfig['password']}\"", $envContent);
                    
                    if (file_put_contents($envPath, $envContent)) {
                        echo "<div class='success'>✅ File .env aggiornato automaticamente!</div>";
                    } else {
                        echo "<div class='error'>❌ Errore aggiornamento automatico .env</div>";
                    }
                } else {
                    echo "<div class='info'>ℹ️ File .env già configurato correttamente</div>";
                }
            } else {
                echo "<div class='warning'>⚠️ File .env non trovato in api/</div>";
            }
            ?>
            
        <?php else: ?>
            <div class="critical">
                <h3>❌ NESSUNA CONFIGURAZIONE FUNZIONANTE</h3>
                <p><strong>Possibili cause:</strong></p>
                <ul>
                    <li>🗄️ <strong>Database non esistente:</strong> Vai nel pannello Hostinger e verifica se il database esiste</li>
                    <li>🔑 <strong>Credenziali sbagliate:</strong> Username o password potrebbero essere diversi</li>
                    <li>🚫 <strong>Permessi database:</strong> L'utente potrebbe non avere i permessi</li>
                    <li>🌐 <strong>Host diverso:</strong> Hostinger potrebbe usare un host MySQL diverso</li>
                </ul>
                
                <h4>🛠️ AZIONI IMMEDIATE:</h4>
                <ol>
                    <li><strong>Pannello Hostinger:</strong> <a href="https://hpanel.hostinger.com" target="_blank">hpanel.hostinger.com</a></li>
                    <li><strong>Vai a:</strong> "Database MySQL"</li>
                    <li><strong>Verifica:</strong> Database, username, password</li>
                    <li><strong>Ricrea se necessario</strong> e ricarica questo script</li>
                </ol>
                
                <h4>📋 Errori dettagliati:</h4>
                <?php foreach ($testResults as $index => $result): ?>
                    <div class="error">
                        <strong>Config <?= $index + 1 ?>:</strong> <?= htmlspecialchars($result['error']) ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- INFO PANNELLO HOSTINGER -->
        <div class="action"><h3>📋 Info Pannello Hostinger</h3></div>
        
        <div class="info">
            <h4>🎯 Come verificare nel pannello Hostinger:</h4>
            <ol>
                <li><strong>Login:</strong> <a href="https://hpanel.hostinger.com" target="_blank">hpanel.hostinger.com</a></li>
                <li><strong>Seleziona:</strong> vincenzorocca.com</li>
                <li><strong>Menu:</strong> "Database" → "Database MySQL"</li>
                <li><strong>Cerca:</strong> Database che inizia con "u336414084_"</li>
                <li><strong>Verifica:</strong> Nome esatto, username, password</li>
                <li><strong>Se manca:</strong> Crea nuovo database</li>
            </ol>
            
            <p><strong>💡 Suggerimento:</strong> Se il database non esiste, creane uno nuovo con:</p>
            <ul>
                <li><strong>Nome:</strong> portfolioVince (diventerà u336414084_portfolioVince)</li>
                <li><strong>Username:</strong> portfolioVince (diventerà u336414084_portfolioVince)</li>
                <li><strong>Password:</strong> Ciaociao52.?</li>
            </ul>
        </div>

        <!-- PULSANTI AZIONE -->
        <div style="text-align: center; margin-top: 30px;">
            <button onclick="location.reload()" style="background: #dc2626; color: white; padding: 15px 30px; border: none; border-radius: 8px; cursor: pointer;">
                🔄 Ritest Credenziali
            </button>
            
            <button onclick="window.open('https://hpanel.hostinger.com')" style="background: #3b82f6; color: white; padding: 15px 30px; border: none; border-radius: 8px; cursor: pointer; margin-left: 10px;">
                🌐 Pannello Hostinger
            </button>
            
            <?php if ($workingConfig): ?>
            <button onclick="window.open('https://vincenzorocca.com/api/v1/test')" style="background: #10b981; color: white; padding: 15px 30px; border: none; border-radius: 8px; cursor: pointer; margin-left: 10px;">
                🚀 Test API
            </button>
            <?php endif; ?>
        </div>
    </div>
</body>
</html> 