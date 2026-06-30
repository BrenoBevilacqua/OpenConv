@extends('convenios.app')
<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
@section('content')
    <h2 class="text-xl font-bold mb-4 text-center">Painel de Monitoramento</h2>
    
    <!-- Cards de indicadores -->
    <div class="flex justify-center items-center gap-6 mb-6">
        <!-- Card de Convênios Vencidos -->
        <div class="bg-red-100 border-l-4 border-red-500 rounded-lg shadow-md p-4 max-w-sm w-full">
            <div class="flex items-center">
                <div class="p-3 bg-red-500 rounded-full mr-4">
                    <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Convênios Vencidos</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $conveniosVencidos }}</p>
                </div>
            </div>
        </div>
        
        <!-- Card de Contratos Vencidos -->
        <div class="bg-yellow-100 border-l-4 border-yellow-500 rounded-lg shadow-md p-4 max-w-sm w-full">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-500 rounded-full mr-4">
                    <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Contratos Vencidos</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $contratosVencidos }}</p>
                </div>
            </div>
        </div>
    </div>
    </div>
    <br>
    <h2 class="text-xl font-bold mb-4 text-center">Histórico de Modificações</h2>

    <div class="overflow-x-auto">
        <table class="w-full table-auto border-collapse">
            <thead class="bg-gray-500 text-white">
                <tr>
                    <th class="w-1/4 text-center px-4 py-2">Identificação do Convênio</th>
                    <th class="w-1/4 text-center px-4 py-2">Data de Registro</th>
                    <th class="w-1/4 text-center px-4 py-2">Ação</th>
                    <th class="w-1/4 text-center px-4 py-2">Usuário de Inclusão</th>
                </tr>
            </thead>
            <tbody>
                @foreach($logs as $log)
                    @continue(!$log->user)
                    <tr class="border-b hover:bg-gray-100">
                        <td class="text-center px-4 py-2">{{ $log->numero_convenio }} / {{ $log->ano_convenio }}</td>
                        <td class="text-center px-4 py-2">
                            {{ $log->data_modificacao ? $log->data_modificacao->format('d/m/Y H:i') : 'N/A' }}
                        </td>
                        <td class="text-center px-4 py-2">{{ $log->acao }}</td>
                        <td class="text-center px-4 py-2">{{ $log->user->email }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <br>
        <div>
        {{ $logs->links() }}
        </div>
    </div>
@endsection