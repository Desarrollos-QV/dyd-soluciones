@extends('layouts.app')
@section('title', 'Editar Unidad')

@section('content')
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('./') }}">Inicio</a></li>
        <li class="breadcrumb-item active" aria-current="page">Editar Unidad</li>
    </ol>
</nav>

<form action="{{ route('unidades.update', $unidad) }}" method="POST" class="row" enctype="multipart/form-data" id="crate_unity">
    @csrf
    @method('PUT')
    @include('admin.unidades.form')
</form>
@endsection
@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('crate_unity');

        form.addEventListener('submit', async function (event) {
            event.preventDefault(); // Evita el envío tradicional del formulario

            const formData = new FormData(form);

            try {
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
                    // Redirigir o realizar alguna acción adicional
                    window.location.href = `${data.redirect}?success=${encodeURIComponent(data.message)}`;
                } else {
                    const errorData = await response.json();
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