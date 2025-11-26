<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WorkSession;
use App\Models\WorkSessionPhoto;

class JornadaFotosController extends Controller
{
    /**
     * Recibe las fotos (online u offline),
     * las guarda en storage y registra en BD.
     *
     * Flutter debe enviar:
     * - session_key     => string (device_session_uuid de esa jornada)
     * - selfie_inicio   => archivo (opcional)
     * - foto_contexto   => archivo (opcional)
     */
    public function store(Request $request)
    {
        // ✅ Ahora validamos por device_session_uuid, no por id numérico
        $validated = $request->validate([
            'session_key'   => 'required|string|exists:work_sessions,device_session_uuid',

            'selfie_inicio' => 'nullable|image|mimes:jpg,jpeg,png|max:10240',
            'foto_contexto' => 'nullable|image|mimes:jpg,jpeg,png|max:10240',
        ]);

        // Buscamos la sesión por su device_session_uuid
        $session = WorkSession::where('device_session_uuid', $validated['session_key'])->firstOrFail();

        $createdPhotos = [];
        $existingPhotos = [];

        // -----------------------------------------------------
        // SELFIE INICIO
        // -----------------------------------------------------
        if ($request->hasFile('selfie_inicio')) {
            $exists = WorkSessionPhoto::where('work_session_id', $session->id)
                ->where('type', 'selfie_inicio')
                ->first();

            if ($exists) {
                $existingPhotos[] = $exists;
            } else {
                $pathSelfie = $request->file('selfie_inicio')->store('jornadas', 'public');

                $createdPhotos[] = WorkSessionPhoto::create([
                    'work_session_id' => $session->id,
                    'type'            => 'selfie_inicio',
                    'photo_path'      => $pathSelfie,
                ]);
            }
        }

        // -----------------------------------------------------
        // FOTO CONTEXTO INICIO
        // -----------------------------------------------------
        if ($request->hasFile('foto_contexto')) {
            $exists = WorkSessionPhoto::where('work_session_id', $session->id)
                ->where('type', 'contexto_inicio')
                ->first();

            if ($exists) {
                $existingPhotos[] = $exists;
            } else {
                $pathContext = $request->file('foto_contexto')->store('jornadas', 'public');

                $createdPhotos[] = WorkSessionPhoto::create([
                    'work_session_id' => $session->id,
                    'type'            => 'contexto_inicio',
                    'photo_path'      => $pathContext,
                ]);
            }
        }

        return response()->json([
            'saved'              => true,
            'message'            => 'Fotos procesadas correctamente.',
            'photos_saved_count' => count($createdPhotos),
            'created_photos'     => $createdPhotos,
            'existing_photos'    => $existingPhotos,
        ], 200);
    }
}
