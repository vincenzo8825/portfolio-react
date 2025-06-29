<?php
// Test completo per vincenzorocca.com su Hostinger
header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Completo - Hostinger</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .test-section { background: white; padding: 20px; margin: 20px 0; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .success { color: #22c55e; }
        .error { color: #ef4444; }
        .warning { color: #f59e0b; }
        .info { color: #3b82f6; }
        pre { background: #f8f9fa; padding: 10px; border-radius: 4px; overflow-x: auto; }
        button { background: #3b82f6; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; margin: 5px; }
        button:hover { background: #2563eb; }
        #results { margin-top: 20px; }
    </style>
</head>
<body>
    <h1>🚀 Test Completo - Portfolio Vincenzo Rocca</h1>
    
    <!-- Test 1: File Structure -->
    <div class="test-section">
        <h2>📁 Test Struttura File</h2>
        <?php
        $files_to_check = [
            'index.html' => '✅ Frontend React',
            'api/index.php' => '🔧 API Backend',
            'api/.env' => '🔑 Configurazione',
            'assets/index-CoZPpuo8.css' => '🎨 CSS/Tailwind',
            'assets/index-CXYiKPGX.js' => '📦 JavaScript Bundle',
            '.htaccess' => '⚙️ Routing'
        ];
        
        foreach ($files_to_check as $file => $description) {
            if (file_exists($file)) {
                echo "<div class='success'>✅ $description: $file</div>";
            } else {
                echo "<div class='error'>❌ $description: $file (MANCANTE)</div>";
            }
        }
        ?>
    </div>

    <!-- Test 2: Database -->
    <div class="test-section">
        <h2>🗃️ Test Database</h2>
        <?php
        if (file_exists('api/.env')) {
            try {
                $env_content = file_get_contents('api/.env');
                preg_match('/DB_HOST=(.*)/', $env_content, $host_match);
                preg_match('/DB_DATABASE=(.*)/', $env_content, $db_match);
                preg_match('/DB_USERNAME=(.*)/', $env_content, $user_match);
                preg_match('/DB_PASSWORD=(.*)/', $env_content, $pass_match);
                
                $host = trim($host_match[1] ?? '');
                $database = trim($db_match[1] ?? '');
                $username = trim($user_match[1] ?? '');
                $password = trim($pass_match[1] ?? '');
                
                if ($host && $database && $username) {
                    $mysqli = new mysqli($host, $username, $password, $database);
                    
                    if ($mysqli->connect_error) {
                        echo "<div class='error'>❌ Connessione fallita: " . $mysqli->connect_error . "</div>";
                    } else {
                        echo "<div class='success'>✅ Connessione riuscita al database: $database</div>";
                        
                        // Test tabelle
                        $tables = ['users', 'projects', 'technologies', 'contacts'];
                        foreach ($tables as $table) {
                            $result = $mysqli->query("SELECT COUNT(*) as count FROM $table");
                            if ($result) {
                                $row = $result->fetch_assoc();
                                echo "<div class='info'>📊 Tabella $table: {$row['count']} record</div>";
                            }
                        }
                        $mysqli->close();
                    }
                } else {
                    echo "<div class='warning'>⚠️ Configurazione database incompleta</div>";
                }
            } catch (Exception $e) {
                echo "<div class='error'>❌ Errore database: " . $e->getMessage() . "</div>";
            }
        } else {
            echo "<div class='error'>❌ File .env mancante</div>";
        }
        ?>
    </div>

    <!-- Test 3: API Routes -->
    <div class="test-section">
        <h2>🔌 Test API</h2>
        <p>Testa le API del backend Laravel:</p>
        <button onclick="testAPI('/api/v1/test')">Test Base</button>
        <button onclick="testAPI('/api/v1/projects')">Progetti</button>
        <button onclick="testAPI('/api/v1/technologies')">Tecnologie</button>
        <button onclick="testAPI('/api/v1/auth/login', 'POST', {email: 'test@example.com', password: 'test'})">Login</button>
        <div id="api-results"></div>
    </div>

    <!-- Test 4: CSS/Frontend -->
    <div class="test-section">
        <h2>🎨 Test Frontend CSS</h2>
        <p>Controlla se i file CSS sono caricati correttamente:</p>
        <button onclick="checkCSS()">Verifica CSS</button>
        <button onclick="window.open('/', '_blank')">Apri Frontend</button>
        <div id="css-results"></div>
    </div>

    <!-- Test 5: Email -->
    <div class="test-section">
        <h2>📧 Test Email SMTP</h2>
        <?php
        if (file_exists('api/.env')) {
            $env_content = file_get_contents('api/.env');
            preg_match('/MAIL_HOST=(.*)/', $env_content, $mail_host);
            preg_match('/MAIL_USERNAME=(.*)/', $env_content, $mail_user);
            
            $mail_host = trim($mail_host[1] ?? '');
            $mail_user = trim($mail_user[1] ?? '');
            
            if ($mail_host && $mail_user) {
                echo "<div class='success'>✅ SMTP configurato: $mail_host ($mail_user)</div>";
            } else {
                echo "<div class='warning'>⚠️ Configurazione SMTP incompleta</div>";
            }
        }
        ?>
        <button onclick="testEmail()">Test Invio Email</button>
        <div id="email-results"></div>
    </div>

    <script>
    async function testAPI(endpoint, method = 'GET', data = null) {
        const resultDiv = document.getElementById('api-results');
        resultDiv.innerHTML += `<div class="info">🔄 Testing: ${endpoint}</div>`;
        
        try {
            const options = {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            };
            
            if (data && method !== 'GET') {
                options.body = JSON.stringify(data);
            }
            
            const response = await fetch(endpoint, options);
            const result = await response.json();
            
            if (response.ok) {
                resultDiv.innerHTML += `<div class="success">✅ ${endpoint}: OK</div>`;
                resultDiv.innerHTML += `<pre>${JSON.stringify(result, null, 2)}</pre>`;
            } else {
                resultDiv.innerHTML += `<div class="error">❌ ${endpoint}: ${response.status}</div>`;
            }
        } catch (error) {
            resultDiv.innerHTML += `<div class="error">❌ ${endpoint}: ${error.message}</div>`;
        }
    }
    
    function checkCSS() {
        const resultDiv = document.getElementById('css-results');
        const cssFiles = [
            '/assets/index-x-U5VJmg.css',
            '/assets/index-DfIjBL69.js'
        ];
        
        cssFiles.forEach(file => {
            fetch(file)
                .then(response => {
                    if (response.ok) {
                        resultDiv.innerHTML += `<div class="success">✅ ${file}: Caricato</div>`;
                    } else {
                        resultDiv.innerHTML += `<div class="error">❌ ${file}: ${response.status}</div>`;
                    }
                })
                .catch(error => {
                    resultDiv.innerHTML += `<div class="error">❌ ${file}: ${error.message}</div>`;
                });
        });
    }
    
    async function testEmail() {
        const resultDiv = document.getElementById('email-results');
        resultDiv.innerHTML = '<div class="info">🔄 Testing email...</div>';
        
        try {
            const response = await fetch('/api/v1/contacts', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    name: 'Test System',
                    email: 'test@example.com',
                    subject: 'Test Email',
                    message: 'Questo è un test automatico del sistema email.'
                })
            });
            
            const result = await response.json();
            
            if (response.ok) {
                resultDiv.innerHTML = '<div class="success">✅ Email test inviato con successo</div>';
            } else {
                resultDiv.innerHTML = '<div class="error">❌ Errore invio email: ' + result.message + '</div>';
            }
        } catch (error) {
            resultDiv.innerHTML = '<div class="error">❌ Errore: ' + error.message + '</div>';
        }
    }
    </script>

    <div class="test-section">
        <h2>ℹ️ Informazioni Sistema</h2>
        <pre><?php
        echo "Server: " . $_SERVER['SERVER_NAME'] . "\n";
        echo "PHP Version: " . PHP_VERSION . "\n";
        echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "\n";
        echo "Current Path: " . __DIR__ . "\n";
        echo "Timestamp: " . date('Y-m-d H:i:s') . "\n";
        echo "User Agent: " . $_SERVER['HTTP_USER_AGENT'] . "\n";
        ?></pre>
    </div>
</body>
</html> 