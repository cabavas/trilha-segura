@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow">
        <h1 class="text-2xl font-bold mb-4">Detalhes da Trilha</h1>

        <div class="grid grid-cols-2 gap-4 mb-6">
            <div class="bg-gray-50 p-3 rounded">
                <span class="text-sm text-gray-500">Início</span>
                <p class="font-semibold">{{ $trilha->inicio->format('d/m/Y H:i') }}</p>
            </div>
            <div class="bg-gray-50 p-3 rounded">
                <span class="text-sm text-gray-500">Fim</span>
                <p class="font-semibold">{{ $trilha->fim?->format('d/m/Y H:i') ?? '—' }}</p>
            </div>
            <div class="bg-gray-50 p-3 rounded">
                <span class="text-sm text-gray-500">Duração</span>
                <p class="font-semibold">{{ gmdate('H:i:s', $trilha->duracao_segundos ?? 0) }}</p>
            </div>
            <div class="bg-gray-50 p-3 rounded">
                <span class="text-sm text-gray-500">Passos</span>
                <p class="font-semibold">{{ $trilha->passos_total ?? 0 }}</p>
            </div>
            <div class="bg-gray-50 p-3 rounded">
                <span class="text-sm text-gray-500">Distância</span>
                <p class="font-semibold">{{ number_format($trilha->distancia ?? 0, 0) }} m</p>
            </div>
        </div>

        <h2 class="text-xl font-semibold mb-2">📸 Fotos e Áudios</h2>
        @if($trilha->midias->isEmpty())
            <p class="text-gray-500">Nenhuma mídia registrada.</p>
        @else
            <div class="grid grid-cols-3 gap-2">
                @foreach($trilha->midias as $midia)
                    <div class="bg-gray-100 p-2 rounded">
                        @if($midia->tipo === 'foto')
                            @php
                                // Tenta construir o caminho público se o arquivo foi salvo em storage/public
                                $publicPath = public_path('storage/' . $midia->local_path);
                                $exists = file_exists($publicPath);
                            @endphp
                            @if($exists)
                                <img src="{{ asset('storage/' . $midia->local_path) }}" class="w-full h-24 object-cover rounded"/>
                            @else
                                <div class="w-full h-24 bg-gray-200 flex items-center justify-center text-gray-500 rounded">
                                    📷 Indisponível
                                </div>
                            @endif
                        @else
                            {{-- Áudio --}}
                            @php
                                $audioPublicPath = public_path('storage/' . $midia->local_path);
                                $audioExists = file_exists($audioPublicPath);
                            @endphp
                            @if($audioExists)
                                <audio controls class="w-full mt-1">
                                    <source src="{{ asset('storage/' . $midia->local_path) }}" type="audio/mpeg">
                                    Seu navegador não suporta áudio.
                                </audio>
                            @else
                                <div class="text-center py-4 bg-gray-200 rounded">
                                    🎵 Áudio (arquivo não encontrado)
                                </div>
                            @endif
                        @endif
                        <p class="text-xs text-center mt-1">
                            {{ $midia->sincronizado ? '☁️ Sincronizado' : '📱 Local' }}
                        </p>
                    </div>
                @endforeach
            </div>
        @endif

        <a href="{{ route('trilhas.index') }}" class="block mt-6 text-center text-green-700 underline">
            ← Voltar para lista
        </a>
    </div>
@endsection