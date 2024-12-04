<!-- resources/views/home.blade.php -->

@extends('layouts.app')

@section('title', 'Página de Inicio')

@section('content')


<!-- Agrega el preloader aquí -->
<div id="preloader">
    <div class="contieneloader">
        <div class="loader"></div>
    </div>
</div>

<img src="{{ Vite::asset('resources/images/prueba1.jpeg') }}" alt="barber" class="hero-image"/>
<section class="secciones">
    <h2 class="titulo-secciones"><i class="bi bi-file-person-fill"></i> THE TEAM</h2>
    <div class="partes">
        <section class="slider-container">
            <div class="slider-images">
                <div class="slider-img">
                    <img src="{{ Vite::asset('resources/images/prueba4.jpg') }}" alt="2" />
                    <div class="overlay"></div>
                    <h1 class="car">FACIAL</h1>
                    <div class="details">
                        <h2 class="car2">LOREM</h2>
                        <p class="car3">Avant-garde Style</p>
                    </div>
                </div>
    
                <div class="slider-img active">
                    <img src="{{ Vite::asset('resources/images/prueba2.jpg') }}" alt="2" />
                    <div class="overlay"></div>
                    <h1 class="car">PRESICION</h1>
                    <div class="details">
                        <h2 class="car2">BARBER</h2>
                        <p class="car3">Master Touch</p>
                    </div>
                </div>
    
                <div class="slider-img">
                    <img src="{{ Vite::asset('resources/images/prueba3.jpg') }}" alt="3" />
                    <div class="overlay"></div>
                    <h1 class="car">CREATIVITY</h1>
                    <div class="details">
                        <h2 class="car2">HAROLD</h2>
                        <p class="car3">We capture your identity</p>
                    </div>
                </div>
            </div>
        </section>
    </div>
</section>
    
<section class="secciones">
    <h2 class="titulo-secciones"><i class="bi bi-cash-stack"></i> SERVICES WE OFFER</h2>
    <div class="partes">
    
        <div class="carrusel">
            <div class="carrusel-inner">
                <div class="card">
                    
                    <img src="{{ Vite::asset('resources/images/bar.png') }}" alt="Imagen 1" class="card-img-top"/>
                    <div class="card-body">
                        <h5 class="card-title">HAIRCUT</h5>
                        <p class="card-text">Get te haircut you want with our expert stylist.
                            Wheter it´s a classic style or something unique, just bring a picture,
                            and we´ll create the look you desire.</p>
                        <p class="card-text">40 min. $35.00</p>
                      
                    </div>
                </div>
                <div class="card">
                    <img src="{{ Vite::asset('resources/images/bar.png') }}" alt="Imagen 2" class="card-img-top"/>
                    <div class="card-body">
                        <h5 class="card-title">FULL CUT</h5>
                        <p class="card-text">Experience our original full haircut package:
                            A premiu Grooming service that includes a precise haircut, detailed beard
                            shaping and eyebrow trimming.</p>
                        <p class="card-text">1 hour. $60.00</p>
                        
                    </div>
                </div>
                <div class="card">
                    <img src="{{ Vite::asset('resources/images/bar.png') }}" alt="Imagen 3" class="card-img-top"/>
                    <div class="card-body">
                        <h5 class="card-title">KIDS</h5>
                        <p class="card-text">We welcome kids for haircuts! For their comfort and safety,
                            we recommend parent and adult supervision for those who are
                            a bit more active.</p>
                        <p class="card-text">30 min. $35.00</p>
                        
                    </div>
                </div>
                <div class="card">
                    <img src="{{ Vite::asset('resources/images/bar.png') }}" alt="Imagen 4" class="card-img-top"/>
                    <div class="card-body">
                        <h5 class="card-title">BEAR GROOMING</h5>
                        <p class="card-text">We offer precise line-ups, shaping, trimming, and shaving.
                            Enjoy a hot towel tratment and relaxing oil for a refreshing experience.</p>
                        <p class="card-text">30 min. $30.00</p>

                        
                    </div>
                </div>
                <div class="card">
                    <img src="{{ Vite::asset('resources/images/bar.png') }}" alt="Imagen 5" class="card-img-top"/>
                    <div class="card-body">
                        <h5 class="card-title">WILD CUT</h5>
                        <p class="card-text">Come and live the Wild Deer experience, a service in personal care and
                            well-being, leaving you feeling renewed, confident and ready for any adventure.</p>
                        <p class="card-text">1 hour, 30 min. $115.00</p>
                    
                    </div>
                </div>
                <div class="card">
                    <img src="{{ Vite::asset('resources/images/bar.png') }}" alt="Imagen 5" class="card-img-top"/>
                    <div class="card-body">
                        <h5 class="card-title">FACIAL</h5>
                        <p class="card-text">We apply mask rich in natural ingredients to deeply
                            nourish and hydrate the skin. This mask, inspired by the purity
                            of nature, returns luminosity and eslasticity to your face.</p>
                        <p class="card-text">30 min. $55.00</p>
                        
                    </div>
                </div>
                <div class="card">
                    <img src="{{ Vite::asset('resources/images/bar.png') }}" alt="Imagen 6" class="card-img-top"/>
                    <div class="card-body">
                        <h5 class="card-title">LINE UP</h5>
                        <p class="card-text">Defining the lines of the forehead, sideburns and nape,
                            creating a symmetrical and polished finish.</p>
                        <p class="card-text">30 min. $40.00</p>
                       
                    </div>
                </div>
                <div class="card">
                    <img src="{{ Vite::asset('resources/images/bar.png') }}" alt="Imagen 7" class="card-img-top"/>
                    <div class="card-body">
                        <h5 class="card-title">HYDROGEN OXYGEN</h5>
                        <p class="card-text">Is a non-invasive skin care procedure that uses a special
                            device to deliver a mixture of hydrogen gas and oxygen to the skin
                            for deeply cleanising pores and reducing imperfections.</p>
                        <p class="card-text">1 hour. $140.00</p>
                       
                    </div>
                </div>
            </div>
            <button class="prev" onclick="moveSlide(-1)">&#10094;</button>
            <button class="next" onclick="moveSlide(1)">&#10095;</button>
        </div>
    
    </div>
</section>

<section class="secciones">
    <h2 class="titulo-secciones"><i class="bi bi-scissors"></i> OUR WORKSPACE</h2>
    <div class="partes">
        <img src="{{ Vite::asset('resources/images/prueba5.jpg') }}" alt="workspace" class="hero-image"/>
    </div>
    </section>

<section class="secciones">
    <h2 class="titulo-secciones"><i class="bi bi-play-btn"></i> OUR WORK</h2>
    <div class="partes">
        <div class="video-container">
            <iframe src="https://www.youtube.com/embed/dig_n1ryyWI?si=5NncztdVKXDki9EL" title="YouTube video player"
                frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
        </div>
    </div>
</section>

<section class="secciones">
    <div class="partes">
        <div class="contenedor_elementos">
            <div class="wild_deer_info">
                <div class="info info-1">
                    <h2>OUR LOCATIONS</h2>
                    <hr>
                    <p>1234 NW 12th St, City City, 12345</p>
                    <hr>
                    <h2>WE´RE OPEN FROM MONDAY TO FRIDAY</h2>
                    <hr>
                    <p>Contact By Email: email@example.com</p>
                    <p>Contact By Cell Number: 123-321-1234</p>
                </div>
                <div class="info info-2">
                    <img src="{{ Vite::asset('resources/images/slogan_imagen.jpg') }}" alt="slogan" class="hero-image"/>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="secciones">
    <h2 class="titulo-secciones"><i class="bi bi-pin-map-fill"></i> VISIT US!</h2>
    <div class="partes">
        <div class="contenedor_elementos">
            <iframe class="mapa"
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3151.835434509847!2d-94.7052346846815!3d39.22990397941769!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x87c0a8b6a7e9c7c7%3A0x8e4b7a5e5f4a5b9!2s7111%20NW%2086th%20St%2C%20Kansas%20City%2C%20MO%2064153%2C%20Estados%20Unidos!5e0!3m2!1ses-419!2ses-419!4v1697123456789"
                width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy">
            </iframe>
        </div>
    </div>
</section>

<section>
    <button id="scrollToTopBtn" class="scroll-to-top fa fa-arrow-up" onclick="scrollToTop()">
    </button>

    
</section>

@endsection
@push('scripts')
    @vite(['resources/js/index.js', 'resources/js/preloader.js'])
@endpush