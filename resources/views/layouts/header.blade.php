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
            <li class="nav-item">
                <a class="nav-link {{ Request::routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a>
            </li>

            @guest
                <!-- Enlaces visibles solo para invitados -->
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('login') ? 'active' : '' }}" href="{{ route('login') }}">Login</a>
                </li>
                <li class="nav-item me-3">
                    <a class="nav-link {{ Request::routeIs('register') ? 'active' : '' }}" href="{{ route('register') }}">Sign Up</a>
                </li>
            @else
                <!-- Enlaces visibles solo para usuarios autenticados -->
                <li class="nav-item">
                    <a 
                        class="nav-link {{ Request::routeIs('perfil.usuario*') ? 'active' : '' }}" 
                        href="{{ route('perfil.usuario', ['username' => auth()->user()->usr_username]) }}"
                    >
                        Profile
                    </a>
                </li>

                @php
                    // Obtener el rol del usuario autenticado
                    $role = Auth::user()->role->rol_nombre;
                @endphp

                @if($role === 'Cliente')
                    <li class="nav-item">
                        <a class="nav-link {{ Request::routeIs('agendar.usuario') ? 'active' : '' }}" href="{{ route('agendar.usuario') }}">Book An Appointment</a>
                    </li>
                @endif

                @if(Auth::check())
                    <li class="nav-item">
                        <a class="nav-link {{ Request::routeIs('my-appointments') ? 'active' : '' }}" href="{{ route('my-appointments') }}">My Appointments</a>
                    </li>
                @endif

                @if($role === 'Administrador')
                    <li class="nav-item">
                        <a class="nav-link {{ Request::routeIs('AllSchedules.index') ? 'active' : '' }}" href="{{ route('AllSchedules.index') }}">All Schedules</a>
                    </li>
                @endif

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
            @endguest
        </ul>
    </div>
</nav>

