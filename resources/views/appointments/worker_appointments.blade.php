<!-- resources/views/agenda/mi_agenda.blade.php -->

@extends('layouts.app')

@section('title', 'Mi Agenda')

@section('content')
<div class="container my-5">
    <h2 class="mb-4 text-center">Mi Agenda</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <!-- Listado de Citas -->
    @if($citas->isEmpty())
        <p class="text-center">No tienes citas programadas.</p>
    @else
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Servicio</th>
                        <th>Cliente</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                        <th>Confirmar Llegada</th> <!-- Nueva columna añadida -->
                    </tr>
                </thead>
                <tbody>
                    @foreach($citas as $citaItem)
                        <tr>
                            <td>{{ $citaItem->servicios->pluck('srv_nombre')->join(', ') }}</td>
                            <td>
                                @if($citaItem->cta_is_free)
                                    <span class="badge bg-info">Gratis</span>
                                @elseif($citaItem->cliente)
                                    {{ $citaItem->cliente->usr_nombre_completo }}
                                @else
                                    <span class="badge bg-secondary">Sin Cliente</span>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($citaItem->cta_fecha)->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($citaItem->cta_hora)->format('H:i') }}</td>
                            <td>
                                @if($citaItem->cta_is_free)
                                    <span class="badge bg-info">Gratis</span>
                                @elseif($citaItem->estadoCita->estado_nombre == 'Confirmada')
                                    <span class="badge bg-success">{{ $citaItem->estadoCita->estado_nombre }}</span>
                                @elseif($citaItem->estadoCita->estado_nombre == 'Cancelada')
                                    <span class="badge bg-danger">{{ $citaItem->estadoCita->estado_nombre }}</span>
                                @else
                                    <span class="badge bg-secondary">{{ $citaItem->estadoCita->estado_nombre }}</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('citas.edit', $citaItem->cta_id) }}" class="btn btn-sm btn-warning">Editar</a>
                                
                                <form action="{{ route('citas.destroy', $citaItem->cta_id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta cita?');" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                                </form>
                            </td>
                            <td>
                                @if(!$citaItem->cta_arrival_confirmed && !$citaItem->cta_is_free)
                                    <!-- Botón para Confirmar Llegó a Tiempo -->
                                    <button 
                                        class="btn btn-sm btn-success confirm-arrival" 
                                        data-id="{{ $citaItem->cta_id }}" 
                                        data-status="on_time"
                                    >
                                        Llegó a Tiempo
                                    </button>
                                    
                                    <!-- Botón para Confirmar Llegó con Retraso -->
                                    <button 
                                        class="btn btn-sm btn-secondary confirm-arrival" 
                                        data-id="{{ $citaItem->cta_id }}" 
                                        data-status="late"
                                    >
                                        Llegó con Retraso
                                    </button>
                                @else
                                    <!-- Mostrar estado de confirmación si ya fue confirmado -->
                                    @if($citaItem->cta_arrival_confirmed)
                                        @if($citaItem->cta_punctuality_status == 'on_time')
                                            <span class="badge bg-success">Llegó a Tiempo</span>
                                        @elseif($citaItem->cta_punctuality_status == 'late')
                                            <span class="badge bg-warning">Llegó con Retraso</span>
                                        @endif
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

</div>
@endsection

@push('scripts')
    <!-- Incluir SweetAlert2 para mejores alertas (opcional) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Incluir jQuery desde una CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        // Configurar el CSRF token para todas las solicitudes AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });

        $(document).ready(function(){
            console.log('mi_agenda script loaded'); // Log para verificar que el script se está ejecutando

            $('.confirm-arrival').on('click', function(){
                console.log('confirm-arrival button clicked'); // Log para verificar que se hace clic en el botón

                var citaId = $(this).data('id');
                var status = $(this).data('status');

                Swal.fire({ // Usando SweetAlert para una mejor experiencia
                    title: 'Confirmar Llegada',
                    text: "¿Estás seguro de confirmar la llegada como " + (status === 'on_time' ? 'Puntual' : 'Retrasado') + "?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, confirmar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route("citas.confirmArrival", ":id") }}'.replace(':id', citaId),
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
            });
        });
    </script>
@endpush
