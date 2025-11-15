@extends('layouts.app')

@section('content')
<div class="container">

@if(Session::has('mensaje'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
  {{ Session::get('mensaje') }}
</div> 
@endif

<a href="{{ url('empleado/create') }}" class="btn btn-success">Registrar Colaborador</a>
<BR>
<BR>
<table class="table table-light">
<thead class="thead-light">
    <tr>
         <th scope="col">Foto</th>
        <th scope="col">Cédula</th>
        <th scope="col">Nombre y Apellido</th>
        <th scope="col">Teléfono</th>
        <th scope="col">Correo</th>
        <th scope="col">Dirección</th>
        <th scope="col">Tipo de Sangre</th>
        <th scope="col">Contacto Emergencia</th>
        <th scope="col">Especialista en:</th>
        <th scope="col">Acciones</th>
       
    <tbody>
        @foreach($empleados as $empleado)
        <tr>          
            <td> <img class="img-thumbnail " src="{{ asset('storage').'/'.$empleado->foto }}" alt="" width="100" height="100"></td>
            <td>{{ $empleado->cedula }}</td>
            <td>{{ $empleado->nombre }} {{ $empleado->apellido }}</td>
            <td>{{ $empleado->telefono }}</td>
            <td>{{ $empleado->correo }}</td>
            <td>{{ $empleado->direccion }}</td>
            <td>{{ $empleado->tipo_sangre }}</td>
            <td>{{ $empleado->contacto_emergencia }}</td>
            <td>{{ $empleado->especialidad }}</td>
            
                      <td> 
            <a href="{{ url('/empleado/' . $empleado->id_personal . '/edit') }}" class=" btn btn-warning">EDITAR</a> <br>

            <form action="{{url('/empleado/'.$empleado->id_personal)}}" method="post"  class="d-inline">
                {{-- <input type="submit" value="Eliminar"> --}}
                @csrf
                {{method_field('DELETE')}}
                <input type="submit" class="btn btn-danger" onclick="return confirm('¿Desea eliminar este empleado?')" value="Eliminar">
            <td>
            
    


        </tr>
        @endforeach
    </tbody>
</table>
{!! $empleados->links() !!}
</div>
@endsection