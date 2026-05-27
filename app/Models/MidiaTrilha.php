<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MidiaTrilha extends Model
{
    protected $table = 'midia_trilha';

    protected $fillable = [
        'trilha_id',
        'tipo',
        'local_path',
        'remote_url',
        'latitude',
        'longitude',
        'capturado_em',
        'sincronizado'
    ];

    protected $casts = [
        'capturado_em' => 'datetime',
        'sincronizado' => 'boolean',
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    public function trilha(): BelongsTo
    {
        return $this->belongsTo(Trilha::class);
    }
}
