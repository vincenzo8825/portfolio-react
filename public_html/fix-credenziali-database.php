<?php
header('Content-Type: text/html; charset=utf-8');

echo "<h1>🔧 FIX CREDENZIALI DATABASE</h1>";

// Prova diverse combinazioni di credenziali
$credenziali = [
    [
        'host' => 'localhost',
        'dbname' => 'u336414084_portfolioVince', 
        'username' => 'u336414084_portfolioVince',
        'password' => 'Ciaociao52.?'
    ],
    [
        'host' => 'localhost',
        'dbname' => 'u336414084_portfolioVince',
        'username' => 'u336414084',
        'password' => 'Ciaociao52.?'
    ],
    [
        'host' => 'localhost', 
        'dbname' => 'u336414084_portfolioVince',
        'username' => 'u336414084_portfolioVince',
        'password' => 'Ciaociao5.3n'
    ],
    [
        'host' => 'localhost',
        'dbname' => 'u336414084_portfolioVince',
        'username' => 'u336414084',
        'password' => 'Ciaociao5.3n'
    ]
];

$connessioneOK = false;
$credentialiGiuste = null;

foreach ($credenziali as $i => $cred) {
    echo "<h3>🔍 Test #" . ($i + 1) . "</h3>";
    echo "<p><strong>Host:</strong> {$cred['host']}</p>";
    echo "<p><strong>Database:</strong> {$cred['dbname']}</p>";
    echo "<p><strong>Username:</strong> {$cred['username']}</p>";
    echo "<p><strong>Password:</strong> {$cred['password']}</p>";
    
    try {
        $pdo = new PDO("mysql:host={$cred['host']};dbname={$cred['dbname']};charset=utf8mb4", 
                       $cred['username'], $cred['password'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]);
        
        echo "<p>✅ <strong>CONNESSIONE RIUSCITA!</strong></p>";
        $connessioneOK = true;
        $credentialiGiuste = $cred;
        
        // Test rapido tabelle
        try {
            $stmt = $pdo->query("SHOW TABLES");
            $tables = $stmt->fetchAll();
            echo "<p><strong>Tabelle trovate:</strong> " . count($tables) . "</p>";
            
            if (count($tables) > 0) {
                echo "<ul>";
                foreach ($tables as $table) {
                    $tableName = array_values($table)[0];
                    echo "<li>$tableName</li>";
                }
                echo "</ul>";
            } else {
                echo "<p>⚠️ <strong>Database vuoto - necessario importare SQL</strong></p>";
            }
            
        } catch (Exception $e) {
            echo "<p>⚠️ <strong>Errore lettura tabelle:</strong> " . $e->getMessage() . "</p>";
        }
        
        break; // Ferma al primo successo
        
    } catch (Exception $e) {
        echo "<p>❌ <strong>ERRORE:</strong> " . $e->getMessage() . "</p>";
    }
    
    echo "<hr>";
}

if ($connessioneOK && $credentialiGiuste) {
    echo "<div style='background: #d4edda; border: 1px solid #c3e6cb; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
    echo "<h2 style='color: #155724; margin: 0;'>🎉 CREDENZIALI CORRETTE TROVATE!</h2>";
    echo "<p style='color: #155724;'><strong>Host:</strong> {$credentialiGiuste['host']}</p>";
    echo "<p style='color: #155724;'><strong>Database:</strong> {$credentialiGiuste['dbname']}</p>";
    echo "<p style='color: #155724;'><strong>Username:</strong> {$credentialiGiuste['username']}</p>";
    echo "<p style='color: #155724;'><strong>Password:</strong> {$credentialiGiuste['password']}</p>";
    echo "</div>";
    
    // Ora testa il login con le credenziali giuste
    echo "<h2>🧪 TEST LOGIN CON CREDENZIALI CORRETTE</h2>";
    
    try {
        // Verifica se esiste la tabella users
        $stmt = $pdo->query("SHOW TABLES LIKE 'users'");
        $userTableExists = $stmt->rowCount() > 0;
        
        if (!$userTableExists) {
            echo "<p>❌ <strong>Tabella users non esiste</strong></p>";
            echo "<p>🔧 <strong>Creo tabella users...</strong></p>";
            
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
            echo "<p>✅ <strong>Tabella users creata</strong></p>";
        }
        
        // Verifica utente admin
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute(['vincenzorocca88@gmail.com']);
        $user = $stmt->fetch();
        
        if (!$user) {
            echo "<p>➕ <strong>Creo utente admin...</strong></p>";
            
            $hashedPassword = password_hash('admin123', PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("
                INSERT INTO users (name, email, is_admin, password, created_at, updated_at) 
                VALUES (?, ?, ?, ?, NOW(), NOW())
            ");
            $stmt->execute(['Vincenzo Rocca', 'vincenzorocca88@gmail.com', 1, $hashedPassword]);
            echo "<p>✅ <strong>Utente admin creato</strong></p>";
        } else {
            echo "<p>✅ <strong>Utente admin già esiste</strong></p>";
            
            // Aggiorna password per essere sicuri
            $newHashedPassword = password_hash('admin123', PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET password = ?, updated_at = NOW() WHERE email = ?");
            $stmt->execute([$newHashedPassword, 'vincenzorocca88@gmail.com']);
            echo "<p>✅ <strong>Password admin aggiornata</strong></p>";
        }
        
        // Test login
        $stmt = $pdo->prepare("SELECT id, name, email, password, is_admin FROM users WHERE email = ?");
        $stmt->execute(['vincenzorocca88@gmail.com']);
        $user = $stmt->fetch();
        
        if ($user && password_verify('admin123', $user['password'])) {
            echo "<p>✅ <strong>LOGIN TEST SUCCESSO!</strong></p>";
            
            echo "<div style='background: #d1ecf1; border: 1px solid #bee5eb; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
            echo "<h3 style='color: #0c5460; margin: 0;'>🎉 TUTTO RISOLTO!</h3>";
            echo "<p style='color: #0c5460;'>Il login ora funziona perfettamente!</p>";
            echo "<p style='color: #0c5460;'><strong>Vai su:</strong> <a href='https://vincenzorocca.com/admin/login'>https://vincenzorocca.com/admin/login</a></p>";
            echo "<p style='color: #0c5460;'><strong>Email:</strong> vincenzorocca88@gmail.com</p>";
            echo "<p style='color: #0c5460;'><strong>Password:</strong> admin123</p>";
            echo "</div>";
            
        } else {
            echo "<p>❌ <strong>Login test fallito</strong></p>";
        }
        
    } catch (Exception $e) {
        echo "<p>❌ <strong>Errore test login:</strong> " . $e->getMessage() . "</p>";
    }
    
} else {
    echo "<div style='background: #f8d7da; border: 1px solid #f5c6cb; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
    echo "<h2 style='color: #721c24; margin: 0;'>❌ NESSUNA CREDENZIALE FUNZIONA</h2>";
    echo "<p style='color: #721c24;'>Controlla le credenziali del database nel pannello Hostinger.</p>";
    echo "</div>";
}
?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; line-height: 1.6; }
h1, h2, h3 { color: #333; }
ul { margin: 10px 0; padding-left: 20px; }
</style> 