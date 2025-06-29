<?php
header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Test Sistema - Vincenzo Rocca</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .success { color: green; }
        .error { color: red; }
        .info { color: blue; }
        button { padding: 10px 15px; margin: 5px; background: #007cba; color: white; border: none; border-radius: 4px; cursor: pointer; }
    </style>
</head>
<body>
    <h1>🚀 Test Sistema Portfolio</h1>
    
    <h3>📁 File Structure:</h3>
    <?php
    $files = ['index.html', 'api/index.php', 'api/.env', 'assets/index-x-U5VJmg.css'];
    foreach($files as $file) {
        echo file_exists($file) ? "<div class='success'>✅ $file</div>" : "<div class='error'>❌ $file</div>";
    }
    ?>
    
    <h3>🗃️ Database:</h3>
    <?php
    try {
        require_once 'api/vendor/autoload.php';
        $env = file_get_contents('api/.env');
        preg_match('/DB_HOST=(.*)/', $env, $host);
        preg_match('/DB_DATABASE=(.*)/', $env, $db);
        preg_match('/DB_USERNAME=(.*)/', $env, $user);
        preg_match('/DB_PASSWORD=(.*)/', $env, $pass);
        
        $mysqli = new mysqli(trim($host[1]), trim($user[1]), trim($pass[1]), trim($db[1]));
        if (!$mysqli->connect_error) {
            echo "<div class='success'>✅ Database connesso</div>";
            $result = $mysqli->query("SELECT COUNT(*) as c FROM projects");
            $row = $result->fetch_assoc();
            echo "<div class='info'>📊 Progetti: {$row['c']}</div>";
        } else {
            echo "<div class='error'>❌ Database: " . $mysqli->connect_error . "</div>";
        }
    } catch(Exception $e) {
        echo "<div class='error'>❌ " . $e->getMessage() . "</div>";
    }
    ?>
    
    <h3>🔌 Test API:</h3>
    <button onclick="testAPI()">Test API Laravel</button>
    <button onclick="testLogin()">Test Login</button>
    <button onclick="window.open('/', '_blank')">Apri Frontend</button>
    
    <div id="results"></div>
    
    <script>
    async function testAPI() {
        const res = document.getElementById('results');
        res.innerHTML = '<div class="info">🔄 Testing API...</div>';
        
        try {
            const response = await fetch('/api/v1/test');
            const data = await response.json();
            
            if (response.ok) {
                res.innerHTML = '<div class="success">✅ API Laravel funziona!</div><pre>' + JSON.stringify(data, null, 2) + '</pre>';
            } else {
                res.innerHTML = '<div class="error">❌ API Error: ' + response.status + '</div>';
            }
        } catch(error) {
            res.innerHTML = '<div class="error">❌ Errore: ' + error.message + '</div>';
        }
    }
    
    async function testLogin() {
        const res = document.getElementById('results');
        res.innerHTML = '<div class="info">🔄 Testing Login...</div>';
        
        try {
            const response = await fetch('/api/v1/auth/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    email: 'admin@example.com',
                    password: 'password'
                })
            });
            
            const data = await response.json();
            
            if (response.ok) {
                res.innerHTML = '<div class="success">✅ Login endpoint raggiungibile!</div><pre>' + JSON.stringify(data, null, 2) + '</pre>';
            } else {
                res.innerHTML = '<div class="info">ℹ️ Login endpoint OK (credenziali test): ' + response.status + '</div><pre>' + JSON.stringify(data, null, 2) + '</pre>';
            }
        } catch(error) {
            res.innerHTML = '<div class="error">❌ Errore Login: ' + error.message + '</div>';
        }
    }
    </script>
    
    <p><strong>Istruzioni:</strong></p>
    <ol>
        <li>Verifica che tutti i file siano ✅</li>
        <li>Testa l'API Laravel</li>
        <li>Apri il frontend e verifica che CSS e login funzionino</li>
    </ol>
</body>
</html> 