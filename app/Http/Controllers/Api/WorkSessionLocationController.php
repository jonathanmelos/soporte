<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WorkSession;
use App\Models\WorkSessionLocation;
use Illuminate\Http\Request;

class WorkSessionLocationController extends Controller
{
    public function sync(Request $request)
    {
        $user = $request->user(); // viene del token
        $tecnicoId = optional($user->tecnico)->id;

        if (! $tecnicoId) {
            return response()->json([
                'message' => 'Técnico no encontrado',
            ], 404);
        }

        /**
         * Flutter envía algo así:
         *
         * {
         *   "0": {
         *     "device_session_uuid": "DEVICEUUID_1",
         *     "recorded_at": "2025-11-16T21:30:41.000Z",
         *     "lat": -0.2208,
         *     "lng": -78.4849,
         *     "accuracy": 12.8,
         *     "event_type": "ping"
         *   },
         *   "1": { ... }
         * }
         *
         * Es decir, un objeto con claves "0","1","2"... en el root.
         */
        $data = $request->validate([
            '*.device_session_uuid' => ['required', 'string', 'max:255'],
            '*.lat'                 => ['required', 'numeric'],
            '*.lng'                 => ['required', 'numeric'],
            '*.accuracy'            => ['nullable', 'numeric'],
            '*.event_type'          => ['nullable', 'string'],
            '*.recorded_at'         => ['required', 'date'],
        ]);

        $created = [];
        $skipped = [];

        foreach ($data as $item) {
            // Buscar la sesión asociada por device_session_uuid + técnico
            $session = WorkSession::where('tecnico_id', $tecnicoId)
                ->where('device_session_uuid', $item['device_session_uuid'])
                ->first();

            if (! $session) {
                // No encontré la sesión, la salto y la registro en skipped
                $skipped[] = $item['device_session_uuid'];
                continue;
            }

            $loc = WorkSessionLocation::create([
                'work_session_id' => $session->id,
                'tecnico_id'      => $tecnicoId,
                'lat'             => $item['lat'],
                'lng'             => $item['lng'],
                'accuracy'        => $item['accuracy'] ?? null,
                'event_type'      => $item['event_type'] ?? 'ping',
                'recorded_at'     => $item['recorded_at'],
                'source'          => 'mobile',
            ]);

            $created[] = $loc->id;
        }

        return response()->json([
            'status'       => 'ok',
            'created_ids'  => $created,
            'skipped_keys' => $skipped,
        ]);
    }
}
