<?php
// API Minimalista per test login senza Laravel
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: https://vincenzorocca.com');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
header('Access-Control-Allow-Credentials: true');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Error handling
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    // Database connection
    $pdo = new PDO(
        'mysql:host=localhost;dbname=u336414084_portfolioVince', 
        'u336414084_vincenzorocca8', 
        'Ciaociao52.?',
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );

    // Route handling
    $path = $_SERVER['PATH_INFO'] ?? $_GET['path'] ?? '';
    $method = $_SERVER['REQUEST_METHOD'];

    switch ($path) {
        case '/test':
            if ($method === 'GET') {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'API minimalista funzionante',
                    'timestamp' => date('Y-m-d H:i:s'),
                    'server' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown'
                ]);
            }
            break;

        case '/login':
            if ($method === 'POST') {
                $input = json_decode(file_get_contents('php://input'), true);
                
                if (!$input || !isset($input['email']) || !isset($input['password'])) {
                    http_response_code(400);
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Email e password richiesti'
                    ]);
                    break;
                }

                // Check user
                $stmt = $pdo->prepare("SELECT id, email, password, name, is_admin FROM users WHERE email = ?");
                $stmt->execute([$input['email']]);
                $user = $stmt->fetch();

                if (!$user) {
                    http_response_code(401);
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Credenziali non valide'
                    ]);
                    break;
                }

                // Verify password
                if (!password_verify($input['password'], $user['password'])) {
                    http_response_code(401);
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Credenziali non valide'
                    ]);
                    break;
                }

                // Login successful
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Login effettuato con successo',
                    'user' => [
                        'id' => $user['id'],
                        'email' => $user['email'],
                        'name' => $user['name'],
                        'is_admin' => (bool)$user['is_admin']
                    ],
                    'token' => 'dummy_token_' . time() // Token temporaneo per test
                ]);
            }
            break;

        case '/users':
            if ($method === 'GET') {
                $stmt = $pdo->query("SELECT id, email, name, is_admin, created_at FROM users ORDER BY created_at DESC");
                $users = $stmt->fetchAll();
                
                echo json_encode([
                    'status' => 'success',
                    'users' => $users
                ]);
            }
            break;

        case '/projects':
            if ($method === 'GET') {
                $stmt = $pdo->query("SELECT id, title, description, created_at FROM projects ORDER BY created_at DESC");
                $projects = $stmt->fetchAll();
                
                echo json_encode([
                    'status' => 'success',
                    'projects' => $projects
                ]);
            }
            break;

        case '/database-info':
            if ($method === 'GET') {
                // Get table info
                $tables = [];
                $stmt = $pdo->query("SHOW TABLES");
                while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
                    $table_name = $row[0];
                    $count_stmt = $pdo->query("SELECT COUNT(*) as count FROM `$table_name`");
                    $count = $count_stmt->fetch()['count'];
                    $tables[$table_name] = $count;
                }

                echo json_encode([
                    'status' => 'success',
                    'database' => 'u336414084_portfolioVince',
                    'tables' => $tables
                ]);
            }
            break;

        default:
            // Show available routes
            echo json_encode([
                'status' => 'info',
                'message' => 'API Minimalista Portfolio',
                'available_routes' => [
                    'GET /test' => 'Test di base',
                    'POST /login' => 'Login con email/password',
                    'GET /users' => 'Lista utenti',
                    'GET /projects' => 'Lista progetti',
                    'GET /database-info' => 'Info database'
                ],
                'usage' => [
                    'base_url' => 'https://vincenzorocca.com/test-api-minimalista.php',
                    'example_login' => [
                        'url' => 'https://vincenzorocca.com/test-api-minimalista.php/login',
                        'method' => 'POST',
                        'data' => [
                            'email' => 'vincenzorocca88@gmail.com',
                            'password' => 'admin123'
                        ]
                    ]
                ]
            ]);
            break;
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Errore database',
        'error' => $e->getMessage()
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Errore del server',
        'error' => $e->getMessage()
    ]);
}
?> 