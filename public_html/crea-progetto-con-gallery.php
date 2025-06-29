<?php
// Script per creare un progetto di test con gallery
header('Content-Type: text/html; charset=UTF-8');

echo "<!DOCTYPE html>
<html>
<head>
    <title>Crea Progetto con Gallery</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 0 auto; padding: 20px; }
        .success { color: #28a745; background: #d4edda; padding: 15px; border-radius: 8px; margin: 15px 0; }
        .error { color: #dc3545; background: #f8d7da; padding: 15px; border-radius: 8px; margin: 15px 0; }
        .info { color: #0c5460; background: #d1ecf1; padding: 15px; border-radius: 8px; margin: 15px 0; }
    </style>
</head>
<body>";

echo "<h1>🎯 Crea Progetto Test con Gallery</h1>";

try {
    // Connessione database
    $host = 'localhost';
    $dbname = 'u336414084_portfolioVince';
    $username = 'u336414084_portfolioVince';
    $password = 'Ciaociao52.?';
    
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Usa alcune immagini dalla directory uploads esistente
    $uploadDir = __DIR__ . '/api/uploads/';
    $existingImages = [];
    
    if (is_dir($uploadDir)) {
        $files = scandir($uploadDir);
        foreach ($files as $file) {
            if (preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $file)) {
                $existingImages[] = 'https://vincenzorocca.com/api/uploads/' . $file;
                if (count($existingImages) >= 5) break; // Prendi solo 5 immagini
            }
        }
    }
    
    if (empty($existingImages)) {
        // Se non ci sono immagini, crea degli URL di esempio
        $existingImages = [
            'https://vincenzorocca.com/api/uploads/test1.jpg',
            'https://vincenzorocca.com/api/uploads/test2.jpg',
            'https://vincenzorocca.com/api/uploads/test3.jpg'
        ];
    }
    
    // Crea il progetto con gallery
    $projectData = [
        'title' => 'Portfolio React con Gallery Test',
        'slug' => 'portfolio-react-gallery-test',
        'short_description' => 'Progetto di test per verificare il funzionamento della gallery',
        'content' => 'Questo è un progetto di test creato per verificare che il sistema di gallery funzioni correttamente. Include multiple immagini caricate tramite il sistema di upload.',
        'image_url' => $existingImages[0], // Prima immagine come principale
        'demo_url' => 'https://vincenzorocca.com',
        'github_url' => 'https://github.com/vincenzorocca',
        'technologies' => json_encode(['React', 'Laravel', 'PHP', 'MySQL', 'JavaScript']),
        'status' => 'completed',
        'is_featured' => 0,
        'features' => json_encode([
            'Upload multiplo immagini',
            'Gallery responsive',
            'Navigation tra immagini',
            'Thumbnail grid',
            'Admin panel'
        ]),
        'challenges' => json_encode([
            'Gestione upload multipli',
            'Ottimizzazione immagini',
            'Responsive design',
            'Database JSON fields'
        ]),
        'results' => json_encode([
            'Sistema gallery funzionante',
            'Upload senza errori',
            'Visualizzazione completa',
            'Performance ottimizzate'
        ]),
        'gallery' => json_encode($existingImages), // QUESTO È IL CAMPO IMPORTANTE
        'additional_links' => json_encode([
            ['name' => 'Demo Live', 'url' => 'https://vincenzorocca.com'],
            ['name' => 'Admin Panel', 'url' => 'https://vincenzorocca.com/admin']
        ])
    ];
    
    // Inserisci il progetto
    $sql = "INSERT INTO projects (
        title, slug, short_description, content, image_url, demo_url, github_url,
        technologies, status, is_featured, features, challenges, results, gallery, additional_links,
        created_at, updated_at
    ) VALUES (
        :title, :slug, :short_description, :content, :image_url, :demo_url, :github_url,
        :technologies, :status, :is_featured, :features, :challenges, :results, :gallery, :additional_links,
        NOW(), NOW()
    )";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($projectData);
    
    $projectId = $pdo->lastInsertId();
    
    echo "<div class='success'>";
    echo "<h2>✅ Progetto Creato con Successo!</h2>";
    echo "<p><strong>ID Progetto:</strong> $projectId</p>";
    echo "<p><strong>Titolo:</strong> " . htmlspecialchars($projectData['title']) . "</p>";
    echo "<p><strong>Immagini in Gallery:</strong> " . count($existingImages) . "</p>";
    echo "</div>";
    
    echo "<div class='info'>";
    echo "<h3>📸 Immagini nella Gallery:</h3>";
    echo "<ul>";
    foreach ($existingImages as $index => $image) {
        echo "<li>$index: " . basename($image) . "</li>";
    }
    echo "</ul>";
    echo "</div>";
    
    echo "<div class='info'>";
    echo "<h3>🔗 Link per Testare:</h3>";
    echo "<ul>";
    echo "<li><a href='https://vincenzorocca.com/projects/$projectId' target='_blank'>Visualizza Progetto nel Frontend</a></li>";
    echo "<li><a href='https://vincenzorocca.com/admin/projects/$projectId/edit' target='_blank'>Modifica in Admin Panel</a></li>";
    echo "<li><a href='https://vincenzorocca.com/api/v1/projects/$projectId' target='_blank'>Dati API JSON</a></li>";
    echo "<li><a href='/debug-gallery-database.php' target='_blank'>Debug Gallery Database</a></li>";
    echo "</ul>";
    echo "</div>";
    
    // Verifica che sia stato salvato correttamente
    $stmt = $pdo->prepare("SELECT id, title, gallery FROM projects WHERE id = ?");
    $stmt->execute([$projectId]);
    $savedProject = $stmt->fetch();
    
    if ($savedProject) {
        $savedGallery = json_decode($savedProject['gallery'], true);
        
        echo "<div class='info'>";
        echo "<h3>✅ Verifica Salvataggio:</h3>";
        echo "<p><strong>Gallery salvata nel DB:</strong> " . (is_array($savedGallery) ? count($savedGallery) . " immagini" : "ERRORE") . "</p>";
        echo "<p><strong>Contenuto gallery:</strong></p>";
        echo "<pre>" . htmlspecialchars($savedProject['gallery']) . "</pre>";
        echo "</div>";
    }
    
} catch (Exception $e) {
    echo "<div class='error'>";
    echo "<h2>❌ Errore</h2>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "</div>";
}

echo "</body></html>";
?> 