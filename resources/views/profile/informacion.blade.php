@extends('layouts.app')

@section('title', 'Detalles del Cliente')

@section('content')
    <div class="container my-5">
        <h2 class="mb-4">Detalles del Cliente</h2>

        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <strong>Información de {{ $cliente->usr_nombre_completo }}</strong>
            </div>
            <div class="card-body">
                <!-- Foto del Cliente -->
                <div class="mb-3 text-center">
                    @if($cliente->usr_foto_perfil)
                        <img src="{{ asset('storage/' . $cliente->usr_foto_perfil) }}" alt="Foto de {{ $cliente->usr_nombre_completo }}" class="img-thumbnail" style="max-width: 200px;">
                    @else
                        <img src="{{ asset('images/default-avatar.png') }}" alt="Foto por Defecto" class="img-thumbnail" style="max-width: 200px;">
                    @endif
                </div>

                <!-- Información del Cliente -->
                <p><strong>Nombre Completo:</strong> {{ $cliente->usr_nombre_completo }}</p>
                <p><strong>Email:</strong> {{ $cliente->usr_correo_electronico }}</p>
                <p><strong>Teléfono:</strong> {{ $cliente->usr_telefono }}</p>
            </div>
        </div>

        <!-- Botón para Volver a Mis Citas -->
        <a href="{{ route('my-appointments') }}" class="btn btn-secondary">Volver a Mis Citas</a>
    </div>
@endsection
