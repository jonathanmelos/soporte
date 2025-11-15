<h1>{{ $modo }} Proyecto de  
    @if(isset($empresa))
        {{ $empresa }}
    @endif
</h1>

@if(Session::has('mensaje'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ Session::get('mensaje') }}
    </div>
@endif

@php
    $usuario = Auth::user(); // Asegúrate de que Auth está funcionando y devuelve el usuario logueado
@endphp

<!-- Campo oculto para empresa -->
@if(isset($empresa))
    <input type="hidden" name="empresa" value="{{ $empresa }}">
@endif

<!-- Campo oculto para id_requerimiento -->
<input type="hidden" name="id_requerimiento" 
       value="{{ isset($proyecto->id_requerimiento) ? $proyecto->id_requerimiento : ($id_requerimiento ?? '') }}">

<div class="form-group">
    <label for="contacto">Contacto:</label>
    <input class="form-control" type="text" name="autorizado" id="autorizado"
        value="{{ isset($proyecto->contacto) ? $proyecto->autorizado : ($contacto ?? '') }}">
</div>

<div class="form-group">
    <label for="responsable">Responsable:</label>
    <input class="form-control" type="text" name="responsable" id="responsable"
           value="{{ isset($proyecto->responsable) ? $proyecto->responsable : $usuario->name }}">
</div>

<div class="form-group">
    <label for="fecha_entrega">Fecha de Entrega:</label>
    <input class="form-control" type="date" name="fecha_entrega" id="fecha_entrega" value="{{ isset($proyecto->fecha_entrega) ? $proyecto->fecha_entrega : '' }}">
</div>

<div class="form-group">
    <label for="precio">Precio:</label>
    <input class="form-control" type="number" step="0.01" name="precio" id="precio" value="{{ isset($proyecto->precio) ? $proyecto->precio : '' }}">
</div>

<div class="form-group">
    <label for="documento">Carga la Cotización:</label><br>
    @if(isset($proyecto->documento))
        <a href="{{ asset('storage/'.$proyecto->documento) }}" target="_blank">Ver documento actual</a><br>
    @endif
    <input class="form-control" type="file" name="documento" id="documento">
</div>

<br>

<input type="hidden" name="estado" value="pendiente">

<input class="btn btn-success" type="submit" value="{{ $modo }} Proyecto">
<a class="btn btn-primary" href="{{ url('proyecto/') }}">Regresar</a>

