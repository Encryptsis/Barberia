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
            selectable: false, // Desactivar selección de rangos
            selectMirror: true,
            allDaySlot: false, // Ocultar el espacio de "todo el día"
            initialDate: today, // Fecha inicial: hoy
            businessHours: {
                daysOfWeek: [0, 1, 2, 3, 4, 5, 6], // Domingo a Sábado
                startTime: '11:00',
                endTime: '21:00', // 9:00 pm
            },
            slotMinTime: '11:00:00',
            slotMaxTime: '21:00:00', // 9:00 pm
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
                    url: window.citasRoutes.getAvailableTimes,
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        professional_id: selectedAttendantId,
                        start: fetchInfo.startStr,
                        end: fetchInfo.endStr,
                    },
                    success: function(data) {
                        console.log('Horas disponibles y ocupadas:', data);
                        successCallback(data);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error al obtener las horas disponibles y ocupadas:', error);
                        failureCallback(error);
                    }
                });
            },
            eventClick: function(info) {
                var classNames = info.event.classNames;

                // Agregar log para verificar las clases del evento
                console.log('Clases del evento:', classNames);

                var isBooked = classNames.includes('booked-slot');

                if (isBooked) {
                    Swal.fire(
                        'Horario Ocupado',
                        'Este horario ya está reservado. Por favor, elige otro horario.',
                        'error'
                    );
                    return; // No hacer nada más si está ocupado
                }

                // Obtener la fecha y hora en la zona horaria local
                var eventStart = new Date(info.event.start);
                selectedDateTime = eventStart;

                console.log('Fecha y hora seleccionadas:', selectedDateTime);

                // Remover la clase 'selected' de todos los eventos
                calendar.getEvents().forEach(function(event) {
                    if(event.classNames.includes('selected')) {
                        // Crear una nueva lista de clases sin 'selected'
                        var newClassNames = event.classNames.filter(c => c !== 'selected');
                        event.setProp('classNames', newClassNames);
                    }
                });

                // Añadir la clase 'selected' al evento actual
                var newClasses = info.event.classNames.slice(); // Clonar array
                if(!newClasses.includes('selected')) {
                    newClasses.push('selected');
                    info.event.setProp('classNames', newClasses);
                }

                // Opcional: Mostrar detalles de la cita seleccionada
                // Puedes agregar más lógica aquí si lo deseas
            },
            height: 'auto', // Ajustar automáticamente la altura del calendario
        });

        calendar.render();
    }

    // Evento para el cambio de servicio en Paso 1
    $('#service').on('change', function() {
        var serviceId = $(this).val();
        if (serviceId) {
            var url = window.citasRoutes.getProfessionals.replace(':service_id', serviceId);
            console.log('URL para obtener profesionales:', url);
            $.ajax({
                url: url,
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
            fecha: selectedDateTime.toLocaleDateString('en-CA'), // Formato YYYY-MM-DD en zona local
            hora: selectedDateTime.toLocaleTimeString('en-GB', { hour12: false }), // Formato HH:MM:SS en zona local
            use_free_appointment: useFreeAppointment, // 1 o 0
        };

        console.log('Datos a enviar:', data); // Añadir para depuración

        // Enviar solicitud AJAX para guardar la cita
        $.ajax({
            url: window.citasRoutes.saveAppointment,
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
                        window.location.href = window.citasRoutes.login;
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

