{{-- resources/views/horas_tecnicos/horas.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="container">

    <h1 class="h3 mb-3">Reporte de horas por técnico</h1>

    {{-- Filtros --}}
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end mb-0">
                <div class="col-md-3">
                    <label for="desde" class="form-label">Desde</label>
                    <input
                        type="date"
                        id="desde"
                        name="desde"
                        class="form-control"
                        value="{{ $desde }}"
                    >
                </div>

                <div class="col-md-3">
                    <label for="hasta" class="form-label">Hasta</label>
                    <input
                        type="date"
                        id="hasta"
                        name="hasta"
                        class="form-control"
                        value="{{ $hasta }}"
                    >
                </div>

                <div class="col-md-3">
                    <label for="tecnico_id" class="form-label">Técnico</label>
                    <select
                        id="tecnico_id"
                        name="tecnico_id"
                        class="form-select"
                    >
                        <option value="">Todos</option>
                        @foreach($tecnicos as $t)
                            <option value="{{ $t->id }}" {{ $tecnicoId == $t->id ? 'selected' : '' }}>
                                {{ $t->nombre_completo }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3 d-flex justify-content-start">
                    <button type="submit" class="btn btn-primary mt-auto">
                        Filtrar
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabla --}}
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Fecha</th>
                            <th>Día</th>
                            <th>Técnico</th>
                            <th>Hora entrada</th>
                            <th>Hora salida</th>
                            <th class="text-end">Total horas</th>
                            <th>Tipo jornada</th>
                            <th class="text-end">Precio unitario</th>
                            <th class="text-end">Precio total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rows as $row)
                            <tr>
                                <td>{{ $row['fecha'] }}</td>
                                <td>{{ $row['dia'] ? ucfirst($row['dia']) : '' }}</td>
                                <td>{{ $row['tecnico'] }}</td>
                                <td>{{ $row['hora_entrada'] }}</td>
                                <td>{{ $row['hora_salida'] }}</td>
                                <td class="text-end">{{ number_format($row['horas'], 2) }}</td>
                                <td>{{ $row['tipo_jornada'] }}</td>

                                {{-- Precio unitario (tarifa del técnico) --}}
                                <td class="text-end">
                                    @if($row['precio_unitario'] > 0)
                                        {{ number_format($row['precio_unitario'], 2) }}
                                    @else
                                        -
                                    @endif
                                </td>

                                {{-- Precio total (horas * tarifa) --}}
                                <td class="text-end">
                                    {{ number_format($row['precio_total'], 2) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-3">
                                    No hay datos para los filtros seleccionados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            {{-- 5 primeras columnas unidas para el texto "Totales" --}}
                            <th colspan="5">Totales</th>
                            {{-- Total de horas --}}
                            <th class="text-end">{{ number_format($totalHoras, 2) }}</th>
                            {{-- Tipo jornada / precio unitario sin totales --}}
                            <th></th>
                            <th></th>
                            {{-- Total de precios --}}
                            <th class="text-end">{{ number_format($totalPrecio, 2) }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection
