@extends('layouts.app')

@section('title', 'Detalles del Cliente')

@section('content')
    <div class="container my-5">
        <!-- Título de la Página -->
        <h2 class="mb-4 text-center">Detalles del Cliente</h2>

        <!-- Tarjeta de Perfil del Cliente -->
        <div class="card shadow-lg">
            <div class="row g-0">
                <!-- Columna de la Foto del Cliente -->
                <div class="col-md-4 d-flex align-items-center justify-content-center p-4 bg-light">
                    @if($cliente->usr_foto_perfil)
                        <!-- Foto de Perfil del Cliente -->
                        <img src="{{ asset('storage/' . $cliente->usr_foto_perfil) }}" alt="Foto de {{ $cliente->usr_nombre_completo }}" class="img-fluid rounded-circle border border-3 hover-shadow" style="max-width: 200px;">
                    @else
                        <!-- Imagen por Defecto si no hay foto de perfil -->
                        <img src="{{ asset('images/default-avatar.png') }}" alt="Foto por Defecto" class="img-fluid rounded-circle border border-3 hover-shadow" style="max-width: 200px;">
                    @endif
                </div>
                <!-- Columna de la Información del Cliente -->
                <div class="col-md-8">
                    <div class="card-body">
                        <!-- Nombre del Cliente -->
                        <h4 class="card-title fw-bold mb-3">{{ $cliente->usr_nombre_completo }}</h4>
                       
                        <!-- Información de Email -->
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-envelope-fill me-3 text-primary" style="font-size: 1.5rem;"></i>
                            <div>
                                <strong>Email:</strong>
                                <p class="mb-0">{{ $cliente->usr_correo_electronico }}</p>
                            </div>
                        </div>
                        <!-- Información de Teléfono -->
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-telephone-fill me-3 text-success" style="font-size: 1.5rem;"></i>
                            <div>
                                <strong>Teléfono:</strong>
                                <p class="mb-0">{{ $cliente->usr_telefono }}</p>
                            </div>
                        </div>
                        <!-- Puedes agregar más información aquí si es necesario -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Botón para Volver a Mis Citas -->
        <div class="text-center mt-4">
            <a href="{{ route('my-appointments') }}" class="btn btn-secondary btn-lg">
                <i class="bi bi-arrow-left-circle me-2"></i> Volver a Mis Citas
            </a>
        </div>
    </div>
@endsection
