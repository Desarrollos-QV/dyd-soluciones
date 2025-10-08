@extends('layouts.app')
@section('title', 'Crear nueva Unidad')
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/vendors/dropify/dist/dropify.min.css') }}">
@endsection
@section('content')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('./') }}">Inicio</a></li>
            <li class="breadcrumb-item active" aria-current="page">Agregar Nueva Unidad</li>
        </ol>
    </nav>

    <form action="{{ route('unidades.store') }}" method="POST" id="crate_unity" class="row" enctype="multipart/form-data">
        @csrf
        @include('admin.unidades.form')
    </form>
@endsection

@section('js')

    <script src="{{ asset('assets/vendors/dropify/dist/dropify.min.js') }}"></script>
    <script src="{{ asset('assets/js/dropify.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('crate_unity');

            form.addEventListener('submit', async function(event) {
                event.preventDefault(); // Evita el envío tradicional del formulario

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
                        // alert('Elemento del inventario creado exitosamente.');
                        // Redirigir o realizar alguna acción adicional 
                        window.location.href =
                            `${data.redirect}?success=${encodeURIComponent(data.message)}`;
                    } else {
                        const errorData = await response.json();
                        console.error('Errores:', errorData);
                        // alert('Hubo un error al enviar el formulario.');
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



            const dispositivo_instalado = document.getElementById('dispositivo_instalado');
            const other_dispositivo_instalado = document.getElementById('other_dispositivo_instalado');
            const inner_other_disp = document.getElementById('inner_other_disp');
            const NewInput = document.getElementById('input_dispositivo_instalado');
            const Unity = "{{ isset($unidad->id) ? 1 : 0 }}";

            if (Unity == 1) {
                if (dispositivo_instalado.selectedIndex == 0) // No coincide ninguna opcion y no esta seleccionado
                {
                    other_dispositivo_instalado.style.display = "block";
                    NewInput.setAttribute('name', 'dispositivo_instalado');
                    NewInput.focus();
                    dispositivo_instalado.removeAttribute('name');
                }
            }


            dispositivo_instalado.addEventListener('change', (ev) => {
                let value = ev.target.value;
                if (value == 'Otro') {
                    other_dispositivo_instalado.style.display = "block";
                    NewInput.setAttribute('name', 'dispositivo_instalado');
                    NewInput.value = "";
                    NewInput.focus();
                    dispositivo_instalado.removeAttribute('name');
                } else {

                    other_dispositivo_instalado.style.display = "none";
                    dispositivo_instalado.setAttribute('name', 'dispositivo_instalado');
                    NewInput.removeAttribute('name');
                }
            });
        });
    </script>
@endsection
