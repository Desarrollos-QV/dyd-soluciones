@extends('layouts.app')
@section('title')
    Editando tecnico - "{{$tecnico->name}}"
@endsection
@section('content')

<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('./') }}">Inicio</a></li>
        <li class="breadcrumb-item active" aria-current="page">Editando Tecnicos</li>
    </ol>
</nav>
<div class="row">
    <div class="col-lg-8 mx-auto">
        <form action="{{ route('tecnicos.update', $tecnico) }}" method="POST">
            @csrf
            @method('PUT')
            @include('admin.tecnicos.form')
        </form>
    </div>
</div>
@endsection
