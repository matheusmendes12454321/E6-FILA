<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SenhaController;

Route::post('/eventos', [SenhaController::class, 'emitir']);
Route::post('/senhas/chamar-proxima', [SenhaController::class, 'chamarProxima']);
Route::put('/senhas/{id}/iniciar', [SenhaController::class, 'iniciarAtendimento']);
Route::put('/senhas/{id}/finalizar', [SenhaController::class, 'finalizarAtendimento']);
Route::put('/senhas/{id}/ausente', [SenhaController::class, 'marcarAusente']);

Route::get('/painel/atual', [SenhaController::class, 'painelAtual']);
Route::get('/dashboard', [SenhaController::class, 'dashboard']);
Route::get('/comandos/pendentes', [SenhaController::class, 'comandosPendentes']);