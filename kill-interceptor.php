<?php
// Script per eliminare definitivamente l'interceptor
echo "í´¥ KILLING INTERCEPTOR...\n";

// Elimina il file interceptor
if (file_exists('index.php')) {
    if (unlink('index.php')) {
        echo "âœ… Interceptor eliminato: index.php\n";
    } else {
        echo "âŒ Errore nell'eliminazione di index.php\n";
    }
} else {
    echo "â„¹ï¸ index.php non trovato\n";
}

// Crea un nuovo index.php vuoto
file_put_contents('index.php', '<?php /* Interceptor disabilitato */ ?>');
echo "âœ… Nuovo index.php vuoto creato\n";

echo "í¾‰ Interceptor eliminato!\n";
?>
