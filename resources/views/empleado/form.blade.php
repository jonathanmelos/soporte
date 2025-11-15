  <h1>{{ $modo }} Colaborador</h1>

     @if(Session::has('mensaje'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ Session::get('mensaje') }}
        </div> 
    @endif

<div class="form-group">
<label for="cedula">Cédula:</label>
<input class="form-control" type="text" name="cedula" id="cedula" placeholder="Cédula" value="{{ isset($empleado->cedula) ? $empleado->cedula : '' }}" required><br>
</div>
<div class="form-group">
<label for="nombre">Nombre:</label>
<input class="form-control" type="text" name="nombre" id="nombre" placeholder="Nombre" value="{{ isset($empleado->nombre) ? $empleado->nombre : '' }}" required><br>
</div>
<div class="form-group">
<label for="apellido">Apellido:</label>
<input class="form-control" type="text" name="apellido" id="apellido" placeholder="Apellido" value="{{ isset($empleado->apellido) ? $empleado->apellido : '' }}" required><br>
</div>
<div class="form-group">
<label for="telefono">Teléfono:</label>
<input class="form-control" type="text" name="telefono" id="telefono" placeholder="Teléfono" value="{{ isset($empleado->telefono) ? $empleado->telefono : '' }}"><br>
</div>
<div class="form-group">
<label for="correo">Correo:</label>
<input class="form-control"type="text" name="correo" id="correo" placeholder="Correo" value="{{ isset($empleado->correo) ? $empleado->correo : '' }}"><br>
</div>
<div class="form-group">
<label for="direccion">Dirección:</label>
<input class="form-control" type="text" name="direccion" id="direccion" placeholder="Dirección" value="{{ isset($empleado->direccion) ? $empleado->direccion : '' }}"><br>
</div>
<div class="form-group">
<label for="tipo_sangre">Tipo de Sangre:</label>
<input class="form-control" type="text" name="tipo_sangre" id="tipo_sangre" placeholder="Tipo de Sangre" value="{{ isset($empleado->tipo_sangre) ? $empleado->tipo_sangre : '' }}"><br>
</div>
<div class="form-group">
<label for="contacto_emergencia">Contacto Emergencia:</label>
<input class="form-control" type="text" name="contacto_emergencia" id="contacto_emergencia" placeholder="Contacto Emergencia" value="{{ isset($empleado->contacto_emergencia) ? $empleado->contacto_emergencia : '' }}"><br>
</div>
<div class="form-group">
<label for="especialidad">Especialidad:</label>
<input class="form-control" type="text" name="especialidad" id="especialidad" placeholder="Especialidad" value="{{ isset($empleado->especialidad) ? $empleado->especialidad : '' }}" required><br>
</div>
<div class="form-group">
<label for="departamento">Departamento:</label>
<input class="form-control" type="text" name="departamento" id="departamento" placeholder="Departamento" value="{{ isset($empleado->departamento) ? $empleado->departamento : '' }}" required><br>
</div>
<div class="form-group">
<label for="cargo">Cargo:</label>
<input class="form-control" type="text" name="cargo" id="cargo" placeholder="Cargo" value="{{ isset($empleado->cargo) ? $empleado->cargo : '' }}"><br>
</div>
<div class="form-group">
<label for="foto"> 
    @if(isset($empleado->foto))
        <img class="img-thumbnail img-fluid" src="{{ asset('storage/'.$empleado->foto) }}" alt="" width="100" height="100">
    @else
        <span>No hay foto disponible</span>
    @endif
</label>

<input class="form-control" type="file" name="foto" id="foto" placeholder="Foto" value=""><br>
</div>


<input class="btn btn-success" type="submit" value="{{ $modo }} Datos">
<a input class="btn btn-primary" href="{{ url('empleado/') }}" class="btn btn-success">Regresar</a>


