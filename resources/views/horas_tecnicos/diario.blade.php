{{-- resources/views/horas_tecnicos/diario.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="container">

    <h1 class="h3 mb-3">Registro diario de sesiones</h1>

    {{-- Mensaje de éxito al guardar ubicación --}}
    @if(session('status'))
        <div class="alert alert-success py-2">
            {{ session('status') }}
        </div>
    @endif

    {{-- Filtros --}}
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end mb-0">
                <div class="col-md-3">
                    <label for="fecha" class="form-label">Fecha</label>
                    <input
                        type="date"
                        id="fecha"
                        name="fecha"
                        class="form-control"
                        value="{{ $fecha }}"
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

                <div class="col-md-2 d-flex">
                    <button type="submit" class="btn btn-primary mt-auto">
                        Ver
                    </button>
                </div>

                {{-- Botón para volver al día de hoy con "Todos" --}}
                <div class="col-md-2 d-flex">
                    <a href="{{ route('reportes.diario', ['fecha' => now()->toDateString()]) }}"
                       class="btn btn-secondary mt-auto">
                        Hoy
                    </a>
                </div>
            </form>
        </div>
    </div>

    <p class="mb-3">
        <strong>Día:</strong> {{ ucfirst($diaNombre) }} ({{ $fecha }})
    </p>

    {{-- Tabla --}}
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Día</th>
                            <th>Técnico</th>
                            <th>Hora entrada</th>
                            <th>Ubicación entrada (lat, lng, acc)</th>
                            <th>Hora salida</th>
                            <th>Ubicación salida (lat, lng, acc)</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($rows as $row)
                            <tr>
                                <td>{{ ucfirst($row['dia']) }}</td>
                                <td>{{ $row['tecnico'] }}</td>

                                <td>{{ $row['hora_entrada'] }}</td>

                                {{-- Ubicación de entrada --}}
                                <td>
                                    @if($row['lat_entrada'] !== null && $row['lng_entrada'] !== null)
                                        @php
                                            $textoEntrada = $row['lat_entrada'].', '.$row['lng_entrada'];
                                            if ($row['acc_entrada'] !== null) {
                                                $textoEntrada .= ' ('.$row['acc_entrada'].' m)';
                                            }
                                            $entradaTieneUbicacion = !empty($row['entrada_ubicacion_id']) || !empty($row['entrada_nombre']);
                                        @endphp

                                        {{-- Título + botón Ver mapa --}}
                                        @if(!empty($row['entrada_nombre']))
                                            <div style="display:flex; align-items:center; gap:8px; margin-bottom:2px;">
                                                <strong style="font-size:0.95rem;">
                                                    {{ $row['entrada_nombre'] }}
                                                </strong>

                                                {{-- Botón Google Maps (asociada) --}}
                                                <a href="https://www.google.com/maps?q={{ $row['lat_entrada'] }},{{ $row['lng_entrada'] }}"
                                                   target="_blank"
                                                   style="display:inline-flex; align-items:center; gap:4px; font-size:0.8rem; padding:3px 8px; background:#0d6efd; color:#fff; border-radius:4px; text-decoration:none;">
                                                    <span>📍</span>
                                                    <span>Ver mapa</span>
                                                </a>
                                            </div>
                                        @else
                                            <div style="display:flex; align-items:center; gap:8px; margin-bottom:2px;">
                                                <strong style="font-size:0.95rem;">
                                                    Ubicación no asociada
                                                </strong>

                                                {{-- Botón Google Maps (no asociada) --}}
                                                <a href="https://www.google.com/maps?q={{ $row['lat_entrada'] }},{{ $row['lng_entrada'] }}"
                                                   target="_blank"
                                                   style="display:inline-flex; align-items:center; gap:4px; font-size:0.8rem; padding:3px 8px; background:#6c757d; color:#fff; border-radius:4px; text-decoration:none;">
                                                    <span>📍</span>
                                                    <span>Ver mapa</span>
                                                </a>
                                            </div>
                                        @endif

                                        {{-- Coordenadas clickeables debajo (para abrir modal) --}}
                                        <span
                                            style="{{ $entradaTieneUbicacion ? 'cursor: pointer; color:#0d6efd;' : 'text-decoration: underline; cursor: pointer; color:#0d6efd;' }}"
                                            onclick="abrirModalUbicacion(
                                                'entrada',
                                                '{{ $row['lat_entrada'] }}',
                                                '{{ $row['lng_entrada'] }}',
                                                '{{ $row['acc_entrada'] }}',
                                                '{{ $row['entrada_ubicacion_id'] }}'
                                            )"
                                        >
                                            {{ $textoEntrada }}
                                        </span>
                                    @else
                                        -
                                    @endif
                                </td>

                                <td>{{ $row['hora_salida'] }}</td>

                                {{-- Ubicación de salida --}}
                                <td>
                                    @if($row['lat_salida'] !== null && $row['lng_salida'] !== null)
                                        @php
                                            $textoSalida = $row['lat_salida'].', '.$row['lng_salida'];
                                            if ($row['acc_salida'] !== null) {
                                                $textoSalida .= ' ('.$row['acc_salida'].' m)';
                                            }
                                            $salidaTieneUbicacion = !empty($row['salida_ubicacion_id']) || !empty($row['salida_nombre']);
                                        @endphp

                                        {{-- Título + botón Ver mapa --}}
                                        @if(!empty($row['salida_nombre']))
                                            <div style="display:flex; align-items:center; gap:8px; margin-bottom:2px;">
                                                <strong style="font-size:0.95rem;">
                                                    {{ $row['salida_nombre'] }}
                                                </strong>

                                                <a href="https://www.google.com/maps?q={{ $row['lat_salida'] }},{{ $row['lng_salida'] }}"
                                                   target="_blank"
                                                   style="display:inline-flex; align-items:center; gap:4px; font-size:0.8rem; padding:3px 8px; background:#0d6efd; color:#fff; border-radius:4px; text-decoration:none;">
                                                    <span>📍</span>
                                                    <span>Ver mapa</span>
                                                </a>
                                            </div>
                                        @else
                                            <div style="display:flex; align-items:center; gap:8px; margin-bottom:2px;">
                                                <strong style="font-size:0.95rem;">
                                                    Ubicación no asociada
                                                </strong>

                                                <a href="https://www.google.com/maps?q={{ $row['lat_salida'] }},{{ $row['lng_salida'] }}"
                                                   target="_blank"
                                                   style="display:inline-flex; align-items:center; gap:4px; font-size:0.8rem; padding:3px 8px; background:#6c757d; color:#fff; border-radius:4px; text-decoration:none;">
                                                    <span>📍</span>
                                                    <span>Ver mapa</span>
                                                </a>
                                            </div>
                                        @endif

                                        {{-- Coordenadas clickeables debajo (para abrir modal) --}}
                                        <span
                                            style="{{ $salidaTieneUbicacion ? 'cursor: pointer; color:#0d6efd;' : 'text-decoration: underline; cursor: pointer; color:#0d6efd;' }}"
                                            onclick="abrirModalUbicacion(
                                                'salida',
                                                '{{ $row['lat_salida'] }}',
                                                '{{ $row['lng_salida'] }}',
                                                '{{ $row['acc_salida'] }}',
                                                '{{ $row['salida_ubicacion_id'] }}'
                                            )"
                                        >
                                            {{ $textoSalida }}
                                        </span>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-3">
                                    No hay sesiones para esta fecha / filtros.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Overlay del modal --}}
<div id="ubicacion-modal-overlay"
     style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.3); z-index:999;">
</div>

{{-- Modal para crear/asociar ubicación --}}
<div id="ubicacion-modal"
     style="display:none; position:fixed; top:10%; left:50%; transform:translateX(-50%);
            background:#fff; border:1px solid #ccc; padding:20px; z-index:1000; width:400px;">
    <h3>Registrar ubicación / asociar cliente</h3>

    <form method="POST" action="{{ route('ubicaciones.storeFromDiario') }}">
        @csrf

        <input type="hidden" id="modal_tipo" name="tipo">
        <input type="hidden" id="modal_lat" name="lat">
        <input type="hidden" id="modal_lng" name="lng">
        <input type="hidden" id="modal_accuracy" name="accuracy">
        <input type="hidden" id="modal_ubicacion_id" name="ubicacion_id">

        <div style="margin-bottom:0.5rem;">
            <strong>Lat:</strong> <span id="modal_lat_text"></span><br>
            <strong>Lng:</strong> <span id="modal_lng_text"></span><br>
            <strong>Accuracy:</strong> <span id="modal_acc_text"></span> m
        </div>

        <div style="margin-bottom:0.5rem;">
            <label for="modal_nombre">Nombre ubicación</label><br>
            <input type="text" id="modal_nombre" name="nombre" style="width:100%;">
        </div>

        <div style="margin-bottom:0.5rem;">
            <label for="modal_radio_m">Radio (m)</label><br>
            <input type="number" step="0.01" id="modal_radio_m" name="radio_m" style="width:100%;">
        </div>

        <div style="margin-bottom:0.5rem;">
            <label for="modal_cliente_id">Cliente</label><br>
            <select id="modal_cliente_id" name="cliente_id" style="width:100%;">
                <option value="">-- Seleccione cliente --</option>
                @foreach($clientes as $c)
                    <option value="{{ $c->id_cliente }}">{{ $c->empresa }}</option>
                @endforeach
            </select>
        </div>

        <div style="display:flex; justify-content:flex-end; gap:0.5rem;">
            <button type="button" onclick="cerrarModalUbicacion()">Cancelar</button>
            <button type="submit">Guardar</button>
        </div>
    </form>
</div>

<script>
    function abrirModalUbicacion(tipo, lat, lng, accuracy, ubicacionId) {
        document.getElementById('ubicacion-modal-overlay').style.display = 'block';
        document.getElementById('ubicacion-modal').style.display = 'block';

        document.getElementById('modal_tipo').value = tipo;
        document.getElementById('modal_lat').value = lat;
        document.getElementById('modal_lng').value = lng;
        document.getElementById('modal_accuracy').value = accuracy || '';
        document.getElementById('modal_ubicacion_id').value = ubicacionId || '';

        document.getElementById('modal_lat_text').innerText = lat;
        document.getElementById('modal_lng_text').innerText = lng;
        document.getElementById('modal_acc_text').innerText = accuracy || '-';

        // sugerimos radio igual a accuracy si existe
        document.getElementById('modal_radio_m').value = accuracy || 20;
    }

    function cerrarModalUbicacion() {
        document.getElementById('ubicacion-modal-overlay').style.display = 'none';
        document.getElementById('ubicacion-modal').style.display = 'none';
    }
</script>
@endsection
