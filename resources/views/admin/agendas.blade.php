<!-- resources/views/admin/agendas.blade.php -->

@extends('layouts.app')

@section('title', 'Agendas de Trabajadores')

@section('content')
    <!-- Inclusión de CSS de FullCalendar -->
    <link href='https://unpkg.com/fullcalendar@5.10.1/main.min.css' rel='stylesheet' />

    <style>
        /* Estilos personalizados */
        body {
            margin: 0;
            font-family: Cambria, Georgia, serif;
            background-image: url("{{ Vite::asset('public/Imagenes/background.jpeg') }}"); 
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-position: center;
        } 

        .calendar-container {
            max-width: 900px;
            margin: 40px auto;
        }

        .calendar {
            margin-bottom: 50px;
        }

        .fc-event {
            cursor: pointer;
        }
    </style>

    <section class="secciones" style="margin-top: 3.5rem;">
        <h2 class="titulo-secciones text-center text-white">Agendas de Trabajadores</h2>

        <!-- Mostrar mensajes de éxito o error -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        @endif

        @foreach($trabajadores as $trabajador)
            <div class="calendar-container">
                <h3 class="text-white text-center mb-3">{{ $trabajador->usr_nombre_completo }}</h3>
                <div id='calendar-{{ $trabajador->usr_id }}' class="calendar"></div>
            </div>
        @endforeach
    </section>

    <!-- Incluir el script de FullCalendar -->
    <script src='https://unpkg.com/fullcalendar@5.10.1/main.min.js'></script>
    <!-- Incluir jQuery desde CDN si es necesario -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Incluir Popper y Bootstrap JS para tooltips (opcional) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @foreach($trabajadores as $trabajador)
                var calendarEl{{ $trabajador->usr_id }} = document.getElementById('calendar-{{ $trabajador->usr_id }}');

                var calendar{{ $trabajador->usr_id }} = new FullCalendar.Calendar(calendarEl{{ $trabajador->usr_id }}, {
                    initialView: 'timeGridWeek',
                    locale: 'es',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay'
                    },
                    selectable: false,
                    editable: false,
                    events: [
                        @foreach($trabajador->citas as $cita)
                            {
                                title: '{{ $cita->servicios->pluck('srv_nombre')->join(', ') }}',
                                start: '{{ $cita->cta_fecha }}T{{ $cita->cta_hora }}',
                                end: '{{ \Carbon\Carbon::parse($cita->cta_fecha . ' ' . $cita->cta_hora)->addMinutes($cita->servicios->first()->srv_duracion)->format('Y-m-d\TH:i:s') }}',
                                color: '{{ $trabajador->color ?? '#28a745' }}',
                                description: 'Estado: {{ $cita->estadoCita->estado_nombre }}',
                            },
                        @endforeach
                    ],
                    eventClick: function(info) {
                        alert(
                            'Servicio: ' + info.event.title + '\n' +
                            'Fecha: ' + info.event.start.toLocaleDateString() + '\n' +
                            'Hora: ' + info.event.start.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) + '\n' +
                            'Estado: ' + info.event.extendedProps.description
                        );
                    },
                    eventMouseEnter: function(info) {
                        // Mostrar tooltip con detalles usando Bootstrap Tooltips
                        var tooltip = new bootstrap.Tooltip(info.el, {
                            title: info.event.title + '\n' +
                                   info.event.extendedProps.description,
                            placement: 'top',
                            trigger: 'hover',
                            container: 'body'
                        });
                        info.el.setAttribute('data-bs-original-title', info.event.title + '\n' + info.event.extendedProps.description);
                    },
                    eventMouseLeave: function(info) {
                        // Bootstrap maneja el ocultamiento del tooltip automáticamente
                    }
                });

                calendar{{ $trabajador->usr_id }}.render();
            @endforeach
        });
    </script>
@endsection
