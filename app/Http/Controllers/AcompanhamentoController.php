<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;
use App\Models\Convenio;

// Funções relacionadas a acompanhamentos
class AcompanhamentoController extends Controller {

    public function storeAcompanhamento(Request $request, Convenio $convenio) {
        Log::info('Requisição de acompanhamento recebida', [
            'convenio_id' => $convenio->id,
            'dados' => $request->all(),
            'método' => $request->method()
        ]);

        $request->validate([
            'status' => 'required|in:em_execucao,finalizado,cancelado',
            'situacao' => 'nullable|string|max:255', 
            'monitorado' => 'required|boolean',
            'porcentagem_conclusao' => 'required|integer|min:0|max:100',
            'valor_liberado' => 'required|numeric',
        ]);

        // Verificar se já existe um acompanhamento para este convênio
        $acompanhamento = $convenio->acompanhamentos()->latest()->first();

        if ($acompanhamento) {
            // Se existe, atualizar em vez de criar um novo
            $acompanhamento->update([
                'status' => $request->status,
                'situacao' => $request->situacao,  
                'monitorado' => $request->monitorado == '1',
                'porcentagem_conclusao' => $request->porcentagem_conclusao,
                'valor_liberado' => str_replace(['.', ','], ['', '.'], $request->valor_liberado),
            ]);
        } else {
            // Se não existe, criar um novo
            $acompanhamento = $convenio->acompanhamentos()->create([
                'status' => $request->status,
                'situacao' => $request->situacao, 
                'monitorado' => $request->monitorado == '1',
                'porcentagem_conclusao' => $request->porcentagem_conclusao,
                'valor_liberado' => str_replace(['.', ','], ['', '.'], $request->valor_liberado),
            ]);
        }

        Log::info('Acompanhamento atualizado', ['acompanhamento' => $acompanhamento->toArray()]);

        return response()->json([
            'sucesso' => true,
            'acompanhamento' => [
                'status' => $acompanhamento->status,
                'situacao' => $acompanhamento->situacao, 
                'status_formatado' => ucfirst(str_replace('_', ' ', $acompanhamento->status)),
                'monitorado' => $acompanhamento->monitorado,
                'porcentagem_conclusao' => $acompanhamento->porcentagem_conclusao,
                'data_formatada' => $acompanhamento->created_at->format('d/m/Y H:i'),
                'valor_liberado' => str_replace(['.', ','], ['', '.'], $request->valor_liberado),
            ]
        ]);
    }
    public function getAcompanhamento(Convenio $convenio) {
        $acompanhamento = $convenio->acompanhamentos()->latest()->first();
    
        if (!$acompanhamento) {
            return response()->json(['sucesso' => false]);
        }
    
        return response()->json([
            'sucesso' => true,
            'acompanhamento' => [
                'status' => $acompanhamento->status,
                'situacao' => $acompanhamento->situacao ?? '',
                'monitorado' => $acompanhamento->monitorado,
                'porcentagem_conclusao' => $acompanhamento->porcentagem_conclusao ?? 0,
                'valor_liberado' => $acompanhamento->valor_liberado,
            ]
        ]);
    }
}