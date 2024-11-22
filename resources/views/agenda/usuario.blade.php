<!-- resources/views/appointments/citas.blade.php -->

@extends('layouts.app')

@section('title', 'Agenda')

@section('content')
    <!-- Inclusión de CSS de FullCalendar -->
    <link href='https://unpkg.com/fullcalendar@5.10.1/main.min.css' rel='stylesheet' />

    <style>
        /* Estilos existentes y personalizados */
        body {
            margin: 0;
            font-family: Cambria, Georgia, serif;
            background-image: url("{{ asset('Imagenes/background.jpeg') }}"); 
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-position: center;
        } 
        /* Estilos personalizados para el calendario y formulario */
        .fc-toolbar h2 {
            color: white; /* Texto del mes */
            font-size: 1rem; /* Reducir tamaño de fuente */
        }
        .form-label {
            color: white; /* Texto de los labels */
        }
        #calendar {
            max-width: 800px; /* Reducir el ancho máximo del calendario */
            margin: auto;
            height: auto; /* Ajusta automáticamente la altura */
        }
        .fc {
            background-color: #5a6978; /* Color de fondo del calendario */
            border: none; /* Eliminar bordes */
            border-radius: 5px; /* Bordes redondeados */
            box-shadow: 0 2px 5px rgba(0,0,0,0.1); /* Añadir sombra */
        }
        /* Estilos para las etiquetas de horas */
        .fc-timegrid-axis-cushion {
            color: white;
            font-weight: bold;
            font-size: 0.8rem; /* Reducir tamaño de fuente */
        }
        /* Estilos para las celdas de eventos disponibles */
        .fc-event.available-slot {
            background-color: #28a745 !important; /* Verde más vibrante */
            border: none;
            border-radius: 3px; /* Bordes más redondeados */
            cursor: pointer;
            font-size: 0.8rem; /* Reducir tamaño de fuente */
            padding: 2px 4px; /* Reducir padding */
            text-align: center;
            color: transparent !important; /* Ocultar texto */
        }
        /* Estilos para el evento seleccionado */
        .fc-event.selected {
            background-color: #dc3545 !important; /* Rojo */
        }
        @media (max-width: 768px) {
            #calendar {
                width: 100%; /* Hacer el calendario responsive */
                height: auto; /* Ajustar la altura automáticamente */
            }
            .fc-timegrid-axis-cushion {
                font-size: 0.7rem; /* Reducir aún más tamaño de fuente en móviles */
            }
            .fc-event {
                font-size: 0.7rem; /* Reducir tamaño de fuente de los eventos en móviles */
            }
            .fc-toolbar-title {
                font-size: 0.9rem; /* Reducir tamaño de fuente del título en móviles */
            }
            .fc-button {
                font-size: 0.7rem; /* Reducir tamaño de fuente de los botones en móviles */
                padding: 4px 6px; /* Reducir padding de los botones en móviles */
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
            padding-left: 0;
        }
        .progressbar li {
            list-style-type: none;
            color: gray;
            text-transform: uppercase;
            font-size: 12px;
            width: 25%; /* Cambiar a 25% para 4 pasos */
            float: left;
            position: relative;
            text-align: center;
            padding: 10px 0;
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

        /* Estilos para pasos activos */
        .progressbar li.active {
            color: green;
        }
        .progressbar li.active:before {
            border-color: green;
            background-color: green;
            color: white;
        }

        /* Estilos para pasos completados */
        .progressbar li.completed {
            color: green;
        }
        .progressbar li.completed:before {
            border-color: green;
            background-color: green;
            color: white;
        }

        /* Estilos para líneas después de pasos completados */
        .progressbar li.completed + li:after {
            background-color: green; /* Línea verde cuando el paso anterior está completado */
        }

        /* Estilos para botones */
        .btn {
            margin: 5px;
        }
        /* Estilos para el resumen */
        #summary p {
            background-color: rgba(255, 255, 255, 0.1);
            padding: 10px;
            border-radius: 5px;
            color: white;
        }
        /* Estilos para mensajes de confirmación */
        .alert-success {
            background-color: rgba(40, 167, 69, 0.8);
            color: white;
            padding: 15px;
            border-radius: 5px;
        }
        .alert-danger {
            background-color: rgba(220, 53, 69, 0.8);
            color: white;
            padding: 15px;
            border-radius: 5px;
        }

        /* Estilos adicionales para hacer el calendario más compacto */
        .fc-timegrid-slot {
            padding: 2px 0; /* Reducir padding */
        }
        .fc-timegrid-slot-lane {
            height: 30px !important; /* Ajustar la altura de cada fila de hora */
        }
        .fc-scrollgrid-section-header, .fc-scrollgrid-section-body {
            padding: 0; /* Eliminar padding en las secciones del calendario */
        }

        /* Forzar que todos los eventos disponibles sean verdes, incluidos los sábados */
        .fc-event.available-slot {
            background-color: #28a745 !important; /* Verde más vibrante */
            border-color: #28a745 !important;
            color: transparent !important; /* Ocultar texto */
        }
    </style> 


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
                            <label class="form-check-label" for="use_free_appointment">
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
    <script>
        // Función para actualizar la barra de progreso
        function updateProgressBar(currentStepIndex) {
            $('.progressbar li').removeClass('active completed');

            $('.progressbar li').each(function (index) {
                if (index < currentStepIndex) {
                    $(this).addClass('completed'); // Pasos completados
                } else if (index === currentStepIndex) {
                    $(this).addClass('active'); // Paso actual
                }
            });
        }

        var selectedServiceId;
        var selectedAttendantId;
        var selectedDateTime;
        var useFreeAppointment = 0; // Variable para almacenar si se usará cita gratuita

        $(document).ready(function() {
            // Configurar AJAX para incluir el token CSRF en los encabezados
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Función para inicializar el calendario
            function initializeCalendar() {
                var calendarEl = document.getElementById('calendar');

                var today = new Date();
                today.setHours(0, 0, 0, 0); // Asegurar que la hora es 00:00

                var calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'timeGridSevenDay', // Usar la vista personalizada
                    views: {
                        timeGridSevenDay: {
                            type: 'timeGrid',
                            duration: { days: 7 }, // Duración de 7 días
                            buttonText: '7 días'
                        }
                    },
                    locale: 'es',
                    selectable: true,
                    selectMirror: true,
                    allDaySlot: false, // Ocultar el espacio de "todo el día"
                    initialDate: today, // Fecha inicial: hoy
                    businessHours: {
                        daysOfWeek: [0, 1, 2, 3, 4, 5, 6], // Domingo a Sábado
                        startTime: '11:00',
                        endTime: '19:00', // 7:00 pm
                    },
                    slotMinTime: '11:00:00',
                    slotMaxTime: '19:00:00', // 7:00 pm
                    slotDuration: '01:00:00', // Intervalos de 1 hora
                    slotLabelInterval: '01:00', // Etiquetas cada 1 hora
                    slotLabelFormat: {
                        hour: 'numeric',
                        minute: '2-digit',
                        hour12: false // Formato de 24 horas
                    },
                    aspectRatio: 1.5, // Ajusta la proporción ancho/alto para hacerlo más compacto
                    contentHeight: 'auto', // Ajusta automáticamente la altura según el contenido
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: '' // Eliminar otros botones si los hay
                    },
                    validRange: {
                        start: today, // No permitir navegar a fechas anteriores a hoy
                    },
                    eventContent: function(arg) {
                        // Personalizar el contenido del evento para que no muestre texto
                        return { html: '<div></div>' };
                    },
                    events: function(fetchInfo, successCallback, failureCallback) {
                        $.ajax({
                            url: "{{ route('get.available.times') }}",
                            type: 'GET',
                            dataType: 'json',
                            data: {
                                professional_id: selectedAttendantId,
                                start: fetchInfo.startStr,
                                end: fetchInfo.endStr,
                            },
                            success: function(data) {
                                console.log('Horas disponibles:', data);
                                successCallback(data);
                            },
                            error: function(xhr, status, error) {
                                console.error('Error al obtener las horas disponibles:', error);
                                failureCallback(error);
                            }
                        });
                    },
                    eventClick: function(info) {
                        selectedDateTime = info.event.start;
                        console.log('Fecha y hora seleccionadas:', selectedDateTime);

                        // Resaltar el evento seleccionado
                        calendar.getEvents().forEach(function(event) {
                            event.setProp('classNames', ['available-slot']);
                        });
                        info.event.setProp('classNames', ['available-slot', 'selected']);

                        // Añadir log para verificar que el evento es de sábado
                        var dayOfWeek = selectedDateTime.getDay(); // 0: Domingo, 6: Sábado
                        console.log('Día de la semana seleccionado:', dayOfWeek); // 6 para Sábado
                    },
                    height: 'auto', // Ajustar automáticamente la altura del calendario
                });

                calendar.render();
            }

            // Evento para el cambio de servicio en Paso 1
            $('#service').on('change', function() {
                var serviceId = $(this).val();
                if (serviceId) {
                    $.ajax({
                        url: "{{ route('get.professionals', '') }}/" + serviceId,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            $('#attendant').empty();
                            if (data.length > 0) {
                                $('#no-professionals').hide();
                                $('#attendant').append('<option value="">Seleccionar...</option>');
                                $.each(data, function(key, value) {
                                    $('#attendant').append('<option value="' + value.usr_id + '">' + value.usr_nombre_completo + '</option>');
                                });
                            } else {
                                $('#attendant').append('<option value="">Seleccionar...</option>');
                                $('#no-professionals').show();
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error al obtener los profesionales:', error);
                            $('#attendant').empty();
                            $('#attendant').append('<option value="">Seleccionar...</option>');
                            $('#no-professionals').show();
                        }
                    });
                } else {
                    $('#attendant').empty();
                    $('#attendant').append('<option value="">Seleccionar...</option>');
                    $('#no-professionals').hide();
                }
            });

            // Evento para el botón "Siguiente" del Paso 1
            $('#to-step-2').on('click', function() {
                selectedServiceId = $('#service').val();
                selectedAttendantId = $('#attendant').val();
                useFreeAppointment = $('#use_free_appointment').is(':checked') ? 1 : 0;

                if (selectedServiceId && selectedAttendantId) {
                    // Pasar al paso 2
                    $('.step').removeClass('active');
                    $('#step-2').addClass('active');

                    // Actualizar la barra de progreso
                    updateProgressBar(1); // Índice del paso 2

                    // Inicializar el calendario
                    initializeCalendar();
                } else {
                    Swal.fire(
                        'Error',
                        'Por favor, selecciona un servicio y un profesional.',
                        'error'
                    );
                }
            });

            // Evento para el botón "Siguiente" del Paso 2
            $('#step-2 .next-step').on('click', function() {
                if (selectedDateTime) {
                    // Pasar al paso 3
                    $('.step').removeClass('active');
                    $('#step-3').addClass('active');

                    // Actualizar la barra de progreso
                    updateProgressBar(2); // Índice del paso 3

                    // Mostrar el resumen
                    $('#summary').html(`
                        <p><strong>Servicio:</strong> ${$('#service option:selected').text()}</p>
                        <p><strong>Profesional:</strong> ${$('#attendant option:selected').text()}</p>
                        <p><strong>Fecha:</strong> ${selectedDateTime.toLocaleDateString()}</p>
                        <p><strong>Hora:</strong> ${selectedDateTime.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}</p>
                        ${useFreeAppointment ? '<p><strong>Cita Gratuita:</strong> Sí</p>' : '<p><strong>Cita Gratuita:</strong> No</p>'}
                    `);
                } else {
                    Swal.fire(
                        'Error',
                        'Por favor, selecciona una fecha y hora para tu cita.',
                        'error'
                    );
                }
            });

            // Evento para el botón "Atrás" en Paso 2 y Paso 3
            $('.prev-step').on('click', function() {
                var currentStep = $(this).closest('.step').attr('id');
                $('.step').removeClass('active');

                if (currentStep === 'step-2') {
                    $('#step-1').addClass('active');
                    updateProgressBar(0); // Índice del paso 1
                } else if (currentStep === 'step-3') {
                    $('#step-2').addClass('active');
                    updateProgressBar(1); // Índice del paso 2
                }
            });

            // Evento para confirmar la reserva en Paso 3
            $('#confirmBooking').on('click', function() {
                if (!selectedDateTime) {
                    Swal.fire(
                        'Error',
                        'Por favor, selecciona una fecha y hora para tu cita.',
                        'error'
                    );
                    return;
                }

                // Preparar los datos a enviar
                var data = {
                    service_id: selectedServiceId,
                    professional_id: selectedAttendantId,
                    fecha: selectedDateTime.toISOString().split('T')[0], // Formato YYYY-MM-DD
                    hora: selectedDateTime.toTimeString().split(' ')[0], // Formato HH:MM:SS
                    use_free_appointment: useFreeAppointment, // 1 o 0
                };

                console.log('Datos a enviar:', data); // Añadir para depuración

                // Enviar solicitud AJAX para guardar la cita
                $.ajax({
                    url: "{{ route('citas.saveAppointment') }}",
                    type: 'POST',
                    dataType: 'json',
                    data: data,
                    success: function(response) {
                        if (response.success) {
                            // Pasar al paso 4
                            $('.step').removeClass('active');
                            $('#step-4').addClass('active');

                            // Actualizar la barra de progreso
                            updateProgressBar(3); // Índice del paso 4

                            // Mostrar mensaje de confirmación
                            $('#confirmationMessage').html(`
                                <div class="alert alert-success">
                                    ${response.success}
                                </div>
                            `);

                            // Actualizar el saldo de puntos de fidelidad
                            if(response.userPoints !== undefined){
                                $('#pointsSection').html(`
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title">Puntos de Fidelidad</h5>
                                            <p class="card-text">
                                                <strong>Saldo Actual:</strong> ${response.userPoints} puntos
                                            </p>
                                            ${response.userPoints >= 100 ? `
                                                <div class="alert alert-info">
                                                    ¡Felicidades! Has acumulado ${response.userPoints} puntos. Tienes derecho a una <strong>cita gratuita</strong>.
                                                </div>
                                            ` : `
                                                <p>Acumula 100 puntos para obtener una cita gratuita.</p>
                                            `}
                                        </div>
                                    </div>
                                `);
                            }
                        } else if (response.error) {
                            Swal.fire(
                                'Error',
                                response.error,
                                'error'
                            );
                        }
                    },
                    error: function(xhr) {
                        console.error('Estado de la respuesta:', xhr.status);
                        console.error('Respuesta del servidor:', xhr.responseText);
                        if (xhr.status === 422) {
                            // Errores de validación
                            var errors = xhr.responseJSON.error;
                            var errorMessages = '';
                            $.each(errors, function(key, value) {
                                errorMessages += value + '\n';
                            });
                            Swal.fire(
                                'Error de Validación',
                                errorMessages,
                                'error'
                            );
                        } else if (xhr.status === 409) {
                            // Conflicto: intervalo ya reservado
                            Swal.fire(
                                'Conflicto',
                                xhr.responseJSON.error,
                                'error'
                            );
                        } else if (xhr.status === 401) {
                            Swal.fire(
                                'No Autorizado',
                                'No estás autenticado. Por favor, inicia sesión.',
                                'error'
                            ).then(() => {
                                window.location.href = "{{ route('login') }}";
                            });
                        } else {
                            Swal.fire(
                                'Error',
                                'Ocurrió un error al confirmar la reserva. Por favor, inténtalo de nuevo.',
                                'error'
                            );
                        }
                    }
                });
            });

            // Evento para agregar más servicios en Paso 4
            $('#addService').on('click', function() {
                $('#addServiceModal').modal('show');
            });

            // Evento para el formulario de servicios adicionales
            $('#additional-services-form').on('submit', function(e) {
                e.preventDefault();
                // Lógica para agregar más servicios
                // Por ahora, simplemente cerramos el modal y actualizamos la interfaz
                var additionalServiceId = $('#additional-service').val();
                var additionalAttendantId = $('#additional-attendant').val();
                var additionalTime = $('#additional-time').val();

                if(additionalServiceId && additionalAttendantId && additionalTime){
                    // Aquí deberías implementar la lógica para agregar el servicio adicional
                    // Por ejemplo, enviar una solicitud AJAX al servidor para manejar la adición de servicios
                    // Después de agregar, actualizar el resumen y puntos si es necesario

                    // Simulación de éxito
                    $('#addServiceModal').modal('hide');
                    Swal.fire(
                        'Éxito',
                        'Servicio adicional agregado exitosamente.',
                        'success'
                    ).then(() => {
                        // Opcional: Actualizar el resumen de la reserva
                        $('#summary').append(`
                            <p><strong>Servicio Adicional:</strong> ${$('#additional-service option:selected').text()}</p>
                            <p><strong>Profesional:</strong> ${$('#additional-attendant option:selected').text()}</p>
                            <p><strong>Hora:</strong> ${additionalTime}</p>
                        `);
                        // Opcional: Actualizar puntos de fidelidad si aplica
                    });
                } else {
                    Swal.fire(
                        'Error',
                        'Por favor, completa todos los campos para agregar el servicio adicional.',
                        'error'
                    );
                }
            });

            // Función para manejar la selección de cita gratuita en Paso 1
            $('#use_free_appointment').on('change', function() {
                if ($(this).is(':checked')) {
                    Swal.fire(
                        'Atención',
                        'Al usar una cita gratuita, se deducirán 100 puntos de tu saldo.',
                        'info'
                    );
                }
            });
        });
    </script>

@endsection
