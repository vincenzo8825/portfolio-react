<?php
/**
 * 🔄 TEST COMUNICAZIONE FRONTEND ↔ BACKEND
 * ========================================
 * Test completo per verificare che frontend e backend comunichino correttamente
 * su vincenzorocca.com prima del deploy definitivo
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
    <title>🔄 Test Comunicazione Frontend ↔ Backend</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', system-ui; margin: 40px; background: #f5f7fa; }
        .container { max-width: 1400px; margin: 0 auto; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .test-section { margin: 30px 0; padding: 25px; border: 2px solid #e5e7eb; border-radius: 10px; background: #fafafa; }
        .success { color: #059669; font-weight: bold; background: #ecfdf5; padding: 10px; border-radius: 6px; margin: 5px 0; }
        .error { color: #dc2626; font-weight: bold; background: #fef2f2; padding: 10px; border-radius: 6px; margin: 5px 0; }
        .warning { color: #d97706; font-weight: bold; background: #fffbeb; padding: 10px; border-radius: 6px; margin: 5px 0; }
        .info { color: #2563eb; font-weight: bold; background: #eff6ff; padding: 10px; border-radius: 6px; margin: 5px 0; }
        button { background: #3b82f6; color: white; border: none; padding: 14px 28px; border-radius: 8px; cursor: pointer; margin: 8px; font-size: 16px; transition: all 0.3s; }
        button:hover { background: #2563eb; transform: translateY(-2px); }
        .critical { background: #dc2626; }
        .critical:hover { background: #b91c1c; }
        input, textarea { width: 100%; padding: 12px; margin: 8px 0; border: 2px solid #d1d5db; border-radius: 8px; font-size: 14px; }
        .results { background: #f8fafc; padding: 20px; border-radius: 8px; margin: 15px 0; border-left: 4px solid #3b82f6; }
        .hidden { display: none; }
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .status-indicator { width: 12px; height: 12px; border-radius: 50%; display: inline-block; margin-right: 8px; }
        .status-ok { background: #10b981; }
        .status-error { background: #ef4444; }
        .status-warning { background: #f59e0b; }
        .real-time { background: #1f2937; color: white; padding: 15px; border-radius: 8px; font-family: monospace; font-size: 12px; white-space: pre-wrap; max-height: 300px; overflow-y: auto; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔄 Test Comunicazione Frontend ↔ Backend</h1>
        <p><strong>Verifica completa della comunicazione tra React frontend e Laravel backend su vincenzorocca.com</strong></p>
        
        <!-- STATUS GENERALE -->
        <div class="test-section">
            <h2>📊 Status Sistema</h2>
            <div class="grid">
                <div>
                    <h3>🎨 Frontend Status</h3>
                    <div id="frontend-status">
                        <span class="status-indicator status-warning"></span>In verifica...
                    </div>
                </div>
                <div>
                    <h3>🔧 Backend Status</h3>
                    <div id="backend-status">
                        <span class="status-indicator status-warning"></span>In verifica...
                    </div>
                </div>
            </div>
            <button onclick="testSystemStatus()">🔍 Verifica Status Sistema</button>
            <div id="status-results" class="results hidden"></div>
        </div>

        <!-- TEST API CONNECTIVITY -->
        <div class="test-section">
            <h2>🌐 Test Connettività API</h2>
            <p>Verifica che tutte le API endpoint rispondano correttamente</p>
            
            <button onclick="testAPIConnectivity()">🔗 Test Connettività API</button>
            <button onclick="testCORSHeaders()">🌍 Test Headers CORS</button>
            
            <div id="api-results" class="results hidden"></div>
        </div>

        <!-- TEST AUTENTICAZIONE -->
        <div class="test-section">
            <h2>🔐 Test Autenticazione Completa</h2>
            <p>Test del flusso completo di autenticazione admin</p>
            
            <div class="grid">
                <div>
                    <input type="email" id="test-email" placeholder="Email Admin" value="vincenzorocca88@gmail.com">
                </div>
                <div>
                    <input type="password" id="test-password" placeholder="Password Admin">
                </div>
            </div>
            
            <button onclick="testFullAuthFlow()">🔑 Test Flusso Autenticazione</button>
            <button onclick="testTokenPersistence()">💾 Test Persistenza Token</button>
            
            <div id="auth-results" class="results hidden"></div>
        </div>

        <!-- TEST CRUD COMPLETO -->
        <div class="test-section">
            <h2>📁 Test CRUD Frontend → Backend</h2>
            <p>Test completo delle operazioni CRUD con comunicazione frontend-backend</p>
            
            <button onclick="testProjectCRUDFlow()">📝 Test CRUD Progetti</button>
            <button onclick="testContactFormFlow()">📧 Test Form Contatti</button>
            <button onclick="testUploadFlow()">☁️ Test Upload Sistema</button>
            
            <div id="crud-results" class="results hidden"></div>
        </div>

        <!-- TEST PERFORMANCE -->
        <div class="test-section">
            <h2>⚡ Test Performance & Loading</h2>
            <p>Verifica tempi di risposta e performance delle API</p>
            
            <button onclick="testAPIPerformance()">📈 Test Performance API</button>
            <button onclick="testFrontendLoading()">🎨 Test Caricamento Frontend</button>
            
            <div id="performance-results" class="results hidden"></div>
        </div>

        <!-- SIMULAZIONE PRODUZIONE -->
        <div class="test-section">
            <h2>🚀 Simulazione Ambiente Produzione</h2>
            <p>Test che simula l'utilizzo reale del portfolio</p>
            
            <button onclick="runProductionSimulation()" class="critical">🎯 Simulazione Produzione Completa</button>
            
            <div id="simulation-results" class="results hidden"></div>
        </div>

        <!-- LOG REAL-TIME -->
        <div class="test-section">
            <h2>📝 Log Real-time</h2>
            <div id="realtime-log" class="real-time">Pronto per logging...</div>
            <button onclick="clearLog()">🗑️ Pulisci Log</button>
        </div>
    </div>

    <script>
        // Configurazione globale
        const API_BASE = 'https://vincenzorocca.com/api/v1';
        const FRONTEND_URL = 'https://vincenzorocca.com';
        let authToken = null;
        let testResults = {
            frontend: false,
            backend: false,
            auth: false,
            crud: false,
            performance: true
        };

        // Logging real-time
        function log(message, type = 'info') {
            const logElement = document.getElementById('realtime-log');
            const timestamp = new Date().toLocaleTimeString();
            const typeSymbol = type === 'success' ? '✅' : type === 'error' ? '❌' : type === 'warning' ? '⚠️' : 'ℹ️';
            logElement.textContent += `[${timestamp}] ${typeSymbol} ${message}\n`;
            logElement.scrollTop = logElement.scrollHeight;
        }

        function clearLog() {
            document.getElementById('realtime-log').textContent = 'Log pulito...\n';
        }

        // Utility per API calls
        async function makeAPICall(endpoint, options = {}) {
            const url = `${API_BASE}${endpoint}`;
            const startTime = performance.now();
            
            try {
                log(`Chiamata API: ${endpoint}`);
                
                const defaultHeaders = {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                };

                if (authToken) {
                    defaultHeaders['Authorization'] = `Bearer ${authToken}`;
                }

                const response = await fetch(url, {
                    ...options,
                    headers: { ...defaultHeaders, ...options.headers }
                });

                const endTime = performance.now();
                const responseTime = Math.round(endTime - startTime);

                const data = await response.json();
                
                log(`API ${endpoint}: ${response.status} (${responseTime}ms)`, response.ok ? 'success' : 'error');
                
                return { 
                    status: response.status, 
                    data, 
                    ok: response.ok,
                    responseTime,
                    headers: Object.fromEntries(response.headers.entries())
                };
            } catch (error) {
                const endTime = performance.now();
                const responseTime = Math.round(endTime - startTime);
                log(`Errore API ${endpoint}: ${error.message} (${responseTime}ms)`, 'error');
                return { status: 0, data: { message: error.message }, ok: false, responseTime };
            }
        }

        // Test 1: Status Sistema
        async function testSystemStatus() {
            log('Inizio test status sistema', 'info');
            
            // Test Frontend
            try {
                const frontendResponse = await fetch(FRONTEND_URL);
                if (frontendResponse.ok) {
                    testResults.frontend = true;
                    document.getElementById('frontend-status').innerHTML = 
                        '<span class="status-indicator status-ok"></span>Frontend OK';
                    log('Frontend: OK', 'success');
                } else {
                    throw new Error('Frontend non raggiungibile');
                }
            } catch (error) {
                document.getElementById('frontend-status').innerHTML = 
                    '<span class="status-indicator status-error"></span>Frontend ERROR';
                log(`Frontend: ${error.message}`, 'error');
            }

            // Test Backend
            const backendTest = await makeAPICall('/test');
            if (backendTest.ok) {
                testResults.backend = true;
                document.getElementById('backend-status').innerHTML = 
                    '<span class="status-indicator status-ok"></span>Backend OK';
            } else {
                document.getElementById('backend-status').innerHTML = 
                    '<span class="status-indicator status-error"></span>Backend ERROR';
            }

            const results = `
                <h3>📊 Risultati Test Sistema</h3>
                <div class="${testResults.frontend ? 'success' : 'error'}">
                    Frontend: ${testResults.frontend ? 'ONLINE' : 'OFFLINE'}
                </div>
                <div class="${testResults.backend ? 'success' : 'error'}">
                    Backend: ${testResults.backend ? 'ONLINE' : 'OFFLINE'}
                </div>
                <div class="info">
                    Status generale: ${testResults.frontend && testResults.backend ? 'SISTEMA OK' : 'PROBLEMI RILEVATI'}
                </div>
            `;

            document.getElementById('status-results').innerHTML = results;
            document.getElementById('status-results').classList.remove('hidden');
        }

        // Test 2: Connettività API
        async function testAPIConnectivity() {
            log('Test connettività API endpoints', 'info');
            
            const endpoints = [
                { path: '/test', name: 'Test Endpoint', critical: true },
                { path: '/projects', name: 'Lista Progetti', critical: true },
                { path: '/projects/featured', name: 'Progetti Featured', critical: false },
                { path: '/technologies', name: 'Tecnologie', critical: true }
            ];

            let results = '<h3>🌐 Test Connettività API</h3>';
            let allCriticalOK = true;

            for (const endpoint of endpoints) {
                const response = await makeAPICall(endpoint.path);
                const status = response.ok ? 'success' : 'error';
                const symbol = response.ok ? '✅' : '❌';
                
                if (endpoint.critical && !response.ok) {
                    allCriticalOK = false;
                }

                results += `
                    <div class="${status}">
                        ${symbol} ${endpoint.name} (${endpoint.path})
                        <br>Status: ${response.status} | Tempo: ${response.responseTime}ms
                        ${endpoint.critical ? ' | <strong>CRITICO</strong>' : ''}
                    </div>
                `;
            }

            results += `
                <div class="${allCriticalOK ? 'success' : 'error'}">
                    <strong>Risultato:</strong> ${allCriticalOK ? 'Tutti gli endpoint critici sono OK' : 'ERRORI negli endpoint critici'}
                </div>
            `;

            document.getElementById('api-results').innerHTML = results;
            document.getElementById('api-results').classList.remove('hidden');
        }

        // Test 3: Headers CORS
        async function testCORSHeaders() {
            log('Test headers CORS', 'info');
            
            const response = await makeAPICall('/test');
            
            let results = '<h3>🌍 Test Headers CORS</h3>';
            
            const corsHeaders = [
                'access-control-allow-origin',
                'access-control-allow-methods',
                'access-control-allow-headers',
                'access-control-allow-credentials'
            ];

            corsHeaders.forEach(header => {
                const value = response.headers[header];
                const hasHeader = !!value;
                
                results += `
                    <div class="${hasHeader ? 'success' : 'warning'}">
                        ${hasHeader ? '✅' : '⚠️'} ${header}: ${value || 'Non presente'}
                    </div>
                `;
            });

            document.getElementById('api-results').innerHTML += results;
        }

        // Test 4: Flusso Autenticazione
        async function testFullAuthFlow() {
            log('Test flusso autenticazione completo', 'info');
            
            const email = document.getElementById('test-email').value;
            const password = document.getElementById('test-password').value;

            if (!email || !password) {
                document.getElementById('auth-results').innerHTML = 
                    '<div class="error">❌ Inserisci email e password per il test</div>';
                document.getElementById('auth-results').classList.remove('hidden');
                return;
            }

            let results = '<h3>🔐 Test Flusso Autenticazione</h3>';

            // Test 1: Login
            const loginResponse = await makeAPICall('/auth/login', {
                method: 'POST',
                body: JSON.stringify({ email, password })
            });

            if (loginResponse.ok && loginResponse.data.success) {
                authToken = loginResponse.data.data.token;
                testResults.auth = true;
                
                results += `
                    <div class="success">
                        ✅ Login riuscito
                        <br>Token ottenuto: ${authToken.substring(0, 20)}...
                        <br>User: ${loginResponse.data.data.user.name}
                    </div>
                `;

                // Test 2: Verifica token con /auth/me
                const meResponse = await makeAPICall('/auth/me');
                if (meResponse.ok) {
                    results += `
                        <div class="success">
                            ✅ Token valido - Dati utente recuperati
                            <br>Email: ${meResponse.data.data.email}
                        </div>
                    `;
                } else {
                    results += '<div class="error">❌ Token non valido per /auth/me</div>';
                }

                // Test 3: Accesso a endpoint protetto
                const adminResponse = await makeAPICall('/admin/projects');
                if (adminResponse.status === 200 || adminResponse.status === 401) {
                    results += `
                        <div class="success">
                            ✅ Endpoint admin raggiungibile (status: ${adminResponse.status})
                        </div>
                    `;
                } else {
                    results += '<div class="error">❌ Endpoint admin non raggiungibile</div>';
                }

            } else {
                results += `
                    <div class="error">
                        ❌ Login fallito
                        <br>Errore: ${loginResponse.data.message || 'Credenziali non valide'}
                    </div>
                `;
            }

            document.getElementById('auth-results').innerHTML = results;
            document.getElementById('auth-results').classList.remove('hidden');
        }

        // Test 5: Persistenza Token
        async function testTokenPersistence() {
            if (!authToken) {
                log('Test persistenza token: token non disponibile', 'warning');
                return;
            }

            log('Test persistenza e refresh token', 'info');
            
            // Simula refresh token
            const refreshResponse = await makeAPICall('/auth/refresh', { method: 'POST' });
            
            let results = '<h3>💾 Test Persistenza Token</h3>';
            
            if (refreshResponse.ok) {
                const newToken = refreshResponse.data.data.token;
                results += `
                    <div class="success">
                        ✅ Token refresh riuscito
                        <br>Nuovo token: ${newToken.substring(0, 20)}...
                    </div>
                `;
                authToken = newToken;
            } else {
                results += '<div class="error">❌ Token refresh fallito</div>';
            }

            document.getElementById('auth-results').innerHTML += results;
        }

        // Test 6: CRUD Flow
        async function testProjectCRUDFlow() {
            if (!authToken) {
                log('Test CRUD: autenticazione richiesta', 'warning');
                return;
            }

            log('Test flusso CRUD progetti', 'info');
            
            let results = '<h3>📁 Test CRUD Progetti</h3>';
            let testProjectId = null;

            // CREATE
            const createData = {
                title: 'Test Project ' + Date.now(),
                description: 'Progetto di test creato automaticamente',
                technologies: ['React', 'Laravel', 'MySQL'],
                status: 'completed'
            };

            const createResponse = await makeAPICall('/admin/projects', {
                method: 'POST',
                body: JSON.stringify(createData)
            });

            if (createResponse.ok) {
                testProjectId = createResponse.data.data.id;
                results += `
                    <div class="success">
                        ✅ CREATE: Progetto creato (ID: ${testProjectId})
                    </div>
                `;
            } else {
                results += '<div class="error">❌ CREATE: Fallito</div>';
            }

            // READ
            if (testProjectId) {
                const readResponse = await makeAPICall(`/admin/projects/${testProjectId}`);
                if (readResponse.ok) {
                    results += '<div class="success">✅ READ: Progetto recuperato</div>';
                } else {
                    results += '<div class="error">❌ READ: Fallito</div>';
                }

                // UPDATE
                const updateResponse = await makeAPICall(`/admin/projects/${testProjectId}`, {
                    method: 'PUT',
                    body: JSON.stringify({ title: createData.title + ' (Updated)' })
                });

                if (updateResponse.ok) {
                    results += '<div class="success">✅ UPDATE: Progetto aggiornato</div>';
                } else {
                    results += '<div class="error">❌ UPDATE: Fallito</div>';
                }

                // DELETE
                const deleteResponse = await makeAPICall(`/admin/projects/${testProjectId}`, {
                    method: 'DELETE'
                });

                if (deleteResponse.ok) {
                    results += '<div class="success">✅ DELETE: Progetto eliminato</div>';
                    testResults.crud = true;
                } else {
                    results += '<div class="error">❌ DELETE: Fallito</div>';
                }
            }

            document.getElementById('crud-results').innerHTML = results;
            document.getElementById('crud-results').classList.remove('hidden');
        }

        // Test 7: Form Contatti
        async function testContactFormFlow() {
            log('Test form contatti', 'info');
            
            const contactData = {
                name: 'Test User',
                email: 'test@example.com',
                subject: 'Test messaggio automatico',
                message: 'Questo è un messaggio di test automatico per verificare il funzionamento del sistema di contatti.',
                projectType: 'website'
            };

            const response = await makeAPICall('/contacts', {
                method: 'POST',
                body: JSON.stringify(contactData)
            });

            let results = '<h3>📧 Test Form Contatti</h3>';
            
            if (response.ok) {
                results += `
                    <div class="success">
                        ✅ Messaggio di contatto inviato
                        <br>ID: ${response.data.data.id}
                        <br>Email di conferma dovrebbe essere inviata
                    </div>
                `;
            } else {
                results += `
                    <div class="error">
                        ❌ Invio messaggio fallito
                        <br>Errore: ${response.data.message || 'Errore sconosciuto'}
                    </div>
                `;
            }

            document.getElementById('crud-results').innerHTML += results;
        }

        // Test 8: Upload System
        async function testUploadFlow() {
            if (!authToken) {
                log('Test upload: autenticazione richiesta', 'warning');
                return;
            }

            log('Test sistema upload', 'info');
            
            // Simula test upload (senza file reale)
            let results = '<h3>☁️ Test Upload Sistema</h3>';
            
            // Test endpoint upload
            const uploadTestResponse = await makeAPICall('/admin/upload/image', {
                method: 'POST',
                body: new FormData() // FormData vuoto per test
            });

            if (uploadTestResponse.status === 422) {
                results += `
                    <div class="success">
                        ✅ Endpoint upload raggiungibile
                        <br>Validation working (nessun file fornito)
                    </div>
                `;
            } else {
                results += `
                    <div class="warning">
                        ⚠️ Endpoint upload: status ${uploadTestResponse.status}
                    </div>
                `;
            }

            document.getElementById('crud-results').innerHTML += results;
        }

        // Test 9: Performance API
        async function testAPIPerformance() {
            log('Test performance API', 'info');
            
            let results = '<h3>📈 Test Performance API</h3>';
            const tests = [
                { path: '/test', name: 'Test Endpoint' },
                { path: '/projects', name: 'Lista Progetti' },
                { path: '/technologies', name: 'Tecnologie' }
            ];

            let totalTime = 0;
            let testCount = 0;

            for (const test of tests) {
                const response = await makeAPICall(test.path);
                totalTime += response.responseTime;
                testCount++;

                const performanceClass = response.responseTime < 500 ? 'success' : 
                                       response.responseTime < 1000 ? 'warning' : 'error';
                
                results += `
                    <div class="${performanceClass}">
                        ${test.name}: ${response.responseTime}ms
                    </div>
                `;
            }

            const avgTime = Math.round(totalTime / testCount);
            const overallClass = avgTime < 500 ? 'success' : avgTime < 1000 ? 'warning' : 'error';

            results += `
                <div class="${overallClass}">
                    <strong>Tempo medio: ${avgTime}ms</strong>
                </div>
            `;

            document.getElementById('performance-results').innerHTML = results;
            document.getElementById('performance-results').classList.remove('hidden');
        }

        // Test 10: Caricamento Frontend
        async function testFrontendLoading() {
            log('Test caricamento frontend', 'info');
            
            const startTime = performance.now();
            
            try {
                const response = await fetch(FRONTEND_URL);
                const endTime = performance.now();
                const loadTime = Math.round(endTime - startTime);
                
                let results = '<h3>🎨 Test Caricamento Frontend</h3>';
                
                const performanceClass = loadTime < 1000 ? 'success' : 
                                       loadTime < 2000 ? 'warning' : 'error';
                
                results += `
                    <div class="${performanceClass}">
                        Tempo caricamento: ${loadTime}ms
                    </div>
                    <div class="info">
                        Status: ${response.status} ${response.statusText}
                    </div>
                `;

                document.getElementById('performance-results').innerHTML += results;
                
            } catch (error) {
                document.getElementById('performance-results').innerHTML += `
                    <div class="error">❌ Errore caricamento frontend: ${error.message}</div>
                `;
            }
        }

        // Test 11: Simulazione Produzione Completa
        async function runProductionSimulation() {
            log('INIZIO SIMULAZIONE PRODUZIONE COMPLETA', 'info');
            
            let results = '<h3>🚀 Simulazione Produzione Completa</h3>';
            let score = 0;
            let totalTests = 0;

            // Test 1: Sistema base
            results += '<h4>1. Test Sistema Base</h4>';
            await testSystemStatus();
            if (testResults.frontend && testResults.backend) {
                score += 2;
                results += '<div class="success">✅ Sistema base: OK (+2 punti)</div>';
            } else {
                results += '<div class="error">❌ Sistema base: FAIL</div>';
            }
            totalTests += 2;

            // Test 2: API Connectivity
            results += '<h4>2. Test API</h4>';
            const apiTest = await makeAPICall('/test');
            if (apiTest.ok) {
                score += 1;
                results += '<div class="success">✅ API connectivity: OK (+1 punto)</div>';
            } else {
                results += '<div class="error">❌ API connectivity: FAIL</div>';
            }
            totalTests += 1;

            // Test 3: Performance
            results += '<h4>3. Test Performance</h4>';
            const perfTest = await makeAPICall('/projects');
            if (perfTest.ok && perfTest.responseTime < 1000) {
                score += 1;
                results += '<div class="success">✅ Performance: OK (+1 punto)</div>';
            } else {
                results += '<div class="warning">⚠️ Performance: Accettabile</div>';
            }
            totalTests += 1;

            // Test 4: Sicurezza
            results += '<h4>4. Test Sicurezza</h4>';
            const securityTest = await makeAPICall('/admin/projects');
            if (securityTest.status === 401) {
                score += 1;
                results += '<div class="success">✅ Sicurezza: Endpoint protetti (+1 punto)</div>';
            } else {
                results += '<div class="error">❌ Sicurezza: Problemi protezione</div>';
            }
            totalTests += 1;

            // Calcolo punteggio finale
            const percentage = Math.round((score / totalTests) * 100);
            const finalClass = percentage >= 80 ? 'success' : percentage >= 60 ? 'warning' : 'error';
            const finalStatus = percentage >= 80 ? '🟢 PRONTO PER PRODUZIONE' : 
                               percentage >= 60 ? '🟡 DEPLOY CON ATTENZIONE' : 
                               '🔴 NON PRONTO - CORREGGERE ERRORI';

            results += `
                <hr>
                <div class="${finalClass}">
                    <h3>📊 RISULTATO FINALE</h3>
                    <strong>Punteggio: ${score}/${totalTests} (${percentage}%)</strong><br>
                    <strong>Status: ${finalStatus}</strong>
                </div>
            `;

            if (percentage >= 80) {
                results += `
                    <div class="success">
                        🎉 <strong>DEPLOY APPROVATO!</strong><br>
                        Il sistema è pronto per andare in produzione su vincenzorocca.com
                    </div>
                `;
            } else {
                results += `
                    <div class="error">
                        ⚠️ <strong>DEPLOY NON RACCOMANDATO</strong><br>
                        Correggere i problemi rilevati prima del deploy
                    </div>
                `;
            }

            document.getElementById('simulation-results').innerHTML = results;
            document.getElementById('simulation-results').classList.remove('hidden');
            
            log(`SIMULAZIONE COMPLETATA - Score: ${percentage}%`, percentage >= 80 ? 'success' : 'warning');
        }

        // Auto-start alcuni test al caricamento
        window.addEventListener('load', function() {
            log('Sistema di test inizializzato', 'success');
            log('Configurazione API: ' + API_BASE, 'info');
            log('Frontend URL: ' + FRONTEND_URL, 'info');
        });
    </script>
</body>
</html> 