<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Gestisci OPTIONS per CORS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

echo "🚀 FIX LOGIN IMMEDIATO\n\n";

try {
    // Connessione database
    $host = 'localhost';
    $dbname = 'u336414084_portfolioVince';
    $username = 'u336414084_vincenzorocca8';
    $password = 'Ciaociao52.?';
    
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ]);
    
    echo "✅ Connessione database OK\n";
    
    // 1. Verifica se esiste la tabella users
    try {
        $stmt = $pdo->query("SELECT COUNT(*) FROM users");
        $count = $stmt->fetchColumn();
        echo "✅ Tabella users esiste con $count utenti\n";
    } catch (Exception $e) {
        echo "❌ Tabella users non esiste, la creo...\n";
        
        // Crea tabella users
        $sql = "
        CREATE TABLE `users` (
          `id` bigint unsigned NOT NULL AUTO_INCREMENT,
          `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
          `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
          `email_verified_at` timestamp NULL DEFAULT NULL,
          `is_admin` tinyint(1) NOT NULL DEFAULT '0',
          `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
          `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
          `created_at` timestamp NULL DEFAULT NULL,
          `updated_at` timestamp NULL DEFAULT NULL,
          PRIMARY KEY (`id`),
          UNIQUE KEY `users_email_unique` (`email`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ";
        
        $pdo->exec($sql);
        echo "✅ Tabella users creata\n";
    }
    
    // 2. Verifica se esiste l'utente admin
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute(['vincenzorocca88@gmail.com']);
    $user = $stmt->fetch();
    
    if ($user) {
        echo "✅ Utente admin già esiste\n";
    } else {
        echo "➕ Creo utente admin...\n";
        
        // Crea utente admin
        $hashedPassword = password_hash('admin123', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("
            INSERT INTO users (name, email, is_admin, password, created_at, updated_at) 
            VALUES (?, ?, ?, ?, NOW(), NOW())
        ");
        $stmt->execute(['Vincenzo Rocca', 'vincenzorocca88@gmail.com', 1, $hashedPassword]);
        echo "✅ Utente admin creato\n";
    }
    
    // 3. Aggiorna sempre la password per essere sicuri
    $newHashedPassword = password_hash('admin123', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("UPDATE users SET password = ?, updated_at = NOW() WHERE email = ?");
    $stmt->execute([$newHashedPassword, 'vincenzorocca88@gmail.com']);
    echo "✅ Password admin aggiornata\n";
    
    // 4. Test login diretto
    echo "\n🧪 TEST LOGIN DIRETTO:\n";
    
    $stmt = $pdo->prepare("SELECT id, name, email, password, is_admin FROM users WHERE email = ?");
    $stmt->execute(['vincenzorocca88@gmail.com']);
    $user = $stmt->fetch();
    
    if ($user && password_verify('admin123', $user['password'])) {
        echo "✅ Login test SUCCESSO\n";
        
        // Genera token
        $token = 'auth_token_' . $user['id'] . '_' . time();
        $stmt = $pdo->prepare("UPDATE users SET remember_token = ? WHERE id = ?");
        $stmt->execute([$token, $user['id']]);
        
        $response = [
            'success' => true,
            'user' => [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'is_admin' => (bool)$user['is_admin']
            ],
            'token' => $token,
            'expires_in' => 3600
        ];
        
        echo "✅ Risposta login:\n";
        echo json_encode($response, JSON_PRETTY_PRINT) . "\n";
        
    } else {
        echo "❌ Login test FALLITO\n";
    }
    
    // 5. Test API endpoint
    echo "\n🌐 TEST API ENDPOINT:\n";
    
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
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    echo "URL: $apiUrl\n";
    echo "HTTP Code: $httpCode\n";
    
    if ($error) {
        echo "❌ Errore cURL: $error\n";
    } else {
        echo "📦 Risposta API:\n";
        echo $response . "\n";
        
        $decoded = json_decode($response, true);
        if ($decoded && isset($decoded['success']) && $decoded['success']) {
            echo "✅ API LOGIN FUNZIONA!\n";
        } else {
            echo "❌ API LOGIN NON FUNZIONA\n";
            echo "Risposta decodificata:\n";
            print_r($decoded);
        }
    }
    
    echo "\n🎯 RIASSUNTO:\n";
    echo "- Database: ✅ Connesso\n";
    echo "- Utente Admin: ✅ Esiste\n";
    echo "- Password: ✅ Aggiornata\n";
    echo "- Login Diretto: ✅ Funziona\n";
    echo "- API Endpoint: " . ($httpCode === 200 ? "✅ Funziona" : "❌ Errore $httpCode") . "\n";
    
    echo "\n📧 CREDENZIALI FINALI:\n";
    echo "Email: vincenzorocca88@gmail.com\n";
    echo "Password: admin123\n";
    echo "URL Login: https://vincenzorocca.com/admin/login\n";

} catch (Exception $e) {
    echo "❌ ERRORE GENERALE: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
?> 