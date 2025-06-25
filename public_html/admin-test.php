<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Configurazione database
$db_config = [
    'host' => 'localhost',
    'dbname' => 'u336414084_portfolioVince', 
    'username' => 'u336414084_vincenzorocca8',
    'password' => 'Ciaociao52.?'
];

try {
    $pdo = new PDO(
        "mysql:host={$db_config['host']};dbname={$db_config['dbname']};charset=utf8mb4",
        $db_config['username'],
        $db_config['password'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    $db_connected = true;
} catch (PDOException $e) {
    $db_connected = false;
    $db_error = $e->getMessage();
}

$message = '';

// Gestione azioni
if ($_POST && $db_connected) {
    try {
        switch ($_POST['action']) {
            case 'login':
                $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
                $stmt->execute([$_POST['email']]);
                $user = $stmt->fetch();
                
                if ($user && password_verify($_POST['password'], $user['password'])) {
                    $_SESSION['admin'] = $user;
                    $message = "✅ Login effettuato come " . $user['name'];
                } else {
                    $message = "❌ Credenziali non valide";
                }
                break;
                
            case 'logout':
                session_destroy();
                header("Location: " . $_SERVER['PHP_SELF']);
                exit;
                
            case 'change_password':
                if (!isset($_SESSION['admin'])) {
                    $message = "❌ Devi essere loggato";
                    break;
                }
                
                $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
                $stmt->execute([$_SESSION['admin']['id']]);
                $current = $stmt->fetchColumn();
                
                if (password_verify($_POST['current_password'], $current)) {
                    $new_hash = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
                    $stmt->execute([$new_hash, $_SESSION['admin']['id']]);
                    $message = "✅ Password cambiata con successo";
                } else {
                    $message = "❌ Password attuale errata";
                }
                break;
                
            case 'create_user':
                $hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $is_admin = isset($_POST['is_admin']) ? 1 : 0;
                
                $stmt = $pdo->prepare("INSERT INTO users (name, email, password, is_admin, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())");
                $stmt->execute([$_POST['name'], $_POST['email'], $hash, $is_admin]);
                $message = "✅ Utente creato: " . $_POST['name'];
                break;
                
            case 'create_project':
                $title = $_POST['title'];
                $slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $title));
                $technologies = json_encode(array_filter(explode(',', $_POST['technologies'])));
                $featured = isset($_POST['featured']) ? 1 : 0;
                
                $stmt = $pdo->prepare("INSERT INTO projects (title, slug, description, demo_url, github_url, technologies, featured, status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, 'completed', NOW(), NOW())");
                $stmt->execute([$title, $slug, $_POST['description'], $_POST['demo_url'], $_POST['github_url'], $technologies, $featured]);
                $message = "✅ Progetto creato: $title";
                break;
                
            case 'delete_project':
                $stmt = $pdo->prepare("DELETE FROM projects WHERE id = ?");
                $stmt->execute([$_POST['project_id']]);
                $message = "✅ Progetto eliminato";
                break;
                
            case 'send_contact':
                $stmt = $pdo->prepare("INSERT INTO contacts (name, email, subject, message, budget, timeline, project_type, status, ip_address, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, 'new', ?, NOW(), NOW())");
                $stmt->execute([
                    $_POST['name'], $_POST['email'], $_POST['subject'], $_POST['message'],
                    $_POST['budget'], $_POST['timeline'], $_POST['project_type'], $_SERVER['REMOTE_ADDR']
                ]);
                $message = "✅ Messaggio di contatto inviato";
                break;
        }
    } catch (Exception $e) {
        $message = "❌ Errore: " . $e->getMessage();
    }
}

// Caricamento dati
$stats = ['users' => 0, 'projects' => 0, 'contacts' => 0, 'technologies' => 0];
$users = [];
$projects = [];
$contacts = [];

if ($db_connected) {
    try {
        $stats['users'] = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
        $stats['projects'] = $pdo->query("SELECT COUNT(*) FROM projects")->fetchColumn();
        $stats['contacts'] = $pdo->query("SELECT COUNT(*) FROM contacts")->fetchColumn();
        $stats['technologies'] = $pdo->query("SELECT COUNT(*) FROM technologies")->fetchColumn();
        
        $users = $pdo->query("SELECT * FROM users ORDER BY created_at DESC")->fetchAll();
        $projects = $pdo->query("SELECT * FROM projects ORDER BY created_at DESC LIMIT 10")->fetchAll();
        $contacts = $pdo->query("SELECT * FROM contacts ORDER BY created_at DESC LIMIT 5")->fetchAll();
    } catch (Exception $e) {
        $message .= " | Errore caricamento dati: " . $e->getMessage();
    }
}

$is_logged = isset($_SESSION['admin']);
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🛠️ Admin Test - Portfolio Vincenzo Rocca</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f8f9fa; }
        .container { max-width: 1400px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%); color: white; padding: 30px; border-radius: 12px; margin-bottom: 30px; text-align: center; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .status-bar { background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #28a745; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .status-bar.error { border-left-color: #dc3545; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .card { background: white; border-radius: 12px; padding: 25px; box-shadow: 0 4px 6px rgba(0,0,0,0.07); border: 1px solid #e9ecef; }
        .card-header { font-size: 18px; font-weight: 600; color: #2c3e50; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #3498db; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 500; color: #495057; }
        .form-group input, .form-group textarea, .form-group select { width: 100%; padding: 12px; border: 2px solid #e9ecef; border-radius: 6px; font-size: 14px; transition: border-color 0.3s; }
        .form-group input:focus, .form-group textarea:focus, .form-group select:focus { outline: none; border-color: #3498db; }
        .btn { padding: 12px 24px; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 500; transition: all 0.3s; margin-right: 10px; margin-bottom: 10px; }
        .btn-primary { background: #3498db; color: white; }
        .btn-primary:hover { background: #2980b9; }
        .btn-success { background: #27ae60; color: white; }
        .btn-success:hover { background: #229954; }
        .btn-danger { background: #e74c3c; color: white; }
        .btn-danger:hover { background: #c0392b; }
        .table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .table th, .table td { padding: 12px; text-align: left; border-bottom: 1px solid #e9ecef; }
        .table th { background: #f8f9fa; font-weight: 600; color: #495057; }
        .table tr:hover { background: #f8f9fa; }
        .badge { padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 500; }
        .badge-success { background: #d1f2eb; color: #00875a; }
        .badge-danger { background: #ffe6e6; color: #de3e3e; }
        .badge-primary { background: #e3f2fd; color: #1976d2; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 20px; }
        .stat-card { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; border-radius: 8px; text-align: center; }
        .stat-number { font-size: 32px; font-weight: bold; margin-bottom: 5px; }
        .stat-label { font-size: 14px; opacity: 0.9; }
        .console { background: #2c3e50; color: #ecf0f1; padding: 20px; border-radius: 8px; font-family: 'Courier New', monospace; font-size: 13px; margin-top: 20px; max-height: 300px; overflow-y: auto; }
        .two-cols { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        @media (max-width: 768px) { 
            .two-cols { grid-template-columns: 1fr; }
            .grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🛠️ Admin Test - Portfolio Vincenzo Rocca</h1>
            <p>Pannello completo per testare e gestire tutte le funzionalità del sito</p>
        </div>

        <div class="status-bar <?= !$db_connected ? 'error' : '' ?>">
            <strong>🗄️ Database:</strong> 
            <?= $db_connected ? '✅ Connesso a ' . $db_config['dbname'] : '❌ Errore: ' . $db_error ?>
            
            <?php if ($is_logged): ?>
                | <strong>👤 Utente:</strong> ✅ <?= $_SESSION['admin']['name'] ?> (<?= $_SESSION['admin']['email'] ?>)
                <?php if ($_SESSION['admin']['is_admin']): ?>
                    <span class="badge badge-success">ADMIN</span>
                <?php endif; ?>
            <?php else: ?>
                | <strong>👤 Utente:</strong> ❌ Non loggato
            <?php endif; ?>
        </div>

        <?php if ($message): ?>
        <div class="status-bar <?= strpos($message, '❌') !== false ? 'error' : '' ?>">
            <strong>📋 Ultimo Risultato:</strong> <?= $message ?>
        </div>
        <?php endif; ?>

        <!-- Statistiche -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number"><?= $stats['users'] ?></div>
                <div class="stat-label">👥 Utenti</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $stats['projects'] ?></div>
                <div class="stat-label">🚀 Progetti</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $stats['contacts'] ?></div>
                <div class="stat-label">📧 Contatti</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $stats['technologies'] ?></div>
                <div class="stat-label">⚡ Tecnologie</div>
            </div>
        </div>

        <!-- Login Section -->
        <div class="grid">
            <div class="card">
                <div class="card-header">🔐 Autenticazione</div>
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
                        <button type="submit" class="btn btn-primary">🔓 Accedi</button>
                    </form>
                <?php else: ?>
                    <p>✅ Sei loggato come: <strong><?= $_SESSION['admin']['name'] ?></strong></p>
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="action" value="logout">
                        <button type="submit" class="btn btn-danger">🔒 Logout</button>
                    </form>
                <?php endif; ?>
            </div>

            <?php if ($is_logged): ?>
            <div class="card">
                <div class="card-header">🔑 Cambio Password</div>
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
                    <button type="submit" class="btn btn-success">🔄 Cambia Password</button>
                </form>
            </div>
            <?php endif; ?>
        </div>

        <!-- Gestione Utenti -->
        <div class="card">
            <div class="card-header">👥 Gestione Utenti</div>
            <div class="two-cols">
                <div>
                    <h4>Crea Nuovo Utente</h4>
                    <form method="POST">
                        <input type="hidden" name="action" value="create_user">
                        <div class="form-group">
                            <label>Nome Completo:</label>
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
                            <label>
                                <input type="checkbox" name="is_admin"> 
                                Privilegi di Amministratore
                            </label>
                        </div>
                        <button type="submit" class="btn btn-success">➕ Crea Utente</button>
                    </form>
                </div>
                <div>
                    <h4>Utenti Registrati (<?= count($users) ?>)</h4>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Email</th>
                                <th>Ruolo</th>
                                <th>Registrato</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= htmlspecialchars($user['name']) ?></td>
                                <td><?= htmlspecialchars($user['email']) ?></td>
                                <td>
                                    <span class="badge <?= $user['is_admin'] ? 'badge-success' : 'badge-primary' ?>">
                                        <?= $user['is_admin'] ? '👑 Admin' : '👤 User' ?>
                                    </span>
                                </td>
                                <td><?= date('d/m/Y', strtotime($user['created_at'])) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 