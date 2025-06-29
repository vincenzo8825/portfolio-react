<?php
header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🧪 Test Gallery Dettaglio Progetto</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; line-height: 1.6; }
        .container { max-width: 1200px; margin: 0 auto; }
        .test-section { background: #f5f5f5; padding: 20px; margin: 20px 0; border-radius: 8px; }
        .success { color: #27ae60; font-weight: bold; }
        .error { color: #e74c3c; font-weight: bold; }
        .warning { color: #f39c12; font-weight: bold; }
        .gallery-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-top: 15px; }
        .gallery-item { border: 1px solid #ddd; border-radius: 8px; overflow: hidden; }
        .gallery-item img { width: 100%; height: 150px; object-fit: cover; }
        .gallery-item .caption { padding: 10px; background: #fff; }
        pre { background: #f8f9fa; padding: 15px; border-radius: 5px; overflow-x: auto; font-size: 12px; }
        .project-card { background: white; border: 1px solid #ddd; border-radius: 8px; padding: 20px; margin: 15px 0; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🧪 Test Gallery Dettaglio Progetto</h1>
        <p><strong>Obiettivo:</strong> Verificare che l'API restituisca correttamente i dati della gallery per la pagina di dettaglio del progetto.</p>

        <?php
        // Test connessione database e progetti con gallery
        echo '<div class="test-section">';
        echo '<h2>📊 Test Database e Gallery</h2>';
        
        try {
            $pdo = new PDO("mysql:host=localhost;dbname=u336414084_portfolioVince;charset=utf8mb4", 
                          'u336414084_vincenzorocca8', 'Ciaociao52.?');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            echo '<p class="success">✅ Connessione database riuscita</p>';
            
            // Trova progetti con gallery
            $stmt = $pdo->query("SELECT id, title, slug, gallery FROM projects WHERE gallery IS NOT NULL AND gallery != '[]' AND gallery != '' ORDER BY created_at DESC LIMIT 3");
            $projects = $stmt->fetchAll();
            
            if (empty($projects)) {
                echo '<p class="warning">⚠️ Nessun progetto con gallery trovato</p>';
            } else {
                echo '<p class="success">✅ Trovati ' . count($projects) . ' progetti con gallery</p>';
                
                foreach ($projects as $project) {
                    $gallery = json_decode($project['gallery'], true);
                    echo '<div class="project-card">';
                    echo "<h3>#{$project['id']} - {$project['title']}</h3>";
                    echo "<p><strong>Gallery:</strong> " . (is_array($gallery) ? count($gallery) . ' immagini' : 'Errore decodifica') . "</p>";
                    
                    if (is_array($gallery) && !empty($gallery)) {
                        echo '<div class="gallery-grid">';
                        foreach (array_slice($gallery, 0, 3) as $index => $imageUrl) {
                            echo '<div class="gallery-item">';
                            echo '<img src="' . htmlspecialchars($imageUrl) . '" alt="Gallery ' . ($index + 1) . '">';
                            echo '<div class="caption">Immagine ' . ($index + 1) . '</div>';
                            echo '</div>';
                        }
                        echo '</div>';
                    }
                    echo '</div>';
                }
            }
            
        } catch (Exception $e) {
            echo '<p class="error">❌ Errore: ' . $e->getMessage() . '</p>';
        }
        echo '</div>';
        ?>

        <div class="test-section">
            <h2>📝 Risultati e Prossimi Passi</h2>
            <p><strong>✅ Completato:</strong></p>
            <ul>
                <li>API aggiornata per decodificare campo gallery dal database</li>
                <li>Campo gallery mappato a images per compatibilità frontend</li>
                <li>Frontend ricompilato con nuova logica</li>
                <li>Cache buster aggiornato</li>
            </ul>
            
            <p><strong>📋 Deploy via FTP:</strong></p>
            <ol>
                <li>Caricare api/index.php aggiornato</li>
                <li>Caricare index.html con cache buster</li>
                <li>Caricare assets/index-DRfpeSjE.js</li>
                <li>Testare la navigazione gallery sui progetti</li>
            </ol>
        </div>
    </div>
</body>
</html> 