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


 <form method="POST" action="{{ route('requerimientos.store') }}">

    @csrf
    @include('requerimientos.form', ['modo' => 'Crear'])

</form>

</div>
@endsection
    