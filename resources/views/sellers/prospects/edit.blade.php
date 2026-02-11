@extends('layouts.app') 
@section('title', 'Editar Prospecto')
@section('content')
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('sellers.dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item active" aria-current="page">Editar Prospecto</li>
    </ol>
</nav>

<form action="{{ route('sellers.prospects.update', $prospect->id) }}" id="prospects_form" class="row" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    @include('sellers.prospects.form')
</form>
@endsection


@section('js')
<script src="{{ asset('assets/vendors/bootstrap-maxlength/bootstrap-maxlength.min.js') }}"></script>
<script src="{{ asset('assets/vendors/inputmask/jquery.inputmask.min.js') }}"></script>
<script src="{{ asset('assets/js/inputmask.js') }}"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('prospects_form');

        form.addEventListener('submit', async function (event) {
            event.preventDefault(); // Evita el envío tradicional del formulario

            const formData = new FormData(form);

            try {
                // Laravel treats PUT requests via _method field in POST
                // but Fetch API can send PUT directly depending on backend configuration.
                // Standard Laravel REST usually requires POST with _method=PUT for forms
                // but if we use fetch with PUT method, it might not parse body if content-type is multipart/form-data without special handling (Laravel limitation with PUT/PATCH and FormData).
                // Safest way is POST with _method=PUT inside FormData which @method('PUT') does.
                
                const response = await fetch(form.action, {
                    method: 'POST', 
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData
                });

                if (response.ok) {
                    const data = await response.json();
                    window.location.href = `${data.redirect}?success=${encodeURIComponent(data.message)}`;
                } else {
                    const errorData = await response.json();
                    console.error('Errores:', errorData);
                    Swal.fire({
                        type: 'error',
                        title: 'Oops... Hubo un error al enviar el formulario.',
                        text: errorData.message
                    });
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    type: 'error',
                    title: 'Oops...',
                    text: "Ocurrió un error inesperado."
                });
            }
        });
    });
</script>
@endsection
