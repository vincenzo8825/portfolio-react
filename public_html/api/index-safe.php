<?php
// API Safe - usa solo campi esistenti nella tabella projects

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
        'image_url' => $project['image_url'],
        'demo_url' => $project['demo_url'],
        'github_url' => $project['github_url'],
        'status' => $project['status'],
        'featured' => (bool)($project['featured'] ?? false),
        'project_date' => $project['project_date'] ?? null,
        'technologies' => json_decode($project['technologies'] ?? '[]', true),
        'features' => json_decode($project['features'] ?? '[]', true),
        'challenges' => json_decode($project['challenges'] ?? '[]', true),
        'results' => json_decode($project['results'] ?? '[]', true),
        'images' => json_decode($project['gallery'] ?? '[]', true), // gallery -> images
        'additional_links' => json_decode($project['additional_links'] ?? '[]', true),
        'created_at' => $project['created_at'],
        'updated_at' => $project['updated_at']
    ];
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
            if ($path === '/admin/projects') {
                $token = getAuthToken();
                if (!validateToken($token)) {
                    sendResponse(['success' => false, 'message' => 'Non autorizzato'], 401);
                }

                // Solo campi sicuramente esistenti
                $title = $input['title'] ?? '';
                $description = $input['description'] ?? '';
                $image_url = $input['image_url'] ?? '';
                $demo_url = $input['demo_url'] ?? '';
                $github_url = $input['github_url'] ?? '';
                $status = $input['status'] ?? 'in-progress';
                $featured = $input['featured'] ?? false;
                $project_date = $input['project_date'] ?? null;

                // JSON fields che dovrebbero esistere
                $technologies = isset($input['technologies']) ? json_encode($input['technologies']) : '[]';
                $features = isset($input['features']) ? json_encode($input['features']) : null;
                $challenges = isset($input['challenges']) ? json_encode($input['challenges']) : null;
                $results = isset($input['results']) ? json_encode($input['results']) : null;
                $gallery = isset($input['gallery']) ? json_encode($input['gallery']) : null;
                $additional_links = isset($input['additional_links']) ? json_encode($input['additional_links']) : null;

                $slug = generateSlug($title);

                // Query con solo campi base
                $stmt = $pdo->prepare("
                    INSERT INTO projects (
                        title, slug, description, image_url, demo_url, github_url, 
                        status, featured, project_date, technologies, features, 
                        challenges, results, gallery, additional_links, sort_order, 
                        created_at, updated_at
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0, NOW(), NOW())
                ");

                $stmt->execute([
                    $title, $slug, $description, $image_url, $demo_url, $github_url,
                    $status, $featured ? 1 : 0, $project_date, $technologies, $features,
                    $challenges, $results, $gallery, $additional_links
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
            break;

        case 'GET':
            if ($path === '/projects') {
                $stmt = $pdo->prepare("SELECT * FROM projects ORDER BY sort_order DESC, created_at DESC");
                $stmt->execute();
                $projects = $stmt->fetchAll();

                $adaptedProjects = [];
                foreach ($projects as $project) {
                    $adaptedProjects[] = adaptProjectForFrontend($project);
                }

                sendResponse(['success' => true, 'data' => $adaptedProjects]);
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