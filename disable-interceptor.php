<?php
// Script per disabilitare definitivamente l'interceptor
echo "🔧 Disabilitando interceptor...\n";

$interceptorPath = '/api/index.php';
$backupPath = '/api/index.php.DISABLED';

// Rinomina il file interceptor per disabilitarlo
if (file_exists($interceptorPath)) {
    if (rename($interceptorPath, $backupPath)) {
        echo "✅ Interceptor disabilitato: $interceptorPath -> $backupPath\n";
    } else {
        echo "❌ Errore nel rinominare interceptor\n";
    }
} else {
    echo "ℹ️ Interceptor già disabilitato o non trovato\n";
}

// Verifica che Laravel sia accessibile
$laravelPath = '/api/public/index.php';
if (file_exists($laravelPath)) {
    echo "✅ Laravel index.php trovato: $laravelPath\n";
} else {
    echo "❌ Laravel index.php non trovato: $laravelPath\n";
}

echo "🎉 Operazione completata!\n";
?> 