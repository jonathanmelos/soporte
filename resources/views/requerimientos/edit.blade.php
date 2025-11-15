@extends('layouts.app')

@section('content')
<div class="container">

<form action="{{ route('requerimientos.update', $requerimiento) }}" method="POST">
    @csrf
     @include('requerimientos.form', ['modo' => 'Editar'])
      @method('PUT')

  
</form>


</div>
@endsection



