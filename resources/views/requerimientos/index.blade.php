@extends('layouts.app')

@section('content')
<div class="container">

    @if(Session::has('mensaje'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ Session::get('mensaje') }}
        </div> 
    @endif

    <!-- Botón para nuevo requerimiento -->
    <a href="{{ url('requerimientos/create') }}" class="btn btn-success mb-3">Nuevo Requerimiento</a>

    <table class="table table-light">
        <thead class="thead-light">
            <tr>
                <th scope="col">Cliente</th>
                <th scope="col">Fecha</th>
                <th scope="col">Solicitado por</th>
                <th scope="col">Creado por</th>
                <th scope="col">Trabajo</th>
                <th scope="col">Prioridad</th>
                <th scope="col">Plazo de Ejecución</th>
                <th scope="col">Forma de Contrato</th>
                <th scope="col">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($requerimientos as $req)
                <tr>
                    <td>{{ $req->cliente->empresa ?? 'Sin cliente' }}</td>
                    <td>{{ $req->fecha }}</td>
                    <td>{{ $req->contacto }}</td>
                    <td>{{ $req->creado }}</td>
                    <td>{{ $req->trabajo }}</td>
                    <td>{{ ucfirst($req->proridad) }}</td>
                    <td>{{ ucfirst($req->ejecucion) }}</td>
                    <td>{{ ucfirst($req->pago) }}</td>
                    <td>
                        <div class="btn-group" role="group" aria-label="Acciones">
                            <!-- Editar -->
                            <a href="{{ url('/requerimientos/' . $req->id_requerimientos . '/edit') }}" class="btn btn-warning btn-sm">Editar</a>

                            <!-- Aprobar -->
                            <form action="{{ url('/requerimientos/actualizar/' . $req->id_requerimientos) }}" method="post" class="d-inline">
                                @csrf
                                {{ method_field('PATCH') }}
                                <button type="submit" class="btn btn-primary btn-sm" onclick="return confirm('¿Desea aprobar este requerimiento?')">Aprobar</button>
                            </form>

                            <!-- Descartar -->
                            <a href="{{ url('/requerimientos/' . $req->id_requerimientos . '/descartar') }}" class="btn btn-danger btn-sm">Descartar</a>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Paginación -->
    {!! $requerimientos->links() !!}
</div>
@endsection
