<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Medicao;
use App\Models\Convenio;


// Funções relacionadas a acompanhamentos
class MedicaoController extends Controller {

    public function getMedicoes($id){
        $convenio = Convenio::findOrFail($id);
        $medicoes = $convenio->medicoes;

        $medicoesFormatadas = $medicoes->map(function ($medicao) {
            return [
                'id' => $medicao->id,
                'numero_medicao' => $medicao->numero_medicao,
                'porcentagem_conclusao' => $medicao->porcentagem_conclusao,
                'valor' => $medicao->valor,
            ];
        });

        return response()->json([
            'sucesso' => true,
            'medicoes' => $medicoesFormatadas
        ]);
    }

    public function storeMedicao(Request $request, $id){
        $request->merge([
            'porcentagem_conclusao' => str_replace(['.', ','], ['', '.'], $request->porcentagem_conclusao)
        ]);
        try {
            $request->validate([
                'numero_medicao' => 'required',
                'porcentagem_conclusao' => 'required|numeric|min:0|max:100',
                'valor' => 'required|numeric',
            ]);

            $medicao = new Medicao();
            $medicao->convenio_id = $id;
            $medicao->numero_medicao = $request->numero_medicao;
            $medicao->porcentagem_conclusao = $request->porcentagem_conclusao;
            $medicao->valor = $request->valor;
            $medicao->save();

            return response()->json([
                'sucesso' => true,
                'medicao' => [
                    'id' => $medicao->id,
                    'numero_medicao' => $medicao->numero_medicao,
                    'porcentagem_conclusao' => $medicao->porcentagem_conclusao,
                    'valor' => $medicao->valor
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'sucesso' => false,
                'mensagem' => 'Erro ao salvar medição: ' . $e->getMessage()
            ], 500);
        }
    }


    public function destroy($convenio_id, $medicao_id){
        try {
            $medicao = Medicao::where('convenio_id', $convenio_id)
                     ->where('id', $medicao_id)
                     ->firstOrFail();

        $medicao->delete();

        return response()->json([
            'sucesso' => true,
            'mensagem' => 'Medição apagada com sucesso.'
        ]);
    }   catch (\Exception $e) {
            return response()->json([
                'sucesso' => false,
                'mensagem' => 'Erro ao apagar medição: ' . $e->getMessage()
            ], 500);
        }
    }
}