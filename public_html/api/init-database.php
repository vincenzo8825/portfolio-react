<?php
// Script per inizializzare il database con tabelle e dati di base

// Database configuration - Hostinger MySQL
$dbConfig = [
    'host' => 'localhost',
    'dbname' => 'u336414084_portfolioVince',
    'username' => 'u336414084_vincenzorocca8',
    'password' => 'Ciaociao52.?',
    'charset' => 'utf8mb4'
];

try {
    $dsn = "mysql:host={$dbConfig['host']};dbname={$dbConfig['dbname']};charset={$dbConfig['charset']}";
    $pdo = new PDO($dsn, $dbConfig['username'], $dbConfig['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ]);

    echo "✅ Connessione database riuscita\n";

    // 1. Inserisci utente admin se non esiste
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
    $stmt->execute(['vincenzorocca88@gmail.com']);

    if ($stmt->fetchColumn() == 0) {
        $hashedPassword = password_hash('admin123', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("
            INSERT INTO users (name, email, password, is_admin, created_at, updated_at)
            VALUES (?, ?, ?, 1, NOW(), NOW())
        ");
        $stmt->execute(['Vincenzo Rocca', 'vincenzorocca88@gmail.com', $hashedPassword]);
        echo "✅ Utente admin creato\n";
    } else {
        echo "✅ Utente admin già esistente\n";
    }

    // 2. Inserisci tecnologie se non esistono
    $technologies = [
        ['React', 'react', '⚛️', '#61DAFB', 'frontend', 1, 1],
        ['Laravel', 'laravel', '🚀', '#FF2D20', 'backend', 1, 2],
        ['JavaScript', 'javascript', '💛', '#F7DF1E', 'frontend', 1, 3],
        ['PHP', 'php', '🐘', '#777BB4', 'backend', 1, 4],
        ['MySQL', 'mysql', '🗄️', '#4479A1', 'database', 1, 5],
        ['Tailwind CSS', 'tailwind-css', '🎨', '#06B6D4', 'frontend', 1, 6],
        ['Node.js', 'nodejs', '🟢', '#339933', 'backend', 0, 7],
        ['Vue.js', 'vuejs', '💚', '#4FC08D', 'frontend', 0, 8],
        ['Docker', 'docker', '🐳', '#2496ED', 'tools', 0, 9],
        ['Git', 'git', '📚', '#F05032', 'tools', 0, 10],
        ['TypeScript', 'typescript', '💙', '#3178C6', 'frontend', 0, 11],
        ['Python', 'python', '🐍', '#3776AB', 'backend', 0, 12],
        ['MongoDB', 'mongodb', '🍃', '#47A248', 'database', 0, 13],
        ['AWS', 'aws', '☁️', '#FF9900', 'tools', 0, 14],
        ['Express.js', 'expressjs', '⚡', '#000000', 'backend', 0, 15],
        ['Bootstrap', 'bootstrap', '🅱️', '#7952B3', 'frontend', 0, 16],
        ['Figma', 'figma', '🎨', '#F24E1E', 'tools', 0, 17],
        ['Vite', 'vite', '⚡', '#646CFF', 'tools', 0, 18]
    ];

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM technologies");
    $stmt->execute();

    if ($stmt->fetchColumn() == 0) {
        $stmt = $pdo->prepare("
            INSERT INTO technologies (name, slug, icon, color, category, featured, sort_order, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
        ");

        foreach ($technologies as $tech) {
            $stmt->execute($tech);
        }
        echo "✅ " . count($technologies) . " tecnologie inserite\n";
    } else {
        echo "✅ Tecnologie già esistenti\n";
    }

    // 3. Inserisci progetti di esempio se non esistono
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM projects");
    $stmt->execute();

    if ($stmt->fetchColumn() == 0) {
        $projects = [
            [
                'E-commerce Platform Avanzato',
                'e-commerce-platform-avanzato',
                'Piattaforma e-commerce completa con gestione prodotti, carrello, pagamenti e admin panel',
                'Sviluppo di una piattaforma e-commerce moderna e scalabile con funzionalità avanzate di gestione prodotti, sistema di pagamenti integrato, dashboard amministrativa completa e ottimizzazioni SEO.',
                'https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=800',
                'https://demo.vincenzorocca.com/ecommerce',
                'https://github.com/vincenzorocca/ecommerce-platform',
                '',
                '',
                json_encode(['React', 'Laravel', 'MySQL', 'Tailwind CSS', 'Stripe']),
                json_encode(['Gestione Prodotti', 'Carrello Avanzato', 'Pagamenti Sicuri', 'Admin Dashboard', 'SEO Ottimizzato']),
                json_encode(['Integrazione Pagamenti', 'Performance Optimization', 'Sicurezza Dati']),
                json_encode(['95% Performance Score', 'Zero Vulnerabilità', '1000+ Prodotti Gestiti']),
                json_encode([]),
                json_encode([]),
                'completed',
                1,
                'TechCorp Solutions',
                '4 mesi',
                'E-Commerce',
                '2024-01-15'
            ],
            [
                'Dashboard Analytics Real-time',
                'dashboard-analytics-realtime',
                'Dashboard per analisi dati in tempo reale con grafici interattivi e reportistica avanzata',
                'Creazione di una dashboard completa per l\'analisi di dati business in tempo reale, con visualizzazioni interattive, export automatici e sistema di alerting personalizzabile.',
                'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=800',
                'https://demo.vincenzorocca.com/dashboard',
                'https://github.com/vincenzorocca/analytics-dashboard',
                '',
                '',
                json_encode(['React', 'Node.js', 'MongoDB', 'D3.js', 'Socket.io']),
                json_encode(['Real-time Charts', 'Export Automatico', 'Alerting System', 'Multi-tenancy']),
                json_encode(['Performance Real-time', 'Scalabilità Dati', 'UX Complessa']),
                json_encode(['10M+ Data Points', 'Sub-second Response', '99.9% Uptime']),
                json_encode([]),
                json_encode([]),
                'completed',
                1,
                'DataViz Inc',
                '3 mesi',
                'Dashboard',
                '2024-02-20'
            ],
            [
                'App Mobile Task Management',
                'app-mobile-task-management',
                'Applicazione mobile per gestione attività con sincronizzazione cloud e collaborazione team',
                'Sviluppo di un\'app mobile nativa per la gestione di attività e progetti, con funzionalità di collaborazione team, sincronizzazione cloud e notifiche push intelligenti.',
                'https://images.unsplash.com/photo-1611224923853-80b023f02d71?w=800',
                'https://apps.apple.com/app/taskmaster-pro',
                'https://github.com/vincenzorocca/taskmaster-mobile',
                '',
                '',
                json_encode(['React Native', 'Firebase', 'Node.js', 'TypeScript']),
                json_encode(['Sync Cloud', 'Collaboration', 'Push Notifications', 'Offline Mode']),
                json_encode(['Performance Mobile', 'Sync Conflicts', 'Battery Optimization']),
                json_encode(['4.8★ Rating', '50K+ Downloads', '95% Retention']),
                json_encode([]),
                json_encode([]),
                'completed',
                1,
                'Productivity Labs',
                '5 mesi',
                'Mobile App',
                '2024-03-10'
            ]
        ];

        $stmt = $pdo->prepare("
            INSERT INTO projects (
                title, slug, description, long_description, image_url, demo_url,
                github_url, linkedin_url, video_url, technologies, features,
                challenges, results, gallery, additional_links, status, featured,
                client, duration, category, project_date, created_at, updated_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
        ");

        foreach ($projects as $project) {
            $stmt->execute($project);
        }
        echo "✅ " . count($projects) . " progetti di esempio inseriti\n";
    } else {
        echo "✅ Progetti già esistenti\n";
    }

    echo "\n🎉 Database inizializzato con successo!\n";
    echo "📧 Email admin: vincenzorocca88@gmail.com\n";
    echo "🔑 Password admin: admin123\n";

} catch (Exception $e) {
    echo "❌ Errore: " . $e->getMessage() . "\n";
}
?>
