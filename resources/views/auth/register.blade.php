
@extends('layouts.auth')

@section('title', 'Perfil')

@section('content')

    <div class="wrapper">
        <div class="auth-content">
            <div class="card">
                <div class="card-body">
                    <div class="mb-4">
                        <p class="titulo">Welcome, Create Your Account</p>
                    </div>
                    <form action="../../index.html" method="POST" autocomplete="on">
                        
                        <div class="form-group">
                            <label for="usuario">User:</label>
                            <input type="text" class="form-control" name="usuario" required>
                        </div>

                        <div class="form-group">
                            <label for="clave">Password:</label>
                            <input type="password" class="form-control" name="clave" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="nombre_cliente">Name:</label>
                            <input type="text" class="form-control" name="usuario" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="numero_cliente">Phone:</label>
                            <input type="text" class="form-control" name="usuario" required>
                        </div>
                        <div class="form-group">
                            <label for="correo_cliente">E-mail:</label>
                            <input type="text" class="form-control" name="usuario" required>
                        </div>

                        <input class="btn shadow-2 col-md-12 text-uppercase mt-4" type="submit" name="accion" value="Get Into">
                    </form>
                    
                    <hr>
                    
                    <div class="mt-1">
                        <div class="row">
                            <p style="display: inline; margin: 0;">Do you already have an account?</p>
                            <a href="login.html" class="registro-link"> Login</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @endsection

  