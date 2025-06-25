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
    echo json_encode(['success' => false, 'error' => 'Database connection failed']);
    exit;
}

// Check authentication - più permissiva per evitare logout
$authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
if (!str_starts_with($authHeader, 'Bearer ')) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Token mancante']);
    exit;
}

$token = substr($authHeader, 7);
if (empty($token) || strlen($token) < 10) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Token non valido']);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

try {
    switch($method) {
        case 'GET':
            // Get all projects or specific project
            $projectId = $_GET['id'] ?? null;
            
            if ($projectId) {
                // Get specific project
                $stmt = $pdo->prepare("SELECT * FROM projects WHERE id = ?");
                $stmt->execute([$projectId]);
                $project = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($project) {
                    // Parse JSON fields for React
                    if ($project['technologies']) {
                        $project['technologies'] = json_decode($project['technologies'], true);
                    }
                    if ($project['features']) {
                        $project['features'] = json_decode($project['features'], true);
                    }
                    if ($project['gallery']) {
                        $project['gallery'] = json_decode($project['gallery'], true);
                    }
                    
                    echo json_encode(['success' => true, 'data' => $project]);
                } else {
                    http_response_code(404);
                    echo json_encode(['success' => false, 'message' => 'Progetto non trovato']);
                }
            } else {
                // Get all projects
                $stmt = $pdo->query("SELECT * FROM projects ORDER BY created_at DESC");
                $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                // Parse JSON fields for each project
                foreach ($projects as &$project) {
                    if ($project['technologies']) {
                        $project['technologies'] = json_decode($project['technologies'], true);
                    }
                    if ($project['features']) {
                        $project['features'] = json_decode($project['features'], true);
                    }
                    if ($project['gallery']) {
                        $project['gallery'] = json_decode($project['gallery'], true);
                    }
                }
                
                echo json_encode(['success' => true, 'data' => $projects]);
            }
            break;
            
        case 'POST':
            // Create new project
            $input = json_decode(file_get_contents('php://input'), true);
            $title = $input['name'] ?? $input['title'] ?? '';
            
            if (empty($title) || empty($input['description'])) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Titolo e descrizione sono obbligatori']);
                break;
            }
            
            // Convert technologies to JSON if string
            $technologies = $input['technologies'] ?? [];
            if (is_string($technologies) && !empty($technologies)) {
                $techArray = array_map('trim', explode(',', $technologies));
                $technologies = $techArray;
            }
            
            // Generate slug
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
            $originalSlug = $slug;
            $counter = 1;
            
            // Ensure unique slug
            while (true) {
                $stmt = $pdo->prepare("SELECT id FROM projects WHERE slug = ?");
                $stmt->execute([$slug]);
                if (!$stmt->fetch()) break;
                $slug = $originalSlug . '-' . $counter++;
            }
            
            $stmt = $pdo->prepare("INSERT INTO projects (title, slug, description, long_description, demo_url, github_url, image_url, technologies, category, status, featured, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
            $stmt->execute([
                $title,
                $slug,
                $input['description'] ?? '',
                $input['long_description'] ?? '',
                $input['demo_url'] ?? '',
                $input['github_url'] ?? '',
                $input['image_url'] ?? '',
                json_encode($technologies),
                $input['category'] ?? 'web',
                $input['status'] ?? 'completed',
                $input['featured'] ?? 0
            ]);
            
            $projectId = $pdo->lastInsertId();
            echo json_encode(['success' => true, 'message' => 'Progetto creato con successo', 'data' => ['id' => $projectId]]);
            break;
            
        case 'PUT':
            // Update project
            $projectId = $_GET['id'] ?? 0;
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (!$projectId) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'ID progetto mancante']);
                break;
            }
            
            $title = $input['name'] ?? $input['title'] ?? '';
            
            // Convert technologies to JSON if string
            $technologies = $input['technologies'] ?? [];
            if (is_string($technologies) && !empty($technologies)) {
                $techArray = array_map('trim', explode(',', $technologies));
                $technologies = $techArray;
            }
            
            $stmt = $pdo->prepare("UPDATE projects SET title = ?, description = ?, long_description = ?, demo_url = ?, github_url = ?, image_url = ?, technologies = ?, category = ?, status = ?, featured = ?, updated_at = NOW() WHERE id = ?");
            $stmt->execute([
                $title,
                $input['description'] ?? '',
                $input['long_description'] ?? '',
                $input['demo_url'] ?? '',
                $input['github_url'] ?? '',
                $input['image_url'] ?? '',
                json_encode($technologies),
                $input['category'] ?? 'web',
                $input['status'] ?? 'completed',
                $input['featured'] ?? 0,
                $projectId
            ]);
            
            echo json_encode(['success' => true, 'message' => 'Progetto aggiornato con successo']);
            break;
            
        case 'DELETE':
            // Delete project
            $projectId = $_GET['id'] ?? 0;
            
            if (!$projectId) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'ID progetto mancante']);
                break;
            }
            
            $stmt = $pdo->prepare("DELETE FROM projects WHERE id = ?");
            $stmt->execute([$projectId]);
            
            echo json_encode(['success' => true, 'message' => 'Progetto eliminato con successo']);
            break;
            
        default:
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Metodo non supportato']);
            break;
    }
    
} catch(Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Server error: ' . $e->getMessage()]);
}
?> 