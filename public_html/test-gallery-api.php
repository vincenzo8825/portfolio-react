<?php
// Test per verificare l'API gallery
header('Content-Type: application/json');

$host = 'localhost';
$dbname = 'u336414084_vincenzorocca8';
$username = 'u336414084_portfolioVince';
$password = 'Ciaociao52.?';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Step 1: Aggiorniamo il progetto ID 14 con gallery di test
    $projectId = 14;
    $testGallery = [
        "https://vincenzorocca.com/api/uploads/gallery1.jpg",
        "https://vincenzorocca.com/api/uploads/gallery2.jpg",
        "https://vincenzorocca.com/api/uploads/gallery3.jpg"
    ];
    
    $galleryJson = json_encode($testGallery);
    $stmt = $pdo->prepare("UPDATE projects SET gallery = ? WHERE id = ?");
    $updateResult = $stmt->execute([$galleryJson, $projectId]);
    
    echo "🔄 Step 1: Aggiornamento database\n";
    echo "Progetto ID: $projectId\n";
    echo "Gallery JSON: $galleryJson\n";
    echo "Update result: " . ($updateResult ? "SUCCESS" : "FAILED") . "\n\n";
    
    // Step 2: Verifichiamo direttamente dal database
    $stmt = $pdo->prepare("SELECT id, title, gallery FROM projects WHERE id = ?");
    $stmt->execute([$projectId]);
    $dbProject = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "📋 Step 2: Verifica database\n";
    echo "DB Gallery: " . $dbProject['gallery'] . "\n";
    $decodedGallery = json_decode($dbProject['gallery'], true);
    echo "Decoded count: " . count($decodedGallery) . "\n\n";
    
    // Step 3: Test della funzione adaptProjectForFrontend
    function adaptProjectForFrontend($project) {
        $adapted = $project;
        $adapted['featured'] = (bool)($project['is_featured'] ?? false);
        $adapted['long_description'] = $project['content'] ?? '';
        $adapted['description'] = $project['short_description'] ?? $project['description'] ?? '';
        
        $adapted['technologies'] = json_decode($project['technologies'] ?? '[]', true);
        $adapted['features'] = json_decode($project['features'] ?? '[]', true);
        $adapted['challenges'] = json_decode($project['challenges'] ?? '[]', true);
        $adapted['results'] = json_decode($project['results'] ?? '[]', true);
        $adapted['gallery'] = json_decode($project['gallery'] ?? '[]', true);
        $adapted['additional_links'] = json_decode($project['additional_links'] ?? '[]', true);
        
        // Map gallery to images field (frontend expects 'images' for gallery display)
        $adapted['images'] = $adapted['gallery'];
        
        $adapted['linkedin_url'] = '';
        $adapted['video_url'] = '';
        $adapted['client'] = '';
        $adapted['duration'] = '';
        $adapted['category'] = 'Web Development';
        $adapted['project_date'] = $project['started_at'] ?? $project['created_at'] ?? date('Y-m-d');
        
        if (empty($adapted['technologies'])) {
            $adapted['technologies'] = ['React', 'Laravel', 'MySQL'];
        }
        if (empty($adapted['features'])) {
            $adapted['features'] = ['Feature 1', 'Feature 2', 'Feature 3'];
        }
        
        return $adapted;
    }
    
    $adaptedProject = adaptProjectForFrontend($dbProject);
    
    echo "🔄 Step 3: Test adaptProjectForFrontend\n";
    echo "Gallery field: " . json_encode($adaptedProject['gallery']) . "\n";
    echo "Images field: " . json_encode($adaptedProject['images']) . "\n";
    echo "Images count: " . count($adaptedProject['images']) . "\n\n";
    
    // Step 4: Test dell'API endpoint
    echo "🌐 Step 4: Test API endpoint\n";
    echo "URL: https://vincenzorocca.com/api/v1/projects/$projectId\n";
    
    $apiUrl = "https://vincenzorocca.com/api/v1/projects/$projectId";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    $apiResponse = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    echo "HTTP Code: $httpCode\n";
    if ($apiResponse) {
        $apiData = json_decode($apiResponse, true);
        if (isset($apiData['data'])) {
            $project = $apiData['data'];
            echo "API Gallery: " . json_encode($project['gallery'] ?? 'NOT_FOUND') . "\n";
            echo "API Images: " . json_encode($project['images'] ?? 'NOT_FOUND') . "\n";
            
            if (isset($project['images'])) {
                echo "API Images count: " . count($project['images']) . "\n";
            }
        } else {
            echo "API Response: $apiResponse\n";
        }
    } else {
        echo "API Error: No response\n";
    }
    
    echo "\n✅ Test completato!\n";

} catch (PDOException $e) {
    echo "❌ Errore database: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "❌ Errore generale: " . $e->getMessage() . "\n";
}
?> 