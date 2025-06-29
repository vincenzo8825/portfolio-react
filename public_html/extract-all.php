<?php
echo "🚀 ESTRAZIONE COMPLETA LARAVEL\n";
echo "===============================\n\n";

$archives = [
    'api/bootstrap.tar.gz' => 'api/',
    'api/vendor.tar.gz' => 'api/'
];

foreach ($archives as $archive => $destination) {
    if (file_exists($archive)) {
        echo "📦 Estraendo $archive...\n";
        
        $command = "cd $destination && tar -xzf " . basename($archive) . " 2>&1";
        $output = shell_exec($command);
        
        if ($output) {
            echo "   Output: $output\n";
        }
        
        unlink($archive);
        echo "   ✅ $archive estratto e rimosso\n\n";
    } else {
        echo "   ❌ $archive non trovato\n\n";
    }
}

// Verifica finale
$dirs = ['api/bootstrap', 'api/vendor', 'api/app', 'api/config'];
echo "🔍 VERIFICA FINALE:\n";
foreach ($dirs as $dir) {
    if (is_dir($dir)) {
        $count = count(scandir($dir)) - 2;
        echo "✅ $dir esiste ($count items)\n";
    } else {
        echo "❌ $dir NON esiste\n";
    }
}

echo "\n🧪 TEST LARAVEL:\n";
if (file_exists('api/bootstrap/app.php')) {
    echo "✅ Bootstrap app.php trovato\n";
} else {
    echo "❌ Bootstrap app.php NON trovato\n";
}

if (is_dir('api/vendor/laravel')) {
    echo "✅ Vendor Laravel trovato\n";
} else {
    echo "❌ Vendor Laravel NON trovato\n";
}

echo "\n✅ Estrazione completata!\n";
?> 