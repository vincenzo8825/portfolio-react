<?php
header('Content-Type: text/html; charset=utf-8');

echo "<h1>🚀 TEST IMMEDIATO - STATO SISTEMA</h1>";

// 1. Test file caricati
echo "<h2>📁 FILE CARICATI</h2>";
$files = [
    'fix-login-immediato.php' => 'Fix Login',
    'verifica-database-completo.php' => 'Verifica Database', 
    'test-login-debug.php' => 'Debug Login',
    'database-hostinger-completo.sql' => 'Database SQL',
    'api/index.php' => 'API Aggiornata',
    'index.html' => 'Frontend',
    'assets/index-CXYiKPGX.js' => 'JavaScript'
];

foreach ($files as $file => $desc) {
    if (file_exists($file)) {
        $size = round(filesize($file) / 1024, 2);
        echo "<p>✅ <strong>$desc</strong>: $file ($size KB)</p>";
    } else {
        echo "<p>❌ <strong>$desc</strong>: $file NON TROVATO</p>";
    }
}

// 2. Test connessione database
echo "<h2>🗄️ TEST DATABASE</h2>";
try {
    $host = 'localhost';
    $dbname = 'u336414084_portfolioVince';
    $username = 'u336414084_vincenzorocca8';
    $password = 'Ciaociao52.?';
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", 
                   $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
    echo "<p>✅ <strong>Connessione database OK</strong></p>";
    
    // Test tabelle
    $tables = ['users', 'projects', 'technologies'];
    foreach ($tables as $table) {
        try {
            $stmt = $pdo->query("SELECT COUNT(*) FROM `$table`");
            $count = $stmt->fetchColumn();
            echo "<p>✅ <strong>Tabella $table</strong>: $count record</p>";
        } catch (Exception $e) {
            echo "<p>❌ <strong>Tabella $table</strong>: NON ESISTE</p>";
        }
    }
    
} catch (Exception $e) {
    echo "<p>❌ <strong>Errore database</strong>: " . $e->getMessage() . "</p>";
}

// 3. Test API
echo "<h2>🌐 TEST API</h2>";
$apiUrl = 'https://vincenzorocca.com/api/v1/projects';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "<p><strong>Test API progetti</strong>: HTTP $httpCode</p>";
if ($httpCode === 200) {
    echo "<p>✅ <strong>API funziona</strong></p>";
} else {
    echo "<p>❌ <strong>API non funziona</strong></p>";
}

// 4. Links utili
echo "<h2>🔗 LINKS UTILI</h2>";
echo "<ul>";
echo "<li><a href='fix-login-immediato.php' target='_blank'>🔧 Fix Login Immediato</a></li>";
echo "<li><a href='verifica-database-completo.php' target='_blank'>🔍 Verifica Database</a></li>";
echo "<li><a href='test-login-debug.php' target='_blank'>🐛 Debug Login</a></li>";
echo "<li><a href='https://vincenzorocca.com' target='_blank'>🏠 Sito Principale</a></li>";
echo "<li><a href='https://vincenzorocca.com/admin/login' target='_blank'>👤 Login Admin</a></li>";
echo "</ul>";

echo "<hr>";
echo "<p><strong>🎯 PROSSIMO PASSO:</strong> Clicca su 'Fix Login Immediato' per risolvere il problema del login!</p>";
?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; line-height: 1.6; }
h1, h2 { color: #333; }
a { color: #007cba; text-decoration: none; }
a:hover { text-decoration: underline; }
</style> 