<?php

namespace App\Http\Controllers;

use App\Models\Tecnico;
use App\Models\WorkSession;
use App\Models\Ubicacion;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReporteDiarioController extends Controller
{
    /**
     * Vista 2: registro diario de sesiones con ubicaciones tomadas
     * de work_session_locations (entrada/salida + accuracy) y
     * verificación contra la tabla ubicaciones (lat/lng + radio_m)
     * y sus clientes asociados.
     */
    public function index(Request $request)
    {
        // Fecha seleccionada o hoy por defecto
        $fecha = $request->input('fecha', now()->toDateString());
        $tecnicoId = $request->input('tecnico_id');

        // Nombre del día (Lunes, Martes, etc.)
        $diaNombre = Carbon::parse($fecha)->locale('es')->dayName;

        // Traemos sesiones de ese día con técnico y locations ordenadas por recorded_at
        $query = WorkSession::query()
            ->with([
                'tecnico',
                'locations' => function ($q) {
                    $q->orderBy('recorded_at', 'asc');
                },
            ])
            ->whereDate('started_at', $fecha);

        if ($tecnicoId) {
            $query->where('tecnico_id', $tecnicoId);
        }

        $sessions = $query->orderBy('started_at', 'asc')->get();

        // Transformamos las sesiones a filas para la vista
        $rows = $sessions->map(function (WorkSession $s) use ($diaNombre) {

            $firstLocation = $s->locations->first();  // posible ubicación de entrada
            $lastLocation  = $s->locations->last();   // posible ubicación de salida

            /**
             * 1) Intentar coincidencia EXACTA (lat/lng) para entrada
             */
            $entradaUbicacion = $this->findUbicacionPorCoordenadas(
                $firstLocation?->lat,
                $firstLocation?->lng
            );

            /**
             * 2) Si no hay coincidencia exacta, usar búsqueda por radio/accuracy
             */
            if (!$entradaUbicacion) {
                $entradaUbicacion = $this->findUbicacionCercana(
                    $firstLocation?->lat,
                    $firstLocation?->lng,
                    $firstLocation?->accuracy
                );
            }

            /**
             * Igual para la salida
             */
            $salidaUbicacion = $this->findUbicacionPorCoordenadas(
                $lastLocation?->lat,
                $lastLocation?->lng
            );

            if (!$salidaUbicacion) {
                $salidaUbicacion = $this->findUbicacionCercana(
                    $lastLocation?->lat,
                    $lastLocation?->lng,
                    $lastLocation?->accuracy
                );
            }

            $entradaTieneCliente = $entradaUbicacion && $entradaUbicacion->clientes->isNotEmpty();
            $salidaTieneCliente  = $salidaUbicacion && $salidaUbicacion->clientes->isNotEmpty();

            return [
                'dia'          => $diaNombre,
                'tecnico'      => $s->tecnico?->nombre_completo ?? '',
                'hora_entrada' => $s->started_at?->format('H:i') ?? '',
                'hora_salida'  => $s->ended_at?->format('H:i') ?? '',

                // Ubicación de entrada
                'lat_entrada'           => $firstLocation?->lat,
                'lng_entrada'           => $firstLocation?->lng,
                'acc_entrada'           => $firstLocation?->accuracy,
                'entrada_ubicacion_id'  => $entradaUbicacion?->id,
                'entrada_tiene_cliente' => $entradaTieneCliente,
                'entrada_nombre'        => $entradaUbicacion?->nombre,

                // Ubicación de salida
                'lat_salida'            => $lastLocation?->lat,
                'lng_salida'            => $lastLocation?->lng,
                'acc_salida'            => $lastLocation?->accuracy,
                'salida_ubicacion_id'   => $salidaUbicacion?->id,
                'salida_tiene_cliente'  => $salidaTieneCliente,
                'salida_nombre'         => $salidaUbicacion?->nombre,

                'session_id'            => $s->id,
            ];
        });

        // Técnicos para el combo de filtros
        $tecnicos = Tecnico::orderBy('nombres')->get();

        // Clientes para el modal
        $clientes = Cliente::orderBy('empresa')->get();

        return view('horas_tecnicos.diario', [
            'rows'      => $rows,
            'fecha'     => $fecha,
            'diaNombre' => $diaNombre,
            'tecnicos'  => $tecnicos,
            'tecnicoId' => $tecnicoId,
            'clientes'  => $clientes,
        ]);
    }

    /**
     * 1) Busca una ubicación *exacta* por lat y lng.
     * Se usa primero porque en tu BD los valores suelen coincidir
     * con los de work_session_locations.
     */
    protected function findUbicacionPorCoordenadas($lat, $lng): ?Ubicacion
    {
        if ($lat === null || $lng === null) {
            return null;
        }

        return Ubicacion::with('clientes')
            ->where('lat', $lat)
            ->where('lng', $lng)
            ->first();
    }

    /**
     * 2) Si no hay coincidencia exacta, busca una ubicación existente
     * cercana a la coordenada dada usando radio_m o accuracy.
     */
    protected function findUbicacionCercana($lat, $lng, $accuracy): ?Ubicacion
    {
        if ($lat === null || $lng === null) {
            return null;
        }

        // Si no hay accuracy, usamos un mínimo de 20m
        $radioBusqueda = max($accuracy ?? 20.0, 20.0);

        // Traemos ubicaciones con sus clientes
        $ubicaciones = Ubicacion::with('clientes')->get();

        foreach ($ubicaciones as $ubi) {
            // Usamos el radio propio de la ubicación si existe; si no, el de búsqueda
            $radio = $ubi->radio_m ?? $radioBusqueda;

            $dist = $this->distanceMeters(
                (float) $lat,
                (float) $lng,
                (float) $ubi->lat,
                (float) $ubi->lng
            );

            if ($dist <= $radio) {
                return $ubi;
            }
        }

        return null;
    }

    /**
     * Calcula la distancia en metros entre dos puntos (lat/lng)
     * usando la fórmula de Haversine.
     */
    protected function distanceMeters(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $R = 6371000.0; // radio de la Tierra en metros

        $phi1 = deg2rad($lat1);
        $phi2 = deg2rad($lat2);
        $deltaPhi = deg2rad($lat2 - $lat1);
        $deltaLambda = deg2rad($lng2 - $lng1);

        $a = sin($deltaPhi / 2) ** 2
            + cos($phi1) * cos($phi2) * sin($deltaLambda / 2) ** 2;

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $R * $c;
    }
}
