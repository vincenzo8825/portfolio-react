<?php
// Debug per verificare gallery nel database e mapping
header('Content-Type: text/html; charset=UTF-8');

echo "<!DOCTYPE html>
<html>
<head>
    <title>Debug Gallery Database</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 1200px; margin: 0 auto; padding: 20px; }
        .project { background: #f8f9fa; padding: 15px; margin: 15px 0; border-radius: 8px; }
        .gallery-images { display: grid; grid-template-columns: repeat(auto-fill, minmax(100px, 1fr)); gap: 10px; margin: 10px 0; }
        .gallery-images img { width: 100%; height: 80px; object-fit: cover; border-radius: 4px; }
        .json-data { background: #f1f3f4; padding: 10px; border-radius: 4px; font-family: monospace; font-size: 12px; }
        .error { color: #dc3545; }
        .success { color: #28a745; }
        .warning { color: #ffc107; }
    </style>
</head>
<body>";

echo "<h1>🔍 Debug Gallery Database</h1>";

try {
    // Connessione database
    $host = 'localhost';
    $dbname = 'u336414084_portfolioVince';
    $username = 'u336414084_portfolioVince';
    $password = 'Ciaociao52.?';
    
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<p class='success'>✅ Database connesso</p>";
    
    // Funzione adaptProjectForFrontend (copia dall'API)
    function adaptProjectForFrontend($project) {
        $adapted = $project;
        
        // Map database fields to frontend expected fields
        $adapted['featured'] = (bool)($project['is_featured'] ?? false);
        $adapted['long_description'] = $project['content'] ?? '';
        $adapted['description'] = $project['short_description'] ?? $project['description'] ?? '';

        // Decode JSON fields from database
        $adapted['technologies'] = json_decode($project['technologies'] ?? '[]', true);
        $adapted['features'] = json_decode($project['features'] ?? '[]', true);
        $adapted['challenges'] = json_decode($project['challenges'] ?? '[]', true);
        $adapted['results'] = json_decode($project['results'] ?? '[]', true);
        $adapted['gallery'] = json_decode($project['gallery'] ?? '[]', true);
        $adapted['additional_links'] = json_decode($project['additional_links'] ?? '[]', true);

        // Map gallery to images field (frontend expects 'images' for gallery display)
        $adapted['images'] = $adapted['gallery'];

        return $adapted;
    }
    
    // Recupera tutti i progetti
    $stmt = $pdo->query("SELECT * FROM projects ORDER BY id DESC");
    $projects = $stmt->fetchAll();
    
    echo "<h2>📊 Progetti nel Database: " . count($projects) . "</h2>";
    
    foreach ($projects as $project) {
        echo "<div class='project'>";
        echo "<h3>🔹 " . htmlspecialchars($project['title']) . " (ID: " . $project['id'] . ")</h3>";
        
        // Mostra dati raw dal database
        echo "<h4>📋 Dati Raw Database:</h4>";
        echo "<strong>Gallery field:</strong> ";
        if (empty($project['gallery'])) {
            echo "<span class='warning'>VUOTO o NULL</span>";
        } else {
            echo "<span class='success'>PRESENTE</span>";
            echo "<div class='json-data'>" . htmlspecialchars($project['gallery']) . "</div>";
        }
        
        // Test adaptProjectForFrontend
        echo "<h4>🔄 Dopo adaptProjectForFrontend:</h4>";
        $adapted = adaptProjectForFrontend($project);
        
        echo "<strong>Gallery array:</strong> ";
        if (empty($adapted['gallery'])) {
            echo "<span class='warning'>VUOTO</span>";
        } else {
            echo "<span class='success'>" . count($adapted['gallery']) . " elementi</span>";
            echo "<div class='json-data'>" . print_r($adapted['gallery'], true) . "</div>";
        }
        
        echo "<strong>Images field (per frontend):</strong> ";
        if (empty($adapted['images'])) {
            echo "<span class='warning'>VUOTO</span>";
        } else {
            echo "<span class='success'>" . count($adapted['images']) . " elementi</span>";
            
            // Mostra le immagini se presenti
            if (count($adapted['images']) > 0) {
                echo "<div class='gallery-images'>";
                foreach ($adapted['images'] as $index => $image) {
                    echo "<div>";
                    echo "<img src='$image' alt='Image $index' onerror='this.style.border=\"2px solid red\"'>";
                    echo "<small>$index: " . basename($image) . "</small>";
                    echo "</div>";
                }
                echo "</div>";
            }
        }
        
        // Test API endpoint
        echo "<h4>🌐 Test API Response:</h4>";
        $apiUrl = "https://vincenzorocca.com/api/v1/projects/" . $project['id'];
        $apiResponse = @file_get_contents($apiUrl);
        
        if ($apiResponse) {
            $apiData = json_decode($apiResponse, true);
            if ($apiData && isset($apiData['success']) && $apiData['success']) {
                $projectData = $apiData['data'];
                echo "<strong>API images:</strong> ";
                if (empty($projectData['images'])) {
                    echo "<span class='error'>VUOTO nell'API!</span>";
                } else {
                    echo "<span class='success'>" . count($projectData['images']) . " elementi</span>";
                }
            } else {
                echo "<span class='error'>API response non valida</span>";
            }
        } else {
            echo "<span class='error'>API non raggiungibile</span>";
        }
        
        echo "</div>";
    }
    
    // Test specifico per progetti con gallery
    echo "<h2>🎯 Progetti con Gallery Non Vuota</h2>";
    $stmt = $pdo->query("SELECT id, title, gallery FROM projects WHERE gallery IS NOT NULL AND gallery != '' AND gallery != '[]' AND gallery != 'null'");
    $projectsWithGallery = $stmt->fetchAll();
    
    if (count($projectsWithGallery) > 0) {
        echo "<p class='success'>Trovati " . count($projectsWithGallery) . " progetti con gallery</p>";
        
        foreach ($projectsWithGallery as $project) {
            echo "<div class='project'>";
            echo "<h4>" . htmlspecialchars($project['title']) . "</h4>";
            
            $gallery = json_decode($project['gallery'], true);
            if (is_array($gallery) && count($gallery) > 0) {
                echo "<p class='success'>" . count($gallery) . " immagini nella gallery</p>";
                echo "<div class='gallery-images'>";
                foreach ($gallery as $image) {
                    echo "<img src='$image' alt='Gallery image'>";
                }
                echo "</div>";
            } else {
                echo "<p class='warning'>Gallery non decodificabile correttamente</p>";
            }
            echo "</div>";
        }
    } else {
        echo "<p class='error'>❌ NESSUN PROGETTO CON GALLERY TROVATO!</p>";
        echo "<p>Questo spiega perché non vedi le immagini nel ProjectDetail.</p>";
    }
    
} catch (Exception $e) {
    echo "<p class='error'>❌ Errore: " . $e->getMessage() . "</p>";
}

echo "</body></html>";
?> 