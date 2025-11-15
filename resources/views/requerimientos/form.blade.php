<h1>{{ $modo }} Requerimiento</h1>

@if(Session::has('mensaje'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ Session::get('mensaje') }}
    </div>
@endif

@csrf

<input type="hidden" name="id_rubro[]" value="{{ $rubro->id_rubro_requerimiento ?? '' }}">

<div class="form-group">
    <table class="table table-responsive">
        <tr>
            <td><label for="cliente">Nombre Cliente:</label></td>
            <td>
                <input 
                    type="text" 
                    class="form-control" 
                    name="cliente_nombre" 
                    id="cliente_nombre" 
                    placeholder="Escribe el nombre del cliente" 
                    value="{{ old('cliente_nombre', optional($requerimiento->cliente)->empresa) }}" 
                    autocomplete="off">
                <input type="hidden" name="id_cliente" id="id_cliente" value="{{ old('id_cliente', optional($requerimiento->cliente)->id_cliente) }}">
            </td>
            <td><label for="contacto">Solicitado por:</label></td>
            <td><input class="form-control" type="text" name="contacto" id="contacto" placeholder="Contacto" value="{{ $requerimiento->contacto ?? '' }}"></td>
        </tr>
        <tr>
            <td><label for="creado">Creado por:</label></td>
            <td><input class="form-control" type="text" name="creado" id="creado" placeholder="Creado" value="{{ $requerimiento->creado ?? Auth::user()->name }}" required readonly></td>
            <td><label for="pago">Forma de Pago:</label></td>
            <td>
                <select class="form-control" name="pago" id="pago">
                    <option value="cotizacion" {{ isset($requerimiento->pago) && $requerimiento->pago == 'cotizacion' ? 'selected' : '' }}>Cotización</option>
                    <option value="lista" {{ isset($requerimiento->pago) && $requerimiento->pago == 'lista' ? 'selected' : '' }}>Lista</option>
                </select>
            </td>
        </tr>
        <tr>
            <td><label for="proridad">Prioridad:</label></td>
            <td>
                <select class="form-control" name="proridad" id="proridad">
                    <option value="alta" {{ isset($requerimiento->proridad) && $requerimiento->proridad == 'alta' ? 'selected' : '' }}>Alta</option>
                    <option value="media" {{ isset($requerimiento->proridad) && $requerimiento->proridad == 'media' ? 'selected' : '' }}>Media</option>
                    <option value="baja" {{ isset($requerimiento->proridad) && $requerimiento->proridad == 'baja' ? 'selected' : '' }}>Baja</option>
                </select>
            </td>
            <td><label for="ejecucion">Ejecución:</label></td>
            <td>
                <select class="form-control" name="ejecucion" id="ejecucion">
                    <option value="corto" {{ isset($requerimiento->ejecucion) && $requerimiento->ejecucion == 'corto' ? 'selected' : '' }}>Corto plazo</option>
                    <option value="mediano" {{ isset($requerimiento->ejecucion) && $requerimiento->ejecucion == 'mediano' ? 'selected' : '' }}>Mediano plazo</option>
                    <option value="largo" {{ isset($requerimiento->ejecucion) && $requerimiento->ejecucion == 'largo' ? 'selected' : '' }}>Largo plazo</option>
                </select>
            </td>
        </tr>
        <tr>
            <td><label for="trabajo">Trabajo a realizar:</label></td>
            <td colspan="3"><textarea class="form-control" name="trabajo" id="trabajo" placeholder="Trabajo" required>{{ $requerimiento->trabajo ?? '' }}</textarea></td>
        </tr>
    </table>
</div>

<div id="rubros-container">
    <h3>Rubros del Requerimiento</h3>
    <div class="table-responsive">
        <table class="table table-bordered" id="rubros-table">
            <thead class="table-light">
                <tr>
                    <th style="width: 25%;">Rubro</th>
                    <th style="width: 15%;">Unidad</th>
                    <th style="width: 10%;">Cantidad</th>
                    <th style="width: 25%;">Nota</th>
                    <th style="width: 20%;">Archivo</th>
                    @if($modo === 'Crear')
                             <th style="width: 5%;">Acción</th>
                        @endif

                   
                </tr>
            </thead>
            <tbody>
                <!-- Se gestionan las filas iniciales según la variable $modo -->
                @if($modo == 'Editar' && isset($requerimiento->rubros) && count($requerimiento->rubros))
                    @foreach($requerimiento->rubros as $index => $rubro)
                        <tr class="rubro-row">
                            <td><input type="text" class="form-control" name="nombre_rubro[]" value="{{ $rubro->nombre_rubro }}" placeholder="Nombre del rubro"></td>
                            <td>
                                <select class="form-control" name="unidad[]">
                                    @foreach(['m²', 'm³', 'm', 'kg', 'mm', 'cm', 'unidad', 'hora', 'jornada', 'viaje', 'kit', 'otros'] as $unidad)
                                        <option value="{{ $unidad }}" {{ $rubro->unidad == $unidad ? 'selected' : '' }}>{{ $unidad }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="number" class="form-control" name="cantidad[]" value="{{ $rubro->cantidad }}" step="any"></td>
                            <td><textarea class="form-control" name="nota[]">{{ $rubro->nota }}</textarea></td>
                            <td>
                                @if($rubro->archivo)
                                    <a href="{{ asset('storage/' . $rubro->archivo) }}" target="_blank">Ver archivo</a><br>
                                @endif
                                <input type="file" class="form-control" name="archivo[]">
                            </td>
                            <td>{!! $modo === 'Crear' ? '<button type="button" class="btn btn-danger btn-sm eliminar-rubro">🗑</button>' : '' !!}</td>
                        </tr>
                    @endforeach
                @else
                    <tr class="rubro-row">
                        <td><input type="text" class="form-control" name="nombre_rubro[]" placeholder="Nombre del rubro"></td>
                        <td>
                            <select class="form-control" name="unidad[]">
                                @foreach(['m²', 'm³', 'm', 'kg', 'mm', 'cm', 'unidad', 'hora', 'jornada', 'viaje', 'kit', 'otros'] as $unidad)
                                    <option value="{{ $unidad }}">{{ $unidad }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td><input type="number" class="form-control" name="cantidad[]" placeholder="Cantidad" step="any"></td>
                        <td><textarea class="form-control" name="nota[]" placeholder="Notas adicionales"></textarea></td>
                        <td><input type="file" class="form-control" name="archivo[]"></td>
                       <td>{!! $modo === 'Crear' ? '<button type="button" class="btn btn-danger btn-sm eliminar-rubro">🗑</button>' : '' !!}</td>

                        
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
    @if ($modo === 'Crear')
    <button type="button" class="btn btn-primary" id="add-rubro">Agregar otro rubro</button>
@endif
    
</div>

<br>
<input class="btn btn-success" type="submit" value="{{ $modo }} Requerimiento">
<a class="btn btn-primary" href="{{ url('requerimientos/') }}">Regresar</a>

<!-- Enlaces a jQuery y jQuery UI -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

<script>
$(document).ready(function () {
    // Autocompletar cliente
    $('#cliente_nombre').autocomplete({
        source: function (request, response) {
            $.ajax({
                url: '{{ route("clientes.autocompletar") }}',
                data: { q: request.term },
                success: function (data) {
                    response(data.map(cliente => ({
                        label: cliente.empresa,
                        value: cliente.empresa,
                        id: cliente.id_cliente
                    })));
                }
            });
        },
        minLength: 1,
        select: function (event, ui) {
            $('#id_cliente').val(ui.item.id);
        },
        change: function (event, ui) {
            if (!ui.item) {
                $('#id_cliente').val('');
            }
        }
    });

    // Agregar nueva fila
    $('#add-rubro').click(function() {
        const newRow = `
        <tr class="rubro-row">
            <td><input type="text" class="form-control" name="nombre_rubro[]" placeholder="Nombre del rubro"></td>
            <td>
                <select class="form-control" name="unidad[]">
                    <option value="m²">m²</option>
                    <option value="m³">m³</option>
                    <option value="m">m</option>
                    <option value="kg">kg</option>
                    <option value="mm">mm</option>
                    <option value="cm">cm</option>
                    <option value="unidad">unidad</option>
                    <option value="hora">hora</option>
                    <option value="jornada">jornada</option>
                    <option value="viaje">viaje</option>
                    <option value="kit">kit</option>
                    <option value="otros">otros</option>
                </select>
            </td>
            <td><input type="number" class="form-control" name="cantidad[]" placeholder="Cantidad" step="any"></td>
            <td><textarea class="form-control" name="nota[]" placeholder="Notas adicionales"></textarea></td>
            <td><input type="file" class="form-control" name="archivo[]"></td>
            <td><button type="button" class="btn btn-danger btn-sm eliminar-rubro">🗑</button></td>
        </tr>`;
        $('#rubros-table tbody').append(newRow);
    });

    // Eliminar fila
    $(document).on('click', '.eliminar-rubro', function() {
        $(this).closest('tr').remove();
    });
});
</script>
