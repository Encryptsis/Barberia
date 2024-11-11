
@extends('layouts.auth')

@section('title', 'Register')

@section('content')

    <div class="wrapper">
        <div class="auth-content">
            <div class="card">
                <div class="card-body">
                    <div class="mb-4">
                        <p class="titulo">Welcome, Create Your Account</p>
                    </div>

                    <!-- Mostrar mensajes de éxito -->
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Mostrar errores de validación -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif                    

                    <form action="{{ route('register.store') }}" method="POST" autocomplete="on">
                        @csrf <!-- Protección contra CSRF -->

                        <div class="form-group">
                            <label for="usr_username">Username:</label>
                            <input type="text" class="form-control" name="usr_username" id="usr_username" value="{{ old('usr_username') }}" required>
                        </div>

                        <div class="form-group">
                            <label for="usr_password">Password:</label>
                            <input type="password" class="form-control" name="usr_password" id="usr_password" required>
                        </div>

                        <div class="form-group">
                            <label for="usr_password_confirmation">Confirm Password:</label>
                            <input type="password" class="form-control" name="usr_password_confirmation" id="usr_password_confirmation" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="usr_nombre_completo">Name:</label>
                            <input type="text" class="form-control" name="usr_nombre_completo" id="usr_nombre_completo" value="{{ old('usr_nombre_completo') }}" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="usr_telefono">Phone:</label>
                            <input type="text" class="form-control" name="usr_telefono" id="usr_telefono" value="{{ old('usr_telefono') }}">
                        </div>
                        <div class="form-group">
                            <label for="usr_correo_electronico">E-mail:</label>
                            <input type="email" class="form-control" name="usr_correo_electronico" id="usr_correo_electronico" value="{{ old('usr_correo_electronico') }}" required>
                        </div>

                        <input class="btn shadow-2 col-md-12 text-uppercase mt-4" type="submit" name="accion" value="Register">
                    </form>
                    
                    <hr>
                    
                    <div class="mt-1">
                        <div class="row">
                            <p style="display: inline; margin: 0;">Do you already have an account?</p>
                            <a href="{{ route('login') }}" class="registro-link"> Login</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @endsection

  