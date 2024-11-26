<!-- resources/views/agenda/edit_mi_agenda.blade.php -->

@extends('layouts.app')

@section('title', 'Editar Cita - Mi Agenda')

@section('content')
    <!-- Contenedor Principal -->
    <div class="container my-5">
        <h2 class="mb-4 text-center">Editar</h2>

        <!-- Mostrar mensajes de éxito o error -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        @endif

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

                    <!-- Servicio -->
                    <div class="mb-3">
                        <label for="service" class="form-label">Servicio</label>
                        <select class="form-select" id="service" name="service" required>
                            <option value="">-- Selecciona un Servicio --</option>
                            @foreach($servicios as $servicio)
                                <option value="{{ $servicio->srv_id }}" {{ $cita->servicios->pluck('srv_id')->contains($servicio->srv_id) ? 'selected' : '' }}>
                                    {{ $servicio->srv_nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Profesional -->
                    <div class="mb-3">
                        <label for="attendant" class="form-label">Profesional</label>
                        <select class="form-select" id="attendant" name="attendant" required>
                            <option value="">-- Selecciona un Profesional --</option>
                            @foreach($profesionales as $profesional)
                                <option value="{{ $profesional->usr_id }}" {{ $cita->cta_profesional_id == $profesional->usr_id ? 'selected' : '' }}>
                                    {{ $profesional->usr_nombre_completo }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Fecha -->
                    <div class="mb-3">
                        <label for="fecha" class="form-label">Fecha</label>
                        <input 
                            type="date" 
                            class="form-control" 
                            id="fecha" 
                            name="fecha" 
                            value="{{ old('fecha', $cita->cta_fecha) }}" 
                            required
                        >
                    </div>

                    <!-- Hora -->
                    <div class="mb-3">
                        <label for="hora" class="form-label">Hora</label>
                        <select class="form-select" id="hora" name="hora" required>
                            <option value="">-- Selecciona una Hora --</option>
                            @for ($hour = 11; $hour <= 20; $hour++)
                                @php
                                    $formattedHour = sprintf('%02d:00', $hour);
                                    $formattedDisplay = \Carbon\Carbon::createFromFormat('H:i', $formattedHour)->format('h:i A');
                                @endphp
                                <option value="{{ $formattedHour }}" {{ \Carbon\Carbon::parse($cita->cta_hora)->format('H:i') == $formattedHour ? 'selected' : '' }}>
                                    {{ $formattedDisplay }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <!-- Botones de Acción -->
                    <button type="submit" class="btn btn-success">Actualizar Cita</button>
                    <a href="{{ route('my-appointments') }}" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>

    </div>

    <!-- Incluir jQuery y Bootstrap JS desde CDN si no están incluidos en tu layout -->
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
                        url: "{{ route('appointments.getProfessionals', '') }}/" + serviceId,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            $('#attendant').empty();
                            if (data.length > 0) {
                                $('#attendant').append('<option value="">-- Selecciona un Profesional --</option>');
                                $.each(data, function(key, value) {
                                    $('#attendant').append('<option value="' + value.usr_id + '">' + value.usr_nombre_completo + '</option>');
                                });
                            } else {
                                $('#attendant').append('<option value="">No hay profesionales disponibles</option>');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error al obtener los profesionales:', error);
                            $('#attendant').empty();
                            $('#attendant').append('<option value="">Error al cargar profesionales</option>');
                        }
                    });
                } else {
                    $('#attendant').empty();
                    $('#attendant').append('<option value="">-- Selecciona un Servicio Primero --</option>');
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
