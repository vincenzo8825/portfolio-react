<?php
header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🧪 Test Creazione Progetto con Gallery</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; line-height: 1.6; }
        .container { max-width: 800px; margin: 0 auto; }
        .test-section { background: #f5f5f5; padding: 20px; margin: 20px 0; border-radius: 8px; }
        .success { color: #27ae60; font-weight: bold; }
        .error { color: #e74c3c; font-weight: bold; }
        .warning { color: #f39c12; font-weight: bold; }
        pre { background: #f8f9fa; padding: 15px; border-radius: 5px; overflow-x: auto; font-size: 12px; }
        .form-group { margin: 15px 0; }
        .form-group label { display: block; font-weight: bold; margin-bottom: 5px; }
        .form-group input, .form-group textarea { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        .btn { background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
        .btn:hover { background: #0056b3; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🧪 Test Creazione Progetto con Gallery</h1>
        
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            echo '<div class="test-section">';
            echo '<h2>📤 Risultato Creazione Progetto</h2>';
            
            // Dati del progetto
            $projectData = [
                'title' => $_POST['title'] ?? 'Test Gallery Project',
                'description' => $_POST['description'] ?? 'Progetto di test per la gallery',
                'content' => $_POST['content'] ?? 'Contenuto dettagliato del progetto di test',
                'technologies' => ['React', 'Laravel', 'JavaScript', 'CSS'],
                'features' => ['Gallery dinamica', 'Navigazione immagini', 'Responsive design'],
                'gallery' => [
                    'https://vincenzorocca.com/api/uploads/685fd48a6406f_1751110794.jpg',
                    'https://vincenzorocca.com/api/uploads/685fd997a2091_1751112087.png',
                    'https://vincenzorocca.com/api/uploads/685fe1c76ba95_1751114183.png'
                ],
                'status' => 'completed',
                'demo_url' => 'https://vincenzorocca.com',
                'github_url' => 'https://github.com/vincenzorocca'
            ];
            
            // Invia richiesta all'API
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://vincenzorocca.com/api/v1/admin/projects');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($projectData));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Authorization: Bearer auth_token_1_1735364700' // Token admin
            ]);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);
            
            if ($error) {
                echo '<p class="error">❌ Errore cURL: ' . $error . '</p>';
            } elseif ($httpCode !== 200 && $httpCode !== 201) {
                echo '<p class="error">❌ HTTP Error: ' . $httpCode . '</p>';
                echo '<pre>' . htmlspecialchars($response) . '</pre>';
            } else {
                $result = json_decode($response, true);
                
                if (json_last_error() !== JSON_ERROR_NONE) {
                    echo '<p class="error">❌ Errore parsing JSON: ' . json_last_error_msg() . '</p>';
                    echo '<pre>' . htmlspecialchars($response) . '</pre>';
                } else {
                    echo '<p class="success">✅ Progetto creato con successo!</p>';
                    
                    if (isset($result['data']['id'])) {
                        $projectId = $result['data']['id'];
                        echo "<p><strong>🆔 ID Progetto:</strong> $projectId</p>";
                        echo "<p><strong>🔗 URL Dettaglio:</strong> <a href='https://vincenzorocca.com/projects/$projectId' target='_blank'>https://vincenzorocca.com/projects/$projectId</a></p>";
                        
                        // Test recupero progetto
                        echo '<h3>🔍 Test Recupero Progetto</h3>';
                        
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, "https://vincenzorocca.com/api/v1/projects/$projectId");
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                        
                        $getResponse = curl_exec($ch);
                        $getHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                        curl_close($ch);
                        
                        if ($getHttpCode === 200) {
                            $projectDetails = json_decode($getResponse, true);
                            
                            if (isset($projectDetails['data'])) {
                                $project = $projectDetails['data'];
                                
                                echo '<p class="success">✅ Progetto recuperato correttamente</p>';
                                echo '<h4>📊 Verifica Campi:</h4>';
                                echo '<ul>';
                                echo '<li><strong>Gallery:</strong> ' . (isset($project['gallery']) && is_array($project['gallery']) ? count($project['gallery']) . ' immagini' : 'Non presente') . '</li>';
                                echo '<li><strong>Images (frontend):</strong> ' . (isset($project['images']) && is_array($project['images']) ? count($project['images']) . ' immagini' : 'Non presente') . '</li>';
                                echo '<li><strong>Technologies:</strong> ' . (isset($project['technologies']) && is_array($project['technologies']) ? count($project['technologies']) . ' tecnologie' : 'Non presente') . '</li>';
                                echo '<li><strong>Features:</strong> ' . (isset($project['features']) && is_array($project['features']) ? count($project['features']) . ' features' : 'Non presente') . '</li>';
                                echo '</ul>';
                                
                                if (isset($project['images']) && is_array($project['images']) && !empty($project['images'])) {
                                    echo '<h4>🖼️ Immagini Gallery (campo "images"):</h4>';
                                    echo '<ul>';
                                    foreach ($project['images'] as $index => $imageUrl) {
                                        echo '<li>Immagine ' . ($index + 1) . ': <a href="' . htmlspecialchars($imageUrl) . '" target="_blank">' . htmlspecialchars($imageUrl) . '</a></li>';
                                    }
                                    echo '</ul>';
                                } else {
                                    echo '<p class="warning">⚠️ Campo "images" non presente o vuoto</p>';
                                }
                                
                                echo '<h4>📋 Risposta API Completa:</h4>';
                                echo '<pre>' . htmlspecialchars(json_encode($project, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)) . '</pre>';
                            }
                        } else {
                            echo '<p class="error">❌ Errore recupero progetto: HTTP ' . $getHttpCode . '</p>';
                        }
                    }
                    
                    echo '<h4>📋 Risposta Creazione:</h4>';
                    echo '<pre>' . htmlspecialchars(json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)) . '</pre>';
                }
            }
            
            echo '</div>';
        } else {
        ?>
        
        <div class="test-section">
            <h2>📝 Crea Progetto di Test con Gallery</h2>
            <p>Questo test creerà un progetto con una gallery di 3 immagini per verificare che la funzionalità gallery funzioni correttamente.</p>
            
            <form method="POST">
                <div class="form-group">
                    <label for="title">Titolo Progetto:</label>
                    <input type="text" id="title" name="title" value="Test Gallery Project <?php echo date('H:i:s'); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="description">Descrizione:</label>
                    <input type="text" id="description" name="description" value="Progetto di test per verificare la gallery" required>
                </div>
                
                <div class="form-group">
                    <label for="content">Contenuto:</label>
                    <textarea id="content" name="content" rows="3" required>Questo è un progetto di test creato per verificare che la funzionalità gallery funzioni correttamente. Il progetto includerà 3 immagini di esempio nella gallery.</textarea>
                </div>
                
                <button type="submit" class="btn">🚀 Crea Progetto con Gallery</button>
            </form>
            
            <h3>📋 Dati che verranno inviati:</h3>
            <ul>
                <li><strong>Gallery:</strong> 3 immagini di esempio</li>
                <li><strong>Technologies:</strong> React, Laravel, JavaScript, CSS</li>
                <li><strong>Features:</strong> Gallery dinamica, Navigazione immagini, Responsive design</li>
                <li><strong>Status:</strong> completed</li>
            </ul>
        </div>
        
        <?php } ?>
        
        <div class="test-section">
            <h2>📋 Obiettivo Test</h2>
            <p><strong>🎯 Scopo:</strong> Creare un progetto con gallery e verificare che:</p>
            <ol>
                <li>Il progetto venga creato correttamente nel database</li>
                <li>L'API restituisca il campo "gallery" decodificato</li>
                <li>L'API restituisca il campo "images" mappato per il frontend</li>
                <li>La pagina di dettaglio mostri tutte le immagini della gallery</li>
                <li>La navigazione gallery funzioni (frecce e thumbnails)</li>
            </ol>
        </div>
    </div>
</body>
</html> 