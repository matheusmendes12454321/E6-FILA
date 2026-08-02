<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fila extends Model
{
    use HasFactory;

    protected $table = 'filas';

    protected $fillable = [
        'nome',
        'sigla',
        'prefixo', // <--- Deve estar liberado aqui
        'descricao',
    ];
}