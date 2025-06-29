<?php
header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🧪 Test Final Bundle - Portfolio Vincenzo Rocca</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .test-box {
            background: white;
            padding: 20px;
            margin: 10px 0;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .success { border-left: 4px solid #4CAF50; }
        .warning { border-left: 4px solid #FF9800; }
        .error { border-left: 4px solid #f44336; }
        .code {
            background: #f4f4f4;
            padding: 10px;
            border-radius: 4px;
            font-family: monospace;
            margin: 10px 0;
        }
        .btn {
            background: #007cba;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
            display: inline-block;
            margin: 10px 5px;
        }
        .btn:hover {
            background: #005a8b;
        }
    </style>
</head>
<body>
    <h1>🧪 Test Final Bundle</h1>
    
    <div class="test-box success">
        <h2>🔗 Link Portfolio</h2>
        <a href="https://vincenzorocca.com" target="_blank" class="btn">
            📱 Apri Portfolio
        </a>
        <p>Clicca per aprire il portfolio e testare il nuovo bundle.</p>
    </div>

    <div class="test-box warning">
        <h2>📄 File Index.html</h2>
        <?php
        $indexPath = __DIR__ . '/index.html';
        if (file_exists($indexPath)) {
            $indexContent = file_get_contents($indexPath);
            
            // Cerca il bundle attuale
            if (preg_match('/index-([a-zA-Z0-9]+)\.js/', $indexContent, $matches)) {
                $bundleHash = $matches[1];
                echo "<p>✅ <strong>Bundle trovato:</strong> index-{$bundleHash}.js</p>";
                
                // Verifica se è il bundle nuovo
                if ($bundleHash === 'BEG0SLG8') {
                    echo "<p>🎉 <strong>NUOVO BUNDLE ATTIVO!</strong></p>";
                } else {
                    echo "<p>⚠️ <strong>Bundle diverso da quello appena buildato</strong></p>";
                }
            } else {
                echo "<p>❌ Bundle NON trovato in HTML</p>";
            }
        } else {
            echo "<p>❌ File index.html non trovato</p>";
        }
        ?>
    </div>

    <div class="test-box warning">
        <h2>📁 File Assets</h2>
        <?php
        $assetsPath = __DIR__ . '/assets';
        if (is_dir($assetsPath)) {
            $files = scandir($assetsPath);
            $jsFiles = array_filter($files, function($file) {
                return preg_match('/\.js$/', $file);
            });
            
            echo "<p><strong>File JavaScript trovati:</strong></p>";
            foreach ($jsFiles as $file) {
                $size = round(filesize($assetsPath . '/' . $file) / 1024, 1);
                echo "<div class=\"code\">📄 {$file} - {$size} KB</div>";
            }
        } else {
            echo "<p>❌ Directory assets non trovata</p>";
        }
        ?>
    </div>

    <div class="test-box success">
        <h2>🧪 Test JavaScript</h2>
        <p><strong>Istruzioni per il test:</strong></p>
        <ol>
            <li>Apri il portfolio dal link sopra</li>
            <li>Apri DevTools (F12) → Console</li>
            <li>Cerca questi messaggi:</li>
        </ol>
        <div class="code">
🔧 API configurata per: https://vincenzorocca.com/api/v1<br>
🔍 Environment: production<br>
🔧 VITE_API_BASE_URL: https://vincenzorocca.com/api/v1
        </div>
        <p><strong>Nel frontend dovresti vedere:</strong></p>
        <div class="code">
✅ Featured projects loaded successfully: X<br>
✅ Technologies loaded successfully: X
        </div>
    </div>

    <div class="test-box error">
        <h2>🎯 Se vedi ancora errori</h2>
        <p>Se vedi ancora errori "Failed to fetch", prova:</p>
        <ol>
            <li><strong>CTRL+SHIFT+R</strong> (super hard refresh)</li>
            <li>Svuota cache browser</li>
            <li>Modalità incognito</li>
        </ol>
    </div>

    <script>
    // Test configurazione API lato client
    console.log('🧪 Test Final Bundle caricato');
    console.log('📍 URL corrente:', window.location.href);
    console.log('🕐 Timestamp:', new Date().toISOString());
    
    // Verifica se fetch funziona
    fetch('https://vincenzorocca.com/api/v1/test')
        .then(response => {
            console.log('✅ Test API Response:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('✅ Test API Data:', data);
        })
        .catch(error => {
            console.error('❌ Test API Error:', error);
        });
    </script>
</body>
</html> 