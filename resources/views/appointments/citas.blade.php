<!-- resources/views/appointments/citas.blade.php -->

@extends('layouts.app')

@section('title', 'Mis Citas')

@section('content')
    <!-- Contenedor Principal -->
    <div class="container my-5">
        <div class="mb-7"></div> <!-- Espaciador vacío -->
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

    </div>
@endsection
