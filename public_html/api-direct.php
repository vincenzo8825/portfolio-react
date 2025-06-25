<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

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
    echo json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]);
    exit;
}

$endpoint = $_GET['endpoint'] ?? '';
$method = $_SERVER['REQUEST_METHOD'];

try {
    switch($endpoint) {
        case 'technologies':
            if ($method === 'GET') {
                $stmt = $pdo->query("SELECT * FROM technologies ORDER BY name");
                $technologies = $stmt->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode(['data' => $technologies]);
            }
            break;
            
        case 'projects':
            if ($method === 'GET') {
                $stmt = $pdo->query("SELECT * FROM projects ORDER BY created_at DESC");
                $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode(['data' => $projects]);
            } elseif ($method === 'POST') {
                $input = json_decode(file_get_contents('php://input'), true);
                
                // Validazione base - usa title invece di name
                $title = $input['name'] ?? $input['title'] ?? '';
                if (empty($title) || empty($input['description'])) {
                    http_response_code(400);
                    echo json_encode(['error' => 'Titolo e descrizione sono obbligatori']);
                    break;
                }
                
                // Converti technologies in JSON array se è una stringa
                $technologies = $input['technologies'] ?? '';
                if (is_string($technologies) && !empty($technologies)) {
                    // Se è una stringa separata da virgole, convertila in array JSON
                    $techArray = array_map('trim', explode(',', $technologies));
                    $technologies = json_encode($techArray);
                } elseif (empty($technologies)) {
                    $technologies = json_encode([]);
                }
                
                // Genera slug dal titolo
                $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
                
                $stmt = $pdo->prepare("INSERT INTO projects (title, slug, description, demo_url, github_url, image_url, technologies, category, status, long_description, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
                $stmt->execute([
                    $title,
                    $slug,
                    $input['description'] ?? '',
                    $input['demo_url'] ?? '',
                    $input['github_url'] ?? '',
                    $input['image_url'] ?? '',
                    $technologies,
                    $input['category'] ?? 'web',
                    $input['status'] ?? 'completed',
                    $input['long_description'] ?? ''
                ]);
                
                $project_id = $pdo->lastInsertId();
                echo json_encode(['success' => true, 'message' => 'Progetto creato con successo', 'project_id' => $project_id]);
            } elseif ($method === 'PUT') {
                $input = json_decode(file_get_contents('php://input'), true);
                $project_id = $_GET['id'] ?? 0;
                
                $title = $input['name'] ?? $input['title'] ?? '';
                
                // Converti technologies in JSON array per UPDATE
                $technologies = $input['technologies'] ?? '';
                if (is_string($technologies) && !empty($technologies)) {
                    $techArray = array_map('trim', explode(',', $technologies));
                    $technologies = json_encode($techArray);
                } elseif (empty($technologies)) {
                    $technologies = json_encode([]);
                }
                
                $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
                
                $stmt = $pdo->prepare("UPDATE projects SET title = ?, slug = ?, description = ?, demo_url = ?, github_url = ?, image_url = ?, technologies = ?, category = ?, status = ?, long_description = ?, updated_at = NOW() WHERE id = ?");
                $stmt->execute([
                    $title,
                    $slug,
                    $input['description'] ?? '',
                    $input['demo_url'] ?? '',
                    $input['github_url'] ?? '',
                    $input['image_url'] ?? '',
                    $technologies,
                    $input['category'] ?? 'web',
                    $input['status'] ?? 'completed',
                    $input['long_description'] ?? '',
                    $project_id
                ]);
                
                echo json_encode(['success' => true, 'message' => 'Progetto aggiornato con successo']);
            } elseif ($method === 'DELETE') {
                $project_id = $_GET['id'] ?? 0;
                
                $stmt = $pdo->prepare("DELETE FROM projects WHERE id = ?");
                $stmt->execute([$project_id]);
                
                echo json_encode(['success' => true, 'message' => 'Progetto eliminato con successo']);
            }
            break;
            
        case 'login':
            if ($method === 'POST') {
                $input = json_decode(file_get_contents('php://input'), true);
                $email = $input['email'] ?? '';
                $password = $input['password'] ?? '';
                
                $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
                $stmt->execute([$email]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($user && password_verify($password, $user['password'])) {
                    // Generate simple token (in production use JWT)
                    $token = bin2hex(random_bytes(32));
                    
                    echo json_encode([
                        'success' => true,
                        'token' => $token,
                        'user' => [
                            'id' => $user['id'],
                            'name' => $user['name'],
                            'email' => $user['email'],
                            'is_admin' => (bool)$user['is_admin']
                        ]
                    ]);
                } else {
                    // Debug: Check if user exists
                    if ($user) {
                        // User exists but password is wrong
                        http_response_code(401);
                        echo json_encode(['success' => false, 'message' => 'Password non corretta per utente: ' . $email]);
                    } else {
                        // User doesn't exist
                        http_response_code(401);
                        echo json_encode(['success' => false, 'message' => 'Utente non trovato: ' . $email]);
                    }
                }
            }
            break;
            
        case 'create-admin':
            if ($method === 'POST') {
                $input = json_decode(file_get_contents('php://input'), true);
                $email = $input['email'] ?? '';
                $password = $input['password'] ?? '';
                $name = $input['name'] ?? '';
                
                // Check if user already exists
                $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
                $stmt->execute([$email]);
                $existing_user = $stmt->fetch();
                
                if ($existing_user) {
                    echo json_encode(['success' => false, 'message' => 'Utente già esistente']);
                } else {
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("INSERT INTO users (name, email, password, is_admin, created_at, updated_at) VALUES (?, ?, ?, 1, NOW(), NOW())");
                    $stmt->execute([$name, $email, $hashed_password]);
                    
                    echo json_encode(['success' => true, 'message' => 'Utente admin creato con successo']);
                }
            }
            break;
            
        case 'contacts':
            if ($method === 'POST') {
                $input = json_decode(file_get_contents('php://input'), true);
                
                // Validazione base
                if (empty($input['name']) || empty($input['email']) || empty($input['message'])) {
                    http_response_code(400);
                    echo json_encode(['success' => false, 'error' => 'Nome, email e messaggio sono obbligatori']);
                    break;
                }
                
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
                
                // Send beautiful HTML email notification
                $to = 'vincenzorocca88@gmail.com';
                $email_subject = '[PORTFOLIO] ' . ($input['subject'] ?? 'Nuovo messaggio');
                
                // Create beautiful HTML email (same as api-proxy.php)
                $email_html = '<!DOCTYPE html><html><head><meta charset="UTF-8"><style>body{font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Arial,sans-serif;line-height:1.6;color:#333;margin:0;padding:20px;background:#f8fafc}.container{max-width:600px;margin:0 auto;background:white;border-radius:12px;overflow:hidden;box-shadow:0 4px 12px rgba(0,0,0,0.1)}.header{background:linear-gradient(135deg,#3b82f6 0%,#1e40af 100%);color:white;padding:30px;text-align:center}.header h1{margin:0;font-size:24px;font-weight:600}.content{padding:30px}.field{margin-bottom:20px}.field-label{font-weight:600;color:#374151;margin-bottom:5px;display:block}.field-value{background:#f8fafc;padding:12px;border-radius:8px;border-left:4px solid #3b82f6}.message-box{background:#f0f9ff;border:1px solid #bfdbfe;border-radius:8px;padding:20px;margin:20px 0}.footer{background:#f8fafc;padding:20px;text-align:center;font-size:14px;color:#6b7280;border-top:1px solid #e5e7eb}.badge{display:inline-block;background:#10b981;color:white;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:500}</style></head><body><div class="container"><div class="header"><h1>📧 Nuovo Messaggio Portfolio</h1><p>Messaggio ricevuto da vincenzorocca.com</p></div><div class="content"><div class="field"><span class="field-label">👤 Nome:</span><div class="field-value"><strong>' . htmlspecialchars($input['name'] ?? '') . '</strong></div></div><div class="field"><span class="field-label">📧 Email:</span><div class="field-value">' . htmlspecialchars($input['email'] ?? '') . '</div></div><div class="field"><span class="field-label">💬 Messaggio:</span><div class="message-box">' . nl2br(htmlspecialchars($input['message'] ?? '')) . '</div></div><div style="margin-top:30px;padding:20px;background:#f0f9ff;border-radius:8px"><h3 style="margin:0 0 15px 0;color:#1e40af">📊 Dettagli</h3><p><strong>ID:</strong> <span class="badge">' . $contact_id . '</span></p><p><strong>Data:</strong> ' . date('d/m/Y H:i:s') . '</p></div></div><div class="footer"><p><strong>Portfolio Vincenzo Rocca</strong></p><p>🌐 vincenzorocca.com</p></div></div></body></html>';
                
                $email_text = "Nuovo messaggio dal portfolio:\n\nNome: " . ($input['name'] ?? '') . "\nEmail: " . ($input['email'] ?? '') . "\nMessaggio: " . ($input['message'] ?? '') . "\n\nID: " . $contact_id . "\nData: " . date('Y-m-d H:i:s');
                
                $boundary = uniqid('boundary_');
                $headers = 'From: Portfolio <noreply@vincenzorocca.com>' . "\r\n" .
                          'Reply-To: ' . ($input['email'] ?? '') . "\r\n" .
                          'X-Mailer: PHP/' . phpversion() . "\r\n" .
                          'MIME-Version: 1.0' . "\r\n" .
                          'Content-Type: multipart/alternative; boundary="' . $boundary . '"';
                
                $email_body = '--' . $boundary . "\r\n" .
                             'Content-Type: text/plain; charset=UTF-8' . "\r\n\r\n" .
                             $email_text . "\r\n\r\n" .
                             '--' . $boundary . "\r\n" .
                             'Content-Type: text/html; charset=UTF-8' . "\r\n\r\n" .
                             $email_html . "\r\n\r\n" .
                             '--' . $boundary . '--';
                
                // Try to send email
                $email_sent = @mail($to, $email_subject, $email_body, $headers);
                
                echo json_encode([
                    'success' => true, 
                    'message' => 'Contact form submitted successfully',
                    'contact_id' => $contact_id,
                    'email_sent' => $email_sent
                ]);
            }
            break;
            
        case 'stats':
            if ($method === 'GET') {
                $users_count = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
                $projects_count = $pdo->query("SELECT COUNT(*) FROM projects")->fetchColumn();
                $contacts_count = $pdo->query("SELECT COUNT(*) FROM contacts")->fetchColumn();
                $technologies_count = $pdo->query("SELECT COUNT(*) FROM technologies")->fetchColumn();
                $featured_projects = $pdo->query("SELECT COUNT(*) FROM projects WHERE featured = 1")->fetchColumn();
                $completed_projects = $pdo->query("SELECT COUNT(*) FROM projects WHERE status = 'completed'")->fetchColumn();
                
                echo json_encode([
                    'data' => [
                        'users' => (int)$users_count,
                        'projects' => (int)$projects_count,
                        'contacts' => (int)$contacts_count,
                        'technologies' => (int)$technologies_count,
                        'featured_projects' => (int)$featured_projects,
                        'completed_projects' => (int)$completed_projects
                    ]
                ]);
            }
            break;
            
        case 'toggle-featured':
            if ($method === 'POST') {
                $input = json_decode(file_get_contents('php://input'), true);
                $project_id = $input['project_id'] ?? 0;
                
                // Controlla quanti progetti sono già in evidenza
                $featured_count = $pdo->query("SELECT COUNT(*) FROM projects WHERE featured = 1")->fetchColumn();
                
                // Ottieni lo stato attuale del progetto
                $stmt = $pdo->prepare("SELECT featured FROM projects WHERE id = ?");
                $stmt->execute([$project_id]);
                $current_featured = $stmt->fetchColumn();
                
                if (!$current_featured && $featured_count >= 3) {
                    http_response_code(400);
                    echo json_encode(['error' => 'Massimo 3 progetti possono essere in evidenza']);
                    break;
                }
                
                // Toggle featured status
                $new_featured = $current_featured ? 0 : 1;
                $stmt = $pdo->prepare("UPDATE projects SET featured = ? WHERE id = ?");
                $stmt->execute([$new_featured, $project_id]);
                
                echo json_encode([
                    'success' => true,
                    'message' => $new_featured ? 'Progetto aggiunto ai preferiti' : 'Progetto rimosso dai preferiti',
                    'featured' => (bool)$new_featured
                ]);
            }
            break;
            
        case 'update-project':
            if ($method === 'POST') {
                $input = json_decode(file_get_contents('php://input'), true);
                
                $stmt = $pdo->prepare("UPDATE projects SET title = ?, description = ?, demo_url = ?, github_url = ?, technologies = ?, featured = ?, updated_at = NOW() WHERE id = ?");
                $stmt->execute([
                    $input['title'] ?? '',
                    $input['description'] ?? '',
                    $input['demo_url'] ?? '',
                    $input['github_url'] ?? '',
                    json_encode($input['technologies'] ?? []),
                    isset($input['featured']) ? 1 : 0,
                    $input['id'] ?? 0
                ]);
                
                echo json_encode(['success' => true, 'message' => 'Progetto aggiornato con successo']);
            }
            break;
            
        case 'delete-project':
            if ($method === 'POST') {
                $input = json_decode(file_get_contents('php://input'), true);
                $project_id = $input['project_id'] ?? 0;
                
                $stmt = $pdo->prepare("DELETE FROM projects WHERE id = ?");
                $stmt->execute([$project_id]);
                
                echo json_encode(['success' => true, 'message' => 'Progetto eliminato con successo']);
            }
            break;
            
        case 'upload-test':
            if ($method === 'POST') {
                // Simula upload file (in produzione andrà implementato con Cloudinary)
                $input = json_decode(file_get_contents('php://input'), true);
                $filename = $input['filename'] ?? 'test-image.jpg';
                $file_type = $input['type'] ?? 'image';
                
                // Simula URL Cloudinary
                $cloudinary_url = "https://res.cloudinary.com/djt2a7xwk/image/upload/v1/portfolio/" . time() . "_" . $filename;
                
                echo json_encode([
                    'success' => true,
                    'message' => 'File caricato con successo (simulazione)',
                    'data' => [
                        'url' => $cloudinary_url,
                        'public_id' => 'portfolio/' . time() . "_" . pathinfo($filename, PATHINFO_FILENAME),
                        'type' => $file_type
                    ]
                ]);
            }
            break;
            
        case 'get-contacts':
            if ($method === 'GET') {
                $stmt = $pdo->query("SELECT * FROM contacts ORDER BY created_at DESC LIMIT 20");
                $contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                echo json_encode(['data' => $contacts]);
            }
            break;
            
        case 'update-contact-status':
            if ($method === 'POST') {
                $input = json_decode(file_get_contents('php://input'), true);
                $contact_id = $input['contact_id'] ?? 0;
                $status = $input['status'] ?? 'new';
                
                $stmt = $pdo->prepare("UPDATE contacts SET status = ?, updated_at = NOW() WHERE id = ?");
                $stmt->execute([$status, $contact_id]);
                
                echo json_encode(['success' => true, 'message' => 'Status contatto aggiornato']);
            }
            break;
            
        case 'change-password':
            if ($method === 'POST') {
                $input = json_decode(file_get_contents('php://input'), true);
                $user_id = $input['user_id'] ?? 0;
                $current_password = $input['current_password'] ?? '';
                $new_password = $input['new_password'] ?? '';
                
                // Verifica password attuale
                $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
                $stmt->execute([$user_id]);
                $stored_password = $stmt->fetchColumn();
                
                if (password_verify($current_password, $stored_password)) {
                    $hashed = password_hash($new_password, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("UPDATE users SET password = ?, updated_at = NOW() WHERE id = ?");
                    $stmt->execute([$hashed, $user_id]);
                    
                    echo json_encode(['success' => true, 'message' => 'Password cambiata con successo']);
                } else {
                    http_response_code(400);
                    echo json_encode(['error' => 'Password attuale non corretta']);
                }
            }
            break;
            
        default:
            http_response_code(404);
            echo json_encode(['error' => 'Endpoint not found']);
            break;
    }
} catch(Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
}
?> 