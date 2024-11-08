<!-- resources/views/auth/login.blade.php -->

@extends('layouts.auth')

@section('title', 'Perfil')

@section('content')


    <div class="wrapper">
        <div class="auth-content">
            <div class="card">
                <div class="card-body">
                    <div class="mb-4">
                        <p class="titulo text-center">Welcome, Log in</p>
                    </div>
                    
                    <form action="perfil_cliente.html" method="POST" autocomplete="on">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="usuario" class="form-label">User:</label>
                            <input 
                                type="text" 
                                class="form-control @error('usuario') is-invalid @enderror" 
                                id="usuario" 
                                name="usuario" 
                                value="{{ old('usuario') }}" 
                                required 
                                autofocus
                            >
                            @error('usuario')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="clave" class="form-label">Password:</label>
                            <input 
                                type="password" 
                                class="form-control @error('clave') is-invalid @enderror" 
                                id="clave" 
                                name="clave" 
                                required
                            >
                            @error('clave')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <button 
                            type="submit" 
                            class="btn shadow-2 col-md-12 text-uppercase mt-4 btn-primary"
                            id="btnSubmit"
                        >
                            Get Into
                        </button>
                    </form>
                    
                    <hr>
                    
                    <div class="mt-3 text-center">
                        <p class="mb-0">Do you already have an account?</p>
                        <a href="registro.html" class="registro-link"> Register</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
