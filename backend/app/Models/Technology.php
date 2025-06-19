<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Technology extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'icon',
        'color',
        'category',
        'proficiency',
        'sort_order',
        'featured'
    ];

    protected $casts = [
        'featured' => 'boolean',
        'proficiency' => 'integer',
        'sort_order' => 'integer'
    ];

    // Scope per tecnologie in evidenza
    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('featured', true);
    }

    // Scope per categoria
    public function scopeByCategory(Builder $query, string $category): Builder
    {
        return $query->where('category', $category);
    }

    // Scope per ordinamento
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    // Scope per livello di competenza minimo
    public function scopeMinProficiency(Builder $query, int $level): Builder
    {
        return $query->where('proficiency', '>=', $level);
    }

    // Accessor per il nome della categoria formattato
    public function getCategoryNameAttribute(): string
    {
        return ucfirst($this->category);
    }

    // Accessor per il livello di competenza in formato testo
    public function getProficiencyTextAttribute(): string
    {
        return match($this->proficiency) {
            1 => 'Principiante',
            2 => 'Base',
            3 => 'Intermedio',
            4 => 'Avanzato',
            5 => 'Esperto',
            default => 'Non specificato'
        };
    }
}
