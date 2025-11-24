<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WorkSessionPause;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class WorkSessionPauseSyncController extends Controller
{
    public function sync(Request $request)
    {
        // El payload que llega desde Flutter tiene forma:
        // {
        //   "0": {
        //      "local_id": 1,
        //      "device_session_uuid": "DEVICEUUID_10",
        //      "start_at": "2025-11-24T00:10:00.000Z",
        //      "end_at": "2025-11-24T00:15:00.000Z"
        //   },
        //   "1": { ... }
        // }

        // Validamos esa estructura "plana" con claves numéricas en la raíz
        $data = $request->validate([
            '*.local_id'            => ['required', 'integer'],
            '*.device_session_uuid' => ['required', 'string', 'max:100'],
            '*.start_at'            => ['required', 'date'],
            '*.end_at'              => ['nullable', 'date'],
        ]);

        $created = [];
        $skipped = [];

        foreach ($data as $row) {
            $localId        = $row['local_id'];
            $sessionUuid    = $row['device_session_uuid']; // ya viene construido desde Flutter
            $startAt        = Carbon::parse($row['start_at']);
            $endAt          = !empty($row['end_at']) ? Carbon::parse($row['end_at']) : null;

            $pause = WorkSessionPause::create([
                'uuid'         => (string) Str::uuid(),
                'session_uuid' => $sessionUuid,
                'start_at'     => $startAt,
                'end_at'       => $endAt,
            ]);

            $created[] = [
                'local_id' => $localId,
                'uuid'     => $pause->uuid,
            ];
        }

        return response()->json([
            'status'  => 'ok',
            'created' => $created,
            'skipped' => $skipped,
        ]);
    }
}
