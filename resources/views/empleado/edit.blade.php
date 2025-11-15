@extends('layouts.app')

@section('content')
<div class="container">

<form method="post" action="{{url('/empleado/'.$empleado->id_personal)}}" enctype="multipart/form-data">
    @csrf
    {{method_field('PATCH')}}
    @include('empleado.form' , ['modo' => 'Editar'])
    
    </form>
</div>
@endsection

    