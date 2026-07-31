<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Senha;
use Illuminate\Http\Request;

class SenhaController extends Controller
{
    /**
     * Chama a próxima senha respeitando a prioridade e altera o status para 'chamada'.
     */
    public function chamarProxima()
    {
        // Regra simples: Busca primeiro se houver alguma prioritaria aguardando, senao pega a comum
        $senha = Senha::where('status', 'aguardando')
            ->orderByRaw("CASE WHEN tipo = 'prioritaria' THEN 1 ELSE 2 END")
            ->orderBy('created_at', 'asc')
            ->first();

        if (!$senha) {
            return response()->json(['message' => 'Nenhuma senha aguardando na fila.'], 404);
        }

        $senha->update([
            'status' => 'chamada',
            'chamada_em' => now(),
        ]);

        return response()->json([
            'message' => 'Senha chamada!',
            'senha' => $senha,
        ]);
    }

    /**
     * Lista as senhas que estão sendo chamadas ou em atendimento.
     */
    public function listarAtivas()
    {
        $senhas = Senha::whereIn('status', ['chamada', 'em_atendimento'])
            ->orderBy('updated_at', 'desc')
            ->get();

        return response()->json($senhas);
    }
}