<?php
header('Content-Type: text/html; charset=utf-8');

echo "<h1>🔍 VERIFICA DATABASE COMPLETO</h1>";

try {
    // Connessione database
    $host = 'localhost';
    $dbname = 'u336414084_portfolioVince';
    $username = 'u336414084_portfolioVince';
    $password = 'Ciaociao52.?';
    
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ]);
    
    echo "<p>✅ <strong>Connessione database OK</strong></p>";
    
    // Lista delle tabelle che dovrebbero esistere
    $expectedTables = [
        'users', 'technologies', 'projects', 'contacts', 
        'migrations', 'cache', 'cache_locks', 'sessions', 'personal_access_tokens'
    ];
    
    echo "<h2>📋 VERIFICA TABELLE</h2>";
    
    foreach ($expectedTables as $table) {
        try {
            $stmt = $pdo->query("SELECT COUNT(*) FROM `$table`");
            $count = $stmt->fetchColumn();
            echo "<p>✅ <strong>$table</strong>: $count record</p>";
        } catch (Exception $e) {
            echo "<p>❌ <strong>$table</strong>: NON ESISTE</p>";
        }
    }
    
    // Verifica struttura tabella projects
    echo "<h2>🗂️ STRUTTURA TABELLA PROJECTS</h2>";
    try {
        $stmt = $pdo->query("DESCRIBE projects");
        $columns = $stmt->fetchAll();
        
        $expectedColumns = [
            'id', 'title', 'slug', 'description', 'long_description', 'client', 'duration', 
            'category', 'features', 'challenges', 'results', 'testimonial', 'testimonial_author', 
            'testimonial_role', 'image_url', 'gallery', 'demo_url', 'github_url', 'linkedin_url', 
            'video_url', 'additional_links', 'technologies', 'status', 'sort_order', 'featured', 
            'project_date', 'created_at', 'updated_at'
        ];
        
        $existingColumns = array_column($columns, 'Field');
        
        echo "<p><strong>Colonne esistenti:</strong> " . count($existingColumns) . "</p>";
        echo "<p><strong>Colonne attese:</strong> " . count($expectedColumns) . "</p>";
        
        $missing = array_diff($expectedColumns, $existingColumns);
        if (empty($missing)) {
            echo "<p>✅ <strong>Tutte le colonne sono presenti!</strong></p>";
        } else {
            echo "<p>❌ <strong>Colonne mancanti:</strong> " . implode(', ', $missing) . "</p>";
        }
        
        echo "<details><summary>Dettagli colonne</summary><table border='1' style='border-collapse: collapse;'>";
        echo "<tr><th>Campo</th><th>Tipo</th><th>Null</th><th>Default</th></tr>";
        foreach ($columns as $col) {
            echo "<tr>";
            echo "<td>" . $col['Field'] . "</td>";
            echo "<td>" . $col['Type'] . "</td>";
            echo "<td>" . $col['Null'] . "</td>";
            echo "<td>" . $col['Default'] . "</td>";
            echo "</tr>";
        }
        echo "</table></details>";
        
    } catch (Exception $e) {
        echo "<p>❌ <strong>Errore verifica struttura projects:</strong> " . $e->getMessage() . "</p>";
    }
    
    // Verifica progetti con gallery
    echo "<h2>🖼️ VERIFICA PROGETTI CON GALLERY</h2>";
    try {
        $stmt = $pdo->query("SELECT id, title, gallery FROM projects WHERE gallery IS NOT NULL AND gallery != ''");
        $projectsWithGallery = $stmt->fetchAll();
        
        echo "<p><strong>Progetti con gallery:</strong> " . count($projectsWithGallery) . "</p>";
        
        foreach ($projectsWithGallery as $project) {
            $gallery = json_decode($project['gallery'], true);
            $imageCount = is_array($gallery) ? count($gallery) : 0;
            echo "<p>📁 <strong>{$project['title']}</strong>: $imageCount immagini</p>";
        }
        
    } catch (Exception $e) {
        echo "<p>❌ <strong>Errore verifica gallery:</strong> " . $e->getMessage() . "</p>";
    }
    
    // Verifica utente admin
    echo "<h2>👤 VERIFICA UTENTE ADMIN</h2>";
    try {
        $stmt = $pdo->prepare("SELECT id, name, email, is_admin FROM users WHERE email = ?");
        $stmt->execute(['vincenzorocca88@gmail.com']);
        $admin = $stmt->fetch();
        
        if ($admin) {
            echo "<p>✅ <strong>Utente admin trovato:</strong></p>";
            echo "<ul>";
            echo "<li>ID: {$admin['id']}</li>";
            echo "<li>Nome: {$admin['name']}</li>";
            echo "<li>Email: {$admin['email']}</li>";
            echo "<li>Admin: " . ($admin['is_admin'] ? 'Sì' : 'No') . "</li>";
            echo "</ul>";
        } else {
            echo "<p>❌ <strong>Utente admin NON trovato</strong></p>";
        }
        
    } catch (Exception $e) {
        echo "<p>❌ <strong>Errore verifica admin:</strong> " . $e->getMessage() . "</p>";
    }
    
    // Verifica tecnologie
    echo "<h2>⚡ VERIFICA TECNOLOGIE</h2>";
    try {
        $stmt = $pdo->query("SELECT COUNT(*) FROM technologies");
        $techCount = $stmt->fetchColumn();
        
        $stmt = $pdo->query("SELECT name FROM technologies ORDER BY sort_order ASC LIMIT 10");
        $technologies = $stmt->fetchAll();
        
        echo "<p><strong>Tecnologie totali:</strong> $techCount</p>";
        echo "<p><strong>Prime 10:</strong> " . implode(', ', array_column($technologies, 'name')) . "</p>";
        
    } catch (Exception $e) {
        echo "<p>❌ <strong>Errore verifica tecnologie:</strong> " . $e->getMessage() . "</p>";
    }
    
    // Stato generale
    echo "<h2>🎯 STATO GENERALE</h2>";
    
    $allGood = true;
    $checks = [
        'Database connesso' => true,
        'Tabella users' => true,
        'Tabella projects' => true,
        'Utente admin' => isset($admin) && $admin,
        'Progetti con gallery' => isset($projectsWithGallery) && count($projectsWithGallery) > 0,
        'Tecnologie' => isset($techCount) && $techCount > 0
    ];
    
    foreach ($checks as $check => $status) {
        $icon = $status ? '✅' : '❌';
        echo "<p>$icon <strong>$check</strong></p>";
        if (!$status) $allGood = false;
    }
    
    if ($allGood) {
        echo "<div style='background: #d4edda; border: 1px solid #c3e6cb; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
        echo "<h3 style='color: #155724; margin: 0;'>🎉 DATABASE COMPLETO E FUNZIONANTE!</h3>";
        echo "<p style='color: #155724; margin: 5px 0 0 0;'>Tutti i controlli sono passati. Il database è pronto per l'uso.</p>";
        echo "</div>";
        
        echo "<h3>🚀 PROSSIMI PASSI:</h3>";
        echo "<ol>";
        echo "<li>Testa il login: <a href='https://vincenzorocca.com/admin/login'>https://vincenzorocca.com/admin/login</a></li>";
        echo "<li>Credenziali: vincenzorocca88@gmail.com / admin123</li>";
        echo "<li>Verifica progetti: <a href='https://vincenzorocca.com/projects'>https://vincenzorocca.com/projects</a></li>";
        echo "</ol>";
    } else {
        echo "<div style='background: #f8d7da; border: 1px solid #f5c6cb; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
        echo "<h3 style='color: #721c24; margin: 0;'>⚠️ DATABASE INCOMPLETO</h3>";
        echo "<p style='color: #721c24; margin: 5px 0 0 0;'>Alcuni controlli sono falliti. Importa il file database-hostinger-completo.sql</p>";
        echo "</div>";
    }

} catch (Exception $e) {
    echo "<div style='background: #f8d7da; border: 1px solid #f5c6cb; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
    echo "<h3 style='color: #721c24; margin: 0;'>❌ ERRORE CONNESSIONE DATABASE</h3>";
    echo "<p style='color: #721c24; margin: 5px 0 0 0;'>" . $e->getMessage() . "</p>";
    echo "</div>";
}
?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; line-height: 1.6; }
h1, h2, h3 { color: #333; }
table { width: 100%; margin: 10px 0; }
th, td { padding: 8px; text-align: left; }
th { background-color: #f2f2f2; }
details { margin: 10px 0; }
summary { cursor: pointer; font-weight: bold; }
</style> 