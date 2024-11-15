<!-- resources/views/citas.blade.php -->

@extends('layouts.app')

@section('title', 'Mis Citas')

@section('content')
    <!-- Contenedor Principal -->
    <div class="container my-5">
        <h2 class="mb-4 text-center">Mis Citas</h2>

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

        <!-- Mostrar errores de validación (si hay una cita siendo editada) -->
        @if(isset($cita) && $errors->any())
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
        @if(isset($cita))
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <strong>Editar Cita</strong>
                </div>
                <div class="card-body">
                    <form action="{{ route('citas.update', $cita->cta_id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="service" class="form-label">Servicio</label>
                            <select class="form-select" id="service" name="service" required>
                                <option value="">Seleccionar...</option>
                                @foreach($servicios as $servicio)
                                    <option value="{{ $servicio->srv_id }}" {{ $cita->servicios->pluck('srv_id')->contains($servicio->srv_id) ? 'selected' : '' }}>
                                        {{ $servicio->srv_nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="attendant" class="form-label">Profesional</label>
                            <select class="form-select" id="attendant" name="attendant" required>
                                <option value="">Seleccionar...</option>
                                @foreach($profesionales as $profesional)
                                    <option value="{{ $profesional->usr_id }}" {{ $cita->cta_profesional_id == $profesional->usr_id ? 'selected' : '' }}>
                                        {{ $profesional->usr_nombre_completo }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="fecha" class="form-label">Fecha</label>
                            <input type="date" class="form-control" id="fecha" name="fecha" value="{{ $cita->cta_fecha }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="hora" class="form-label">Hora</label>
                            <input type="time" class="form-control" id="hora" name="hora" value="{{ \Carbon\Carbon::parse($cita->cta_hora)->format('H:i') }}" required>
                        </div>

                        <button type="submit" class="btn btn-success">Actualizar Cita</button>
                        <a href="{{ route('my.appointments') }}" class="btn btn-secondary">Cancelar</a>
                    </form>
                </div>
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
                            <th>Profesional</th>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($citas as $citaItem)
                            <tr>
                                <td>{{ $citaItem->servicios->pluck('srv_nombre')->join(', ') }}</td>
                                <td>{{ $citaItem->profesional->usr_nombre_completo }}</td>
                                <td>{{ \Carbon\Carbon::parse($citaItem->cta_fecha)->format('d/m/Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($citaItem->cta_hora)->format('H:i') }}</td>
                                <td>
                                    @if($citaItem->estadoCita->estado_nombre == 'Confirmada')
                                        <span class="badge bg-success">{{ $citaItem->estadoCita->estado_nombre }}</span>
                                    @elseif($citaItem->estadoCita->estado_nombre == 'Cancelada')
                                        <span class="badge bg-danger">{{ $citaItem->estadoCita->estado_nombre }}</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $citaItem->estadoCita->estado_nombre }}</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('citas.edit', $citaItem->cta_id) }}" class="btn btn-sm btn-warning">Editar</a>
                                    
                                    <!-- Botón para abrir el modal de eliminación -->
                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $citaItem->cta_id }}">
                                        Eliminar
                                    </button>

                                    <!-- Modal de Confirmación de Eliminación -->
                                    <div class="modal fade" id="deleteModal{{ $citaItem->cta_id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $citaItem->cta_id }}" aria-hidden="true">
                                      <div class="modal-dialog">
                                        <div class="modal-content">
                                          <form action="{{ route('citas.destroy', $citaItem->cta_id) }}" method="POST">
                                              @csrf
                                              @method('DELETE')
                                              <div class="modal-header">
                                                  <h5 class="modal-title" id="deleteModalLabel{{ $citaItem->cta_id }}">Confirmar Eliminación</h5>
                                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                              </div>
                                              <div class="modal-body">
                                                  ¿Estás seguro de que deseas eliminar esta cita?
                                              </div>
                                              <div class="modal-footer">
                                                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                  <button type="submit" class="btn btn-danger">Eliminar</button>
                                              </div>
                                          </form>
                                        </div>
                                      </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <!-- Botón para Volver al Inicio -->
        <div class="text-center">
            <a href="{{ route('home') }}" class="btn btn-primary mt-3">Volver al Inicio</a>
        </div>
    </div>

    <!-- Incluir jQuery y Bootstrap JS desde CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Scripts Personalizados -->
    <script>
        $(document).ready(function() {
            // Evento para actualizar los profesionales según el servicio seleccionado en el formulario de edición
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
                                $('#attendant').append('<option value="">Seleccionar...</option>');
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
                    $('#attendant').append('<option value="">Seleccionar...</option>');
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
