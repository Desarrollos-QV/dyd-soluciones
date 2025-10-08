@extends('layouts.app')
@section('title')
    Editando tecnico - "{{$tecnico->name}}"
@endsection
@section('css')
<link rel="stylesheet" href="{{ asset('assets/vendors/dropify/dist/dropify.min.css') }}">
@endsection
@section('content')

<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('./') }}">Inicio</a></li>
        <li class="breadcrumb-item active" aria-current="page">Editando Tecnicos</li>
    </ol>
</nav>
<div class="row">
    <div class="col-lg-10 mx-auto">
        <form action="{{ route('tecnicos.update', $tecnico) }}" method="POST" id="edit_tecnico" class="row" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('admin.tecnicos.form')
        </form>
    </div>
</div>
@endsection
@section('js')
<script src="{{ asset('assets/vendors/dropify/dist/dropify.min.js') }}"></script>
<script src="{{ asset('assets/js/dropify.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('edit_tecnico');

            form.addEventListener('submit', async function(event) {
                event.preventDefault();
                const formData = new FormData(form);
                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        },
                        body: formData
                    });

                    if (response.ok) {
                        const data = await response.json();
                        console.log(data);
                        window.location.href =
                            `${data.redirect}?success=${encodeURIComponent(data.message)}`;
                    } else {
                        const errorData = await response.json();
                        console.error('Errores:', errorData);
                        Swal.fire({
                            type: 'error',
                            title: 'Oops... Hubo un error al enviar el formulario.',
                            text: errorData.message
                        }).then(() => {
                            window.location.reload();
                        });
                    }
                } catch (error) {
                    console.error('Error:', error);
                    Swal.fire({
                        type: 'error',
                        title: 'Oops...',
                        text: "Ocurrió un error inesperado."
                    }).then(() => {
                        window.location.reload();
                    });
                }
            });
        });
    </script>
@endsection