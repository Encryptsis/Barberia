
import { Calendar } from '@fullcalendar/core';
import timeGridPlugin from '@fullcalendar/timegrid';

window.addEventListener('scroll', function () {
    const navbar = document.querySelector('.navbar');
    if (window.scrollY > 50) {
        navbar.classList.add('scrolled');
    } else {
        navbar.classList.remove('scrolled');
    }
});



// Obtener el botón
var scrollToTopBtn = document.getElementById("scrollToTopBtn");

// Cuando el usuario desplaza 20px desde la parte superior de la página, mostrar el botón
window.onscroll = function () { scrollFunction() };

function scrollFunction() {
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
        scrollToTopBtn.style.display = "block";
    } else {
        scrollToTopBtn.style.display = "none";
    }
}

// Cuando el usuario hace clic en el botón, desplazarse hasta la parte superior de la página
scrollToTopBtn.addEventListener("click", function () {
    // Para navegadores modernos
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
});



jQuery(document).ready(function ($) {
    $(".slider-img").on("click", function () {
        $(".slider-img").removeClass("active");
        $(this).addClass("active");
    });
});

function openWhatsApp() {
    window.open('https://wa.me/NUMBER?text=Saludos', '_blank');
}

let currentIndex = 0;

window.moveSlide = function(direction) { // Añadimos 'window.' aquí
    const slides = document.querySelectorAll('.card');
    const isLargeScreen = window.innerWidth > 768; // Ajusta este valor según sea necesario

    // Calcular el nuevo índice
    const newIndex = currentIndex + direction;

    // Verificar si el nuevo índice está dentro de los límites
    if (isLargeScreen) {
        // Para pantallas grandes
        if (newIndex >= 0 && newIndex < slides.length - 2) {
            currentIndex = newIndex; // Actualizar solo si está dentro de los límites
        }
    } else {
        // Para pantallas pequeñas
        if (newIndex >= 0 && newIndex < slides.length) {
            currentIndex = newIndex; // Actualizar solo si está dentro de los límites
        }
    }

    const offset = -currentIndex * (slides[0].clientWidth + 20); // Ajustar el desplazamiento
    document.querySelector('.carrusel-inner').style.transform = `translateX(${offset}px)`;
};

