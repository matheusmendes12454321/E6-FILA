<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EventoController;
use App\Http\Controllers\Api\SenhaController;
use App\Http\Controllers\Api\PainelController;

// Rota para o ESP32 registrar eventos de botão (emitir senha)
Route::post('/eventos', [EventoController::class, 'registrar']);

// Rotas de atendimento e gerenciamento de senhas
Route::prefix('senhas')->group(function () {
    Route::post('/chamar-proxima', [SenhaController::class, 'chamarProxima']);
    Route::get('/em-atendimento', [SenhaController::class, 'listarAtivas']);
});

// Rota para exibição do Painel Web / TV
Route::get('/painel/atual', [PainelController::class, 'exibirAtual']);