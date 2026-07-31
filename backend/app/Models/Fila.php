<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Fila extends Model
{
    use HasFactory;

    protected $table = 'filas';

    protected $fillable = [
        'nome',
        'prefixo',
        'ativa',
        'politica_prioridade',
    ];

    /**
     * Uma Fila possui muitas Senhas.
     */
    public function senhas(): HasMany
    {
        return $this->hasMany(Senha::class);
    }
}