<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Acao;
use Carbon\Carbon;


// Funções relacionadas a ações

class AcoesController extends Controller
{
     // guardar ações
   public function storeAcao(Request $request, $id){
    try {
        $request->validate([
            'tipo' => 'required|in:concedente,convenente',
            'observacao' => 'required|string',
            'data_edicao' => 'required|date',
        ]);

        // SEMPRE cria uma nova ação
        $acao = new Acao();
        $acao->convenio_id = $id;
        $acao->tipo = $request->tipo;
        $acao->observacao = $request->observacao;
        $acao->data_edicao = $request->data_edicao;
        $acao->save();

        return response()->json([
            'sucesso' => true,
            'acao' => [
                'id' => $acao->id,
                'tipo' => $acao->tipo,
                'data_edicao_formatada' => Carbon::parse($acao->data_edicao)->format('d/m/Y'),
                'observacao' => $acao->observacao,
                'convenio_id' => $acao->convenio_id
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'sucesso' => false,
            'mensagem' => 'Erro ao salvar ação: ' . $e->getMessage()
        ], 500);
    }
}


    public function destroy($convenio_id, $acao_id){
        try {
            $acao = Acao::where('convenio_id', $convenio_id)
                     ->where('id', $acao_id)
                     ->firstOrFail();

        $acao->delete();

        return response()->json([
            'sucesso' => true,
            'mensagem' => 'Ação apagada com sucesso.'
        ]);
    }   catch (\Exception $e) {
            return response()->json([
                'sucesso' => false,
                'mensagem' => 'Erro ao apagar ação: ' . $e->getMessage()
            ], 500);
        }
    }
}