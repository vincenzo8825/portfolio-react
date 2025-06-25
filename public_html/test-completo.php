<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Configurazione database Hostinger
$db_config = [
    'host' => 'localhost',
    'dbname' => 'u336414084_portfolioVince',
    'username' => 'u336414084_vincenzorocca8',
    'password' => 'Ciaociao52.?'
];

// Connessione database
try {
    $pdo = new PDO(
        "mysql:host={$db_config['host']};dbname={$db_config['dbname']};charset=utf8mb4",
        $db_config['username'],
        $db_config['password'],
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
    $db_status = "✅ Database connesso correttamente";
} catch (PDOException $e) {
    $db_status = "❌ Errore database: " . $e->getMessage();
}

// Gestione delle azioni POST
$action_result = '';

if ($_POST) {
    try {
        switch ($_POST['action'] ?? '') {
            case 'login':
                $email = $_POST['email'] ?? '';
                $password = $_POST['password'] ?? '';
                
                $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
                $stmt->execute([$email]);
                $user = $stmt->fetch();
                
                if ($user && password_verify($password, $user['password'])) {
                    $_SESSION['admin_logged'] = true;
                    $_SESSION['admin_user'] = $user;
                    $action_result = "✅ Login effettuato con successo! Admin: " . ($user['is_admin'] ? 'Sì' : 'No');
                } else {
                    $action_result = "❌ Credenziali non valide";
                }
                break;
                
            case 'logout':
                session_destroy();
                $action_result = "✅ Logout effettuato";
                break;
                
            case 'change_password':
                if (!isset($_SESSION['admin_logged'])) {
                    $action_result = "❌ Devi essere loggato";
                    break;
                }
                
                $current_password = $_POST['current_password'] ?? '';
                $new_password = $_POST['new_password'] ?? '';
                $user_id = $_SESSION['admin_user']['id'];
                
                $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
                $stmt->execute([$user_id]);
                $user = $stmt->fetch();
                
                if (password_verify($current_password, $user['password'])) {
                    $hashed = password_hash($new_password, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
                    $stmt->execute([$hashed, $user_id]);
                    $action_result = "✅ Password cambiata con successo";
                } else {
                    $action_result = "❌ Password attuale non corretta";
                }
                break;
                
            case 'create_user':
                $name = $_POST['user_name'] ?? '';
                $email = $_POST['user_email'] ?? '';
                $password = $_POST['user_password'] ?? '';
                $is_admin = isset($_POST['user_is_admin']) ? 1 : 0;
                
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (name, email, password, is_admin, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())");
                $stmt->execute([$name, $email, $hashed, $is_admin]);
                $action_result = "✅ Utente creato: $name ($email) - Admin: " . ($is_admin ? 'Sì' : 'No');
                break;
                
            case 'send_contact':
                $name = $_POST['contact_name'] ?? '';
                $email = $_POST['contact_email'] ?? '';
                $subject = $_POST['contact_subject'] ?? '';
                $message = $_POST['contact_message'] ?? '';
                $budget = $_POST['contact_budget'] ?? '';
                $timeline = $_POST['contact_timeline'] ?? '';
                $project_type = $_POST['contact_project_type'] ?? '';
                
                $stmt = $pdo->prepare("INSERT INTO contacts (name, email, subject, message, budget, timeline, project_type, status, ip_address, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, 'new', ?, NOW(), NOW())");
                $stmt->execute([$name, $email, $subject, $message, $budget, $timeline, $project_type, $_SERVER['REMOTE_ADDR']]);
                $action_result = "✅ Messaggio di contatto inviato da $name";
                break;
                
            case 'create_project':
                $title = $_POST['project_title'] ?? '';
                $slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $title));
                $description = $_POST['project_description'] ?? '';
                $demo_url = $_POST['project_demo_url'] ?? '';
                $github_url = $_POST['project_github_url'] ?? '';
                $technologies = json_encode(explode(',', $_POST['project_technologies'] ?? ''));
                $featured = isset($_POST['project_featured']) ? 1 : 0;
                
                $stmt = $pdo->prepare("INSERT INTO projects (title, slug, description, demo_url, github_url, technologies, featured, status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, 'completed', NOW(), NOW())");
                $stmt->execute([$title, $slug, $description, $demo_url, $github_url, $technologies, $featured]);
                $action_result = "✅ Progetto creato: $title";
                break;
                
            case 'delete_project':
                $project_id = $_POST['project_id'] ?? 0;
                $stmt = $pdo->prepare("DELETE FROM projects WHERE id = ?");
                $stmt->execute([$project_id]);
                $action_result = "✅ Progetto eliminato (ID: $project_id)";
                break;
        }
    } catch (Exception $e) {
        $action_result = "❌ Errore: " . $e->getMessage();
    }
}

// Recupera dati per visualizzazione
$users = [];
$projects = [];
$contacts = [];
$technologies = [];

try {
    $users = $pdo->query("SELECT * FROM users ORDER BY created_at DESC")->fetchAll();
    $projects = $pdo->query("SELECT * FROM projects ORDER BY created_at DESC")->fetchAll();
    $contacts = $pdo->query("SELECT * FROM contacts ORDER BY created_at DESC LIMIT 10")->fetchAll();
    $technologies = $pdo->query("SELECT * FROM technologies ORDER BY name")->fetchAll();
} catch (Exception $e) {
    $action_result .= " | Errore nel caricamento dati: " . $e->getMessage();
}

$is_logged = isset($_SESSION['admin_logged']);
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Completo Portfolio - Vincenzo Rocca</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f5f5; padding: 20px; }
        .container { max-width: 1200px; margin: 0 auto; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; border-radius: 10px; margin-bottom: 20px; text-align: center; }
        .status { background: white; padding: 15px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #4CAF50; }
        .error { border-left-color: #f44336; }
        .section { background: white; margin-bottom: 20px; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .section-header { background: #f8f9fa; padding: 15px; border-bottom: 1px solid #dee2e6; font-weight: bold; color: #495057; }
        .section-content { padding: 20px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: 500; color: #333; }
        .form-group input, .form-group textarea, .form-group select { width: 100%; padding: 8px 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; }
        .form-group textarea { resize: vertical; height: 80px; }
        .btn { padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; margin-right: 10px; margin-bottom: 10px; }
        .btn:hover { background: #0056b3; }
        .btn-danger { background: #dc3545; }
        .btn-danger:hover { background: #c82333; }
        .btn-success { background: #28a745; }
        .btn-success:hover { background: #218838; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; }
        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td { padding: 12px; text-align: left; border-bottom: 1px solid #dee2e6; }
        .table th { background: #f8f9fa; font-weight: 600; }
        .table tr:hover { background: #f8f9fa; }
        .badge { padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 500; }
        .badge-success { background: #d4edda; color: #155724; }
        .badge-danger { background: #f8d7da; color: #721c24; }
        .badge-primary { background: #d1ecf1; color: #0c5460; }
        .console { background: #1e1e1e; color: #fff; padding: 15px; border-radius: 4px; font-family: monospace; font-size: 12px; margin-top: 15px; max-height: 200px; overflow-y: auto; }
        .two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
        @media (max-width: 768px) { .two-col { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🚀 Test Completo Portfolio - Vincenzo Rocca</h1>
            <p>Pagina di debug per testare tutte le funzionalità del sito</p>
        </div>

        <div class="status <?= strpos($db_status, '❌') !== false ? 'error' : '' ?>">
            <strong>Status Database:</strong> <?= $db_status ?>
            <?php if ($is_logged): ?>
                | <strong>Stato Login:</strong> ✅ Loggato come <?= $_SESSION['admin_user']['name'] ?> (<?= $_SESSION['admin_user']['email'] ?>)
            <?php else: ?>
                | <strong>Stato Login:</strong> ❌ Non loggato
            <?php endif; ?>
        </div>

        <?php if ($action_result): ?>
            <div class="status <?= strpos($action_result, '❌') !== false ? 'error' : '' ?>">
                <strong>Risultato Ultima Azione:</strong> <?= $action_result ?>
            </div>
        <?php endif; ?>

        <div class="grid">
            <!-- Sezione Login -->
            <div class="section">
                <div class="section-header">🔐 Autenticazione</div>
                <div class="section-content">
                    <?php if (!$is_logged): ?>
                        <form method="POST">
                            <input type="hidden" name="action" value="login">
                            <div class="form-group">
                                <label>Email:</label>
                                <input type="email" name="email" value="vincenzorocca88@gmail.com" required>
                            </div>
                            <div class="form-group">
                                <label>Password:</label>
                                <input type="password" name="password" value="admin123" required>
                            </div>
                            <button type="submit" class="btn">Login</button>
                        </form>
                    <?php else: ?>
                        <p>✅ Sei loggato come: <strong><?= $_SESSION['admin_user']['name'] ?></strong></p>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="action" value="logout">
                            <button type="submit" class="btn btn-danger">Logout</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Sezione Cambio Password -->
            <?php if ($is_logged): ?>
            <div class="section">
                <div class="section-header">🔑 Cambio Password</div>
                <div class="section-content">
                    <form method="POST">
                        <input type="hidden" name="action" value="change_password">
                        <div class="form-group">
                            <label>Password Attuale:</label>
                            <input type="password" name="current_password" required>
                        </div>
                        <div class="form-group">
                            <label>Nuova Password:</label>
                            <input type="password" name="new_password" required>
                        </div>
                        <button type="submit" class="btn">Cambia Password</button>
                    </form>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Sezione Creazione Utenti -->
        <div class="section">
            <div class="section-header">👤 Gestione Utenti</div>
            <div class="section-content">
                <div class="two-col">
                    <div>
                        <h4>Crea Nuovo Utente</h4>
                        <form method="POST">
                            <input type="hidden" name="action" value="create_user">
                            <div class="form-group">
                                <label>Nome:</label>
                                <input type="text" name="user_name" required>
                            </div>
                            <div class="form-group">
                                <label>Email:</label>
                                <input type="email" name="user_email" required>
                            </div>
                            <div class="form-group">
                                <label>Password:</label>
                                <input type="password" name="user_password" required>
                            </div>
                            <div class="form-group">
                                <label>
                                    <input type="checkbox" name="user_is_admin"> È Admin
                                </label>
                            </div>
                            <button type="submit" class="btn btn-success">Crea Utente</button>
                        </form>
                    </div>
                    <div>
                        <h4>Utenti Esistenti</h4>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>Email</th>
                                    <th>Admin</th>
                                    <th>Creato</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?= htmlspecialchars($user['name']) ?></td>
                                    <td><?= htmlspecialchars($user['email']) ?></td>
                                    <td>
                                        <span class="badge <?= $user['is_admin'] ? 'badge-success' : 'badge-danger' ?>">
                                            <?= $user['is_admin'] ? 'Sì' : 'No' ?>
                                        </span>
                                    </td>
                                    <td><?= date('d/m/Y H:i', strtotime($user['created_at'])) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sezione Form Contatti -->
        <div class="section">
            <div class="section-header">📧 Test Form Contatti</div>
            <div class="section-content">
                <div class="two-col">
                    <div>
                        <h4>Invia Messaggio di Test</h4>
                        <form method="POST">
                            <input type="hidden" name="action" value="send_contact">
                            <div class="form-group">
                                <label>Nome:</label>
                                <input type="text" name="contact_name" value="Test User" required>
                            </div>
                            <div class="form-group">
                                <label>Email:</label>
                                <input type="email" name="contact_email" value="test@example.com" required>
                            </div>
                            <div class="form-group">
                                <label>Oggetto:</label>
                                <input type="text" name="contact_subject" value="Test contatto">
                            </div>
                            <div class="form-group">
                                <label>Messaggio:</label>
                                <textarea name="contact_message" required>Questo è un messaggio di test per verificare il funzionamento del form contatti.</textarea>
                            </div>
                            <div class="form-group">
                                <label>Budget:</label>
                                <select name="contact_budget">
                                    <option value="">Seleziona...</option>
                                    <option value="< 1000€">< 1000€</option>
                                    <option value="1000€ - 5000€">1000€ - 5000€</option>
                                    <option value="5000€ - 10000€">5000€ - 10000€</option>
                                    <option value="> 10000€">> 10000€</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Timeline:</label>
                                <select name="contact_timeline">
                                    <option value="">Seleziona...</option>
                                    <option value="< 1 mese">< 1 mese</option>
                                    <option value="1-3 mesi">1-3 mesi</option>
                                    <option value="3-6 mesi">3-6 mesi</option>
                                    <option value="> 6 mesi">> 6 mesi</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Tipo Progetto:</label>
                                <select name="contact_project_type">
                                    <option value="">Seleziona...</option>
                                    <option value="Sito Web">Sito Web</option>
                                    <option value="E-commerce">E-commerce</option>
                                    <option value="App Mobile">App Mobile</option>
                                    <option value="Consulenza">Consulenza</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-success">Invia Test</button>
                        </form>
                    </div>
                    <div>
                        <h4>Messaggi Ricevuti (Ultimi 10)</h4>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>Email</th>
                                    <th>Oggetto</th>
                                    <th>Status</th>
                                    <th>Data</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($contacts as $contact): ?>
                                <tr>
                                    <td><?= htmlspecialchars($contact['name']) ?></td>
                                    <td><?= htmlspecialchars($contact['email']) ?></td>
                                    <td><?= htmlspecialchars($contact['subject'] ?? 'N/A') ?></td>
                                    <td>
                                        <span class="badge badge-primary">
                                            <?= ucfirst($contact['status']) ?>
                                        </span>
                                    </td>
                                    <td><?= date('d/m/Y H:i', strtotime($contact['created_at'])) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sezione Progetti -->
        <div class="section">
            <div class="section-header">🚀 Gestione Progetti</div>
            <div class="section-content">
                <div class="two-col">
                    <div>
                        <h4>Crea Nuovo Progetto</h4>
                        <form method="POST">
                            <input type="hidden" name="action" value="create_project">
                            <div class="form-group">
                                <label>Titolo:</label>
                                <input type="text" name="project_title" required>
                            </div>
                            <div class="form-group">
                                <label>Descrizione:</label>
                                <textarea name="project_description" required></textarea>
                            </div>
                            <div class="form-group">
                                <label>URL Demo:</label>
                                <input type="url" name="project_demo_url">
                            </div>
                            <div class="form-group">
                                <label>URL GitHub:</label>
                                <input type="url" name="project_github_url">
                            </div>
                            <div class="form-group">
                                <label>Tecnologie (separate da virgola):</label>
                                <input type="text" name="project_technologies" placeholder="React,Laravel,MySQL">
                            </div>
                            <div class="form-group">
                                <label>
                                    <input type="checkbox" name="project_featured"> In Evidenza
                                </label>
                            </div>
                            <button type="submit" class="btn btn-success">Crea Progetto</button>
                        </form>
                    </div>
                    <div>
                        <h4>Progetti Esistenti</h4>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Titolo</th>
                                    <th>Status</th>
                                    <th>Featured</th>
                                    <th>Azioni</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($projects as $project): ?>
                                <tr>
                                    <td><?= htmlspecialchars($project['title']) ?></td>
                                    <td>
                                        <span class="badge badge-success">
                                            <?= ucfirst($project['status']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge <?= $project['featured'] ? 'badge-success' : 'badge-danger' ?>">
                                            <?= $project['featured'] ? 'Sì' : 'No' ?>
                                        </span>
                                    </td>
                                    <td>
                                        <form method="POST" style="display: inline;">
                                            <input type="hidden" name="action" value="delete_project">
                                            <input type="hidden" name="project_id" value="<?= $project['id'] ?>">
                                            <button type="submit" class="btn btn-danger" onclick="return confirm('Sei sicuro?')">Elimina</button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sezione Tecnologie -->
        <div class="section">
            <div class="section-header">⚡ Tecnologie</div>
            <div class="section-content">
                <p><strong>Tecnologie nel database:</strong> <?= count($technologies) ?></p>
                <?php if (count($technologies) > 0): ?>
                    <div style="display: flex; flex-wrap: wrap; gap: 10px; margin-top: 10px;">
                        <?php foreach (array_slice($technologies, 0, 20) as $tech): ?>
                            <span class="badge badge-primary"><?= htmlspecialchars($tech['name']) ?></span>
                        <?php endforeach; ?>
                        <?php if (count($technologies) > 20): ?>
                            <span class="badge badge-success">... e altri <?= count($technologies) - 20 ?></span>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <p style="color: #dc3545;">❌ Nessuna tecnologia trovata nel database</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Console Debug -->
        <div class="section">
            <div class="section-header">🔧 Console Debug</div>
            <div class="section-content">
                <div class="console" id="debug-console">
                    <div>📊 STATISTICHE DATABASE:</div>
                    <div>• Utenti: <?= count($users) ?></div>
                    <div>• Progetti: <?= count($projects) ?></div>
                    <div>• Contatti: <?= count($contacts) ?></div>
                    <div>• Tecnologie: <?= count($technologies) ?></div>
                    <div></div>
                    <div>🌐 INFORMAZIONI SERVER:</div>
                    <div>• PHP Version: <?= phpversion() ?></div>
                    <div>• Server: <?= $_SERVER['SERVER_SOFTWARE'] ?? 'N/A' ?></div>
                    <div>• Document Root: <?= $_SERVER['DOCUMENT_ROOT'] ?? 'N/A' ?></div>
                    <div>• Request URI: <?= $_SERVER['REQUEST_URI'] ?? 'N/A' ?></div>
                    <div>• Remote Address: <?= $_SERVER['REMOTE_ADDR'] ?? 'N/A' ?></div>
                    <div></div>
                    <div>🔗 TEST CONNESSIONI:</div>
                    <div id="connection-tests">Clicca "Testa Connessioni" per verificare...</div>
                </div>
                <button onclick="testConnections()" class="btn" style="margin-top: 10px;">Testa Connessioni</button>
                <button onclick="clearConsole()" class="btn btn-danger" style="margin-top: 10px;">Pulisci Console</button>
            </div>
        </div>
    </div>

    <script>
        function testConnections() {
            const console = document.getElementById('connection-tests');
            console.innerHTML = 'Testing...';
            
            // Test API calls
            const tests = [
                { name: 'API Tecnologie', url: '/api/public/index.php?endpoint=technologies' },
                { name: 'API Progetti', url: '/api/public/index.php?endpoint=projects' },
                { name: 'Frontend React', url: '/index.html' }
            ];
            
            let results = [];
            
            tests.forEach((test, index) => {
                fetch(test.url)
                    .then(response => {
                        results.push(`✅ ${test.name}: ${response.status} ${response.statusText}`);
                        if (index === tests.length - 1) {
                            console.innerHTML = results.join('<br>');
                        }
                    })
                    .catch(error => {
                        results.push(`❌ ${test.name}: ${error.message}`);
                        if (index === tests.length - 1) {
                            console.innerHTML = results.join('<br>');
                        }
                    });
            });
        }
        
        function clearConsole() {
            document.getElementById('debug-console').innerHTML = '<div>Console pulita...</div>';
        }
        
        // Auto-refresh delle statistiche ogni 30 secondi
        setInterval(() => {
            const timestamp = new Date().toLocaleString('it-IT');
            const console = document.getElementById('debug-console');
            console.innerHTML += `<div style="color: #4CAF50;">🕒 ${timestamp} - Pagina attiva</div>`;
            console.scrollTop = console.scrollHeight;
        }, 30000);
    </script>
</body>
</html> 