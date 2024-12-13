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

                    <form id="register-form" action="{{ route('register.store') }}" method="POST" autocomplete="on">
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

                        <div style="max-width: 400px; margin: 50px auto;">
                            <label id="card-element-label">Método de Pago:</label>
                            <div id="card-element" aria-labelledby="card-element-label" style="border: 1px solid #ced4da; border-radius: 4px; padding: 10px; background-color: #fff;">
                            </div>
                            <div id="card-errors" role="alert" class="text-danger mt-2"></div>
                        </div>
                        
                        

                        

                        <button id="submit-button" class="btn shadow-2 col-md-12 text-uppercase mt-4" type="submit">Register</button>
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

@push('scripts')
<script src="https://js.stripe.com/v3/"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const stripe = Stripe('{{ $stripeKey }}');
            const elements = stripe.elements();
            const cardElement = elements.create('card', {
                style: {
                    base: {
                        color: '#32325d',
                        fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                        fontSmoothing: 'antialiased',
                        fontSize: '16px',
                        '::placeholder': {
                            color: '#a0aec0'
                        }
                    },
                    invalid: {
                        color: '#fa755a',
                        iconColor: '#fa755a'
                    }
                }
            });
            cardElement.mount('#card-element');

            // Manejar errores en tiempo real
            cardElement.on('change', function(event) {
                const displayError = document.getElementById('card-errors');
                if (event.error) {
                    displayError.textContent = event.error.message;
                } else {
                    displayError.textContent = '';
                }
            });

            // Manejar el envío del formulario
            const form = document.getElementById('register-form');
            const submitButton = document.getElementById('submit-button');

            form.addEventListener('submit', async function(event) {
                event.preventDefault();
                submitButton.disabled = true;

                const { setupIntent, error } = await stripe.confirmCardSetup(
                    '{{ $clientSecret }}',
                    {
                        payment_method: {
                            card: cardElement,
                            billing_details: {
                                name: document.getElementById('usr_nombre_completo').value,
                                email: document.getElementById('usr_correo_electronico').value,
                            },
                        },
                    }
                );

                if (error) {
                    // Mostrar error en el frontend
                    const errorElement = document.getElementById('card-errors');
                    errorElement.textContent = error.message;
                    submitButton.disabled = false;
                } else {
                    // Enviar el Payment Method ID al servidor junto con el formulario
                    const hiddenInput = document.createElement('input');
                    hiddenInput.setAttribute('type', 'hidden');
                    hiddenInput.setAttribute('name', 'payment_method_id');
                    hiddenInput.setAttribute('value', setupIntent.payment_method);
                    form.appendChild(hiddenInput);

                    // Enviar el formulario
                    form.submit();
                }
            });
        });
    </script>
@endpush
