<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dispositivo extends Model
{
    use HasFactory;

    protected $table = 'dispositivos';

    protected $fillable = [
        'codigo',
        'nome',
        'status',
        'ultima_comunicacao',
    ];

    protected $casts = [
        'ultima_comunicacao' => 'datetime',
    ];
}