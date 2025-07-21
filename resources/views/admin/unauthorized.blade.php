@extends('layouts.app')
@section('title')
    Oups!! no tienes permiso de ver esta seccion
@endsection
@section('content')
<div class="row w-100 mx-0 auth-page">
	<div class="col-md-8 col-xl-6 mx-auto d-flex flex-column align-items-center">
		<img src="{{ asset('assets/images/403.png') }}" class="img-fluid mb-2" alt="403">
		<h1 class="font-weight-bold mb-22 mt-2 tx-80 text-muted">403</h1>
		<h4 class="mb-2">Error de permisos!</h4>
		<h6 class="text-muted mb-3 text-center">Oopps!! Parece que no tienes permiso para ver esta sección.</h6>
		<a href="{{ route('dashboard') }}" class="btn btn-primary">Volver al Dashboard</a>
	</div>
</div>
@endsection
