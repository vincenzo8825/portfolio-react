<?php
echo "🗑️ RIMOZIONE INTERCEPTOR E SETUP LARAVEL\n";
echo "==========================================\n\n";

// Rimuovi l'interceptor
if (file_exists('api/index.php')) {
    unlink('api/index.php');
    echo "✅ Interceptor rimosso\n";
} else {
    echo "ℹ️ Interceptor già rimosso\n";
}

// Verifica che Laravel sia presente
if (file_exists('api/public/index.php')) {
    echo "✅ Laravel index.php presente\n";
} else {
    echo "❌ Laravel index.php NON presente\n";
}

// Test diretto del file Laravel
echo "\n🧪 Test diretto Laravel:\n";
echo "File: api/public/index.php\n";
if (file_exists('api/public/index.php')) {
    $content = file_get_contents('api/public/index.php');
    echo "Dimensione: " . strlen($content) . " bytes\n";
    echo "Prime righe:\n";
    echo substr($content, 0, 200) . "...\n";
} else {
    echo "File non trovato!\n";
}

echo "\n✅ Rimozione interceptor completata!\n";
echo "Ora testa: https://vincenzorocca.com/api/v1/test\n";
?> 