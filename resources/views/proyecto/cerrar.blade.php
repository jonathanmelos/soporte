<!-- proyecto/cerrar.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Cierre exitoso de Proyecto</h1>
   <table class="table table-bordered table-striped">
    <tbody>
        <tr>
            <td>ID del Proyecto</td>
            <td>{{ $proyecto->id_proyecto }}</td>
        </tr>
        <tr>
            <td>Autorizado</td>
            <td>{{ $proyecto->autorizado ?? '' }}</td>
        </tr>
        <tr>
            <td>Fecha de Creación</td>
            <td>{{ $proyecto->fecha_creacion }}</td>
        </tr>
        <tr>
            <td>Fecha de Entrega</td>
            <td>{{ $proyecto->fecha_entrega }}</td>
        </tr>
        <tr>
            <td>Fecha Actual</td>
            <td>{{ \Carbon\Carbon::now()->format('Y-m-d') }}</td>
        </tr>
        <tr>
            <td>Precio</td>
            <td>{{ $proyecto->precio }}</td>
        </tr>
    </tbody>
</table>

<h2>Califica tu experiencia</h2>
<!-- Formulario para cerrar el proyecto con valoraciones -->
<form method="POST" action="{{ route('proyecto.actualizarCierre', $proyecto->id_proyecto) }}">
 @csrf
    @method('PUT')
 <!-- Hidden: Fecha de Finalización y Estado -->
    <input type="hidden" name="fecha_finalizacion" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
    <input type="hidden" name="estado" value="finalizado">
    <!-- Provisión de Material -->
    <div class="form-group">
        <label class="font-weight-bold d-block">Provisión de Material <span class="text-danger">*</span></label>
        @for ($i = 1; $i <= 5; $i++)
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="val_material" id="val_material_{{ $i }}" value="{{ $i }}" required>
                <label class="form-check-label" for="val_material_{{ $i }}">{{ $i }}</label>
            </div>
        @endfor
    </div>
<br>
    <!-- Trabajo en Equipo -->
    <div class="form-group">
        <label class="font-weight-bold d-block">Trabajo en Equipo <span class="text-danger">*</span></label>
        @for ($i = 1; $i <= 5; $i++)
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="val_equipo" id="val_equipo_{{ $i }}" value="{{ $i }}" required>
                <label class="form-check-label" for="val_equipo_{{ $i }}">{{ $i }}</label>
            </div>
        @endfor
    </div>

   <br> <!-- Comunicación con el Cliente -->
    <div class="form-group">
        <label class="font-weight-bold d-block">Comunicación con el Cliente <span class="text-danger">*</span></label>
        @for ($i = 1; $i <= 5; $i++)
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="val_cliente" id="val_cliente_{{ $i }}" value="{{ $i }}" required>
                <label class="form-check-label" for="val_cliente_{{ $i }}">{{ $i }}</label>
            </div>
        @endfor
    </div>

  <br>  <!-- Planificación e Imprevistos -->
    <div class="form-group">
        <label class="font-weight-bold d-block">Planificación e Imprevistos <span class="text-danger">*</span></label>
        @for ($i = 1; $i <= 5; $i++)
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="val_planificacion" id="val_planificacion_{{ $i }}" value="{{ $i }}" required>
                <label class="form-check-label" for="val_planificacion_{{ $i }}">{{ $i }}</label>
            </div>
        @endfor
    </div>
<br>
    <button type="submit" class="btn btn-danger">Cerrar Proyecto</button>
</form>


</div>
@endsection
