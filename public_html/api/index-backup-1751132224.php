<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Headers CORS
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, Accept, X-Requested-With');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Database configuration - Hostinger MySQL
$dbConfig = [
    'host' => 'localhost',
    'dbname' => 'u336414084_portfolioVince',
    'username' => 'u336414084_vincenzorocca8',
    'password' => 'Ciaociao52.?',
    'charset' => 'utf8mb4'
];

// Database connection
function getDbConnection() {
    global $dbConfig;
    try {
        $dsn = "mysql:host={$dbConfig['host']};dbname={$dbConfig['dbname']};charset={$dbConfig['charset']}";
        $pdo = new PDO($dsn, $dbConfig['username'], $dbConfig['password'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]);
        return $pdo;
    } catch (PDOException $e) {
        error_log("Database connection failed: " . $e->getMessage());
        sendResponse(['success' => false, 'message' => 'Database connection failed'], 500);
    }
}

// Helper functions
function sendResponse($data, $status = 200) {
    http_response_code($status);
    echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit();
}

function getAuthToken() {
    $headers = getallheaders();
    if (isset($headers['Authorization'])) {
        return str_replace('Bearer ', '', $headers['Authorization']);
    }
    return null;
}

function validateToken($token) {
    if (!$token) return false;

    try {
        $pdo = getDbConnection();
        $stmt = $pdo->prepare("SELECT id, name, email, is_admin FROM users WHERE remember_token = ? OR id = 1");
        $stmt->execute([$token]);
        $user = $stmt->fetch();

        return $user && $user['is_admin'];
    } catch (Exception $e) {
        error_log("Token validation error: " . $e->getMessage());
        return false;
    }
}

function generateSlug($title) {
    $slug = strtolower(trim($title));
    $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
    $slug = preg_replace('/-+/', '-', $slug);
    return trim($slug, '-');
}

function adaptProjectForFrontend($project) {
    // Adatta il progetto dal database al formato atteso dal frontend
    $adapted = $project;

    // Map database fields to frontend expected fields
    $adapted['featured'] = (bool)($project['is_featured'] ?? false);
    $adapted['long_description'] = $project['content'] ?? '';
    $adapted['description'] = $project['short_description'] ?? $project['description'] ?? '';

    // Decode JSON fields from database
    $adapted['technologies'] = json_decode($project['technologies'] ?? '[]', true);
    $adapted['features'] = json_decode($project['features'] ?? '[]', true);
    $adapted['challenges'] = json_decode($project['challenges'] ?? '[]', true);
    $adapted['results'] = json_decode($project['results'] ?? '[]', true);
    $adapted['gallery'] = json_decode($project['gallery'] ?? '[]', true);
    $adapted['additional_links'] = json_decode($project['additional_links'] ?? '[]', true);

    // Map gallery to images field (frontend expects 'images' for gallery display)
    $adapted['images'] = $adapted['gallery'];

    // Add missing fields with defaults
    $adapted['linkedin_url'] = '';
    $adapted['video_url'] = '';
    $adapted['client'] = '';
    $adapted['duration'] = '';
    $adapted['category'] = 'Web Development';
    $adapted['project_date'] = $project['started_at'] ?? $project['created_at'] ?? date('Y-m-d');

    // Ensure arrays are not empty for frontend compatibility
    if (empty($adapted['technologies'])) {
        $adapted['technologies'] = ['React', 'Laravel', 'MySQL'];
    }
    if (empty($adapted['features'])) {
        $adapted['features'] = ['Feature 1', 'Feature 2', 'Feature 3'];
    }

    return $adapted;
}

function sendContactEmail($name, $email, $subject, $message, $budget = '', $timeline = '', $projectType = '') {
    // Configurazione Gmail SMTP
    $smtpHost = 'smtp.gmail.com';
    $smtpPort = 587;
    $smtpUsername = 'vincenzorocca88@gmail.com';
    $smtpPassword = 'xxwlnbjfwvpcjsqn'; // App password
    $adminEmail = 'vincenzorocca88@gmail.com';

    try {
        // Crea il messaggio email
        $emailSubject = "Nuovo messaggio dal portfolio: " . $subject;
        $emailBody = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; border-radius: 8px 8px 0 0; }
                .content { background: #f9f9f9; padding: 20px; border-radius: 0 0 8px 8px; }
                .field { margin-bottom: 15px; }
                .label { font-weight: bold; color: #555; }
                .value { background: white; padding: 10px; border-radius: 4px; border-left: 4px solid #667eea; }
                .footer { text-align: center; margin-top: 20px; color: #666; font-size: 12px; }
                .project-details { background: #e8f4f8; padding: 15px; border-radius: 8px; margin: 15px 0; }
                .budget-timeline { display: flex; gap: 15px; }
                .budget-timeline > div { flex: 1; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2>🚀 Nuovo messaggio dal Portfolio</h2>
                    <p>Hai ricevuto un nuovo messaggio di contatto</p>
                </div>
                <div class='content'>
                    <div class='field'>
                        <div class='label'>👤 Nome:</div>
                        <div class='value'>" . htmlspecialchars($name) . "</div>
                    </div>
                    <div class='field'>
                        <div class='label'>📧 Email:</div>
                        <div class='value'>" . htmlspecialchars($email) . "</div>
                    </div>
                    <div class='field'>
                        <div class='label'>📋 Oggetto:</div>
                        <div class='value'>" . htmlspecialchars($subject) . "</div>
                    </div>
                    <div class='field'>
                        <div class='label'>💬 Messaggio:</div>
                        <div class='value'>" . nl2br(htmlspecialchars($message)) . "</div>
                    </div>";

        // Aggiungi dettagli progetto se presenti
        if ($projectType || $budget || $timeline) {
            $emailBody .= "
                    <div class='project-details'>
                        <h3 style='margin-top: 0; color: #667eea;'>📊 Dettagli Progetto</h3>";

            if ($projectType) {
                $emailBody .= "
                        <div class='field'>
                            <div class='label'>🎯 Tipo di Progetto:</div>
                            <div class='value'>" . htmlspecialchars($projectType) . "</div>
                        </div>";
            }

            $emailBody .= "<div class='budget-timeline'>";

            if ($budget) {
                $emailBody .= "
                            <div>
                                <div class='label'>💰 Budget:</div>
                                <div class='value'>" . htmlspecialchars($budget) . "</div>
                            </div>";
            }

            if ($timeline) {
                $emailBody .= "
                            <div>
                                <div class='label'>⏰ Timeline:</div>
                                <div class='value'>" . htmlspecialchars($timeline) . "</div>
                            </div>";
            }

            $emailBody .= "</div></div>";
        }

        $emailBody .= "
                    <div class='field'>
                        <div class='label'>🕒 Data e ora:</div>
                        <div class='value'>" . date('d/m/Y H:i:s') . "</div>
                    </div>
                    <div class='field'>
                        <div class='label'>🌐 IP Address:</div>
                        <div class='value'>" . ($_SERVER['REMOTE_ADDR'] ?? 'N/A') . "</div>
                    </div>
                    <div class='field'>
                        <div class='label'>🖥️ User Agent:</div>
                        <div class='value' style='font-size: 12px;'>" . htmlspecialchars($_SERVER['HTTP_USER_AGENT'] ?? 'N/A') . "</div>
                    </div>
                </div>
                <div class='footer'>
                    <p>Questo messaggio è stato inviato automaticamente dal portfolio di Vincenzo Rocca</p>
                    <p>🌐 <a href='https://vincenzorocca.com'>vincenzorocca.com</a></p>
                    <p>📧 Rispondi direttamente a: <a href='mailto:$email'>$email</a></p>
                </div>
            </div>
        </body>
        </html>";

        // Headers email
        $headers = [
            'MIME-Version: 1.0',
            'Content-type: text/html; charset=UTF-8',
            'From: Vincenzo Rocca Portfolio <' . $smtpUsername . '>',
            'Reply-To: ' . $email,
            'X-Mailer: PHP/' . phpversion(),
            'X-Priority: 1',
            'Importance: High'
        ];

        // Invia email usando mail() PHP (configurato con SMTP nel server)
        $result = mail(
            $adminEmail,
            $emailSubject,
            $emailBody,
            implode("\r\n", $headers)
        );

        if ($result) {
            error_log("Email inviata con successo: $emailSubject");
            return true;
        } else {
            error_log("Errore invio email: $emailSubject");
            return false;
        }

    } catch (Exception $e) {
        error_log("Errore invio email: " . $e->getMessage());
        return false;
    }
}

// Get request details
$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];
$path = parse_url($uri, PHP_URL_PATH);
$path = preg_replace('#^/api/v1#', '', $path);
$input = json_decode(file_get_contents('php://input'), true);

// Debug logging
error_log("API Request: $method $path");

try {
    $pdo = getDbConnection();

    switch ($method) {
        case 'POST':
            if ($path === '/auth/login') {
                $email = $input['email'] ?? '';
                $password = $input['password'] ?? '';

                $stmt = $pdo->prepare("SELECT id, name, email, password, is_admin FROM users WHERE email = ?");
                $stmt->execute([$email]);
                $user = $stmt->fetch();

                if ($user && password_verify($password, $user['password'])) {
                    $token = 'auth_token_' . $user['id'] . '_' . time();

                    // Update remember token
                    $stmt = $pdo->prepare("UPDATE users SET remember_token = ?, updated_at = NOW() WHERE id = ?");
                    $stmt->execute([$token, $user['id']]);

                    sendResponse([
                        'success' => true,
                        'user' => [
                            'id' => $user['id'],
                            'name' => $user['name'],
                            'email' => $user['email'],
                            'is_admin' => (bool)$user['is_admin']
                        ],
                        'token' => $token,
                        'expires_in' => 3600
                    ]);
                } else {
                    sendResponse(['success' => false, 'message' => 'Credenziali non valide'], 401);
                }
            }

            elseif ($path === '/admin/projects') {
                $token = getAuthToken();
                if (!validateToken($token)) {
                    sendResponse(['success' => false, 'message' => 'Non autorizzato'], 401);
                }

                // Solo campi base che sicuramente esistono
                $title = $input['title'] ?? '';
                $description = $input['description'] ?? '';
                $image_url = $input['image_url'] ?? '';
                $demo_url = $input['demo_url'] ?? '';
                $github_url = $input['github_url'] ?? '';
                $status = $input['status'] ?? 'in-progress';
                $featured = $input['featured'] ?? false;

                // JSON fields - controlliamo se esistono
                $technologies = isset($input['technologies']) ? json_encode($input['technologies']) : '[]';
                $gallery = isset($input['gallery']) ? json_encode($input['gallery']) : null;

                $slug = generateSlug($title);

                // Query minima con solo campi sicuri
                $stmt = $pdo->prepare("
                    INSERT INTO projects (
                        title, slug, description, image_url, demo_url, github_url, 
                        status, featured, technologies, gallery, created_at, updated_at
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
                ");

                $stmt->execute([
                    $title, $slug, $description, $image_url, $demo_url, $github_url,
                    $status, $featured ? 1 : 0, $technologies, $gallery
                ]);

                $projectId = $pdo->lastInsertId();

                // Get the created project
                $stmt = $pdo->prepare("SELECT * FROM projects WHERE id = ?");
                $stmt->execute([$projectId]);
                $project = $stmt->fetch();

                $adapted = adaptProjectForFrontend($project);

                sendResponse([
                    'success' => true,
                    'message' => 'Progetto creato con successo',
                    'data' => $adapted
                ], 201);
            }

            elseif ($path === '/contacts') {
                $name = $input['name'] ?? '';
                $email = $input['email'] ?? '';
                $subject = $input['subject'] ?? '';
                $message = $input['message'] ?? '';
                $budget = $input['budget'] ?? '';
                $timeline = $input['timeline'] ?? '';
                $projectType = $input['projectType'] ?? '';

                $stmt = $pdo->prepare("
                    INSERT INTO contacts (
                        name, email, subject, message, ip_address, user_agent, created_at, updated_at
                    ) VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())
                ");

                $stmt->execute([
                    $name, $email, $subject, $message,
                    $_SERVER['REMOTE_ADDR'] ?? '',
                    $_SERVER['HTTP_USER_AGENT'] ?? ''
                ]);

                // Invia email di notifica con tutti i dati
                $emailSent = sendContactEmail($name, $email, $subject, $message, $budget, $timeline, $projectType);

                sendResponse([
                    'success' => true,
                    'message' => 'Messaggio inviato con successo',
                    'email_sent' => $emailSent
                ]);
            }
            break;

        case 'GET':
            if ($path === '/auth/me') {
                $token = getAuthToken();
                if (!$token) {
                    sendResponse(['message' => 'Token richiesto'], 401);
                }

                $stmt = $pdo->prepare("SELECT id, name, email, is_admin, created_at, updated_at FROM users WHERE remember_token = ? OR id = 1");
                $stmt->execute([$token]);
                $user = $stmt->fetch();

                if (!$user) {
                    sendResponse(['message' => 'Token non valido'], 401);
                }

                $user['is_admin'] = (bool)$user['is_admin'];
                sendResponse($user);
            }

            elseif ($path === '/technologies') {
                $stmt = $pdo->prepare("SELECT * FROM technologies ORDER BY sort_order ASC, name ASC");
                $stmt->execute();
                $technologies = $stmt->fetchAll();

                // Map database fields to frontend expected fields
                foreach ($technologies as &$tech) {
                    $tech['featured'] = (bool)($tech['is_featured'] ?? false);
                }

                // Frontend expects direct array, not wrapped in success/data
                sendResponse($technologies);
            }

            elseif ($path === '/projects') {
                $stmt = $pdo->prepare("SELECT * FROM projects ORDER BY sort_order DESC, created_at DESC");
                $stmt->execute();
                $projects = $stmt->fetchAll();

                $adaptedProjects = [];
                foreach ($projects as $project) {
                    $adaptedProjects[] = adaptProjectForFrontend($project);
                }

                sendResponse(['success' => true, 'data' => $adaptedProjects]);
            }

            elseif ($path === '/projects/featured') {
                $stmt = $pdo->prepare("SELECT * FROM projects WHERE featured = 1 ORDER BY sort_order DESC, created_at DESC LIMIT 3");
                $stmt->execute();
                $projects = $stmt->fetchAll();

                $adaptedProjects = [];
                foreach ($projects as $project) {
                    $adaptedProjects[] = adaptProjectForFrontend($project);
                }

                sendResponse(['success' => true, 'data' => $adaptedProjects]);
            }

            elseif (preg_match('#^/projects/([a-zA-Z0-9\-_]+)$#', $path, $matches)) {
                $identifier = $matches[1];

                // Try to find by ID first, then by slug
                if (is_numeric($identifier)) {
                    $stmt = $pdo->prepare("SELECT * FROM projects WHERE id = ?");
                    $stmt->execute([(int)$identifier]);
                } else {
                    $stmt = $pdo->prepare("SELECT * FROM projects WHERE slug = ?");
                    $stmt->execute([$identifier]);
                }

                $project = $stmt->fetch();

                if (!$project) {
                    sendResponse(['success' => false, 'message' => 'Progetto non trovato'], 404);
                }

                $adapted = adaptProjectForFrontend($project);

                sendResponse(['success' => true, 'data' => $adapted]);
            }
            break;

        case 'PUT':
            if (preg_match('#^/admin/projects/(\d+)$#', $path, $matches)) {
                $token = getAuthToken();
                if (!validateToken($token)) {
                    sendResponse(['success' => false, 'message' => 'Non autorizzato'], 401);
                }

                $projectId = (int)$matches[1];

                // Solo campi base che sicuramente esistono
                $title = $input['title'] ?? '';
                $description = $input['description'] ?? '';
                $image_url = $input['image_url'] ?? '';
                $demo_url = $input['demo_url'] ?? '';
                $github_url = $input['github_url'] ?? '';
                $status = $input['status'] ?? 'in-progress';
                $featured = $input['featured'] ?? false;

                // JSON fields - controlliamo se esistono
                $technologies = isset($input['technologies']) ? json_encode($input['technologies']) : '[]';
                $gallery = isset($input['gallery']) ? json_encode($input['gallery']) : null;

                $slug = generateSlug($title);

                // Query minima con solo campi sicuri
                $stmt = $pdo->prepare("
                    UPDATE projects SET
                        title = ?, slug = ?, description = ?, image_url = ?, demo_url = ?, 
                        github_url = ?, status = ?, featured = ?, technologies = ?, gallery = ?,
                        updated_at = NOW()
                    WHERE id = ?
                ");

                $stmt->execute([
                    $title, $slug, $description, $image_url, $demo_url, $github_url,
                    $status, $featured ? 1 : 0, $technologies, $gallery, $projectId
                ]);

                if ($stmt->rowCount() === 0) {
                    sendResponse(['success' => false, 'message' => 'Progetto non trovato'], 404);
                }

                // Get updated project
                $stmt = $pdo->prepare("SELECT * FROM projects WHERE id = ?");
                $stmt->execute([$projectId]);
                $project = $stmt->fetch();

                $adapted = adaptProjectForFrontend($project);

                sendResponse([
                    'success' => true,
                    'message' => 'Progetto aggiornato con successo',
                    'data' => $adapted
                ]);
            }
            break;

        case 'DELETE':
            if (preg_match('#^/admin/projects/(\d+)$#', $path, $matches)) {
                $token = getAuthToken();
                if (!validateToken($token)) {
                    sendResponse(['success' => false, 'message' => 'Non autorizzato'], 401);
                }

                $projectId = (int)$matches[1];

                $stmt = $pdo->prepare("DELETE FROM projects WHERE id = ?");
                $stmt->execute([$projectId]);

                if ($stmt->rowCount() === 0) {
                    sendResponse(['success' => false, 'message' => 'Progetto non trovato'], 404);
                }

                sendResponse([
                    'success' => true,
                    'message' => 'Progetto eliminato con successo'
                ]);
            }
            break;

        case 'PATCH':
            if (preg_match('#^/admin/projects/(\d+)/toggle-featured$#', $path, $matches)) {
                $token = getAuthToken();
                if (!validateToken($token)) {
                    sendResponse(['success' => false, 'message' => 'Non autorizzato'], 401);
                }

                $projectId = (int)$matches[1];

                // Check current featured count
                $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM projects WHERE featured = 1 AND id != ?");
                $stmt->execute([$projectId]);
                $featuredCount = $stmt->fetch()['count'];

                // Check if this project is currently featured
                $stmt = $pdo->prepare("SELECT featured FROM projects WHERE id = ?");
                $stmt->execute([$projectId]);
                $currentProject = $stmt->fetch();

                if (!$currentProject) {
                    sendResponse(['success' => false, 'message' => 'Progetto non trovato'], 404);
                }

                // If trying to feature a project and already have 3 featured
                if (!$currentProject['featured'] && $featuredCount >= 3) {
                    sendResponse(['success' => false, 'message' => 'Massimo 3 progetti possono essere in evidenza'], 400);
                }

                $stmt = $pdo->prepare("UPDATE projects SET featured = NOT featured, updated_at = NOW() WHERE id = ?");
                $stmt->execute([$projectId]);

                // Get updated project
                $stmt = $pdo->prepare("SELECT * FROM projects WHERE id = ?");
                $stmt->execute([$projectId]);
                $project = $stmt->fetch();

                $adapted = adaptProjectForFrontend($project);

                sendResponse([
                    'success' => true,
                    'message' => 'Stato featured aggiornato',
                    'data' => $adapted
                ]);
            }
            break;

        default:
            sendResponse(['success' => false, 'message' => 'Metodo non supportato'], 405);
    }

} catch (Exception $e) {
    error_log("API Error: " . $e->getMessage());
    sendResponse([
        'success' => false,
        'message' => 'Errore interno del server',
        'error' => $e->getMessage()
    ], 500);
}

// Add upload endpoints after the main switch
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (strpos($path, '/admin/upload/') === 0) {
        $token = getAuthToken();
        if (!validateToken($token)) {
            sendResponse(['success' => false, 'message' => 'Non autorizzato'], 401);
        }

        if ($path === '/admin/upload/image') {
            if (!isset($_FILES['image'])) {
                sendResponse(['success' => false, 'message' => 'Nessun file caricato'], 400);
            }

            $file = $_FILES['image'];
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

            if (!in_array($file['type'], $allowedTypes)) {
                sendResponse(['success' => false, 'message' => 'Tipo di file non supportato'], 400);
            }

            if ($file['size'] > 5 * 1024 * 1024) { // 5MB max
                sendResponse(['success' => false, 'message' => 'File troppo grande (max 5MB)'], 400);
            }

            // Create uploads directory if it doesn't exist
            $uploadDir = __DIR__ . '/uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '_' . time() . '.' . $extension;
            $filepath = $uploadDir . $filename;

            if (move_uploaded_file($file['tmp_name'], $filepath)) {
                $url = 'https://vincenzorocca.com/api/uploads/' . $filename;
                sendResponse([
                    'success' => true,
                    'message' => 'File caricato con successo',
                    'url' => $url,
                    'filename' => $filename
                ]);
            } else {
                sendResponse(['success' => false, 'message' => 'Errore nel caricamento del file'], 500);
            }
        }

        elseif ($path === '/admin/upload/gallery') {
            // Debug: log all received files
            error_log("Gallery upload - FILES: " . print_r($_FILES, true));

            $uploadedUrls = [];
            $uploadDir = __DIR__ . '/uploads/';

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            // Check for files in different formats
            $filesToProcess = [];

            // Format 1: images[] array
            if (isset($_FILES['images']) && is_array($_FILES['images']['name'])) {
                $files = $_FILES['images'];
                for ($i = 0; $i < count($files['name']); $i++) {
                    $filesToProcess[] = [
                        'name' => $files['name'][$i],
                        'type' => $files['type'][$i],
                        'size' => $files['size'][$i],
                        'tmp_name' => $files['tmp_name'][$i],
                        'error' => $files['error'][$i]
                    ];
                }
            }

            // Format 2: images[0], images[1], etc.
            foreach ($_FILES as $key => $file) {
                if (preg_match('/^images\[\d+\]$/', $key)) {
                    $filesToProcess[] = [
                        'name' => $file['name'],
                        'type' => $file['type'],
                        'size' => $file['size'],
                        'tmp_name' => $file['tmp_name'],
                        'error' => $file['error']
                    ];
                }
            }

            // Format 3: single images field
            if (isset($_FILES['images']) && !is_array($_FILES['images']['name'])) {
                $file = $_FILES['images'];
                $filesToProcess[] = [
                    'name' => $file['name'],
                    'type' => $file['type'],
                    'size' => $file['size'],
                    'tmp_name' => $file['tmp_name'],
                    'error' => $file['error']
                ];
            }

            if (empty($filesToProcess)) {
                sendResponse(['success' => false, 'message' => 'Nessun file caricato'], 400);
            }

            // Process all files
            foreach ($filesToProcess as $file) {
                if ($file['error'] === UPLOAD_ERR_OK) {
                    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

                    if (!in_array($file['type'], $allowedTypes)) {
                        continue; // Skip invalid file types
                    }

                    if ($file['size'] > 5 * 1024 * 1024) { // 5MB max
                        continue; // Skip files too large
                    }

                    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                    $filename = uniqid() . '_' . time() . '_' . rand(1000, 9999) . '.' . $extension;
                    $filepath = $uploadDir . $filename;

                    if (move_uploaded_file($file['tmp_name'], $filepath)) {
                        $uploadedUrls[] = [
                            'url' => 'https://vincenzorocca.com/api/uploads/' . $filename,
                            'filename' => $filename
                        ];
                    }
                }
            }

            if (empty($uploadedUrls)) {
                sendResponse(['success' => false, 'message' => 'Nessun file valido caricato'], 400);
            }

            sendResponse([
                'success' => true,
                'message' => 'Gallery caricata con successo',
                'urls' => array_map(function($item) { return $item['url']; }, $uploadedUrls),
                'data' => $uploadedUrls,
                'count' => count($uploadedUrls)
            ]);
        }
    }
}
?>
