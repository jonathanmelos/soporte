@extends('layouts.app')

@section('content')
   <div class="container mt-5">
    <h2 class="mb-4">Subir archivo XML de factura</h2>

    @if(session('info'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    @endif

    <form action="{{ route('facturas.upload') }}" method="POST" enctype="multipart/form-data" class="card p-4 shadow-sm">
        @csrf
        <div class="mb-3">
            <label for="xml_file" class="form-label">Archivo XML:</label>
            <input type="file" name="xml_file" id="xml_file" class="form-control" required accept=".xml">
        </div>
        <button type="submit" class="btn btn-primary">Cargar</button>
    </form>
</div>
<hr class="my-5">
<div class="card p-4 shadow-sm mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
    <a href="{{ route('facturas.upload.form', ['mes' => $mesAnterior['mes'], 'año' => $mesAnterior['año']]) }}" class="btn btn-outline-secondary">
        ←
    </a>

    <h4 class="mb-0 text-center flex-grow-1">{{ ucfirst($nombreMes) }} {{ $año }}</h4>

    <a href="{{ route('facturas.upload.form', ['mes' => $mesSiguiente['mes'], 'año' => $mesSiguiente['año']]) }}" class="btn btn-outline-secondary">
        →
    </a>
</div>



    @if($facturasDelMes->isEmpty())
        <p class="text-muted text-center">No se han cargado facturas este mes.</p>
    @else
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle text-center">
                <thead class="table-light">
                    <tr>
                        <th>Fecha de Autorización</th>
                        <th>Nombre Comercial Emisor</th>
                        <th>Importe Total</th>
                        <th>Ver</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($facturasDelMes as $factura)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($factura->fecha_autorizacion)->format('d/m/Y H:i') }}</td>
                            <td>{{ $factura->nombre_comercial_emisor }}</td>
                            <td>${{ number_format($factura->importe_total, 2) }}</td>
                            <td>
                                <a href="{{ route('facturas.show', $factura->id) }}" class="btn btn-sm btn-outline-primary">
                                    Ver
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>


@endsection
