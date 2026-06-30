@vite('resources/js/app.js')
@vite('resources/css/app.css')

<meta name="csrf-token" content="{{ csrf_token() }}">
<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

<div id="modalTermo"
     style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background-color: rgba(0,0,0,0.5); z-index: 9999;">

    <div style="background:white; width:80%; max-width:700px; margin:5% auto; padding:20px;
        border-radius:8px; position:relative; max-height:90%; overflow-y:auto;">

        <h3 class="text-xl font-semibold mb-4">Novo Termo</h3>

        <form id="formNovoTermo">
            @csrf
            <input type="hidden" name="convenio_id" id="termoConvenioId">

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Número do Termo</label>
                <input type="number" name="numero_termo" required
                       class="w-full p-2 border border-gray-300 rounded">
            </div>

            <div class="mb-4">
                <label for="aditivo" class="block text-sm font-medium text-gray-700">Aditivo</label>
                <select name="aditivo" id="aditivo" required class="w-full p-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="valor">Valor</option>
                    <option value="prazo">Prazo</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="termo_data" class="block text-sm font-medium text-gray-700 mb-1" id="termo_data_name" style="display: none;">Prazo</label>
                <input type="date" name="termo_data" id="termo_data" class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" style="display: none;">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1" id="termo_valor_name">Valor</label>
                <input type="text" id="termo_valor" name="termo_valor"
                       class="money w-full p-2 border border-gray-300 rounded">
            </div>

            <div class="text-right">
                <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Salvar
                </button>
            </div>
        </form>

        <hr class="my-4">

        <h4 class="text-lg font-semibold mb-3">Termos Cadastrados</h4>

        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-gray-100">
                    <th class="py-2 px-4 border-b">Nº Termo</th>
                    <th class="py-2 px-4 border-b">Aditivo</th>
                    <th class="py-2 px-4 border-b">Valor / Prazo</th>
                    <th class="py-2 px-4 border-b">Ações</th>
                </tr>
            </thead>

            <tbody id="lista-termos">
                
            </tbody>
        </table>

        <button onclick="fecharModalTermos()"
                class="absolute top-3 right-3 bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
            X
        </button>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const aditivoSelect = document.getElementById('aditivo');
        const conditionalForm1 = document.getElementById('termo_valor');
        const conditionalForm2 = document.getElementById('termo_data');
        const conditionalForm1Name = document.getElementById('termo_valor_name');
        const conditionalForm2Name = document.getElementById('termo_data_name');

        // Function to toggle fields based on aditivo value
        function toggleAditivoFields() {
            const value = aditivoSelect.value;
            
            if (value === 'valor') {
                conditionalForm2.style.display = 'none';
                conditionalForm2Name.style.display = 'none';
                conditionalForm2.removeAttribute('required');
                conditionalForm2.value = ''; 
                
                conditionalForm1Name.style.display = 'block';
                conditionalForm1.style.display = 'block';
                conditionalForm1.setAttribute('required', 'required');
            } else {
                conditionalForm1.style.display = 'none';
                conditionalForm1Name.style.display = 'none';
                conditionalForm1.removeAttribute('required');
                conditionalForm1.value = ''; 
                
                conditionalForm2Name.style.display = 'block';
                conditionalForm2.style.display = 'block';
                conditionalForm2.setAttribute('required', 'required');
            }
        }

        // Listen for changes in the select
        aditivoSelect.addEventListener('change', toggleAditivoFields);

        // Also handle form reset (when form.reset() is called)
        const form = document.getElementById('formNovoTermo');
        if (form) {
            // Override the form reset to also reset the conditional fields
            const originalReset = form.reset.bind(form);
            form.reset = function() {
                originalReset();
                // Wait for reset to complete, then toggle fields
                setTimeout(() => {
                    toggleAditivoFields();
                }, 0);
            };
        }

        // Initialize on page load
        toggleAditivoFields();
    });
</script>