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
            $start = $s->started_at;
            $end   = $s->ended_at;

            // Total de horas de la sesión
            $horas = 0.0;
            if (!is_null($s->duration_seconds)) {
                $horas = round($s->duration_seconds / 3600, 2);
                $totalHoras += $horas;
            }

            // Tipo de jornada según hora de inicio y día
            $tipoJornada = $this->clasificarJornada($start);

            // Obtenemos las tarifas del técnico (pueden venir null)
            $tec          = $s->tecnico;
            $tarifa1      = $tec?->tarifa_hora_1 ?? 0;
            $tarifa2      = $tec?->tarifa_hora_2 ?? 0;
            $tarifa3      = $tec?->tarifa_hora_3 ?? 0;

            // Elegimos la tarifa según el tipo de jornada
            switch ($tipoJornada) {
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

            // Precio total de la sesión
            $precioTotal  = round($horas * $precioUnitario, 2);
            $totalPrecio += $precioTotal;

            $rows[] = [
                'fecha'           => $start ? $start->toDateString() : '',
                'dia'             => $start
                    ? ucfirst(Carbon::parse($start)->locale('es')->dayName)
                    : '',
                'tecnico'         => $this->nombreTecnico($s->tecnico),
                'hora_entrada'    => $start ? $start->format('H:i') : '',
                'hora_salida'     => $end ? $end->format('H:i') : '',
                'horas'           => $horas,
                'tipo_jornada'    => $tipoJornada,

                // Nuevos campos para precios
                'precio_unitario' => $precioUnitario,
                'precio_total'    => $precioTotal,
            ];
        }

        // Técnicos para el combo
        $tecnicos = Tecnico::orderBy('nombres')->get();

        return view('horas_tecnicos.horas', [
            'rows'        => $rows,
            'totalHoras'  => $totalHoras,
            'totalPrecio' => $totalPrecio,
            // estos nombres son los que usas en la vista
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
     * Clasifica el tipo de jornada según la hora de inicio y el día:
     *
     * - Lunes a viernes:
     *   - 08:00–17:30  → normal
     *   - >17:30–24:00 → adicional
     *   - 00:00–08:00  → extra
     *
     * - Sábado y domingo → extra
     */
    protected function clasificarJornada(?Carbon $inicio): string
    {
        if (!$inicio) {
            return '';
        }

        // Fines de semana siempre "extra"
        if ($inicio->isWeekend()) {
            return 'extra';
        }

        // Minutos desde medianoche
        $minutos = $inicio->hour * 60 + $inicio->minute;

        $ochoAM         = 8 * 60;           // 08:00
        $cinco30PM      = 17 * 60 + 30;     // 17:30
        $casiMedianoche = 24 * 60 - 1;      // 23:59

        if ($minutos >= $ochoAM && $minutos <= $cinco30PM) {
            return 'normal';
        }

        // Adicionales después de 17:30 hasta la medianoche
        if ($minutos > $cinco30PM && $minutos <= $casiMedianoche) {
            return 'adicional';
        }

        // Todo lo que quede (00:00–07:59) lo consideramos extra
        return 'extra';
    }
}
