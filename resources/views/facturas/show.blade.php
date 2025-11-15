@extends('layouts.app')

@section('content')
<div class="container my-5">
    <h2 class="mb-4">Factura Electrónica</h2>

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

    <div class="card mb-4">
        <div class="card-header fw-bold">Datos del Emisor</div>
        <div class="card-body">
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>RUC:</strong> <span class="copy">{{ $factura->ruc_emisor }}</span></li>
                <li class="list-group-item"><strong>Razón Social:</strong> <span class="copy">{{ $factura->razon_social_emisor }}</span></li>
                <li class="list-group-item"><strong>Nombre Comercial:</strong> <span class="copy">{{ $factura->nombre_comercial_emisor }}</span></li>
            </ul>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header fw-bold">Datos del Comprador</div>
        <div class="card-body">
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>RUC/CI:</strong> <span class="copy">{{ $factura->ruc_comprador }}</span></li>
                <li class="list-group-item"><strong>Nombre:</strong> <span class="copy">{{ $factura->razon_social_comprador }}</span></li>
            </ul>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header fw-bold">Resumen de Factura</div>
        <div class="card-body">
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>Clave de Acceso:</strong> <span class="copy">{{ $factura->clave_acceso }}</span></li>
                <li class="list-group-item"><strong>Nro. Autorización:</strong> <span class="copy">{{ $factura->numero_autorizacion }}</span></li>
                <li class="list-group-item"><strong>Fecha Emisión:</strong> {{ \Carbon\Carbon::parse($factura->fecha_emision)->format('d/m/Y') }}</li>
                <li class="list-group-item"><strong>Fecha Autorización:</strong> {{ \Carbon\Carbon::parse($factura->fecha_autorizacion)->format('d/m/Y H:i:s') }}</li>
                <li class="list-group-item"><strong>Total Sin Impuestos:</strong> ${{ number_format($factura->total_sin_impuestos, 2) }}</li>
                <li class="list-group-item"><strong>Descuento:</strong> ${{ number_format($factura->total_descuento, 2) }}</li>
                <li class="list-group-item"><strong>Importe Total:</strong> ${{ number_format($factura->importe_total, 2) }}</li>
            </ul>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header fw-bold">Detalles</div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th >Código</th>
                        <th>Descripción</th>
                        <th class="text-end">Cantidad</th>
                        <th class="text-end">P. Unitario</th>
                        <th class="text-end">Descuento</th>
                        <th class="text-end">Subtotal</th>
                        <th class="text-end">IVA</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($factura->detalles as $detalle)
                        <tr>
                            <td class="text-end">{{ $detalle->codigo }}</td>
                            <td class="copy">{{ $detalle->descripcion }}</td>
                            <td class="text-end">{{ number_format($detalle->cantidad, 2) }}</td>
                            <td class="text-end">${{ number_format($detalle->precio_unitario, 4) }}</td>
                            <td class="text-end">${{ number_format($detalle->descuento, 2) }}</td>
                            <td class="text-end">${{ number_format($detalle->precio_total_sin_impuesto, 2) }}</td>
                            <td class="text-end">${{ number_format($detalle->impuesto_valor, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <a href="{{ route('facturas.upload.form') }}" class="btn btn-outline-primary">← Cargar otra factura</a>
</div>

<style>
    .copy {
        cursor: pointer;
        background-color: #f9f9f9;
        padding: 2px 4px;
        display: inline-block;
        border-radius: 4px;
    }
    .copy:hover {
        background-color: #e0e0e0;
    }
</style>

<script>
    document.querySelectorAll('.copy').forEach(el => {
        el.addEventListener('click', () => {
            const text = el.innerText;
            navigator.clipboard.writeText(text).then(() => {
                el.style.backgroundColor = '#d4edda';
                setTimeout(() => {
                    el.style.backgroundColor = '#f9f9f9';
                }, 500);
            });
        });
    });
</script>
@endsection
