@extends('layouts.app')
@section('title')
    Agregando nuevo Cliente
@endsection
@section('content')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('./') }}">Inicio</a></li>
            <li class="breadcrumb-item active" aria-current="page">Nuevo Cliente</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-10 mx-auto">
            <form action="{{ route('assignements.store') }}" method="POST">
                @csrf
                @include('admin.asignaciones.form')
            </form>
        </div>
    </div>
@endsection