<!-- resources/views/layouts/app.blade.php -->

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- Configuración básica del documento -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mi Aplicación Laravel')</title>
    
    <!-- Vincular CSS y JS con Vite -->
    @vite(['resources/css/generales.css', 'resources/css/preloader.css', 'resources/js/app.js'])

    <!-- Otros estilos específicos de cada vista -->
    @stack('styles')
</head>
<body class="page-background">
    <!-- Cabecera -->
    @include('partials.header')
    
    <!-- Contenido Principal -->
    <div>
        @yield('content')
    </div>

    <!-- Pie de Página -->
    @include('partials.footer')

    <!-- Cargar jQuery desde CDN (si es necesario) -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    
    <!-- Vincular JS General con Vite -->
    @vite(['resources/js/index.js'])

    <!-- Scripts adicionales (si es necesario) -->
    @stack('scripts')
</body>
</html>
