<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TechnicianProfileController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();
        $tecnico = $user->tecnico;

        if (! $tecnico) {
            return response()->json(['message' => 'Técnico no encontrado'], 404);
        }

        $tecnico->load(['especialidades', 'zonas']);

        return response()->json([
            'user'    => $user,
            'tecnico' => $tecnico,
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();
        $tecnico = $user->tecnico;

        if (! $tecnico) {
            return response()->json(['message' => 'Técnico no encontrado'], 404);
        }

        // ⚠️ Usamos "sometimes" para permitir updates parciales
        $data = $request->validate([
            'cedula' => [
                'sometimes',
                'string',
                'max:20',
                'unique:tecnicos,cedula,' . $tecnico->id,
            ],
            'nombres'             => ['sometimes', 'string', 'max:100'],
            'apellidos'           => ['sometimes', 'string', 'max:100'],
            'telefono'            => ['sometimes', 'nullable', 'string', 'max:20'],
            'correo'              => ['sometimes', 'nullable', 'email', 'max:100'],
            'direccion'           => ['sometimes', 'nullable', 'string', 'max:255'],
            'tipo_sangre'         => ['sometimes', 'nullable', 'string', 'max:10'],
            'contacto_emergencia' => ['sometimes', 'nullable', 'string', 'max:100'],
        ]);

        // Actualiza solo los campos que llegaron
        $tecnico->fill($data);

        // Recalcular si el perfil está completo
        $tecnico->perfil_completo = (
            !empty($tecnico->cedula) &&
            !empty($tecnico->nombres) &&
            !empty($tecnico->apellidos) &&
            !empty($tecnico->telefono)
        );

        $tecnico->save();

        return response()->json([
            'message' => 'Perfil actualizado correctamente',
            'tecnico' => $tecnico->fresh(),
        ]);
    }
}
