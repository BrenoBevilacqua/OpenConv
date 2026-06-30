<!-- Modal Contratos -->
@vite(['resources/css/app.css', 'resources/js/app.js'])
<meta name="csrf-token" content="{{ csrf_token() }}">
<div id="modalContratos" class="fixed inset-0 flex justify-center items-center bg-gray-900 bg-opacity-30 hidden z-50">
    <div class="bg-white p-5 rounded-lg shadow-lg w-11/12 max-w-3xl relative">
        <!-- Cabeçalho do Modal -->
        <div class="flex items-start justify-between p-4 border-b rounded-t">
            <h3 class="text-xl font-semibold text-gray-900">
                Contratos do Convênio
            </h3>
            <button type="button" onclick="fecharModalContratos()" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>

        <!-- Corpo do Modal -->
        <div class="p-6 space-y-6 max-h-[70vh] overflow-y-auto">
            <!-- Formulário para adicionar novo contrato -->
            <div class="pt-6">
                <h4 class="text-lg font-medium text-gray-900 mb-4">Adicionar Novo Contrato</h4>
                <form id="formNovoContrato" class="space-y-4">
                    @csrf
                    <input type="hidden" name="convenio_id" id="contratoConvenioId">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="numero_contrato" class="block text-sm font-medium text-gray-700 mb-1">Número do Contrato</label>
                            <input type="text" name="numero_contrato" id="numero_contrato" class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        </div>

                        <div>
                            <label for="empresa_contratada" class="block text-sm font-medium text-gray-700 mb-1">Empresa Contratada</label>
                            <input type="text" name="empresa_contratada" id="empresa_contratada" class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
                    </div>

                    <div>
                        <label for="objeto" class="block text-sm font-medium text-gray-700 mb-1">Objeto</label>
                        <textarea name="objeto" id="objeto" rows="3" class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="valor" class="block text-sm font-medium text-gray-700 mb-1">Valor</label>
                            <input type="text" name="valor" id="valor" class="money w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        </div>

                        <div>
                            <label for="data_assinatura" class="block text-sm font-medium text-gray-700 mb-1">Data de Assinatura</label>
                            <input type="date" name="data_assinatura" id="data_assinatura" class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="validade_inicio" class="block text-sm font-medium text-gray-700 mb-1">Início da Validade</label>
                            <input type="date" name="validade_inicio" id="validade_inicio" class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        </div>

                        <div>
                            <label for="validade_fim" class="block text-sm font-medium text-gray-700 mb-1">Fim da Validade</label>
                            <input type="date" name="validade_fim" id="validade_fim" class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
                    </div>
                </form>
            </div>
            <!-- Lista de Contratos -->
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="py-2 px-4 border-b">Nº Contrato</th>
                            <th class="py-2 px-4 border-b">Empresa</th>
                            <th class="py-2 px-4 border-b">Objeto</th>
                            <th class="py-2 px-4 border-b">Valor</th>
                            <th class="py-2 px-4 border-b">Validade</th>
                            <th class="py-2 px-4 border-b">Ações</th>
                        </tr>
                    </thead>
                    <tbody id="lista-contratos" class="divide-y divide-gray-200">
                       <!-- Will be populated by JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Botões de ação -->
        <div class="flex items-center justify-end p-6 border-t border-gray-200 rounded-b gap-3">
            <button type="button" onclick="fecharModalContratos()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-colors">
                Cancelar
            </button>
            <button type="button" onclick="salvarContrato()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors">
                Salvar Contrato
            </button>
        </div>
    </div>
</div>

