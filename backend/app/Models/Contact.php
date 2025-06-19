<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'subject',
        'message',
        'phone',
        'company',
        'status',
        'admin_notes',
        'ip_address',
        'user_agent'
    ];

    protected $casts = [
        'read_at' => 'datetime',
        'replied_at' => 'datetime'
    ];

    // Scope per messaggi non letti
    public function scopeUnread(Builder $query): Builder
    {
        return $query->where('status', 'new');
    }

    // Scope per messaggi letti
    public function scopeRead(Builder $query): Builder
    {
        return $query->whereIn('status', ['read', 'replied']);
    }

    // Scope per status specifico
    public function scopeByStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }

    // Scope per ordinamento (più recenti prima)
    public function scopeLatest(Builder $query): Builder
    {
        return $query->orderBy('created_at', 'desc');
    }

    // Mutator per salvare come letto
    public function markAsRead(): void
    {
        $this->update([
            'status' => 'read',
            'read_at' => now()
        ]);
    }

    // Mutator per salvare come risposto
    public function markAsReplied(): void
    {
        $this->update([
            'status' => 'replied',
            'replied_at' => now()
        ]);
    }

    // Accessor per il nome dello status formattato
    public function getStatusNameAttribute(): string
    {
        return match($this->status) {
            'new' => 'Nuovo',
            'read' => 'Letto',
            'replied' => 'Risposto',
            'archived' => 'Archiviato',
            default => 'Sconosciuto'
        };
    }

    // Accessor per verificare se il messaggio è stato letto
    public function getIsReadAttribute(): bool
    {
        return in_array($this->status, ['read', 'replied', 'archived']);
    }
}
