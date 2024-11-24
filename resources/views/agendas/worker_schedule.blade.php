@extends('layouts.app')

@section('title', 'Agendas de Trabajadores')

@section('content')
    <div class="container my-5">
        <h2 class="mb-4 text-center">Agendas de Trabajadores</h2>

        @foreach($workers as $worker)
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5>{{ $worker->usr_nombre_completo }}</h5>
                    <p>Rol: {{ ucfirst($worker->role->rol_nombre) }}</p>
                </div>
                <div class="card-body">
                    @if($worker->citasProfesional->isEmpty())
                        <p>No tiene citas programadas.</p>
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
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($worker->citasProfesional as $cita)
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
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@endsection
