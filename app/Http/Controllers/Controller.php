<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\LogHistorico;
use App\Models\Convenio;
use App\Models\Contrato;

class Controller
{
    public function historico(Request $request){
        $logs = LogHistorico::with('user')
            ->orderBy('data_modificacao', 'desc')
            ->paginate(10); // Paginação com 10 itens por página

        // Calcular convenios vencidos
        $conveniosVencidos = Convenio::where('data_vigencia', '<', now())->count();

        // Calcular contratos vencidos
        $contratosVencidos = Contrato::where('validade_fim', '<', now())->count();

        // Calcular valor total de repasses dos convênios ativos
        $valorTotalRepasses = Convenio::where('data_vigencia', '>=', now())
            ->sum('valor_repasse');

        return view('convenios.info', compact('logs', 'conveniosVencidos', 'contratosVencidos', 'valorTotalRepasses'));
    }
}
