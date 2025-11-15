<!-- resources/views/proyecto/show.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h2> Proyecto de: {{ $proyecto->empresa }}  tarea: {{ $proyecto->requerimientos->trabajo }}   </h2>
     <div class="card">
        <div class="card-header">
            Proyecto: {{ $proyecto->id_proyecto }} / {{ $proyecto->fecha_creacion }} / {{ $proyecto->id_requerimiento }}
        </div>
        <div class="card-body">
            <p><strong>Solicitado por:</strong> {{ $proyecto->autorizado }}</p>
            <p><strong>Responsable:</strong> {{ $proyecto->responsable }}</p>
            <p><strong>Fecha de Entrega:</strong> {{ $proyecto->fecha_entrega }}</p>
                      <p><strong>Precio:</strong> {{ $proyecto->precio }}</p>
            <p><strong>Estado:</strong> {{ $proyecto->estado }}</p>
            
           <!-- Enlace para descargar el documento -->
           <p><strong>Cotización:</strong> 
            @if (Storage::exists('public/'.$proyecto->documento)) 
                <a href="{{ asset('storage/'.$proyecto->documento) }}" class="btn btn-success" download>Descargar Documento</a>
            @else
                <span>No hay documento disponible</span>
            @endif
        </p>
        
        <!-- Sección de Reportes adjuntos -->
        <p><strong>Reportes adjuntos:</strong></p>
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
    </div>
</div>

<div class="mt-3">
    <!-- Botones para acciones adicionales -
    <a href="{{ url('/proyecto/'.$proyecto->id_proyecto.'/edit') }}" class="btn btn-warning">Editar Proyecto</a>
    <a href="{{ url('../chatreporte/create?id_proyecto=' . $proyecto->id_proyecto) }}" class="btn btn-primary">Agregar reporte</a>-->
    <a href="{{ url('/proyecto') }}" class="btn btn-secondary">Volver a Lista Proyectos</a>
</div>
</div>
@endsection