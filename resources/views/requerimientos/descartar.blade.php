@extends('layouts.app')

@section('content')
<style>
    /* Aumentar el tamaño del radio button y del texto */
    .form-check-input {
        width: 30px;
        height: 30px;
        margin-top: 5px;
        margin-right: 10px;
    }

    .form-check-label {
        font-size: 18px;
        cursor: pointer;
        font-weight: bold;
        padding-left: 10px;
    }

    .form-check-label:hover {
        color: #007bff; /* Cambia el color cuando el mouse pasa por encima */
    }

    .form-check {
        margin-bottom: 15px;
        font-family: Arial, sans-serif;
    }
</style>
<div class="container">
    <h1>Descartar Requerimiento</h1>
    <table class="table table-bordered table-striped">
        <tbody>
            <tr>
                <td>Cliente</td>
                <td>{{ $requerimiento->id_cliente }}</td>
            </tr>
            <tr>
                <td>Solicitado por</td>
                <td>{{ $requerimiento->contacto ?? '' }}</td>
            </tr>
            <tr>
                <td>Creado por</td>
                <td>{{ $requerimiento->creado ?? '' }}</td>
            </tr>
            <tr>
                <td>Trabajo a realizar</td>
                <td>{{ $requerimiento->trabajo ?? '' }}</td>
            </tr>
            <tr>
                <td>Prioridad</td>
                <td>{{ $requerimiento->proridad ?? '' }}</td>
            </tr>
            <tr>
                <td>Plazo de ejecución</td>
                <td>{{ $requerimiento->ejecucion ?? '' }}</td>
            </tr>
            <tr>
                <td>Forma de pago</td>
                <td>{{ $requerimiento->pago ?? '' }}</td>
            </tr>
        </tbody>
    </table>

    <form method="post" action="{{ url('/requerimientos/'.$requerimiento->id_requerimientos) }}" enctype="multipart/form-data">
        @csrf
        @method('PATCH')

        @if(Session::has('mensaje'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ Session::get('mensaje') }}
            </div>
        @endif
<br>
        <h3>Seleccione el motivo:</h3>
        
        <div class="form-check form-check-lg">
            <input class="form-check-input" type="radio" name="comentarios" id="comentarios" value="fuera_presupuesto" required>
            <label class="form-check-label" for="fuera_presupuesto">
                Fuera de presupuesto
            </label>
        </div>

        <div class="form-check form-check-lg">
            <input class="form-check-input" type="radio" name="comentarios" id="comentarios" value="cambio_prioridades" required>
            <label class="form-check-label" for="cambio_prioridades">
                Cambio de prioridades del cliente
            </label>
        </div>

        <div class="form-check form-check-lg">
            <input class="form-check-input" type="radio" name="comentarios" id="comentarios" value="no_respuesta_cliente" required>
            <label class="form-check-label" for="no_respuesta_cliente">
                El cliente no da respuesta
            </label>
        </div>

        <div class="form-check form-check-lg">
            <input class="form-check-input" type="radio" name="comentarios" id="comentarios" value="no_realizamos_trabajo" required>
            <label class="form-check-label" for="no_realizamos_trabajo">
                No realizamos ese trabajo
            </label>
        </div>

        <div class="form-check form-check-lg">
            <input class="form-check-input" type="radio" name="comentarios" id="comentarios" value="problemas_tecnicos" required>
            <label class="form-check-label" for="problemas_tecnicos">
                Problemas técnicos
            </label>
        </div>

        <div class="form-check form-check-lg">
            <input class="form-check-input" type="radio" name="comentarios" id="comentarios" value="plazos_poco_realistas" required>
            <label class="form-check-label" for="plazos_poco_realistas">
                Plazos poco realistas
            </label>
        </div>

        <div class="form-check form-check-lg">
            <input class="form-check-input" type="radio" name="comentarios" id="comentarios" value="poca_comunicacion" required>
            <label class="form-check-label" for="poca_comunicacion">
                Poca comunicación con el cliente
            </label>
        </div>

        <div class="form-check form-check-lg">
            <input class="form-check-input" type="radio" name="comentarios" id="otros" value="otros" required>
            <label class="form-check-label" for="otros">
                Otros
            </label>
        </div>
<br>
        <div class="form-group">
            <h3 for="observaciones"> Observaciones:</h3>
            <textarea class="form-control" name="observaciones" id="observaciones" placeholder="Observaciones">{{ $requerimiento->observaciones ?? '' }}</textarea><br>
        </div>
        <input type="hidden" name="estado" value="no aprobado">
        <input class="btn btn-success" type="submit" value="Descartar Requerimiento">
        <a class="btn btn-primary" href="{{ url('requerimientos/') }}">Regresar</a>
    </form>
</div>
@endsection
