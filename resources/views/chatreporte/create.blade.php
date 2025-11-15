@extends('layouts.app')

@section('content')
<div class="container">

    {{-- Errores de validación --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Mensaje de éxito --}}
    @if(session('mensaje'))
        <div class="alert alert-success">
            {{ session('mensaje') }}
        </div>
    @endif

    {{-- Título e información del proyecto --}}
    <h1>Chat del proyecto {{ request()->get('trabajo') }} </h1>
    <h2>De: {{ request()->get('empresa') }} </h2>
    <br>

    {{-- Tabla de reportes anteriores --}}
    @if($reportes->isEmpty())
        <p>No hay reportes anteriores para este proyecto.</p>
    @else
        <div class="table-responsive mb-4">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Fecha</th>
                        <th>Inspector</th>
                        <th>Reporte</th>
                        <th>Foto</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reportes as $reporte)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($reporte->fecha)->format('d/m/Y') }}</td>
                            <td>{{ $reporte->usuario }}</td>
                            <td>{{ e($reporte->texto) }}</td>
                            <td>
                                @if($reporte->foto1)
                                    <img src="{{ asset('storage/' . $reporte->foto1) }}" width="100" height="100" class="img-thumbnail">
                                @else
                                    Sin foto
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    {{-- Formulario de nuevo reporte --}}
    <form method="POST" action="{{ url('/chatreporte') }}" enctype="multipart/form-data">
        @csrf

        {{-- Campos ocultos --}}
        <input type="hidden" name="empresa" value="{{ request()->get('empresa') }}">
        <input type="hidden" name="trabajo" value="{{ request()->get('trabajo') }}">
        <input type="hidden" name="id_proyecto" value="{{ old('id_proyecto', $id_proyecto) }}">

        {{-- Fecha --}}
        <div class="form-group mb-3">
            <label for="fecha">Fecha:</label>
            <input type="date" name="fecha" id="fecha" class="form-control @error('fecha') is-invalid @enderror" value="{{ old('fecha', date('Y-m-d')) }}" required>
            @error('fecha')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Texto --}}
        <div class="form-group mb-3">
            <label for="texto">Mensaje o reporte:</label>
            <textarea class="form-control @error('texto') is-invalid @enderror" name="texto" id="texto" rows="5" placeholder="Escribe el reporte o mensaje aquí..." required>{{ old('texto') }}</textarea>
            @error('texto')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Foto --}}
        <div class="form-group mb-3">
            <label for="foto1">Subir foto (opcional):</label>
            <input type="file" class="form-control @error('foto1') is-invalid @enderror" name="foto1" id="foto1" accept="image/*">
            @error('foto1')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Usuario --}}
        <div class="form-group mb-3">
            <label for="usuario">Inspector:</label>
            <input type="text" class="form-control @error('usuario') is-invalid @enderror" name="usuario" id="usuario" value="{{ old('usuario', Auth::check() ? Auth::user()->name : '') }}" required>
            @error('usuario')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Botones --}}
        <button type="submit" class="btn btn-success">Enviar reporte</button>
        <a href="{{ url('/proyecto') }}" class="btn btn-primary">Regresar</a>
    </form>

</div>
@endsection
