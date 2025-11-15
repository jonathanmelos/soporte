<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WorkSession;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class WorkSessionSyncController extends Controller
{
    public function sync(Request $request)
    {
        $user = $request->user();
        $tecnico = $user->tecnico;

        if (! $tecnico) {
            return response()->json(['message' => 'Técnico no encontrado'], 404);
        }

        $data = $request->validate([
            '*.device_session_uuid' => ['nullable', 'string', 'max:255'],
            '*.started_at'          => ['required', 'date'],
            '*.ended_at'            => ['nullable', 'date'],
            '*.duration_seconds'    => ['nullable', 'integer'],
            '*.start_lat'           => ['nullable', 'numeric'],
            '*.start_lng'           => ['nullable', 'numeric'],
            '*.end_lat'             => ['nullable', 'numeric'],
            '*.end_lng'             => ['nullable', 'numeric'],
        ]);

        $created = [];
        $skipped = [];

        foreach ($data as $item) {
            $query = WorkSession::where('tecnico_id', $tecnico->id);

            if (! empty($item['device_session_uuid'])) {
                $query->where('device_session_uuid', $item['device_session_uuid']);
            } else {
                $query->where('started_at', Carbon::parse($item['started_at']));
            }

            $existing = $query->first();

            if ($existing) {
                $skipped[] = $existing->id;
                continue;
            }

            $session = WorkSession::create([
                'tecnico_id'        => $tecnico->id,
                'started_at'        => $item['started_at'],
                'ended_at'          => $item['ended_at'] ?? null,
                'duration_seconds'  => $item['duration_seconds'] ?? null,
                'start_lat'         => $item['start_lat'] ?? null,
                'start_lng'         => $item['start_lng'] ?? null,
                'end_lat'           => $item['end_lat'] ?? null,
                'end_lng'           => $item['end_lng'] ?? null,
                'device_session_uuid' => $item['device_session_uuid'] ?? null,
            ]);

            $created[] = $session->id;
        }

        return response()->json([
            'message' => 'Sincronización completada',
            'created' => $created,
            'skipped' => $skipped,
        ]);
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $tecnico = $user->tecnico;

        if (! $tecnico) {
            return response()->json(['message' => 'Técnico no encontrado'], 404);
        }

        $sessions = WorkSession::where('tecnico_id', $tecnico->id)
            ->orderByDesc('started_at')
            ->limit(100)
            ->get();

        return response()->json($sessions);
    }
}
