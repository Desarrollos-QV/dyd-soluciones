@extends('layouts.app')
@section('title')
    Nuevo tecnico
@endsection
@section('content')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('./') }}">Inicio</a></li>
            <li class="breadcrumb-item active" aria-current="page">Agregando Tecnicos</li>
        </ol>
    </nav>

    <form action="{{ route('tecnicos.store') }}" method="POST" id="create_tecnico" class="row"
        enctype="multipart/form-data">
        @csrf
        @include('admin.tecnicos.form')
    </form>
@endsection
@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('create_tecnico');

            /** Envio de Form **/
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

        /** Agregado de herramientas **/
        function addTools() {
            const tbody = document.getElementById('rows-container');
            const tr = document.createElement('tr');
            tr.className = 'd-flex align-items-center justify-content-between';
            tr.innerHTML = `
            <td><input type="text" name="tools[]" placeholder="Agrega nueva herramienta" style="padding: 5px 0 5px 5px;"/></td>
            <td>
                <button type="button" class="btn btn-outline-primary d-flex justify-content-center align-items-center"
                    onclick="removeRow(this)">
                    <svg xmlns="http://www.w3.org/2000/svg" style="cursor:pointer;" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="feather feather-trash-2">
                        <polyline points="3 6 5 6 21 6"></polyline>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                        <line x1="10" y1="11" x2="10" y2="17"></line>
                        <line x1="14" y1="11" x2="14" y2="17"></line>
                    </svg>
                    &nbsp;<span class="mt-1">Eliminar</span>
                </button>
            </td>
    `;
            tbody.appendChild(tr);
        }

        function removeRow(btn) {
            // Elimina la fila (tr) que contiene el botón presionado
            btn.closest('tr').remove();
        }
    </script>
@endsection
