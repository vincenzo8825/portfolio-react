<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database configuration for Hostinger
$host = 'localhost';
$dbname = 'u336414084_portfolioVince';
$username = 'u336414084_vincenzorocca8';
$password = 'Ciaociao52.?';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db_connected = true;
    $db_message = "✅ Database connesso";
} catch(PDOException $e) {
    $db_connected = false;
    $db_message = "❌ Errore database: " . $e->getMessage();
}

$result_message = '';

// Handle form submissions
if ($_POST && $db_connected) {
    try {
        switch($_POST['action']) {
            case 'login':
                $email = $_POST['email'];
                $pass = $_POST['password'];
                $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
                $stmt->execute([$email]);
                $user = $stmt->fetch();
                
                if ($user && password_verify($pass, $user['password'])) {
                    $_SESSION['user'] = $user;
                    $result_message = "✅ Login riuscito come " . $user['name'];
                } else {
                    $result_message = "❌ Credenziali non valide";
                }
                break;
                
            case 'create_user':
                $name = $_POST['name'];
                $email = $_POST['email'];
                $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $is_admin = isset($_POST['is_admin']) ? 1 : 0;
                
                $stmt = $pdo->prepare("INSERT INTO users (name, email, password, is_admin, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())");
                $stmt->execute([$name, $email, $pass, $is_admin]);
                $result_message = "✅ Utente creato: $name";
                break;
                
            case 'create_project':
                $title = $_POST['title'];
                $slug = strtolower(str_replace(' ', '-', $title));
                $description = $_POST['description'];
                $demo_url = $_POST['demo_url'];
                $github_url = $_POST['github_url'];
                $technologies = json_encode(explode(',', $_POST['technologies']));
                $featured = isset($_POST['featured']) ? 1 : 0;
                
                $stmt = $pdo->prepare("INSERT INTO projects (title, slug, description, demo_url, github_url, technologies, featured, status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, 'completed', NOW(), NOW())");
                $stmt->execute([$title, $slug, $description, $demo_url, $github_url, $technologies, $featured]);
                $result_message = "✅ Progetto creato: $title";
                break;
                
            case 'send_contact':
                $name = $_POST['name'];
                $email = $_POST['email'];
                $subject = $_POST['subject'];
                $message = $_POST['message'];
                
                $stmt = $pdo->prepare("INSERT INTO contacts (name, email, subject, message, status, created_at, updated_at) VALUES (?, ?, ?, ?, 'new', NOW(), NOW())");
                $stmt->execute([$name, $email, $subject, $message]);
                $result_message = "✅ Messaggio di contatto inviato";
                break;
                
            case 'change_password':
                if (!isset($_SESSION['user'])) {
                    $result_message = "❌ Devi essere loggato";
                    break;
                }
                
                $current = $_POST['current_password'];
                $new = $_POST['new_password'];
                $user_id = $_SESSION['user']['id'];
                
                $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
                $stmt->execute([$user_id]);
                $user = $stmt->fetch();
                
                if (password_verify($current, $user['password'])) {
                    $hashed = password_hash($new, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
                    $stmt->execute([$hashed, $user_id]);
                    $result_message = "✅ Password cambiata";
                } else {
                    $result_message = "❌ Password corrente errata";
                }
                break;
        }
    } catch(Exception $e) {
        $result_message = "❌ Errore: " . $e->getMessage();
    }
}

// Get data for display
$users = [];
$projects = [];
$contacts = [];
$technologies = [];

if ($db_connected) {
    try {
        $users = $pdo->query("SELECT * FROM users ORDER BY created_at DESC")->fetchAll();
        $projects = $pdo->query("SELECT * FROM projects ORDER BY created_at DESC")->fetchAll();
        $contacts = $pdo->query("SELECT * FROM contacts ORDER BY created_at DESC LIMIT 5")->fetchAll();
        $technologies = $pdo->query("SELECT * FROM technologies ORDER BY name")->fetchAll();
    } catch(Exception $e) {
        $result_message .= " | Errore caricamento: " . $e->getMessage();
    }
}

$logged_in = isset($_SESSION['user']);
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Sistema Portfolio</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; }
        .header { background: #333; color: white; padding: 20px; border-radius: 8px; text-align: center; }
        .status { background: white; padding: 15px; margin: 20px 0; border-radius: 8px; border-left: 4px solid #28a745; }
        .error { border-left-color: #dc3545; }
        .section { background: white; margin: 20px 0; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .section h3 { margin-bottom: 15px; color: #333; border-bottom: 2px solid #007bff; padding-bottom: 10px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input, .form-group textarea, .form-group select { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        .btn { background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; margin-right: 10px; }
        .btn:hover { background: #0056b3; }
        .btn-danger { background: #dc3545; }
        .btn-success { background: #28a745; }
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .table th, .table td { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; }
        .table th { background: #f8f9fa; }
        .badge { padding: 2px 6px; border-radius: 3px; font-size: 12px; }
        .badge-success { background: #d4edda; color: #155724; }
        .badge-danger { background: #f8d7da; color: #721c24; }
        @media (max-width: 768px) { .grid { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🧪 Test Sistema Portfolio Vincenzo Rocca</h1>
            <p>Pagina completa per testare tutte le funzionalità</p>
        </div>

        <div class="status <?= !$db_connected ? 'error' : '' ?>">
            <strong>Database:</strong> <?= $db_message ?>
            <?php if ($logged_in): ?>
                | <strong>Login:</strong> ✅ <?= $_SESSION['user']['name'] ?> (<?= $_SESSION['user']['email'] ?>)
            <?php else: ?>
                | <strong>Login:</strong> ❌ Non loggato
            <?php endif; ?>
        </div>

        <?php if ($result_message): ?>
        <div class="status <?= strpos($result_message, '❌') !== false ? 'error' : '' ?>">
            <?= $result_message ?>
        </div>
        <?php endif; ?>

        <div class="grid">
            <!-- LOGIN -->
            <div class="section">
                <h3>🔐 Login</h3>
                <?php if (!$logged_in): ?>
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
                <p>✅ Loggato come: <strong><?= $_SESSION['user']['name'] ?></strong></p>
                <a href="?logout" class="btn btn-danger">Logout</a>
                <?php endif; ?>
            </div>

            <!-- CAMBIO PASSWORD -->
            <?php if ($logged_in): ?>
            <div class="section">
                <h3>🔑 Cambio Password</h3>
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
            <?php endif; ?>
        </div>

        <!-- CREA UTENTE -->
        <div class="section">
            <h3>👤 Gestione Utenti</h3>
            <div class="grid">
                <div>
                    <h4>Crea Nuovo Utente</h4>
                    <form method="POST">
                        <input type="hidden" name="action" value="create_user">
                        <div class="form-group">
                            <label>Nome:</label>
                            <input type="text" name="name" required>
                        </div>
                        <div class="form-group">
                            <label>Email:</label>
                            <input type="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label>Password:</label>
                            <input type="password" name="password" required>
                        </div>
                        <div class="form-group">
                            <label><input type="checkbox" name="is_admin"> È Admin</label>
                        </div>
                        <button type="submit" class="btn btn-success">Crea Utente</button>
                    </form>
                </div>
                <div>
                    <h4>Utenti Esistenti (<?= count($users) ?>)</h4>
                    <table class="table">
                        <tr><th>Nome</th><th>Email</th><th>Admin</th></tr>
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['name']) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td><span class="badge <?= $user['is_admin'] ? 'badge-success' : 'badge-danger' ?>"><?= $user['is_admin'] ? 'Sì' : 'No' ?></span></td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div>
        </div>

        <!-- PROGETTI -->
        <div class="section">
            <h3>🚀 Gestione Progetti</h3>
            <div class="grid">
                <div>
                    <h4>Crea Nuovo Progetto</h4>
                    <form method="POST">
                        <input type="hidden" name="action" value="create_project">
                        <div class="form-group">
                            <label>Titolo:</label>
                            <input type="text" name="title" required>
                        </div>
                        <div class="form-group">
                            <label>Descrizione:</label>
                            <textarea name="description" required></textarea>
                        </div>
                        <div class="form-group">
                            <label>URL Demo:</label>
                            <input type="url" name="demo_url">
                        </div>
                        <div class="form-group">
                            <label>URL GitHub:</label>
                            <input type="url" name="github_url">
                        </div>
                        <div class="form-group">
                            <label>Tecnologie (sep. da virgola):</label>
                            <input type="text" name="technologies" placeholder="React,Laravel,MySQL">
                        </div>
                        <div class="form-group">
                            <label><input type="checkbox" name="featured"> In Evidenza</label>
                        </div>
                        <button type="submit" class="btn btn-success">Crea Progetto</button>
                    </form>
                </div>
                <div>
                    <h4>Progetti Esistenti (<?= count($projects) ?>)</h4>
                    <table class="table">
                        <tr><th>Titolo</th><th>Status</th><th>Featured</th></tr>
                        <?php foreach ($projects as $project): ?>
                        <tr>
                            <td><?= htmlspecialchars($project['title']) ?></td>
                            <td><span class="badge badge-success"><?= ucfirst($project['status']) ?></span></td>
                            <td><span class="badge <?= $project['featured'] ? 'badge-success' : 'badge-danger' ?>"><?= $project['featured'] ? 'Sì' : 'No' ?></span></td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div>
        </div>

        <!-- FORM CONTATTI -->
        <div class="section">
            <h3>📧 Test Form Contatti</h3>
            <div class="grid">
                <div>
                    <h4>Invia Messaggio Test</h4>
                    <form method="POST">
                        <input type="hidden" name="action" value="send_contact">
                        <div class="form-group">
                            <label>Nome:</label>
                            <input type="text" name="name" value="Test User" required>
                        </div>
                        <div class="form-group">
                            <label>Email:</label>
                            <input type="email" name="email" value="test@example.com" required>
                        </div>
                        <div class="form-group">
                            <label>Oggetto:</label>
                            <input type="text" name="subject" value="Test contatto">
                        </div>
                        <div class="form-group">
                            <label>Messaggio:</label>
                            <textarea name="message" required>Questo è un messaggio di test.</textarea>
                        </div>
                        <button type="submit" class="btn btn-success">Invia Test</button>
                    </form>
                </div>
                <div>
                    <h4>Messaggi Ricevuti (<?= count($contacts) ?>)</h4>
                    <table class="table">
                        <tr><th>Nome</th><th>Email</th><th>Oggetto</th><th>Data</th></tr>
                        <?php foreach ($contacts as $contact): ?>
                        <tr>
                            <td><?= htmlspecialchars($contact['name']) ?></td>
                            <td><?= htmlspecialchars($contact['email']) ?></td>
                            <td><?= htmlspecialchars($contact['subject'] ?? 'N/A') ?></td>
                            <td><?= date('d/m H:i', strtotime($contact['created_at'])) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div>
        </div>

        <!-- STATISTICHE -->
        <div class="section">
            <h3>📊 Statistiche Sistema</h3>
            <div class="grid">
                <div>
                    <h4>Database</h4>
                    <ul>
                        <li>Utenti: <strong><?= count($users) ?></strong></li>
                        <li>Progetti: <strong><?= count($projects) ?></strong></li>
                        <li>Contatti: <strong><?= count($contacts) ?></strong></li>
                        <li>Tecnologie: <strong><?= count($technologies) ?></strong></li>
                    </ul>
                </div>
                <div>
                    <h4>Server Info</h4>
                    <ul>
                        <li>PHP: <strong><?= phpversion() ?></strong></li>
                        <li>Server: <strong><?= $_SERVER['SERVER_SOFTWARE'] ?? 'N/A' ?></strong></li>
                        <li>Host: <strong><?= $_SERVER['HTTP_HOST'] ?? 'N/A' ?></strong></li>
                        <li>IP: <strong><?= $_SERVER['REMOTE_ADDR'] ?? 'N/A' ?></strong></li>
                    </ul>
                </div>
            </div>
            
            <?php if (count($technologies) > 0): ?>
            <div style="margin-top: 20px;">
                <h4>Tecnologie Disponibili</h4>
                <div style="display: flex; flex-wrap: wrap; gap: 5px;">
                    <?php foreach (array_slice($technologies, 0, 20) as $tech): ?>
                        <span class="badge badge-success"><?= htmlspecialchars($tech['name']) ?></span>
                    <?php endforeach; ?>
                    <?php if (count($technologies) > 20): ?>
                        <span class="badge badge-danger">+<?= count($technologies) - 20 ?> altre</span>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <?php if (isset($_GET['logout'])): ?>
    <script>
        <?php session_destroy(); ?>
        location.href = '<?= $_SERVER['PHP_SELF'] ?>';
    </script>
    <?php endif; ?>
</body>
</html> 