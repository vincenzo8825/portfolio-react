<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Get the path from the URL
$requestUri = $_SERVER['REQUEST_URI'];
$path = parse_url($requestUri, PHP_URL_PATH);

// Remove /api-proxy.php from the path
$endpoint = str_replace('/api-proxy.php', '', $path);

// Database connection
$host = 'localhost';
$dbname = 'u336414084_portfolioVince';
$username = 'u336414084_vincenzorocca8';
$password = 'Ciaociao52.?';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Database connection failed: ' . $e->getMessage()]);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

try {
    // Handle contacts endpoint
    if ($endpoint === '/v1/contacts' && $method === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true);
        
        // Validazione base
        if (empty($input['name']) || empty($input['email']) || empty($input['message'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Nome, email e messaggio sono obbligatori']);
            exit;
        }
        
        // Save to database
        $stmt = $pdo->prepare("INSERT INTO contacts (name, email, phone, company, subject, message, budget, timeline, project_type, status, ip_address, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'new', ?, NOW(), NOW())");
        $stmt->execute([
            $input['name'] ?? '',
            $input['email'] ?? '',
            $input['phone'] ?? '',
            $input['company'] ?? '',
            $input['subject'] ?? '',
            $input['message'] ?? '',
            $input['budget'] ?? '',
            $input['timeline'] ?? '',
            $input['project_type'] ?? '',
            $_SERVER['REMOTE_ADDR']
        ]);
        
        $contact_id = $pdo->lastInsertId();
        
        // Send email notification
        $to = 'vincenzorocca88@gmail.com';
        $email_subject = '[PORTFOLIO] ' . ($input['subject'] ?? 'Nuovo messaggio dal portfolio');
        
        // Create beautiful HTML email
        $email_html = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 20px; background: #f8fafc; }
        .container { max-width: 600px; margin: 0 auto; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%); color: white; padding: 30px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; font-weight: 600; }
        .header p { margin: 10px 0 0 0; opacity: 0.9; }
        .content { padding: 30px; }
        .field { margin-bottom: 20px; }
        .field-label { font-weight: 600; color: #374151; margin-bottom: 5px; display: block; }
        .field-value { background: #f8fafc; padding: 12px; border-radius: 8px; border-left: 4px solid #3b82f6; }
        .message-box { background: #f0f9ff; border: 1px solid #bfdbfe; border-radius: 8px; padding: 20px; margin: 20px 0; }
        .footer { background: #f8fafc; padding: 20px; text-align: center; font-size: 14px; color: #6b7280; border-top: 1px solid #e5e7eb; }
        .badge { display: inline-block; background: #10b981; color: white; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 500; }
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin: 20px 0; }
        @media (max-width: 600px) { .info-grid { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📧 Nuovo Messaggio Portfolio</h1>
            <p>Messaggio ricevuto da vincenzorocca.com</p>
        </div>
        
        <div class="content">
            <div class="field">
                <span class="field-label">👤 Nome:</span>
                <div class="field-value"><strong>' . htmlspecialchars($input['name'] ?? '') . '</strong></div>
            </div>
            
            <div class="info-grid">
                <div class="field">
                    <span class="field-label">📧 Email:</span>
                    <div class="field-value">' . htmlspecialchars($input['email'] ?? '') . '</div>
                </div>
                
                <div class="field">
                    <span class="field-label">📱 Telefono:</span>
                    <div class="field-value">' . htmlspecialchars($input['phone'] ?? 'Non fornito') . '</div>
                </div>
            </div>
            
            <div class="info-grid">
                <div class="field">
                    <span class="field-label">🏢 Azienda:</span>
                    <div class="field-value">' . htmlspecialchars($input['company'] ?? 'Non specificata') . '</div>
                </div>
                
                <div class="field">
                    <span class="field-label">💰 Budget:</span>
                    <div class="field-value">' . htmlspecialchars($input['budget'] ?? 'Non specificato') . '</div>
                </div>
            </div>
            
            <div class="info-grid">
                <div class="field">
                    <span class="field-label">⏰ Timeline:</span>
                    <div class="field-value">' . htmlspecialchars($input['timeline'] ?? 'Non specificata') . '</div>
                </div>
                
                <div class="field">
                    <span class="field-label">🚀 Tipo Progetto:</span>
                    <div class="field-value">' . htmlspecialchars($input['project_type'] ?? 'Non specificato') . '</div>
                </div>
            </div>
            
            <div class="field">
                <span class="field-label">💬 Messaggio:</span>
                <div class="message-box">
                    ' . nl2br(htmlspecialchars($input['message'] ?? '')) . '
                </div>
            </div>
            
            <div style="margin-top: 30px; padding: 20px; background: #f0f9ff; border-radius: 8px;">
                <h3 style="margin: 0 0 15px 0; color: #1e40af;">📊 Dettagli Tecnici</h3>
                <p style="margin: 5px 0;"><strong>ID Contatto:</strong> <span class="badge">' . $contact_id . '</span></p>
                <p style="margin: 5px 0;"><strong>Data/Ora:</strong> ' . date('d/m/Y H:i:s') . '</p>
                <p style="margin: 5px 0;"><strong>IP Address:</strong> ' . $_SERVER['REMOTE_ADDR'] . '</p>
                <p style="margin: 5px 0;"><strong>User Agent:</strong> ' . ($_SERVER['HTTP_USER_AGENT'] ?? 'N/A') . '</p>
            </div>
        </div>
        
        <div class="footer">
            <p><strong>Portfolio Vincenzo Rocca</strong></p>
            <p>🌐 <a href="https://vincenzorocca.com">vincenzorocca.com</a> | 📧 vincenzorocca88@gmail.com</p>
            <p style="margin-top: 15px; font-size: 12px; color: #9ca3af;">
                Questo messaggio è stato inviato automaticamente dal form contatti del portfolio.
            </p>
        </div>
    </div>
</body>
</html>';
        
        // Plain text version for fallback
        $email_text = "Nuovo messaggio dal portfolio:\n\n";
        $email_text .= "Nome: " . ($input['name'] ?? '') . "\n";
        $email_text .= "Email: " . ($input['email'] ?? '') . "\n";
        $email_text .= "Telefono: " . ($input['phone'] ?? 'N/A') . "\n";
        $email_text .= "Azienda: " . ($input['company'] ?? 'N/A') . "\n";
        $email_text .= "Budget: " . ($input['budget'] ?? 'N/A') . "\n";
        $email_text .= "Timeline: " . ($input['timeline'] ?? 'N/A') . "\n";
        $email_text .= "Tipo Progetto: " . ($input['project_type'] ?? 'N/A') . "\n";
        $email_text .= "ID Contatto: " . $contact_id . "\n";
        $email_text .= "Data: " . date('Y-m-d H:i:s') . "\n\n";
        $email_text .= "Messaggio:\n" . ($input['message'] ?? '');
        
        // Multipart headers for HTML email
        $boundary = uniqid('boundary_');
        $headers = 'From: Portfolio Vincenzo Rocca <noreply@vincenzorocca.com>' . "\r\n" .
                  'Reply-To: ' . ($input['email'] ?? 'noreply@vincenzorocca.com') . "\r\n" .
                  'X-Mailer: PHP/' . phpversion() . "\r\n" .
                  'MIME-Version: 1.0' . "\r\n" .
                  'Content-Type: multipart/alternative; boundary="' . $boundary . '"';
        
        $email_body = '--' . $boundary . "\r\n";
        $email_body .= 'Content-Type: text/plain; charset=UTF-8' . "\r\n";
        $email_body .= 'Content-Transfer-Encoding: 8bit' . "\r\n\r\n";
        $email_body .= $email_text . "\r\n\r\n";
        
        $email_body .= '--' . $boundary . "\r\n";
        $email_body .= 'Content-Type: text/html; charset=UTF-8' . "\r\n";
        $email_body .= 'Content-Transfer-Encoding: 8bit' . "\r\n\r\n";
        $email_body .= $email_html . "\r\n\r\n";
        
        $email_body .= '--' . $boundary . '--';
        
        // Try to send email
        $email_sent = @mail($to, $email_subject, $email_body, $headers);
        
        // Log email attempt
        error_log("Email attempt - To: $to, Subject: $email_subject, Result: " . ($email_sent ? 'SUCCESS' : 'FAILED'));
        
        echo json_encode([
            'success' => true,
            'message' => 'Messaggio inviato con successo',
            'data' => [
                'contact_id' => $contact_id,
                'email_sent' => $email_sent,
                'timestamp' => date('Y-m-d H:i:s')
            ]
        ]);
    } 
    // Handle other endpoints if needed
    else {
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'Endpoint not found: ' . $endpoint]);
    }
    
} catch(Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Server error: ' . $e->getMessage()]);
}
?> 