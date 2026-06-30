@extends('convenios.app')

@section('content')
<div class="mb-6">
    <a href="{{ route('convenio.index') }}"
        class="bg-blue-800 hover:bg-blue-900 text-white font-bold py-2 px-4 rounded shadow inline-block">
        ← Voltar
    </a>
</div>

<h1 class="text-2xl font-bold text-center text-gray-800 mb-6">Gerenciar Dados Auxiliares</h1>

@foreach ($lists as $list)
    <div class="mb-8 max-w-2xl mx-auto">

        {{-- Header --}}
        <div class="flex justify-between items-center mb-2">
            <h2 class="text-xl font-semibold text-gray-700">{{ ucfirst($list) }}</h2>
            <button type="button"
                class="toggle-btn bg-gray-200 hover:bg-gray-300 px-3 py-1 rounded text-gray-700 font-semibold">
                Expandir
            </button>
        </div>

        {{-- Add item form --}}
        <form method="POST"
              action="{{ route('admin.dadosStore') }}"
              class="ajax-add-form bg-white p-6 rounded-lg shadow-md mb-4">
            @csrf

            <input type="hidden" name="list" value="{{ $list }}">

            <div class="flex gap-4">
                <input type="text" name="name"
                    placeholder="Novo item em {{ $list }}"
                    class="flex-1 border border-gray-300 rounded px-3 py-2"
                    required>

                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                    Adicionar
                </button>
            </div>
        </form>

        {{-- Items list --}}
        <ul class="bg-white p-4 rounded-lg shadow-md list-container" style="display: none;">
            @forelse($data[$list] as $item)
                <li class="flex justify-between items-center py-1 border-b border-gray-200">
                    <span>{{ $item->name }}</span>

                    <button class="ajax-delete-btn text-red-600 hover:text-red-800 font-semibold"
                            data-id="{{ $item->id }}">
                        Excluir
                    </button>
                </li>
            @empty
                <li class="text-gray-500 italic">Nenhum item cadastrado.</li>
            @endforelse
        </ul>
    </div>
@endforeach

@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {


       // AJAX - add
   
    document.querySelectorAll(".ajax-add-form").forEach(form => {
        form.addEventListener("submit", async function (e) {
            e.preventDefault();

            const formData = new FormData(form);

            const response = await fetch(form.action, {
                method: "POST",
                body: formData,
                headers: { "X-Requested-With": "XMLHttpRequest" }
            });

            const json = await response.json();

            if (json.success) {
                const ul = form.closest(".mb-8").querySelector(".list-container");

                const li = document.createElement("li");
                li.className = "flex justify-between items-center py-1 border-b border-gray-200";
                li.innerHTML = `
                    <span>${json.item.name}</span>
                    <button class="ajax-delete-btn text-red-600 hover:text-red-800 font-semibold"
                            data-id="${json.item.id}">
                        Excluir
                    </button>
                `;

                ul.appendChild(li);
                ul.style.display = "block";
                form.reset();
            }
        });
    });


    // AJAX - delete 
    
    document.addEventListener("click", async function (e) {
        if (!e.target.classList.contains("ajax-delete-btn")) return;

        const id = e.target.dataset.id;

        const response = await fetch(`{{ url('/admin/dadosAuxiliares') }}/${id}`, {
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "X-Requested-With": "XMLHttpRequest"
            }
        });

        const json = await response.json();

        if (json.success) {
            e.target.closest("li").remove();
        }
    });

    // Expand / Minimize
    document.querySelectorAll('.toggle-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const listContainer = this.closest('div.mb-8').querySelector('.list-container');

            if (listContainer.style.display === 'none') {
                listContainer.style.display = 'block';
                this.textContent = 'Minimizar';
            } else {
                listContainer.style.display = 'none';
                this.textContent = 'Expandir';
            }
        });
    });

});
</script>
@endpush
