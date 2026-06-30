<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Convenio;

use App\Models\LogHistorico;
use App\Models\Acao;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

// Funções relacionadas a convênios

class ConvenioController extends Controller
{
    // pagina inicial com tabelas
    public function index(Request $request)
    {
        // Iniciar a query
        $query = Convenio::with([
            'acompanhamentos' => fn($query) => $query->latest()->limit(1),
            'contratos'
        ]);

        // Aplicar filtros
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('identificacao', 'like', "%{$search}%")
                    ->orWhere('numero_convenio', 'like', "%{$search}%")
                    ->orWhere('objeto', 'like', "%{$search}%");
            });
        }

        // Filtro por status
        if ($request->has('status') && !empty($request->status)) {
            $status = $request->status;
            $query->whereHas('acompanhamentos', function ($q) use ($status) {
                $q->where('status', $status)
                    ->orderBy('created_at', 'desc');
            });
        }

        // Filtro por validade
        if ($request->has('validade') && !empty($request->validade)) {
            $hoje = Carbon::now();

            if ($request->validade == 'vigentes') {
                $query->where('data_vigencia', '>=', $hoje);
            } elseif ($request->validade == 'vencidos') {
                $query->where('data_vigencia', '<', $hoje);
            } elseif ($request->validade == '30dias') {
                $trintaDiasDepois = $hoje->copy()->addDays(30);
                $query->whereBetween('data_vigencia', [$hoje, $trintaDiasDepois]);
            }
        }

        // Filtro por contratos vencidos
        if ($request->has('contratos_vencidos') && $request->contratos_vencidos == '1') {
            $hoje = Carbon::now()->format('Y-m-d');

            $query->whereHas('contratos', function ($q) use ($hoje) {
                $q->where('validade_fim', '<', $hoje);
            });
        }

        // Paginação com 10 itens por página
        $convenios = $query->paginate(10);

        // Calcular dias restantes e contar contratos vencidos para cada convênio
        foreach ($convenios as $convenio) {
            $dataVigencia = Carbon::parse($convenio->data_vigencia);
            $hoje = Carbon::now();
            $convenio->dias_restantes = (int) $hoje->diffInDays($dataVigencia, false);

            // Contar contratos vencidos
            $convenio->contratos_vencidos_count = $convenio->contratos
                ->filter(function ($contrato) {
                    return Carbon::parse($contrato->validade_fim)->isPast();
                })->count();
        }

        return view('convenios.index', compact('convenios'));
    }

    // array de dados auxiliares
    private function dadosAuxiliares(){
        $lists = ['fontes', 'concedentes', 'parlamentares', 'naturezas'];
        $data = [];

        foreach ($lists as $list) {
            $data[$list] = \App\Models\Dropdown::where('list', $list)->pluck('name')->toArray();
        }

        return $data;
    }

    // rota para create
    public function create()
    {
        return view('convenios.create', $this->dadosAuxiliares());
    }

    // criar convenio
    public function store(Request $request)
    {
        // Formatação dos valores monetários antes da validação
        $request->merge([
            'valor_repasse' => str_replace(['.', ','], ['', '.'], $request->valor_repasse),
            'valor_contrapartida' => str_replace(['.', ','], ['', '.'], $request->valor_contrapartida),
        ]);

        // Validação dos dados
        $data = $request->validate([
            'numero_convenio' => 'nullable',
            'ano_convenio' => 'nullable|numeric',
            'identificacao' => 'nullable|string',
            'objeto' => 'nullable|string',
            'fonte_recursos' => 'nullable|string',
            'valor_repasse' => 'nullable|numeric',
            'valor_contrapartida' => 'nullable|numeric',
            'concedente' => 'nullable|string',
            'parlamentar' => 'nullable|string',
            'conta_vinculada' => 'nullable|string',
            'natureza_despesa' => 'nullable|string',
            'data_assinatura' => 'nullable|date',
            'data_vigencia' => 'nullable|date',
            'banco' => 'nullable',
            'agencia' => 'nullable',
        ]);

        // Calculando o valor total
        $data['valor_total'] = $data['valor_repasse'] + $data['valor_contrapartida'];

        // Criando o novo convênio
        $newConvenio = Convenio::create($data);

        // Registrando o histórico de criação
        LogHistorico::create([
            'user_id' => auth()->id(),
            'acao' => 'Criação',
            'numero_convenio' => $newConvenio->numero_convenio,
            'ano_convenio' => $newConvenio->ano_convenio,
            'data_modificacao' => now(),
        ]);

        return redirect(route('convenio.index'));
    }

    public function edit($id)
    {
        $convenio = Convenio::with('acoes')->findOrFail($id);
        return view('convenios.edit', array_merge(
            ['convenio' => $convenio],
            $this->dadosAuxiliares()
        ));
    }
    // editar convenio
    public function update(Request $request, $id)
    {
        $convenio = Convenio::findOrFail($id);
        $request->merge([
            'valor_repasse' => str_replace(['.', ','], ['', '.'], $request->valor_repasse),
            'valor_contrapartida' => str_replace(['.', ','], ['', '.'], $request->valor_contrapartida),
            'valor_total' => str_replace(['.', ','], ['', '.'], $request->valor_total),
        ]);

        $validated = $request->validate([
            'numero_convenio' => 'nullable',
            'ano_convenio' => 'nullable',
            'identificacao' => 'nullable',
            'conta_vinculada' => 'nullable',
            'objeto' => 'nullable',
            'fonte_recursos' => 'nullable',
            'concedente' => 'nullable',
            'parlamentar' => 'nullable',
            'natureza_despesa' => 'nullable',
            'valor_repasse' => 'nullable|numeric',
            'valor_contrapartida' => 'nullable|numeric',
            'valor_total' => 'nullable|numeric',
            'data_assinatura' => 'nullable|date',
            'data_vigencia' => 'nullable|date',
            'banco' => 'nullable',
            'agencia' => 'nullable',
        ]);

        LogHistorico::create([
            'user_id' => auth()->id(),
            'acao' => 'Edição',
            'numero_convenio' => $convenio->numero_convenio,
            'ano_convenio' => $convenio->ano_convenio,
            'data_modificacao' => now(),
        ]);

        $convenio->update($validated);

        return redirect()->route('convenio.index')->with('success', 'Convênio atualizado com sucesso!');
    }

    // deletar convenio
    public function destroy($convenioId, $acaoId = null)
    {
        if ($acaoId) {
            $acao = Acao::where('convenio_id', $convenioId)->where('id', $acaoId)->first();

            if (!$acao) {
                return response()->json(['sucesso' => false, 'mensagem' => 'Ação não encontrada.'], 404);
            }

            $acao->delete();
            return response()->json(['sucesso' => true]);
        }

        $convenio = Convenio::findOrFail($convenioId);

        LogHistorico::create([
            'user_id' => auth()->id(),
            'acao' => 'Exclusão',
            'numero_convenio' => $convenio->numero_convenio,
            'ano_convenio' => $convenio->ano_convenio,
            'data_modificacao' => now(),
        ]);

        $convenio->delete();

        return redirect()->route('convenio.index')->with('success', 'Convênio excluído com sucesso.');
    }


    // historico de modificações
   

    // exportar para pdf
    public function exportarPdf($id)
    {
        $convenio = Convenio::findOrFail($id);
        $pdf = Pdf::loadView('convenios.pdf', compact('convenio'))->setPaper('a4', 'landscape');;
        return $pdf->download("convenio_{$convenio->id}.pdf");
    }

}
