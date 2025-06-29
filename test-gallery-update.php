<?php
// Test per aggiornare un progetto con gallery di test
$host = 'localhost';
$dbname = 'u336414084_vincenzorocca8';
$username = 'u336414084_portfolioVince';
$password = 'Ciaociao52.?';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prendiamo il progetto con ID 14 (che abbiamo testato prima)
    $projectId = 14;
    
    // Creiamo una gallery di test
    $testGallery = [
        "https://vincenzorocca.com/api/uploads/test1.jpg",
        "https://vincenzorocca.com/api/uploads/test2.jpg",
        "https://vincenzorocca.com/api/uploads/test3.jpg"
    ];
    
    $galleryJson = json_encode($testGallery);
    
    // Aggiorniamo il progetto
    $stmt = $pdo->prepare("UPDATE projects SET gallery = ? WHERE id = ?");
    $result = $stmt->execute([$galleryJson, $projectId]);
    
    if ($result) {
        echo "✅ Progetto $projectId aggiornato con gallery di test\n";
        echo "Gallery JSON: $galleryJson\n";
        
        // Verifichiamo l'aggiornamento
        $stmt = $pdo->prepare("SELECT id, title, gallery FROM projects WHERE id = ?");
        $stmt->execute([$projectId]);
        $project = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($project) {
            echo "\n📋 Dati progetto aggiornato:\n";
            echo "ID: " . $project['id'] . "\n";
            echo "Title: " . $project['title'] . "\n";
            echo "Gallery: " . $project['gallery'] . "\n";
            
            $decodedGallery = json_decode($project['gallery'], true);
            echo "Gallery decoded (" . count($decodedGallery) . " immagini):\n";
            foreach ($decodedGallery as $i => $img) {
                echo "  " . ($i+1) . ". $img\n";
            }
        }
    } else {
        echo "❌ Errore nell'aggiornamento del progetto\n";
    }

} catch (PDOException $e) {
    echo "❌ Errore database: " . $e->getMessage() . "\n";
}
?> 