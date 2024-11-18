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

    @if($citas->isEmpty())
        <p class="text-center">No tienes citas programadas.</p>
    @else
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Servicio</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($citas as $cita)
                        <tr>
                            <td>{{ $cita->cliente->usr_nombre_completo }}</td>
                            <td>{{ \Carbon\Carbon::parse($cita->cta_fecha)->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($cita->cta_hora)->format('H:i') }}</td>
                            <td>{{ $cita->servicios->pluck('srv_nombre')->join(', ') }}</td>
                            <td>
                                @if($cita->estadoCita->estado_nombre == 'Confirmada')
                                    <span class="badge bg-success">{{ $cita->estadoCita->estado_nombre }}</span>
                                @elseif($cita->estadoCita->estado_nombre == 'Cancelada')
                                    <span class="badge bg-danger">{{ $cita->estadoCita->estado_nombre }}</span>
                                @else
                                    <span class="badge bg-secondary">{{ $cita->estadoCita->estado_nombre }}</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('mi.agenda.edit', $cita->cta_id) }}" class="btn btn-primary btn-sm">Editar</a>
                                
                                <form action="{{ route('mi.agenda.destroy', $cita->cta_id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar esta cita?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
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
