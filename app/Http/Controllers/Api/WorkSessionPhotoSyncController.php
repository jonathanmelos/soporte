<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WorkSession;
use App\Models\WorkSessionPhoto;
use Illuminate\Support\Facades\Storage;

class WorkSessionPhotoSyncController extends Controller
{
    /**
     * Recibe *muchas fotos* desde Flutter (modo offline-first)
     * y las guarda en:
     *  - storage/app/public/jornadas
     *  - tabla work_session_photos
     */
    public function sync(Request $request)
    {
        // Validación básica
        $request->validate([
            'photos' => 'required|array',
            'photos.*.work_session_id' => 'required|exists:work_sessions,id',
            'photos.*.type' => 'required|string|in:selfie_start,selfie_end,context_start,context_end',
        ]);

        $saved = [];

        foreach ($request->photos as $item) {

            // Si no trae archivo, ignorar (Flutter puede mandar algunas sin archivo si están corruptas)
            if (!$request->hasFile("files.{$item['local_id']}")) {
                continue;
            }

            $file = $request->file("files.{$item['local_id']}");

            // Guardar archivo
            $path = $file->store('jornadas', 'public');

            // Registrar en la base de datos
            $photo = WorkSessionPhoto::create([
                'work_session_id' => $item['work_session_id'],
                'type' => $item['type'],
                'photo_path' => $path,
            ]);

            $saved[] = [
                'local_id' => $item['local_id'],
                'server_id' => $photo->id,
                'server_path' => $photo->photo_path,
            ];
        }

        return response()->json([
            'message' => 'Fotos sincronizadas correctamente',
            'saved' => $saved,
        ]);
    }
}
