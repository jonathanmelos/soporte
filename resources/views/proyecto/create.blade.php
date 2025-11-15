@extends('layouts.app')

@section('content')
<div class="container">

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if(session('mensaje'))
    <div class="alert alert-success">
        {{ session('mensaje') }}
    </div>
@endif


<form method="post" action="{{url('/proyecto')}}" enctype="multipart/form-data">
    @csrf
    @include('proyecto.form', [
        'modo' => 'Crear',
        'id_requerimiento' => $id_requerimiento ?? null,
        'contacto' => $contacto ?? null
    ])
</form>


</div>
@endsection