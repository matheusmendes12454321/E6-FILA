<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comandos', function (Blueprint $table) {
            $table->id();
            $table->string('device_id')->default('E6-FILA');
            $table->string('acao');
            $table->json('parametros')->nullable();
            $table->enum('status', ['pendente', 'executado', 'falhou'])->default('pendente');
            $table->text('retorno')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comandos');
    }
};