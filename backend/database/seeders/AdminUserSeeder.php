<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Controlla se esiste già un admin
        $adminExists = User::where('is_admin', true)->exists();

        if (!$adminExists) {
            User::create([
                'name' => 'Vincenzo Rocca',
                'email' => env('ADMIN_EMAIL', 'vincenzo@admin.it'),
                'password' => Hash::make('admin123'), // Password di default - CAMBIALA!
                'is_admin' => true,
                'email_verified_at' => now(),
            ]);

            $this->command->info('✅ Utente admin creato con successo!');
            $this->command->info('📧 Email: ' . env('ADMIN_EMAIL', 'vincenzo@admin.it'));
            $this->command->info('🔑 Password: admin123');
            $this->command->warn('⚠️  IMPORTANTE: Cambia la password dopo il primo login!');
        } else {
            $this->command->info('ℹ️  Utente admin già esistente.');
        }
    }
}
