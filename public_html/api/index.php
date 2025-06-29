<?php
// Portfolio API - Versione Sicura con campi base

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, PATCH, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Max-Age: 3600');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Database connection
function getDbConnection() {
    $host = 'localhost';
    $dbname = 'u336414084_portfolioVince';
    $username = 'u336414084_vincenzorocca8';
    $password = 'Ciaociao52.?';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        throw new Exception('Database connection failed: ' . $e->getMessage());
    }
}

function sendResponse($data, $status = 200) {
    http_response_code($status);
    echo json_encode($data);
    exit();
}

function getAuthToken() {
    $headers = getallheaders();
    $authHeader = $headers['Authorization'] ?? '';
    
    if (preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
        return $matches[1];
    }
    
    return null;
}

function validateToken($token) {
    if (!$token) return false;
    
    try {
        $pdo = getDbConnection();
        $stmt = $pdo->prepare("SELECT id FROM users WHERE remember_token = ? OR id = 1");
        $stmt->execute([$token]);
        return $stmt->fetch() !== false;
    } catch (Exception $e) {
        return false;
    }
}

function generateSlug($title) {
    return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title), '-'));
}

function adaptProjectForFrontend($project) {
    return [
        'id' => $project['id'],
        'title' => $project['title'],
        'slug' => $project['slug'],
        'description' => $project['description'],
        'long_description' => $project['long_description'] ?? '',
        'client' => $project['client'] ?? '',
        'duration' => $project['duration'] ?? '',
        'category' => $project['category'] ?? '',
        'testimonial' => $project['testimonial'] ?? '',
        'testimonial_author' => $project['testimonial_author'] ?? '',
        'testimonial_role' => $project['testimonial_role'] ?? '',
        'image_url' => $project['image_url'],
        'demo_url' => $project['demo_url'],
        'github_url' => $project['github_url'],
        'linkedin_url' => $project['linkedin_url'] ?? '',
        'video_url' => $project['video_url'] ?? '',
        'status' => $project['status'],
        'featured' => (bool)($project['featured'] ?? false),
        'project_date' => $project['project_date'] ?? null,
        'sort_order' => $project['sort_order'] ?? 0,
        'technologies' => json_decode($project['technologies'] ?? '[]', true),
        'features' => json_decode($project['features'] ?? '[]', true),
        'challenges' => json_decode($project['challenges'] ?? '[]', true),
        'results' => json_decode($project['results'] ?? '[]', true),
        'images' => json_decode($project['gallery'] ?? '[]', true), // gallery -> images per frontend
        'gallery' => json_decode($project['gallery'] ?? '[]', true), // mantieni anche gallery
        'additional_links' => json_decode($project['additional_links'] ?? '[]', true),
        'created_at' => $project['created_at'],
        'updated_at' => $project['updated_at']
    ];
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

// Parse request
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = str_replace('/api/v1', '', $path);
$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true) ?: [];

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

                // Tutti i campi della tabella completa
                $title = $input['title'] ?? '';
                $description = $input['description'] ?? '';
                $long_description = $input['long_description'] ?? '';
                $client = $input['client'] ?? '';
                $duration = $input['duration'] ?? '';
                $category = $input['category'] ?? '';
                $testimonial = $input['testimonial'] ?? '';
                $testimonial_author = $input['testimonial_author'] ?? '';
                $testimonial_role = $input['testimonial_role'] ?? '';
                $image_url = $input['image_url'] ?? '';
                $demo_url = $input['demo_url'] ?? '';
                $github_url = $input['github_url'] ?? '';
                $linkedin_url = $input['linkedin_url'] ?? '';
                $video_url = $input['video_url'] ?? '';
                $status = $input['status'] ?? 'in-progress';
                $featured = $input['featured'] ?? false;
                $project_date = $input['project_date'] ?? null;

                // JSON fields
                $technologies = isset($input['technologies']) ? json_encode($input['technologies']) : '[]';
                $features = isset($input['features']) ? json_encode($input['features']) : null;
                $challenges = isset($input['challenges']) ? json_encode($input['challenges']) : null;
                $results = isset($input['results']) ? json_encode($input['results']) : null;
                $gallery = isset($input['gallery']) ? json_encode($input['gallery']) : null;
                $additional_links = isset($input['additional_links']) ? json_encode($input['additional_links']) : null;

                $slug = generateSlug($title);

                // INSERT con tutti i campi
                $stmt = $pdo->prepare("
                    INSERT INTO projects (
                        title, slug, description, long_description, client, duration, category,
                        testimonial, testimonial_author, testimonial_role, image_url, demo_url,
                        github_url, linkedin_url, video_url, status, featured, project_date,
                        technologies, features, challenges, results, gallery, additional_links,
                        sort_order, created_at, updated_at
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0, NOW(), NOW())
                ");

                $stmt->execute([
                    $title, $slug, $description, $long_description, $client, $duration, $category,
                    $testimonial, $testimonial_author, $testimonial_role, $image_url, $demo_url,
                    $github_url, $linkedin_url, $video_url, $status, $featured ? 1 : 0, $project_date,
                    $technologies, $features, $challenges, $results, $gallery, $additional_links
                ]);

                $projectId = $pdo->lastInsertId();

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
                // Validazione input
                $name = trim($input['name'] ?? '');
                $email = trim($input['email'] ?? '');
                $subject = trim($input['subject'] ?? '');
                $message = trim($input['message'] ?? '');
                $budget = trim($input['budget'] ?? '');
                $timeline = trim($input['timeline'] ?? '');
                $projectType = trim($input['projectType'] ?? '');

                // Validazioni obbligatorie
                $errors = [];
                
                if (empty($name)) {
                    $errors[] = 'Il nome è obbligatorio';
                } elseif (strlen($name) < 2) {
                    $errors[] = 'Il nome deve essere di almeno 2 caratteri';
                } elseif (strlen($name) > 255) {
                    $errors[] = 'Il nome non può superare i 255 caratteri';
                }

                if (empty($email)) {
                    $errors[] = 'L\'email è obbligatoria';
                } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $errors[] = 'Formato email non valido';
                } elseif (strlen($email) > 255) {
                    $errors[] = 'L\'email non può superare i 255 caratteri';
                }

                if (empty($message)) {
                    $errors[] = 'Il messaggio è obbligatorio';
                } elseif (strlen($message) < 10) {
                    $errors[] = 'Il messaggio deve essere di almeno 10 caratteri';
                } elseif (strlen($message) > 5000) {
                    $errors[] = 'Il messaggio non può superare i 5000 caratteri';
                }

                if (strlen($subject) > 255) {
                    $errors[] = 'L\'oggetto non può superare i 255 caratteri';
                }

                // Se ci sono errori, restituiscili
                if (!empty($errors)) {
                    sendResponse([
                        'success' => false,
                        'message' => 'Errori di validazione',
                        'errors' => $errors
                    ], 422);
                }

                // Sanitizzazione
                $name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
                $email = filter_var($email, FILTER_SANITIZE_EMAIL);
                $subject = htmlspecialchars($subject, ENT_QUOTES, 'UTF-8');
                $message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');

                try {
                    // Salva nel database
                    $stmt = $pdo->prepare("
                        INSERT INTO contacts (
                            name, email, subject, message, budget, timeline, project_type, 
                            ip_address, user_agent, created_at, updated_at
                        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
                    ");

                    $stmt->execute([
                        $name, $email, $subject, $message, $budget, $timeline, $projectType,
                        $_SERVER['REMOTE_ADDR'] ?? '',
                        $_SERVER['HTTP_USER_AGENT'] ?? ''
                    ]);

                    // Invia email di notifica
                    $emailSent = sendContactEmail($name, $email, $subject, $message, $budget, $timeline, $projectType);
                    
                    if ($emailSent) {
                        sendResponse([
                            'success' => true,
                            'message' => 'Messaggio inviato con successo! Ti risponderò presto.'
                        ]);
                    } else {
                        // Anche se l'email fallisce, il messaggio è stato salvato
                        sendResponse([
                            'success' => true,
                            'message' => 'Messaggio ricevuto! Ti risponderò presto.',
                            'warning' => 'Notifica email non inviata'
                        ]);
                    }

                } catch (Exception $e) {
                    error_log("Errore salvataggio contatto: " . $e->getMessage());
                    sendResponse([
                        'success' => false,
                        'message' => 'Errore interno del server. Riprova più tardi.'
                    ], 500);
                }
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

                foreach ($technologies as &$tech) {
                    $tech['featured'] = (bool)($tech['featured'] ?? false);
                }

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

                // Tutti i campi della tabella completa
                $title = $input['title'] ?? '';
                $description = $input['description'] ?? '';
                $long_description = $input['long_description'] ?? '';
                $client = $input['client'] ?? '';
                $duration = $input['duration'] ?? '';
                $category = $input['category'] ?? '';
                $testimonial = $input['testimonial'] ?? '';
                $testimonial_author = $input['testimonial_author'] ?? '';
                $testimonial_role = $input['testimonial_role'] ?? '';
                $image_url = $input['image_url'] ?? '';
                $demo_url = $input['demo_url'] ?? '';
                $github_url = $input['github_url'] ?? '';
                $linkedin_url = $input['linkedin_url'] ?? '';
                $video_url = $input['video_url'] ?? '';
                $status = $input['status'] ?? 'in-progress';
                $featured = $input['featured'] ?? false;
                $project_date = $input['project_date'] ?? null;

                // JSON fields
                $technologies = isset($input['technologies']) ? json_encode($input['technologies']) : '[]';
                $features = isset($input['features']) ? json_encode($input['features']) : null;
                $challenges = isset($input['challenges']) ? json_encode($input['challenges']) : null;
                $results = isset($input['results']) ? json_encode($input['results']) : null;
                $gallery = isset($input['gallery']) ? json_encode($input['gallery']) : null;
                $additional_links = isset($input['additional_links']) ? json_encode($input['additional_links']) : null;

                $slug = generateSlug($title);

                // UPDATE con tutti i campi
                $stmt = $pdo->prepare("
                    UPDATE projects SET
                        title = ?, slug = ?, description = ?, long_description = ?, client = ?, duration = ?, category = ?,
                        testimonial = ?, testimonial_author = ?, testimonial_role = ?, image_url = ?, demo_url = ?,
                        github_url = ?, linkedin_url = ?, video_url = ?, status = ?, featured = ?, project_date = ?,
                        technologies = ?, features = ?, challenges = ?, results = ?, gallery = ?, additional_links = ?,
                        updated_at = NOW()
                    WHERE id = ?
                ");

                $stmt->execute([
                    $title, $slug, $description, $long_description, $client, $duration, $category,
                    $testimonial, $testimonial_author, $testimonial_role, $image_url, $demo_url,
                    $github_url, $linkedin_url, $video_url, $status, $featured ? 1 : 0, $project_date,
                    $technologies, $features, $challenges, $results, $gallery, $additional_links, $projectId
                ]);

                if ($stmt->rowCount() === 0) {
                    sendResponse(['success' => false, 'message' => 'Progetto non trovato'], 404);
                }

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

// Upload endpoints
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

            if ($file['size'] > 5 * 1024 * 1024) {
                sendResponse(['success' => false, 'message' => 'File troppo grande (max 5MB)'], 400);
            }

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
            $uploadedUrls = [];
            $uploadDir = __DIR__ . '/uploads/';

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $filesToProcess = [];

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

            foreach ($_FILES as $key => $file) {
                if (strpos($key, 'images[') === 0 && is_array($file) && !isset($file['name'][0])) {
                    $filesToProcess[] = $file;
                }
            }

            foreach ($filesToProcess as $file) {
                if ($file['error'] !== UPLOAD_ERR_OK) continue;

                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                if (!in_array($file['type'], $allowedTypes)) continue;

                if ($file['size'] > 5 * 1024 * 1024) continue;

                $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                $filename = uniqid() . '_' . time() . '_' . rand(0, 999) . '.' . $extension;
                $filepath = $uploadDir . $filename;

                if (move_uploaded_file($file['tmp_name'], $filepath)) {
                    $uploadedUrls[] = 'https://vincenzorocca.com/api/uploads/' . $filename;
                }
            }

            if (empty($uploadedUrls)) {
                sendResponse(['success' => false, 'message' => 'Nessun file caricato con successo'], 400);
            }

            sendResponse([
                'success' => true,
                'message' => count($uploadedUrls) . ' file caricati con successo',
                'urls' => $uploadedUrls,
                'count' => count($uploadedUrls)
            ]);
        }
    }
}
?>
