<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Fila;
use App\Models\Senha;
use Illuminate\Http\Request;

class EventoController extends Controller
{
 
    public function registrar(Request $request)
    {
        $request->validate([
            'tipo' => 'required|in:comum,prioritaria',
        ]);

        $prefixo = $request->tipo === 'prioritaria' ? 'P' : 'C';

        $fila = Fila::where('prefixo', $prefixo)->where('ativa', true)->first();

        if (!$fila) {
            return response()->json(['message' => 'Fila não encontrada ou inativa.'], 404);
        }

        $ultimoNumero = Senha::where('fila_id', $fila->id)
            ->whereDate('created_at', now()->today())
            ->max('numero') ?? 0;

        $novoNumero = $ultimoNumero + 1;
        $codigo = $prefixo . str_pad($novoNumero, 3, '0', STR_PAD_LEFT);

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