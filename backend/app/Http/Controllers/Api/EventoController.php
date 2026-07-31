<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Fila;
use App\Models\Senha;
use Illuminate\Http\Request;

class EventoController extends Controller
{
    /**
     * Recebe um evento de clique/botão (ex: vindo do ESP32) e gera uma senha na fila correspondente.
     */
    public function registrar(Request $request)
    {
        $request->validate([
            'tipo' => 'required|in:comum,prioritaria',
        ]);

        // Define o prefixo com base no tipo
        $prefixo = $request->tipo === 'prioritaria' ? 'P' : 'C';

        // Busca a fila ativa com o prefixo
        $fila = Fila::where('prefixo', $prefixo)->where('ativa', true)->first();

        if (!$fila) {
            return response()->json(['message' => 'Fila não encontrada ou inativa.'], 404);
        }

        // Pega o último número gerado para essa fila no dia
        $ultimoNumero = Senha::where('fila_id', $fila->id)
            ->whereDate('created_at', now()->today())
            ->max('numero') ?? 0;

        $novoNumero = $ultimoNumero + 1;
        $codigo = $prefixo . str_pad($novoNumero, 3, '0', STR_PAD_LEFT);

        // Cria a nova senha
        $senha = Senha::create([
            'fila_id' => $fila->id,
            'numero' => $novoNumero,
            'codigo' => $codigo,
            'tipo' => $request->tipo,
            'status' => 'aguardando',
            'emitida_em' => now(),
        ]);

        return response()->json([
            'message' => 'Senha emitida com sucesso!',
            'senha' => $senha,
        ], 201);
    }
}