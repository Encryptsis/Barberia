<!-- resources/views/appointments/citas.blade.php -->

@extends('layouts.app')

@section('title', 'Agenda')

@section('content')
    <!-- Inclusión de CSS de FullCalendar -->
    <link href='https://unpkg.com/fullcalendar@5.10.1/main.min.css' rel='stylesheet' />
    @vite(['resources/css/citas.css', 'resources/js/citas.js'])

        <!-- Definir rutas globales para JS -->
        <script>
            window.citasRoutes = {
                getAvailableTimes: "{{ route('appointments.getAvailableTimes') }}",
                getProfessionals: "/citas/get-professionals/:service_id",
                saveAppointment: "{{ route('appointments.saveAppointment') }}",
                login: "{{ route('login') }}"
            };
        </script>

    <section class="secciones" style="margin-top: 3.5rem;">
        <h2 class="titulo-secciones text-center text-white">Agenda tu Cita</h2>
        
        <!-- Barra de progreso -->
        <ul class="progressbar">
            <li class="active">Servicio</li>          <!-- Índice 0 -->
            <li>Fecha y Hora</li>                     <!-- Índice 1 -->
            <li>Resumen</li>                          <!-- Índice 2 -->
            <li>Confirmación</li>                     <!-- Índice 3 -->
        </ul>

        <!-- Sección de Puntos de Fidelidad -->
        <div class="mb-4">
            <div class="card" id="pointsSection"> <!-- Añadido ID para actualizar dinámicamente -->
                <div class="card-body">
                    <h5 class="card-title">Puntos de Fidelidad</h5>
                    <p class="card-text">
                        <strong>Saldo Actual:</strong> {{ $userPoints }} puntos
                    </p>
                    @if($userPoints >= 100)
                        <div class="alert alert-info">
                            ¡Felicidades! Has acumulado {{ $userPoints }} puntos. Tienes derecho a una <strong>cita gratuita</strong>.
                        </div>
                    @else
                        <p>Acumula 100 puntos para obtener una cita gratuita.</p>
                    @endif
                </div>
            </div>
        </div>

        <div id="appointmentForm" class="mt-4">
            <!-- Paso 1: Selección de Servicio y Profesional -->
            <div class="step active" id="step-1">
                <h4 class="form-label">Selecciona tu Servicio</h4>
                <form id="form-step-1">
                    @csrf <!-- Añade el token CSRF para proteger el formulario -->
                    <div class="form-group">
                        <label for="service" class="form-label">Servicio</label>
                        <select class="form-control" id="service" name="service" required>
                            <option value="">Seleccionar...</option>
                            @foreach($servicios as $servicio)
                                <option value="{{ $servicio->srv_id }}">{{ $servicio->srv_nombre }}</option>
                            @endforeach
                        </select>                        
                    </div>
                    <div class="form-group">
                        <label for="attendant" class="form-label">Seleccionar Profesional</label>
                        <select class="form-control" id="attendant" name="attendant" required>
                            <option value="">Seleccionar...</option>
                            <!-- Las opciones se llenarán dinámicamente según el servicio seleccionado -->
                        </select>
                        <small id="no-professionals" class="form-text text-danger" style="display:none;">
                            No hay personal disponible para ese servicio. Por favor, elige otro servicio.
                        </small>
                    </div>
                    @if($userPoints >= 100)
                        <div class="form-check mt-3">
                            <input class="form-check-input" type="checkbox" value="1" id="use_free_appointment" name="use_free_appointment">
                            <label class="form-check-label text-white" for="use_free_appointment">
                                Usar mi cita gratuita
                            </label>
                        </div>
                    @endif
                    <button type="button" class="btn btn-primary next-step" id="to-step-2">Siguiente</button>
                </form>
            </div>

            <!-- Paso 2: Selección de Fecha y Hora -->
            <div class="step" id="step-2">
                <h4 class="form-label">Selecciona Fecha y Hora</h4>
                <div id="calendar"></div>
                <button type="button" class="btn btn-secondary prev-step mt-3">Atrás</button>
                <button type="button" class="btn btn-primary next-step mt-3">Siguiente</button>
            </div>

            <!-- Paso 3: Resumen de la Reserva -->
            <div class="step" id="step-3">
                <h4 class="form-label">Resumen de tu Reserva</h4>
                <div id="summary">
                    <!-- Se llenará dinámicamente con los detalles de la reserva -->
                </div>
                <div class="d-flex justify-content-between mt-3">
                    <button type="button" class="btn btn-secondary prev-step">Atrás</button>
                    <button type="button" class="btn btn-success" id="confirmBooking">Confirmar Reserva</button>
                </div>
                <!-- Eliminar el botón "Agregar más servicios" de Paso 3 -->
            </div>

            <!-- Paso 4: Confirmación -->
            <div class="step" id="step-4">
                <h4 class="form-label">¡Reserva Confirmada!</h4>
                <div id="confirmationMessage" class="mt-3">
                    <!-- Mensaje de confirmación -->
                </div>
                <button type="button" class="btn btn-primary mt-3" onclick="window.location.reload();">Agendar Otra Cita</button>
            </div>
        </div>
    </section>

    <!-- Modal para agregar servicios adicionales -->
    <div class="modal fade" id="addServiceModal" tabindex="-1" aria-labelledby="addServiceModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Agregar Más Servicios</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="additional-services-form">
                    @csrf <!-- Añade el token CSRF para proteger el formulario -->
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="additional-service" class="form-label">Selecciona Servicio</label>
                            <select class="form-control" id="additional-service" name="additional_service" required>
                                <option value="">Seleccionar...</option>
                                @foreach($servicios as $servicio)
                                    <option value="{{ $servicio->srv_id }}">{{ $servicio->srv_nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="additional-attendant" class="form-label">Seleccionar Profesional</label>
                            <select class="form-control" id="additional-attendant" name="additional_attendant" required>
                                <option value="">Seleccionar...</option>
                                <!-- Las opciones se llenarán dinámicamente según el servicio seleccionado -->
                            </select>
                            <small id="no-professionals-add" class="form-text text-danger" style="display:none;">
                                No hay personal disponible para ese servicio. Por favor, elige otro servicio.
                            </small>
                        </div>
                        <div class="form-group">
                            <label for="additional-time" class="form-label">Seleccionar Hora</label>
                            <input type="time" class="form-control" id="additional-time" name="additional_time" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Agregar Servicio</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Incluir el script de FullCalendar -->
    <script src='https://unpkg.com/fullcalendar@5.10.1/main.min.js'></script>
    <!-- Incluir SweetAlert2 para mejores alertas -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Incluir jQuery desde CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

@endsection
