<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Senha;
use App\Models\Fila;
use App\Models\Comando;

class SenhaController extends Controller
{
    /**
     * Busca ou cria a fila padrão no banco de dados.
     */
    private function obterFilaPadraoId()
    {
        $fila = Fila::first();

        if (!$fila) {
            $fila = Fila::create([
                'nome'    => 'Atendimento Geral',
                'prefixo' => 'C',
            ]);
        }

        return $fila->id;
    }

    /**
     * 1. Emitir Senha (comum ou prioritaria)
     */
    public function emitir(Request $request)
    {
        try {
            $tipo = $request->input('tipo', 'comum');
            $prefixo = ($tipo === 'prioritaria') ? 'P' : 'C';

            // Chama a função centralizada que garante a fila padrão
            $filaId = $this->obterFilaPadraoId();

            $ultimoNumero = Senha::where('tipo', $tipo)->count() + 1;
            $codigo = $prefixo . str_pad($ultimoNumero, 3, '0', STR_PAD_LEFT);

            $senha = Senha::create([
                'fila_id'    => $filaId,
                'numero'     => $ultimoNumero,
                'codigo'     => $codigo,
                'tipo'       => $tipo,
                'status'     => 'aguardando',
                'emitida_em' => now(),
            ]);

            return response()->json([
                'message' => 'Senha emitida com sucesso',
                'senha'   => [
                    'id'     => $senha->id,
                    'codigo' => $senha->codigo,
                    'tipo'   => $senha->tipo,
                    'status' => $senha->status
                ]
            ], 201);

        } catch (\Throwable $e) {
            return response()->json([
                'status'  => 'erro',
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine()
            ], 500);
        }
    }

    /**
     * 2. Chamar Próxima Senha da Fila
     */
    public function chamarProxima()
    {
        $senha = Senha::where('status', 'aguardando')
            ->orderByRaw("CASE WHEN tipo = 'prioritaria' THEN 1 ELSE 2 END")
            ->orderBy('id', 'asc')
            ->first();

        if (!$senha) {
            return response()->json(['message' => 'Nenhuma senha na fila de espera.'], 404);
        }

        $senha->update([
            'status' => 'chamada',
            'chamada_em' => now()
        ]);

        Comando::create([
            'device_id'  => 1,
            'acao'       => 'sinalizar_chamada',
            'parametros' => ['codigo' => $senha->codigo, 'tipo' => $senha->tipo],
            'status'     => 'pendente'
        ]);

        return response()->json([
            'message' => 'Senha chamada!',
            'senha'   => [
                'id'     => $senha->id, 
                'codigo' => $senha->codigo, 
                'tipo'   => $senha->tipo
            ]
        ]);
    }

    /**
     * 3. Painel Atual da Recepção
     */
    /**
     * 3. Painel Atual da Recepção
     */
    /**
     * 3. Painel Atual da Recepção
     */
    public function painelAtual()
    {
        // Pega APENAS quem está com o status 'chamada' neste exato segundo
        $atual = Senha::where('status', 'chamada')
            ->latest('updated_at')
            ->first();

        // Pega o histórico dos últimos atendimentos (chamada, em_atendimento, finalizada, ausente)
        $historico = Senha::whereIn('status', ['chamada', 'em_atendimento', 'finalizada', 'ausente'])
            ->latest('updated_at')
            ->take(5)
            ->get()
            ->map(function ($s) {
                return [
                    'codigo' => $s->codigo,
                    'tipo'   => $s->tipo,
                    'status' => $s->status
                ];
            });

        return response()->json([
            'atual' => $atual ? [
                'codigo' => $atual->codigo,
                'tipo'   => $atual->tipo
            ] : null, // Retorna estritamente NULL se ninguém estiver sendo chamado
            'historico' => $historico
        ]);
    }

    /**
     * 4. Iniciar Atendimento
     */
    public function iniciarAtendimento($id)
    {
        $senha = Senha::find($id);
        if (!$senha) return response()->json(['message' => 'Senha não encontrada'], 404);

        $senha->update(['status' => 'em_atendimento', 'inicio_em' => now()]);

        return response()->json(['message' => 'Atendimento iniciado!']);
    }

    /**
     * 5. Finalizar Atendimento
     */
    public function finalizarAtendimento($id)
    {
        $senha = Senha::find($id);
        if (!$senha) return response()->json(['message' => 'Senha não encontrada'], 404);

        $senha->update(['status' => 'finalizada', 'fim_em' => now()]);

        return response()->json(['message' => 'Atendimento finalizado!']);
    }

    /**
     * 6. Marcar Ausente
     */
    public function marcarAusente($id)
    {
        $senha = Senha::find($id);
        if (!$senha) return response()->json(['message' => 'Senha não encontrada'], 404);

        $senha->update(['status' => 'ausente']);

        return response()->json(['message' => 'Senha marcada como ausente!']);
    }

    /**
     * 7. Indicadores do Dashboard
     */
    public function dashboard()
    {
        return response()->json([
            'indicadores' => [
                'em_espera'                   => Senha::where('status', 'aguardando')->count(),
                'em_atendimento'              => Senha::where('status', 'em_atendimento')->count(),
                'atendidos_hoje'              => Senha::where('status', 'finalizada')->count(),
                'ausentes_hoje'               => Senha::where('status', 'ausente')->count(),
                'tempo_espera_medio_min'      => 0,
                'tempo_atendimento_medio_min' => 0,
            ]
        ]);
    }

    /**
     * 8. Polling para os comandos do ESP32
     */
    public function comandosPendentes()
    {
        $comandos = Comando::where('status', 'pendente')->get();
        Comando::where('status', 'pendente')->update(['status' => 'executado']);
        return response()->json($comandos);
    }
}