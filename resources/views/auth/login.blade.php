@extends('layouts.auth')

@section('title', 'Login')

@section('content')


    <div class="wrapper">
        <div class="auth-content">
            <div class="card">
                <div class="card-body">
                    <div class="mb-4">
                        <p class="titulo text-center">Welcome, Log in</p>
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

                    
                    <form action="{{ route('login.submit') }}" method="POST" autocomplete="on">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="usr_username" class="form-label">Username:</label>
                            <input 
                                type="text" 
                                class="form-control @error('usr_username') is-invalid @enderror"
                                id="usr_username" 
                                name="usr_username"  
                                value="{{ old('usr_username') }}"
                                required 
                                autofocus
                            >
                            @error('usr_username')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="usr_password" class="form-label">Password:</label>
                            <input 
                                type="password" 
                                class="form-control @error('usr_password') is-invalid @enderror" 
                                id="usr_password" 
                                name="usr_password" 
                                required
                            >
                            @error('usr_password')
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
                        <p class="mb-0">Don't have an account yet?</p>
                        <a href="{{ route('register') }}" class="registro-link"> Register</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection