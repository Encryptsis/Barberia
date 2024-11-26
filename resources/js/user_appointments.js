// Configurar el CSRF token para todas las solicitudes AJAX
import $ from 'jquery';
import Swal from 'sweetalert2';

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': window.appointmentsData.csrfToken
            }
        });

        $(document).ready(function(){
            console.log('citas script loaded'); // Log para verificar que el script se está ejecutando

            /**
             * Función para confirmar la llegada del cliente
             * @param {number} citaId - ID de la cita
             * @param {string} status - Estado de puntualidad ('on_time' o 'late')
             */
            function confirmArrival(citaId, status) {
                Swal.fire({
                    title: 'Confirmar Llegada',
                    text: "¿Estás seguro de confirmar que el cliente llegó " + (status === 'on_time' ? 'temprano' : 'tarde') + "?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, confirmar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const url = window.appointmentsData.confirmArrivalUrl.replace(':id', citaId);
                        $.ajax({
                            url: url,
                            type: 'POST',
                            data: {
                                punctuality_status: status
                            },
                            success: function(response){
                                Swal.fire(
                                    '¡Confirmado!',
                                    response.success,
                                    'success'
                                ).then(() => {
                                    location.reload();
                                });
                            },
                            error: function(xhr){
                                if(xhr.responseJSON && xhr.responseJSON.error){
                                    Swal.fire(
                                        'Error',
                                        xhr.responseJSON.error,
                                        'error'
                                    );
                                } else {
                                    Swal.fire(
                                        'Error',
                                        'Ocurrió un error al confirmar la llegada.',
                                        'error'
                                    );
                                }
                            }
                        });
                    }
                });
            }

            // Asignar el evento click a los botones de confirmación de llegada
            $('.confirm-arrival').on('click', function(){
                var citaId = $(this).data('id');
                var status = $(this).data('status');
                confirmArrival(citaId, status);
            });
        });
  