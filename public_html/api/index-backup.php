<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: https://vincenzorocca.com");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, Accept");
header("Access-Control-Allow-Credentials: true");

if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(200);
    exit();
}

$request_uri = $_SERVER["REQUEST_URI"];
$method = $_SERVER["REQUEST_METHOD"];

// GET /api/v1/technologies
if (strpos($request_uri, "/api/v1/technologies") !== false && $method === "GET") {
    echo json_encode([
        "success" => true,
        "data" => [
            ["id" => 1, "name" => "React", "category" => "frontend", "proficiency" => 90],
            ["id" => 2, "name" => "Laravel", "category" => "backend", "proficiency" => 85],
            ["id" => 3, "name" => "JavaScript", "category" => "programming", "proficiency" => 95],
            ["id" => 4, "name" => "PHP", "category" => "programming", "proficiency" => 80],
            ["id" => 5, "name" => "MySQL", "category" => "database", "proficiency" => 75],
            ["id" => 6, "name" => "Node.js", "category" => "backend", "proficiency" => 70]
        ]
    ]);
    exit();
}

// GET /api/v1/projects
if (strpos($request_uri, "/api/v1/projects") !== false && $method === "GET") {
    echo json_encode([
        "success" => true,
        "data" => [
            [
                "id" => 1,
                "title" => "Portfolio React",
                "description" => "Il mio portfolio personale realizzato con React e Laravel",
                "featured" => true,
                "technologies" => [1, 2, 3, 4],
                "image_url" => "https://via.placeholder.com/600x400",
                "github_url" => "https://github.com/vincenzorocca/portfolio",
                "live_url" => "https://vincenzorocca.com"
            ],
            [
                "id" => 2,
                "title" => "E-commerce Laravel",
                "description" => "Sistema e-commerce completo con Laravel e Vue.js",
                "featured" => false,
                "technologies" => [2, 4, 5],
                "image_url" => "https://via.placeholder.com/600x400",
                "github_url" => "https://github.com/vincenzorocca/ecommerce"
            ],
            [
                "id" => 3,
                "title" => "App Mobile React Native",
                "description" => "Applicazione mobile per gestione attività",
                "featured" => true,
                "technologies" => [3, 6],
                "image_url" => "https://via.placeholder.com/600x400",
                "github_url" => "https://github.com/vincenzorocca/mobile-app"
            ]
        ]
    ]);
    exit();
}

// POST /api/v1/admin/projects
if (strpos($request_uri, "/api/v1/admin/projects") !== false && $method === "POST") {
    $input = json_decode(file_get_contents("php://input"), true);
    
    echo json_encode([
        "success" => true,
        "message" => "Progetto creato con successo",
        "data" => [
            "id" => 4,
            "title" => $input["title"] ?? "Nuovo Progetto",
            "description" => $input["description"] ?? "Descrizione...",
            "featured" => $input["featured"] ?? false,
            "technologies" => $input["technologies"] ?? [],
            "created_at" => date("Y-m-d H:i:s")
        ]
    ]);
    exit();
}

// GET /api/v1/auth/me con token
if (strpos($request_uri, "/api/v1/auth/me") !== false && $method === "GET") {
    $headers = getallheaders();
    $token = null;
    
    if (isset($headers['Authorization'])) {
        $token = str_replace('Bearer ', '', $headers['Authorization']);
    }
    
    if (!$token) {
        http_response_code(401);
        echo json_encode(["message" => "Token required"]);
        exit();
    }
    
    echo json_encode([
        "success" => true,
        "data" => [
            "id" => 1,
            "name" => "Vincenzo Rocca",
            "email" => "vincenzorocca88@gmail.com",
            "is_admin" => true,
            "created_at" => "2024-01-01T00:00:00.000000Z",
            "updated_at" => "2024-01-01T00:00:00.000000Z"
        ]
    ]);
    exit();
}

// POST /api/v1/auth/login
if (strpos($request_uri, "/api/v1/auth/login") !== false && $method === "POST") {
    $input = json_decode(file_get_contents("php://input"), true);
    
    if (!$input || !isset($input["email"]) || !isset($input["password"])) {
        http_response_code(400);
        echo json_encode(["message" => "Email and password required"]);
        exit();
    }
    
    if ($input["email"] === "vincenzorocca88@gmail.com" && $input["password"] === "admin123") {
        echo json_encode([
            "success" => true,
            "user" => [
                "id" => 1,
                "email" => "vincenzorocca88@gmail.com",
                "name" => "Vincenzo Rocca",
                "is_admin" => true
            ],
            "token" => "valid_token_" . time(),
            "expires_in" => 3600
        ]);
    } else {
        http_response_code(401);
        echo json_encode(["message" => "Invalid credentials"]);
    }
    exit();
}

// Default response
echo json_encode([
    "status" => "interceptor_active_FULL",
    "request_uri" => $request_uri,
    "method" => $method,
    "timestamp" => date("Y-m-d H:i:s"),
    "supported_endpoints" => [
        "/api/v1/auth/login (POST) ✅",
        "/api/v1/auth/me (GET) ✅", 
        "/api/v1/technologies (GET) ✅",
        "/api/v1/projects (GET) ✅",
        "/api/v1/admin/projects (POST) ✅"
    ]
]);
?> 