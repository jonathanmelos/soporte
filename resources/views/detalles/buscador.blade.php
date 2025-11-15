@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h4 class="mb-3">Buscar productos de facturas</h4>

    <input type="text" id="buscador" class="form-control" placeholder="Escribe parte de la descripción...">

    <ul class="list-group mt-2" id="sugerencias" style="display: none;"></ul>

    <div class="card mt-4" id="resultado" style="display: none;">
        <div class="card-body">
            <h5 class="card-title">Resultado:</h5>
            <p><strong>Código:</strong> <span id="codigo"></span></p>
            <p><strong>Descripción:</strong> <span id="descripcion"></span></p>
            <p><strong>Precio Unitario:</strong> $<span id="precio"></span></p>
            <p><strong>Nombre Comercial del Emisor:</strong> <span id="emisor"></span></p>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const input = document.getElementById('buscador');
    const lista = document.getElementById('sugerencias');
    const resultado = document.getElementById('resultado');

    input.addEventListener('input', function () {
        const query = this.value.trim();

        if (query.length < 2) {
            lista.innerHTML = '';
            lista.style.display = 'none';
            resultado.style.display = 'none';
            return;
        }
        fetch(`{{ url('/buscar-detalle') }}?q=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
                lista.innerHTML = '';

                if (data.length === 0) {
                    lista.style.display = 'none';
                    return;
                }

                data.forEach(item => {
                    const li = document.createElement('li');
                    li.className = 'list-group-item list-group-item-action';
                    li.textContent = item.descripcion;

                    li.addEventListener('click', () => {
                        document.getElementById('buscador').value = item.descripcion;
                        document.getElementById('codigo').textContent = item.codigo;
                        document.getElementById('descripcion').textContent = item.descripcion;
                        document.getElementById('precio').textContent = item.precio_unitario;
                        document.getElementById('emisor').textContent = item.emisor;

                        resultado.style.display = 'block';
                        lista.innerHTML = '';
                        lista.style.display = 'none';
                    });

                    lista.appendChild(li);
                });

                lista.style.display = 'block';
            });
    });
});
</script>
@endsection

