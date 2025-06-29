<?php
header('Content-Type: text/html; charset=utf-8');
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Ì∑™ Portfolio Complete Test</title>
    <style>
        body { font-family: Arial, sans-serif; background: #0d1117; color: #c9d1d9; margin: 20px; }
        .container { max-width: 1200px; margin: 0 auto; }
        .header { background: #238636; padding: 30px; border-radius: 10px; text-align: center; margin-bottom: 30px; }
        .section { background: #161b22; border: 1px solid #30363d; border-radius: 10px; margin: 20px 0; overflow: hidden; }
        .section-header { background: #21262d; padding: 20px; border-bottom: 1px solid #30363d; }
        .section-body { padding: 20px; }
        .test-item { margin: 10px 0; padding: 15px; border-radius: 8px; }
        .test-item.success { background: #0d4429; border-left: 4px solid #238636; }
        .test-item.error { background: #490202; border-left: 4px solid #da3633; }
        .test-item.warning { background: #332b00; border-left: 4px solid #f85149; }
        .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px; margin: 20px 0; }
        .stat-card { background: #161b22; border: 1px solid #30363d; padding: 20px; border-radius: 10px; text-align: center; }
        .stat-number { font-size: 2rem; font-weight: bold; color: #2ea043; }
        .api-response { background: #0d1117; padding: 10px; border-radius: 6px; font-family: monospace; font-size: 0.85rem; max-height: 200px; overflow-y: auto; margin-top: 10px; }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>Ì∑™ Portfolio Complete Test Suite</h1>
        <p>Test avanzato per React + Laravel + MySQL + Hostinger</p>
        <p><strong>Timestamp:</strong> <?= date('Y-m-d H:i:s') ?></p>
    </div>

    <?php
    $totalTests = 0;
    $passedTests = 0;
    $failedTests = 0;
    $warningTests = 0;
    
    function addTest($name, $status, $message, $details = '') {
        global $totalTests, $passedTests, $failedTests, $warningTests;
        $totalTests++;
        switch($status) {
            case 'success': $passedTests++; break;
            case 'error': $failedTests++; break;
            case 'warning': $warningTests++; break;
        }
        
        $icon = $status === 'success' ? '‚úÖ' : ($status === 'error' ? '‚ùå' : '‚ö†Ô∏è');
        echo "<div class='test-item $status'>";
        echo "<div><strong>$icon $name</strong></div>";
        echo "<div>$message</div>";
        if ($details) echo "<div style='opacity: 0.8; font-size: 0.9rem;'>$details</div>";
        echo "</div>";
    }
    
    function testUrl($url, $timeout = 10) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        
        $start = microtime(true);
        $response = curl_exec($ch);
        $end = microtime(true);
        
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        return [
            'status' => $status,
            'response' => $response,
            'error' => $error,
            'time' => round(($end - $start) * 1000, 2),
            'success' => !$error && $status == 200
        ];
    }
    ?>

    <!-- TEST 1: STRUTTURA FILES -->
    <div class="section">
        <div class="section-header">
            <h2>Ì≥Å Struttura Files & Directory</h2>
        </div>
        <div class="section-body">
            <?php
            $files = [
                'index.html' => 'Frontend React',
                '.htaccess' => 'Routing Config',
                'api/.env' => 'Laravel Config',
                'api/public/index.php' => 'Laravel Entry',
                'api/vendor/autoload.php' => 'Composer',
                'api/bootstrap/app.php' => 'Laravel Bootstrap',
                'api/routes/api.php' => 'API Routes',
                'api/app/Http/Controllers/Api/AuthController.php' => 'Auth Controller',
                'api/app/Http/Controllers/Api/ProjectController.php' => 'Project Controller',
                'api/app/Models/User.php' => 'User Model'
            ];
            
            foreach ($files as $file => $desc) {
                $path = __DIR__ . '/' . $file;
                if (file_exists($path)) {
                    $size = filesize($path);
                    $sizeFormatted = $size > 1024 ? round($size/1024, 1) . ' KB' : $size . ' B';
                    addTest($desc, 'success', "File presente ($sizeFormatted)", $file);
                } else {
                    addTest($desc, 'error', "File mancante", $file);
                }
            }
            ?>
        </div>
    </div>

    <!-- TEST 2: LARAVEL BOOTSTRAP -->
    <div class="section">
        <div class="section-header">
            <h2>Ì¥ß Laravel Bootstrap & Config</h2>
        </div>
        <div class="section-body">
            <?php
            $apiDir = __DIR__ . '/api';
            if (is_dir($apiDir)) {
                $oldDir = getcwd();
                chdir($apiDir);
                
                // Test autoloader
                if (file_exists('vendor/autoload.php')) {
                    try {
                        require_once 'vendor/autoload.php';
                        addTest('Composer Autoloader', 'success', 'Caricato correttamente');
                        
                        // Test bootstrap
                        if (file_exists('bootstrap/app.php')) {
                            try {
                                $app = require_once 'bootstrap/app.php';
                                if ($app && is_object($app)) {
                                    addTest('Laravel Bootstrap', 'success', 'App creata: ' . get_class($app));
                                } else {
                                    addTest('Laravel Bootstrap', 'error', 'Bootstrap non valido');
                                }
                            } catch (Exception $e) {
                                addTest('Laravel Bootstrap', 'error', $e->getMessage());
                            }
                        } else {
                            addTest('Laravel Bootstrap', 'error', 'File bootstrap/app.php mancante');
                        }
                    } catch (Exception $e) {
                        addTest('Composer Autoloader', 'error', $e->getMessage());
                    }
                } else {
                    addTest('Composer Autoloader', 'error', 'vendor/autoload.php mancante');
                }
                
                // Test .env
                if (file_exists('.env')) {
                    $envContent = file_get_contents('.env');
                    $hasAppKey = strpos($envContent, 'APP_KEY=base64:') !== false && strpos($envContent, 'YourLaravelAppKeyHere') === false;
                    $hasDbConfig = strpos($envContent, 'DB_DATABASE=u336414084_portfolioVince') !== false;
                    
                    if ($hasAppKey && $hasDbConfig) {
                        addTest('Environment Config', 'success', 'APP_KEY e DB configurati');
                    } else {
                        $missing = [];
                        if (!$hasAppKey) $missing[] = 'APP_KEY';
                        if (!$hasDbConfig) $missing[] = 'DB_CONFIG';
                        addTest('Environment Config', 'warning', 'Da configurare: ' . implode(', ', $missing));
                    }
                } else {
                    addTest('Environment Config', 'error', 'File .env mancante');
                }
                
                chdir($oldDir);
            }
            ?>
        </div>
    </div>

    <!-- TEST 3: API ENDPOINTS -->
    <div class="section">
        <div class="section-header">
            <h2>Ìºê API Endpoints Testing</h2>
        </div>
        <div class="section-body">
            <?php
            $baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];
            
            $endpoints = [
                '/api/v1/test' => 'API Test',
                '/api/v1/technologies' => 'Technologies API',
                '/api/v1/projects' => 'Projects API',
                '/api/public/index.php/api/v1/test' => 'Laravel Direct',
                '/' => 'Frontend Homepage',
                '/projects' => 'Projects Page',
                '/contact' => 'Contact Page'
            ];
            
            foreach ($endpoints as $endpoint => $desc) {
                $url = $baseUrl . $endpoint;
                $result = testUrl($url);
                
                if ($result['success']) {
                    addTest($desc, 'success', "Status {$result['status']} - {$result['time']}ms", $endpoint);
                    
                    if (strpos($endpoint, '/api/') !== false && $result['response']) {
                        $data = json_decode($result['response'], true);
                        if ($data) {
                            echo "<div class='api-response'>" . htmlspecialchars(json_encode($data, JSON_PRETTY_PRINT)) . "</div>";
                        }
                    }
                } else {
                    $status = $result['status'] >= 500 ? 'error' : 'warning';
                    addTest($desc, $status, "Status {$result['status']}" . ($result['error'] ? " - {$result['error']}" : ''), $endpoint);
                }
            }
            ?>
        </div>
    </div>

    <!-- STATISTICHE -->
    <div class="stats">
        <div class="stat-card">
            <div class="stat-number"><?= $totalTests ?></div>
            <div>Test Totali</div>
        </div>
        <div class="stat-card">
            <div class="stat-number" style="color: #2ea043;"><?= $passedTests ?></div>
            <div>Successi</div>
        </div>
        <div class="stat-card">
            <div class="stat-number" style="color: #f85149;"><?= $failedTests ?></div>
            <div>Errori</div>
        </div>
        <div class="stat-card">
            <div class="stat-number" style="color: #f85149;"><?= $warningTests ?></div>
            <div>Warning</div>
        </div>
        <?php $successRate = $totalTests > 0 ? round(($passedTests / $totalTests) * 100, 1) : 0; ?>
        <div class="stat-card">
            <div class="stat-number" style="color: <?= $successRate >= 80 ? '#2ea043' : '#f85149' ?>;"><?= $successRate ?>%</div>
            <div>Successo</div>
        </div>
    </div>

    <!-- RISULTATO FINALE -->
    <div class="section">
        <div class="section-header">
            <h2><?= $successRate >= 80 ? 'Ìæâ Deploy Pronto!' : 'Ì¥ß Azioni Richieste' ?></h2>
        </div>
        <div class="section-body" style="text-align: center;">
            <?php if ($successRate >= 80): ?>
                <div class="test-item success">
                    <div><strong>‚úÖ Portfolio Deploy Completato!</strong></div>
                    <div>Tutti i test principali sono passati. Portfolio pronto per produzione.</div>
                </div>
            <?php else: ?>
                <div class="test-item error">
                    <div><strong>Ì¥ß Deploy Incompleto</strong></div>
                    <div>Risolvi <?= $failedTests ?> errori critici prima del deploy.</div>
                </div>
            <?php endif; ?>
            
            <div style="margin-top: 20px;">
                <a href="<?= $_SERVER['PHP_SELF'] ?>" style="background: #238636; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; margin: 5px;">Ì¥Ñ Ricarica Test</a>
                <a href="/" style="background: #21262d; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; margin: 5px;">Ìø† Homepage</a>
                <a href="/admin" style="background: #21262d; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; margin: 5px;">‚ö° Admin</a>
            </div>
        </div>
    </div>

    <div style="text-align: center; margin-top: 30px; padding: 20px; background: #161b22; border-radius: 10px;">
        <p><strong>Portfolio Complete Test Suite</strong> | <?= date('Y-m-d H:i:s') ?></p>
        <p>Senior Developer Testing | React + Laravel + MySQL + Hostinger</p>
    </div>
</div>

</body>
</html>
