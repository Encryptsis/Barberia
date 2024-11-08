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
    </style>

    <section class="secciones" style="margin-top: 3.5rem;">
        <h2 class="titulo-secciones">Barber Profile</h2>
        <div class="text-center">
     
            <img src="{{ Vite::asset('resources/images/baber3.jpeg') }}" alt="Foto del Cliente" class="foto-barbero" id="clienteFoto"/>
        </div>
        <div class="info-card">
            <div class="static-info" id="nombreCliente">
                <strong>Name:</strong> Juan Pérez
            </div>
            <div class="static-info" id="correoCliente">
                <strong>E-mail:</strong> juan.perez@example.com
            </div>
            <div class="static-info" id="telefonoCliente">
                <strong>Phone:</strong> +34 123 456 789
            
            <button class="btn-edit" id="editarPerfil">
                <i class="fas fa-edit"></i> Editar Perfil
            </button>
        </div>

        <!-- Formulario de edición -->
        <div class="edit-form" id="editForm">
            <h3 class="text-center">Edit Profile</h3>
            <form id="formEdit">
                <div class="mb-3">
                    <label for="nombreInput">Name:</label>
                    <input type="text" class="form-control" id="nombreInput" placeholder="Ingrese el nombre" value="Juan Pérez">
                </div>
                <div class="mb-3">
                    <label for="correoInput">E-mail:</label>
                    <input type="email" class="form-control" id="correoInput" placeholder="Ingrese el correo electrónico" value="juan.perez@example.com">
                </div>
                <div class="mb-3">
                    <label for="telefonoInput">Phone:</label>
                    <input type="tel" class="form-control" id="telefonoInput" placeholder="Ingrese el número de teléfono" value="+34 123 456 789">
                </div>
                <div class="mb-3">
                    <label for="fotoInput">Photo:</label>
                    <input type="file" class="form-control" id="fotoInput">
                </div>
                <button type="button" class="btn btn-primary" id="guardarCambios">Save</button>
                <button type="button" class="btn btn-secondary" id="cancelarEdicion">Cancel</button>
            </form>
        </div>
    </section>


   
    <div class="estrella" id="estrella">&#9733;</div> <!-- Estrella brillante -->

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('editarPerfil').addEventListener('click', function() {
            document.getElementById('editForm').style.display = 'block'; // Mostrar el formulario
        });

        document.getElementById('guardarCambios').addEventListener('click', function() {
            const nombre = document.getElementById('nombreInput').value;
            const correo = document.getElementById('correoInput').value;
            const telefono = document.getElementById('telefonoInput').value;
            const foto = document.getElementById('fotoInput').files[0];

            // Actualizar la información en la tarjeta
            document.getElementById('nombreCliente').innerHTML = `<strong>Nombre del Cliente:</strong> ${nombre}`;
            document.getElementById('correoCliente').innerHTML = `<strong>Correo Electrónico:</strong> ${correo}`;
            document.getElementById('telefonoCliente').innerHTML = `<strong>Teléfono:</strong> ${telefono}`;

            // Si se ha seleccionado una nueva foto, actualizarla
            if (foto) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('clienteFoto').src = e.target.result; // Cambiar la imagen
                }
                reader.readAsDataURL(foto);
            }

            // Ocultar el formulario después de guardar
            document.getElementById('editForm').style.display = 'none';
        });

        document.getElementById('cancelarEdicion').addEventListener('click', function() {
            document.getElementById('editForm').style.display = 'none'; // Ocultar el formulario
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Verificar si el premio está disponible
            if (localStorage.getItem('premioDisponible') === 'true') {
                document.getElementById('estrella').style.display = 'block'; // Mostrar estrella
                localStorage.removeItem('premioDisponible'); // Eliminar estado para que no vuelva a aparecer
            }
        });
    </script>

@endsection

@push('scripts')
    @vite(['resources/js/index.js', 'resources/js/preloader.js'])
@endpush