<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EventoController;
use App\Http\Controllers\Api\SenhaController;
use App\Http\Controllers\Api\PainelController;

/*
|--------------------------------------------------------------------------
| Rotas do Sistema de Filas E6-FILA
|--------------------------------------------------------------------------
*/

// Rotas consumidas pelo Hardware (ESP32)
Route::post('/eventos', [EventoController::class, 'registrar']);

// Rotas para Gerenciamento de Senhas (Atendimento)
Route::prefix('senhas')->group(function () {
    Route::post('/emitir', [SenhaController::class, 'emitir']);
    Route::post('/chamar-proxima', [SenhaController::class, 'chamarProxima']);
    Route::get('/em-atendimento', [SenhaController::class, 'listarAtivas']);
});

// Rotas para Exibição no Painel Web / TV
Route::get('/painel/atual', [PainelController::class, 'exibirAtual']);