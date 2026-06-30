document.addEventListener("DOMContentLoaded", function () {
    initApp();
    exposeGlobals();
    initExtraEvents();
});

/* ============================================================
 *  MÓDULO: ModalManager
 * ============================================================ */
const ModalManager = {
    abrirModalAcompanhamento() {
        const el = document.getElementById("modalAcompanhamento");
        if (!el) return;
        el.style.display = "block";

        if (typeof carregarDadosAcompanhamento === "function") {
            carregarDadosAcompanhamento();
        }
    },

    fecharModalAcompanhamento() {
        const el = document.getElementById("modalAcompanhamento");
        if (el) el.style.display = "none";
    },

    abrirModalAcoes() {
        const el = document.getElementById("modalAcoes");
        if (!el) return;
        el.style.display = "block";
        document.body.style.overflow = "hidden";
        MoneyUtils.initMoneyFields();
    },

    fecharModalAcoes() {
        const el = document.getElementById("modalAcoes");
        if (el) {
            el.style.display = "none";
            document.body.style.overflow = "";
        }
    },

    abrirModalMedicoes(convenioId) {
        const el = document.getElementById("modalMedicao");
        if (!el) return;
        const hiddenInput = document.getElementById("medicaoConvenioId");
        if (hiddenInput) hiddenInput.value = convenioId;
        MedicoesManager.carregarMedicoes(convenioId);
        el.style.display = "block";
        document.body.style.overflow = "hidden";
        MoneyUtils.initMoneyFields();
    },

    fecharModalMedicoes() {
        const el = document.getElementById("modalMedicao");
        if (el) {
            el.style.display = "none";
            document.body.style.overflow = "";
        }
    },

    abrirModalTermos(convenioId) {
        const el = document.getElementById("modalTermo");
        if (!el) return;
        const hiddenInput = document.getElementById("termoConvenioId");
        if (hiddenInput) hiddenInput.value = convenioId;
        TermosManager.carregarTermos(convenioId);
        el.style.display = "block";
        document.body.style.overflow = "hidden";
        MoneyUtils.initMoneyFields();
    },

    fecharModalTermos() {
        const el = document.getElementById("modalTermo");
        if (el) {
            el.style.display = "none";
            document.body.style.overflow = "";
            MoneyUtils.initMoneyFields();
        }
    },

    abrirModalContratos(convenioId) {
        const el = document.getElementById("modalContratos");
        if (!el) return;
        
        // Set the convenio_id in the hidden input
        const hiddenInput = document.getElementById("contratoConvenioId");
        if (hiddenInput) hiddenInput.value = convenioId;
        
        // Load contracts for this convenio
        ContratosManager.carregarContratos(convenioId);
        
        el.classList.remove("hidden");
        document.body.style.overflow = "hidden";
        MoneyUtils.initMoneyFields();
    },

    fecharModalContratos() {
        const el = document.getElementById("modalContratos");
        if (el) {
            el.classList.add("hidden");
            document.body.style.overflow = "";
        }
    },


    formatarData(data) {
        return data ? new Date(data).toLocaleDateString("pt-BR") : "N/A";
    }
};

/* ============================================================
 *  MÓDULO: ContratosManager
 * ============================================================ */
const ContratosManager = {
    carregarContratos(convenioId) {
        fetch(`/convenios/${convenioId}/contratos`)
            .then(r => r.json())
            .then(json => {
                const lista = document.getElementById("lista-contratos");
                if (!lista) {
                    console.error("#lista-contratos não encontrado");
                    return;
                }

                lista.innerHTML = "";

                if (!json.sucesso || !json.contratos || json.contratos.length === 0) {
                    lista.innerHTML = `
                        <tr class="bg-white">
                            <td colspan="6" class="p-4 text-center text-gray-500">
                                Nenhum contrato encontrado
                            </td>
                        </tr>`;
                    return;
                }

                json.contratos.forEach(c => {
                    const hoje = new Date();
                    const vencido = new Date(c.validade_fim) < hoje;
                    const vencidoClass = vencido ? "text-red-600 font-medium" : "";

                    lista.innerHTML += `
                        <tr>
                            <td style="padding: 10px; border: 1px solid #ccc; text-align: center;">${c.numero_contrato}</td>
                            <td style="padding: 10px; border: 1px solid #ccc; text-align: center;">${c.empresa_contratada}</td>
                            <td style="padding: 10px; border: 1px solid #ccc; word-break: break-word; white-space: normal;">${c.objeto}</td>
                            <td style="padding: 10px; border: 1px solid #ccc; text-align: center;">
                                ${parseFloat(c.valor || 0).toLocaleString("pt-BR", {minimumFractionDigits: 2})}
                            </td>
                            <td style="padding: 10px; border: 1px solid #ccc; text-align: center; ${vencidoClass}">
                                ${c.validade_inicio} a \n${c.validade_fim}
                                ${vencido ? " (Vencido)" : ""}
                            </td>
                            <td style="padding: 10px; border: 1px solid #ccc; text-align: center">
                                <button onclick="deletarContrato(${convenioId}, ${c.id}, this)"
                                        class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                                    Apagar
                                </button>
                            </td>
                        </tr>`;
                });
            })
            .catch(err => {
                console.error("Erro ao carregar contratos:", err);
                const lista = document.getElementById("lista-contratos");
            });
    },



    salvarContrato() {
        const form = document.getElementById("formNovoContrato");
        const convenioId = form.querySelector("[name='convenio_id']").value;

        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        const valor = form.querySelector("#valor");
        if (valor) valor.value = MoneyUtils.parseMoedaParaFloat(valor.value);

        const formData = new FormData(form);

        fetch(`/convenios/${convenioId}/contratos`, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name=\"csrf-token\"]').content,
                Accept: "application/json"
            },
            body: formData
        })
        .then(r => r.json())
        .then(json => {
            if (json.sucesso) {
                // Reload the list
                ContratosManager.carregarContratos(convenioId);
                form.reset();

                //alert("Contrato salvo!");
            } else {
                alert("Erro: " + (json.mensagem || "desconhecido"));
            }
        })
        .catch(err => {
            console.error(err);
            alert("Erro ao salvar contrato.");
        });
    },

    
    deletarContrato(convenioId, contratoId, btn) {
        if (!confirm("Excluir contrato?")) return;

        fetch(`/convenios/${convenioId}/contratos/${contratoId}`, {
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                "Content-Type": "application/json"
            }
        })
        .then(r => r.json())
        .then(json => {
            if (json.sucesso) {
                btn.closest("tr").remove();
            } else {
                alert("Erro ao excluir: " + (json.mensagem || "Erro desconhecido"));
            }
        })
        .catch(err => {
            console.error(err);
            alert("Erro inesperado ao excluir.");
        });
    },

};

/* ============================================================
 *  MÓDULO: MedicoesManager
 * ============================================================ */

const MedicoesManager = {
    carregarMedicoes(convenioId) {
        fetch(`/convenios/${convenioId}/medicoes`)
            .then(r => r.json())
            .then(json => {
                const lista = document.getElementById("lista-medicoes");
                if (!lista) {
                    console.error("#lista-medicoes não encontrado");
                    return;
                }

                lista.innerHTML = "";

                if (!json.sucesso || !json.medicoes || json.medicoes.length === 0) {
                    lista.innerHTML = `
                        <tr class="bg-white">
                            <td colspan="4" class="p-4 text-center text-gray-500">
                                Nenhuma medição encontrada
                            </td>
                        </tr>`;
                    return;
                }

                json.medicoes.forEach(m => {
                    lista.innerHTML += `
                        <tr>
                            <td style="padding: 10px; border: 1px solid #ccc; text-align: center;">${m.numero_medicao}</td>
                            <td style="padding: 10px; border: 1px solid #ccc; text-align: center;">${m.porcentagem_conclusao}%</td>
                            <td style="padding: 10px; border: 1px solid #ccc; text-align: center;">
                                ${parseFloat(m.valor || 0).toLocaleString("pt-BR", {minimumFractionDigits: 2})}
                            </td>
                            <td style="padding: 10px; border: 1px solid #ccc; text-align: center;">
                                <button onclick="deletarMedicao(${convenioId}, ${m.id}, this)"
                                        class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                                    Apagar
                                </button>
                            </td>
                        </tr>`;
                });
            })
            .catch(err => {
                console.error("Erro ao carregar medições:", err);
            });
    },

    salvarMedicao() {
        const form = document.getElementById("formNovaMedicao");
        const convenioId = form.querySelector("[name='convenio_id']").value;

        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        const valor = form.querySelector("#valor_medicao");
        if (valor) valor.value = MoneyUtils.parseMoedaParaFloat(valor.value);

        const formData = new FormData(form);

        fetch(`/convenios/${convenioId}/medicoes`, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                Accept: "application/json"
            },
            body: formData
        })
        .then(r => r.json())
        .then(json => {
            if (json.sucesso) {
                // Reload the list
                MedicoesManager.carregarMedicoes(convenioId);
                form.reset();

                const pct = document.getElementById("resultadoPorcentagem");
                if (pct) pct.innerText = "0%";

                //alert("Medição salva!");
            } else {
                alert("Erro: " + (json.mensagem || "desconhecido"));
            }
        })
        .catch(err => {
            console.error(err);
            alert("Erro ao salvar medição.");
        });
    },

    deletarMedicao(convenioId, medicaoId, btn) {
        if (!confirm("Apagar medição?")) return;

        fetch(`/convenios/${convenioId}/medicoes/${medicaoId}`, {
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name=\"csrf-token\"]').content,
                Accept: "application/json"
            }
        })
        .then(r => r.json())
        .then(json => {
            if (json.sucesso) {
                btn.closest("tr").remove();
            } else {
                alert("Erro ao apagar.");
            }
        })
        .catch(err => {
            console.error(err);
            alert("Erro inesperado ao apagar.");
        });
    }
};

const TermosManager = {
    carregarTermos(convenioId) {
        fetch(`/convenios/${convenioId}/termos`)
            .then(r => r.json())
            .then(json => {
                const lista = document.getElementById("lista-termos");
                if (!lista) {
                    console.error("#lista-termos não encontrado");
                    return;
                }

                lista.innerHTML = "";

                if (!json.sucesso || !json.termos || json.termos.length === 0) {
                    lista.innerHTML = `
                        <tr class="bg-white">
                            <td colspan="4" class="p-4 text-center text-gray-500">
                                Nenhum termo encontrado
                            </td>
                        </tr>`;
                    return;
                }

                json.termos.forEach(t => {
                    const valorPrazo = t.termo_valor !== null 
                        ? parseFloat(t.termo_valor).toLocaleString("pt-BR", {minimumFractionDigits: 2})
                        : t.termo_data;
                    
                    lista.innerHTML += `
                        <tr>
                            <td style="padding: 10px; border: 1px solid #ccc; text-align: center;">${t.numero_termo}</td>
                            <td style="padding: 10px; border: 1px solid #ccc; text-align: center;">${t.aditivo}</td>
                            <td style="padding: 10px; border: 1px solid #ccc; text-align: center;">${valorPrazo}</td>
                            <td style="padding: 10px; border: 1px solid #ccc; text-align: center;">
                                <button onclick="deletarTermo(${convenioId}, ${t.id}, this)"
                                        class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                                    Apagar
                                </button>
                            </td>
                        </tr>`;
                });
            })
            .catch(err => {
                console.error("Erro ao carregar termos:", err);
            });
    },


    salvarTermo() {
        const form = document.getElementById("formNovoTermo");
        const convenioId = form.querySelector("[name='convenio_id']").value;

        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        const valor = form.querySelector("#termo_valor");
        if (valor && valor.value) valor.value = MoneyUtils.parseMoedaParaFloat(valor.value);

        const formData = new FormData(form);

        fetch(`/convenios/${convenioId}/termos`, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                Accept: "application/json"
            },
            body: formData
        })
        .then(r => r.json())
        .then(json => {
            if (json.sucesso) {
                // Reload the list
                TermosManager.carregarTermos(convenioId);
                form.reset();

                //alert("Termo salvo!");
            } else {
                alert("Erro: " + (json.mensagem || "desconhecido"));
            }
        })
        .catch(err => {
            console.error(err);
            alert("Erro ao salvar Termo.");
        });
    },

    deletarTermo(convenioId, termoId, btn) {
        if (!confirm("Apagar termo?")) return;

        fetch(`/convenios/${convenioId}/termos/${termoId}`, {
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name=\"csrf-token\"]').content,
                Accept: "application/json"
            }
        })
        .then(r => r.json())
        .then(json => {
            if (json.sucesso) {
                btn.closest("tr").remove();
            } else {
                alert("Erro ao apagar.");
            }
        })
        .catch(err => {
            console.error(err);
            alert("Erro inesperado ao apagar.");
        });
    }
};

/* ============================================================
 *  MÓDULO: MoneyUtils
 * ============================================================ */

const MoneyUtils = {
    initMoneyFields() {
        document.querySelectorAll(".money").forEach(el => this.formatarMoeda(el));
    },

    formatarMoeda(input) {
        if (!input) return;

        if (input.value) {
            let v = input.value.replace(/\D/g, "");
            if (v) {
                v = (parseFloat(v) / 100).toFixed(2);
                v = v.replace(".", ",");
                v = v.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                input.value = v;
            }
        }

        input.addEventListener("input", () => {
            let v = input.value.replace(/\D/g, "");
            v = (parseInt(v || 0) / 100).toFixed(2);
            v = v.replace(".", ",");
            v = v.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            input.value = v;
        });
    },

    parseMoedaParaFloat(v) {
        return v ? parseFloat(v.replace(/\./g, "").replace(",", ".")) : 0;
    }

};

/* ============================================================
 *  MÓDULO: FormsManager
 * ============================================================ */

const FormsManager = {
    setupAcompanhamentoForm() {
        const form = document.getElementById("formNovoAcompanhamento");
        if (!form) return;

        form.addEventListener("submit", function (e) {
            e.preventDefault();
            const valor = document.getElementById("valor_liberado");
            if (valor) valor.value = MoneyUtils.parseMoedaParaFloat(valor.value);

            const formData = new FormData(form);
            const convenioId = formData.get("convenio_id");

            fetch(`/convenios/${convenioId}/acompanhamentos`, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                    Accept: "application/json"
                },
                body: formData
            })
            .then(r => r.json())
            .then(json => {
                if (json.sucesso) {
                    alert("Acompanhamento salvo!");
                    form.reset();
                    ModalManager.fecharModalAcompanhamento();
                    window.location.reload();
                } else {
                    alert("Erro: " + (json.mensagem || "desconhecido"));
                }
            })
            .catch(err => {
                console.error(err);
                alert("Erro inesperado.");
            });
        });
    },

    setupContratoForm() {
        const form = document.getElementById("formNovoContrato");
        if (!form) return;

        form.addEventListener("submit", function (e) {
            e.preventDefault();
            ContratosManager.salvarContrato();
        });
    },

    setupMedicaoForm() {
        const form = document.getElementById("formNovaMedicao");
        if (!form) return;

        form.addEventListener("submit", function (e) {
            e.preventDefault();
            MedicoesManager.salvarMedicao();
        });
    },

    setupTermoForm() {
        const form = document.getElementById("formNovoTermo");
        if (!form) return;

        form.addEventListener("submit", function (e) {
            e.preventDefault();
            TermosManager.salvarTermo();
        });
    },

    setupAcaoForm() {
        const form = document.getElementById("formNovaAcao");
        if (!form) return;

        form.addEventListener("submit", function (e) {
            e.preventDefault();
            AcoesManager.salvarAcao();
        });
    },

    setupMoneySubmitHandler() {
        document.querySelectorAll("form").forEach(form => {
            form.addEventListener("submit", () => {
                form.querySelectorAll(".money").forEach(i => {
                    i.value = MoneyUtils.parseMoedaParaFloat(i.value);
                });
            });
        });
    }
};

/* ============================================================
 *  FUNÇÕES DE INICIALIZAÇÃO
 * ============================================================ */

function initApp() {
    MoneyUtils.initMoneyFields();
    FormsManager.setupAcompanhamentoForm();
    FormsManager.setupAcaoForm();
    FormsManager.setupMedicaoForm();
    FormsManager.setupTermoForm();
    FormsManager.setupContratoForm();
    FormsManager.setupMoneySubmitHandler();
}

function exposeGlobals() {
    window.abrirModalAcompanhamento = ModalManager.abrirModalAcompanhamento;
    window.fecharModalAcompanhamento = ModalManager.fecharModalAcompanhamento;

    window.abrirModalAcoes = ModalManager.abrirModalAcoes;
    window.fecharModalAcoes = ModalManager.fecharModalAcoes;

    window.abrirModalContratos = (id) => ModalManager.abrirModalContratos(id);
    window.fecharModalContratos = ModalManager.fecharModalContratos;
    window.salvarContrato = ContratosManager.salvarContrato;
    window.deletarContrato = ContratosManager.deletarContrato;
    

    window.abrirModalMedicoes = (id) => ModalManager.abrirModalMedicoes(id);
    window.fecharModalMedicoes = ModalManager.fecharModalMedicoes;
    window.deletarMedicao = MedicoesManager.deletarMedicao;

    window.abrirModalTermos = (id) => ModalManager.abrirModalTermos(id);
    window.fecharModalTermos = ModalManager.fecharModalTermos;
    window.deletarTermo = TermosManager.deletarTermo;

    window.updatePorcentagemValue = MoneyUtils.updatePorcentagemValue;
}

