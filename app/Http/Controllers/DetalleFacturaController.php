<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DetalleFactura;

class DetalleFacturaController extends Controller
{
    /**
     * Buscar ítems por descripción para autocompletado.
     */
    public function buscar(Request $request)
    {
        $query = $request->input('q');

        $resultados = DetalleFactura::with('factura')
            ->where('descripcion', 'like', '%' . $query . '%')
            ->limit(10)
            ->get()
            ->map(function ($detalle) {
                return [
                    'id' => $detalle->id,
                    'codigo' => $detalle->codigo,
                    'descripcion' => $detalle->descripcion,
                    'precio_unitario' => $detalle->precio_unitario,
                    'emisor' => $detalle->factura->nombre_comercial_emisor ?? '',
                ];
            });

        return response()->json($resultados);
    }
}

