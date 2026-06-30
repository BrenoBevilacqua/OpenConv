@vite('resources/js/app.js')
@vite('resources/css/app.css')

<meta name="csrf-token" content="{{ csrf_token() }}">
<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

<div id="modalMedicao"
     style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background-color: rgba(0,0,0,0.5); z-index: 9999;">

    <div style="background:white; width:80%; max-width:700px; margin:5% auto; padding:20px;
        border-radius:8px; position:relative; max-height:90%; overflow-y:auto;">

        <h3 class="text-xl font-semibold mb-4">Nova Medição</h3>

        <form id="formNovaMedicao">
            @csrf
            <input type="hidden" name="convenio_id" id="medicaoConvenioId">

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Número da Medição</label>
                <input type="number" name="numero_medicao" required
                       class="w-full p-2 border border-gray-300 rounded">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Porcentagem de Conclusão</label>
                <div style="display:flex; align-items:center;">
                    <input class="flex-grow mr-2" type="range" name="porcentagem_conclusao" id="porcentagemSlider" 
                        min="0" max="100" value="{{ old('porcentagem_conclusao', 1) }}" 
                        oninput="document.getElementById('porcentagemInput').value = this.value">
                    <input style="width: 70px; padding: 10px; border: 1px solid rgb(209 213 219); border-radius: 5px" type="text" name="porcentagem_conclusao" id="porcentagemInput" 
                        min="0" max="100" value="{{ old('porcentagem_conclusao', 1) }}" 
                        oninput="document.getElementById('porcentagemSlider').value = this.value"> 
                        
                    
                    
                        
        
                <!-- Number Input -->
                    
                    </div>
                </div>

            <div class="mb-4">
                <label  class="block text-sm font-medium text-gray-700">Valor</label>
                <input type="text" step="0.01" name="valor" required
                        class="money w-full p-2 border border-gray-300 rounded" id="valor_medicao">
            </div>

            <div class="text-right">
                <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Salvar
                </button>
            </div>
        </form>

        <hr class="my-4">

        <h4 class="text-lg font-semibold mb-3">Medições Cadastradas</h4>

        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-gray-100">
                    <th class="py-2 px-4 border-b">Nº Medição</th>
                    <th class="py-2 px-4 border-b">% Conclusão</th>
                    <th class="py-2 px-4 border-b">Valor (R$)</th>
                    <th class="py-2 px-4 border-b">Ações</th>
                </tr>
            </thead>

            <tbody id="lista-medicoes">
                
            </tbody>
        </table>

        <button onclick="fecharModalMedicoes()"
                class="absolute top-3 right-3 bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
            X
        </button>
    </div>
</div>

<script>
const sliderInput = document.getElementById('porcentagemInput');
const textOutput = document.getElementById('porcentagemSlider'); // If displaying visually

sliderInput.addEventListener('input', function() {
    // Replace comma with dot for native processing and standardizing form data
    let cleanValue = this.value.replace(',', '.'); 
    textOutput.value = cleanValue;
});
</script>