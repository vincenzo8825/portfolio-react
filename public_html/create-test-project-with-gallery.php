<?php
// Script per creare un progetto di test con gallery

$host = 'localhost';
$dbname = 'u336414084_portfolioVince';
$username = 'u336414084_vincenzorocca8';
$password = 'Ciaociao52.?';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Gallery con 3 immagini di esempio (usando quelle già caricate)
    $gallery = [
        'https://vincenzorocca.com/api/uploads/685fd48a6406f_1751110794.jpg',
        'https://vincenzorocca.com/api/uploads/685fd997a2091_1751112087.png',
        'https://vincenzorocca.com/api/uploads/685fe1c76ba95_1751114183.png'
    ];
    
    $technologies = ['React', 'Laravel', 'JavaScript', 'CSS', 'MySQL'];
    $features = ['Gallery dinamica', 'Navigazione immagini', 'Responsive design', 'Admin panel'];
    
    // Inserisci progetto di test
    $stmt = $pdo->prepare("
        INSERT INTO projects (
            title, slug, description, short_description, content, 
            technologies, features, gallery, status, is_featured, is_public, 
            demo_url, github_url, created_at, updated_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
    ");
    
    $title = 'Test Gallery Project ' . date('H:i:s');
    $slug = 'test-gallery-project-' . time();
    $description = 'Progetto di test per verificare la funzionalità gallery con navigazione';
    $content = 'Questo progetto è stato creato automaticamente per testare la funzionalità gallery. Include 3 immagini di esempio con navigazione completa.';
    
    $stmt->execute([
        $title,
        $slug,
        $description,
        $description,
        $content,
        json_encode($technologies),
        json_encode($features),
        json_encode($gallery),
        'completed',
        1, // featured
        1, // public
        'https://vincenzorocca.com',
        'https://github.com/vincenzorocca'
    ]);
    
    $projectId = $pdo->lastInsertId();
    
    echo "✅ Progetto creato con successo!\n";
    echo "🆔 ID: $projectId\n";
    echo "📝 Titolo: $title\n";
    echo "🖼️ Gallery: " . count($gallery) . " immagini\n";
    echo "🔗 URL: https://vincenzorocca.com/projects/$projectId\n\n";
    
    echo "🧪 Test ora:\n";
    echo "1. Vai su https://vincenzorocca.com/projects/$projectId\n";
    echo "2. Verifica che si vedano tutte e 3 le immagini della gallery\n";
    echo "3. Testa la navigazione con frecce sinistra/destra\n";
    echo "4. Testa i thumbnails in basso\n";
    echo "5. Apri console browser per vedere i log di debug\n";
    
} catch (Exception $e) {
    echo "❌ Errore: " . $e->getMessage() . "\n";
}
?> 