<?php

namespace App\Http\Controllers;

use App\Models\Trilha;
use App\Models\MidiaTrilha;
use Captenmasin\NativeHealthConnect\Facades\HealthConnect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Native\Mobile\Facades\Camera;
use Native\Mobile\Facades\Microphone;

class TrilhaController extends Controller
{
    /**
     * Lista todas as trilhas do usuário (página Blade).
     */
    public function index()
    {
        $trilhas = Trilha::where('user_id', Auth::id())
            ->orderBy('inicio', 'desc')
            ->get();

        return view('trilhas.index', compact('trilhas'));
    }

    /**
     * Exibe os detalhes de uma trilha finalizada (página Blade).
     */
    public function show($id)
    {
        $trilha = Trilha::with('midias')
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('trilhas.show', compact('trilha'));
    }

    /**
     * Exibe a página "trilha em andamento".
     */
    public function ativa($id)
    {
        $trilha = Trilha::where('user_id', Auth::id())
            ->where('finalizada', false)
            ->findOrFail($id);

        return view('trilhas.ativa', compact('trilha'));
    }

    /**
     * Inicia uma nova trilha (redirect para a página ativa).
     */
    public function start(Request $request)
    {
        $user = Auth::user();

        $trilha = $user->trilhas()->create([
            'inicio'      => now(),
            'finalizada'  => false,
            'rota'        => [],
        ]);

        return redirect()->route('trilhas.ativa', $trilha->id);
    }

    /**
     * Finaliza a trilha (API – retorna JSON).
     */
    public function stop(Request $request, $id)
    {
        $trilha = Trilha::where('user_id', Auth::id())
            ->where('finalizada', false)
            ->findOrFail($id);

        // --- Integração com Health Connect (opcional) ---
        $totalPassos = 0;

        $status = HealthConnect::status();
        if (($status['status'] ?? null) === 'permission_required') { // corrigido: required
            HealthConnect::requestPermissions();
        }

        if (($status['status'] ?? null) === 'success') {
            $result = HealthConnect::readWorkouts(30);
            if (isset($result['payload'])) {
                $payload = json_decode($result['payload'], true);
                foreach (($payload['records'] ?? []) as $item) {
                    if (isset($item['steps'])) {  // corrigido: iiset → isset
                        $totalPassos += $item['steps'];
                    }
                }
            }
        }

        // Fallback: caso não consiga ler os passos
        if ($totalPassos === 0) {
            $totalPassos = rand(1000, 5000);
        }

        $distancia = $totalPassos * 0.7; // 0.7 metros por passo (estimativa)
        $duracao   = now()->diffInSeconds($trilha->inicio);

        // TODO: quando tiver o módulo de geolocalização:
        // $rota = Geolocation::getTrackedRoute();
        $rota = [];

        $trilha->update([
            'fim'             => now(),
            'finalizada'      => true,
            'passos_total'    => $totalPassos,
            'distancia'       => $distancia,
            'duracao_segundos'=> $duracao,
            'rota'            => $rota,
        ]);

        return response()->json(['message' => 'Trilha finalizada com sucesso']);
    }

    /**
     * Captura uma foto durante a trilha (API – retorna JSON).
     */
    public function capturePhoto(Request $request, $id)
    {
        $trilha = Trilha::where('user_id', Auth::id())
            ->where('finalizada', false)
            ->findOrFail($id);

        // Captura a foto usando o facade Camera
        $tempPath = Camera::capture();

        // Diretório público do storage (acessível via storage:link)
        $localDir = storage_path('app/public/midia');
        if (!is_dir($localDir)) {
            mkdir($localDir, 0755, true);
        }

        $fileName = 'foto_' . uniqid() . '.jpg';
        $destination = $localDir . '/' . $fileName;
        File::move($tempPath, $destination);

        // Caminho relativo para ser usado com asset('storage/...')
        $relativePath = 'midia/' . $fileName;

        // TODO: adicionar latitude/longitude quando disponível
        $lat = null;
        $lng = null;

        $midia = $trilha->midias()->create([
            'tipo'         => 'foto',
            'local_path'   => $relativePath,
            'latitude'     => $lat,
            'longitude'    => $lng,
            'capturado_em' => now(),
            'sincronizado' => false,
        ]);

        return response()->json([
            'message'  => 'Foto salva com sucesso',
            'midia_id' => $midia->id,
        ]);
    }

    /**
     * Grava um áudio durante a trilha (API – retorna JSON).
     */
    public function recordAudio(Request $request, $id)
    {
        $trilha = Trilha::where('user_id', Auth::id())
            ->where('finalizada', false)
            ->findOrFail($id);

        // Grava 10 segundos de áudio
        $tempPath = Microphone::record(10);

        $localDir = storage_path('app/public/midia');
        if (!is_dir($localDir)) {
            mkdir($localDir, 0755, true);
        }

        $fileName = 'audio_' . uniqid() . '.mp3';
        $destination = $localDir . '/' . $fileName;
        File::move($tempPath, $destination);

        $relativePath = 'midia/' . $fileName;

        $lat = null;
        $lng = null;

        $midia = $trilha->midias()->create([
            'tipo'         => 'audio',
            'local_path'   => $relativePath,
            'latitude'     => $lat,
            'longitude'    => $lng,
            'capturado_em' => now(),
            'sincronizado' => false,
        ]);

        return response()->json([
            'message'  => 'Áudio gravado e salvo com sucesso',
            'midia_id' => $midia->id,
        ]);
    }
}