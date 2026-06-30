<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Convenio;
use App\Models\Contrato;

class ContratoController extends Controller
{
    public function getContratos($id){
        $convenio = Convenio::findOrFail($id);
        $contratos = $convenio->contratos;

        $contratosFormatados = $contratos->map(function ($contrato) {
            return [
                'id' => $contrato->id,
                'numero_contrato' => $contrato->numero_contrato,
                'objeto' => $contrato->objeto,
                'empresa_contratada' => $contrato->empresa_contratada,
                'valor' => $contrato->valor,
                'data_assinatura' => $contrato->data_assinatura,
                'validade_inicio' => $contrato->validade_inicio,
                'validade_fim' => $contrato->validade_fim,
            ];
        });

        return response()->json([
            'sucesso' => true,
            'contratos' => $contratosFormatados
        ]);
    }
    // guardar Contrato
    public function storeContrato(Request $request, $convenioId){
        $request->validate([
            'numero_contrato' => 'required|string',
            'objeto' => 'required|string',
            'empresa_contratada' => 'required|string',
            'valor' => 'required|numeric|min:0',
            'data_assinatura' => 'required|date',
            'validade_inicio' => 'required|date',
            'validade_fim' => 'required|date|after_or_equal:validade_inicio',
        ]);

        try {
            $contrato = Contrato::create([
                'convenio_id' => $convenioId,
                'numero_contrato' => $request->numero_contrato,
                'objeto' => $request->objeto,
                'empresa_contratada' => $request->empresa_contratada,
                'valor' => $request->valor,
                'data_assinatura' => $request->data_assinatura,
                'validade_inicio' => $request->validade_inicio,
                'validade_fim' => $request->validade_fim,
            ]);

            return response()->json([
                'sucesso' => true,
                'contrato' => $contrato
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'sucesso' => false,
                'mensagem' => 'Erro ao salvar contrato: ' . $e->getMessage()
            ], 500);
        }
    }

    // deletar contrato
    public function destroyContrato($convenioId, $contratoId){
        $contrato = Contrato::where('convenio_id', $convenioId)->findOrFail($contratoId);
        $contrato->delete();

        return response()->json(['sucesso' => true]);
    }
}