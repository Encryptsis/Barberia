import $ from 'jquery';

$(document).ready(function() {
    // Evento para actualizar los profesionales según el servicio seleccionado en el formulario de edición
    $('#service').on('change', function() {
        var serviceId = $(this).val();
        if (serviceId) {
            $.ajax({
                url: window.appointmentsEditData.getProfessionalsUrl + '/' + serviceId,
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
