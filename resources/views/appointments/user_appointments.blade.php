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

            <!-- Sección de Límites de Citas - Solo Visible para Clientes -->
            @if($isClient)
                <div class="mb-4">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <strong>Límites de Citas</strong>
                        </div>
                        <div class="card-body">
                            <!-- Límite Global de Citas Activas -->
                            @if($globalLimit)
                                <p>
                                    <strong>Límite Global de Citas Activas:</strong> 
                                    {{ $activeGlobalAppointmentsCount }} de {{ $globalLimit->limite_diario }}
                                </p>
                                
                                @if($remainingGlobalAppointments <= 1 && $remainingGlobalAppointments > 0)
                                    <div class="alert alert-warning mt-3" role="alert">
                                        Estás cerca de alcanzar el límite global de citas activas.
                                    </div>
                                @elseif($remainingGlobalAppointments == 0)
                                    <div class="alert alert-danger mt-3" role="alert">
                                        Has alcanzado el límite global de citas activas.
                                    </div>
                                @endif
                            @else
                                <p class="text-danger">No se ha configurado un límite global de citas.</p>
                            @endif

                            <!-- Lista de Límites por Categoría -->
                            @if($categoryLimits->isNotEmpty())
                                <ul class="list-group list-group-flush">
                                    @foreach($categoryLimits as $catId => $limit)
                                        @php
                                            $categoryName = $limit->categoria_servicio->cat_nombre;
                                            $active = $activeCategoryAppointmentsCount->get((int) $limit->cat_id, 0);
                                            $remaining = $remainingCategoryAppointments->get((int) $limit->cat_id, 0);
                                        @endphp
                                        <li class="list-group-item">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <strong>{{ $categoryName }}:</strong> 
                                                    {{ $active }} de {{ $limit->limite_diario }}
                                                </div>
                                               @if($remaining == 0)
                                                    <span class="badge bg-danger text-white">Límite alcanzado</span>
                                                @endif
                                            </div>
                                            
                                            @if($remaining == 1 || $remaining == 2)
                                                <div class="alert alert-warning mt-2 mb-0" role="alert">
                                                    Te queda {{ $remaining }} cita para la categoría <strong>{{ $categoryName }}</strong>.
                                                </div>
                                            @elseif($remaining == 0)
                                                <div class="alert alert-danger mt-2 mb-0" role="alert">
                                                    Has alcanzado el límite de citas para la categoría <strong>{{ $categoryName }}</strong>.
                                                </div>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-muted">No hay límites de citas por categoría configurados.</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- Formulario de Filtros -->
            <div class="mb-4">
                <form method="GET" action="{{ route('my-appointments') }}">
                    <div class="row mb-3">
                        <!-- Filtro de Estado (Múltiple) -->
                        <div class="col-md-3">
                            <label for="estado" class="form-label">Estado</label>
                            <select name="estado[]" id="estado" class="form-select" multiple>
                                @foreach($estados as $estado)
                                    <option value="{{ $estado->estado_id }}" 
                                        {{ in_array($estado->estado_id, request('estado', [])) ? 'selected' : '' }}>
                                        {{ $estado->estado_nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Filtro de Fecha (Exacta) -->
                        <div class="col-md-3">
                            <label for="fecha" class="form-label">Fecha (exacta)</label>
                            <input type="date" name="fecha" id="fecha" class="form-control" value="{{ request('fecha') }}">
                        </div>

                        <!-- Filtro de Servicio -->
                        <div class="col-md-3">
                            <label for="servicio_id" class="form-label">Servicio</label>
                            <select name="servicio_id" id="servicio_id" class="form-select">
                                <option value="">Todos</option>
                                @foreach($servicios as $servicio)
                                    <option value="{{ $servicio->srv_id }}" 
                                        {{ request('servicio_id') == $servicio->srv_id ? 'selected' : '' }}>
                                        {{ $servicio->srv_nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Filtro de Cliente o Profesional -->
                        @if($isWorker)
                            <div class="col-md-3">
                                <label for="cliente_id" class="form-label">Cliente</label>
                                <select name="cliente_id" id="cliente_id" class="form-select">
                                    <option value="">Todos</option>
                                    @foreach($clientes as $cliente)
                                        <option value="{{ $cliente->usr_id }}" 
                                            {{ request('cliente_id') == $cliente->usr_id ? 'selected' : '' }}>
                                            {{ $cliente->usr_nombre_completo }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <div class="col-md-3">
                                <label for="profesional_id" class="form-label">Profesional</label>
                                <select name="profesional_id" id="profesional_id" class="form-select">
                                    <option value="">Todos</option>
                                    @foreach($profesionales as $profesional)
                                        <option value="{{ $profesional->usr_id }}" 
                                            {{ request('profesional_id') == $profesional->usr_id ? 'selected' : '' }}>
                                            {{ $profesional->usr_nombre_completo }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                    </div>

                    <div class="row mb-3">
                        <!-- Ordenar por -->
                        <div class="col-md-3">
                            <label for="sort_by" class="form-label">Ordenar por</label>
                            <select name="sort_by" id="sort_by" class="form-select">
                                <option value="cta_fecha" {{ request('sort_by') == 'cta_fecha' ? 'selected' : '' }}>Fecha</option>
                                <option value="estado" {{ request('sort_by') == 'estado' ? 'selected' : '' }}>Estado</option>
                                <option value="costo" {{ request('sort_by') == 'costo' ? 'selected' : '' }}>Costo</option>
                            </select>
                        </div>

                        <!-- Dirección de Ordenamiento -->
                        <div class="col-md-3">
                            <label for="sort_dir" class="form-label">Dirección</label>
                            <select name="sort_dir" id="sort_dir" class="form-select">
                                <option value="asc" {{ request('sort_dir', 'asc') == 'asc' ? 'selected' : '' }}>Ascendente</option>
                                <option value="desc" {{ request('sort_dir') == 'desc' ? 'selected' : '' }}>Descendente</option>
                            </select>
                        </div>

                        <!-- Botón de Filtrar y Limpiar -->
                        <div class="col-md-6 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">Filtrar</button>
                            <a href="{{ route('my-appointments') }}" class="btn btn-secondary">Limpiar</a>
                        </div>
                    </div>
                </form>
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
                                                <a href="{{ route('informacion.show', $citaItem->cliente->usr_id) }}">
                                                    {{ $citaItem->cliente->usr_nombre_completo }}
                                                </a>
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
                                        @elseif($citaItem->estadoCita->estado_nombre == 'Expirada')
                                            <span class="badge bg-danger">{{ $citaItem->estadoCita->estado_nombre }}</span>
                                        @elseif($citaItem->estadoCita->estado_nombre == 'Completada')
                                            <span class="badge bg-info text-dark">{{ $citaItem->estadoCita->estado_nombre }}</span>
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
                                                <!-- Verificar si la llegada ha sido confirmada -->
                                                @if($citaItem->cta_arrival_confirmed)
                                                    <!-- Botón para Completar Cita -->
                                                    <form action="{{ route('appointments.complete', $citaItem->cta_id) }}" method="POST" style="display:inline-block;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-primary">Completar Cita</button>
                                                    </form>
                                                @else
                                                    <!-- Mostrar un mensaje indicando que se debe confirmar la llegada -->
                                                    <span class="text-muted">Confirma la llegada para completar</span>
                                                @endif
                                                <!-- Opciones para citas confirmadas -->
                                                <a href="{{ route('citas.edit', $citaItem->cta_id) }}" class="btn btn-sm btn-warning">Editar</a>
                                                
                                                <!-- Botón para Cancelar -->
                                                <form action="{{ route('appointments.reject', $citaItem->cta_id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas cancelar esta cita?');" style="display:inline-block;">
                                                    @csrf
                                                    <input type="hidden" name="action" value="cancel">
                                                    <button type="submit" class="btn btn-sm btn-danger">Cancelar</button>
                                                </form>
                                            @elseif($citaItem->estadoCita->estado_nombre == 'Expirada')
                                                <!-- Cita expirada: no mostrar botones -->
                                                <span class="text-muted">Cita expirada</span>
                                            @elseif($citaItem->estadoCita->estado_nombre == 'Cancelada')
                                                <!-- Opciones para citas canceladas -->
                                                <!-- No se muestran botones -->
                                            @endif
                                        @else
                                            @if(in_array($citaItem->estadoCita->estado_nombre, ['Pendiente', 'Confirmada']))
                                                
                                                @php
                                                    $appointmentDateTime = \Carbon\Carbon::parse($citaItem->cta_fecha . ' ' . $citaItem->cta_hora);
                                                    $now = \Carbon\Carbon::now();
                                                    $diffInHours = $now->floatDiffInHours($appointmentDateTime, false);
                                                @endphp

                                                @if($diffInHours > 3)
                                                    <!-- Mostrar botón Editar habilitado -->
                                                    <a href="{{ route('citas.edit', $citaItem->cta_id) }}" class="btn btn-sm btn-warning">Editar</a>
                                                @else
                                                    <!-- Mostrar botón Editar deshabilitado con tooltip explicativo -->
                                                    <button 
                                                        class="btn btn-sm btn-warning" 
                                                        disabled 
                                                        title="No puedes editar la cita a menos de 3 horas de su inicio"
                                                    >
                                                        Editar
                                                    </button>
                                                @endif        

                                                <!-- Botón para Cancelar siempre visible -->
                                                <form action="{{ route('appointments.reject', $citaItem->cta_id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas cancelar esta cita?');" style="display:inline-block;">
                                                    @csrf
                                                    <input type="hidden" name="action" value="cancel">
                                                    <button type="submit" class="btn btn-sm btn-danger">Cancelar</button>
                                                </form>
                                            @elseif($citaItem->estadoCita->estado_nombre == 'Expirada')
                                                <!-- Cita expirada: no mostrar botones -->
                                                <span class="text-muted">Cita expirada</span>
                                            @elseif($citaItem->estadoCita->estado_nombre == 'Cancelada')
                                                <!-- Opciones para citas canceladas -->
                                                <!-- No se muestran botones -->
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
