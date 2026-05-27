<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | TrilhaSegura</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
<div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8">
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-green-700">🌲 TrilhaSegura</h1>
        <p class="text-gray-600 mt-2">Acesse sua conta</p>
    </div>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-4">
            <label for="email" class="block text-gray-700 font-medium mb-2">E-mail</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                   class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 @error('email') border-red-500 @enderror">
        </div>

        <div class="mb-4">
            <label for="password" class="block text-gray-700 font-medium mb-2">Senha</label>
            <input type="password" name="password" id="password" required
                   class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
        </div>

        <div class="mb-6 flex items-center justify-between">
            <label class="inline-flex items-center">
                <input type="checkbox" name="remember" class="rounded border-gray-300 text-green-600 shadow-sm focus:ring-green-500">
                <span class="ml-2 text-sm text-gray-600">Lembrar-me</span>
            </label>
            <a href="#" class="text-sm text-green-600 hover:underline">Esqueceu a senha?</a>
        </div>

        <button type="submit"
                class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition">
            Entrar
        </button>
    </form>

    <div class="mt-6 text-center">
        <p class="text-gray-600">Não tem uma conta? <a href="#" class="text-green-600 hover:underline">Registre-se</a></p>
    </div>
</div>
</body>
</html>