<?php

namespace App\Http\Controllers;

use App\Models\Ubicacion;
use App\Models\Cliente;
use Illuminate\Http\Request;

class UbicacionController extends Controller
{
    /**
     * Crear o actualizar ubicación desde el modal de la vista diario
     */
    public function storeFromDiario(Request $request)
    {
        $request->validate([
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'nombre' => 'nullable|string|max:200',
            'radio_m' => 'nullable|numeric',
            'cliente_id' => 'nullable|exists:clientes,id_cliente',
        ]);

        // Si existe ID, actualizamos. Si no, creamos nueva.
        $ubicacion = Ubicacion::firstOrNew([
            'lat' => $request->lat,
            'lng' => $request->lng,
        ]);

        $ubicacion->nombre = $request->nombre;
        $ubicacion->radio_m = $request->radio_m ?: 25; // default 25m
        $ubicacion->save();

        // Asociar cliente
        if ($request->cliente_id) {
            $ubicacion->clientes()->syncWithoutDetaching([$request->cliente_id]);
        }

        return redirect()->back()->with('status', 'Ubicación guardada correctamente.');
    }
}
