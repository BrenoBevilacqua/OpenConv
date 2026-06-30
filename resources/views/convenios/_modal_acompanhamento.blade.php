@vite(['resources/css/app.css', 'resources/js/app.js'])
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
<div id="modalAcompanhamento" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background-color: rgba(0,0,0,0.5); z-index: 9999;">
    <div style="background:white; width:80%; max-width:700px; margin:5% auto; padding:20px; border-radius:8px; position:relative; max-height:90%; overflow-y:auto; box-sizing: border-box;">

        <h3 style="margin-bottom: 20px;">Atualizar Acompanhamento</h3>

        <form id="formNovoAcompanhamento" data-action="{{ route('convenios.acompanhamentos.store', $convenio->id) }}" method="POST">
            @csrf
            <input type="hidden" name="convenio_id" value="{{ $convenio->id }}">

            <div style="margin-bottom: 15px;">
                <label for="status" style="display:block; margin-bottom: 5px;">Status do Convênio</label>
                <select name="status" required style="width:100%; padding:10px; border:1px solid #ccc; border-radius:4px;">
                    <option value="em_execucao">Em execução</option>
                    <option value="finalizado">Finalizado</option>
                    <option value="cancelado">Cancelado</option>
                </select>
            </div>

            <div style="margin-bottom: 15px;">
                <label for="porcentagem_conclusao" style="display:block; margin-bottom: 5px;">Porcentagem de Conclusão</label>
                <div style="display:flex; align-items:center;">
                    <input type="range" name="porcentagem_conclusao" id="porcentagem_conclusao" min="0" max="100" value="0"
                        style="flex-grow:1; margin-right:10px;" oninput="document.getElementById('porcentagemValue').innerText = this.value + '%'">
                    <span id="porcentagemValue">0%</span>
                </div>
            </div>

            <div style="margin-bottom: 15px;">
                <label for="monitorado" style="display:block; margin-bottom: 5px;">Monitoramento</label>
                <select name="monitorado" id="monitorado" required class="form-select">
                    <option value="1">Monitorado</option>
                    <option value="0">Não Monitorado</option>
                </select>
            </div>

            <div>
                <label class="block font-bold text-gray-700 mb-1">Valor Liberado</label>
                <input type="text" id="valor_liberado" name="valor_liberado"
                    class="w-full border border-gray-300 rounded px-3 py-2 money"
                    value="{{ old('valor_liberado', $convenio->acompanhamento?->valor_liberado ?? 0)}}">
            </div>

            <!--<div style="margin-bottom: 15px;">
                <label class="block text-sm font-medium text-gray-700 mb-1">Situação</label>
                <input type="text" name="situacao" id="situacao"
                    style="width:100%; padding:10px; border:1px solid #ccc; border-radius:4px;">
            </div>-->

            <div>
                <label for="situacao" class="block text-sm font-medium text-gray-700 mb-1">Situação</label>
                <textarea name="situacao" id="situacao" rows="3" class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required></textarea>
            </div>


            <br>
            <div style="text-align:right;">
                <button type="submit" style="padding: 10px 20px; background-color: #3498db; color:white; border:none; border-radius:6px;">Salvar</button>
            </div>
        </form>

        <button onclick="fecharModalAcompanhamento()" style="position:absolute; top:10px; right:10px; background-color:#e74c3c; color:white; border:none; padding:5px 10px; border-radius:4px;">X</button>
    </div>
</div>

<script>
    function carregarDadosAcompanhamento() {
        const convenioId = document.querySelector('input[name="convenio_id"]').value;

        fetch(`/convenios/${convenioId}/acompanhamento`)
            .then(response => response.json())
            .then(data => {
                if (data.sucesso && data.acompanhamento) {
                    // Preencher os campos com os dados existentes
                    document.querySelector('select[name="status"]').value = data.acompanhamento.status;
                    document.querySelector('input[name="porcentagem_conclusao"]').value = data.acompanhamento.porcentagem_conclusao;
                    document.getElementById('porcentagemValue').innerText = data.acompanhamento.porcentagem_conclusao + '%';
                    document.querySelector('select[name="monitorado"]').value = data.acompanhamento.monitorado ? '1' : '0';
                    document.querySelector('textarea[name="situacao"]').value = data.acompanhamento.situacao ?? '';


                    // Formatação do valor liberado para exibição
                    const valorLiberado = parseFloat(data.acompanhamento.valor_liberado);
                    if (!isNaN(valorLiberado)) {
                        const valorFormatado = valorLiberado.toLocaleString('pt-BR', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        });
                        document.getElementById('valor_liberado').value = valorFormatado;
                    }
                }
            })
            .catch(error => {
                console.error('Erro ao carregar dados do acompanhamento:', error);
            });
    }
</script>