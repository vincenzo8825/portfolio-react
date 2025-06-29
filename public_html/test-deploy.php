<?php
// TEST DEPLOY - Verifica completa del deployment
// ==============================================

header('Content-Type: text/html; charset=utf-8');
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>🧪 Test Deploy</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; }
        .success { color: #28a745; font-weight: bold; }
        .error { color: #dc3545; font-weight: bold; }
        .warning { color: #ffc107; font-weight: bold; }
        .test { margin: 15px 0; padding: 15px; border-radius: 5px; background: #f8f9fa; }
        .test.success { background: #d4edda; }
        .test.error { background: #f8d7da; }
    </style>
</head>
<body>

<div class="container">
    <h1>🧪 Test Deploy Portfolio</h1>
    <p><strong>Timestamp:</strong> <?= date('Y-m-d H:i:s') ?></p>

    <h2>📁 Test Struttura File</h2>
    <?php
    $files = [
        'index.html' => 'Frontend React',
        '.htaccess' => 'Routing',
        'api/.env' => 'Config Laravel',
        'api/public/index.php' => 'Laravel Entry',
        'api/vendor/autoload.php' => 'Composer'
    ];
    
    foreach ($files as $file => $desc) {
        $exists = file_exists(__DIR__ . '/' . $file);
        $class = $exists ? 'success' : 'error';
        $icon = $exists ? '✅' : '❌';
        echo "<div class='test $class'>$icon <strong>$desc:</strong> $file</div>";
    }
    ?>

    <h2>🔧 Test Laravel</h2>
    <?php
    $apiDir = __DIR__ . '/api';
    if (is_dir($apiDir)) {
        $oldDir = getcwd();
        chdir($apiDir);
        
        // Test autoloader
        if (file_exists('vendor/autoload.php')) {
            try {
                require_once 'vendor/autoload.php';
                echo "<div class='test success'>✅ <strong>Autoloader:</strong> OK</div>";
            } catch (Exception $e) {
                echo "<div class='test error'>❌ <strong>Autoloader:</strong> " . $e->getMessage() . "</div>";
            }
        } else {
            echo "<div class='test error'>❌ <strong>Autoloader:</strong> vendor/autoload.php mancante</div>";
        }
        
        // Test bootstrap
        if (file_exists('bootstrap/app.php')) {
            try {
                $app = require_once 'bootstrap/app.php';
                echo "<div class='test success'>✅ <strong>Bootstrap:</strong> " . get_class($app) . "</div>";
            } catch (Exception $e) {
                echo "<div class='test error'>❌ <strong>Bootstrap:</strong> " . $e->getMessage() . "</div>";
            }
        }
        
        chdir($oldDir);
    }
    ?>

    <h2>🌐 Test URL</h2>
    <?php
    function testUrl($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $response = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        return $status;
    }
    
    $urls = [
        'https://vincenzorocca.com/' => 'Homepage',
        'https://vincenzorocca.com/api/v1/test' => 'API Test',
        'https://vincenzorocca.com/api/public/index.php' => 'Laravel Direct'
    ];
    
    foreach ($urls as $url => $desc) {
        $status = testUrl($url);
        $class = ($status == 200) ? 'success' : 'error';
        $icon = ($status == 200) ? '✅' : '❌';
        echo "<div class='test $class'>$icon <strong>$desc:</strong> Status $status</div>";
    }
    ?>

    <h2>📋 Checklist</h2>
    <p>✅ Elimina tutto il contenuto di public_html/ su Hostinger</p>
    <p>✅ Carica tutto il contenuto di public_html_final/</p>
    <p>⚠️ Configura le credenziali in api/.env</p>
    <p>⚠️ Imposta permessi 755 su api/storage/</p>
    <p>✅ Testa il sito web</p>

    <div style="text-align: center; margin-top: 30px;">
        <a href="<?= $_SERVER['PHP_SELF'] ?>" style="color: #007bff;">🔄 Ricarica Test</a>
    </div>
</div>

</body>
</html> 