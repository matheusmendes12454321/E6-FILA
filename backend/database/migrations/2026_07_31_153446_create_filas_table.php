<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('filas', function (Blueprint $table) {
            $table->id();
            $table->string('nome'); // Ex: Atendimento Comum, Atendimento Prioritário
            $table->string('prefixo', 5)->unique(); // Ex: 'C', 'P'
            $table->boolean('ativa')->default(true);
            $table->string('politica_prioridade')->default('1_prioritaria_para_2_comuns');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('filas');
    }
};