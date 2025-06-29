<?php
// Script per aggiungere più tecnologie al database (versione corretta)

// Database connection
$host = 'localhost';
$dbname = 'u336414084_portfolioVince';
$username = 'u336414084_vincenzorocca8';
$password = 'Ciaociao52.?';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Connessione database riuscita\n";
    
    // Nuove tecnologie da aggiungere
    $newTechnologies = [
        // Frontend Frameworks/Libraries
        ['name' => 'Next.js', 'icon' => 'fab fa-react', 'color' => '#000000', 'category' => 'frontend', 'proficiency_level' => 'intermediate', 'description' => 'Framework React per produzione', 'is_featured' => 0, 'sort_order' => 20],
        ['name' => 'Nuxt.js', 'icon' => 'fab fa-vuejs', 'color' => '#00DC82', 'category' => 'frontend', 'proficiency_level' => 'intermediate', 'description' => 'Framework Vue.js universale', 'is_featured' => 0, 'sort_order' => 21],
        ['name' => 'Angular', 'icon' => 'fab fa-angular', 'color' => '#DD0031', 'category' => 'frontend', 'proficiency_level' => 'beginner', 'description' => 'Framework TypeScript di Google', 'is_featured' => 0, 'sort_order' => 22],
        ['name' => 'Svelte', 'icon' => 'fas fa-fire', 'color' => '#FF3E00', 'category' => 'frontend', 'proficiency_level' => 'beginner', 'description' => 'Compilatore per UI reattive', 'is_featured' => 0, 'sort_order' => 23],
        ['name' => 'SASS/SCSS', 'icon' => 'fab fa-sass', 'color' => '#CF649A', 'category' => 'frontend', 'proficiency_level' => 'expert', 'description' => 'Preprocessore CSS avanzato', 'is_featured' => 1, 'sort_order' => 24],
        
        // Backend Technologies
        ['name' => 'Node.js', 'icon' => 'fab fa-node-js', 'color' => '#339933', 'category' => 'backend', 'proficiency_level' => 'advanced', 'description' => 'Runtime JavaScript server-side', 'is_featured' => 1, 'sort_order' => 25],
        ['name' => 'Express.js', 'icon' => 'fas fa-server', 'color' => '#000000', 'category' => 'backend', 'proficiency_level' => 'advanced', 'description' => 'Framework web per Node.js', 'is_featured' => 0, 'sort_order' => 26],
        ['name' => 'Python', 'icon' => 'fab fa-python', 'color' => '#3776AB', 'category' => 'backend', 'proficiency_level' => 'intermediate', 'description' => 'Linguaggio versatile e potente', 'is_featured' => 0, 'sort_order' => 27],
        ['name' => 'Django', 'icon' => 'fas fa-cog', 'color' => '#092E20', 'category' => 'backend', 'proficiency_level' => 'beginner', 'description' => 'Framework web Python', 'is_featured' => 0, 'sort_order' => 28],
        ['name' => 'FastAPI', 'icon' => 'fas fa-bolt', 'color' => '#009688', 'category' => 'backend', 'proficiency_level' => 'beginner', 'description' => 'API moderne e veloci con Python', 'is_featured' => 0, 'sort_order' => 29],
        
        // Database
        ['name' => 'Redis', 'icon' => 'fas fa-database', 'color' => '#DC382D', 'category' => 'database', 'proficiency_level' => 'intermediate', 'description' => 'Database in-memory per caching', 'is_featured' => 0, 'sort_order' => 30],
        ['name' => 'SQLite', 'icon' => 'fas fa-database', 'color' => '#003B57', 'category' => 'database', 'proficiency_level' => 'advanced', 'description' => 'Database leggero e veloce', 'is_featured' => 0, 'sort_order' => 31],
        ['name' => 'Firebase', 'icon' => 'fas fa-fire', 'color' => '#FFCA28', 'category' => 'database', 'proficiency_level' => 'intermediate', 'description' => 'Database NoSQL di Google', 'is_featured' => 0, 'sort_order' => 32],
        
        // DevOps & Tools
        ['name' => 'Docker', 'icon' => 'fab fa-docker', 'color' => '#2496ED', 'category' => 'tools', 'proficiency_level' => 'intermediate', 'description' => 'Containerizzazione applicazioni', 'is_featured' => 1, 'sort_order' => 33],
        ['name' => 'Webpack', 'icon' => 'fas fa-cube', 'color' => '#8DD6F9', 'category' => 'tools', 'proficiency_level' => 'advanced', 'description' => 'Module bundler per JavaScript', 'is_featured' => 0, 'sort_order' => 34],
        ['name' => 'Vite', 'icon' => 'fas fa-bolt', 'color' => '#646CFF', 'category' => 'tools', 'proficiency_level' => 'expert', 'description' => 'Build tool veloce per frontend', 'is_featured' => 1, 'sort_order' => 35],
        ['name' => 'ESLint', 'icon' => 'fas fa-check-circle', 'color' => '#4B32C3', 'category' => 'tools', 'proficiency_level' => 'expert', 'description' => 'Linter per JavaScript/TypeScript', 'is_featured' => 0, 'sort_order' => 36],
        ['name' => 'Prettier', 'icon' => 'fas fa-magic', 'color' => '#F7B93E', 'category' => 'tools', 'proficiency_level' => 'expert', 'description' => 'Formatter automatico del codice', 'is_featured' => 0, 'sort_order' => 37],
        
        // Mobile
        ['name' => 'React Native', 'icon' => 'fab fa-react', 'color' => '#61DAFB', 'category' => 'mobile', 'proficiency_level' => 'intermediate', 'description' => 'App native con React', 'is_featured' => 0, 'sort_order' => 38],
        ['name' => 'Flutter', 'icon' => 'fas fa-mobile-alt', 'color' => '#02569B', 'category' => 'mobile', 'proficiency_level' => 'beginner', 'description' => 'Framework mobile di Google', 'is_featured' => 0, 'sort_order' => 39],
        ['name' => 'Ionic', 'icon' => 'fas fa-mobile', 'color' => '#3880FF', 'category' => 'mobile', 'proficiency_level' => 'beginner', 'description' => 'App ibride cross-platform', 'is_featured' => 0, 'sort_order' => 40],
        
        // Testing
        ['name' => 'Jest', 'icon' => 'fas fa-vial', 'color' => '#C21325', 'category' => 'tools', 'proficiency_level' => 'intermediate', 'description' => 'Testing framework JavaScript', 'is_featured' => 0, 'sort_order' => 41],
        ['name' => 'Cypress', 'icon' => 'fas fa-robot', 'color' => '#17202C', 'category' => 'tools', 'proficiency_level' => 'beginner', 'description' => 'Testing end-to-end', 'is_featured' => 0, 'sort_order' => 42],
        ['name' => 'PHPUnit', 'icon' => 'fab fa-php', 'color' => '#777BB4', 'category' => 'tools', 'proficiency_level' => 'intermediate', 'description' => 'Testing framework per PHP', 'is_featured' => 0, 'sort_order' => 43],
        
        // Cloud & Services
        ['name' => 'AWS', 'icon' => 'fab fa-aws', 'color' => '#FF9900', 'category' => 'tools', 'proficiency_level' => 'beginner', 'description' => 'Servizi cloud Amazon', 'is_featured' => 0, 'sort_order' => 44],
        ['name' => 'Vercel', 'icon' => 'fas fa-cloud', 'color' => '#000000', 'category' => 'tools', 'proficiency_level' => 'intermediate', 'description' => 'Deploy e hosting moderno', 'is_featured' => 0, 'sort_order' => 45],
        ['name' => 'Netlify', 'icon' => 'fas fa-globe', 'color' => '#00C7B7', 'category' => 'tools', 'proficiency_level' => 'intermediate', 'description' => 'Deploy automatico per frontend', 'is_featured' => 0, 'sort_order' => 46],
        
        // API & Communication
        ['name' => 'GraphQL', 'icon' => 'fas fa-project-diagram', 'color' => '#E10098', 'category' => 'backend', 'proficiency_level' => 'beginner', 'description' => 'Query language per API', 'is_featured' => 0, 'sort_order' => 47],
        ['name' => 'REST API', 'icon' => 'fas fa-exchange-alt', 'color' => '#61DAFB', 'category' => 'backend', 'proficiency_level' => 'expert', 'description' => 'Architettura API standard', 'is_featured' => 1, 'sort_order' => 48],
        ['name' => 'Socket.io', 'icon' => 'fas fa-plug', 'color' => '#010101', 'category' => 'backend', 'proficiency_level' => 'intermediate', 'description' => 'Comunicazione real-time', 'is_featured' => 0, 'sort_order' => 49],
        
        // CMS & E-commerce
        ['name' => 'WordPress', 'icon' => 'fab fa-wordpress', 'color' => '#21759B', 'category' => 'other', 'proficiency_level' => 'advanced', 'description' => 'CMS più popolare al mondo', 'is_featured' => 0, 'sort_order' => 50],
        ['name' => 'Shopify', 'icon' => 'fas fa-shopping-cart', 'color' => '#7AB55C', 'category' => 'other', 'proficiency_level' => 'intermediate', 'description' => 'Piattaforma e-commerce', 'is_featured' => 0, 'sort_order' => 51],
        ['name' => 'Strapi', 'icon' => 'fas fa-layer-group', 'color' => '#2E7EEA', 'category' => 'backend', 'proficiency_level' => 'beginner', 'description' => 'Headless CMS moderno', 'is_featured' => 0, 'sort_order' => 52]
    ];
    
    $insertedCount = 0;
    $skippedCount = 0;
    
    foreach ($newTechnologies as $tech) {
        // Controlla se esiste già
        $stmt = $pdo->prepare("SELECT id FROM technologies WHERE name = ?");
        $stmt->execute([$tech['name']]);
        
        if ($stmt->fetch()) {
            echo "⏭️  Saltata: {$tech['name']} (già esistente)\n";
            $skippedCount++;
            continue;
        }
        
        // Inserisci nuova tecnologia
        $stmt = $pdo->prepare("
            INSERT INTO technologies (
                name, icon, color, category, proficiency_level, 
                description, is_featured, sort_order, created_at, updated_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
        ");
        
        $stmt->execute([
            $tech['name'],
            $tech['icon'],
            $tech['color'],
            $tech['category'],
            $tech['proficiency_level'],
            $tech['description'],
            $tech['is_featured'],
            $tech['sort_order']
        ]);
        
        echo "✅ Aggiunta: {$tech['name']} ({$tech['category']})\n";
        $insertedCount++;
    }
    
    echo "\n📊 RIEPILOGO:\n";
    echo "✅ Tecnologie aggiunte: $insertedCount\n";
    echo "⏭️  Tecnologie saltate: $skippedCount\n";
    
    // Conta totale tecnologie
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM technologies");
    $total = $stmt->fetch()['total'];
    echo "📈 Totale tecnologie nel database: $total\n";
    
    echo "\n🎯 Ora l'admin può scegliere tra $total tecnologie diverse!\n";
    
} catch (PDOException $e) {
    echo "❌ Errore database: " . $e->getMessage() . "\n";
}
?> 