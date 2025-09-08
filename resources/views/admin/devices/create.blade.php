@extends('layouts.app') 
@section('title', 'Registrar nuevo Dispositivo')
@section('content')
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('./') }}">Inicio</a></li>
        <li class="breadcrumb-item active" aria-current="page">Registrar nuevo Dispositivo</li>
    </ol>
</nav>

<form action="{{ route('devices.store') }}" method="POST" id="devices_form" class="row" enctype="multipart/form-data">
    @csrf
    @include('admin.devices.form')
</form> 
@endsection

@section('js')
<script src="{{ asset('assets/vendors/bootstrap-maxlength/bootstrap-maxlength.min.js') }}"></script>
<script src="{{ asset('assets/vendors/inputmask/jquery.inputmask.min.js') }}"></script>
<script src="{{ asset('assets/js/inputmask.js') }}"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('devices_form');

        form.addEventListener('submit', async function (event) {
            event.preventDefault(); 
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