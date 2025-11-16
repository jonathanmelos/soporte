<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Device;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    public function register(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'device_uuid' => 'required|string|max:100',
            'platform'    => 'required|string|max:50',
            'brand'       => 'nullable|string|max:100',
            'model'       => 'nullable|string|max:100',
            'os_version'  => 'nullable|string|max:50',
            'app_version' => 'nullable|string|max:50',
        ]);

        $device = Device::updateOrCreate(
            [
                'user_id'     => $user->id,
                'device_uuid' => $data['device_uuid'],
            ],
            [
                'platform'    => $data['platform'],
                'brand'       => $data['brand'] ?? null,
                'model'       => $data['model'] ?? null,
                'os_version'  => $data['os_version'] ?? null,
                'app_version' => $data['app_version'] ?? null,
                'last_seen_at'=> now(),
            ]
        );

        return response()->json([
            'status'    => 'ok',
            'device_id' => $device->id,
        ]);
    }
}
