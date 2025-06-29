<?php
// Test completo per vincenzorocca.com su Hostinger - VERSIONE CORRETTA
header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Completo CORRETTO</title>
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
    <h1>🚀 Test Portfolio - VERSIONE CORRETTA</h1>
    
    <div class="test-section">
        <h2>📁 File Structure</h2>
        <?php
        $files = [
            'index.html' => 'Frontend React',
            'api/index.php' => 'API Backend',
            'assets/index-CoZPpuo8.css' => 'CSS Bundle',
            'assets/index-CXYiKPGX.js' => 'JS Bundle',
            '.htaccess' => 'Routing'
        ];
        
        foreach ($files as $file => $desc) {
            if (file_exists($file)) {
                echo "<div class='success'>✅ $desc: $file</div>";
            } else {
                echo "<div class='error'>❌ $desc: $file (MANCANTE)</div>";
            }
        }
        ?>
    </div>

    <div class="test-section">
        <h2>🗃️ Database</h2>
        <?php
        try {
            $mysqli = new mysqli('localhost', 'u336414084_vincenzorocca8', 'Ciaociao52.?', 'u336414084_portfolioVince');
            
            if ($mysqli->connect_error) {
                echo "<div class='error'>❌ Connessione fallita</div>";
            } else {
                echo "<div class='success'>✅ Database connesso</div>";
                
                $tables = ['users', 'projects', 'technologies', 'contacts'];
                foreach ($tables as $table) {
                    $result = $mysqli->query("SELECT COUNT(*) as count FROM $table");
                    if ($result) {
                        $row = $result->fetch_assoc();
                        echo "<div class='info'>📊 $table: {$row['count']} record</div>";
                    }
                }
                $mysqli->close();
            }
        } catch (Exception $e) {
            echo "<div class='error'>❌ Errore: " . $e->getMessage() . "</div>";
        }
        ?>
    </div>

    <div class="test-section">
        <h2>🔌 API Test</h2>
        <button onclick="testAPI('/api/v1/projects')">Test Progetti</button>
        <button onclick="testAPI('/api/v1/technologies')">Test Tecnologie</button>
        <div id="api-results"></div>
    </div>

    <script>
    async function testAPI(endpoint) {
        const div = document.getElementById('api-results');
        div.innerHTML += `<div class="info">🔄 Testing: ${endpoint}</div>`;
        
        try {
            const response = await fetch(endpoint);
            const result = await response.json();
            
            if (response.ok) {
                div.innerHTML += `<div class="success">✅ ${endpoint}: OK</div>`;
            } else {
                div.innerHTML += `<div class="error">❌ ${endpoint}: ${response.status}</div>`;
            }
        } catch (error) {
            div.innerHTML += `<div class="error">❌ ${endpoint}: ${error.message}</div>`;
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
        echo "User Agent: " . ($_SERVER['HTTP_USER_AGENT'] ?? 'N/A') . "\n";
        echo "Request URI: " . ($_SERVER['REQUEST_URI'] ?? 'N/A') . "\n";
        echo "HTTP Host: " . ($_SERVER['HTTP_HOST'] ?? 'N/A') . "\n";
        ?></pre>
    </div>

    <div class="test-section">
        <h2>🔧 Controlli Avanzati</h2>
        <button onclick="testUploads()">Test Directory Upload</button>
        <button onclick="testPermissions()">Test Permessi</button>
        <div id="advanced-results"></div>
    </div>

    <script>
    async function testUploads() {
        const resultDiv = document.getElementById('advanced-results');
        
        // Controlla se la directory uploads esiste
        try {
            const response = await fetch('/api/uploads/', {method: 'HEAD'});
            if (response.ok) {
                resultDiv.innerHTML += '<div class="success">✅ Directory uploads accessibile</div>';
            } else {
                resultDiv.innerHTML += '<div class="warning">⚠️ Directory uploads non accessibile (normale se vuota)</div>';
            }
        } catch (error) {
            resultDiv.innerHTML += '<div class="error">❌ Errore controllo uploads: ' + error.message + '</div>';
        }
    }

    function testPermissions() {
        const resultDiv = document.getElementById('advanced-results');
        resultDiv.innerHTML += '<div class="info">ℹ️ Controllo permessi tramite API...</div>';
        
        fetch('/api/v1/admin/test-permissions', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer test-token'
            }
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                resultDiv.innerHTML += '<div class="success">✅ Permessi API corretti</div>';
            } else {
                resultDiv.innerHTML += '<div class="warning">⚠️ Controllo permessi: ' + result.message + '</div>';
            }
        })
        .catch(error => {
            resultDiv.innerHTML += '<div class="info">ℹ️ Endpoint permessi non implementato (normale)</div>';
        });
    }
    </script>
</body>
</html> 