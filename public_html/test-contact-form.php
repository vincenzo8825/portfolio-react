<?php
header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Contact Form - Portfolio Vincenzo Rocca</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }
        
        .content {
            padding: 30px;
        }
        
        .test-section {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
            border-left: 5px solid #667eea;
        }
        
        .test-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .test-header h3 {
            font-size: 1.5rem;
            color: #333;
            margin-left: 10px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #555;
        }
        
        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        
        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-right: 10px;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }
        
        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }
        
        .result {
            margin-top: 20px;
            padding: 15px;
            border-radius: 10px;
            font-weight: 500;
        }
        
        .result.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .result.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .result.info {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }
        
        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }
        
        @media (max-width: 768px) {
            .grid {
                grid-template-columns: 1fr;
            }
            
            .header h1 {
                font-size: 2rem;
            }
        }
        
        .required {
            color: #e74c3c;
        }
        
        .validation-info {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
        }
        
        .validation-info h4 {
            color: #856404;
            margin-bottom: 10px;
        }
        
        .validation-info ul {
            color: #856404;
            margin-left: 20px;
        }
        
        .validation-info li {
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🧪 Test Contact Form</h1>
            <p>Test completo del form di contatto con validazioni e invio email</p>
        </div>
        
        <div class="content">
            
            <!-- INFORMAZIONI VALIDAZIONI -->
            <div class="validation-info">
                <h4>📋 Regole di Validazione</h4>
                <ul>
                    <li><strong>Nome:</strong> Obbligatorio, min 2 caratteri, max 255 caratteri</li>
                    <li><strong>Email:</strong> Obbligatoria, formato valido, max 255 caratteri</li>
                    <li><strong>Messaggio:</strong> Obbligatorio, min 10 caratteri, max 5000 caratteri</li>
                    <li><strong>Oggetto:</strong> Opzionale, max 255 caratteri</li>
                    <li><strong>Budget, Timeline, Tipo Progetto:</strong> Opzionali</li>
                </ul>
            </div>

            <!-- TEST 1: FORM VALIDO -->
            <div class="test-section">
                <div class="test-header">
                    <span style="font-size: 1.5rem;">✅</span>
                    <h3>Test Form Valido</h3>
                </div>
                <form id="validForm">
                    <div class="grid">
                        <div class="form-group">
                            <label>Nome <span class="required">*</span></label>
                            <input type="text" id="validName" value="Mario Rossi" required>
                        </div>
                        <div class="form-group">
                            <label>Email <span class="required">*</span></label>
                            <input type="email" id="validEmail" value="mario.rossi@example.com" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Oggetto</label>
                        <input type="text" id="validSubject" value="Richiesta preventivo sito web">
                    </div>
                    <div class="form-group">
                        <label>Messaggio <span class="required">*</span></label>
                        <textarea id="validMessage" rows="4" required>Salve, sono interessato a sviluppare un sito web per la mia azienda. Potreste fornirmi un preventivo dettagliato? Grazie.</textarea>
                    </div>
                    <div class="grid">
                        <div class="form-group">
                            <label>Budget</label>
                            <select id="validBudget">
                                <option value="">Seleziona budget</option>
                                <option value="1k-5k" selected>€1.000 - €5.000</option>
                                <option value="5k-10k">€5.000 - €10.000</option>
                                <option value="10k-25k">€10.000 - €25.000</option>
                                <option value="25k+">€25.000+</option>
                                <option value="discuss">Da discutere</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Timeline</label>
                            <select id="validTimeline">
                                <option value="">Seleziona timeline</option>
                                <option value="1-2-weeks">1-2 settimane</option>
                                <option value="1-month" selected>1 mese</option>
                                <option value="2-3-months">2-3 mesi</option>
                                <option value="3-6-months">3-6 mesi</option>
                                <option value="6-months+">6+ mesi</option>
                                <option value="flexible">Flessibile</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Tipo di Progetto</label>
                        <select id="validProjectType">
                            <option value="">Seleziona tipo</option>
                            <option value="e-commerce">E-Commerce</option>
                            <option value="web-app" selected>Web Application</option>
                            <option value="landing-page">Landing Page</option>
                            <option value="portfolio">Portfolio</option>
                            <option value="blog">Blog/CMS</option>
                            <option value="api">API Development</option>
                            <option value="mobile">Mobile App</option>
                            <option value="other">Altro</option>
                        </select>
                    </div>
                    <button type="button" class="btn" onclick="testValidForm()">✅ Invia Form Valido</button>
                </form>
                <div id="validResult"></div>
            </div>

            <!-- TEST 2: FORM CON ERRORI -->
            <div class="test-section">
                <div class="test-header">
                    <span style="font-size: 1.5rem;">❌</span>
                    <h3>Test Validazione Errori</h3>
                </div>
                <form id="invalidForm">
                    <div class="grid">
                        <div class="form-group">
                            <label>Nome <span class="required">*</span> (vuoto)</label>
                            <input type="text" id="invalidName" value="" required>
                        </div>
                        <div class="form-group">
                            <label>Email <span class="required">*</span> (formato non valido)</label>
                            <input type="email" id="invalidEmail" value="email-non-valida" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Oggetto (troppo lungo)</label>
                        <input type="text" id="invalidSubject" value="Questo è un oggetto molto lungo che supera i 255 caratteri consentiti per testare la validazione. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.">
                    </div>
                    <div class="form-group">
                        <label>Messaggio <span class="required">*</span> (troppo corto)</label>
                        <textarea id="invalidMessage" rows="4" required>Ciao</textarea>
                    </div>
                    <button type="button" class="btn" onclick="testInvalidForm()">❌ Test Errori Validazione</button>
                </form>
                <div id="invalidResult"></div>
            </div>

            <!-- TEST 3: FORM SOLO CAMPI OBBLIGATORI -->
            <div class="test-section">
                <div class="test-header">
                    <span style="font-size: 1.5rem;">📝</span>
                    <h3>Test Solo Campi Obbligatori</h3>
                </div>
                <form id="minimalForm">
                    <div class="grid">
                        <div class="form-group">
                            <label>Nome <span class="required">*</span></label>
                            <input type="text" id="minimalName" value="Giulia Bianchi" required>
                        </div>
                        <div class="form-group">
                            <label>Email <span class="required">*</span></label>
                            <input type="email" id="minimalEmail" value="giulia.bianchi@test.com" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Messaggio <span class="required">*</span></label>
                        <textarea id="minimalMessage" rows="4" required>Messaggio di test con solo i campi obbligatori compilati per verificare il corretto funzionamento.</textarea>
                    </div>
                    <button type="button" class="btn" onclick="testMinimalForm()">📝 Invia Solo Obbligatori</button>
                </form>
                <div id="minimalResult"></div>
            </div>

        </div>
    </div>

    <script>
        const API_BASE = '/api/v1';

        async function sendContactMessage(data) {
            try {
                const response = await fetch(`${API_BASE}/contacts`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();
                
                if (!response.ok) {
                    throw new Error(result.message || `HTTP ${response.status}`);
                }

                return result;
            } catch (error) {
                console.error('API Error:', error);
                throw error;
            }
        }

        function showResult(elementId, message, type = 'info') {
            const element = document.getElementById(elementId);
            element.innerHTML = `<div class="result ${type}">${message}</div>`;
        }

        async function testValidForm() {
            const btn = event.target;
            btn.disabled = true;
            btn.textContent = '⏳ Invio in corso...';

            try {
                const data = {
                    name: document.getElementById('validName').value,
                    email: document.getElementById('validEmail').value,
                    subject: document.getElementById('validSubject').value,
                    message: document.getElementById('validMessage').value,
                    budget: document.getElementById('validBudget').value,
                    timeline: document.getElementById('validTimeline').value,
                    projectType: document.getElementById('validProjectType').value
                };

                console.log('Sending data:', data);
                const result = await sendContactMessage(data);
                
                showResult('validResult', `
                    <strong>✅ SUCCESS!</strong><br>
                    ${result.message}<br>
                    <small>Controlla la tua email per la conferma di ricezione.</small>
                `, 'success');

            } catch (error) {
                showResult('validResult', `
                    <strong>❌ ERRORE!</strong><br>
                    ${error.message}
                `, 'error');
            } finally {
                btn.disabled = false;
                btn.textContent = '✅ Invia Form Valido';
            }
        }

        async function testInvalidForm() {
            const btn = event.target;
            btn.disabled = true;
            btn.textContent = '⏳ Test in corso...';

            try {
                const data = {
                    name: document.getElementById('invalidName').value,
                    email: document.getElementById('invalidEmail').value,
                    subject: document.getElementById('invalidSubject').value,
                    message: document.getElementById('invalidMessage').value,
                    budget: '',
                    timeline: '',
                    projectType: ''
                };

                console.log('Sending invalid data:', data);
                const result = await sendContactMessage(data);
                
                // Se arriviamo qui, c'è un problema con le validazioni
                showResult('invalidResult', `
                    <strong>⚠️ PROBLEMA!</strong><br>
                    Le validazioni non hanno funzionato come previsto.<br>
                    Risposta: ${result.message}
                `, 'error');

            } catch (error) {
                // Questo è il comportamento atteso
                showResult('invalidResult', `
                    <strong>✅ VALIDAZIONI OK!</strong><br>
                    Gli errori sono stati correttamente rilevati:<br>
                    <em>${error.message}</em>
                `, 'success');
            } finally {
                btn.disabled = false;
                btn.textContent = '❌ Test Errori Validazione';
            }
        }

        async function testMinimalForm() {
            const btn = event.target;
            btn.disabled = true;
            btn.textContent = '⏳ Invio in corso...';

            try {
                const data = {
                    name: document.getElementById('minimalName').value,
                    email: document.getElementById('minimalEmail').value,
                    subject: '', // Vuoto
                    message: document.getElementById('minimalMessage').value,
                    budget: '', // Vuoto
                    timeline: '', // Vuoto
                    projectType: '' // Vuoto
                };

                console.log('Sending minimal data:', data);
                const result = await sendContactMessage(data);
                
                showResult('minimalResult', `
                    <strong>✅ SUCCESS!</strong><br>
                    ${result.message}<br>
                    <small>Form inviato con successo anche con campi opzionali vuoti.</small>
                `, 'success');

            } catch (error) {
                showResult('minimalResult', `
                    <strong>❌ ERRORE!</strong><br>
                    ${error.message}
                `, 'error');
            } finally {
                btn.disabled = false;
                btn.textContent = '📝 Invia Solo Obbligatori';
            }
        }
    </script>
</body>
</html> 