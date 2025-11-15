@extends('layouts.app')

@section('content')
<div class="container">

<form method="post" action="{{ url('/proyecto/'.$proyecto->id_proyecto) }}" enctype="multipart/form-data">

    @csrf
    {{method_field('PATCH')}}
    @include('proyecto.form' , ['modo' => 'Editar'])
    
    </form>
</div>
@endsection

