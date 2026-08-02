<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Senha extends Model
{
    use HasFactory;

    protected $table = 'senhas';

    protected $fillable = [
        'fila_id',
        'numero',
        'codigo',
        'tipo',
        'status',
        'emitida_em',
        'chamada_em',
        'inicio_em',
        'fim_em',
    ];

    protected $casts = [
        'emitida_em' => 'datetime',
        'chamada_em' => 'datetime',
        'inicio_em' => 'datetime',
        'fim_em' => 'datetime',
    ];

    public function fila(): BelongsTo
    {
        return $this->belongsTo(Fila::class);
    }
}