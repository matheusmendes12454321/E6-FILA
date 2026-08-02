<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comando extends Model
{
    use HasFactory;

    protected $table = 'comandos';

    protected $fillable = [
        'device_id',
        'acao',
        'parametros',
        'status',
        'retorno',
    ];

    protected $casts = [
        'parametros' => 'array',
    ];
}