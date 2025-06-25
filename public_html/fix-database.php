<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🔧 Fix Database - Portfolio Vincenzo Rocca</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); min-height: 100vh; padding: 20px; }
        .container { max-width: 1200px; margin: 0 auto; }
        
        .header { background: rgba(255,255,255,0.1); backdrop-filter: blur(10px); color: white; padding: 30px; border-radius: 20px; text-align: center; margin-bottom: 30px; }
        .header h1 { font-size: 2.5rem; margin-bottom: 10px; }
        
        .card { background: white; border-radius: 20px; padding: 30px; margin-bottom: 20px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); }
        .card-header { font-size: 1.5rem; font-weight: 600; color: #374151; margin-bottom: 20px; display: flex; align-items: center; gap: 12px; }
        
        .btn { padding: 12px 24px; border: none; border-radius: 12px; cursor: pointer; font-weight: 600; transition: all 0.3s; margin-right: 12px; margin-bottom: 12px; }
        .btn-primary { background: #3b82f6; color: white; }
        .btn-success { background: #10b981; color: white; }
        .btn-danger { background: #ef4444; color: white; }
        .btn-warning { background: #f59e0b; color: white; }
        
        .status { padding: 16px; border-radius: 12px; margin: 16px 0; font-weight: 500; }
        .status-success { background: #dcfce7; color: #16a34a; border: 1px solid #bbf7d0; }
        .status-error { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }
        .status-info { background: #dbeafe; color: #2563eb; border: 1px solid #bfdbfe; }
        
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; }
        pre { background: #1f2937; color: #e5e7eb; padding: 16px; border-radius: 8px; overflow-x: auto; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #e5e7eb; }
        th { background: #f8fafc; font-weight: 600; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔧 Database Fix & Diagnostics</h1>
            <p>Strumento per diagnosticare e sistemare i problemi del database</p>
        </div>

        <?php
        // Database connection
        $host = 'localhost';
        $dbname = 'u336414084_portfolioVince';
        $username = 'u336414084_vincenzorocca8';
        $password = 'Ciaociao52.?';

        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo '<div class="status status-success">✅ Connessione database: OK</div>';
        } catch(PDOException $e) {
            echo '<div class="status status-error">❌ Errore connessione database: ' . $e->getMessage() . '</div>';
            exit;
        }

        // Actions
        $action = $_GET['action'] ?? '';

        if ($action === 'create_admin') {
            $email = 'vincenzorocca88@gmail.com';
            $password_plain = 'admin123';
            $name = 'Vincenzo Rocca';
            
            try {
                // Check if user already exists
                $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
                $stmt->execute([$email]);
                $existing_user = $stmt->fetch();
                
                if ($existing_user) {
                    echo '<div class="status status-info">ℹ️ Utente admin già esistente</div>';
                } else {
                    $hashed_password = password_hash($password_plain, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("INSERT INTO users (name, email, password, is_admin, created_at, updated_at) VALUES (?, ?, ?, 1, NOW(), NOW())");
                    $stmt->execute([$name, $email, $hashed_password]);
                    
                    echo '<div class="status status-success">✅ Utente admin creato: ' . $email . ' / ' . $password_plain . '</div>';
                }
            } catch(Exception $e) {
                echo '<div class="status status-error">❌ Errore creazione admin: ' . $e->getMessage() . '</div>';
            }
        }

        if ($action === 'fix_password') {
            $email = 'vincenzorocca88@gmail.com';
            $password_plain = 'admin123';
            
            try {
                $hashed_password = password_hash($password_plain, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE email = ?");
                $stmt->execute([$hashed_password, $email]);
                
                if ($stmt->rowCount() > 0) {
                    echo '<div class="status status-success">✅ Password aggiornata per: ' . $email . ' / ' . $password_plain . '</div>';
                } else {
                    echo '<div class="status status-error">❌ Utente non trovato per aggiornamento password</div>';
                }
            } catch(Exception $e) {
                echo '<div class="status status-error">❌ Errore aggiornamento password: ' . $e->getMessage() . '</div>';
            }
        }

        if ($action === 'test_login') {
            $email = 'vincenzorocca88@gmail.com';
            $password_plain = 'admin123';
            
            try {
                $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
                $stmt->execute([$email]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($user) {
                    echo '<div class="status status-info">ℹ️ Utente trovato: ' . $user['name'] . ' (Admin: ' . ($user['is_admin'] ? 'Sì' : 'No') . ')</div>';
                    
                    if (password_verify($password_plain, $user['password'])) {
                        echo '<div class="status status-success">✅ Test login: SUCCESSO</div>';
                    } else {
                        echo '<div class="status status-error">❌ Test login: Password non corretta</div>';
                        echo '<div class="status status-info">Hash memorizzato: ' . substr($user['password'], 0, 50) . '...</div>';
                        echo '<div class="status status-info">Test hash: ' . substr(password_hash($password_plain, PASSWORD_DEFAULT), 0, 50) . '...</div>';
                    }
                } else {
                    echo '<div class="status status-error">❌ Utente non trovato: ' . $email . '</div>';
                }
            } catch(Exception $e) {
                echo '<div class="status status-error">❌ Errore test login: ' . $e->getMessage() . '</div>';
            }
        }

        if ($action === 'check_tables') {
            try {
                $tables = ['users', 'projects', 'contacts', 'technologies'];
                
                foreach ($tables as $table) {
                    $stmt = $pdo->query("SELECT COUNT(*) as count FROM $table");
                    $result = $stmt->fetch();
                    echo '<div class="status status-info">📋 Tabella ' . $table . ': ' . $result['count'] . ' record</div>';
                }
            } catch(Exception $e) {
                echo '<div class="status status-error">❌ Errore controllo tabelle: ' . $e->getMessage() . '</div>';
            }
        }

        if ($action === 'show_users') {
            try {
                $stmt = $pdo->query("SELECT id, name, email, is_admin, created_at FROM users ORDER BY created_at DESC");
                $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                echo '<div class="card"><div class="card-header">👥 Utenti nel Database</div>';
                if (count($users) > 0) {
                    echo '<table>';
                    echo '<tr><th>ID</th><th>Nome</th><th>Email</th><th>Admin</th><th>Creato</th></tr>';
                    foreach ($users as $user) {
                        echo '<tr>';
                        echo '<td>' . $user['id'] . '</td>';
                        echo '<td>' . htmlspecialchars($user['name']) . '</td>';
                        echo '<td>' . htmlspecialchars($user['email']) . '</td>';
                        echo '<td>' . ($user['is_admin'] ? '✅ Sì' : '❌ No') . '</td>';
                        echo '<td>' . $user['created_at'] . '</td>';
                        echo '</tr>';
                    }
                    echo '</table>';
                } else {
                    echo '<p>Nessun utente trovato</p>';
                }
                echo '</div>';
            } catch(Exception $e) {
                echo '<div class="status status-error">❌ Errore lettura utenti: ' . $e->getMessage() . '</div>';
            }
        }
        ?>

        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header">🚀 Azioni Rapide</div>
            
            <a href="?action=create_admin" class="btn btn-success">👤 Crea Utente Admin</a>
            <a href="?action=fix_password" class="btn btn-warning">🔑 Aggiorna Password Admin</a>
            <a href="?action=test_login" class="btn btn-primary">🔐 Test Login</a>
            <a href="?action=check_tables" class="btn btn-info">📋 Controlla Tabelle</a>
            <a href="?action=show_users" class="btn btn-primary">�� Mostra Utenti</a>
            <a href="/test-email-system.php" target="_blank" class="btn btn-warning">📧 Test Email System</a>
            <a href="/fix-constraint.php" target="_blank" class="btn btn-danger">🔧 Fix Constraint Progetti</a>
        </div>

        <!-- Database Status -->
        <div class="grid">
            <div class="card">
                <div class="card-header">📊 Statistiche Database</div>
                <?php
                try {
                    $users_count = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
                    $projects_count = $pdo->query("SELECT COUNT(*) FROM projects")->fetchColumn();
                    $contacts_count = $pdo->query("SELECT COUNT(*) FROM contacts")->fetchColumn();
                    $technologies_count = $pdo->query("SELECT COUNT(*) FROM technologies")->fetchColumn();
                    
                    echo '<p><strong>👥 Utenti:</strong> ' . $users_count . '</p>';
                    echo '<p><strong>🚀 Progetti:</strong> ' . $projects_count . '</p>';
                    echo '<p><strong>📧 Contatti:</strong> ' . $contacts_count . '</p>';
                    echo '<p><strong>🛠️ Tecnologie:</strong> ' . $technologies_count . '</p>';
                } catch(Exception $e) {
                    echo '<p>Errore lettura statistiche: ' . $e->getMessage() . '</p>';
                }
                ?>
            </div>

            <div class="card">
                <div class="card-header">🔧 Configurazione Database</div>
                <p><strong>Host:</strong> <?= $host ?></p>
                <p><strong>Database:</strong> <?= $dbname ?></p>
                <p><strong>Username:</strong> <?= $username ?></p>
                <p><strong>PHP Version:</strong> <?= phpversion() ?></p>
                <p><strong>Time:</strong> <?= date('Y-m-d H:i:s') ?></p>
            </div>
        </div>

        <!-- Test API -->
        <div class="card">
            <div class="card-header">🧪 Test API Endpoints</div>
            
            <button onclick="testAPI()" class="btn btn-primary">🔗 Test API Direct</button>
            <button onclick="testLogin()" class="btn btn-success">🔐 Test Login API</button>
            <button onclick="testProjects()" class="btn btn-warning">🚀 Test Projects API</button>
            
            <div id="api-results" style="margin-top: 20px;"></div>
        </div>

        <!-- Console -->
        <div class="card">
            <div class="card-header">🖥️ Console Debug</div>
            <pre id="console">Database Fix Tool inizializzato
Timestamp: <?= date('Y-m-d H:i:s') ?>
Host: <?= $_SERVER['HTTP_HOST'] ?>
========================</pre>
            
            <button onclick="clearConsole()" class="btn btn-danger">🗑️ Pulisci Console</button>
        </div>
    </div>

    <script>
        function log(message) {
            const console = document.getElementById('console');
            const timestamp = new Date().toLocaleTimeString();
            console.innerHTML += `\n[${timestamp}] ${message}`;
            console.scrollTop = console.scrollHeight;
        }

        function showResult(message, type = 'info') {
            const container = document.getElementById('api-results');
            const div = document.createElement('div');
            div.className = `status status-${type}`;
            div.innerHTML = message;
            container.appendChild(div);
            
            setTimeout(() => {
                if (div.parentNode) {
                    div.parentNode.removeChild(div);
                }
            }, 10000);
        }

        async function testAPI() {
            log('Testing API endpoints...');
            
            try {
                const response = await fetch('/api-direct.php?endpoint=stats');
                const result = await response.json();
                
                if (response.ok) {
                    showResult('✅ API Direct: Funzionante', 'success');
                    log('API Direct test: SUCCESS');
                    log('Stats: ' + JSON.stringify(result.data));
                } else {
                    throw new Error('API response error');
                }
            } catch (error) {
                showResult('❌ API Direct: Errore - ' + error.message, 'error');
                log('API Direct test: FAILED - ' + error.message);
            }
        }

        async function testLogin() {
            log('Testing login API...');
            
            try {
                const response = await fetch('/api-direct.php?endpoint=login', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        email: 'vincenzorocca88@gmail.com',
                        password: 'admin123'
                    })
                });

                const result = await response.json();
                
                if (result.success) {
                    showResult('✅ Login API: Successo - ' + result.user.name, 'success');
                    log('Login API test: SUCCESS - ' + result.user.name + ' (Admin: ' + result.user.is_admin + ')');
                } else {
                    showResult('❌ Login API: ' + result.message, 'error');
                    log('Login API test: FAILED - ' + result.message);
                }
            } catch (error) {
                showResult('❌ Login API: Errore - ' + error.message, 'error');
                log('Login API test: ERROR - ' + error.message);
            }
        }

        async function testProjects() {
            log('Testing projects API...');
            
            try {
                // Test GET projects
                const getResponse = await fetch('/api-direct.php?endpoint=projects');
                const getResult = await getResponse.json();
                
                if (getResponse.ok) {
                    showResult('✅ GET Projects: ' + getResult.data.length + ' progetti trovati', 'success');
                    log('GET Projects test: SUCCESS - ' + getResult.data.length + ' projects');
                } else {
                    throw new Error('GET projects failed');
                }

                // Test POST project
                const postResponse = await fetch('/api-direct.php?endpoint=projects', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        name: 'Test Project API',
                        description: 'Progetto di test per API',
                        technologies: 'Test, JavaScript, PHP',
                        category: 'web',
                        status: 'completed'
                    })
                });

                const postResult = await postResponse.json();
                
                if (postResult.success) {
                    showResult('✅ POST Project: Progetto creato con ID ' + postResult.project_id, 'success');
                    log('POST Project test: SUCCESS - ID ' + postResult.project_id);
                } else {
                    throw new Error('POST project failed: ' + postResult.error);
                }
                
            } catch (error) {
                showResult('❌ Projects API: Errore - ' + error.message, 'error');
                log('Projects API test: FAILED - ' + error.message);
            }
        }

        function clearConsole() {
            document.getElementById('console').innerHTML = 'Console pulita\nTimestamp: ' + new Date().toLocaleString();
        }

        // Initialize
        window.onload = function() {
            log('Database Fix Tool loaded successfully');
        };
    </script>
</body>
</html> 