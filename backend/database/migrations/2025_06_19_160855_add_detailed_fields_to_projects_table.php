<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            // Campi per la pagina di dettaglio
            $table->string('client')->nullable()->after('long_description'); // Nome cliente
            $table->string('duration')->nullable()->after('client'); // Durata progetto
            $table->string('category')->nullable()->after('duration'); // Categoria progetto
            $table->json('features')->nullable()->after('category'); // Caratteristiche principali
            $table->json('challenges')->nullable()->after('features'); // Sfide e soluzioni
            $table->json('results')->nullable()->after('challenges'); // Risultati ottenuti
            $table->text('testimonial')->nullable()->after('results'); // Testimonianza cliente
            $table->string('testimonial_author')->nullable()->after('testimonial'); // Autore testimonianza
            $table->string('testimonial_role')->nullable()->after('testimonial_author'); // Ruolo autore
            $table->string('video_url')->nullable()->after('github_url'); // URL video dimostrativo
            $table->json('additional_links')->nullable()->after('video_url'); // Link aggiuntivi
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn([
                'client',
                'duration',
                'category',
                'features',
                'challenges',
                'results',
                'testimonial',
                'testimonial_author',
                'testimonial_role',
                'video_url',
                'additional_links'
            ]);
        });
    }
};
