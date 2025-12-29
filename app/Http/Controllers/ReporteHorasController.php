<?php

namespace App\Http\Controllers;

use App\Models\Tecnico;
use App\Models\WorkSession;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReporteHorasController extends Controller
{
    /**
     * Vista 1: reporte de horas por técnico.
     */
    public function index(Request $request)
    {
        // Fechas por defecto:
        // - Desde: día 1 del mes actual
        // - Hasta: hoy
        $hoy          = now()->toDateString();
        $desdeDefault = now()->startOfMonth()->toDateString();

        $desde     = $request->input('desde', $desdeDefault);
        $hasta     = $request->input('hasta', $hoy);
        $tecnicoId = $request->input('tecnico_id');

        $query = WorkSession::query()
            ->with('tecnico')
            ->when($desde, function ($q) use ($desde) {
                $q->whereDate('started_at', '>=', $desde);
            })
            ->when($hasta, function ($q) use ($hasta) {
                $q->whereDate('started_at', '<=', $hasta);
            })
            ->when($tecnicoId, function ($q) use ($tecnicoId) {
                $q->where('tecnico_id', $tecnicoId);
            })
            ->orderBy('started_at', 'asc');

        $sessions = $query->get();

        $rows        = [];
        $totalHoras  = 0.0;
        $totalPrecio = 0.0;

        foreach ($sessions as $s) {
            /** @var ?Carbon $start */
            $start = $s->started_at;
            /** @var ?Carbon $end */
            $end   = $s->ended_at;

            // Si no tenemos inicio no podemos calcular nada
            if (!$start) {
                continue;
            }

            // Si no hay ended_at pero sí duration_seconds, lo calculamos
            if (!$end && !is_null($s->duration_seconds)) {
                $end = $start->copy()->addSeconds($s->duration_seconds);
            }

            // Si aún así no tenemos fin, saltamos la sesión
            if (!$end) {
                continue;
            }

            // Si terminó antes de empezar, asumimos que cruza medianoche
            if ($end->lt($start)) {
                $end = $end->copy()->addDay();
            }

            // Partimos la sesión en segmentos según tipo de jornada
            $segments = $this->segmentarPorTipoJornada($start, $end);

            $tec     = $s->tecnico;
            $tarifa1 = $tec?->tarifa_hora_1 ?? 0; // normal
            $tarifa2 = $tec?->tarifa_hora_2 ?? 0; // adicional
            $tarifa3 = $tec?->tarifa_hora_3 ?? 0; // extra

            foreach ($segments as $seg) {
                /** @var Carbon $segStart */
                $segStart = $seg['start'];
                /** @var Carbon $segEnd */
                $segEnd   = $seg['end'];
                $tipo     = $seg['tipo_jornada'];

                $seconds = $segStart->diffInSeconds($segEnd);
                $horas   = round($seconds / 3600, 2);

                if ($horas <= 0) {
                    continue;
                }

                // Elegimos la tarifa según el tipo de jornada
                switch ($tipo) {
                    case 'normal':
                        $precioUnitario = $tarifa1;
                        break;
                    case 'adicional':
                        $precioUnitario = $tarifa2;
                        break;
                    case 'extra':
                        $precioUnitario = $tarifa3;
                        break;
                    default:
                        $precioUnitario = 0;
                        break;
                }

                $precioTotal  = round($horas * $precioUnitario, 2);
                $totalHoras  += $horas;
                $totalPrecio += $precioTotal;

                $rows[] = [
                    'fecha'           => $segStart->toDateString(),
                    'dia'             => ucfirst($segStart->locale('es')->dayName),
                    'tecnico'         => $this->nombreTecnico($s->tecnico),
                    'hora_entrada'    => $segStart->format('H:i'),
                    'hora_salida'     => $segEnd->format('H:i'),
                    'horas'           => $horas,
                    'tipo_jornada'    => $tipo,
                    'precio_unitario' => $precioUnitario,
                    'precio_total'    => $precioTotal,
                ];
            }
        }

        // Ordenamos por fecha y hora entrada por si acaso
        usort($rows, function ($a, $b) {
            return strcmp($a['fecha'] . ' ' . $a['hora_entrada'], $b['fecha'] . ' ' . $b['hora_entrada']);
        });

        // Técnicos para el combo
        $tecnicos = Tecnico::orderBy('nombres')->get();

        return view('horas_tecnicos.horas', [
            'rows'        => $rows,
            'totalHoras'  => $totalHoras,
            'totalPrecio' => $totalPrecio,
            'desde'       => $desde,
            'hasta'       => $hasta,
            'tecnicos'    => $tecnicos,
            'tecnicoId'   => $tecnicoId,
        ]);
    }

    /**
     * Devuelve el nombre a mostrar del técnico:
     * usa el accessor nombre_completo del modelo,
     * que ya hace fallback al correo si no hay nombres/apellidos.
     */
    protected function nombreTecnico(?Tecnico $tecnico): string
    {
        if (!$tecnico) {
            return '';
        }

        return trim($tecnico->nombre_completo);
    }

    /**
     * Parte una sesión en segmentos por tipo de jornada:
     *
     * - Lunes a viernes:
     *   - 00:00–06:00 → extra
     *   - 06:00–08:00 → extra (franja intermedia, la contamos como extra)
     *   - 08:00–17:00 → normal
     *   - 17:00–24:00 → adicional
     *
     * - Sábado y domingo: todo se considera extra.
     *
     * @return array cada item: ['start' => Carbon, 'end' => Carbon, 'tipo_jornada' => string]
     */
    protected function segmentarPorTipoJornada(Carbon $start, Carbon $end): array
    {
        $segments = [];
        $cursor   = $start->copy();

        while ($cursor < $end) {
            $dayStart = $cursor->copy()->startOfDay();
            $dayEnd   = $cursor->copy()->endOfDay(); // 23:59:59

            // Este trozo de sesión no pasa de este día
            $limit = $end->copy()->min($dayEnd);

            // Si es fin de semana, TODO el tramo es extra
            if ($cursor->isWeekend()) {
                $segments[] = [
                    'start'        => $cursor->copy(),
                    'end'          => $limit->copy(),
                    'tipo_jornada' => 'extra',
                ];
                // avanzamos al siguiente segundo después del límite
                $cursor = $limit->copy()->addSecond();
                continue;
            }

            // Definimos las bandas horarias para ese día
            $extraStart1   = $dayStart->copy()->setTime(0, 0);
            $extraEnd1     = $dayStart->copy()->setTime(6, 0);  // 00:00–06:00 extra

            $extraStart2   = $dayStart->copy()->setTime(6, 0);
            $extraEnd2     = $dayStart->copy()->setTime(8, 0);  // 06:00–08:00 extra (decisión conservadora)

            $normalStart   = $dayStart->copy()->setTime(8, 0);  // 08:00
            $normalEnd     = $dayStart->copy()->setTime(17, 0); // 17:00

            $adicionalStart= $dayStart->copy()->setTime(17, 0); // ~17:01 en la práctica
            $adicionalEnd  = $dayEnd->copy();                   // hasta 23:59:59

            $bands = [
                ['start' => $extraStart1,    'end' => $extraEnd1,     'tipo' => 'extra'],
                ['start' => $extraStart2,    'end' => $extraEnd2,     'tipo' => 'extra'],
                ['start' => $normalStart,    'end' => $normalEnd,     'tipo' => 'normal'],
                ['start' => $adicionalStart, 'end' => $adicionalEnd,  'tipo' => 'adicional'],
            ];

            foreach ($bands as $band) {
                $bandStart = $band['start'];
                $bandEnd   = $band['end'];

                // Recortamos la banda al tramo [cursor, limit]
                $segStart = $cursor->copy()->max($bandStart);
                $segEnd   = $limit->copy()->min($bandEnd);

                if ($segStart < $segEnd) {
                    $segments[] = [
                        'start'        => $segStart->copy(),
                        'end'          => $segEnd->copy(),
                        'tipo_jornada' => $band['tipo'],
                    ];
                }
            }

            // Pasamos al siguiente día / tramo
            $cursor = $limit->copy()->addSecond();
        }

        return $segments;
    }
}
