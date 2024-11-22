<!-- resources/views/appointments/edit.blade.php -->

@extends('layouts.app')

@section('title', 'Editar Cita')

@section('content')
    <!-- Contenedor Principal -->
    <div class="container my-5">
        <h2 class="mb-4 text-center">Editar Cita</h2>

        <!-- Mostrar errores de validación -->
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        @endif

        <!-- Formulario de Edición de Cita -->
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <strong>Editar Cita</strong>
            </div>
            <div class="card-body">
                <form action="{{ route('citas.update', $cita->cta_id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="service" class="form-label">Servicio</label>
                        <select class="form-select" id="service" name="service" required>
                            <option value="">Seleccionar...</option>
                            @foreach($servicios as $servicio)
                                <option value="{{ $servicio->srv_id }}" {{ $cita->servicios->pluck('srv_id')->contains($servicio->srv_id) ? 'selected' : '' }}>
                                    {{ $servicio->srv_nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="attendant" class="form-label">Profesional</label>
                        <select class="form-select" id="attendant" name="attendant" required>
                            <option value="">Seleccionar...</option>
                            @foreach($profesionales as $profesional)
                                <option value="{{ $profesional->usr_id }}" {{ $cita->cta_profesional_id == $profesional->usr_id ? 'selected' : '' }}>
                                    {{ $profesional->usr_nombre_completo }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="fecha" class="form-label">Fecha</label>
                        <input 
                            type="date" 
                            class="form-control" 
                            id="fecha" 
                            name="fecha" 
                            value="{{ $cita->cta_fecha }}" 
                            required
                        >
                    </div>

                    <div class="mb-3">
                        <label for="hora" class="form-label">Hora</label>
                        <select class="form-select" id="hora" name="hora" required>
                            <option value="">Seleccionar...</option>
                            @for ($hour = 11; $hour <= 20; $hour++)
                                @php
                                    $formattedHour = sprintf('%02d:00', $hour);
                                @endphp
                                <option value="{{ $formattedHour }}" {{ \Carbon\Carbon::parse($cita->cta_hora)->format('H:i') == $formattedHour ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::createFromFormat('H:i', $formattedHour)->format('h:i A') }}
                                </option>
                            @endfor
                        </select>
                        <!-- Elemento para mostrar mensajes de error o no disponibilidad -->
                        <div id="attendant-error" class="form-text text-danger" style="display: none;"></div>
                    </div>

                    <button type="submit" class="btn btn-success">Actualizar Cita</button>
                    <a href="{{ route('my.appointments') }}" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>

    </div>

    <!-- Incluir jQuery y Bootstrap JS desde CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Si ya tienes Bootstrap JS incluido en tu layout, puedes omitirlo aquí -->

    <!-- Scripts Personalizados -->
    <script>
        $(document).ready(function() {
            // Evento para actualizar los profesionales según el servicio seleccionado en el formulario de edición
            $('#service').on('change', function() {
                var serviceId = $(this).val();
                if (serviceId) {
                    $.ajax({
                        url: "{{ route('citas.getProfessionals', '') }}/" + serviceId,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            $('#attendant').empty();
                            $('#attendant-error').hide(); // Ocultar cualquier mensaje previo
                            if (data.length > 0) {
                                $('#attendant').append('<option value="">Seleccionar...</option>');
                                $.each(data, function(key, value) {
                                    $('#attendant').append('<option value="' + value.usr_id + '">' + value.usr_nombre_completo + '</option>');
                                });
                            } else {
                                $('#attendant').append('<option value="">Seleccionar...</option>');
                                $('#attendant-error').text('No hay profesionales disponibles para el servicio seleccionado.').show();
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error al obtener los profesionales:', error);
                            $('#attendant').empty();
                            $('#attendant').append('<option value="">Seleccionar...</option>');
                            $('#attendant-error').text('Hubo un error al cargar los profesionales. Por favor, intenta nuevamente más tarde.').show();
                        }
                    });
                } else {
                    $('#attendant').empty();
                    $('#attendant').append('<option value="">Seleccionar...</option>');
                    $('#attendant-error').hide(); // Ocultar cualquier mensaje previo
                }
            });
    
            // Pre-cargar los profesionales si el servicio ya está seleccionado (en caso de edición)
            var initialServiceId = $('#service').val();
            if (initialServiceId) {
                $('#service').trigger('change');
            }

        });
    </script>
    
@endsection
