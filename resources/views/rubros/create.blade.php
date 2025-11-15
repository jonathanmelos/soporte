@extends('layouts.app')

@section('content')
<div class="container">

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('mensaje'))
        <div class="alert alert-success">
            {{ session('mensaje') }}
        </div>
    @endif

    <h2>Crear Nuevo Rubro</h2>

    <form method="POST" action="{{ route('rubros.store') }}">
        @csrf

        <!-- Campo Nombre -->
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre del Rubro</label>
            <input 
                type="text" 
                class="form-control @error('nombre') is-invalid @enderror" 
                id="nombre" 
                name="nombre" 
                value="{{ old('nombre') }}" 
                required
            >
            @error('nombre')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Campo Unidad de Medida -->
<div class="mb-3">
    <label for="unidad_medida" class="form-label">Unidad de Medida</label>
    <select 
        class="form-select @error('unidad_medida') is-invalid @enderror" 
        id="unidad_medida" 
        name="unidad_medida" 
        required
    >
        <option value="" disabled {{ old('unidad_medida') ? '' : 'selected' }}>Seleccione una unidad</option>
        @php
            $unidades = ['m','m²', 'm³',  'kg', 'mm', 'cm', 'unidad', 'punto', 'hora', 'jornada', 'viaje', 'kit', 'otros'];
        @endphp
        @foreach ($unidades as $unidad)
            <option value="{{ $unidad }}" {{ old('unidad_medida') == $unidad ? 'selected' : '' }}>
                {{ $unidad }}
            </option>
        @endforeach
    </select>
    @error('unidad_medida')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>


        <!-- Campo Categoría -->
        <!-- Campo Categoría con lista desplegable -->
<div class="mb-3">
    <label for="categoria" class="form-label">Categoría</label>
    <select 
        class="form-select @error('categoria') is-invalid @enderror" 
        id="categoria" 
        name="categoria" 
        required>
        <option value="" disabled selected>Selecciona una categoría</option>
        <option value="Preliminares">Preliminares</option>
        <option value="Estructura de Concreto">Estructura de Concreto</option>
        <option value="Estructura Metálica">Estructura Metálica</option>
        <option value="Estructura de Madera">Estructura de Madera</option>
        <option value="Mampostería y Muros">Mampostería y Muros</option>
        <option value="Instalaciones eléctricas y comunicación">Instalaciones eléctricas y comunicación</option>
        <option value="Instalaciones hidrosanitarias">Instalaciones hidrosanitarias</option>
        <option value="Instalaciones especiales">Instalaciones especiales</option>
        <option value="Pintura y revestimientos">Pintura y revestimientos</option>
        <option value="Carpintería y herrería">Carpintería y herrería</option>
        <option value="Cubiertas y techos">Cubiertas y techos</option>
        <option value="Pisos y pavimentos">Pisos y pavimentos</option>
        <option value="Equipamiento y mobiliario">Equipamiento y mobiliario</option>
        <option value="Servicios complementarios">Servicios complementarios</option>
    </select>
    @error('categoria')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>


        <!-- Campo oculto para el nombre del usuario -->
        <input type="hidden" name="creado" value="{{ Auth::user()->name }}">

        <button type="submit" class="btn btn-primary">Guardar Rubro</button>
    </form>

</div>
@endsection
