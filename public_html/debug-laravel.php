<?php
echo "🔍 DEBUG STRUTTURA LARAVEL\n";
echo "==========================\n\n";

// Verifica file principali
$files = [
    '.htaccess' => '.',
    'api/public/index.php' => 'api/public',
    'api/public/.htaccess' => 'api/public',
    'api/artisan' => 'api',
    'api/composer.json' => 'api'
];

foreach ($files as $file => $desc) {
    if (file_exists($file)) {
        echo "✅ $file esiste (" . filesize($file) . " bytes)\n";
    } else {
        echo "❌ $file NON esiste\n";
    }
}

// Verifica directory
$dirs = [
    'api',
    'api/app',
    'api/app/Http',
    'api/app/Http/Controllers',
    'api/app/Http/Controllers/Api',
    'api/config',
    'api/routes',
    'api/database',
    'api/public'
];

echo "\n📁 DIRECTORY:\n";
foreach ($dirs as $dir) {
    if (is_dir($dir)) {
        $count = count(scandir($dir)) - 2; // Escludi . e ..
        echo "✅ $dir esiste ($count files)\n";
    } else {
        echo "❌ $dir NON esiste\n";
    }
}

// Contenuto .htaccess
echo "\n📄 CONTENUTO .htaccess:\n";
if (file_exists('.htaccess')) {
    echo file_get_contents('.htaccess');
} else {
    echo "File non trovato\n";
}

echo "\n\n📄 CONTENUTO api/public/.htaccess:\n";
if (file_exists('api/public/.htaccess')) {
    echo file_get_contents('api/public/.htaccess');
} else {
    echo "File non trovato\n";
}

echo "\n\n🧪 TEST DIRETTO:\n";
echo "URL corrente: " . $_SERVER['REQUEST_URI'] . "\n";
echo "Server: " . $_SERVER['SERVER_NAME'] . "\n";

?> 