<?php
// Script per riorganizzare il deploy del portfolio
echo "🔄 Riorganizzazione Deploy Portfolio\n";
echo "=====================================\n\n";

// Definisco la struttura corretta
$source_dir = 'public_html_final';
$target_dir = 'public_html';

// File e cartelle da copiare nella root
$root_files = [
    'index.html',
    '.htaccess',
    'favicon.ico',
    'android-chrome-192x192.png',
    'android-chrome-512x512.png',
    'apple-touch-icon.png',
    'favicon-32x32.png',
    'site.webmanifest',
    'vite.svg'
];

// Cartelle da copiare completamente
$directories = [
    'assets',
    'api'
];

// File di test e debug utili
$test_files = [
    'test-completo-corretto.php',
    'fix-deploy.php',
    'test-api-simple.php'
];

echo "1. Creazione directory target...\n";
if (!is_dir($target_dir)) {
    mkdir($target_dir, 0755, true);
    echo "✅ Creata directory: $target_dir\n";
} else {
    echo "ℹ️ Directory $target_dir già esistente\n";
}

echo "\n2. Copia file root...\n";
foreach ($root_files as $file) {
    $source = "$source_dir/$file";
    $target = "$target_dir/$file";
    
    if (file_exists($source)) {
        copy($source, $target);
        echo "✅ Copiato: $file\n";
    } else {
        echo "⚠️ Non trovato: $file\n";
    }
}

echo "\n3. Copia directory...\n";
foreach ($directories as $dir) {
    $source_path = "$source_dir/$dir";
    $target_path = "$target_dir/$dir";
    
    if (is_dir($source_path)) {
        echo "📂 Copiando directory: $dir\n";
        copyDirectory($source_path, $target_path);
        echo "✅ Completata: $dir\n";
    } else {
        echo "⚠️ Directory non trovata: $dir\n";
    }
}

echo "\n4. Copia file di test...\n";
foreach ($test_files as $file) {
    $source = "$source_dir/$file";
    $target = "$target_dir/$file";
    
    if (file_exists($source)) {
        copy($source, $target);
        echo "✅ Copiato test: $file\n";
    } else {
        echo "⚠️ Test non trovato: $file\n";
    }
}

echo "\n5. Correzioni specifiche...\n";

// Correggi il file .env se necessario
$env_file = "$target_dir/api/.env";
$env_production = "$target_dir/api/env-production.txt";

if (!file_exists($env_file) && file_exists($env_production)) {
    copy($env_production, $env_file);
    echo "✅ Creato .env da env-production.txt\n";
    
    // Correggi le credenziali
    $env_content = file_get_contents($env_file);
    $env_content = str_replace(
        'DB_USERNAME=u336414084_portfolioVince',
        'DB_USERNAME=u336414084_vincenzorocca8',
        $env_content
    );
    file_put_contents($env_file, $env_content);
    echo "✅ Corrette credenziali database nel .env\n";
}

// Crea directory uploads se non esiste
$uploads_dir = "$target_dir/api/uploads";
if (!is_dir($uploads_dir)) {
    mkdir($uploads_dir, 0755, true);
    echo "✅ Creata directory uploads\n";
}

echo "\n6. Pulizia file non necessari...\n";

// Rimuovi file di backup e temporanei dalla directory API
$cleanup_patterns = [
    "$target_dir/api/*backup*",
    "$target_dir/api/*temp*",
    "$target_dir/api/*.tar.gz",
    "$target_dir/api/index-*.php"
];

foreach ($cleanup_patterns as $pattern) {
    $files = glob($pattern);
    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file);
            echo "🗑️ Rimosso: " . basename($file) . "\n";
        }
    }
}

echo "\n✅ RIORGANIZZAZIONE COMPLETATA!\n";
echo "=====================================\n";
echo "📂 Directory pronta: $target_dir/\n";
echo "📋 Contenuto:\n";
echo "   - Frontend React (index.html + assets/)\n";
echo "   - API Backend (api/)\n";
echo "   - File di configurazione (.htaccess)\n";
echo "   - File di test e debug\n\n";
echo "🚀 Puoi ora caricare la cartella '$target_dir' direttamente su Hostinger!\n";

// Funzione per copiare directory ricorsivamente
function copyDirectory($source, $destination) {
    if (!is_dir($destination)) {
        mkdir($destination, 0755, true);
    }
    
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );
    
    foreach ($iterator as $item) {
        $target = $destination . DIRECTORY_SEPARATOR . $iterator->getSubPathName();
        
        if ($item->isDir()) {
            if (!is_dir($target)) {
                mkdir($target, 0755, true);
            }
        } else {
            copy($item, $target);
        }
    }
}

// Crea anche un file README per la directory
$readme_content = "# Portfolio Vincenzo Rocca - Deploy Ready

Questa cartella contiene la versione corretta e riorganizzata del portfolio, pronta per essere caricata su Hostinger.

## Struttura:
- `index.html` - Frontend React
- `assets/` - CSS, JS e altri asset
- `api/` - Backend API con database
- `.htaccess` - Configurazione routing
- File di test per verifica funzionamento

## Istruzioni:
1. Carica tutto il contenuto di questa cartella nella directory `/public_html/` su Hostinger
2. Verifica che il file `api/.env` esista (se non c'è, rinomina `api/env-production.txt`)
3. Crea la directory `api/uploads/` con permessi 755
4. Testa il funzionamento con `/test-completo-corretto.php`

## Credenziali Database:
- Host: localhost
- Database: u336414084_portfolioVince  
- Username: u336414084_vincenzorocca8
- Password: Ciaociao52.?

Generato automaticamente il " . date('Y-m-d H:i:s') . "
";

file_put_contents("$target_dir/README-DEPLOY.md", $readme_content);
echo "📝 Creato README-DEPLOY.md\n";
?> 