@extends('layouts.app')
@section('title', 'Editar Cliente')
@section('css')
<link rel="stylesheet" href="{{ asset('assets/vendors/dropify/dist/dropify.min.css') }}">
@endsection
@section('content')
<form action="{{ route('clientes.update', $cliente) }}" method="POST" class="row" enctype="multipart/form-data" id="cliente_form">
    @csrf
    @method('PUT')
    @include('admin.clientes.form')
</form>
@endsection
@section('js')
<script src="{{ asset('assets/vendors/dropify/dist/dropify.min.js') }}"></script>
<script src="{{ asset('assets/js/dropify.js') }}"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('cliente_form');

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
                    window.location.href = `${data.redirect}?success=${encodeURIComponent(data.message)}`;
                } else {
                    const errorData = await response.json();
                    console.error('Errores:', errorData);

                    let errorMsg = errorData.message || 'Error al validar el formulario.';
                    if (errorData.errors) {
                        const errorList = Object.values(errorData.errors).flat().join('<br>');
                        if (errorList) {
                            errorMsg = errorList;
                        }
                    }

                    Swal.fire({
                        icon: 'error',
                        type: 'error',
                        title: 'Oops... Revisa la siguiente información:',
                        html: errorMsg
                    });
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    type: 'error',
                    title: 'Oops...',
                    text: "Ocurrió un error inesperado al procesar la solicitud."
                });
            }
        });
    });
</script>
@endsection