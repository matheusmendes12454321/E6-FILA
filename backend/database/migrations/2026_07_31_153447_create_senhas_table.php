<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('senhas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fila_id')->constrained('filas')->onDelete('cascade');
            $table->integer('numero'); // Número sequencial da fila
            $table->string('codigo', 10); // Código formatado (Ex: C001, P002)
            $table->enum('tipo', ['comum', 'prioritaria'])->default('comum');
            $table->enum('status', ['aguardando', 'chamada', 'em_atendimento', 'finalizada', 'ausente'])->default('aguardando');
            
            // Registos de tempo obrigatórios para métricas do gestor
            $table->timestamp('emitida_em')->useCurrent();
            $table->timestamp('chamada_em')->nullable();
            $table->timestamp('inicio_em')->nullable();
            $table->timestamp('fim_em')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('senhas');
    }
};