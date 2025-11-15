<h1>{{ $modo }} Reporte para {{ request()->get('empresa') }} </h1>
<h2>Proyecto: {{request()->get('trabajo') }}</h2> <br>


@if(Session::has('mensaje'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ Session::get('mensaje') }}
    </div> 
@endif

<div class="form-group">
    <!-- Campo oculto para id_proyecto -->
    <input type="hidden" name="id_proyecto" value="{{ old('id_proyecto', $id_proyecto) }}">
</div>

<div class="form-group">
    <label for="tareas">Tareas realizadas:</label>
    <textarea class="form-control" name="tareas" id="tareas" placeholder="Descripción de tareas" required>{{ isset($reporte->tareas) ? $reporte->tareas : '' }}</textarea><br>
</div>

<div class="form-group">
    <label for="tecnicos">Técnicos:</label>
    <textarea class="form-control" name="tecnicos" id="tecnicos" placeholder="Técnicos involucrados" required>{{ isset($reporte->tecnicos) ? $reporte->tecnicos : '' }}</textarea><br>
</div>

<div class="form-group">
    <label for="material">Material:</label>
    <textarea class="form-control" name="material" id="material" placeholder="Material utilizado" required>{{ isset($reporte->material) ? $reporte->material : '' }}</textarea><br>
</div>

<div class="form-group">
    <label for="herramienta">Herramientas:</label>
    <textarea class="form-control" name="herramienta" id="herramienta" placeholder="Herramientas utilizadas" required>{{ isset($reporte->herramienta) ? $reporte->herramienta : '' }}</textarea><br>
</div>

<div class="form-group">
    <label for="novedades">Novedades:</label>
    <textarea class="form-control" name="novedades" id="novedades" placeholder="Novedades o incidencias">{{ isset($reporte->novedades) ? $reporte->novedades : '' }}</textarea><br>
</div>

<div class="form-group">
    <label for="foto1">Foto 1:</label>
    @if(isset($reporte->foto1))
        <img class="img-thumbnail img-fluid" src="{{ asset('storage/'.$reporte->foto1) }}" alt="" width="100" height="100">
    @endif
    <input class="form-control" type="file" name="foto1" id="foto1"><br>
</div>

<div class="form-group">
    <label for="foto2">Foto 2:</label>
    @if(isset($reporte->foto2))
        <img class="img-thumbnail img-fluid" src="{{ asset('storage/'.$reporte->foto2) }}" alt="" width="100" height="100">
    @endif
    <input class="form-control" type="file" name="foto2" id="foto2"><br>
</div>

<div class="form-group">
    <label for="foto3">Foto 3:</label>
    @if(isset($reporte->foto3))
        <img class="img-thumbnail img-fluid" src="{{ asset('storage/'.$reporte->foto3) }}" alt="" width="100" height="100">
    @endif
    <input class="form-control" type="file" name="foto3" id="foto3"><br>
</div>

<input class="btn btn-success" type="submit" value="{{ $modo }} Reporte">
<a class="btn btn-primary" href="{{ url('reporte/') }}">Regresar</a>
