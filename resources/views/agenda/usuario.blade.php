@extends('layouts.app')

@section('title', 'Agenda')

@section('content')
    <style>
        body {
            margin: 0;
            font-family: Cambria, Georgia, serif;
            background-image: url("{{ Vite::asset('public/Imagenes/background.jpeg') }}"); 
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-position: center;
        } 
        /* Estilos personalizados */
        .fc-toolbar h2 {
            color: white; /* Texto del mes */
        }
        .form-label {
            color: white; /* Texto de los labels */
        }
        #calendar {
            max-width: 900px; /* Tamaño del calendario */
            margin: auto;
            max-height: 400px;
        }
        .fc-event {
            background-color: green; /* Color de las citas agendadas */
        }
        .fc {
            background-color: #5a6978; /* Color de fondo del calendario */
        }
        .fc-time-grid .fc-slot {
            height: 45px; /* Altura uniforme para los intervalos de hora */
        }
        .fc-time-grid .fc-slot[data-time="18:30:00"] {
            height: 45px; /* Ajuste de altura para las 6:30 PM */
        }
        @media (max-width: 768px) {
            #calendar {
                width: 100%; /* Hacer el calendario responsive */
            }
        }
        /* Estilos para el formulario multistep */
        .step {
            display: none;
        }
        .step.active {
            display: block;
        }
        .progressbar {
            counter-reset: step;
            margin-bottom: 30px;
            overflow: hidden;
        }
        .progressbar li {
            list-style-type: none;
            color: gray;
            text-transform: uppercase;
            font-size: 12px;
            width: 20%;
            float: left;
            position: relative;
            text-align: center;
        }
        .progressbar li:before {
            content: counter(step);
            counter-increment: step;
            width: 30px;
            height: 30px;
            line-height: 30px;
            border: 1px solid gray;
            display: block;
            text-align: center;
            margin: 0 auto 10px auto;
            border-radius: 50%;
            background-color: white;
        }
        .progressbar li:after {
            content: '';
            position: absolute;
            width: 100%;
            height: 2px;
            background-color: gray;
            top: 15px;
            left: -50%;
            z-index: -1;
        }
        .progressbar li:first-child:after {
            content: none;
        }
        .progressbar li.active {
            color: green;
        }
        .progressbar li.active:before {
            border-color: green;
            background-color: green;
            color: white;
        }
        .progressbar li.active + li:after {
            background-color: green;
        }
    </style>

    <section class="secciones" style="margin-top: 3.5rem;">
        <h2 class="titulo-secciones text-center text-white">Agenda tu Cita</h2>
        
        <!-- Barra de progreso -->
        <ul class="progressbar">
            <li class="active">Servicio</li>
            <li>Fecha y Hora</li>
            <li>Resumen</li>
            <li>Confirmación</li>
        </ul>

        <div id="appointmentForm" class="mt-4">
            <!-- Paso 1: Selección de Servicio y Profesional -->
            <div class="step active" id="step-1">
                <h4 class="form-label">Selecciona tu Servicio</h4>
                <form id="form-step-1">
                    <div class="form-group">
                        <label for="service" class="form-label">Servicio</label>
                        <select class="form-control" id="service" name="service" required>
                            <option value="">Seleccionar...</option>
                            <option value="Corte">Corte de Pelo</option>
                            <option value="Afeitado">Afeitado</option>
                            <option value="Facial">Facial</option>
                            <option value="Masaje">Masaje</option>
                            <option value="Tinte">Tinte</option>
                            <option value="Corte de Cabello">Corte de Cabello</option>
                            <option value="Corte de Barba">Corte de Barba</option>
                            <option value="Estilo">Estilo</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="attendant" class="form-label">Seleccionar Profesional</label>
                        <select class="form-control" id="attendant" name="attendant" required>
                            <option value="">Seleccionar...</option>
                            <!-- Las opciones se llenarán dinámicamente según el servicio seleccionado -->
                        </select>
                        <small id="no-professionals" class="form-text text-danger" style="display:none;">
                            No hay personal disponible para ese día, elige una fecha diferente.
                        </small>
                    </div>
                    <button type="button" class="btn btn-primary next-step">Siguiente</button>
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
                <button type="button" class="btn btn-secondary prev-step mt-3">Atrás</button>
                <button type="button" class="btn btn-success" id="confirmBooking">Confirmar Reserva</button>
                <button type="button" class="btn btn-info" id="addService" style="display:none;">Agregar más servicios</button>
            </div>

            <!-- Paso 4: Confirmación -->
            <div class="step" id="step-4">
                <h4 class="form-label">¡Reserva Confirmada!</h4>
                <div id="confirmationMessage" class="mt-3">
                    <!-- Mensaje de confirmación -->
                </div>
                <button type="button" class="btn btn-primary" onclick="window.location.reload();">Agendar Otra Cita</button>
            </div>
        </div>

        <div id="calendar-selection" style="display:none;">
            <!-- Calendario para la selección de fecha y hora -->
            <div id="calendar-container">
                <div id="calendar"></div>
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
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="additional-service" class="form-label">Selecciona Servicio</label>
                            <select class="form-control" id="additional-service" name="additional_service" required>
                                <option value="">Seleccionar...</option>
                                <option value="Corte">Corte de Pelo</option>
                                <option value="Afeitado">Afeitado</option>
                                <option value="Facial">Facial</option>
                                <option value="Masaje">Masaje</option>
                                <option value="Tinte">Tinte</option>
                                <option value="Corte de Cabello">Corte de Cabello</option>
                                <option value="Corte de Barba">Corte de Barba</option>
                                <option value="Estilo">Estilo</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="additional-attendant" class="form-label">Seleccionar Profesional</label>
                            <select class="form-control" id="additional-attendant" name="additional_attendant" required>
                                <option value="">Seleccionar...</option>
                                <!-- Opciones dinámicas -->
                            </select>
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

@endsection
