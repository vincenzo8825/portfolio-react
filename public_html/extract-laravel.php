<?php
echo "🚀 ESTRAZIONE LARAVEL SU HOSTINGER\n";
echo "===================================\n\n";

$archives = [
    'api/app.tar.gz' => 'api/',
    'api/config.tar.gz' => 'api/',
    'api/routes.tar.gz' => 'api/',
    'api/database.tar.gz' => 'api/'
];

foreach ($archives as $archive => $destination) {
    if (file_exists($archive)) {
        echo "📦 Estraendo $archive in $destination...\n";
        
        // Estrai l'archivio
        $command = "cd $destination && tar -xzf " . basename($archive);
        $output = shell_exec($command . ' 2>&1');
        
        if ($output) {
            echo "   Output: $output\n";
        }
        
        // Rimuovi l'archivio dopo l'estrazione
        unlink($archive);
        echo "   ✅ $archive estratto e rimosso\n\n";
    } else {
        echo "   ❌ $archive non trovato\n\n";
    }
}

// Verifica che le directory esistano
$dirs = ['api/app', 'api/config', 'api/routes', 'api/database'];
foreach ($dirs as $dir) {
    if (is_dir($dir)) {
        echo "✅ $dir esiste\n";
    } else {
        echo "❌ $dir NON esiste\n";
    }
}

echo "\n🧪 Test Laravel...\n";
if (file_exists('api/public/index.php')) {
    echo "✅ Laravel index.php trovato\n";
} else {
    echo "❌ Laravel index.php NON trovato\n";
}

if (file_exists('api/app/Http/Controllers/Api/ProjectController.php')) {
    echo "✅ ProjectController trovato\n";
} else {
    echo "❌ ProjectController NON trovato\n";
}

echo "\n🎉 Estrazione completata!\n";
echo "Visita: https://vincenzorocca.com/extract-laravel.php per vedere questo output\n";
?> 