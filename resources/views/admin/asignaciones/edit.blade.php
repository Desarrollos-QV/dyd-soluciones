@extends('layouts.app')
@section('title', 'Editar Asignación')

@section('content')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('./') }}">Inicio</a></li>
            <li class="breadcrumb-item active" aria-current="page">Editar Asignación</li>
        </ol>
    </nav>


    <div class="row">
        <div class="col-lg-10 mx-auto">
            <form action="{{ route('assignements.update', $assignement) }}" method="POST" 
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @include('admin.asignaciones.form')
            </form>
        </div>
    </div>
@endsection
