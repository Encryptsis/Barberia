<!-- resources/views/layouts/app.blade.php -->

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- Configuración básica del documento -->
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mi Aplicación Laravel')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <!-- Vincular CSS y JS con Vite -->
    @vite(['resources/css/generales.css', 'resources/css/preloader.css', 'resources/js/app.js'])

    <!-- Otros estilos específicos de cada vista -->
    @stack('styles')

    <style>
        /* Asegura que html y body ocupen el 100% de la altura */
        html, body {
            height: 100%;
            margin: 0;
        }

        /* Wrapper Flexbox */
        .flex-wrapper {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Contenido Principal */
        .content {
            flex: 1;
        }

        /* Footer Styling */
        footer {
            background-color: #000000; /* Color de fondo del footer */
            color: white; /* Color del texto */
        }

        .footer-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px 30px;
        }

        .footer-image {
            max-width: 100px;
            height: auto;
        }

        .text-content span {
            display: block;
        }
    </style>
</head>
<body class="page-background" >
    <div class="flex-wrapper">
        <!-- Cabecera -->
        @include('partials.header')
        
        <!-- Contenido Principal -->
        <main class="content">
            @yield('content')
        </main>

        <!-- Pie de Página -->
        @include('partials.footer')
    </div>

    <!-- Cargar jQuery desde CDN (si es necesario) -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    
    <!-- Bootstrap JS Bundle (incluye Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
   
    <!-- Vincular JS General con Vite -->
    @vite(['resources/js/index.js'])

    <!-- Scripts adicionales (si es necesario) -->
    @stack('scripts')
</body>
</html>
