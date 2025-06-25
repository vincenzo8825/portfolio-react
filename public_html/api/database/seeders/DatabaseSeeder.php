<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Project;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crea l'utente admin
        $this->call([
            AdminUserSeeder::class,
            TechnologySeeder::class,
        ]);

        // Crea un progetto di test
        Project::create([
            'title' => 'Portfolio React - Sistema Demo',
            'slug' => 'portfolio-react-sistema-demo',
            'description' => 'Un sistema completo per la gestione del portfolio con React frontend e Laravel backend.',
            'long_description' => 'Questo progetto dimostra l\'integrazione completa tra React e Laravel per creare un sistema di gestione portfolio professionale. Include autenticazione admin, CRUD completo per progetti, gestione immagini e categorizzazione automatica.',
            'image_url' => 'https://via.placeholder.com/600x400/3B82F6/ffffff?text=Portfolio+Demo',
            'demo_url' => 'http://localhost:5175',
            'github_url' => 'https://github.com/vincenzo/portfolio-react',
            'technologies' => ['React', 'Laravel', 'Tailwind CSS', 'MySQL'],
            'status' => 'completed',
            'featured' => true,
            'project_date' => now()->format('Y-m-d'),
            'sort_order' => 1
        ]);

        $this->command->info('✅ Progetto demo creato con successo!');

        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
