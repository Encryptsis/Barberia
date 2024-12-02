@extends('layouts.app')

@section('title', 'Mis Citas')

@section('content')
    @php
        // Obtener el rol del usuario actual
        $userRole = Auth::check() ? Auth::user()->role->rol_nombre : null;

        // Determinar si el usuario es un trabajador
        $isWorker = in_array($userRole, ['Administrador', 'Barbero', 'Facialista']);

        // Determinar si el usuario es un cliente
        $isClient = $userRole === 'Cliente';
    @endphp

    <!-- Contenedor Principal -->
    <section class="secciones page-background" style="margin-top: 3.5rem;">
        <div class="container my-5">
            <!-- Ajuste del Espaciado del Encabezado -->
            <h2 class="mt-5 mb-4 text-center">Mis Citas</h2>

            @if($isClient)
                <!-- Sección de Puntos de Fidelidad -->
                <div class="mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Puntos de Fidelidad</h5>
                            <p class="card-text">
                                <strong>Saldo Actual:</strong> {{ $userPoints }} puntos
                            </p>
                            @if($userPoints >= 100)
                                <div class="alert alert-info">
                                    ¡Felicidades! Has acumulado {{ $userPoints }} puntos. Tienes derecho a un <strong>corte de cabello gratuito</strong>.
                                </div>
                            @else
                                <p>Acumula 100 puntos para obtener un corte de cabello gratuito.</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- Sección de Límites de Citas -->
            <div class="mb-4">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <strong>Límites de Citas</strong>
                    </div>
                    <div class="card-body">
                        <p><strong>Límite Global de Citas Activas:</strong> {{ $remainingGlobalAppointments }} de {{ $globalLimit->limite_diario }}</p>
                        <ul>
                            @foreach($categoryLimits as $limit)
                                @php
                                    $categoryName = $limit->categoria_servicio->cat_nombre;
                                    $remaining = $remainingCategoryAppointments->get($limit->cat_id, 0);
                                @endphp
                                <li><strong>{{ $categoryName }}:</strong> {{ $remaining }} de {{ $limit->limite_diario }}</li>
                            @endforeach
                        </ul>
                        @if($remainingGlobalAppointments <= 1)
                            <div class="alert alert-warning mt-3">
                                Estás cerca de alcanzar el límite global de citas activas.
                            </div>
                        @endif

                        @foreach($categoryLimits as $limit)
                            @php
                                $categoryName = $limit->categoria_servicio->cat_nombre;
                                $remaining = $remainingCategoryAppointments->get($limit->cat_id, 0);
                            @endphp
                            @if($remaining <= 1)
                                <div class="alert alert-warning mt-2">
                                    Te queda {{ $remaining }} cita para la categoría <strong>{{ $categoryName }}</strong>.
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Mostrar mensajes de éxito o error -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>
            @endif

            @if(session('success_delete'))
                <div class="alert alert-primary alert-dismissible fade show" role="alert">
                    {{ session('success_delete') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
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
                                @if($isWorker)
                                    <th>Cliente</th>
                                @else
                                    <th>Profesional</th>
                                @endif
                                <th>Fecha</th>
                                <th>Hora</th>
                                <th>Estado</th>
                                <th>Costo</th>
                                <th>Acciones</th>
                                <!-- Nueva Columna para Confirmar Llegada Solo para Trabajadores -->
                                @if($isWorker)
                                    <th>Confirmar Llegada</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($citas as $citaItem)
                                <tr>
                                    <td>{{ $citaItem->servicios->pluck('srv_nombre')->join(', ') }}</td>
                                    
                                    @if($isWorker)
                                        <td>
                                            @if($citaItem->cliente)
                                                {{ $citaItem->cliente->usr_nombre_completo }}
                                            @else
                                                <span class="badge bg-secondary">Sin Cliente</span>
                                            @endif
                                        </td>
                                    @else
                                        <td>
                                           
                                            @if($citaItem->profesional)
                                                {{ $citaItem->profesional->usr_nombre_completo }}
                                            @else
                                                <span class="badge bg-secondary">Sin Profesional</span>
                                            @endif
                                        </td>
                                    @endif

                                    <td>{{ \Carbon\Carbon::parse($citaItem->cta_fecha)->format('d/m/Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($citaItem->cta_hora)->format('H:i') }}</td>
                                    <td>
                                        @if($citaItem->estadoCita->estado_nombre == 'Confirmada')
                                            <span class="badge bg-success">{{ $citaItem->estadoCita->estado_nombre }}</span>
                                        @elseif($citaItem->estadoCita->estado_nombre == 'Pendiente')
                                            <span class="badge bg-warning">{{ $citaItem->estadoCita->estado_nombre }}</span>
                                        @elseif($citaItem->estadoCita->estado_nombre == 'Cancelada')
                                            <span class="badge bg-secondary">{{ $citaItem->estadoCita->estado_nombre }}</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $citaItem->estadoCita->estado_nombre }}</span>
                                        @endif
                                    </td>
                                    
                                    <td>
                                        @if($citaItem->cta_is_free)
                                            GRATIS
                                        @else
                                            @php
                                                $totalCosto = $citaItem->servicios->sum('srv_precio');
                                            @endphp
                                            {{ number_format($totalCosto, 2) }} $
                                        @endif
                                    </td>
                                    <td>
                                        @if($isWorker)
                                            @if($citaItem->estadoCita->estado_nombre == 'Pendiente')
                                                <!-- Botón para Confirmar -->
                                                <form action="{{ route('appointments.confirm', $citaItem->cta_id) }}" method="POST" style="display:inline-block;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success">Confirmar</button>
                                                </form>
                                            
                                                <!-- Botón para Rechazar -->
                                                <form action="{{ route('appointments.reject', $citaItem->cta_id) }}" method="POST" style="display:inline-block;">
                                                    @csrf
                                                    <input type="hidden" name="action" value="reject">
                                                    <button type="submit" class="btn btn-sm btn-danger">Rechazar</button>
                                                </form>
                                            @elseif($citaItem->estadoCita->estado_nombre == 'Confirmada')
                                                <!-- Opciones para citas confirmadas -->
                                                <a href="{{ route('citas.edit', $citaItem->cta_id) }}" class="btn btn-sm btn-warning">Editar</a>
                                                
                                                <!-- Botón para Cancelar -->
                                                <form action="{{ route('appointments.reject', $citaItem->cta_id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas cancelar esta cita?');" style="display:inline-block;">
                                                    @csrf
                                                    <input type="hidden" name="action" value="cancel">
                                                    <button type="submit" class="btn btn-sm btn-danger">Cancelar</button>
                                                </form>
                                            @elseif($citaItem->estadoCita->estado_nombre == 'Cancelada')
                                                <!-- Opciones para citas canceladas -->
                                                <button type="button" class="btn btn-sm btn-warning" disabled title="Esta cita ha sido cancelada y no se puede editar">Editar</button>
                                                <button type="button" class="btn btn-sm btn-danger" disabled title="Esta cita ha sido cancelada y no se puede cancelar nuevamente">Cancelar</button>
                                            @endif
                                        @else
                                            @if($citaItem->estadoCita->estado_nombre == 'Pendiente' || $citaItem->estadoCita->estado_nombre == 'Confirmada')
                                                <!-- Acciones para clientes con estado pendiente o confirmada -->
                                                <a href="{{ route('citas.edit', $citaItem->cta_id) }}" class="btn btn-sm btn-warning">Editar</a>
                                                
                                                <!-- Botón para Cancelar -->
                                                <form action="{{ route('appointments.reject', $citaItem->cta_id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas cancelar esta cita?');" style="display:inline-block;">
                                                    @csrf
                                                    <input type="hidden" name="action" value="cancel">
                                                    <button type="submit" class="btn btn-sm btn-danger">Cancelar</button>
                                                </form>
                                            @elseif($citaItem->estadoCita->estado_nombre == 'Cancelada')
                                                <!-- Opciones para citas canceladas -->
                                                <button type="button" class="btn btn-sm btn-warning" disabled title="Esta cita ha sido cancelada y no se puede editar">Editar</button>
                                                <button type="button" class="btn btn-sm btn-danger" disabled title="Esta cita ha sido cancelada y no se puede cancelar nuevamente">Cancelar</button>
                                            @endif
                                        @endif
                                    </td>
                                    
                                    <!-- Nueva Celda para Confirmar Llegada Solo para Trabajadores -->
                                    @if($isWorker)
                                        <td>
                                            @if(
                                                !$citaItem->cta_arrival_confirmed &&
                                                $citaItem->estadoCita->estado_nombre == 'Confirmada'
                                            )
                                                <!-- Botón para Confirmar Llegó Temprano -->
                                                <button 
                                                    class="btn btn-sm btn-success confirm-arrival" 
                                                    data-id="{{ $citaItem->cta_id }}" 
                                                    data-status="on_time"
                                                >
                                                    Llegó Temprano
                                                </button>
                                                
                                                <!-- Botón para Confirmar Llegó Tarde -->
                                                <button 
                                                    class="btn btn-sm btn-warning confirm-arrival" 
                                                    data-id="{{ $citaItem->cta_id }}" 
                                                    data-status="late"
                                                >
                                                    Llegó Tarde / No llegó
                                                </button>
                                            @else
                                                <!-- Mostrar estado de confirmación si ya fue confirmado -->
                                                @if($citaItem->cta_arrival_confirmed)
                                                    @if($citaItem->cta_punctuality_status == 'on_time')
                                                        <span class="badge bg-success">Llegó Temprano</span>
                                                    @elseif($citaItem->cta_punctuality_status == 'late')
                                                        <span class="badge bg-warning">Llegó Tarde</span>
                                                    @endif
                                                @else
                                                    <!-- Opcionalmente, puedes mostrar un mensaje o dejar el espacio en blanco -->
                                                    <span class="text-muted">No disponible</span>
                                                @endif
                                            @endif
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            <!-- Paginación -->
            <div class="d-flex justify-content-center mt-4">
                {{ $citas->links() }}
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        window.appointmentsData = {
            csrfToken: '{{ csrf_token() }}',
            confirmArrivalUrl: '{{ route("appointments.confirmArrival", ":id") }}'
        };
    </script>

    <!-- Incluir el archivo JS compilado con Vite -->
    @vite('resources/js/user_appointments.js')
@endpush
