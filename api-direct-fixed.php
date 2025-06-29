<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Configurazione database CORRETTA
$host = 'localhost';
$dbname = 'u336414084_portfolioVince';
$username = 'u336414084_portfolioVince';
$password = 'Ciaociao5.2';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database connection failed', 'message' => $e->getMessage()]);
    exit();
}

// Routing semplificato
$request_uri = $_SERVER['REQUEST_URI'] ?? '';
$path = parse_url($request_uri, PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Rimuovi /api/ dal path
$path = str_replace('/api/', '', $path);

switch ($path) {
    case 'v1/technologies':
        if ($method === 'GET') {
            getTechnologies($pdo);
        }
        break;
        
    case 'v1/projects':
        if ($method === 'GET') {
            getProjects($pdo);
        } elseif ($method === 'POST') {
            createProject($pdo);
        }
        break;
        
    case 'v1/auth/me':
        if ($method === 'GET') {
            getCurrentUser($pdo);
        }
        break;
        
    case 'v1/auth/login':
        if ($method === 'POST') {
            login($pdo);
        }
        break;
        
    default:
        echo json_encode(['error' => 'Endpoint not found', 'path' => $path]);
        break;
}

function getTechnologies($pdo) {
    try {
        $stmt = $pdo->query("SELECT * FROM technologies ORDER BY sort_order ASC, name ASC");
        $technologies = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($technologies);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Database error', 'message' => $e->getMessage()]);
    }
}

function getProjects($pdo) {
    try {
        $stmt = $pdo->query("SELECT * FROM projects ORDER BY created_at DESC");
        $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Decodifica JSON fields
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
        
        echo json_encode($projects);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Database error', 'message' => $e->getMessage()]);
    }
}

function createProject($pdo) {
    try {
        $input = json_decode(file_get_contents('php://input'), true);
        
        $stmt = $pdo->prepare("
            INSERT INTO projects (
                title, slug, description, long_description, client, duration, 
                category, technologies, features, challenges, results, 
                testimonial, image_url, gallery, demo_url, github_url, 
                linkedin_url, video_url, additional_links, project_date, 
                featured, status, created_at, updated_at
            ) VALUES (
                ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW()
            )
        ");
        
        $slug = strtolower(str_replace(' ', '-', $input['title'] ?? 'nuovo-progetto'));
        
        $stmt->execute([
            $input['title'] ?? 'Nuovo Progetto',
            $slug,
            $input['description'] ?? '',
            $input['long_description'] ?? '',
            $input['client'] ?? '',
            $input['duration'] ?? '',
            $input['category'] ?? 'web',
            json_encode($input['technologies'] ?? []),
            json_encode($input['features'] ?? []),
            $input['challenges'] ?? '',
            $input['results'] ?? '',
            $input['testimonial'] ?? '',
            $input['image_url'] ?? '',
            json_encode($input['gallery'] ?? []),
            $input['demo_url'] ?? '',
            $input['github_url'] ?? '',
            $input['linkedin_url'] ?? '',
            $input['video_url'] ?? '',
            json_encode($input['additional_links'] ?? []),
            $input['project_date'] ?? date('Y-m-d'),
            $input['featured'] ?? 0,
            $input['status'] ?? 'completed'
        ]);
        
        $projectId = $pdo->lastInsertId();
        echo json_encode(['id' => $projectId, 'message' => 'Project created successfully']);
        
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Database error', 'message' => $e->getMessage()]);
    }
}

function getCurrentUser($pdo) {
    // Simulazione utente admin per testing
    echo json_encode([
        'id' => 1,
        'name' => 'Vincenzo Rocca',
        'email' => 'vincenzorocca88@gmail.com',
        'is_admin' => true
    ]);
}

function login($pdo) {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if ($input['email'] === 'vincenzorocca88@gmail.com' && $input['password'] === 'admin123') {
        echo json_encode([
            'user' => [
                'id' => 1,
                'name' => 'Vincenzo Rocca',
                'email' => 'vincenzorocca88@gmail.com',
                'is_admin' => true
            ],
            'token' => 'mock-token-' . time()
        ]);
    } else {
        http_response_code(401);
        echo json_encode(['error' => 'Invalid credentials']);
    }
}
?> 