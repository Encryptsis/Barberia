<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- Configuración básica del documento -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mi Aplicación Laravel')</title>
    
        <!-- Bootstrap CSS via CDN -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
        <!-- Bootstrap Icons via CDN -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
        
        <!-- CSS Personalizado -->
        @vite(['resources/css/acceso.css'])
    

</head>
<body class="page-background">

    <!-- Contenido Principal -->
    <div>
        @yield('content')
    </div>
    
    @stack('scripts')
</body>
</html>
