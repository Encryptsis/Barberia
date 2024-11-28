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

                <!-- Campo para el Profesional -->
                <div class="mb-3">
                    <label for="attendant" class="form-label">Profesional</label>
                    <select class="form-select" id="attendant" name="attendant" required>
                        <!-- Las opciones serán cargadas vía JavaScript -->
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
@endsection

@push('scripts')
    <script>
        window.appointmentsEditData = {
            getProfessionalsUrl: "{{ route('appointments.getProfessionals', '') }}",
            assignedProfessionalId: {{ $cita->cta_profesional_id ?? 'null' }},
        };
    </script>

    @vite('resources/js/user_appointments_edit.js')
@endpush