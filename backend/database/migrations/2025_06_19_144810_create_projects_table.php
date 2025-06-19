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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->text('long_description')->nullable();
            $table->string('image_url')->nullable();
            $table->json('gallery')->nullable(); // Array di immagini
            $table->string('demo_url')->nullable();
            $table->string('github_url')->nullable();
            $table->json('technologies'); // Array di tecnologie usate
            $table->enum('status', ['in-progress', 'completed', 'paused'])->default('in-progress');
            $table->integer('sort_order')->default(0);
            $table->boolean('featured')->default(false);
            $table->date('project_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
