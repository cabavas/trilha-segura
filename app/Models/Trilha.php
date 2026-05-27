<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Trilha extends Model
{
    protected $fillable = [
        'user_id',
        'inicio',
        'fim',
        'passos_total',
        'distancia',
        'duracao_segundos',
        'rota',
        'finalizada',
    ];

    protected $casts = [
        'inicio' => 'datetime',
        'fim' => 'datetime',
        'rota' => 'array',
        'finalizada' => 'boolean',
        'passos_total' => 'integer',
        'distancia' => 'float',
        'duracao_segundos' => 'integer'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function midias(): HasMany
    {
        return $this->hasMany(MidiaTrilha::class);
    }
}
