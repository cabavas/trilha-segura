@extends('layouts.app')

@section('content')
    <div class="max-w-md mx-auto bg-white rounded-lg shadow p-6">
        <h1 class="text-xl font-bold mb-2">🔴 Trilha em Andamento</h1>
        <p class="text-gray-500 mb-6">Iniciada em {{ $trilha->inicio->format('d/m/Y H:i') }}</p>

        <div class="grid grid-cols-2 gap-4 mb-6">
            <button onclick="capturarFoto()"
                    class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-4 rounded-lg">
                📸 Foto
            </button>
            <button onclick="gravarAudio()"
                    class="bg-purple-500 hover:bg-purple-600 text-white font-bold py-3 px-4 rounded-lg">
                🎤 Áudio
            </button>
        </div>

        <div id="mensagem" class="text-center text-sm text-green-600 mb-4"></div>

        <button onclick="confirmarParar()"
                class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-4 rounded-lg">
            ⏹️ Finalizar Trilha
        </button>
    </div>
@endsection

@push('scripts')
    <script>
        const trilhaId = {{ $trilha->id }};

        async function capturarFoto() {
            mostrarMensagem('Capturando foto...');
            try {
                const response = await fetch(`/api/trilhas/${trilhaId}/foto`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    }
                });

                let data;
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    data = await response.json();
                } else {
                    throw new Error('Resposta inválida do servidor (não é JSON)');
                }

                if (!response.ok) {
                    throw new Error(data.error || 'Erro desconhecido');
                }

                mostrarMensagem(data.message || 'Foto salva!');
            } catch (error) {
                console.error(error);
                mostrarMensagem(error.message || 'Erro ao capturar foto.', true);
            }
        }

        async function gravarAudio() {
            mostrarMensagem('Gravando áudio (10s)...');
            try {
                const response = await fetch(`/api/trilhas/${trilhaId}/audio`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    }
                });

                let data;
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    data = await response.json();
                } else {
                    throw new Error('Resposta inválida do servidor (não é JSON)');
                }

                if (!response.ok) {
                    throw new Error(data.error || 'Erro desconhecido');
                }

                mostrarMensagem(data.message || 'Áudio salvo!');
            } catch (error) {
                console.error(error);
                mostrarMensagem(error.message || 'Erro ao gravar áudio.', true);
            }
        }

        function confirmarParar() {
            if (confirm('Deseja realmente finalizar a trilha?')) {
                fetch(`/api/trilhas/${trilhaId}/parar`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    }
                })
                    .then(async response => {
                        let data;
                        const contentType = response.headers.get('content-type');
                        if (contentType && contentType.includes('application/json')) {
                            data = await response.json();
                        } else {
                            throw new Error('Resposta inválida do servidor');
                        }
                        if (!response.ok) throw new Error(data.error || 'Erro ao finalizar');
                        window.location.href = `/trilhas/${trilhaId}`;
                    })
                    .catch(error => {
                        alert('Erro: ' + error.message);
                    });
            }
        }

        function mostrarMensagem(texto, erro = false) {
            const div = document.getElementById('mensagem');
            div.textContent = texto;
            div.className = `text-center text-sm mb-4 ${erro ? 'text-red-600' : 'text-green-600'}`;
            setTimeout(() => div.textContent = '', 4000);
        }
    </script>
@endpush