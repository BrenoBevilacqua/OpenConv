<?php

namespace App\Http\Controllers;

use App\Models\Convenio;
use Carbon\Carbon;

use App\Models\Termo;
use Illuminate\Http\Request;


// Funções relacionadas a acompanhamentos
class TermoController extends Controller {

    public function getTermos($id){
        $convenio = Convenio::findOrFail($id);
        $termos = $convenio->termos;

        $termosFormatadas = $termos->map(function ($termo) {
            return [
                'id' => $termo->id,
                'numero_termo' => $termo->numero_termo,
                'aditivo' => $termo->aditivo,
                'termo_data' => $termo->termo_data,
                'termo_valor' => $termo->termo_valor,
            ];
        });

        return response()->json([
            'sucesso' => true,
            'termos' => $termosFormatadas
        ]);
    }
    
    public function storeTermo(Request $request, $id){
            $request->validate([
                'numero_termo' => 'required',
                'aditivo' => 'required',
                'termo_valor' => 'numeric|nullable',
                'termo_data' => 'date|nullable'
            ]);
            try{
                $termo = new Termo();
                $termo->convenio_id = $id;
                $termo->numero_termo = $request->numero_termo;
                $termo->aditivo = $request->aditivo;
                $termo->termo_valor = $request->termo_valor;
                $termo->termo_data = $request->termo_data;
                $termo->save();

                

                return response()->json([
                    'sucesso' => true,
                    'termo' => $termo,
                ]);
        } catch (\Exception $e) {
            return response()->json([
                'sucesso' => false,
                'mensagem' => 'Erro ao salvar termo: ' . $e->getMessage()
            ], 500);
        }
    }



    public function destroy($convenio_id, $termo_id){
        try {
            $termo = Termo::where('convenio_id', $convenio_id)
                     ->where('id', $termo_id)
                     ->firstOrFail();

        $termo->delete();

        return response()->json([
            'sucesso' => true,
            'mensagem' => 'Termo apagada com sucesso.'
        ]);
    }   catch (\Exception $e) {
            return response()->json([
                'sucesso' => false,
                'mensagem' => 'Erro ao apagar termo: ' . $e->getMessage()
            ], 500);
        }
    }
}