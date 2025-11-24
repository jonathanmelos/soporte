<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WorkSession;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class WorkSessionSyncController extends Controller
{
    public function sync(Request $request)
    {
        $user = $request->user();
        $tecnico = $user?->tecnico;

        if (! $tecnico) {
            return response()->json([
                'message' => 'Técnico no encontrado',
            ], 404);
        }

        // Flutter envía:
        // {
        //   "0": {
        //     "device_session_uuid": "DEVICEUUID_1",
        //     "device_uuid": "DEVICEUUID",
        //     "started_at": "2025-11-24T00:00:00.000Z",
        //     "ended_at":   "2025-11-24T01:00:00.000Z",
        //     "duration_seconds": 3600,
        //     "start_lat": null,
        //     "start_lng": null,
        //     "end_lat": null,
        //     "end_lng": null
        //   },
        //   "1": { ... }
        // }

        $data = $request->validate([
            '*.device_session_uuid' => ['required', 'string', 'max:255'],
            '*.device_uuid'         => ['required', 'string', 'max:100'],
            '*.started_at'          => ['required', 'date'],
            '*.ended_at'            => ['nullable', 'date'],
            '*.duration_seconds'    => ['nullable', 'integer'],
            '*.start_lat'           => ['nullable', 'numeric'],
            '*.start_lng'           => ['nullable', 'numeric'],
            '*.end_lat'             => ['nullable', 'numeric'],
            '*.end_lng'             => ['nullable', 'numeric'],
        ]);

        $created = [];
        $updated = [];

        foreach ($data as $row) {
            $deviceSessionUuid = $row['device_session_uuid'];

            // Buscar si ya existe sesión para este técnico + device_session_uuid
            $session = WorkSession::where('tecnico_id', $tecnico->id)
                ->where('device_session_uuid', $deviceSessionUuid)
                ->first();

            $isNew = false;
            if (! $session) {
                $session = new WorkSession();
                $session->tecnico_id = $tecnico->id;
                $session->device_session_uuid = $deviceSessionUuid;
                $session->uuid = (string) Str::uuid();
                $isNew = true;
            }

            $session->device_uuid = $row['device_uuid'];

            $session->started_at = Carbon::parse($row['started_at']);
            $session->ended_at   = !empty($row['ended_at'])
                ? Carbon::parse($row['ended_at'])
                : null;

            $session->duration_seconds = $row['duration_seconds'] ?? null;

            $session->start_lat = $row['start_lat'] ?? null;
            $session->start_lng = $row['start_lng'] ?? null;
            $session->end_lat   = $row['end_lat'] ?? null;
            $session->end_lng   = $row['end_lng'] ?? null;

            $session->save();

            if ($isNew) {
                $created[] = $session->id;
            } else {
                $updated[] = $session->id;
            }
        }

        return response()->json([
            'status'  => 'ok',
            'created' => $created,
            'updated' => $updated,
        ]);
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $tecnico = $user?->tecnico;

        if (! $tecnico) {
            return response()->json([
                'message' => 'Técnico no encontrado',
            ], 404);
        }

        $sessions = WorkSession::where('tecnico_id', $tecnico->id)
            ->orderByDesc('started_at')
            ->limit(100)
            ->get();

        return response()->json($sessions);
    }
}
