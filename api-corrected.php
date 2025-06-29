<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Configurazione database - CREDENZIALI CORRETTE
$host = 'localhost';
$dbname = 'u336414084_portfolioVince';
$username = 'u336414084_vincenzorocca8';
$password = 'Ciaociao52.?';

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
        
    case 'v1/projects/featured':
        if ($method === 'GET') {
            getFeaturedProjects($pdo);
        }
        break;
        
    case 'v1/admin/projects':
        if ($method === 'POST') {
            createProject($pdo);
        }
        break;
        
    case 'v1/contacts':
        if ($method === 'POST') {
            sendContact($pdo);
        }
        break;
        
    case 'v1/admin/upload/image':
        if ($method === 'POST') {
            uploadImage();
        }
        break;
        
    case 'v1/admin/upload/video':
        if ($method === 'POST') {
            uploadVideo();
        }
        break;
        
    case 'v1/admin/upload/gallery':
        if ($method === 'POST') {
            uploadGallery();
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

function getFeaturedProjects($pdo) {
    try {
        $stmt = $pdo->query("SELECT * FROM projects WHERE featured = 1 ORDER BY created_at DESC");
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
        
        // Query corretta con colonne esistenti
        $stmt = $pdo->prepare("
            INSERT INTO projects (
                title, slug, description, long_description, client, duration, 
                category, technologies, features, challenges, results, 
                testimonial, testimonial_author, testimonial_role, image_url, 
                gallery, demo_url, github_url, linkedin_url, video_url, 
                additional_links, status, sort_order, featured, project_date, 
                created_at, updated_at
            ) VALUES (
                ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW()
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
            json_encode($input['challenges'] ?? []),
            json_encode($input['results'] ?? []),
            $input['testimonial'] ?? '',
            $input['testimonial_author'] ?? '',
            $input['testimonial_role'] ?? '',
            $input['image_url'] ?? '',
            json_encode($input['gallery'] ?? []),
            $input['demo_url'] ?? '',
            $input['github_url'] ?? '',
            $input['linkedin_url'] ?? '',
            $input['video_url'] ?? '',
            json_encode($input['additional_links'] ?? []),
            $input['status'] ?? 'completed',
            $input['sort_order'] ?? 0,
            $input['featured'] ?? 0,
            $input['project_date'] ?? date('Y-m-d')
        ]);
        
        $projectId = $pdo->lastInsertId();
        
        // Risposta nel formato corretto per il frontend
        echo json_encode([
            'id' => $projectId,
            'title' => $input['title'] ?? 'Nuovo Progetto',
            'slug' => $slug,
            'message' => 'Project created successfully'
        ]);
        
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Database error', 'message' => $e->getMessage()]);
    }
}

function sendContact($pdo) {
    try {
        $input = json_decode(file_get_contents('php://input'), true);
        
        // Simula invio contatto
        echo json_encode([
            'success' => true,
            'message' => 'Message sent successfully'
        ]);
        
    } catch (Exception $e) {
        echo json_encode(['error' => 'Contact error', 'message' => $e->getMessage()]);
    }
}

function uploadImage() {
    // Simula upload immagine
    echo json_encode([
        'success' => true,
        'url' => 'https://via.placeholder.com/800x600/007bff/ffffff?text=Uploaded+Image',
        'message' => 'Image uploaded successfully'
    ]);
}

function uploadVideo() {
    // Simula upload video
    echo json_encode([
        'success' => true,
        'url' => 'https://sample-videos.com/zip/10/mp4/SampleVideo_1280x720_1mb.mp4',
        'message' => 'Video uploaded successfully'
    ]);
}

function uploadGallery() {
    // Simula upload galleria
    echo json_encode([
        'success' => true,
        'urls' => [
            'https://via.placeholder.com/400x300/007bff/ffffff?text=Gallery+1',
            'https://via.placeholder.com/400x300/28a745/ffffff?text=Gallery+2',
            'https://via.placeholder.com/400x300/dc3545/ffffff?text=Gallery+3'
        ],
        'message' => 'Gallery uploaded successfully'
    ]);
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