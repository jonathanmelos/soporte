@extends('layouts.app')

@section('content')
<div class="container">
    @if(Session::has('mensaje'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ Session::get('mensaje') }}
        </div>
    @endif

    <table class="table table-light">
        <thead class="thead-light">
            <tr>
                <th scope="col">Cliente</th>
                <th scope="col">Trabajo a realizar</th>
                <th scope="col">Solicitador por</th>
                <th scope="col">Responsable</th>
                <th scope="col">Fecha de Entrega</th>
                <th scope="col">Precio</th>
                <th scope="col">Reportes adjuntos</th>
                <th scope="col">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($proyectos as $proyecto)
                <tr>
                    <td>{{ $proyecto->empresa }}</td>
                    <td>{{ $proyecto->requerimientos->trabajo }}</td>
                    <td>{{ $proyecto->autorizado }}</td>
                    <td>{{ $proyecto->responsable }}</td>
                    <td>{{ $proyecto->fecha_entrega }}</td>
                    <td>{{ $proyecto->precio }}</td>
                    <td>{{ $proyecto->chatreportes_count }}</td>
                    <td>
                        <div class="btn-group" role="group" aria-label="Acciones">
                            <!-- Botón Ver -->
                            <a href="{{ url('/proyecto/' . $proyecto->id_proyecto) }}" class="btn btn-info btn-sm">Ver</a>

                            <!-- Botón Agregar Reporte -->
                            <a href="{{ url('/chatreporte/create?id_proyecto=' . $proyecto->id_proyecto . '&empresa=' . urlencode($proyecto->empresa) . '&trabajo=' . urlencode($proyecto->requerimientos->trabajo)) }}" class="btn btn-warning btn-sm">Agregar reporte</a>

                            <!-- Botón Cerrar Proyecto -->
                            @if ($proyecto->chatreportes_count > 0)
                                <a href="{{ route('proyecto.cerrar', $proyecto->id_proyecto) }}" class="btn btn-danger btn-sm" onclick="return confirm('¿Desea cerrar este proyecto?')">Cerrar proyecto</a>
                            @else
                                <span class="btn btn-secondary btn-sm">Agrega un reporte</span>
                            @endif
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Paginación -->
    {!! $proyectos->links() !!}
</div>
@endsection
