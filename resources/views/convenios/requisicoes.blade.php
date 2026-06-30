@extends('convenios.app')
@section('content')
<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
<script> src="https://cdn.tailwindcss.com"</script>
<div class="container mx-auto px-4">
    <h1 class="text-xl font-bold mb-4 text-center">Requisições de Cadastro</h1>
   
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 text-center">
            {{ session('success') }}
        </div>
    @endif

    <table class="w-full table-auto border-collapse">
    <thead class="bg-gray-500 text-white">
        <tr>
            <th class="w-1/3 text-center px-4 py-2">Nome</th>
            <th class="w-1/3 text-center px-4 py-2">Email</th>
            <th class="w-1/3 text-center px-4 py-2">Ações</th>
        </tr>
    </thead>
    <tbody>
        @forelse($usuarios as $user)
            <tr class="border-b">
                <td class="text-center px-4 py-2">{{ $user->name }}</td>
                <td class="text-center px-4 py-2">{{ $user->email }}</td>
                <td class="text-center px-4 py-2">
                    <form method="POST" action="{{ route('admin.aprovar', $user->id) }}" class="inline-block">
                        @csrf
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-1 rounded">
                            Aprovar
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="3" class="text-center py-4">Nenhuma requisição pendente.</td>
            </tr>
        @endforelse
    </tbody>
</table>

</div>
@endsection
