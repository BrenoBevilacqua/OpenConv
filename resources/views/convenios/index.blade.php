@extends('convenios.app')
@section('content')
@include('convenios._modal_contratos')
@include('convenios._modal_medicao')
@include('convenios._modal_termo')

@vite(['resources/css/app.css', 'resources/js/app.js'])
<div class="container mx-auto px-4 py-8 max-w-7xl">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Lista de Convênios</h1>
        <a href="{{ route('convenio.create') }}"
            class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-2.5 rounded-lg shadow-md transition-all duration-200 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            Novo Convênio
        </a>
    </div>

    <!-- Filtros e pesquisa -->
    <div class="bg-white shadow-md rounded-lg p-4 mb-6">
        <form action="{{ route('convenio.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Pesquisar</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}"
                    placeholder="Identificação, número, objeto..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" id="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Todos</option>
                    <option value="em_execucao" {{ request('status') == 'em_execucao' ? 'selected' : '' }}>Em execução</option>
                    <option value="finalizado" {{ request('status') == 'finalizado' ? 'selected' : '' }}>Finalizado</option>
                    <option value="cancelado" {{ request('status') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                </select>
            </div>

            <div>
                <label for="validade" class="block text-sm font-medium text-gray-700 mb-1">Validade</label>
                <select name="validade" id="validade" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Todos</option>
                    <option value="vigentes" {{ request('validade') == 'vigentes' ? 'selected' : '' }}>Vigentes</option>
                    <option value="vencidos" {{ request('validade') == 'vencidos' ? 'selected' : '' }}>Vencidos</option>
                    <option value="30dias" {{ request('validade') == '30dias' ? 'selected' : '' }}>Vence em 30 dias</option>
                </select>
            </div>

            <div>
                <label for="contratos_vencidos" class="block text-sm font-medium text-gray-700 mb-1">Contratos</label>
                <select name="contratos_vencidos" id="contratos_vencidos" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Todos</option>
                    <option value="1" {{ request('contratos_vencidos') == '1' ? 'selected' : '' }}>Com contratos vencidos</option>
                </select>
            </div>

            <div class="md:col-span-2 lg:col-span-4 flex justify-end">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-2 rounded-lg transition-all duration-200 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                    </svg>
                    Filtrar
                </button>

                <a href="{{ route('convenio.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium px-5 py-2 rounded-lg ml-2 transition-all duration-200 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
                    </svg>
                    Limpar
                </a>
            </div>
        </form>
    </div>

    <!-- Tabela de convênios -->
    <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-100">
        @if($convenios->count() == 0)
        <div class="p-10 text-center text-gray-500">
            Nenhum convênio encontrado com os filtros selecionados.
        </div>
        @else
        <!-- Tabela redesenhada como grid de cards para acomodar melhor conteúdo longo -->
        <div class="grid grid-cols-1 divide-y divide-gray-200">
            @foreach ($convenios as $convenio)
            <div class="p-4 hover:bg-gray-50 transition-colors">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4">

                    <!-- Dados Gerais - 4 colunas -->
                    <div class="md:col-span-3">
                        <h3 class="text-xs uppercase text-gray-500 font-medium mb-2 text-center">Dados Gerais</h3>
                        <div class="space-y-2">
                            <div class="font-medium text-gray-900">{{ $convenio->identificacao }}</div>
                            <div class="text-gray-500">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    Nº {{ $convenio->numero_convenio }}/{{ $convenio->ano_convenio }}
                                </span>
                            </div>
                            <div class="text-gray-500 text-xs">C/C: {{ $convenio->conta_vinculada }}</div>
                            <div class="text-gray-500 text-xs">Banco: {{ $convenio->banco }}</div>
                            <div class="text-gray-500 text-xs">Agência: {{ $convenio->agencia }}</div>

                            @php
                            // Calcular contratos vencidos
                            $contratosVencidos = $convenio->contratos->filter(function($contrato) {
                            return \Carbon\Carbon::parse($contrato->validade_fim)->isPast();
                            })->count();
                            @endphp

                            <div class="flex flex-wrap gap-2 text-xs">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ $convenio->contratos->count() }} contrato(s)
                                </span>

                                @if($contratosVencidos > 0)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    {{ $contratosVencidos }} vencido(s)
                                </span>
                                @endif
                            </div>

                            <div class="text-gray-600 text-xs mt-1">
                                <span class="font-medium block">Objeto:</span>
                                <span class="break-words whitespace-normal">{{ $convenio->objeto }}</span>
                            </div>
                        </div>
                    </div>
                    @php
                    $acompanhamento = $convenio->acompanhamentos->first();
                    @endphp
                    <!-- Recursos/Concedentes - 3 colunas -->
                    <div class="md:col-span-3">
                        <h3 class="text-xs uppercase text-gray-500 font-medium mb-2 text-center">Recursos/Concedentes</h3>
                        <div class="space-y-2">
                            <div class="text-xs">
                                <span class="font-medium text-gray-900 block">Fonte:</span>
                                <span class="text-gray-700 break-words whitespace-normal">{{ $convenio->fonte_recursos }}</span>
                            </div>
                            <div class="text-xs">
                                <span class="font-medium text-gray-900 block">Concedente:</span>
                                <span class="text-gray-700 break-words whitespace-normal">{{ $convenio->concedente }}</span>
                            </div>
                            <div class="text-xs">
                                <span class="font-medium text-gray-900 block">Parlamentar:</span>
                                <span class="text-gray-700 break-words whitespace-normal">{{ $convenio->parlamentar ?: 'N/A' }}</span>
                            </div>
                            <div class="text-xs">
                                <span class="font-medium text-gray-900 block">Natureza:</span>
                                <span class="text-gray-700 break-words whitespace-normal">{{ $convenio->natureza_despesa }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Valores/Data - 2 colunas -->
                    <div class="md:col-span-2">

                        <h3 class="text-xs uppercase text-gray-500 font-medium mb-2 text-center">Valores/Data</h3>
                        <div class="space-y-2 text-xs">
                            <div>
                                <span class="font-medium text-gray-900">Total:</span>
                                <span class="text-gray-700">R$ {{ number_format($convenio->valor_total, 2, ',', '.') }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-900">Valor Liberado:</span>
                                <span class="text-gray-700">
                                    R$ {{ number_format(optional($acompanhamento)->valor_liberado ?? 0, 2, ',', '.') }}
                                </span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-900">Repasse:</span>
                                <span class="text-gray-700">R$ {{ number_format($convenio->valor_repasse, 2, ',', '.') }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-900">Contrapartida:</span>
                                <span class="text-gray-700">R$ {{ number_format($convenio->valor_contrapartida, 2, ',', '.') }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-900">Assinatura:</span>
                                <span class="text-gray-700">{{ \Carbon\Carbon::parse($convenio->data_assinatura)->format('d/m/Y') }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-900">Vigência:</span>
                                <span class="text-gray-700">{{ \Carbon\Carbon::parse($convenio->data_vigencia)->format('d/m/Y') }}</span>
                            </div>
                            <div>
                                @php
                                $diasRestantes = $convenio->dias_restantes;
                                $classCor = $diasRestantes < 0 ? 'bg-red-100 text-red-800' :
                                    ($diasRestantes < 30 ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' );
                                    $textoStatus=$diasRestantes>= 0 ? $diasRestantes . ' dia(s) restantes' : 'Vencido há ' . abs($diasRestantes) . ' dias';
                                    @endphp

                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $classCor }}">
                                        {{ $textoStatus }}
                                    </span>
                            </div>
                        </div>
                    </div>

                    <!-- Status - 1.5 colunas -->
                    <div class="md:col-span-2">
                        <h3 class="text-xs uppercase text-gray-500 font-medium mb-2 text-center">Status</h3>


                        @if($acompanhamento)
                        <div class="flex flex-col gap-2">
                            <span class="inline-flex justify-center items-center px-2 py-1 rounded-md text-xs font-medium bg-blue-100 text-blue-800 w-full text-center">
                                {{ ucfirst(str_replace('_', ' ', $acompanhamento->status)) }}
                            </span>

                            <!-- Barra de Progresso -->
                            <div class="w-full bg-gray-200 rounded-full h-2.5 mb-1">
                                <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $acompanhamento->porcentagem_conclusao }}%"></div>
                            </div>
                            <span class="text-xs text-gray-700 text-center">{{ $acompanhamento->porcentagem_conclusao }}% concluído</span>

                            @if($acompanhamento->monitorado)
                            <span class="inline-flex justify-center items-center px-2 py-1 rounded-md text-xs font-medium bg-green-100 text-green-800 w-full text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                Monitorado
                            </span>
                            @else
                            <span class="inline-flex justify-center items-center px-2 py-1 rounded-md text-xs font-medium bg-red-100 text-red-800 w-full text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                                Não Monitorado
                            </span>
                            @endif
                        </div>
                        @else
                        <span class="text-gray-500 text-center block text-xs">Não informado</span>
                        @endif
                    </div>

                    <!-- Ações - 1.5 colunas -->
                    <div class="md:col-span-2 ">
                        <h3 class="text-xs uppercase text-gray-500 font-medium mb-2 text-center">Ações</h3>
                        <div class="flex flex-col gap-2">
                            <button onclick="abrirModalTermos({{ $convenio->id }})"
                                class="inline-flex w-full justify-center items-center bg-emerald-100 text-emerald-700 hover:bg-emerald-200 px-2 py-1 rounded-md transition-colors font-medium text-xs justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd" />
                                </svg>
                                Termos ({{ $convenio->termos->count() }})
                            </button>
                            
                            <button onclick="abrirModalMedicoes({{ $convenio->id }})"
                                class="inline-flex w-full justify-center items-center bg-orange-100 text-orange-700 hover:bg-orange-200 px-2 py-1 rounded-md transition-colors font-medium text-xs justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd" />
                                </svg>
                                Medições ({{ $convenio->medicoes->count() }})
                            </button>
                            <button onclick="abrirModalContratos({{ $convenio->id }})"
                                class="inline-flex w-full justify-center items-center bg-indigo-100 text-indigo-700 hover:bg-indigo-200 px-2 py-1 rounded-md transition-colors font-medium text-xs justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd" />
                                </svg>
                                Contratos ({{ $convenio->contratos->count() }})
                            </button>

                            <!--<div class="flex items-center justify-center gap-2">-->
                            <a href="{{ route('convenios.exportar.pdf', $convenio->id) }}"
                                class="inline-flex w-full justify-center items-center text-gray-700 hover:text-gray-900 px-2 py-1 text-xs bg-gray-100 hover:bg-gray-200 rounded-md"
                                title="Exportar PDF">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clip-rule="evenodd" />
                                </svg>
                                PDF
                            </a>

                            <a href="{{ route('convenio.edit', $convenio->id) }}"
                                class="inline-flex w-full justify-center items-center text-blue-600 hover:text-blue-800 px-2 py-1 text-xs bg-blue-50 hover:bg-blue-100 rounded-md"
                                title="Editar">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                </svg>
                                Editar
                            </a>

                            <form action="{{ route('convenio.destroy', $convenio->id) }}" method="POST"
                                onsubmit="return confirm('Tem certeza que deseja excluir este convênio?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="inline-flex w-full justify-center items-center text-red-600 hover:text-red-800 px-2 py-1 text-xs bg-red-50 hover:bg-red-100 rounded-md"
                                    title="Excluir">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                    Excluir
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
		@endforeach
        </div>
    </div>
    @endif
</div>

<!-- Paginação centralizada com máximo de 10 por página -->
<div>
    {{ $convenios->appends(request()->query())->onEachSide(2)->links() }}
</div>
</div>
@endsection
