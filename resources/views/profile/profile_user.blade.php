@extends('layouts.app')

@section('title', 'Perfil')

@section('content')
    <style>
        body {
            background-color: #f8f9fa;
            color: white;
        }
        .titulo-secciones {
            margin-top: 2rem;
            text-align: center;
        }
        .foto-barbero {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            margin-bottom: 1rem;
        }
        .info-card {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 2rem;
            backdrop-filter: blur(10px);
            margin: 0 auto;
            max-width: 400px;
        }
        .static-info {
            font-size: 1.2rem;
            margin-bottom: 1rem;
            text-align: center;
        }
        .info-bar, .navbar, footer {
            color: white;
        }
        .btn-edit {
            display: block;
            margin: 1rem auto;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            color: white;
            padding: 10px 20px;
            font-size: 1rem;
            cursor: pointer;
        }
        .btn-edit i {
            margin-right: 5px;
        }
        .edit-form {
            display: none; /* Ocultar el formulario por defecto */
            margin-top: 2rem;
            background: rgba(255, 255, 255, 0.1);
            padding: 1.5rem;
            border-radius: 10px;
            backdrop-filter: blur(10px);
        }
        .estrella {
            display: none; /* Ocultar inicialmente */
            position: fixed;
            top: 20px;
            right: 20px;
            font-size: 50px;
            color: gold; /* Color de la estrella */
            animation: brillar 1s infinite alternate;
        }

        @keyframes brillar {
            0% { opacity: 1; }
            100% { opacity: 0.5; }
        }
        /* Estilos para las alertas */
        .alert {
            max-width: 600px;
            margin: 20px auto;
            text-align: center;
        }
    </style>

    <section class="secciones" style="margin-top: 3.5rem;">
        <h2 class="titulo-secciones">Welcome, {{ $usuario->usr_nombre_completo }}</h2>
        
        <!-- Flash messages -->
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

        <div class="text-center">
            @if($usuario->usr_foto_perfil && Storage::disk('public')->exists($usuario->usr_foto_perfil))
                <img src="{{ Storage::url($usuario->usr_foto_perfil) }}?v={{ filemtime(storage_path('app/public/' . $usuario->usr_foto_perfil)) }}" alt="Foto del Usuario" class="foto-barbero" id="clienteFoto"/>
            @else
                <img src="{{ Vite::asset('resources/images/sinfoto.jpg') }}" alt="Foto del Usuario" class="foto-barbero" id="clienteFoto"/>
            @endif
        </div>
        <div class="info-card">
            <div class="static-info" id="nombreCliente">
                <strong>Name:</strong> {{ $usuario->usr_nombre_completo }}
            </div>
            <div class="static-info" id="correoCliente">
                <strong>E-mail:</strong> {{ $usuario->usr_correo_electronico }}
            </div>
            <div class="static-info" id="telefonoCliente">
                <strong>Phone:</strong> {{ $usuario->usr_telefono }}
            </div>
            <button class="btn-edit" id="editarPerfil">
                <i class="fas fa-edit"></i> Editar Perfil
            </button>
        </div>

        <div class="edit-form" id="editForm">
            <h3 class="text-center">Edit Profile</h3>
            <form action="{{ route('perfil.actualizar', ['username' => $usuario->usr_username]) }}" method="POST" enctype="multipart/form-data" id="formEdit">
                @csrf
                <!-- Campos de Perfil -->
                <div class="mb-3">
                    <label for="usr_nombre_completo" class="form-label">Name:</label>
                    <input 
                        type="text" 
                        class="form-control @error('usr_nombre_completo') is-invalid @enderror" 
                        id="usr_nombre_completo" 
                        name="usr_nombre_completo" 
                        value="{{ old('usr_nombre_completo', $usuario->usr_nombre_completo) }}" 
                        required 
                    >
                    @error('usr_nombre_completo')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="usr_correo_electronico" class="form-label">E-mail:</label>
                    <input 
                        type="email" 
                        class="form-control @error('usr_correo_electronico') is-invalid @enderror" 
                        id="usr_correo_electronico" 
                        name="usr_correo_electronico" 
                        value="{{ old('usr_correo_electronico', $usuario->usr_correo_electronico) }}" 
                        required 
                    >
                    @error('usr_correo_electronico')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="usr_telefono" class="form-label">Phone:</label>
                    <input 
                        type="tel" 
                        class="form-control @error('usr_telefono') is-invalid @enderror" 
                        id="usr_telefono" 
                        name="usr_telefono" 
                        value="{{ old('usr_telefono', $usuario->usr_telefono) }}" 
                    >
                    @error('usr_telefono')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="usr_foto_perfil" class="form-label">Photo:</label>
                    <input 
                        type="file" 
                        class="form-control @error('usr_foto_perfil') is-invalid @enderror" 
                        id="usr_foto_perfil" 
                        name="usr_foto_perfil"
                    >
                    @error('usr_foto_perfil')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Campos de Método de Pago -->
                <h3 class="text-center" style="margin-top:2rem;">Actualizar Método de Pago</h3>
                <input type="hidden" name="new_payment_method_id" id="new_payment_method_id" value="">
                <div id="card-element" class="form-control" style="background-color: white; color:#000; padding:10px;"></div>
                <div id="card-errors" role="alert" style="color:red; margin-top:10px;"></div>

                <button 
                    type="submit" 
                    class="btn btn-primary"
                    id="guardarCambios"
                    style="margin-top:10px;"
                >
                    Save
                </button>
                <button 
                    type="button" 
                    class="btn btn-secondary" 
                    id="cancelarEdicion"
                >
                    Cancel
                </button>
            </form>
        </div>
    </section>

    <div class="estrella" id="estrella">&#9733;</div> <!-- Estrella brillante -->

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        // Mostrar y ocultar el formulario de edición
        document.getElementById('editarPerfil').addEventListener('click', function() {
            document.getElementById('editForm').style.display = 'block'; // Mostrar el formulario
        });

        document.getElementById('cancelarEdicion').addEventListener('click', function() {
            document.getElementById('editForm').style.display = 'none'; // Ocultar el formulario
        });

        // Mostrar la estrella si el premio está disponible
        document.addEventListener('DOMContentLoaded', function() {
            if (localStorage.getItem('premioDisponible') === 'true') {
                document.getElementById('estrella').style.display = 'block'; // Mostrar estrella
                localStorage.removeItem('premioDisponible'); // Eliminar estado para que no vuelva a aparecer
            }
        });

        // Configuración de Stripe Elements
        document.addEventListener('DOMContentLoaded', function() {
            const stripe = Stripe('{{ config('stripe.key') }}');
            const elements = stripe.elements();
            const cardElement = elements.create('card', { style: { base: { color: '#000' } } });
            cardElement.mount('#card-element');

            const form = document.getElementById('formEdit');
            const clientSecret = '{{ $setupIntent->client_secret }}';

            form.addEventListener('submit', async (e) => {
                e.preventDefault();

                // Confirmar el SetupIntent con la tarjeta ingresada
                const { setupIntent, error } = await stripe.confirmCardSetup(clientSecret, {
                    payment_method: {
                        card: cardElement,
                        billing_details: {
                            name: '{{ $usuario->usr_nombre_completo }}',
                            email: '{{ $usuario->usr_correo_electronico }}'
                        }
                    }
                });

                if (error) {
                    // Mostrar el error en la interfaz
                    const cardErrors = document.getElementById('card-errors');
                    cardErrors.textContent = error.message;
                } else {
                    // Obtener el payment_method_id y asignarlo al campo oculto
                    document.getElementById('new_payment_method_id').value = setupIntent.payment_method;

                    // Enviar el formulario
                    form.submit();
                }
            });
        });
    </script>
@endsection
