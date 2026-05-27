<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TrilhaSegura</title>
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- Ícones simples com Heroicons CDN (opcional) --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@heroicons/vue@2.0.18/outline/style.css" />
</head>
<body class="bg-gray-100 min-h-screen">
<nav class="bg-green-700 text-white p-4 shadow">
    <div class="container mx-auto flex justify-between items-center">
        <a href="{{ url('/trilhas') }}" class="text-xl font-bold">🌲 TrilhaSegura</a>
        <div>
            @auth
                <span>{{ Auth::user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button class="ml-4 text-sm underline">Sair</button>
                </form>
            @endauth
        </div>
    </div>
</nav>
<main class="container mx-auto p-4">
    @yield('content')
</main>
@stack('scripts')
</body>
</html>