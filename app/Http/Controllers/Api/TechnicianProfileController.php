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

        // Aquí podrías cargar especialidades / zonas / documentos si quieres
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

        $data = $request->validate([
            'cedula'           => ['nullable', 'string', 'max:20'],
            'nombres'          => ['nullable', 'string', 'max:100'],
            'apellidos'        => ['nullable', 'string', 'max:100'],
            'telefono'         => ['nullable', 'string', 'max:20'],
            'direccion'        => ['nullable', 'string', 'max:255'],
            'tipo_sangre'      => ['nullable', 'string', 'max:10'],
            'contacto_emergencia' => ['nullable', 'string', 'max:100'],
            // luego puedes añadir manejo de especialidades/zonas con arrays
        ]);

        $tecnico->fill($data);

        if ($tecnico->cedula && $tecnico->nombres && $tecnico->apellidos && $tecnico->telefono) {
            $tecnico->perfil_completo = true;
        }

        $tecnico->save();

        return response()->json([
            'message' => 'Perfil actualizado',
            'tecnico' => $tecnico,
        ]);
    }
}
