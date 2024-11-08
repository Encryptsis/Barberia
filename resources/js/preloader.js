// public/js/preloader.js

function carga() {
    var preloader = document.getElementById('preloader');
    preloader.style.opacity = '0';
    preloader.style.visibility = 'hidden';
}

// Ejecutar la función 'carga' cuando la ventana haya terminado de cargar
window.addEventListener('load', carga);
