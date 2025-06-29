<?php
/**
 * TEST ADMIN AUTHENTICATION & CRUD OPERATIONS
 * Test completo per login admin e operazioni CRUD
 */

header('Content-Type: text/html; charset=utf-8');
error_reporting(E_ALL);
ini_set('display_errors', 1);

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>🔐 Admin Auth & CRUD Test</title>
    <style>
        body { font-family: Arial, sans-serif; background: #1a1a1a; color: #e0e0e0; margin: 20px; }
        .container { max-width: 1000px; margin: 0 auto; }
        .header { background: #2563eb; padding: 20px; border-radius: 8px; text-align: center; margin-bottom: 20px; }
        .test-section { background: #2a2a2a; border: 1px solid #444; border-radius: 8px; margin: 15px 0; overflow: hidden; }
        .test-header { background: #333; padding: 15px; border-bottom: 1px solid #444; }
        .test-body { padding: 15px; }
        .test-item { margin: 10px 0; padding: 12px; border-radius: 6px; border-left: 4px solid #666; }
        .test-item.success { background: #1a4d3a; border-left-color: #10b981; }
        .test-item.error { background: #4d1a1a; border-left-color: #ef4444; }
        .test-item.warning { background: #4d3a1a; border-left-color: #f59e0b; }
        .form-section { background: #333; padding: 15px; border-radius: 6px; margin: 15px 0; }
        .form-group { margin: 10px 0; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input, .form-group textarea { width: 100%; padding: 8px; border: 1px solid #555; background: #444; color: #e0e0e0; border-radius: 4px; }
        .btn { background: #2563eb; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; margin: 5px; }
        .btn:hover { background: #1d4ed8; }
        .btn.danger { background: #ef4444; }
        .btn.danger:hover { background: #dc2626; }
        .response { background: #1a1a1a; padding: 10px; border-radius: 4px; font-family: monospace; font-size: 0.9rem; margin-top: 10px; max-height: 200px; overflow-y: auto; }
        .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)); gap: 10px; margin: 15px 0; }
        .stat { background: #333; padding: 15px; border-radius: 6px; text-align: center; }
        .stat-number { font-size: 1.5rem; font-weight: bold; color: #10b981; }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>🔐 Admin Authentication & CRUD Test</h1>
        <p>Test completo per login admin e operazioni CRUD</p>
        <p><strong>Timestamp:</strong> <?= date('Y-m-d H:i:s') ?></p>
    </div>

    <?php
    $testResults = [];
    $authToken = null;
    
    function addTestResult($category, $name, $status, $message, $details = '') {
        global $testResults;
        $testResults[$category][] = [
            'name' => $name,
            'status' => $status,
            'message' => $message,
            'details' => $details,
            'time' => date('H:i:s')
        ];
        
        $icon = $status === 'success' ? '✅' : ($status === 'error' ? '❌' : '⚠️');
        echo "<div class='test-item $status'>";
        echo "<div><strong>$icon $name</strong></div>";
        echo "<div>$message</div>";
        if ($details) {
            echo "<div class='response'>" . htmlspecialchars($details) . "</div>";
        }
        echo "</div>";
    }
    
    function makeApiRequest($endpoint, $method = 'GET', $data = null, $headers = []) {
        $baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];
        $url = $baseUrl . $endpoint;
        
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 15,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => array_merge(['Content-Type: application/json'], $headers),
        ]);
        
        if ($data && in_array($method, ['POST', 'PUT', 'PATCH'])) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        return [
            'status' => $httpCode,
            'response' => $response,
            'error' => $error,
            'data' => json_decode($response, true),
            'success' => !$error && $httpCode >= 200 && $httpCode < 300
        ];
    }
    ?>

    <!-- TEST 1: API BASE ENDPOINTS -->
    <div class="test-section">
        <div class="test-header">
            <h3>🌐 API Base Endpoints</h3>
        </div>
        <div class="test-body">
            <?php
            $baseTests = [
                '/api/v1/test' => 'API Test Endpoint',
                '/api/v1/technologies' => 'Technologies Public API',
                '/api/v1/projects' => 'Projects Public API',
            ];
            
            foreach ($baseTests as $endpoint => $description) {
                $result = makeApiRequest($endpoint);
                
                if ($result['success']) {
                    addTestResult('base', $description, 'success', "Status {$result['status']}", 
                        $result['data'] ? json_encode($result['data'], JSON_PRETTY_PRINT) : $result['response']);
                } else {
                    addTestResult('base', $description, 'error', "Status {$result['status']}" . ($result['error'] ? " - {$result['error']}" : ''), 
                        $result['response']);
                }
            }
            ?>
        </div>
    </div>

    <!-- TEST 2: AUTHENTICATION -->
    <div class="test-section">
        <div class="test-header">
            <h3>🔐 Authentication Test</h3>
        </div>
        <div class="test-body">
            <div class="form-section">
                <h4>Admin Login Test</h4>
                <form id="loginForm" onsubmit="return false;">
                    <div class="form-group">
                        <label>Email:</label>
                        <input type="email" id="loginEmail" value="vincenzo@admin.it" required>
                    </div>
                    <div class="form-group">
                        <label>Password:</label>
                        <input type="password" id="loginPassword" value="admin123" required>
                    </div>
                    <button type="button" class="btn" onclick="testLogin()">🔐 Test Login</button>
                </form>
            </div>

            <?php
            // Test login automatico con credenziali default
            $loginData = [
                'email' => 'vincenzo@admin.it',
                'password' => 'admin123'
            ];
            
            $loginResult = makeApiRequest('/api/v1/auth/login', 'POST', $loginData);
            
            if ($loginResult['success'] && isset($loginResult['data']['token'])) {
                $authToken = $loginResult['data']['token'];
                addTestResult('auth', 'Admin Login', 'success', 'Login successful', 
                    "Token: " . substr($authToken, 0, 20) . "...");
                
                // Test token validation
                $meResult = makeApiRequest('/api/v1/auth/me', 'GET', null, ["Authorization: Bearer $authToken"]);
                if ($meResult['success']) {
                    addTestResult('auth', 'Token Validation', 'success', 'Token valid', 
                        json_encode($meResult['data'], JSON_PRETTY_PRINT));
                } else {
                    addTestResult('auth', 'Token Validation', 'error', "Status {$meResult['status']}", 
                        $meResult['response']);
                }
            } else {
                addTestResult('auth', 'Admin Login', 'error', "Status {$loginResult['status']}", 
                    $loginResult['response']);
            }
            ?>
        </div>
    </div>

    <!-- TEST 3: ADMIN CRUD OPERATIONS -->
    <?php if ($authToken): ?>
    <div class="test-section">
        <div class="test-header">
            <h3>📝 Admin CRUD Operations</h3>
        </div>
        <div class="test-body">
            <?php
            $authHeaders = ["Authorization: Bearer $authToken"];
            
            // Test Projects CRUD
            echo "<h4>📋 Projects CRUD</h4>";
            
            // Create Project
            $newProject = [
                'title' => 'Test Project ' . date('His'),
                'description' => 'Test project created by automated test',
                'technologies' => ['PHP', 'Laravel', 'React'],
                'status' => 'completed',
                'featured' => false
            ];
            
            $createResult = makeApiRequest('/api/v1/admin/projects', 'POST', $newProject, $authHeaders);
            if ($createResult['success']) {
                $projectId = $createResult['data']['id'] ?? null;
                addTestResult('crud', 'Create Project', 'success', 'Project created successfully', 
                    json_encode($createResult['data'], JSON_PRETTY_PRINT));
                
                if ($projectId) {
                    // Update Project
                    $updateData = [
                        'title' => 'Updated Test Project ' . date('His'),
                        'featured' => true
                    ];
                    
                    $updateResult = makeApiRequest("/api/v1/admin/projects/$projectId", 'PUT', $updateData, $authHeaders);
                    if ($updateResult['success']) {
                        addTestResult('crud', 'Update Project', 'success', 'Project updated successfully', 
                            json_encode($updateResult['data'], JSON_PRETTY_PRINT));
                    } else {
                        addTestResult('crud', 'Update Project', 'error', "Status {$updateResult['status']}", 
                            $updateResult['response']);
                    }
                    
                    // Delete Project
                    $deleteResult = makeApiRequest("/api/v1/admin/projects/$projectId", 'DELETE', null, $authHeaders);
                    if ($deleteResult['success']) {
                        addTestResult('crud', 'Delete Project', 'success', 'Project deleted successfully', 
                            $deleteResult['response']);
                    } else {
                        addTestResult('crud', 'Delete Project', 'error', "Status {$deleteResult['status']}", 
                            $deleteResult['response']);
                    }
                }
            } else {
                addTestResult('crud', 'Create Project', 'error', "Status {$createResult['status']}", 
                    $createResult['response']);
            }
            
            // Test Technologies CRUD
            echo "<h4>⚙️ Technologies CRUD</h4>";
            
            $newTech = [
                'name' => 'Test Tech ' . date('His'),
                'category' => 'testing',
                'icon' => 'test-icon',
                'color' => '#ff0000'
            ];
            
            $createTechResult = makeApiRequest('/api/v1/admin/technologies', 'POST', $newTech, $authHeaders);
            if ($createTechResult['success']) {
                $techId = $createTechResult['data']['id'] ?? null;
                addTestResult('crud', 'Create Technology', 'success', 'Technology created successfully', 
                    json_encode($createTechResult['data'], JSON_PRETTY_PRINT));
                
                if ($techId) {
                    // Delete Technology
                    $deleteTechResult = makeApiRequest("/api/v1/admin/technologies/$techId", 'DELETE', null, $authHeaders);
                    if ($deleteTechResult['success']) {
                        addTestResult('crud', 'Delete Technology', 'success', 'Technology deleted successfully', 
                            $deleteTechResult['response']);
                    } else {
                        addTestResult('crud', 'Delete Technology', 'error', "Status {$deleteTechResult['status']}", 
                            $deleteTechResult['response']);
                    }
                }
            } else {
                addTestResult('crud', 'Create Technology', 'error', "Status {$createTechResult['status']}", 
                    $createTechResult['response']);
            }
            
            // Test Contacts Management
            echo "<h4>📧 Contacts Management</h4>";
            
            $contactsResult = makeApiRequest('/api/v1/admin/contacts', 'GET', null, $authHeaders);
            if ($contactsResult['success']) {
                $contactsCount = count($contactsResult['data'] ?? []);
                addTestResult('crud', 'Get Contacts', 'success', "Retrieved $contactsCount contacts", 
                    json_encode($contactsResult['data'], JSON_PRETTY_PRINT));
            } else {
                addTestResult('crud', 'Get Contacts', 'error', "Status {$contactsResult['status']}", 
                    $contactsResult['response']);
            }
            
            // Test Contact Statistics
            $statsResult = makeApiRequest('/api/v1/admin/contacts/stats', 'GET', null, $authHeaders);
            if ($statsResult['success']) {
                addTestResult('crud', 'Contact Statistics', 'success', 'Statistics retrieved successfully', 
                    json_encode($statsResult['data'], JSON_PRETTY_PRINT));
            } else {
                addTestResult('crud', 'Contact Statistics', 'error', "Status {$statsResult['status']}", 
                    $statsResult['response']);
            }
            ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- TEST 4: CONTACT FORM -->
    <div class="test-section">
        <div class="test-header">
            <h3>📧 Contact Form Test</h3>
        </div>
        <div class="test-body">
            <div class="form-section">
                <h4>Test Contact Submission</h4>
                <form id="contactForm" onsubmit="return false;">
                    <div class="form-group">
                        <label>Name:</label>
                        <input type="text" id="contactName" value="Test User" required>
                    </div>
                    <div class="form-group">
                        <label>Email:</label>
                        <input type="email" id="contactEmail" value="test@example.com" required>
                    </div>
                    <div class="form-group">
                        <label>Message:</label>
                        <textarea id="contactMessage" rows="4" required>This is a test message from the automated test suite.</textarea>
                    </div>
                    <button type="button" class="btn" onclick="testContact()">📧 Test Contact</button>
                </form>
            </div>

            <?php
            // Test contact form submission
            $contactData = [
                'name' => 'Test User ' . date('His'),
                'email' => 'test@example.com',
                'message' => 'This is a test message from the automated test suite at ' . date('Y-m-d H:i:s')
            ];
            
            $contactResult = makeApiRequest('/api/v1/contacts', 'POST', $contactData);
            if ($contactResult['success']) {
                addTestResult('contact', 'Contact Form Submission', 'success', 'Contact submitted successfully', 
                    json_encode($contactResult['data'], JSON_PRETTY_PRINT));
            } else {
                addTestResult('contact', 'Contact Form Submission', 'error', "Status {$contactResult['status']}", 
                    $contactResult['response']);
            }
            ?>
        </div>
    </div>

    <!-- STATISTICHE -->
    <div class="stats">
        <?php
        $totalTests = 0;
        $successTests = 0;
        $errorTests = 0;
        $warningTests = 0;
        
        foreach ($testResults as $category => $tests) {
            foreach ($tests as $test) {
                $totalTests++;
                switch ($test['status']) {
                    case 'success': $successTests++; break;
                    case 'error': $errorTests++; break;
                    case 'warning': $warningTests++; break;
                }
            }
        }
        
        $successRate = $totalTests > 0 ? round(($successTests / $totalTests) * 100, 1) : 0;
        ?>
        
        <div class="stat">
            <div class="stat-number"><?= $totalTests ?></div>
            <div>Total Tests</div>
        </div>
        <div class="stat">
            <div class="stat-number" style="color: #10b981;"><?= $successTests ?></div>
            <div>Success</div>
        </div>
        <div class="stat">
            <div class="stat-number" style="color: #ef4444;"><?= $errorTests ?></div>
            <div>Errors</div>
        </div>
        <div class="stat">
            <div class="stat-number" style="color: #f59e0b;"><?= $warningTests ?></div>
            <div>Warnings</div>
        </div>
        <div class="stat">
            <div class="stat-number" style="color: <?= $successRate >= 80 ? '#10b981' : '#ef4444' ?>;"><?= $successRate ?>%</div>
            <div>Success Rate</div>
        </div>
    </div>

    <!-- RISULTATO FINALE -->
    <div class="test-section">
        <div class="test-header">
            <h3><?= $successRate >= 80 ? '🎉 Tests Passed!' : '🔧 Action Required' ?></h3>
        </div>
        <div class="test-body" style="text-align: center;">
            <?php if ($successRate >= 80): ?>
                <div class="test-item success">
                    <div><strong>✅ Admin Authentication & CRUD Tests Passed!</strong></div>
                    <div>All main functionality is working correctly.</div>
                </div>
            <?php else: ?>
                <div class="test-item error">
                    <div><strong>🔧 Some Tests Failed</strong></div>
                    <div>Please resolve <?= $errorTests ?> errors before going live.</div>
                </div>
            <?php endif; ?>
            
            <div style="margin-top: 20px;">
                <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn">🔄 Reload Tests</a>
                <a href="/admin" class="btn">⚡ Admin Panel</a>
                <a href="/test-complete.php" class="btn">🧪 Full Test Suite</a>
            </div>
        </div>
    </div>
</div>

<script>
function testLogin() {
    const email = document.getElementById('loginEmail').value;
    const password = document.getElementById('loginPassword').value;
    
    fetch('/api/v1/auth/login', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ email, password })
    })
    .then(response => response.json())
    .then(data => {
        alert('Login test result: ' + JSON.stringify(data, null, 2));
    })
    .catch(error => {
        alert('Login test error: ' + error.message);
    });
}

function testContact() {
    const name = document.getElementById('contactName').value;
    const email = document.getElementById('contactEmail').value;
    const message = document.getElementById('contactMessage').value;
    
    fetch('/api/v1/contacts', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ name, email, message })
    })
    .then(response => response.json())
    .then(data => {
        alert('Contact test result: ' + JSON.stringify(data, null, 2));
    })
    .catch(error => {
        alert('Contact test error: ' + error.message);
    });
}
</script>

</body>
</html> 