@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto">
        <h1 class="text-2xl font-bold mb-4">Minhas Trilhas</h1>

        {{-- Botão para iniciar nova trilha --}}
        <form method="POST" action="{{ route('trilhas.start') }}" class="mb-6">
            @csrf
            <button type="submit"
                    class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-lg shadow">
                ▶️ Iniciar Nova Trilha
            </button>
        </form>

        @if($trilhas->isEmpty())
            <p class="text-gray-500 text-center">Nenhuma trilha registrada ainda.</p>
        @else
            <div class="space-y-4">
                @foreach($trilhas as $trilha)
                    <a href="{{ $trilha->finalizada ? route('trilhas.show', $trilha->id) : route('trilhas.ativa', $trilha->id) }}"
                       class="block bg-white p-4 rounded-lg shadow hover:shadow-md transition">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="font-semibold">
                                    {{ $trilha->finalizada ? '✅ Finalizada' : '🟢 Em andamento' }}
                                </p>
                                <p class="text-sm text-gray-600">
                                    Início: {{ $trilha->inicio->format('d/m/Y H:i') }}
                                </p>
                            </div>
                            <div class="text-right">
                                @if($trilha->finalizada)
                                    <span class="text-sm">{{ $trilha->passos_total }} passos</span><br>
                                    <span class="text-sm">{{ number_format($trilha->distancia, 0) }} m</span>
                                @endif
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
@endsection