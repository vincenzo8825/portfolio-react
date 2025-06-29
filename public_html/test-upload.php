<?php
header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Test Upload Files</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .success { color: green; }
        .error { color: red; }
        .info { color: blue; }
    </style>
</head>
<body>
    <h1>🔧 Test Upload Files - <?= date('H:i:s') ?></h1>
    
    <?php
    $files_to_check = [
        'index.html' => 'index-CXYiKPGX.js',
        'assets/index-CXYiKPGX.js' => 'ProjectDetail',
        'assets/index-CoZPpuo8.css' => 'Tailwind',
        'test-modifiche-finali.php' => 'Test completo'
    ];
    
    echo '<h2>📁 Verifica Files Caricati</h2>';
    
    foreach ($files_to_check as $file => $search) {
        $exists = file_exists($file);
        $class = $exists ? 'success' : 'error';
        $status = $exists ? '✅' : '❌';
        
        echo "<p class='$class'>$status <strong>$file</strong>";
        
        if ($exists && $search) {
            $content = file_get_contents($file);
            $has_content = strpos($content, $search) !== false;
            $content_status = $has_content ? '✅' : '❌';
            echo " - Contenuto '$search': $content_status";
        }
        
        echo "</p>";
    }
    
    // Test dimensioni file
    echo '<h2>📊 Dimensioni Files</h2>';
    if (file_exists('assets/index-CXYiKPGX.js')) {
        $size = filesize('assets/index-CXYiKPGX.js');
        echo "<p class='info'>📄 index-CXYiKPGX.js: " . number_format($size / 1024, 2) . " KB</p>";
    }
    
    if (file_exists('assets/index-CoZPpuo8.css')) {
        $size = filesize('assets/index-CoZPpuo8.css');
        echo "<p class='info'>🎨 index-CoZPpuo8.css: " . number_format($size / 1024, 2) . " KB</p>";
    }
    
    // Test timestamp
    echo '<h2>⏰ Timestamp Files</h2>';
    if (file_exists('index.html')) {
        $time = filemtime('index.html');
        echo "<p class='info'>🕐 index.html: " . date('d/m/Y H:i:s', $time) . "</p>";
    }
    
    if (file_exists('assets/index-CXYiKPGX.js')) {
        $time = filemtime('assets/index-CXYiKPGX.js');
        echo "<p class='info'>🕐 JavaScript: " . date('d/m/Y H:i:s', $time) . "</p>";
    }
    
    echo '<h2>🌐 Link Test</h2>';
    echo '<p><a href="/" target="_blank">🏠 Home Page</a></p>';
    echo '<p><a href="/test-modifiche-finali.php" target="_blank">🧪 Test Completo</a></p>';
    ?>
    
</body>
</html> 