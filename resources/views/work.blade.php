<!-- resources/views/work/work.blade.php -->

@extends('layouts.app')

@section('title', 'Buscar Trabajo - WILD DEER')

@section('content')
    <section class="secciones" style="margin-top: 3.5rem;">
        <h2 class="titulo-secciones">JOB FORM</h2>
    
        <section id="cotizar" class="container my-5">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <!-- Contenedor del Formulario -->
                    <div class="quote-container">
                        <form action="https://formsubmit.co/jcflatworkexcavation@gmail.com" method="POST">
                            <!-- Protección CSRF (opcional si usas FormSubmit) -->
                            {{-- @csrf --}}
                            
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Name:</label>
                                <input type="text" name="name" id="name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="telefono" class="form-label">Phone Number:</label>
                                <input type="tel" name="phone_number" id="phone_number" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">E-mail:</label>
                                <input type="email" name="email" id="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="services" class="form-label">Service:</label>
                                <select class="form-select" name="services" id="services" required>
                                    <option selected disabled>Select a service</option>
                                    <option value="servicio1">BARBER</option>
                                    <option value="servicio2">RECEPTIONIST</option>
                                    <option value="servicio3">FACIAL</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="comments" class="form-label">Skills:</label>
                                <textarea name="comments" id="comments" class="form-control" cols="30" rows="5" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </section>

    
@endsection
