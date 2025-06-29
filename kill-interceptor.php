<?php
// Script per eliminare definitivamente l'interceptor
echo "� KILLING INTERCEPTOR...\n";

// Elimina il file interceptor
if (file_exists('index.php')) {
    if (unlink('index.php')) {
        echo "✅ Interceptor eliminato: index.php\n";
    } else {
        echo "❌ Errore nell'eliminazione di index.php\n";
    }
} else {
    echo "ℹ️ index.php non trovato\n";
}

// Crea un nuovo index.php vuoto
file_put_contents('index.php', '<?php /* Interceptor disabilitato */ ?>');
echo "✅ Nuovo index.php vuoto creato\n";

echo "� Interceptor eliminato!\n";
?>
