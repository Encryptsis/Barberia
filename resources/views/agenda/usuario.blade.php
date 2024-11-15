@extends('layouts.app')

@section('title', 'Agenda')

@section('content')
    <!-- Inclusión de CSS de FullCalendar -->
    <link href='https://unpkg.com/fullcalendar@5.10.1/main.min.css' rel='stylesheet' />

    <style>
        /* Estilos existentes */
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
    <!-- Incluir jQuery desde CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>


        var selectedServiceId;
        var selectedAttendantId;
        var selectedDateTime;

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
                        event.setProp('classNames', []);
                    });
                    info.event.setProp('classNames', ['available-slot', 'selected']);


                        // Añadir log para verificar que el evento es de sábado
                        var dayOfWeek = selectedDateTime.getDay(); // 0: Domingo, 6: Sábado
                        console.log('Día de la semana seleccionado:', dayOfWeek); // 6 para Sábado

                        // No mostrar alerta, ya que el texto está oculto
                    },
                    height: 'auto', // Ajustar automáticamente la altura del calendario
                });

                calendar.render();
            }

            // Evento para el cambio de servicio
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

                if (selectedServiceId && selectedAttendantId) {
                    // Pasar al paso 2
                    $('.step').removeClass('active');
                    $('#step-2').addClass('active');

                    // Actualizar la barra de progreso
                    $('.progressbar li').eq(1).addClass('active');

                    // Inicializar el calendario
                    initializeCalendar();
                } else {
                    alert('Por favor, selecciona un servicio y un profesional.');
                }
            });

            // Evento para el botón "Atrás" en el Paso 2 y Paso 3
            $('.prev-step').on('click', function() {
                var currentStep = $(this).closest('.step').attr('id');
                $('.step').removeClass('active');

                if (currentStep === 'step-2') {
                    $('#step-1').addClass('active');
                    $('.progressbar li').eq(1).removeClass('active');
                } else if (currentStep === 'step-3') {
                    $('#step-2').addClass('active');
                    $('.progressbar li').eq(2).removeClass('active');
                }
            });

            // Evento para el botón "Siguiente" del Paso 2
            $('#step-2 .next-step').on('click', function() {
                if (selectedDateTime) {
                    // Pasar al paso 3
                    $('.step').removeClass('active');
                    $('#step-3').addClass('active');

                    // Actualizar la barra de progreso
                    $('.progressbar li').eq(2).addClass('active');

                    // Mostrar el resumen
                    $('#summary').html(`
                        <p><strong>Servicio:</strong> ${$('#service option:selected').text()}</p>
                        <p><strong>Profesional:</strong> ${$('#attendant option:selected').text()}</p>
                        <p><strong>Fecha:</strong> ${selectedDateTime.toLocaleDateString()}</p>
                        <p><strong>Hora:</strong> ${selectedDateTime.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}</p>
                    `);
                } else {
                    alert('Por favor, selecciona una fecha y hora para tu cita.');
                }
            });

            // Evento para confirmar la reserva
            $('#confirmBooking').on('click', function() {
                if (!selectedDateTime) {
                    alert('Por favor, selecciona una fecha y hora para tu cita.');
                    return;
                }

                // Preparar los datos a enviar
                var data = {
                    service_id: selectedServiceId,
                    professional_id: selectedAttendantId,
                    fecha: selectedDateTime.toISOString().split('T')[0], // Formato YYYY-MM-DD
                    hora: selectedDateTime.toTimeString().split(' ')[0], // Formato HH:MM:SS
                };

                console.log('Datos a enviar:', data); // Añadir para depuración

                // Enviar solicitud AJAX para guardar la cita
                $.ajax({
                    url: "{{ route('save.appointment') }}",
                    type: 'POST',
                    dataType: 'json',
                    data: data,
                    success: function(response) {
                        if (response.success) {
                            // Pasar al paso 4
                            $('.step').removeClass('active');
                            $('#step-4').addClass('active');

                            // Actualizar la barra de progreso
                            $('.progressbar li').eq(3).addClass('active');
                            $('.progressbar li').eq(2).removeClass('active');

                            // Mostrar mensaje de confirmación
                            $('#confirmationMessage').html(`
                                <div class="alert alert-success">
                                    ${response.success}
                                </div>
                            `);
                        } else if (response.error) {
                            alert(response.error);
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
                            alert(errorMessages);
                        } else if (xhr.status === 409) {
                            // Conflicto: intervalo ya reservado
                            alert(xhr.responseJSON.error);
                        } else if (xhr.status === 401) {
                            alert('No estás autenticado. Por favor, inicia sesión.');
                            window.location.href = "{{ route('login') }}";
                        } else {
                            alert('Ocurrió un error al guardar la cita. Por favor, inténtalo de nuevo.');
                        }
                    }


                });
            });

            // Evento para agregar más servicios (si es necesario)
            $('#addService').on('click', function() {
                $('#addServiceModal').modal('show');
            });

            // Evento para el formulario de servicios adicionales
            $('#additional-services-form').on('submit', function(e) {
                e.preventDefault();
                // Lógica para agregar más servicios
                // Por ahora, simplemente cerramos el modal
                $('#addServiceModal').modal('hide');
                alert('Servicio adicional agregado exitosamente.');
            });

        });
    </script>

@endsection
