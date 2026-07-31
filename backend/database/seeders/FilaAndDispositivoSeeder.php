<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Fila;
use App\Models\Dispositivo;

class FilaAndDispositivoSeeder extends Seeder
{
    public function run(): void
    {
        Fila::firstOrCreate(['prefixo' => 'C'], ['nome' => 'Atendimento Comum', 'ativa' => true]);
        Fila::firstOrCreate(['prefixo' => 'P'], ['nome' => 'Atendimento Prioritário', 'ativa' => true]);
        Dispositivo::firstOrCreate(['codigo' => 'E6-FILA'], ['nome' => 'ESP32 - Módulo de Chamada Acessível', 'status' => 'offline']);
    }
}