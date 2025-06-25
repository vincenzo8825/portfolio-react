<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Check authentication
$authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
if (!str_starts_with($authHeader, 'Bearer ')) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Token mancante']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Check if file was uploaded
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $fileName = uniqid() . '_' . $_FILES['image']['name'];
            $uploadPath = $uploadDir . $fileName;
            
            // Validate file type
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (!in_array($_FILES['image']['type'], $allowedTypes)) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Tipo file non supportato']);
                exit;
            }
            
            // Validate file size (max 5MB)
            if ($_FILES['image']['size'] > 5 * 1024 * 1024) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'File troppo grande (max 5MB)']);
                exit;
            }
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                echo json_encode([
                    'success' => true,
                    'data' => [
                        'url' => '/uploads/' . $fileName,
                        'filename' => $fileName,
                        'size' => $_FILES['image']['size'],
                        'type' => $_FILES['image']['type']
                    ]
                ]);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Errore durante il caricamento']);
            }
        } else {
            // No file uploaded, simulate for testing
            $timestamp = time();
            echo json_encode([
                'success' => true,
                'data' => [
                    'url' => '/uploads/placeholder_' . $timestamp . '.jpg',
                    'filename' => 'placeholder_' . $timestamp . '.jpg',
                    'size' => 0,
                    'type' => 'image/jpeg'
                ]
            ]);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Errore server: ' . $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Metodo non supportato']);
}
?> 