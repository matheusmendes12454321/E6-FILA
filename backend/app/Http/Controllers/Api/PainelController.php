<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Senha;

class PainelController extends Controller
{
    /**
     * Exibe a senha atual chamada e o histórico recente para a tela do Painel/TV.
     */
    public function exibirAtual()
    {
        $senhaAtual = Senha::where('status', 'chamada')
            ->orderBy('chamada_em', 'desc')
            ->first();

        $historico = Senha::whereIn('status', ['chamada', 'finalizada'])
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        return response()->json([
            'atual' => $senhaAtual,
            'historico' => $historico,
        ]);
    }
}