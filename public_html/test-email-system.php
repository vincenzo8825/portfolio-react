<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>📧 Test Email System - Portfolio</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%); min-height: 100vh; padding: 20px; }
        .container { max-width: 1000px; margin: 0 auto; }
        
        .header { background: rgba(255,255,255,0.1); backdrop-filter: blur(10px); color: white; padding: 30px; border-radius: 20px; text-align: center; margin-bottom: 30px; }
        .header h1 { font-size: 2.5rem; margin-bottom: 10px; }
        
        .card { background: white; border-radius: 20px; padding: 30px; margin-bottom: 20px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); }
        .card-header { font-size: 1.5rem; font-weight: 600; color: #374151; margin-bottom: 20px; display: flex; align-items: center; gap: 12px; }
        
        .btn { padding: 12px 24px; border: none; border-radius: 12px; cursor: pointer; font-weight: 600; transition: all 0.3s; margin-right: 12px; margin-bottom: 12px; }
        .btn-primary { background: #3b82f6; color: white; }
        .btn-success { background: #10b981; color: white; }
        .btn-danger { background: #ef4444; color: white; }
        .btn-warning { background: #f59e0b; color: white; }
        
        .status { padding: 16px; border-radius: 12px; margin: 16px 0; font-weight: 500; }
        .status-success { background: #dcfce7; color: #16a34a; border: 1px solid #bbf7d0; }
        .status-error { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }
        .status-info { background: #dbeafe; color: #2563eb; border: 1px solid #bfdbfe; }
        
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 500; color: #374151; }
        .form-group input, .form-group textarea { width: 100%; padding: 12px; border: 2px solid #e5e7eb; border-radius: 12px; font-size: 16px; }
        .form-group textarea { min-height: 120px; resize: vertical; }
        
        pre { background: #1f2937; color: #e5e7eb; padding: 16px; border-radius: 8px; overflow-x: auto; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📧 Test Email System</h1>
            <p>Diagnostica e test del sistema di invio email Hostinger</p>
        </div>

        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            
            if ($action === 'test_mail') {
                echo '<div class="card"><div class="card-header">📧 Test Funzione mail() PHP</div>';
                
                $to = 'vincenzorocca88@gmail.com';
                $subject = '[TEST] Email di test dal portfolio - ' . date('Y-m-d H:i:s');
                $message = "Questo è un test email inviato dal portfolio.\n\n";
                $message .= "Timestamp: " . date('Y-m-d H:i:s') . "\n";
                $message .= "Server: " . $_SERVER['HTTP_HOST'] . "\n";
                $message .= "IP Server: " . $_SERVER['SERVER_ADDR'] . "\n";
                $message .= "PHP Version: " . phpversion() . "\n\n";
                $message .= "Se ricevi questa email, il sistema funziona correttamente!";
                
                $headers = 'From: Portfolio Test <noreply@vincenzorocca.com>' . "\r\n" .
                          'Reply-To: vincenzorocca88@gmail.com' . "\r\n" .
                          'X-Mailer: PHP/' . phpversion() . "\r\n" .
                          'Content-Type: text/plain; charset=UTF-8';
                
                $result = @mail($to, $subject, $message, $headers);
                
                if ($result) {
                    echo '<div class="status status-success">✅ Email inviata con successo!</div>';
                    echo '<p><strong>Destinatario:</strong> ' . $to . '</p>';
                    echo '<p><strong>Oggetto:</strong> ' . $subject . '</p>';
                    echo '<p><strong>Orario invio:</strong> ' . date('Y-m-d H:i:s') . '</p>';
                } else {
                    echo '<div class="status status-error">❌ Errore invio email con mail()</div>';
                    $error = error_get_last();
                    if ($error) {
                        echo '<p><strong>Errore:</strong> ' . $error['message'] . '</p>';
                    }
                }
                echo '</div>';
            }
            
            if ($action === 'test_contact') {
                echo '<div class="card"><div class="card-header">📧 Test Invio Contatto</div>';
                
                $name = $_POST['name'] ?? '';
                $email = $_POST['email'] ?? '';
                $subject = $_POST['subject'] ?? '';
                $message = $_POST['message'] ?? '';
                
                if (empty($name) || empty($email) || empty($message)) {
                    echo '<div class="status status-error">❌ Compila tutti i campi obbligatori</div>';
                } else {
                    // Database connection
                    $host = 'localhost';
                    $dbname = 'u336414084_portfolioVince';
                    $username = 'u336414084_vincenzorocca8';
                    $password = 'Ciaociao52.?';

                    try {
                        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        
                        // Save to database
                        $stmt = $pdo->prepare("INSERT INTO contacts (name, email, subject, message, status, ip_address, created_at, updated_at) VALUES (?, ?, ?, ?, 'new', ?, NOW(), NOW())");
                        $stmt->execute([$name, $email, $subject, $message, $_SERVER['REMOTE_ADDR']]);
                        $contact_id = $pdo->lastInsertId();
                        
                        echo '<div class="status status-success">✅ Contatto salvato nel database (ID: ' . $contact_id . ')</div>';
                        
                        // Send email
                        $to = 'vincenzorocca88@gmail.com';
                        $email_subject = '[PORTFOLIO] ' . $subject;
                        $email_message = "Nuovo messaggio dal portfolio:\n\n";
                        $email_message .= "Nome: " . $name . "\n";
                        $email_message .= "Email: " . $email . "\n";
                        $email_message .= "ID Contatto: " . $contact_id . "\n";
                        $email_message .= "Timestamp: " . date('Y-m-d H:i:s') . "\n\n";
                        $email_message .= "Messaggio:\n" . $message;
                        
                        $headers = 'From: Portfolio <noreply@vincenzorocca.com>' . "\r\n" .
                                  'Reply-To: ' . $email . "\r\n" .
                                  'X-Mailer: PHP/' . phpversion() . "\r\n" .
                                  'Content-Type: text/plain; charset=UTF-8';
                        
                        $email_result = @mail($to, $email_subject, $email_message, $headers);
                        
                        if ($email_result) {
                            echo '<div class="status status-success">✅ Email di notifica inviata!</div>';
                        } else {
                            echo '<div class="status status-error">❌ Errore invio email di notifica</div>';
                        }
                        
                    } catch(Exception $e) {
                        echo '<div class="status status-error">❌ Errore database: ' . $e->getMessage() . '</div>';
                    }
                }
                echo '</div>';
            }
        }
        ?>

        <!-- Test Funzione mail() -->
        <div class="card">
            <div class="card-header">🧪 Test Funzione mail() PHP</div>
            <p>Testa se il server Hostinger permette l'invio email con la funzione mail() nativa di PHP.</p>
            
            <form method="POST">
                <input type="hidden" name="action" value="test_mail">
                <button type="submit" class="btn btn-primary">📧 Invia Email di Test</button>
            </form>
        </div>

        <!-- Test Form Contatti -->
        <div class="card">
            <div class="card-header">📝 Test Form Contatti Completo</div>
            
            <form method="POST">
                <input type="hidden" name="action" value="test_contact">
                
                <div class="form-group">
                    <label>Nome *:</label>
                    <input type="text" name="name" value="Mario Rossi" required>
                </div>
                
                <div class="form-group">
                    <label>Email *:</label>
                    <input type="email" name="email" value="mario.rossi@example.com" required>
                </div>
                
                <div class="form-group">
                    <label>Oggetto:</label>
                    <input type="text" name="subject" value="Test invio contatto dal portfolio">
                </div>
                
                <div class="form-group">
                    <label>Messaggio *:</label>
                    <textarea name="message" required>Questo è un messaggio di test inviato dal form contatti del portfolio.

Timestamp: <?= date('Y-m-d H:i:s') ?>
Test di funzionalità email.</textarea>
                </div>
                
                <button type="submit" class="btn btn-success">📧 Invia Test Contatto</button>
            </form>
        </div>

        <!-- Configurazione Server -->
        <div class="card">
            <div class="card-header">🔧 Configurazione Server Email</div>
            
            <h4>Informazioni PHP:</h4>
            <pre><?php
                echo "PHP Version: " . phpversion() . "\n";
                echo "Mail Function: " . (function_exists('mail') ? 'DISPONIBILE' : 'NON DISPONIBILE') . "\n";
                echo "Sendmail Path: " . ini_get('sendmail_path') . "\n";
                echo "SMTP: " . ini_get('SMTP') . "\n";
                echo "SMTP Port: " . ini_get('smtp_port') . "\n";
                echo "From: " . ini_get('sendmail_from') . "\n\n";
                
                echo "Server Info:\n";
                echo "HTTP_HOST: " . $_SERVER['HTTP_HOST'] . "\n";
                echo "SERVER_NAME: " . $_SERVER['SERVER_NAME'] . "\n";
                echo "SERVER_ADDR: " . ($_SERVER['SERVER_ADDR'] ?? 'N/A') . "\n";
                echo "REMOTE_ADDR: " . $_SERVER['REMOTE_ADDR'] . "\n";
            ?></pre>
            
            <h4>Test Configurazione:</h4>
            <button onclick="testEmailConfig()" class="btn btn-warning">🔍 Verifica Configurazione</button>
            <button onclick="checkMailLog()" class="btn btn-info">📋 Check Mail Log</button>
            
            <div id="config-results"></div>
        </div>

        <!-- Log Risultati -->
        <div class="card">
            <div class="card-header">📋 Log Test Email</div>
            <pre id="email-log">Sistema email test inizializzato
Timestamp: <?= date('Y-m-d H:i:s') ?>
========================</pre>
            
            <button onclick="clearLog()" class="btn btn-danger">🗑️ Pulisci Log</button>
        </div>
    </div>

    <script>
        function log(message) {
            const logElement = document.getElementById('email-log');
            const timestamp = new Date().toLocaleTimeString();
            logElement.innerHTML += `\n[${timestamp}] ${message}`;
            logElement.scrollTop = logElement.scrollHeight;
        }

        function showResult(message, type = 'info') {
            const container = document.getElementById('config-results');
            const div = document.createElement('div');
            div.className = `status status-${type}`;
            div.innerHTML = message;
            container.appendChild(div);
            
            setTimeout(() => {
                if (div.parentNode) {
                    div.parentNode.removeChild(div);
                }
            }, 10000);
        }

        async function testEmailConfig() {
            log('Testing email configuration...');
            
            try {
                // Test API endpoint
                const response = await fetch('/api-direct.php?endpoint=contacts', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        name: 'Test API Email',
                        email: 'test@example.com',
                        subject: 'Test via API',
                        message: 'Test invio email via API - ' + new Date().toLocaleString()
                    })
                });

                const result = await response.json();
                
                if (result.success) {
                    showResult('✅ API Contatti: ' + result.message + ' (Email sent: ' + result.email_sent + ')', 'success');
                    log('API contact test: SUCCESS - Email sent: ' + result.email_sent);
                } else {
                    showResult('❌ API Contatti: ' + result.error, 'error');
                    log('API contact test: FAILED - ' + result.error);
                }
            } catch (error) {
                showResult('❌ Errore test API: ' + error.message, 'error');
                log('API test error: ' + error.message);
            }
        }

        function checkMailLog() {
            log('Checking mail server logs...');
            // In un sistema reale, qui potresti leggere i log del server
            showResult('ℹ️ Log mail server: Controlla i log del server per dettagli invio email', 'info');
        }

        function clearLog() {
            document.getElementById('email-log').innerHTML = 'Log pulito\nTimestamp: ' + new Date().toLocaleString();
        }

        // Initialize
        window.onload = function() {
            log('Email test system loaded');
        };
    </script>
</body>
</html> 