<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão de Convênios</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
    </style>

    @stack('styles')
</head>

<body class="bg-gray-100 text-gray-800">

    {{-- Layout principal com sidebar fixa --}}
    <div class="flex h-screen overflow-hidden">

        {{-- Sidebar fixa --}}
        <aside class="w-56 bg-gray-800 text-white flex flex-col justify-between fixed top-0 left-0 h-full p-6">
            <div>

                <div class="flex justify-center mb-4">
                    <img src="{{ asset('img/Logo1000.png') }}" alt="CloudBox" class="mx-auto" style="height: 100px; width: auto !important; margin-bottom:10px;">
                </div>

                <a href="{{ route('convenio.index') }}"
                    class="mb-3 block px-4 py-2 rounded transition-all
                          {{ request()->routeIs('convenio.index') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                    Lista de Convênios
                </a>

                <a href="{{ route('convenios.info') }}"
                    class="mb-3 block px-4 py-2 rounded transition-all
                          {{ request()->routeIs('convenios.info') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                    Dados
                </a>

                @auth
                @if (auth()->user()->role === 'admin_master')
                <a href="{{ route('admin.requisicoes') }}"
                    class="mb-3 block px-4 py-2 rounded transition-all
                                  {{ request()->routeIs('admin.requisicoes') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                    Ver Requisições
                </a>

                <a href="{{ route('admin.dadosAuxiliares') }}"
                    class="mb-3 block px-4 py-2 rounded transition-all
                                  {{ request()->routeIs('admin.dadosAuxiliares') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                    Dados Auxiliares
                </a>
                @endif
                @endauth
            </div>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                    class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold px-4 py-2 rounded transition-all">
                    Logout
                </button>
            </form>
        </aside>

        {{-- Conteúdo principal com rolagem independente --}}
        <main class="ml-56 flex-1 h-screen overflow-y-auto p-6">
            <div class="bg-white p-6 rounded-xl shadow-md">
                @yield('content')
            </div>
        </main>

    </div>

    @stack('scripts')
</body>

</html>