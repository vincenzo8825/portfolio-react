<?php
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>🧪 Test Creazione Progetto con Gallery</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 1000px; margin: 0 auto; padding: 20px; }
        .test-section { background: #f5f5f5; padding: 15px; margin: 10px 0; border-radius: 8px; }
        .success { background: #d4edda; border-left: 4px solid #28a745; }
        .error { background: #f8d7da; border-left: 4px solid #dc3545; }
        .info { background: #d1ecf1; border-left: 4px solid #17a2b8; }
        .warning { background: #fff3cd; border-left: 4px solid #ffc107; }
        pre { background: #f8f9fa; padding: 10px; border-radius: 4px; overflow-x: auto; font-size: 12px; }
        .endpoint { font-family: monospace; background: #e9ecef; padding: 2px 6px; border-radius: 3px; }
        .test-form { background: white; padding: 20px; border-radius: 8px; margin: 10px 0; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, select, textarea { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        button { background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #0056b3; }
        .upload-area { border: 2px dashed #ddd; padding: 20px; text-align: center; border-radius: 8px; margin: 10px 0; }
        .upload-area:hover { border-color: #007bff; }
        .step { background: white; padding: 15px; margin: 10px 0; border-radius: 8px; border-left: 4px solid #007bff; }
        .step h3 { margin-top: 0; color: #007bff; }
    </style>
</head>
<body>
    <h1>🧪 Test Completo: Creazione Progetto con Gallery</h1>
    <p><strong>Data test:</strong> <?= date('d/m/Y H:i:s') ?></p>
    
    <!-- Step 1: Login -->
    <div class="step">
        <h3>📝 Step 1: Login Admin</h3>
        <div class="test-form">
            <form id="loginForm">
                <div class="form-group">
                    <label for="email">Email Admin:</label>
                    <input type="email" id="email" name="email" value="vincenzorocca88@gmail.com" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" value="admin123" required>
                </div>
                
                <button type="submit">🔐 Login</button>
            </form>
            
            <div id="loginResult" style="margin-top: 15px;"></div>
        </div>
    </div>

    <!-- Step 2: Upload Gallery -->
    <div class="step">
        <h3>📁 Step 2: Upload Gallery Immagini</h3>
        <div class="test-form">
            <div class="info">
                <p><strong>ℹ️ Nota:</strong> Prima fai il login, poi testa l'upload gallery</p>
            </div>
            
            <form id="galleryForm" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="galleryFiles">Seleziona 2-3 Immagini:</label>
                    <input type="file" id="galleryFiles" name="images" multiple accept="image/*" required>
                    <small>Seleziona più immagini tenendo premuto Ctrl (Windows) o Cmd (Mac)</small>
                </div>
                
                <button type="submit">📁 Upload Gallery</button>
            </form>
            
            <div id="galleryResult" style="margin-top: 15px;"></div>
        </div>
    </div>

    <!-- Step 3: Create Project -->
    <div class="step">
        <h3>🚀 Step 3: Crea Progetto Completo</h3>
        <div class="test-form">
            <div class="info">
                <p><strong>ℹ️ Nota:</strong> Prima fai login e upload gallery, poi le URL verranno inserite automaticamente</p>
            </div>
            
            <form id="projectForm">
                <div class="form-group">
                    <label for="projectTitle">Titolo Progetto:</label>
                    <input type="text" id="projectTitle" name="title" value="Test E-Commerce Platform" required>
                </div>
                
                <div class="form-group">
                    <label for="projectDescription">Descrizione:</label>
                    <textarea id="projectDescription" name="description" rows="3" required>Piattaforma e-commerce moderna sviluppata con React e Laravel. Include sistema di pagamento, gestione ordini e dashboard admin completa.</textarea>
                </div>
                
                <div class="form-group">
                    <label for="projectImageUrl">Immagine Principale URL:</label>
                    <input type="url" id="projectImageUrl" name="image_url" placeholder="https://vincenzorocca.com/api/uploads/main-image.jpg">
                </div>
                
                <div class="form-group">
                    <label for="projectGallery">Gallery URLs (JSON Array):</label>
                    <textarea id="projectGallery" name="gallery" rows="3" placeholder='["https://vincenzorocca.com/api/uploads/img1.jpg", "https://vincenzorocca.com/api/uploads/img2.jpg"]'></textarea>
                    <small>Le URL della gallery verranno inserite automaticamente dopo l'upload</small>
                </div>
                
                <div class="form-group">
                    <label for="projectTechnologies">Tecnologie (JSON Array):</label>
                    <textarea id="projectTechnologies" name="technologies" rows="2">["React", "Laravel", "MySQL", "Tailwind CSS"]</textarea>
                </div>
                
                <div class="form-group">
                    <label for="projectStatus">Status:</label>
                    <select id="projectStatus" name="status">
                        <option value="completed">Completato</option>
                        <option value="in-progress">In Corso</option>
                        <option value="planning">In Pianificazione</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>
                        <input type="checkbox" id="projectFeatured" name="featured"> 
                        Progetto in Evidenza
                    </label>
                </div>
                
                <button type="submit">🚀 Crea Progetto</button>
            </form>
            
            <div id="projectResult" style="margin-top: 15px;"></div>
        </div>
    </div>

    <!-- Step 4: Verify -->
    <div class="step">
        <h3>✅ Step 4: Verifica Risultato</h3>
        <div id="verificationResult">
            <div class="info">
                <p>Completa gli step precedenti per vedere il risultato finale</p>
            </div>
        </div>
    </div>

    <script>
        let authToken = null;
        let uploadedGalleryUrls = [];

        // Step 1: Login
        document.getElementById('loginForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const resultDiv = document.getElementById('loginResult');
            resultDiv.innerHTML = '<div class="info">🔄 Login in corso...</div>';
            
            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());
            
            try {
                const response = await fetch('https://vincenzorocca.com/api/v1/auth/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                console.log('📦 Login response:', result);
                
                if (result.user && result.token) {
                    authToken = result.token;
                    resultDiv.innerHTML = `
                        <div class="success">
                            <h3>✅ Login Riuscito!</h3>
                            <p><strong>User:</strong> ${result.user.name} (${result.user.email})</p>
                            <p><strong>Admin:</strong> ${result.user.is_admin ? 'Sì ✅' : 'No ❌'}</p>
                            <p><strong>Token:</strong> ${result.token.substring(0, 20)}...</p>
                        </div>
                    `;
                } else {
                    resultDiv.innerHTML = `
                        <div class="error">
                            <h3>❌ Errore Login</h3>
                            <p>${result.message || 'Credenziali non valide'}</p>
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

        // Step 2: Gallery Upload
        document.getElementById('galleryForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            if (!authToken) {
                document.getElementById('galleryResult').innerHTML = '<div class="error">⚠️ Fai prima il login!</div>';
                return;
            }
            
            const resultDiv = document.getElementById('galleryResult');
            const fileInput = document.getElementById('galleryFiles');
            const files = fileInput.files;
            
            if (files.length === 0) {
                resultDiv.innerHTML = '<div class="warning">⚠️ Seleziona almeno un file</div>';
                return;
            }
            
            resultDiv.innerHTML = `<div class="info">🔄 Caricamento ${files.length} file in corso...</div>`;
            
            const formData = new FormData();
            for (let i = 0; i < files.length; i++) {
                formData.append(`images[${i}]`, files[i]);
            }
            
            try {
                const response = await fetch('https://vincenzorocca.com/api/v1/admin/upload/gallery', {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${authToken}`,
                        'Accept': 'application/json'
                    },
                    body: formData
                });
                
                const result = await response.json();
                console.log('📦 Gallery upload response:', result);
                
                if (result.success && result.urls) {
                    uploadedGalleryUrls = result.urls;
                    
                    // Aggiorna automaticamente il campo gallery nel form progetto
                    document.getElementById('projectGallery').value = JSON.stringify(result.urls);
                    
                    let imagesHtml = '';
                    if (result.urls && result.urls.length > 0) {
                        imagesHtml = '<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 10px; margin-top: 10px;">';
                        result.urls.forEach((url, index) => {
                            imagesHtml += `
                                <div style="text-align: center;">
                                    <img src="${url}" alt="Upload ${index + 1}" style="width: 100%; height: 100px; object-fit: cover; border-radius: 4px;">
                                    <small>${url.split('/').pop()}</small>
                                </div>
                            `;
                        });
                        imagesHtml += '</div>';
                    }
                    
                    resultDiv.innerHTML = `
                        <div class="success">
                            <h3>✅ Gallery Caricata con Successo!</h3>
                            <p><strong>File caricati:</strong> ${result.count || result.urls.length}</p>
                            <p><strong>Campo 'urls' presente:</strong> ✅ Sì (${result.urls.length} URL)</p>
                            <p><strong>Gallery aggiornata nel form progetto:</strong> ✅ Automatico</p>
                            ${imagesHtml}
                        </div>
                    `;
                } else {
                    resultDiv.innerHTML = `
                        <div class="error">
                            <h3>❌ Errore nell'upload</h3>
                            <p>${result.message || 'Errore sconosciuto'}</p>
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

        // Step 3: Create Project
        document.getElementById('projectForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            if (!authToken) {
                document.getElementById('projectResult').innerHTML = '<div class="error">⚠️ Fai prima il login!</div>';
                return;
            }
            
            const resultDiv = document.getElementById('projectResult');
            resultDiv.innerHTML = '<div class="info">🔄 Creazione progetto in corso...</div>';
            
            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());
            
            // Parse JSON fields
            try {
                if (data.gallery) data.gallery = JSON.parse(data.gallery);
                if (data.technologies) data.technologies = JSON.parse(data.technologies);
                data.featured = document.getElementById('projectFeatured').checked;
            } catch (error) {
                resultDiv.innerHTML = `<div class="error">❌ Errore nel parsing JSON: ${error.message}</div>`;
                return;
            }
            
            try {
                const response = await fetch('https://vincenzorocca.com/api/v1/admin/projects', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${authToken}`,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                console.log('📦 Project creation response:', result);
                
                if (result.success && result.data) {
                    const project = result.data;
                    
                    resultDiv.innerHTML = `
                        <div class="success">
                            <h3>✅ Progetto Creato con Successo!</h3>
                            <p><strong>ID:</strong> ${project.id}</p>
                            <p><strong>Titolo:</strong> ${project.title}</p>
                            <p><strong>Slug:</strong> ${project.slug}</p>
                            <p><strong>Status:</strong> ${project.status}</p>
                            <p><strong>Featured:</strong> ${project.featured ? 'Sì ✅' : 'No'}</p>
                            <p><strong>Gallery:</strong> ${project.gallery ? project.gallery.length : 0} immagini</p>
                            <p><strong>Tecnologie:</strong> ${project.technologies ? project.technologies.length : 0} selezionate</p>
                        </div>
                    `;
                    
                    // Update verification
                    document.getElementById('verificationResult').innerHTML = `
                        <div class="success">
                            <h3>🎉 Test Completato con Successo!</h3>
                            <p><strong>✅ Login:</strong> Riuscito</p>
                            <p><strong>✅ Upload Gallery:</strong> ${uploadedGalleryUrls.length} immagini caricate</p>
                            <p><strong>✅ Creazione Progetto:</strong> ID ${project.id} creato</p>
                            <p><strong>🌐 URL Progetto:</strong> <a href="https://vincenzorocca.com/projects/${project.slug}" target="_blank">https://vincenzorocca.com/projects/${project.slug}</a></p>
                        </div>
                    `;
                } else {
                    resultDiv.innerHTML = `
                        <div class="error">
                            <h3>❌ Errore nella creazione</h3>
                            <p>${result.message || 'Errore sconosciuto'}</p>
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