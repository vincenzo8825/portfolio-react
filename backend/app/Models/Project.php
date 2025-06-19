<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'long_description',
        'image_url',
        'gallery',
        'demo_url',
        'github_url',
        'video_url',
        'technologies',
        'status',
        'sort_order',
        'featured',
        'project_date',
        'client',
        'duration',
        'category',
        'features',
        'challenges',
        'results',
        'testimonial',
        'testimonial_author',
        'testimonial_role',
        'additional_links'
    ];

    protected $casts = [
        'gallery' => 'array',
        'technologies' => 'array',
        'features' => 'array',
        'challenges' => 'array',
        'results' => 'array',
        'additional_links' => 'array',
        'featured' => 'boolean',
        'project_date' => 'date'
    ];

    // Scope per progetti completati (visibili pubblicamente)
    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('status', 'completed');
    }

    // Scope per progetti in evidenza
    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('featured', true);
    }

    // Scope per ordinamento
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('created_at', 'desc');
    }

    // Accessor per l'URL completa dell'immagine
    public function getImageUrlAttribute($value)
    {
        if (!$value) return null;

        // Se inizia con http/https, è già un URL completo
        if (str_starts_with($value, 'http')) {
            return $value;
        }

        // Altrimenti aggiungi il dominio base
        return config('app.url') . '/storage/' . $value;
    }
}
