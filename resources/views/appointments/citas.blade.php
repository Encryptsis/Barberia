<!-- resources/views/appointments/citas.blade.php -->

@extends('layouts.app')

@section('title', 'Mis Citas')

@section('content')
    <!-- Contenedor Principal -->
    <div class="container my-5">
        <div class="mb-7"></div> <!-- Espaciador vacío -->
        <h2 class="mb-4 text-center">Mis Citas</h2>

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
                                <td>
                                    @if($citaItem->cta_is_free)
                                        <span class="badge bg-info">Gratis</span>
                                    @elseif($citaItem->profesional)
                                        {{ $citaItem->profesional->usr_nombre_completo }}
                                    @else
                                        <span class="badge bg-secondary">Sin Profesional</span>
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
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <!-- Historial de Transacciones de Puntos (Opcional) -->
        @if(isset($ptsTransactions) && !$ptsTransactions->isEmpty())
            <div class="mt-5">
                <h4>Historial de Puntos</h4>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-secondary">
                            <tr>
                                <th>Tipo</th>
                                <th>Cantidad</th>
                                <th>Descripción</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ptsTransactions as $transaction)
                                <tr>
                                    <td>{{ ucfirst($transaction->pts_type) }}</td>
                                    <td>{{ $transaction->pts_amount }}</td>
                                    <td>{{ $transaction->pts_description }}</td>
                                    <td>{{ \Carbon\Carbon::parse($transaction->pts_created_at)->format('d/m/Y H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

    </div>
@endsection
