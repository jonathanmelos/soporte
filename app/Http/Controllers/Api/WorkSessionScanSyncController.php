<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WorkSessionScan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class WorkSessionScanSyncController extends Controller
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
        //     "local_id": 1,
        //     "device_session_uuid": "DEVICEUUID_1",
        //     "project_code": "TEST123",
        //     "area": "ALMACEN",
        //     "description": "QR prueba",
        //     "scanned_at": "2025-11-24T00:50:00.000Z"
        //   },
        //   "1": { ... }
        // }

        $data = $request->validate([
            '*.local_id'            => ['required', 'integer'],
            '*.device_session_uuid' => ['required', 'string', 'max:100'],
            '*.project_code'        => ['nullable', 'string', 'max:100'],
            '*.area'                => ['nullable', 'string', 'max:100'],
            '*.description'         => ['nullable', 'string'],
            '*.scanned_at'          => ['required', 'date'],
        ]);

        $created = [];
        $skipped = [];

        foreach ($data as $row) {
            $sessionUuid = $row['device_session_uuid'];
            $localId     = $row['local_id'];

            $scan = WorkSessionScan::create([
                'uuid'         => (string) Str::uuid(),
                'session_uuid' => $sessionUuid,
                'project_code' => $row['project_code'] ?? null,
                'area'         => $row['area'] ?? null,
                'description'  => $row['description'] ?? null,
                'scanned_at'   => Carbon::parse($row['scanned_at']),
            ]);

            $created[] = [
                'local_id' => $localId,
                'uuid'     => $scan->uuid,
            ];
        }

        return response()->json([
            'status'  => 'ok',
            'created' => $created,
            'skipped' => $skipped,
        ]);
    }
}
