<?php
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>🧪 Test Gallery Upload Fix</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 0 auto; padding: 20px; }
        .test-section { background: #f5f5f5; padding: 15px; margin: 10px 0; border-radius: 8px; }
        .success { background: #d4edda; border-left: 4px solid #28a745; }
        .error { background: #f8d7da; border-left: 4px solid #dc3545; }
        .info { background: #d1ecf1; border-left: 4px solid #17a2b8; }
        pre { background: #f8f9fa; padding: 10px; border-radius: 4px; overflow-x: auto; }
        .endpoint { font-family: monospace; background: #e9ecef; padding: 2px 6px; border-radius: 3px; }
        .test-form { background: white; padding: 20px; border-radius: 8px; margin: 10px 0; }
        button { background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #0056b3; }
        .upload-area { border: 2px dashed #ddd; padding: 20px; text-align: center; border-radius: 8px; }
        .upload-area:hover { border-color: #007bff; }
    </style>
</head>
<body>
    <h1>🧪 Test Gallery Upload Fix</h1>
    <p><strong>Data test:</strong> <?= date('d/m/Y H:i:s') ?></p>
    
    <!-- Test Response Format -->
    <div class="test-section">
        <h2>✅ Correzione Implementata</h2>
        <div class="success">
            <h3>🔧 Problema Risolto</h3>
            <p><strong>Prima:</strong> API rispondeva con <code>{"success": true, "data": [...]}</code></p>
            <p><strong>Ora:</strong> API risponde con <code>{"success": true, "urls": [...], "data": [...]}</code></p>
            <p><strong>Frontend:</strong> Si aspetta il campo <code>urls</code> per funzionare correttamente</p>
        </div>
    </div>

    <!-- Test API Response -->
    <div class="test-section">
        <h2>📋 Test Risposta API</h2>
        <div class="info">
            <h3>🔍 Formato Risposta Aggiornato</h3>
            <pre><?php
$sampleResponse = [
    'success' => true,
    'message' => 'Gallery caricata con successo',
    'urls' => [
        'https://vincenzorocca.com/api/uploads/file1.jpg',
        'https://vincenzorocca.com/api/uploads/file2.jpg'
    ],
    'data' => [
        ['url' => 'https://vincenzorocca.com/api/uploads/file1.jpg', 'filename' => 'file1.jpg'],
        ['url' => 'https://vincenzorocca.com/api/uploads/file2.jpg', 'filename' => 'file2.jpg']
    ],
    'count' => 2
];
echo json_encode($sampleResponse, JSON_PRETTY_PRINT);
?></pre>
        </div>
    </div>

    <!-- Test Upload Form -->
    <div class="test-section">
        <h2>📁 Test Upload Gallery</h2>
        <div class="test-form">
            <form id="galleryForm" enctype="multipart/form-data">
                <div class="upload-area">
                    <h3>📤 Carica Immagini Multiple</h3>
                    <input type="file" id="galleryFiles" name="images" multiple accept="image/*" required>
                    <p>Seleziona più immagini tenendo premuto Ctrl (Windows) o Cmd (Mac)</p>
                </div>
                <br>
                <button type="submit">📁 Testa Upload Gallery</button>
            </form>
            
            <div id="galleryResult" style="margin-top: 15px;"></div>
        </div>
    </div>

    <!-- Test Status -->
    <div class="test-section">
        <h2>🎯 Status Correzioni</h2>
        <div class="success">
            <h3>✅ Tutte le Correzioni Completate</h3>
            <ul>
                <li>✅ <strong>Email contatti</strong>: Include tutti i campi del form</li>
                <li>✅ <strong>Upload gallery</strong>: Formato risposta corretto</li>
                <li>✅ <strong>Frontend</strong>: Cache buster aggiornato (v=1751123456)</li>
                <li>✅ <strong>API</strong>: Endpoint gallery aggiornato</li>
                <li>✅ <strong>Deploy</strong>: Tutti i file caricati su Hostinger</li>
            </ul>
        </div>
    </div>

    <script>
        document.getElementById('galleryForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const resultDiv = document.getElementById('galleryResult');
            const fileInput = document.getElementById('galleryFiles');
            const files = fileInput.files;
            
            if (files.length === 0) {
                resultDiv.innerHTML = '<div class="error">⚠️ Seleziona almeno un file</div>';
                return;
            }
            
            resultDiv.innerHTML = `<div class="info">🔄 Caricamento ${files.length} file in corso...</div>`;
            
            const formData = new FormData();
            for (let i = 0; i < files.length; i++) {
                formData.append(`images[${i}]`, files[i]);
            }
            
            try {
                const token = 'auth_token_1_' + Date.now(); // Token di esempio
                
                const response = await fetch('https://vincenzorocca.com/api/v1/admin/upload/gallery', {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    },
                    body: formData
                });
                
                const result = await response.json();
                
                console.log('📦 Gallery upload response:', result);
                
                if (result.success && result.urls) {
                    let imagesHtml = '';
                    if (result.urls && result.urls.length > 0) {
                        imagesHtml = '<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 10px; margin-top: 10px;">';
                        result.urls.forEach((url, index) => {
                            imagesHtml += `
                                <div style="text-align: center;">
                                    <img src="${url}" alt="Upload ${index + 1}" style="width: 100%; height: 100px; object-fit: cover; border-radius: 4px;">
                                    <small>File ${index + 1}</small>
                                </div>
                            `;
                        });
                        imagesHtml += '</div>';
                    }
                    
                    resultDiv.innerHTML = `
                        <div class="success">
                            <h3>✅ Gallery Caricata con Successo!</h3>
                            <p><strong>File caricati:</strong> ${result.count || result.urls.length}</p>
                            <p><strong>Messaggio:</strong> ${result.message}</p>
                            <p><strong>Campo 'urls' presente:</strong> ✅ Sì (${result.urls.length} URL)</p>
                            <p><strong>Campo 'data' presente:</strong> ${result.data ? '✅ Sì' : '❌ No'}</p>
                            ${imagesHtml}
                        </div>
                    `;
                } else {
                    resultDiv.innerHTML = `
                        <div class="error">
                            <h3>❌ Errore nell'upload</h3>
                            <p>${result.message || 'Errore sconosciuto'}</p>
                            <p><strong>Campo 'urls' presente:</strong> ${result.urls ? '✅ Sì' : '❌ No'}</p>
                            <pre>${JSON.stringify(result, null, 2)}</pre>
                        </div>
                    `;
                }
            } catch (error) {
                resultDiv.innerHTML = `
                    <div class="error">
                        <h3>❌ Errore di Connessione</h3>
                        <p>${error.message}</p>
                    </div>
                `;
            }
        });
    </script>

</body>
</html> 