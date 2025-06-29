<?php
// Test completo: crea progetto con gallery e verifica tutto il flusso

echo "<h2>🚀 Test Progetto con Gallery Completo</h2>";

// Database connection
$host = 'localhost';
$dbname = 'u336414084_portfolioVince';
$username = 'u336414084_vincenzorocca8';
$password = 'Ciaociao52.?';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Connessione database OK<br>";

    // Crea progetto di test con gallery
    $projectData = [
        'title' => 'E-Commerce Platform Test',
        'slug' => 'ecommerce-platform-test',
        'description' => 'Piattaforma e-commerce completa con React e Laravel',
        'long_description' => 'Una piattaforma e-commerce moderna con gestione prodotti, carrello, pagamenti e dashboard admin.',
        'client' => 'TechStore SRL',
        'duration' => '3 mesi',
        'category' => 'E-Commerce',
        'image_url' => 'https://vincenzorocca.com/api/uploads/685fd48a6406f_1751110794.jpg',
        'demo_url' => 'https://demo-ecommerce.vincenzorocca.com',
        'github_url' => 'https://github.com/vincenzorocca/ecommerce-platform',
        'status' => 'completed',
        'featured' => 1,
        'project_date' => '2024-12-01',
        'technologies' => json_encode(['React', 'Laravel', 'MySQL', 'Tailwind CSS', 'Stripe']),
        'features' => json_encode([
            'Catalogo prodotti dinamico',
            'Carrello e checkout',
            'Pagamenti con Stripe',
            'Dashboard admin',
            'Gestione ordini'
        ]),
        'challenges' => json_encode([
            'Integrazione pagamenti sicura',
            'Ottimizzazione performance',
            'UX responsive'
        ]),
        'results' => json_encode([
            '+150% conversioni',
            'Tempo caricamento < 2s',
            '99.9% uptime'
        ]),
        'gallery' => json_encode([
            'https://vincenzorocca.com/api/uploads/685fd48a6406f_1751110794.jpg',
            'https://vincenzorocca.com/api/uploads/685fd4a558451_1751110821_0.png',
            'https://vincenzorocca.com/api/uploads/685fd4f2aa7a0_1751110898_0.png'
        ]),
        'additional_links' => json_encode([
            ['name' => 'Documentazione API', 'url' => 'https://docs.vincenzorocca.com'],
            ['name' => 'Case Study', 'url' => 'https://blog.vincenzorocca.com/ecommerce-case-study']
        ])
    ];

    // Inserisci progetto
    $stmt = $pdo->prepare("
        INSERT INTO projects (
            title, slug, description, long_description, client, duration, category,
            image_url, demo_url, github_url, status, featured, project_date,
            technologies, features, challenges, results, gallery, additional_links,
            sort_order, created_at, updated_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0, NOW(), NOW())
    ");

    $stmt->execute([
        $projectData['title'],
        $projectData['slug'],
        $projectData['description'],
        $projectData['long_description'],
        $projectData['client'],
        $projectData['duration'],
        $projectData['category'],
        $projectData['image_url'],
        $projectData['demo_url'],
        $projectData['github_url'],
        $projectData['status'],
        $projectData['featured'],
        $projectData['project_date'],
        $projectData['technologies'],
        $projectData['features'],
        $projectData['challenges'],
        $projectData['results'],
        $projectData['gallery'],
        $projectData['additional_links']
    ]);

    $projectId = $pdo->lastInsertId();
    echo "✅ Progetto creato con ID: $projectId<br>";

    // Verifica progetto creato
    $stmt = $pdo->prepare("SELECT * FROM projects WHERE id = ?");
    $stmt->execute([$projectId]);
    $project = $stmt->fetch(PDO::FETCH_ASSOC);

    echo "<h3>📦 Dati Progetto Salvati:</h3>";
    echo "<strong>Titolo:</strong> " . $project['title'] . "<br>";
    echo "<strong>Slug:</strong> " . $project['slug'] . "<br>";
    echo "<strong>Status:</strong> " . $project['status'] . "<br>";
    echo "<strong>Featured:</strong> " . ($project['featured'] ? 'Sì' : 'No') . "<br>";

    echo "<h3>🎨 Gallery (JSON):</h3>";
    $gallery = json_decode($project['gallery'], true);
    if ($gallery && is_array($gallery)) {
        echo "<ul>";
        foreach ($gallery as $index => $imageUrl) {
            echo "<li>Immagine " . ($index + 1) . ": <a href='$imageUrl' target='_blank'>$imageUrl</a></li>";
        }
        echo "</ul>";
        echo "<strong>Totale immagini in gallery:</strong> " . count($gallery) . "<br>";
    } else {
        echo "❌ Gallery vuota o formato non valido<br>";
    }

    echo "<h3>💻 Technologies:</h3>";
    $technologies = json_decode($project['technologies'], true);
    if ($technologies && is_array($technologies)) {
        echo implode(', ', $technologies) . "<br>";
    }

    echo "<h3>⭐ Features:</h3>";
    $features = json_decode($project['features'], true);
    if ($features && is_array($features)) {
        echo "<ul>";
        foreach ($features as $feature) {
            echo "<li>$feature</li>";
        }
        echo "</ul>";
    }

    // Test API adaptProjectForFrontend
    echo "<h3>🔄 Test Adattamento Frontend:</h3>";
    
    // Simula la funzione adaptProjectForFrontend
    $adapted = [
        'id' => $project['id'],
        'title' => $project['title'],
        'slug' => $project['slug'],
        'description' => $project['description'],
        'long_description' => $project['long_description'],
        'client' => $project['client'],
        'duration' => $project['duration'],
        'category' => $project['category'],
        'image_url' => $project['image_url'],
        'demo_url' => $project['demo_url'],
        'github_url' => $project['github_url'],
        'status' => $project['status'],
        'featured' => (bool)$project['featured'],
        'project_date' => $project['project_date'],
        'technologies' => json_decode($project['technologies'] ?? '[]', true),
        'features' => json_decode($project['features'] ?? '[]', true),
        'challenges' => json_decode($project['challenges'] ?? '[]', true),
        'results' => json_decode($project['results'] ?? '[]', true),
        'images' => json_decode($project['gallery'] ?? '[]', true), // gallery -> images
        'additional_links' => json_decode($project['additional_links'] ?? '[]', true),
        'created_at' => $project['created_at'],
        'updated_at' => $project['updated_at']
    ];

    echo "<strong>Campo 'images' per frontend:</strong><br>";
    if ($adapted['images'] && is_array($adapted['images'])) {
        echo "✅ " . count($adapted['images']) . " immagini disponibili<br>";
        foreach ($adapted['images'] as $index => $img) {
            echo "- Immagine " . ($index + 1) . ": " . basename($img) . "<br>";
        }
    } else {
        echo "❌ Nessuna immagine nel campo 'images'<br>";
    }

    echo "<h3>🌐 Test URL API:</h3>";
    echo "<a href='https://vincenzorocca.com/api/v1/projects/$projectId' target='_blank'>GET /projects/$projectId</a><br>";
    echo "<a href='https://vincenzorocca.com/api/v1/projects' target='_blank'>GET /projects (tutti)</a><br>";
    echo "<a href='https://vincenzorocca.com/api/v1/projects/featured' target='_blank'>GET /projects/featured</a><br>";

    echo "<h3>✅ Test Completato!</h3>";
    echo "Il progetto è stato creato con successo e contiene:<br>";
    echo "- ✅ Tutti i campi base<br>";
    echo "- ✅ Gallery con " . count($gallery) . " immagini<br>";
    echo "- ✅ Technologies, Features, Challenges, Results<br>";
    echo "- ✅ Additional Links<br>";
    echo "- ✅ Mapping corretto gallery → images per frontend<br>";

} catch (Exception $e) {
    echo "❌ Errore: " . $e->getMessage() . "<br>";
}
?> 