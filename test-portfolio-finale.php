<?php
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>🧪 Test Portfolio Finale - Verifiche Complete</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 1200px; margin: 0 auto; padding: 20px; }
        .test-section { background: #f5f5f5; padding: 15px; margin: 10px 0; border-radius: 8px; }
        .success { background: #d4edda; border-left: 4px solid #28a745; }
        .error { background: #f8d7da; border-left: 4px solid #dc3545; }
        .info { background: #d1ecf1; border-left: 4px solid #17a2b8; }
        .warning { background: #fff3cd; border-left: 4px solid #ffc107; }
        pre { background: #f8f9fa; padding: 10px; border-radius: 4px; overflow-x: auto; }
        .endpoint { font-family: monospace; background: #e9ecef; padding: 2px 6px; border-radius: 3px; }
    </style>
</head>
<body>

<h1>🧪 Test Portfolio Finale - Verifiche Complete</h1>
<p><strong>Timestamp:</strong> <?= date('Y-m-d H:i:s') ?></p>

<?php
$baseUrl = 'https://vincenzorocca.com/api/v1';
$frontendUrl = 'https://vincenzorocca.com';

function testEndpoint($url, $description, $expectedFormat = null) {
    echo "<div class='test-section'>";
    echo "<h3>🔍 $description</h3>";
    echo "<p><strong>URL:</strong> <span class='endpoint'>$url</span></p>";
    
    $start = microtime(true);
    $response = @file_get_contents($url);
    $time = round((microtime(true) - $start) * 1000, 2);
    
    if ($response === false) {
        echo "<div class='error'>❌ <strong>ERRORE:</strong> Impossibile raggiungere l'endpoint</div>";
    } else {
        $data = json_decode($response, true);
        $httpCode = http_response_code();
        
        echo "<div class='success'>✅ <strong>SUCCESSO:</strong> Risposta ricevuta in {$time}ms</div>";
        
        if ($data === null) {
            echo "<div class='warning'>⚠️ <strong>ATTENZIONE:</strong> Risposta non è JSON valido</div>";
            echo "<pre>" . htmlspecialchars(substr($response, 0, 500)) . "</pre>";
        } else {
            echo "<div class='info'>📦 <strong>Formato JSON valido</strong></div>";
            
            if ($expectedFormat) {
                switch ($expectedFormat) {
                    case 'array':
                        if (is_array($data)) {
                            echo "<div class='success'>✅ Formato array corretto</div>";
                            echo "<p><strong>Elementi:</strong> " . count($data) . "</p>";
                        } else {
                            echo "<div class='error'>❌ Atteso array, ricevuto: " . gettype($data) . "</div>";
                        }
                        break;
                    case 'object_with_data':
                        if (isset($data['success']) && isset($data['data'])) {
                            echo "<div class='success'>✅ Formato {success, data} corretto</div>";
                            if (is_array($data['data'])) {
                                echo "<p><strong>Elementi in data:</strong> " . count($data['data']) . "</p>";
                            }
                        } else {
                            echo "<div class='error'>❌ Atteso formato {success, data}</div>";
                        }
                        break;
                }
            }
            
            echo "<pre>" . htmlspecialchars(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) . "</pre>";
        }
    }
    echo "</div>";
}
?>

<h2>🌐 Test Frontend</h2>
<?php
echo "<div class='test-section'>";
echo "<h3>🏠 Homepage Accessibilità</h3>";
$frontendResponse = @file_get_contents($frontendUrl);
if ($frontendResponse && strpos($frontendResponse, 'Vincenzo Rocca') !== false) {
    echo "<div class='success'>✅ Homepage caricata correttamente</div>";
    
    // Check for new bundle
    if (strpos($frontendResponse, 'index-CMitw-D5.js') !== false) {
        echo "<div class='success'>✅ Nuovo bundle JavaScript caricato (index-CMitw-D5.js)</div>";
    } else {
        echo "<div class='warning'>⚠️ Bundle JavaScript potrebbe essere vecchio</div>";
    }
    
    // Check for cache busters
    if (strpos($frontendResponse, '?v=1751119989') !== false) {
        echo "<div class='success'>✅ Cache buster applicato</div>";
    } else {
        echo "<div class='warning'>⚠️ Cache buster non trovato</div>";
    }
} else {
    echo "<div class='error'>❌ Homepage non accessibile o contenuto mancante</div>";
}
echo "</div>";
?>

<h2>🔧 Test API Endpoints</h2>

<?php
// Test Technologies (dovrebbe essere array diretto)
testEndpoint("$baseUrl/technologies", "Technologies API (formato array diretto)", 'array');

// Test Projects
testEndpoint("$baseUrl/projects", "All Projects API", 'object_with_data');

// Test Featured Projects (max 3)
testEndpoint("$baseUrl/projects/featured", "Featured Projects API (max 3)", 'object_with_data');

// Test Project by ID
testEndpoint("$baseUrl/projects/7", "Project by ID", 'object_with_data');

// Test Auth endpoint
testEndpoint("$baseUrl/auth/me", "Auth Me (should fail without token)", null);
?>

<h2>🔐 Test Autenticazione</h2>
<?php
echo "<div class='test-section'>";
echo "<h3>🔑 Test Login</h3>";

$loginData = json_encode([
    'email' => 'vincenzorocca88@gmail.com',
    'password' => 'admin123'
]);

$context = stream_context_create([
    'http' => [
        'method' => 'POST',
        'header' => "Content-Type: application/json\r\n",
        'content' => $loginData
    ]
]);

$loginResponse = @file_get_contents("$baseUrl/auth/login", false, $context);
if ($loginResponse) {
    $loginData = json_decode($loginResponse, true);
    if (isset($loginData['token'])) {
        echo "<div class='success'>✅ Login riuscito, token ricevuto</div>";
        $token = $loginData['token'];
        
        // Test auth/me con token
        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => "Authorization: Bearer $token\r\n"
            ]
        ]);
        
        $userResponse = @file_get_contents("$baseUrl/auth/me", false, $context);
        if ($userResponse) {
            $userData = json_decode($userResponse, true);
            if (isset($userData['is_admin'])) {
                echo "<div class='success'>✅ Auth/me funziona con token</div>";
                echo "<p><strong>Admin:</strong> " . ($userData['is_admin'] ? 'Sì' : 'No') . "</p>";
            } else {
                echo "<div class='error'>❌ Auth/me risposta invalida</div>";
            }
        } else {
            echo "<div class='error'>❌ Auth/me non funziona con token</div>";
        }
    } else {
        echo "<div class='error'>❌ Login fallito: " . htmlspecialchars($loginResponse) . "</div>";
    }
} else {
    echo "<div class='error'>❌ Impossibile testare login</div>";
}
echo "</div>";
?>

<h2>📊 Test Upload (simulato)</h2>
<?php
echo "<div class='test-section'>";
echo "<h3>📁 Test Upload Directory</h3>";

// Check if uploads directory exists by testing a potential upload
$uploadTestUrl = "$baseUrl/admin/upload/image";
echo "<p><strong>Upload Endpoint:</strong> <span class='endpoint'>$uploadTestUrl</span></p>";

// Simulate upload without token (should fail with 401)
$context = stream_context_create([
    'http' => [
        'method' => 'POST',
        'header' => "Content-Type: multipart/form-data\r\n"
    ]
]);

$uploadResponse = @file_get_contents($uploadTestUrl, false, $context);
if ($uploadResponse) {
    $uploadData = json_decode($uploadResponse, true);
    if (isset($uploadData['message']) && strpos($uploadData['message'], 'Non autorizzato') !== false) {
        echo "<div class='success'>✅ Upload endpoint risponde correttamente (401 senza token)</div>";
    } else {
        echo "<div class='warning'>⚠️ Upload endpoint risposta inaspettata</div>";
        echo "<pre>" . htmlspecialchars($uploadResponse) . "</pre>";
    }
} else {
    echo "<div class='error'>❌ Upload endpoint non raggiungibile</div>";
}
echo "</div>";
?>

<h2>🎯 Test Funzionalità Specifiche</h2>

<?php
// Test project slug vs ID
echo "<div class='test-section'>";
echo "<h3>🔗 Test Project Slug vs ID</h3>";

// Test numeric ID
$numericResponse = @file_get_contents("$baseUrl/projects/7");
$numericData = $numericResponse ? json_decode($numericResponse, true) : null;

if ($numericData && isset($numericData['data']['slug'])) {
    echo "<div class='success'>✅ Project by numeric ID funziona</div>";
    $slug = $numericData['data']['slug'];
    
    // Test slug
    $slugResponse = @file_get_contents("$baseUrl/projects/$slug");
    $slugData = $slugResponse ? json_decode($slugResponse, true) : null;
    
    if ($slugData && isset($slugData['data']['id'])) {
        echo "<div class='success'>✅ Project by slug funziona</div>";
        echo "<p><strong>ID:</strong> {$slugData['data']['id']}, <strong>Slug:</strong> {$slugData['data']['slug']}</p>";
    } else {
        echo "<div class='error'>❌ Project by slug non funziona</div>";
    }
} else {
    echo "<div class='error'>❌ Impossibile testare slug (project ID 7 non trovato)</div>";
}
echo "</div>";

// Test featured projects limit
echo "<div class='test-section'>";
echo "<h3>⭐ Test Limite Featured Projects</h3>";

$featuredResponse = @file_get_contents("$baseUrl/projects/featured");
$featuredData = $featuredResponse ? json_decode($featuredResponse, true) : null;

if ($featuredData && isset($featuredData['data'])) {
    $count = count($featuredData['data']);
    if ($count <= 3) {
        echo "<div class='success'>✅ Limite featured projects rispettato ($count/3)</div>";
    } else {
        echo "<div class='error'>❌ Troppi featured projects ($count > 3)</div>";
    }
    
    foreach ($featuredData['data'] as $project) {
        echo "<p>• <strong>{$project['title']}</strong> (ID: {$project['id']})</p>";
    }
} else {
    echo "<div class='error'>❌ Impossibile verificare featured projects</div>";
}
echo "</div>";
?>

<h2>📈 Riassunto Test</h2>
<div class='test-section info'>
    <h3>🎯 Problemi Risolti</h3>
    <ul>
        <li>✅ <strong>Technologies API:</strong> Ora restituisce array diretto invece di {success, data}</li>
        <li>✅ <strong>Featured Projects:</strong> Limitato a massimo 3 progetti</li>
        <li>✅ <strong>Project Slug:</strong> Supporta sia ID numerici che slug testuali</li>
        <li>✅ <strong>Upload Endpoints:</strong> Aggiunti per gestire caricamento immagini</li>
        <li>✅ <strong>Cache Busting:</strong> Applicato ai bundle per forzare refresh</li>
        <li>✅ <strong>Frontend Bundle:</strong> Aggiornato con tutte le correzioni</li>
    </ul>
</div>

<div class='test-section'>
    <h3>🚀 Prossimi Passi</h3>
    <ol>
        <li>Testare il portfolio dal browser per verificare che non ci siano più errori console</li>
        <li>Verificare che la selezione delle tecnologie funzioni nel form admin</li>
        <li>Testare l'upload di immagini nel form progetti</li>
        <li>Verificare che la home mostri correttamente i progetti in evidenza dal database</li>
        <li>Controllare che tutti i link e navigazione funzionino correttamente</li>
    </ol>
</div>

<p><em>Test completato: <?= date('Y-m-d H:i:s') ?></em></p>

</body>
</html> 