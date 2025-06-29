<?php
echo "<h1>🎯 Test Finale Sistema Gallery</h1>";

$host = 'localhost';
$dbname = 'u336414084_vincenzorocca8';
$username = 'u336414084_portfolioVince';
$password = 'Ciaociao52.?';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "<h2>📋 Step 1: Aggiornamento Database</h2>";
    
    // Aggiorniamo il progetto ID 14 con gallery di test
    $galleryJson = json_encode([
        "https://vincenzorocca.com/api/uploads/gallery1.jpg",
        "https://vincenzorocca.com/api/uploads/gallery2.jpg", 
        "https://vincenzorocca.com/api/uploads/gallery3.jpg"
    ]);
    
    $stmt = $pdo->prepare("UPDATE projects SET gallery = ? WHERE id = 14");
    $updateResult = $stmt->execute([$galleryJson]);
    
    echo "✅ Progetto 14 aggiornato: " . ($updateResult ? "SUCCESS" : "FAILED") . "<br>";
    echo "📦 Gallery JSON: <code>$galleryJson</code><br>";
    
    // Verifichiamo il database
    $stmt = $pdo->prepare("SELECT id, title, gallery FROM projects WHERE id = 14");
    $stmt->execute();
    $dbProject = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "<h2>🔍 Step 2: Verifica Database</h2>";
    echo "📄 Progetto: " . $dbProject['title'] . "<br>";
    echo "📦 Gallery DB: <code>" . $dbProject['gallery'] . "</code><br>";
    
    $decodedGallery = json_decode($dbProject['gallery'], true);
    echo "📊 Immagini in gallery: " . count($decodedGallery) . "<br>";
    
    foreach ($decodedGallery as $i => $img) {
        echo "&nbsp;&nbsp;" . ($i+1) . ". <code>$img</code><br>";
    }
    
    echo "<h2>🌐 Step 3: Test API</h2>";
    
    // Test dell'API
    $apiUrl = "https://vincenzorocca.com/api/v1/projects/14";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    $apiResponse = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    echo "🔗 URL: <a href='$apiUrl' target='_blank'>$apiUrl</a><br>";
    echo "📡 HTTP Code: $httpCode<br>";
    
    if ($apiResponse) {
        $apiData = json_decode($apiResponse, true);
        
        if (isset($apiData['data'])) {
            $project = $apiData['data'];
            
            echo "<h3>📦 Campi Gallery nell'API:</h3>";
            
            // Verifichiamo il campo gallery
            if (isset($project['gallery'])) {
                echo "✅ Campo 'gallery': " . count($project['gallery']) . " immagini<br>";
                foreach ($project['gallery'] as $i => $img) {
                    echo "&nbsp;&nbsp;" . ($i+1) . ". <code>$img</code><br>";
                }
            } else {
                echo "❌ Campo 'gallery': NON TROVATO<br>";
            }
            
            // Verifichiamo il campo images (quello che il frontend si aspetta)
            if (isset($project['images'])) {
                echo "✅ Campo 'images': " . count($project['images']) . " immagini<br>";
                foreach ($project['images'] as $i => $img) {
                    echo "&nbsp;&nbsp;" . ($i+1) . ". <code>$img</code><br>";
                }
            } else {
                echo "❌ Campo 'images': NON TROVATO<br>";
            }
            
            echo "<h3>🔧 Test Frontend Logic:</h3>";
            
            // Simuliamo la logica del frontend
            $images = $project['images'] ?? $project['gallery'] ?? [$project['image_url']];
            echo "🎯 Immagini per gallery frontend: " . count($images) . "<br>";
            
            if (count($images) > 1) {
                echo "✅ Gallery disponibile - Frontend mostrerà " . count($images) . " immagini<br>";
            } else {
                echo "⚠️ Solo immagine principale - Frontend mostrerà 1 immagine<br>";
            }
            
        } else {
            echo "❌ Formato API non valido<br>";
            echo "<pre>$apiResponse</pre>";
        }
    } else {
        echo "❌ Errore API - Nessuna risposta<br>";
    }
    
    echo "<h2>✅ Test Completato!</h2>";
    echo "<p>Per testare nel frontend, vai su: <a href='https://vincenzorocca.com/projects/14' target='_blank'>https://vincenzorocca.com/projects/14</a></p>";

} catch (Exception $e) {
    echo "<h2>❌ Errore</h2>";
    echo "Errore: " . $e->getMessage();
}
?> 