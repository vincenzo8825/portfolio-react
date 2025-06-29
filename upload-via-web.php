<?php
// Script per upload automatico dei file critici
echo "🚀 Upload file critici su Hostinger...\n";

// File da caricare
$files_to_upload = [
    'api/index.php' => '/public_html/api/index.php',
    'index.html' => '/public_html/index.html',
    'assets/index-af3vQNXx.js' => '/public_html/assets/index-af3vQNXx.js',
    'assets/index-Ci__Ne2l.css' => '/public_html/assets/index-Ci__Ne2l.css'
];

// Configurazione Hostinger
$hostinger_config = [
    'host' => 'vincenzorocca.com',
    'username' => 'u906936113',
    'password' => 'Vincenzo88!',
    'port' => 21
];

// Funzione per upload FTP
function uploadFile($local_file, $remote_file, $config) {
    if (!file_exists($local_file)) {
        echo "❌ File non trovato: $local_file\n";
        return false;
    }
    
    // Crea connessione FTP
    $ftp = ftp_connect($config['host'], $config['port']);
    if (!$ftp) {
        echo "❌ Impossibile connettersi al server FTP\n";
        return false;
    }
    
    // Login
    if (!ftp_login($ftp, $config['username'], $config['password'])) {
        echo "❌ Login FTP fallito\n";
        ftp_close($ftp);
        return false;
    }
    
    // Modalità passiva
    ftp_pasv($ftp, true);
    
    // Upload file
    if (ftp_put($ftp, $remote_file, $local_file, FTP_BINARY)) {
        echo "✅ Caricato: $local_file -> $remote_file\n";
        $success = true;
    } else {
        echo "❌ Errore caricamento: $local_file\n";
        $success = false;
    }
    
    ftp_close($ftp);
    return $success;
}

// Carica i file
foreach ($files_to_upload as $local => $remote) {
    uploadFile($local, $remote, $hostinger_config);
    sleep(1); // Pausa tra upload
}

echo "\n🧪 Test API dopo upload...\n";

// Test API
$test_urls = [
    'https://vincenzorocca.com/api/v1/projects/6',
    'https://vincenzorocca.com/api/v1/projects',
    'https://vincenzorocca.com'
];

foreach ($test_urls as $url) {
    echo "Testing: $url\n";
    $response = file_get_contents($url);
    if ($response) {
        $data = json_decode($response, true);
        if (isset($data['success']) && $data['success']) {
            echo "✅ API OK\n";
        } elseif (isset($data['error'])) {
            echo "❌ API Error: " . $data['error'] . "\n";
        } else {
            echo "📄 HTML Response (length: " . strlen($response) . ")\n";
        }
    } else {
        echo "❌ No response\n";
    }
    echo "---\n";
}

echo "\n✅ Upload completato!\n";
?> 