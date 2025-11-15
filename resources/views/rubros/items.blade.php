@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Detalle del Rubro</h2>

    <div class="card mb-4">
        <div class="card-body">
            <h4 class="card-title">{{ $rubro->nombre }}</h4>
            <p class="card-text"><strong>Categoría:</strong> {{ $rubro->categoria }}</p>
            <p class="card-text"><strong>Unidad de Medida:</strong> {{ $rubro->unidad_medida }}</p>
        </div>
    </div>

    <div class="row">
        <!-- Materiales -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">1. Materiales</div>
                <div class="card-body">
                    <!-- Aquí irán los materiales del rubro -->
                    <p>No hay materiales añadidos aún.</p>
                </div>
            </div>
        </div>

        <!-- Mano de Obra -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-success text-white">2. Mano de Obra</div>
                <div class="card-body">
                    <!-- Aquí irá la mano de obra del rubro -->
                    <p>No hay registros de mano de obra aún.</p>
                </div>
            </div>
        </div>

        <!-- Herramientas -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-warning text-dark">3. Herramientas</div>
                <div class="card-body">
                    <!-- Aquí irán las herramientas del rubro -->
                    <p>No hay herramientas añadidas aún.</p>
                </div>
            </div>
        </div>

        <!-- Gastos Indirectos -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-danger text-white">4. Gastos Indirectos</div>
                <div class="card-body">
                    <!-- Aquí irán los gastos indirectos -->
                    <p>No hay gastos indirectos añadidos aún.</p>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
