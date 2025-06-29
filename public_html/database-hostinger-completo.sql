-- =====================================================
-- DATABASE COMPLETO PER HOSTINGER
-- Portfolio Vincenzo Rocca - Struttura completa
-- =====================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- =====================================================
-- TABELLA: users
-- =====================================================
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Inserisci utente admin (password: admin123)
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `is_admin`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Vincenzo Rocca', 'vincenzorocca88@gmail.com', NULL, 1, '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NOW(), NOW());

-- =====================================================
-- TABELLA: technologies
-- =====================================================
DROP TABLE IF EXISTS `technologies`;
CREATE TABLE `technologies` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category` enum('frontend','backend','database','tools','mobile','other') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'other',
  `proficiency` int NOT NULL DEFAULT '1',
  `sort_order` int NOT NULL DEFAULT '0',
  `featured` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `technologies_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Inserisci tecnologie principali
INSERT INTO `technologies` (`name`, `slug`, `icon`, `color`, `category`, `proficiency`, `sort_order`, `featured`, `created_at`, `updated_at`) VALUES
('React', 'react', 'fab fa-react', '#61DAFB', 'frontend', 5, 1, 1, NOW(), NOW()),
('Laravel', 'laravel', 'fab fa-laravel', '#FF2D20', 'backend', 5, 2, 1, NOW(), NOW()),
('Vue.js', 'vue', 'fab fa-vuejs', '#4FC08D', 'frontend', 4, 3, 1, NOW(), NOW()),
('Node.js', 'nodejs', 'fab fa-node-js', '#339933', 'backend', 4, 4, 1, NOW(), NOW()),
('PHP', 'php', 'fab fa-php', '#777BB4', 'backend', 5, 5, 1, NOW(), NOW()),
('JavaScript', 'javascript', 'fab fa-js-square', '#F7DF1E', 'frontend', 5, 6, 1, NOW(), NOW()),
('TypeScript', 'typescript', 'fab fa-js-square', '#3178C6', 'frontend', 4, 7, 0, NOW(), NOW()),
('MySQL', 'mysql', 'fas fa-database', '#4479A1', 'database', 5, 8, 1, NOW(), NOW()),
('MongoDB', 'mongodb', 'fas fa-leaf', '#47A248', 'database', 4, 9, 0, NOW(), NOW()),
('Docker', 'docker', 'fab fa-docker', '#2496ED', 'tools', 4, 10, 0, NOW(), NOW()),
('Git', 'git', 'fab fa-git-alt', '#F05032', 'tools', 5, 11, 1, NOW(), NOW()),
('AWS', 'aws', 'fab fa-aws', '#232F3E', 'tools', 3, 12, 0, NOW(), NOW()),
('Tailwind CSS', 'tailwind', 'fas fa-paint-brush', '#06B6D4', 'frontend', 5, 13, 1, NOW(), NOW()),
('Bootstrap', 'bootstrap', 'fab fa-bootstrap', '#7952B3', 'frontend', 5, 14, 0, NOW(), NOW()),
('Sass', 'sass', 'fab fa-sass', '#CC6699', 'frontend', 4, 15, 0, NOW(), NOW()),
('Redux', 'redux', 'fas fa-layer-group', '#764ABC', 'frontend', 4, 16, 0, NOW(), NOW()),
('Express.js', 'express', 'fas fa-server', '#000000', 'backend', 4, 17, 0, NOW(), NOW()),
('PostgreSQL', 'postgresql', 'fas fa-database', '#336791', 'database', 3, 18, 0, NOW(), NOW()),
('Redis', 'redis', 'fas fa-database', '#DC382D', 'database', 3, 19, 0, NOW(), NOW()),
('Nginx', 'nginx', 'fas fa-server', '#009639', 'tools', 3, 20, 0, NOW(), NOW());

-- =====================================================
-- TABELLA: projects (COMPLETA)
-- =====================================================
DROP TABLE IF EXISTS `projects`;
CREATE TABLE `projects` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `long_description` text COLLATE utf8mb4_unicode_ci,
  `client` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `duration` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `features` json DEFAULT NULL,
  `challenges` json DEFAULT NULL,
  `results` json DEFAULT NULL,
  `testimonial` text COLLATE utf8mb4_unicode_ci,
  `testimonial_author` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `testimonial_role` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gallery` json DEFAULT NULL,
  `demo_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `github_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `linkedin_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `video_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `additional_links` json DEFAULT NULL,
  `technologies` json NOT NULL,
  `status` enum('in-progress','completed','paused') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'in-progress',
  `sort_order` int NOT NULL DEFAULT '0',
  `featured` tinyint(1) NOT NULL DEFAULT '0',
  `project_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `projects_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Inserisci progetti di esempio con gallery
INSERT INTO `projects` (`title`, `slug`, `description`, `long_description`, `client`, `duration`, `category`, `features`, `challenges`, `results`, `testimonial`, `testimonial_author`, `testimonial_role`, `image_url`, `gallery`, `demo_url`, `github_url`, `linkedin_url`, `video_url`, `additional_links`, `technologies`, `status`, `sort_order`, `featured`, `project_date`, `created_at`, `updated_at`) VALUES

('E-Commerce Platform React', 'ecommerce-platform-react', 'Piattaforma e-commerce completa sviluppata con React e Laravel', 'Una moderna piattaforma e-commerce con gestione prodotti, carrello, pagamenti e dashboard amministrativa. Implementa autenticazione sicura, sistema di recensioni e integrazione con gateway di pagamento.', 'TechStore Italia', '4 mesi', 'E-Commerce', 
'["Gestione prodotti avanzata", "Carrello dinamico", "Pagamenti sicuri", "Dashboard admin", "Sistema recensioni", "Ricerca e filtri", "Gestione ordini", "Notifiche email"]',
'["Integrazione gateway pagamento", "Ottimizzazione performance", "Gestione inventario real-time", "Sistema di spedizioni"]',
'["Aumento vendite del 150%", "Tempo di caricamento < 2s", "95% soddisfazione utenti", "Zero downtime"]',
'Vincenzo ha sviluppato una piattaforma incredibile che ha trasformato il nostro business. La qualità del codice e l\'attenzione ai dettagli sono eccezionali.',
'Marco Rossi', 'CEO TechStore Italia',
'https://vincenzorocca.com/api/uploads/ecommerce-main.jpg',
'["https://vincenzorocca.com/api/uploads/ecommerce-1.jpg", "https://vincenzorocca.com/api/uploads/ecommerce-2.jpg", "https://vincenzorocca.com/api/uploads/ecommerce-3.jpg", "https://vincenzorocca.com/api/uploads/ecommerce-4.jpg"]',
'https://demo-ecommerce.vincenzorocca.com', 'https://github.com/vincenzorocca/ecommerce-react', NULL, NULL,
'[{"title": "Documentazione API", "url": "https://docs.vincenzorocca.com/ecommerce"}, {"title": "Case Study", "url": "https://vincenzorocca.com/case-study/ecommerce"}]',
'["React", "Laravel", "MySQL", "Tailwind CSS", "Redux", "Stripe API"]',
'completed', 100, 1, '2024-01-15', NOW(), NOW()),

('Dashboard Analytics SaaS', 'dashboard-analytics-saas', 'Dashboard avanzata per analytics con grafici interattivi e reportistica', 'Piattaforma SaaS per analytics aziendali con dashboard personalizzabili, grafici interattivi, export dati e sistema di alert automatici. Supporta integrazione con multiple fonti dati.', 'DataInsight Corp', '6 mesi', 'SaaS',
'["Grafici interattivi", "Dashboard personalizzabili", "Export dati", "Alert automatici", "API integrations", "Multi-tenant", "Reportistica avanzata", "Real-time updates"]',
'["Gestione big data", "Performance con grandi dataset", "Sicurezza multi-tenant", "Scalabilità automatica"]',
'["Riduzione tempo analisi del 70%", "Supporto 10M+ record", "99.9% uptime", "Crescita utenti del 300%"]',
'La dashboard ha rivoluzionato il modo in cui analizziamo i nostri dati. L\'interfaccia è intuitiva e le performance sono eccellenti.',
'Sarah Johnson', 'CTO DataInsight Corp',
'https://vincenzorocca.com/api/uploads/dashboard-main.jpg',
'["https://vincenzorocca.com/api/uploads/dashboard-1.jpg", "https://vincenzorocca.com/api/uploads/dashboard-2.jpg", "https://vincenzorocca.com/api/uploads/dashboard-3.jpg", "https://vincenzorocca.com/api/uploads/dashboard-4.jpg", "https://vincenzorocca.com/api/uploads/dashboard-5.jpg"]',
'https://demo-analytics.vincenzorocca.com', 'https://github.com/vincenzorocca/analytics-dashboard', NULL, NULL,
'[{"title": "Demo Video", "url": "https://youtube.com/watch?v=demo"}, {"title": "API Docs", "url": "https://api-docs.vincenzorocca.com"}]',
'["Vue.js", "Node.js", "MongoDB", "Chart.js", "Socket.io", "AWS"]',
'completed', 90, 1, '2024-03-20', NOW(), NOW()),

('App Mobile React Native', 'app-mobile-react-native', 'Applicazione mobile cross-platform per gestione fitness', 'App mobile completa per il fitness con tracking allenamenti, piani nutrizionali, social features e integrazione con dispositivi wearable. Disponibile su iOS e Android.', 'FitLife Mobile', '5 mesi', 'Mobile App',
'["Tracking allenamenti", "Piani nutrizionali", "Social features", "Integrazione wearable", "Notifiche push", "Offline mode", "Sincronizzazione cloud", "Gamification"]',
'["Performance su dispositivi low-end", "Sincronizzazione offline/online", "Integrazione sensori dispositivi", "Store approval process"]',
'["50K+ download primo mese", "4.8 stelle store rating", "80% retention rate", "Featured su App Store"]',
'L\'app ha superato tutte le nostre aspettative. La user experience è fantastica e le performance sono ottime su tutti i dispositivi.',
'Andrea Bianchi', 'Product Manager FitLife',
'https://vincenzorocca.com/api/uploads/mobile-main.jpg',
'["https://vincenzorocca.com/api/uploads/mobile-1.jpg", "https://vincenzorocca.com/api/uploads/mobile-2.jpg", "https://vincenzorocca.com/api/uploads/mobile-3.jpg"]',
'https://apps.apple.com/app/fitlife', 'https://github.com/vincenzorocca/fitlife-mobile', NULL, NULL,
'[{"title": "App Store", "url": "https://apps.apple.com/app/fitlife"}, {"title": "Google Play", "url": "https://play.google.com/store/apps/fitlife"}]',
'["React Native", "Firebase", "Redux", "Node.js", "MongoDB"]',
'completed', 80, 1, '2024-05-10', NOW(), NOW()),

('Portfolio Website', 'portfolio-website', 'Portfolio personale con admin panel e sistema di contatti', 'Sito portfolio moderno e responsive con sezione progetti, tecnologie, contatti e pannello amministrativo per la gestione dei contenuti. Ottimizzato per SEO e performance.', 'Personal Project', '3 mesi', 'Portfolio',
'["Design responsive", "Admin panel", "Sistema contatti", "Gallery progetti", "SEO ottimizzato", "Performance ottimali", "Dark/Light mode", "Animazioni fluide"]',
'["Ottimizzazione SEO", "Performance mobile", "Gestione immagini", "Deploy automatico"]',
'["100% Lighthouse score", "< 1s tempo caricamento", "Posizionamento Google top 10", "Conversioni contatti +200%"]',
NULL, NULL, NULL,
'https://vincenzorocca.com/api/uploads/portfolio-main.jpg',
'["https://vincenzorocca.com/api/uploads/portfolio-1.jpg", "https://vincenzorocca.com/api/uploads/portfolio-2.jpg"]',
'https://vincenzorocca.com', 'https://github.com/vincenzorocca/portfolio-react', NULL, NULL,
'[{"title": "Live Site", "url": "https://vincenzorocca.com"}]',
'["React", "Laravel", "MySQL", "Tailwind CSS", "Vite"]',
'completed', 70, 0, '2024-06-28', NOW(), NOW());

-- =====================================================
-- TABELLA: contacts
-- =====================================================
DROP TABLE IF EXISTS `contacts`;
CREATE TABLE `contacts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `budget` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `timeline` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `project_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('new','read','replied','archived') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'new',
  `replied_at` timestamp NULL DEFAULT NULL,
  `ip_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABELLE DI SISTEMA LARAVEL
-- =====================================================

-- Tabella migrations
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `migrations` (`migration`, `batch`) VALUES
('0001_01_01_000000_create_users_table', 1),
('0001_01_01_000001_create_cache_table', 1),
('0001_01_01_000002_create_jobs_table', 1),
('2025_06_19_144744_create_personal_access_tokens_table', 1),
('2025_06_19_144752_create_technologies_table', 1),
('2025_06_19_144754_create_contacts_table', 1),
('2025_06_19_144810_create_projects_table', 1),
('2025_06_19_145735_add_is_admin_to_users_table', 1),
('2025_06_19_160855_add_detailed_fields_to_projects_table', 1),
('2025_06_19_171905_add_linkedin_url_to_projects_table', 2),
('2025_06_20_154500_add_project_fields_to_contacts_table', 3);

-- Tabella cache
DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabella cache_locks
DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabella sessions
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabella personal_access_tokens
DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- AUTO INCREMENT RESET
-- =====================================================
ALTER TABLE `users` AUTO_INCREMENT = 2;
ALTER TABLE `technologies` AUTO_INCREMENT = 21;
ALTER TABLE `projects` AUTO_INCREMENT = 5;
ALTER TABLE `contacts` AUTO_INCREMENT = 1;
ALTER TABLE `migrations` AUTO_INCREMENT = 12;
ALTER TABLE `personal_access_tokens` AUTO_INCREMENT = 1;

COMMIT;

-- =====================================================
-- FINE DATABASE SETUP
-- ===================================================== 