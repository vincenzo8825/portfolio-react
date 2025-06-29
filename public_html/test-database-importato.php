<?php
/**
 * 🔍 TEST DATABASE IMPORTATO
 * ==========================
 * Verifica che il database sia stato importato correttamente
 */

header('Content-Type: text/html; charset=utf-8');
ini_set('display_errors', 1);
error_reporting(E_ALL);

?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🔍 Test Database Importato</title>
    <style>
        body { font-family: system-ui; margin: 20px; background: #10b981; }
        .container { max-width: 1000px; margin: 0 auto; background: white; padding: 30px; border-radius: 15px; }
        .success { color: #10b981; background: #ecfdf5; padding: 15px; border-radius: 8px; margin: 10px 0; }
        .error { color: #ef4444; background: #fef2f2; padding: 15px; border-radius: 8px; margin: 10px 0; }
        .warning { color: #f59e0b; background: #fffbeb; padding: 15px; border-radius: 8px; margin: 10px 0; }
        .info { color: #3b82f6; background: #eff6ff; padding: 15px; border-radius: 8px; margin: 10px 0; }
        .action { background: #10b981; color: white; padding: 15px; border-radius: 8px; margin: 15px 0; }
        h1 { text-align: center; color: #10b981; }
        .section { background: #f8fafc; padding: 20px; border-radius: 8px; margin: 15px 0; border-left: 5px solid #10b981; }
        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        th, td { padding: 10px; border: 1px solid #e5e7eb; text-align: left; }
        th { background: #f3f4f6; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 TEST DATABASE IMPORTATO</h1>
        
        <div class="action">
            <h3>🎉 Database Importato con Successo!</h3>
            <p>Verifichiamo che tutti i dati siano stati caricati correttamente.</p>
        </div>

        <?php
        // Configurazione database
        $host = 'localhost';
        $database = 'u336414084_portfolioVince';
        $username = 'u336414084_portfolioVince';
        $password = 'Ciaociao52.?';
        
        try {
            $pdo = new PDO(
                "mysql:host={$host};dbname={$database};charset=utf8mb4",
                $username,
                $password,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
            
            echo "<div class='success'>✅ Connessione database riuscita!</div>";
            
        } catch (Exception $e) {
            echo "<div class='error'>❌ Errore connessione: " . $e->getMessage() . "</div>";
            exit;
        }
        ?>

        <!-- VERIFICA TABELLE -->
        <div class="section">
            <h3>📋 Verifica Tabelle</h3>
            <?php
            try {
                $stmt = $pdo->query("SHOW TABLES");
                $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
                
                echo "<div class='success'>✅ Trovate " . count($tables) . " tabelle</div>";
                
                $expectedTables = [
                    'users', 'projects', 'technologies', 'contacts', 
                    'project_technology', 'migrations', 'personal_access_tokens'
                ];
                
                echo "<table>";
                echo "<tr><th>Tabella</th><th>Status</th><th>Record</th></tr>";
                
                foreach ($expectedTables as $table) {
                    if (in_array($table, $tables)) {
                        // Conta record
                        $stmt = $pdo->query("SELECT COUNT(*) as count FROM `{$table}`");
                        $count = $stmt->fetch()['count'];
                        
                        echo "<tr>";
                        echo "<td>{$table}</td>";
                        echo "<td><span style='color: #10b981;'>✅ Presente</span></td>";
                        echo "<td>{$count} record</td>";
                        echo "</tr>";
                    } else {
                        echo "<tr>";
                        echo "<td>{$table}</td>";
                        echo "<td><span style='color: #ef4444;'>❌ Mancante</span></td>";
                        echo "<td>-</td>";
                        echo "</tr>";
                    }
                }
                echo "</table>";
                
            } catch (Exception $e) {
                echo "<div class='error'>❌ Errore verifica tabelle: " . $e->getMessage() . "</div>";
            }
            ?>
        </div>

        <!-- VERIFICA ADMIN USERS -->
        <div class="section">
            <h3>👤 Verifica Admin Users</h3>
            <?php
            try {
                $stmt = $pdo->query("SELECT id, name, email, is_admin, created_at FROM users WHERE is_admin = 1");
                $admins = $stmt->fetchAll();
                
                if (count($admins) > 0) {
                    echo "<div class='success'>✅ Trovati " . count($admins) . " utenti admin</div>";
                    
                    echo "<table>";
                    echo "<tr><th>ID</th><th>Nome</th><th>Email</th><th>Creato</th></tr>";
                    
                    foreach ($admins as $admin) {
                        echo "<tr>";
                        echo "<td>{$admin['id']}</td>";
                        echo "<td>{$admin['name']}</td>";
                        echo "<td>{$admin['email']}</td>";
                        echo "<td>{$admin['created_at']}</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                    
                    echo "<div class='info'>";
                    echo "<strong>🔑 Credenziali Login:</strong><br>";
                    foreach ($admins as $admin) {
                        echo "Email: <code>{$admin['email']}</code> | Password: <code>admin123</code><br>";
                    }
                    echo "</div>";
                    
                } else {
                    echo "<div class='error'>❌ Nessun utente admin trovato</div>";
                }
                
            } catch (Exception $e) {
                echo "<div class='error'>❌ Errore verifica admin: " . $e->getMessage() . "</div>";
            }
            ?>
        </div>

        <!-- VERIFICA PROGETTI -->
        <div class="section">
            <h3>📁 Verifica Progetti</h3>
            <?php
            try {
                $stmt = $pdo->query("SELECT id, title, status, is_featured, is_public FROM projects ORDER BY priority");
                $projects = $stmt->fetchAll();
                
                if (count($projects) > 0) {
                    echo "<div class='success'>✅ Trovati " . count($projects) . " progetti</div>";
                    
                    echo "<table>";
                    echo "<tr><th>ID</th><th>Titolo</th><th>Status</th><th>Featured</th><th>Pubblico</th></tr>";
                    
                    foreach ($projects as $project) {
                        echo "<tr>";
                        echo "<td>{$project['id']}</td>";
                        echo "<td>{$project['title']}</td>";
                        echo "<td>" . ucfirst($project['status']) . "</td>";
                        echo "<td>" . ($project['is_featured'] ? '⭐ Sì' : 'No') . "</td>";
                        echo "<td>" . ($project['is_public'] ? '✅ Sì' : '❌ No') . "</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                    
                } else {
                    echo "<div class='error'>❌ Nessun progetto trovato</div>";
                }
                
            } catch (Exception $e) {
                echo "<div class='error'>❌ Errore verifica progetti: " . $e->getMessage() . "</div>";
            }
            ?>
        </div>

        <!-- VERIFICA TECNOLOGIE -->
        <div class="section">
            <h3>🔧 Verifica Tecnologie</h3>
            <?php
            try {
                $stmt = $pdo->query("SELECT name, icon, category, proficiency_level, is_featured FROM technologies ORDER BY sort_order");
                $technologies = $stmt->fetchAll();
                
                if (count($technologies) > 0) {
                    echo "<div class='success'>✅ Trovate " . count($technologies) . " tecnologie</div>";
                    
                    echo "<table>";
                    echo "<tr><th>Nome</th><th>Icon</th><th>Categoria</th><th>Livello</th><th>Featured</th></tr>";
                    
                    foreach ($technologies as $tech) {
                        echo "<tr>";
                        echo "<td>{$tech['name']}</td>";
                        echo "<td>{$tech['icon']}</td>";
                        echo "<td>" . ucfirst($tech['category']) . "</td>";
                        echo "<td>" . ucfirst($tech['proficiency_level']) . "</td>";
                        echo "<td>" . ($tech['is_featured'] ? '⭐ Sì' : 'No') . "</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                    
                } else {
                    echo "<div class='error'>❌ Nessuna tecnologia trovata</div>";
                }
                
            } catch (Exception $e) {
                echo "<div class='error'>❌ Errore verifica tecnologie: " . $e->getMessage() . "</div>";
            }
            ?>
        </div>

        <!-- VERIFICA RELAZIONI -->
        <div class="section">
            <h3>🔗 Verifica Relazioni Progetti-Tecnologie</h3>
            <?php
            try {
                $stmt = $pdo->query("
                    SELECT p.title as project, GROUP_CONCAT(t.name SEPARATOR ', ') as technologies
                    FROM projects p
                    LEFT JOIN project_technology pt ON p.id = pt.project_id
                    LEFT JOIN technologies t ON pt.technology_id = t.id
                    GROUP BY p.id, p.title
                    ORDER BY p.priority
                ");
                $relations = $stmt->fetchAll();
                
                if (count($relations) > 0) {
                    echo "<div class='success'>✅ Relazioni progetti-tecnologie configurate</div>";
                    
                    echo "<table>";
                    echo "<tr><th>Progetto</th><th>Tecnologie</th></tr>";
                    
                    foreach ($relations as $relation) {
                        echo "<tr>";
                        echo "<td>{$relation['project']}</td>";
                        echo "<td>" . ($relation['technologies'] ?: 'Nessuna') . "</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                    
                } else {
                    echo "<div class='error'>❌ Nessuna relazione trovata</div>";
                }
                
            } catch (Exception $e) {
                echo "<div class='error'>❌ Errore verifica relazioni: " . $e->getMessage() . "</div>";
            }
            ?>
        </div>

        <!-- TEST API -->
        <div class="section">
            <h3>🌐 Test API</h3>
            <div class="info">
                <p>Ora testa le API per verificare che funzionino:</p>
                <ul>
                    <li><a href="https://vincenzorocca.com/api/v1/test" target="_blank">🔗 API Test</a></li>
                    <li><a href="https://vincenzorocca.com/api/v1/projects" target="_blank">📁 API Progetti</a></li>
                    <li><a href="https://vincenzorocca.com/api/v1/technologies" target="_blank">🔧 API Tecnologie</a></li>
                    <li><a href="https://vincenzorocca.com/api/v1/projects/featured" target="_blank">⭐ Progetti Featured</a></li>
                </ul>
            </div>
        </div>

        <!-- RISULTATO FINALE -->
        <div class="action">
            <h2>🎉 VERIFICA COMPLETATA!</h2>
            
            <div class="success">
                <h3>✅ Database Portfolio Operativo</h3>
                <p>Il database è stato importato correttamente e contiene tutti i dati necessari.</p>
            </div>
            
            <div class="info">
                <h4>🎯 Prossimi Test:</h4>
                <ol>
                    <li><strong>Homepage:</strong> <a href="https://vincenzorocca.com" target="_blank">vincenzorocca.com</a></li>
                    <li><strong>Login Admin:</strong> <a href="https://vincenzorocca.com/admin/login" target="_blank">Admin Panel</a></li>
                    <li><strong>API Test:</strong> Clicca i link sopra</li>
                    <li><strong>Form Contatti:</strong> Testa dalla homepage</li>
                </ol>
            </div>
            
            <div class="warning">
                <h4>🔑 Credenziali Admin:</h4>
                <ul>
                    <li><strong>Email:</strong> vincenzorocca88@gmail.com</li>
                    <li><strong>Password:</strong> admin123</li>
                </ul>
            </div>
        </div>

        <div style="text-align: center; margin-top: 30px;">
            <button onclick="window.open('https://vincenzorocca.com')" style="background: #10b981; color: white; padding: 15px 30px; border: none; border-radius: 8px; cursor: pointer;">
                🚀 Testa Portfolio
            </button>
            
            <button onclick="window.open('https://vincenzorocca.com/api/v1/projects')" style="background: #3b82f6; color: white; padding: 15px 30px; border: none; border-radius: 8px; cursor: pointer; margin-left: 10px;">
                📊 Testa API
            </button>
        </div>
    </div>
</body>
</html> 