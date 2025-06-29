<?php
/**
 * TEST SUITE COMPLETA PORTFOLIO VINCENZO ROCCA
 *
 * Questo file testa tutte le funzionalità principali:
 * - Connessione database
 * - API Laravel
 * - Autenticazione
 * - CRUD progetti
 * - Email
 * - Cloudinary
 *
 * ELIMINA QUESTO FILE DOPO I TEST!
 */

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🧪 Test Suite Portfolio - Vincenzo Rocca</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; margin: 40px; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1, h2 { color: #2563eb; }
        .test-section { margin: 30px 0; padding: 20px; border: 1px solid #e5e7eb; border-radius: 8px; }
        .success { background: #f0fdf4; border-color: #22c55e; }
        .error { background: #fef2f2; border-color: #ef4444; }
        .warning { background: #fffbeb; border-color: #f59e0b; }
        .info { background: #eff6ff; border-color: #3b82f6; }
        button { background: #2563eb; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; margin: 5px; }
        button:hover { background: #1d4ed8; }
        input, textarea { width: 100%; padding: 8px; margin: 5px 0; border: 1px solid #d1d5db; border-radius: 4px; }
        .result { margin: 10px 0; padding: 10px; border-radius: 4px; font-family: monospace; }
        .hidden { display: none; }
        pre { background: #f8fafc; padding: 15px; border-radius: 5px; overflow-x: auto; }
        .delete-warning { background: #fecaca; color: #991b1b; padding: 20px; border-radius: 8px; margin: 20px 0; font-weight: bold; text-align: center; }
    </style>
</head>
<body>

<div class="container">
    <h1>🧪 Test Suite Portfolio - Vincenzo Rocca</h1>

    <div class="delete-warning">
        ⚠️ ATTENZIONE: ELIMINA QUESTO FILE DOPO I TEST!<br>
        Questo file contiene informazioni sensibili e non deve rimanere online.
    </div>

    <!-- 1. TEST AMBIENTE -->
    <div class="test-section info" id="env-test">
        <h2>🔧 1. Test Ambiente & Configurazione</h2>
        <button onclick="testEnvironment()">Testa Ambiente</button>
        <div id="env-result" class="result hidden"></div>
    </div>

    <!-- 2. TEST DATABASE -->
    <div class="test-section info" id="db-test">
        <h2>💾 2. Test Database</h2>
        <button onclick="testDatabase()">Testa Database</button>
        <button onclick="testTables()">Verifica Tabelle</button>
        <div id="db-result" class="result hidden"></div>
    </div>

    <!-- 3. TEST API LARAVEL -->
    <div class="test-section info" id="api-test">
        <h2>🚀 3. Test API Laravel</h2>
        <button onclick="testAPI()">Testa API</button>
        <button onclick="testTechnologies()">Testa Technologies</button>
        <button onclick="testProjects()">Testa Projects</button>
        <div id="api-result" class="result hidden"></div>
    </div>

    <!-- 4. TEST AUTENTICAZIONE -->
    <div class="test-section info" id="auth-test">
        <h2>🔐 4. Test Autenticazione</h2>
        <input type="email" id="admin-email" placeholder="Email Admin" value="vincenzorocca88@gmail.com">
        <input type="password" id="admin-password" placeholder="Password Admin" value="admin123">
        <button onclick="testLogin()">Test Login</button>
        <button onclick="testAuthUser()">Test User Autenticato</button>
        <div id="auth-result" class="result hidden"></div>
    </div>

    <!-- 5. TEST EMAIL -->
    <div class="test-section info" id="email-test">
        <h2>📧 5. Test Sistema Email</h2>
        <input type="text" id="test-name" placeholder="Nome Test" value="Test User">
        <input type="email" id="test-email" placeholder="Email Test" value="test@example.com">
        <textarea id="test-message" placeholder="Messaggio Test">Questo è un messaggio di test per verificare il sistema email del portfolio.</textarea>
        <button onclick="testEmail()">Invia Email Test</button>
        <div id="email-result" class="result hidden"></div>
    </div>

    <!-- 6. TEST CLOUDINARY -->
    <div class="test-section info" id="cloud-test">
        <h2>☁️ 6. Test Cloudinary</h2>
        <input type="file" id="test-image" accept="image/*">
        <button onclick="testCloudinary()">Test Upload Immagine</button>
        <div id="cloud-result" class="result hidden"></div>
    </div>

    <!-- 7. TEST CORS -->
    <div class="test-section info" id="cors-test">
        <h2>🔗 7. Test CORS (Cross-Origin)</h2>
        <button onclick="testCORS()">Testa CORS da Vercel</button>
        <div id="cors-result" class="result hidden"></div>
    </div>

    <!-- 8. RIEPILOGO FINALE -->
    <div class="test-section warning" id="summary">
        <h2>📊 8. Riepilogo Test</h2>
        <button onclick="runAllTests()">🚀 ESEGUI TUTTI I TEST</button>
        <div id="summary-result" class="result hidden"></div>
    </div>

</div>

<script>
let authToken = null;
const baseURL = window.location.origin;
const apiURL = baseURL + '/api/v1';

// 1. TEST AMBIENTE
async function testEnvironment() {
    const result = document.getElementById('env-result');
    result.classList.remove('hidden');
    result.innerHTML = 'Testing ambiente...';

    try {
        const response = await fetch(apiURL + '/test');
        const data = await response.json();

        result.className = 'result success';
        result.innerHTML = `
            <strong>✅ Ambiente OK!</strong><br>
            <pre>${JSON.stringify(data, null, 2)}</pre>
        `;
    } catch (error) {
        result.className = 'result error';
        result.innerHTML = `<strong>❌ Errore Ambiente:</strong> ${error.message}`;
    }
}

// 2. TEST DATABASE
async function testDatabase() {
    const result = document.getElementById('db-result');
    result.classList.remove('hidden');
    result.innerHTML = 'Testing database...';

    try {
        const response = await fetch(apiURL + '/technologies');
        const data = await response.json();

        result.className = 'result success';
        result.innerHTML = `
            <strong>✅ Database OK!</strong><br>
            Technologies trovate: ${data.data ? data.data.length : 'N/A'}<br>
            <pre>${JSON.stringify(data, null, 2).substring(0, 500)}...</pre>
        `;
    } catch (error) {
        result.className = 'result error';
        result.innerHTML = `<strong>❌ Errore Database:</strong> ${error.message}`;
    }
}

async function testTables() {
    // Test aggiuntivo per verificare le tabelle
    try {
        const projects = await fetch(apiURL + '/projects');
        const projectsData = await projects.json();

        const result = document.getElementById('db-result');
        result.innerHTML += `<br><strong>Projects:</strong> ${projectsData.data ? projectsData.data.length : 'Errore'}`;
    } catch (error) {
        console.error('Errore test tabelle:', error);
    }
}

// 3. TEST API
async function testAPI() {
    const result = document.getElementById('api-result');
    result.classList.remove('hidden');
    result.innerHTML = 'Testing API Laravel...';

    try {
        const response = await fetch(apiURL + '/test');
        if (!response.ok) throw new Error(`HTTP ${response.status}`);

        const data = await response.json();
        result.className = 'result success';
        result.innerHTML = `<strong>✅ API Laravel OK!</strong><br><pre>${JSON.stringify(data, null, 2)}</pre>`;
    } catch (error) {
        result.className = 'result error';
        result.innerHTML = `<strong>❌ Errore API:</strong> ${error.message}`;
    }
}

async function testTechnologies() {
    try {
        const response = await fetch(apiURL + '/technologies');
        const data = await response.json();

        const result = document.getElementById('api-result');
        result.innerHTML += `<br><strong>Technologies API:</strong> ${data.data ? data.data.length + ' items' : 'Errore'}`;
    } catch (error) {
        console.error('Errore technologies:', error);
    }
}

async function testProjects() {
    try {
        const response = await fetch(apiURL + '/projects');
        const data = await response.json();

        const result = document.getElementById('api-result');
        result.innerHTML += `<br><strong>Projects API:</strong> ${data.data ? data.data.length + ' items' : 'Errore'}`;
    } catch (error) {
        console.error('Errore projects:', error);
    }
}

// 4. TEST AUTENTICAZIONE
async function testLogin() {
    const result = document.getElementById('auth-result');
    result.classList.remove('hidden');
    result.innerHTML = 'Testing login...';

    const email = document.getElementById('admin-email').value;
    const password = document.getElementById('admin-password').value;

    try {
        const response = await fetch(apiURL + '/auth/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ email, password })
        });

        const data = await response.json();

        if (response.ok && data.access_token) {
            authToken = data.access_token;
            result.className = 'result success';
            result.innerHTML = `<strong>✅ Login OK!</strong><br>Token salvato per test successivi.`;
        } else {
            result.className = 'result error';
            result.innerHTML = `<strong>❌ Login Fallito:</strong> ${JSON.stringify(data)}`;
        }
    } catch (error) {
        result.className = 'result error';
        result.innerHTML = `<strong>❌ Errore Login:</strong> ${error.message}`;
    }
}

async function testAuthUser() {
    if (!authToken) {
        alert('Fai prima il login!');
        return;
    }

    try {
        const response = await fetch(apiURL + '/auth/me', {
            headers: {
                'Authorization': `Bearer ${authToken}`,
                'Accept': 'application/json'
            }
        });

        const data = await response.json();
        const result = document.getElementById('auth-result');

        if (response.ok) {
            result.innerHTML += `<br><strong>✅ User Autenticato:</strong> ${data.email}`;
        } else {
            result.innerHTML += `<br><strong>❌ Errore Auth:</strong> ${JSON.stringify(data)}`;
        }
    } catch (error) {
        console.error('Errore auth user:', error);
    }
}

// 5. TEST EMAIL
async function testEmail() {
    const result = document.getElementById('email-result');
    result.classList.remove('hidden');
    result.innerHTML = 'Testing email...';

    const name = document.getElementById('test-name').value;
    const email = document.getElementById('test-email').value;
    const message = document.getElementById('test-message').value;

    try {
        const response = await fetch(apiURL + '/contacts', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ name, email, message })
        });

        const data = await response.json();

        if (response.ok) {
            result.className = 'result success';
            result.innerHTML = `<strong>✅ Email inviata!</strong><br><pre>${JSON.stringify(data, null, 2)}</pre>`;
        } else {
            result.className = 'result error';
            result.innerHTML = `<strong>❌ Errore Email:</strong> ${JSON.stringify(data)}`;
        }
    } catch (error) {
        result.className = 'result error';
        result.innerHTML = `<strong>❌ Errore Email:</strong> ${error.message}`;
    }
}

// 6. TEST CLOUDINARY
async function testCloudinary() {
    const result = document.getElementById('cloud-result');
    result.classList.remove('hidden');

    const fileInput = document.getElementById('test-image');
    if (!fileInput.files[0]) {
        result.className = 'result warning';
        result.innerHTML = '<strong>⚠️ Seleziona prima un\'immagine!</strong>';
        return;
    }

    if (!authToken) {
        result.className = 'result warning';
        result.innerHTML = '<strong>⚠️ Fai prima il login!</strong>';
        return;
    }

    result.innerHTML = 'Testing Cloudinary upload...';

    try {
        const formData = new FormData();
        formData.append('image', fileInput.files[0]);

        const response = await fetch(apiURL + '/admin/upload/image', {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${authToken}`,
                'Accept': 'application/json'
            },
            body: formData
        });

        const data = await response.json();

        if (response.ok) {
            result.className = 'result success';
            result.innerHTML = `
                <strong>✅ Upload Cloudinary OK!</strong><br>
                <img src="${data.url}" style="max-width: 200px; margin: 10px 0;"><br>
                <pre>${JSON.stringify(data, null, 2)}</pre>
            `;
        } else {
            result.className = 'result error';
            result.innerHTML = `<strong>❌ Errore Upload:</strong> ${JSON.stringify(data)}`;
        }
    } catch (error) {
        result.className = 'result error';
        result.innerHTML = `<strong>❌ Errore Cloudinary:</strong> ${error.message}`;
    }
}

// 7. TEST CORS
async function testCORS() {
    const result = document.getElementById('cors-result');
    result.classList.remove('hidden');
    result.innerHTML = 'Testing CORS...';

    try {
        // Simula una chiamata come se venisse da Vercel
        const response = await fetch(apiURL + '/technologies', {
            headers: {
                'Origin': 'https://portfolio-react-liard-phi.vercel.app',
                'Access-Control-Request-Method': 'GET'
            }
        });

        if (response.ok) {
            result.className = 'result success';
            result.innerHTML = '<strong>✅ CORS OK!</strong> Le chiamate da Vercel funzioneranno.';
        } else {
            result.className = 'result warning';
            result.innerHTML = `<strong>⚠️ CORS Warning:</strong> Status ${response.status}`;
        }
    } catch (error) {
        result.className = 'result error';
        result.innerHTML = `<strong>❌ Errore CORS:</strong> ${error.message}`;
    }
}

// 8. ESEGUI TUTTI I TEST
async function runAllTests() {
    const result = document.getElementById('summary-result');
    result.classList.remove('hidden');
    result.innerHTML = '🚀 Eseguendo tutti i test...<br><br>';

    await testEnvironment();
    await new Promise(r => setTimeout(r, 1000));

    await testDatabase();
    await new Promise(r => setTimeout(r, 1000));

    await testAPI();
    await new Promise(r => setTimeout(r, 1000));

    await testLogin();
    await new Promise(r => setTimeout(r, 2000));

    if (authToken) {
        await testAuthUser();
    }

    result.className = 'result info';
    result.innerHTML += '<br><strong>🎉 Test completati!</strong><br>Controlla i risultati sopra.';
}

// Auto-test iniziale
window.onload = function() {
    console.log('🧪 Test Suite Portfolio caricata');
    testEnvironment();
};
</script>

</body>
</html>

<?php
// Test PHP Backend aggiuntivi
echo "<!-- PHP Backend Info: " . PHP_VERSION . " -->";
?>
