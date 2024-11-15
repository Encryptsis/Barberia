<!-- resources/views/partials/header.blade.php -->

<div class="info-bar ">
    <span>MON - SUN: 11.00 A.M. - 08.00 P.M.</span>
    <div class="social-icons">
        <a href="https://www.facebook.com/wilddeerbarbershopandbar?_rdr"><i class="fab fa-facebook-f"></i></a>
        <a href="https://www.instagram.com/wilddeerbarbershopandbar/"><i class="fab fa-instagram"></i></a>
        <a href="https://www.tiktok.com/@wilddeerbarbershop"><i class="fab fa-tiktok"></i></a>
    </div>
</div>

<nav class="navbar navbar-expand-lg">
    <a class="navbar-brand ms-3" href="{{ route('home') }}">WILD DEER BARBERSHOP & BAR</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav">
            <!-- Enlace Home, siempre visible -->
            <li class="nav-item {{ Request::routeIs('home') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('home') }}">Home</a>
            </li>
            
            @if (Auth::check())
                <!-- Enlaces visibles solo para usuarios autenticados -->
                <li class="nav-item {{ Request::routeIs('perfil.usuario') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('perfil.usuario') }}">Profile</a>
                </li>
                <li class="nav-item {{ Request::routeIs('agenda.usuario') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('agenda.usuario') }}">Agenda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('my.appointments') }}">Mis Citas</a>
                </li>
                <!-- Enlace para cerrar sesión -->
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('logout') }}"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            @else
                <!-- Enlaces visibles solo para invitados -->
                <li class="nav-item {{ Request::routeIs('login') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('login') }}">Login</a>
                </li>
                <li class="nav-item me-3 {{ Request::routeIs('register') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('register') }}">Sign Up</a>
                </li>
            @endif
        </ul>
    </div>
</nav>
