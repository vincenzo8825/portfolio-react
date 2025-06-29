<?php
/**
 * 🧪 TEST PORTFOLIO COMPLETO
 * =========================
 * Script per testare tutte le funzionalità del portfolio:
 * - Autenticazione admin
 * - CRUD progetti
 * - Form contatti
 * - Upload immagini
 * - API pubbliche
 */

header('Content-Type: text/html; charset=utf-8');
ini_set('display_errors', 1);
error_reporting(E_ALL);

?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🧪 Test Portfolio Completo</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', system-ui; margin: 40px; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .test-section { margin: 30px 0; padding: 20px; border: 1px solid #e5e5e5; border-radius: 8px; }
        .success { color: #10b981; font-weight: bold; }
        .error { color: #ef4444; font-weight: bold; }
        .warning { color: #f59e0b; font-weight: bold; }
        .info { color: #3b82f6; font-weight: bold; }
        button { background: #3b82f6; color: white; border: none; padding: 12px 24px; border-radius: 6px; cursor: pointer; margin: 5px; }
        button:hover { background: #2563eb; }
        input, textarea, select { width: 100%; padding: 12px; margin: 8px 0; border: 1px solid #d1d5db; border-radius: 6px; }
        .form-row { display: flex; gap: 15px; }
        .form-row > div { flex: 1; }
        .results { background: #f8fafc; padding: 15px; border-radius: 6px; margin: 15px 0; }
        .hidden { display: none; }
        .admin-section { background: #fef3c7; border-left: 4px solid #f59e0b; }
        .public-section { background: #ecfdf5; border-left: 4px solid #10b981; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🧪 Test Portfolio Completo</h1>
        <p><strong>Sistema di testing per tutte le funzionalità del portfolio React + Laravel</strong></p>
        
        <!-- SEZIONE 1: TEST API PUBBLICHE -->
        <div class="test-section public-section">
            <h2>🌐 Test API Pubbliche</h2>
            <p>Test delle API accessibili senza autenticazione</p>
            
            <button onclick="testPublicAPIs()">🔍 Testa API Pubbliche</button>
            <div id="public-api-results" class="results hidden"></div>
        </div>

        <!-- SEZIONE 2: AUTENTICAZIONE ADMIN -->
        <div class="test-section admin-section">
            <h2>🔐 Test Autenticazione Admin</h2>
            <p>Login dell'amministratore per accedere al pannello di controllo</p>
            
            <div class="form-row">
                <div>
                    <input type="email" id="admin-email" placeholder="Email Admin" value="vincenzorocca88@gmail.com">
                </div>
                <div>
                    <input type="password" id="admin-password" placeholder="Password Admin">
                </div>
            </div>
            
            <button onclick="testAdminLogin()">🔑 Test Login Admin</button>
            <button onclick="testAdminLogout()" id="logout-btn" class="hidden">🚪 Logout</button>
            
            <div id="auth-results" class="results hidden"></div>
        </div>

        <!-- SEZIONE 3: CRUD PROGETTI -->
        <div class="test-section admin-section">
            <h2>📁 Test CRUD Progetti</h2>
            <p>Creazione, lettura, modifica ed eliminazione progetti (richiede login admin)</p>
            
            <h3>Crea Nuovo Progetto</h3>
            <div class="form-row">
                <div>
                    <input type="text" id="project-title" placeholder="Titolo Progetto" value="Progetto Test">
                </div>
                <div>
                    <input type="text" id="project-category" placeholder="Categoria" value="Web Development">
                </div>
            </div>
            
            <textarea id="project-description" placeholder="Descrizione progetto" rows="4">Questo è un progetto di test creato automaticamente per verificare il funzionamento del sistema CRUD.</textarea>
            
            <div class="form-row">
                <div>
                    <input type="url" id="project-demo" placeholder="URL Demo (opzionale)">
                </div>
                <div>
                    <input type="url" id="project-github" placeholder="URL GitHub (opzionale)">
                </div>
            </div>
            
            <div class="form-row">
                <div>
                    <select id="project-status">
                        <option value="in-progress">In Corso</option>
                        <option value="completed">Completato</option>
                        <option value="paused">In Pausa</option>
                    </select>
                </div>
                <div>
                    <label>
                        <input type="checkbox" id="project-featured"> Progetto in evidenza
                    </label>
                </div>
            </div>
            
            <input type="text" id="project-technologies" placeholder="Tecnologie (separate da virgola)" value="React, Laravel, MySQL">
            
            <button onclick="testCreateProject()">➕ Crea Progetto</button>
            <button onclick="testReadProjects()">📖 Leggi Progetti</button>
            <button onclick="testUpdateProject()">✏️ Modifica Ultimo Progetto</button>
            <button onclick="testDeleteProject()">🗑️ Elimina Ultimo Progetto</button>
            
            <div id="project-results" class="results hidden"></div>
        </div>

        <!-- SEZIONE 4: FORM CONTATTI -->
        <div class="test-section public-section">
            <h2>📞 Test Form Contatti</h2>
            <p>Test del sistema di contatti e invio email</p>
            
            <div class="form-row">
                <div>
                    <input type="text" id="contact-name" placeholder="Nome" value="Mario Rossi">
                </div>
                <div>
                    <input type="email" id="contact-email" placeholder="Email" value="mario.rossi@example.com">
                </div>
            </div>
            
            <input type="text" id="contact-subject" placeholder="Oggetto" value="Richiesta informazioni portfolio">
            
            <textarea id="contact-message" placeholder="Messaggio" rows="4">Salve, sono interessato ai vostri servizi di sviluppo web. Potreste fornirmi maggiori informazioni sui tempi e costi per un progetto e-commerce?</textarea>
            
            <div class="form-row">
                <div>
                    <select id="contact-budget">
                        <option value="">Seleziona budget</option>
                        <option value="under-1k">Meno di 1.000€</option>
                        <option value="1k-5k">1.000€ - 5.000€</option>
                        <option value="5k-10k">5.000€ - 10.000€</option>
                        <option value="10k-plus">Oltre 10.000€</option>
                    </select>
                </div>
                <div>
                    <select id="contact-timeline">
                        <option value="">Seleziona tempistica</option>
                        <option value="asap">Il prima possibile</option>
                        <option value="1-month">Entro 1 mese</option>
                        <option value="2-3-months">2-3 mesi</option>
                        <option value="flexible">Flessibile</option>
                    </select>
                </div>
            </div>
            
            <select id="contact-project-type">
                <option value="">Tipo di progetto</option>
                <option value="website">Sito Web</option>
                <option value="webapp">Web App</option>
                <option value="ecommerce">E-commerce</option>
                <option value="mobile">App Mobile</option>
                <option value="other">Altro</option>
            </select>
            
            <button onclick="testContactForm()">📧 Invia Messaggio Test</button>
            
            <div id="contact-results" class="results hidden"></div>
        </div>

        <!-- SEZIONE 5: TEST UPLOAD -->
        <div class="test-section admin-section">
            <h2>☁️ Test Upload Immagini</h2>
            <p>Test del sistema di upload su Cloudinary (richiede login admin)</p>
            
            <input type="file" id="upload-file" accept="image/*">
            <button onclick="testImageUpload()">📷 Test Upload Immagine</button>
            
            <div id="upload-results" class="results hidden"></div>
        </div>

        <!-- SEZIONE 6: TEST COMPLETO -->
        <div class="test-section">
            <h2>🚀 Test Completo Sistema</h2>
            <p>Esegue tutti i test in sequenza per verificare l'intero sistema</p>
            
            <button onclick="runCompleteTest()">🧪 Esegui Test Completo</button>
            
            <div id="complete-results" class="results hidden"></div>
        </div>
    </div>

    <script>
        // Variabili globali
        let authToken = null;
        let lastProjectId = null;
        const API_BASE = 'https://vincenzorocca.com/api/v1';

        // Utility: Display results
        function displayResults(elementId, content) {
            const element = document.getElementById(elementId);
            element.innerHTML = content;
            element.classList.remove('hidden');
        }

        // Utility: Make API request
        async function makeRequest(url, options = {}) {
            try {
                const defaultHeaders = {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                };

                if (authToken) {
                    defaultHeaders['Authorization'] = `Bearer ${authToken}`;
                }

                const response = await fetch(url, {
                    ...options,
                    headers: { ...defaultHeaders, ...options.headers }
                });

                const data = await response.json();
                return { status: response.status, data, ok: response.ok };
            } catch (error) {
                return { status: 0, data: { message: error.message }, ok: false };
            }
        }

        // TEST 1: API Pubbliche
        async function testPublicAPIs() {
            let results = '<h3>Risultati Test API Pubbliche:</h3>';
            
            const endpoints = [
                { url: `${API_BASE}/test`, name: 'Test Endpoint' },
                { url: `${API_BASE}/projects`, name: 'Lista Progetti' },
                { url: `${API_BASE}/projects/featured`, name: 'Progetti in Evidenza' },
                { url: `${API_BASE}/technologies`, name: 'Lista Tecnologie' }
            ];

            for (const endpoint of endpoints) {
                const response = await makeRequest(endpoint.url);
                
                if (response.ok) {
                    results += `<div class="success">✅ ${endpoint.name}: OK</div>`;
                } else {
                    results += `<div class="error">❌ ${endpoint.name}: Errore ${response.status}</div>`;
                }
            }

            displayResults('public-api-results', results);
        }

        // TEST 2: Autenticazione Admin
        async function testAdminLogin() {
            const email = document.getElementById('admin-email').value;
            const password = document.getElementById('admin-password').value;

            if (!email || !password) {
                displayResults('auth-results', '<div class="error">❌ Inserisci email e password</div>');
                return;
            }

            const response = await makeRequest(`${API_BASE}/auth/login`, {
                method: 'POST',
                body: JSON.stringify({ email, password })
            });

            if (response.ok && response.data.success) {
                authToken = response.data.data.token;
                document.getElementById('logout-btn').classList.remove('hidden');
                
                displayResults('auth-results', `
                    <div class="success">✅ Login effettuato con successo!</div>
                    <div class="info">👤 Utente: ${response.data.data.user.name}</div>
                    <div class="info">📧 Email: ${response.data.data.user.email}</div>
                    <div class="info">🔑 Token ottenuto</div>
                `);
            } else {
                displayResults('auth-results', `
                    <div class="error">❌ Login fallito</div>
                    <div class="error">Errore: ${response.data.message || 'Credenziali non valide'}</div>
                `);
            }
        }

        async function testAdminLogout() {
            if (!authToken) {
                displayResults('auth-results', '<div class="error">❌ Non sei loggato</div>');
                return;
            }

            const response = await makeRequest(`${API_BASE}/auth/logout`, { method: 'POST' });
            
            if (response.ok) {
                authToken = null;
                document.getElementById('logout-btn').classList.add('hidden');
                displayResults('auth-results', '<div class="success">✅ Logout effettuato</div>');
            } else {
                displayResults('auth-results', '<div class="error">❌ Errore durante logout</div>');
            }
        }

        // TEST 3: CRUD Progetti
        async function testCreateProject() {
            if (!authToken) {
                displayResults('project-results', '<div class="error">❌ Devi essere loggato come admin</div>');
                return;
            }

            const projectData = {
                title: document.getElementById('project-title').value,
                description: document.getElementById('project-description').value,
                category: document.getElementById('project-category').value,
                demo_url: document.getElementById('project-demo').value,
                github_url: document.getElementById('project-github').value,
                status: document.getElementById('project-status').value,
                featured: document.getElementById('project-featured').checked,
                technologies: document.getElementById('project-technologies').value.split(',').map(t => t.trim())
            };

            const response = await makeRequest(`${API_BASE}/admin/projects`, {
                method: 'POST',
                body: JSON.stringify(projectData)
            });

            if (response.ok && response.data.success) {
                lastProjectId = response.data.data.id;
                displayResults('project-results', `
                    <div class="success">✅ Progetto creato con successo!</div>
                    <div class="info">🆔 ID: ${response.data.data.id}</div>
                    <div class="info">📝 Titolo: ${response.data.data.title}</div>
                    <div class="info">🏷️ Slug: ${response.data.data.slug}</div>
                `);
            } else {
                displayResults('project-results', `
                    <div class="error">❌ Errore creazione progetto</div>
                    <div class="error">${response.data.message || 'Errore sconosciuto'}</div>
                `);
            }
        }

        async function testReadProjects() {
            const response = await makeRequest(`${API_BASE}/projects`);
            
            if (response.ok && response.data.success) {
                const projects = response.data.data;
                displayResults('project-results', `
                    <div class="success">✅ Progetti caricati: ${projects.length}</div>
                    <div class="info">📊 Progetti trovati:</div>
                    ${projects.slice(0, 3).map(p => `<div>• ${p.title} (${p.status})</div>`).join('')}
                    ${projects.length > 3 ? `<div>... e altri ${projects.length - 3} progetti</div>` : ''}
                `);
            } else {
                displayResults('project-results', '<div class="error">❌ Errore lettura progetti</div>');
            }
        }

        async function testUpdateProject() {
            if (!authToken || !lastProjectId) {
                displayResults('project-results', '<div class="error">❌ Crea prima un progetto o effettua login</div>');
                return;
            }

            const updateData = {
                title: document.getElementById('project-title').value + ' (Modificato)',
                description: 'Descrizione aggiornata dal test automatico'
            };

            const response = await makeRequest(`${API_BASE}/admin/projects/${lastProjectId}`, {
                method: 'PUT',
                body: JSON.stringify(updateData)
            });

            if (response.ok && response.data.success) {
                displayResults('project-results', `
                    <div class="success">✅ Progetto modificato con successo!</div>
                    <div class="info">📝 Nuovo titolo: ${response.data.data.title}</div>
                `);
            } else {
                displayResults('project-results', '<div class="error">❌ Errore modifica progetto</div>');
            }
        }

        async function testDeleteProject() {
            if (!authToken || !lastProjectId) {
                displayResults('project-results', '<div class="error">❌ Crea prima un progetto o effettua login</div>');
                return;
            }

            const response = await makeRequest(`${API_BASE}/admin/projects/${lastProjectId}`, {
                method: 'DELETE'
            });

            if (response.ok && response.data.success) {
                displayResults('project-results', '<div class="success">✅ Progetto eliminato con successo!</div>');
                lastProjectId = null;
            } else {
                displayResults('project-results', '<div class="error">❌ Errore eliminazione progetto</div>');
            }
        }

        // TEST 4: Form Contatti
        async function testContactForm() {
            const contactData = {
                name: document.getElementById('contact-name').value,
                email: document.getElementById('contact-email').value,
                subject: document.getElementById('contact-subject').value,
                message: document.getElementById('contact-message').value,
                budget: document.getElementById('contact-budget').value,
                timeline: document.getElementById('contact-timeline').value,
                projectType: document.getElementById('contact-project-type').value
            };

            const response = await makeRequest(`${API_BASE}/contacts`, {
                method: 'POST',
                body: JSON.stringify(contactData)
            });

            if (response.ok && response.data.success) {
                displayResults('contact-results', `
                    <div class="success">✅ Messaggio inviato con successo!</div>
                    <div class="info">📧 Email di conferma inviata</div>
                    <div class="info">📬 Notifica admin inviata</div>
                    <div class="info">🆔 ID messaggio: ${response.data.data.id}</div>
                `);
            } else {
                let errorMsg = response.data.message || 'Errore sconosciuto';
                if (response.data.errors) {
                    errorMsg += '<br>Dettagli: ' + Object.values(response.data.errors).flat().join(', ');
                }
                displayResults('contact-results', `<div class="error">❌ ${errorMsg}</div>`);
            }
        }

        // TEST 5: Upload Immagini
        async function testImageUpload() {
            if (!authToken) {
                displayResults('upload-results', '<div class="error">❌ Devi essere loggato come admin</div>');
                return;
            }

            const fileInput = document.getElementById('upload-file');
            if (!fileInput.files[0]) {
                displayResults('upload-results', '<div class="error">❌ Seleziona un file immagine</div>');
                return;
            }

            const formData = new FormData();
            formData.append('image', fileInput.files[0]);

            try {
                const response = await fetch(`${API_BASE}/admin/upload/image`, {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${authToken}`
                    },
                    body: formData
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    displayResults('upload-results', `
                        <div class="success">✅ Immagine caricata con successo!</div>
                        <div class="info">🔗 URL: ${data.data.url}</div>
                        <div class="info">📏 Dimensioni: ${data.data.width}x${data.data.height}</div>
                        <img src="${data.data.url}" style="max-width: 200px; margin: 10px 0;" alt="Uploaded">
                    `);
                } else {
                    displayResults('upload-results', `<div class="error">❌ Errore upload: ${data.message}</div>`);
                }
            } catch (error) {
                displayResults('upload-results', `<div class="error">❌ Errore: ${error.message}</div>`);
            }
        }

        // TEST 6: Test Completo
        async function runCompleteTest() {
            let results = '<h3>🧪 Esecuzione Test Completo</h3>';
            
            results += '<div class="info">1️⃣ Testing API pubbliche...</div>';
            await testPublicAPIs();
            
            results += '<div class="info">2️⃣ Testing autenticazione admin...</div>';
            if (document.getElementById('admin-password').value) {
                await testAdminLogin();
            } else {
                results += '<div class="warning">⚠️ Password admin non inserita - skip login</div>';
            }
            
            results += '<div class="info">3️⃣ Testing lettura progetti...</div>';
            await testReadProjects();
            
            results += '<div class="info">4️⃣ Testing form contatti...</div>';
            await testContactForm();
            
            results += '<div class="success">✅ Test completo terminato!</div>';
            
            displayResults('complete-results', results);
        }
    </script>
</body>
</html> 