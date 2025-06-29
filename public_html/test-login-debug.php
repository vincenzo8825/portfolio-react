<?php
header('Content-Type: text/html; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

echo "<h1>🔧 DEBUG LOGIN API</h1>";

// Test connessione database
try {
    $host = 'localhost';
    $dbname = 'u336414084_portfolioVince';
    $username = 'u336414084_portfolioVince';
    $password = 'Ciaociao52.?';
    
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ]);
    
    echo "<p>✅ <strong>Connessione database OK</strong></p>";
    
} catch (Exception $e) {
    echo "<p>❌ <strong>Errore connessione database:</strong> " . $e->getMessage() . "</p>";
    exit;
}

// Test se esiste la tabella users
try {
    $stmt = $pdo->query("DESCRIBE users");
    $columns = $stmt->fetchAll();
    echo "<p>✅ <strong>Tabella users trovata</strong></p>";
    echo "<details><summary>Colonne tabella users:</summary><pre>";
    foreach ($columns as $col) {
        echo $col['Field'] . " - " . $col['Type'] . "\n";
    }
    echo "</pre></details>";
} catch (Exception $e) {
    echo "<p>❌ <strong>Errore tabella users:</strong> " . $e->getMessage() . "</p>";
}

// Test se esiste l'utente admin
try {
    $stmt = $pdo->prepare("SELECT id, name, email, is_admin FROM users WHERE email = ?");
    $stmt->execute(['vincenzorocca88@gmail.com']);
    $user = $stmt->fetch();
    
    if ($user) {
        echo "<p>✅ <strong>Utente admin trovato:</strong></p>";
        echo "<pre>";
        print_r($user);
        echo "</pre>";
    } else {
        echo "<p>❌ <strong>Utente admin NON trovato</strong></p>";
        
        // Mostra tutti gli utenti
        $stmt = $pdo->query("SELECT id, name, email, is_admin FROM users");
        $users = $stmt->fetchAll();
        echo "<p><strong>Utenti esistenti:</strong></p>";
        echo "<pre>";
        print_r($users);
        echo "</pre>";
    }
} catch (Exception $e) {
    echo "<p>❌ <strong>Errore ricerca utente:</strong> " . $e->getMessage() . "</p>";
}

// Test password hash
echo "<h2>🔐 Test Password</h2>";
$testPassword = 'admin123';
$hashedPassword = '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';

echo "<p><strong>Password test:</strong> $testPassword</p>";
echo "<p><strong>Hash database:</strong> $hashedPassword</p>";

if (password_verify($testPassword, $hashedPassword)) {
    echo "<p>✅ <strong>Password hash VALIDO</strong></p>";
} else {
    echo "<p>❌ <strong>Password hash NON VALIDO</strong></p>";
    
    // Genera nuovo hash
    $newHash = password_hash($testPassword, PASSWORD_DEFAULT);
    echo "<p><strong>Nuovo hash generato:</strong> $newHash</p>";
}

// Test API login simulato
echo "<h2>🧪 Test Login Simulato</h2>";

try {
    $email = 'vincenzorocca88@gmail.com';
    $password = 'admin123';
    
    echo "<p><strong>Tentativo login con:</strong></p>";
    echo "<p>Email: $email</p>";
    echo "<p>Password: $password</p>";
    
    // Cerca utente
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if (!$user) {
        echo "<p>❌ <strong>Utente non trovato</strong></p>";
    } else {
        echo "<p>✅ <strong>Utente trovato</strong></p>";
        
        // Verifica password
        if (password_verify($password, $user['password'])) {
            echo "<p>✅ <strong>Password corretta</strong></p>";
            
            // Simula risposta API
            $response = [
                'success' => true,
                'message' => 'Login effettuato con successo',
                'user' => [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'is_admin' => (bool)$user['is_admin']
                ],
                'token' => 'fake_token_for_test'
            ];
            
            echo "<p>✅ <strong>Risposta API simulata:</strong></p>";
            echo "<pre>";
            echo json_encode($response, JSON_PRETTY_PRINT);
            echo "</pre>";
            
        } else {
            echo "<p>❌ <strong>Password errata</strong></p>";
        }
    }
    
} catch (Exception $e) {
    echo "<p>❌ <strong>Errore test login:</strong> " . $e->getMessage() . "</p>";
}

// Test chiamata API reale
echo "<h2>🌐 Test API Reale</h2>";

$apiUrl = 'https://vincenzorocca.com/api/v1/auth/login';
$postData = json_encode([
    'email' => 'vincenzorocca88@gmail.com',
    'password' => 'admin123'
]);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

echo "<p><strong>URL API:</strong> $apiUrl</p>";
echo "<p><strong>HTTP Code:</strong> $httpCode</p>";

if ($error) {
    echo "<p>❌ <strong>Errore cURL:</strong> $error</p>";
} else {
    echo "<p>✅ <strong>Risposta API:</strong></p>";
    echo "<pre>";
    echo htmlspecialchars($response);
    echo "</pre>";
    
    // Prova a decodificare JSON
    $decoded = json_decode($response, true);
    if ($decoded) {
        echo "<p><strong>JSON decodificato:</strong></p>";
        echo "<pre>";
        print_r($decoded);
        echo "</pre>";
    }
}

echo "<hr>";
echo "<p><strong>🔍 Accedi a questo file per vedere i dettagli:</strong> <a href='https://vincenzorocca.com/test-login-debug.php'>https://vincenzorocca.com/test-login-debug.php</a></p>";
?> 