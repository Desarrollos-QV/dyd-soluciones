@extends('layouts.app')
@section('title')
    Nuevo tecnico
@endsection
@section('content')
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('./') }}">Inicio</a></li>
        <li class="breadcrumb-item active" aria-current="page">Agregando Tecnicos</li>
    </ol>
</nav>
<div class="row">
    <div class="col-lg-8 mx-auto">
        <form action="{{ route('tecnicos.store') }}" method="POST" id="create_tecnico">
            @csrf
            @include('admin.tecnicos.form')
        </form>
    </div>
</div>
@endsection 