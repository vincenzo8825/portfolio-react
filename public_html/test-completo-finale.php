<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Configurazione database
$host = 'localhost';
$dbname = 'u336414084_portfolioVince';
$username = 'u336414084_vincenzorocca8';
$password = 'Ciaociao52.?';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db_connected = true;
} catch(PDOException $e) {
    $db_connected = false;
    $db_error = $e->getMessage();
}

$message = '';

// Gestione azioni POST
if ($_POST && $db_connected) {
    try {
        switch($_POST['action']) {
            case 'login':
                $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
                $stmt->execute([$_POST['email']]);
                $user = $stmt->fetch();
                
                if ($user && password_verify($_POST['password'], $user['password'])) {
                    $_SESSION['admin'] = $user;
                    $message = "✅ Login: " . $user['name'] . " (Admin: " . ($user['is_admin'] ? 'Sì' : 'No') . ")";
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
                    $message = "❌ Login richiesto";
                    break;
                }
                
                $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
                $stmt->execute([$_SESSION['admin']['id']]);
                $current = $stmt->fetchColumn();
                
                if (password_verify($_POST['current_password'], $current)) {
                    $new_hash = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
                    $stmt->execute([$new_hash, $_SESSION['admin']['id']]);
                    $message = "✅ Password cambiata";
                } else {
                    $message = "❌ Password attuale errata";
                }
                break;
                
            case 'create_user':
                $hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $is_admin = isset($_POST['is_admin']) ? 1 : 0;
                
                $stmt = $pdo->prepare("INSERT INTO users (name, email, password, is_admin, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())");
                $stmt->execute([$_POST['name'], $_POST['email'], $hash, $is_admin]);
                $message = "✅ Utente creato: " . $_POST['name'] . ($is_admin ? " (Admin)" : "");
                break;
                
            case 'create_project':
                $title = $_POST['title'];
                $slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $title));
                $technologies = json_encode(array_filter(explode(',', $_POST['technologies'])));
                $featured = isset($_POST['featured']) ? 1 : 0;
                
                // Controlla limite progetti featured
                if ($featured) {
                    $featured_count = $pdo->query("SELECT COUNT(*) FROM projects WHERE featured = 1")->fetchColumn();
                    if ($featured_count >= 3) {
                        $message = "❌ Massimo 3 progetti in evidenza consentiti";
                        break;
                    }
                }
                
                $stmt = $pdo->prepare("INSERT INTO projects (title, slug, description, long_description, demo_url, github_url, linkedin_url, video_url, technologies, featured, status, project_date, client, duration, category, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'completed', ?, ?, ?, ?, NOW(), NOW())");
                $stmt->execute([
                    $title, $slug, $_POST['description'], $_POST['long_description'] ?? '',
                    $_POST['demo_url'], $_POST['github_url'], $_POST['linkedin_url'] ?? '', $_POST['video_url'] ?? '',
                    $technologies, $featured, date('Y-m-d'), $_POST['client'] ?? '', $_POST['duration'] ?? '', $_POST['category'] ?? ''
                ]);
                $message = "✅ Progetto creato: $title" . ($featured ? " (In evidenza)" : "");
                break;
                
            case 'update_project':
                $technologies = json_encode(array_filter(explode(',', $_POST['technologies'])));
                $featured = isset($_POST['featured']) ? 1 : 0;
                
                // Se mette in evidenza, controlla limite
                if ($featured) {
                    $stmt = $pdo->prepare("SELECT featured FROM projects WHERE id = ?");
                    $stmt->execute([$_POST['project_id']]);
                    $current_featured = $stmt->fetchColumn();
                    
                    if (!$current_featured) {
                        $featured_count = $pdo->query("SELECT COUNT(*) FROM projects WHERE featured = 1")->fetchColumn();
                        if ($featured_count >= 3) {
                            $message = "❌ Massimo 3 progetti in evidenza";
                            break;
                        }
                    }
                }
                
                $stmt = $pdo->prepare("UPDATE projects SET title = ?, description = ?, demo_url = ?, github_url = ?, technologies = ?, featured = ?, updated_at = NOW() WHERE id = ?");
                $stmt->execute([
                    $_POST['title'], $_POST['description'], $_POST['demo_url'], $_POST['github_url'],
                    $technologies, $featured, $_POST['project_id']
                ]);
                $message = "✅ Progetto aggiornato: " . $_POST['title'];
                break;
                
            case 'toggle_featured':
                $project_id = $_POST['project_id'];
                
                $stmt = $pdo->prepare("SELECT featured, title FROM projects WHERE id = ?");
                $stmt->execute([$project_id]);
                $project = $stmt->fetch();
                
                if (!$project['featured']) {
                    $featured_count = $pdo->query("SELECT COUNT(*) FROM projects WHERE featured = 1")->fetchColumn();
                    if ($featured_count >= 3) {
                        $message = "❌ Massimo 3 progetti in evidenza";
                        break;
                    }
                }
                
                $new_status = $project['featured'] ? 0 : 1;
                $stmt = $pdo->prepare("UPDATE projects SET featured = ? WHERE id = ?");
                $stmt->execute([$new_status, $project_id]);
                $message = $new_status ? "⭐ " . $project['title'] . " in evidenza" : "⭐ " . $project['title'] . " rimosso da evidenza";
                break;
                
            case 'delete_project':
                $stmt = $pdo->prepare("SELECT title FROM projects WHERE id = ?");
                $stmt->execute([$_POST['project_id']]);
                $title = $stmt->fetchColumn();
                
                $stmt = $pdo->prepare("DELETE FROM projects WHERE id = ?");
                $stmt->execute([$_POST['project_id']]);
                $message = "🗑️ Progetto eliminato: " . $title;
                break;
                
            case 'send_contact':
                $stmt = $pdo->prepare("INSERT INTO contacts (name, email, subject, message, budget, timeline, project_type, phone, company, status, ip_address, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'new', ?, NOW(), NOW())");
                $stmt->execute([
                    $_POST['name'], $_POST['email'], $_POST['subject'], $_POST['message'],
                    $_POST['budget'] ?? '', $_POST['timeline'] ?? '', $_POST['project_type'] ?? '',
                    $_POST['phone'] ?? '', $_POST['company'] ?? '', $_SERVER['REMOTE_ADDR']
                ]);
                $message = "📧 Messaggio contatto inviato da " . $_POST['name'];
                break;
                
            case 'update_contact_status':
                $stmt = $pdo->prepare("UPDATE contacts SET status = ?, admin_notes = ?, updated_at = NOW() WHERE id = ?");
                $stmt->execute([$_POST['status'], $_POST['notes'] ?? '', $_POST['contact_id']]);
                $message = "📧 Status contatto aggiornato";
                break;
        }
    } catch(Exception $e) {
        $message = "❌ Errore: " . $e->getMessage();
    }
}

// Caricamento dati
$stats = ['users' => 0, 'projects' => 0, 'contacts' => 0, 'technologies' => 0, 'featured_projects' => 0];
$users = [];
$projects = [];
$contacts = [];
$technologies = [];

if ($db_connected) {
    try {
        $stats['users'] = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
        $stats['projects'] = $pdo->query("SELECT COUNT(*) FROM projects")->fetchColumn();
        $stats['contacts'] = $pdo->query("SELECT COUNT(*) FROM contacts")->fetchColumn();
        $stats['technologies'] = $pdo->query("SELECT COUNT(*) FROM technologies")->fetchColumn();
        $stats['featured_projects'] = $pdo->query("SELECT COUNT(*) FROM projects WHERE featured = 1")->fetchColumn();
        
        $users = $pdo->query("SELECT * FROM users ORDER BY created_at DESC")->fetchAll();
        $projects = $pdo->query("SELECT * FROM projects ORDER BY created_at DESC")->fetchAll();
        $contacts = $pdo->query("SELECT * FROM contacts ORDER BY created_at DESC LIMIT 10")->fetchAll();
        $technologies = $pdo->query("SELECT * FROM technologies ORDER BY category, name")->fetchAll();
    } catch(Exception $e) {
        $message .= " | Errore caricamento: " . $e->getMessage();
    }
}

$is_logged = isset($_SESSION['admin']);
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🧪 Test Completo Portfolio - Vincenzo Rocca</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f8fafc; }
        .container { max-width: 1400px; margin: 0 auto; padding: 20px; }
        
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; border-radius: 16px; text-align: center; margin-bottom: 30px; box-shadow: 0 8px 32px rgba(0,0,0,0.1); }
        .header h1 { font-size: 2.5rem; margin-bottom: 10px; }
        .header p { font-size: 1.1rem; opacity: 0.9; }
        
        .status-bar { background: white; padding: 20px; border-radius: 12px; margin-bottom: 20px; border-left: 4px solid #10b981; box-shadow: 0 4px 6px rgba(0,0,0,0.05); display: flex; flex-wrap: wrap; gap: 20px; align-items: center; }
        .status-bar.error { border-left-color: #ef4444; }
        .status-item { display: flex; align-items: center; gap: 8px; }
        .status-icon { font-size: 16px; }
        .badge { padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 500; }
        .badge-success { background: #dcfce7; color: #16a34a; }
        .badge-danger { background: #fef2f2; color: #dc2626; }
        
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .stat-card { background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: white; padding: 24px; border-radius: 16px; text-align: center; box-shadow: 0 8px 32px rgba(59, 130, 246, 0.3); }
        .stat-card:nth-child(2) { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
        .stat-card:nth-child(3) { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }
        .stat-card:nth-child(4) { background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); }
        .stat-card:nth-child(5) { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); }
        .stat-number { font-size: 2.5rem; font-weight: bold; margin-bottom: 8px; }
        .stat-label { font-size: 0.9rem; opacity: 0.9; }
        
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .section { background: white; border-radius: 16px; padding: 30px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border: 1px solid #e5e7eb; }
        .section-header { font-size: 1.25rem; font-weight: 600; color: #374151; margin-bottom: 24px; display: flex; align-items: center; gap: 12px; border-bottom: 2px solid #e5e7eb; padding-bottom: 12px; }
        
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 500; color: #374151; }
        .form-group input, .form-group textarea, .form-group select { width: 100%; padding: 12px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 14px; transition: border-color 0.3s; }
        .form-group input:focus, .form-group textarea:focus, .form-group select:focus { outline: none; border-color: #3b82f6; }
        .form-group textarea { min-height: 100px; resize: vertical; }
        .form-check { display: flex; align-items: center; gap: 8px; margin-top: 8px; }
        .form-check input[type="checkbox"] { width: auto; }
        
        .btn { padding: 12px 24px; border: none; border-radius: 8px; cursor: pointer; font-weight: 500; transition: all 0.3s; margin-right: 12px; margin-bottom: 8px; display: inline-flex; align-items: center; gap: 8px; }
        .btn-primary { background: #3b82f6; color: white; }
        .btn-primary:hover { background: #2563eb; transform: translateY(-1px); }
        .btn-success { background: #10b981; color: white; }
        .btn-success:hover { background: #059669; transform: translateY(-1px); }
        .btn-danger { background: #ef4444; color: white; }
        .btn-danger:hover { background: #dc2626; transform: translateY(-1px); }
        .btn-warning { background: #f59e0b; color: white; }
        .btn-warning:hover { background: #d97706; transform: translateY(-1px); }
        
        .table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        .table th, .table td { padding: 12px; text-align: left; border-bottom: 1px solid #e5e7eb; }
        .table th { background: #f9fafb; font-weight: 600; color: #374151; }
        .table tr:hover { background: #f9fafb; }
        
        .two-cols { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
        
        .api-section { background: #1f2937; color: #e5e7eb; border-radius: 12px; padding: 20px; margin-top: 24px; }
        .api-section h4 { color: #10b981; margin-bottom: 16px; }
        .api-buttons { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 16px; }
        .api-results { background: #111827; border-radius: 8px; padding: 16px; font-family: 'Courier New', monospace; font-size: 13px; max-height: 300px; overflow-y: auto; }
        
        @media (max-width: 768px) { 
            .two-cols, .grid { grid-template-columns: 1fr; }
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🧪 Test Completo Portfolio</h1>
            <p>Dashboard completa per testare e gestire tutte le funzionalità del portfolio Vincenzo Rocca</p>
        </div>

        <div class="status-bar <?= !$db_connected ? 'error' : '' ?>">
            <div class="status-item">
                <span class="status-icon">🗄️</span>
                <strong>Database:</strong> 
                <?= $db_connected ? '✅ Connesso' : '❌ Errore: ' . $db_error ?>
            </div>
            
            <?php if ($is_logged): ?>
                <div class="status-item">
                    <span class="status-icon">👤</span>
                    <strong>Utente:</strong> ✅ <?= $_SESSION['admin']['name'] ?>
                    <?php if ($_SESSION['admin']['is_admin']): ?>
                        <span class="badge badge-success">ADMIN</span>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="status-item">
                    <span class="status-icon">👤</span>
                    <strong>Stato:</strong> ❌ Non loggato
                </div>
            <?php endif; ?>
            
            <div class="status-item">
                <span class="status-icon">🌐</span>
                <strong>URL:</strong> <?= $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ?>
            </div>
        </div>

        <?php if ($message): ?>
        <div class="status-bar <?= strpos($message, '❌') !== false ? 'error' : '' ?>">
            <div class="status-item">
                <span class="status-icon">📋</span>
                <strong>Risultato:</strong> <?= $message ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Statistiche -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number"><?= $stats['users'] ?></div>
                <div class="stat-label">👥 Utenti Totali</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $stats['projects'] ?></div>
                <div class="stat-label">🚀 Progetti Totali</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $stats['contacts'] ?></div>
                <div class="stat-label">📧 Messaggi Contatti</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $stats['technologies'] ?></div>
                <div class="stat-label">⚡ Tecnologie</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $stats['featured_projects'] ?>/3</div>
                <div class="stat-label">⭐ Progetti Featured</div>
            </div>
        </div>
    </div>
</body>
</html> 