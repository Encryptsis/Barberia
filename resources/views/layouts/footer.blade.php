<!-- resources/views/partials/footer.blade.php -->

<footer>
    <div class="footer-content">
        <div class="text-content">
            <span>PROPUESTA DE DISEÑO</span>
            <span>POR ALAN LONGORIA</span>
            <!-- Mostrar el enlace "Buscar Trabajo" solo si el usuario no tiene ciertos roles -->
            @if (auth()->guest() || !in_array(auth()->user()->role->rol_nombre, ['Barbero', 'Facialista', 'Administrador']))
                <a href="{{ route('work.index') }}" class="footer-link">Job openings</a>
            @endif
             <!-- Enlace a la Política de Privacidad -->
             <a href="{{ route('privacy') }}" class="footer-link">Política de Privacidad</a>
        </div>
        <img src="{{ Vite::asset('resources/images/logoO.jpeg') }}" alt="logo" class="footer-image"/>
    </div>
</footer>
