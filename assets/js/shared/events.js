function moverCarrusel(direccion = 1) {
    const carrusel = document.getElementById('carruselCursos');
    if (!carrusel) return;

    const card = carrusel.querySelector('.card-curso');
    if (!card) return;

    const cardWidth = card.offsetWidth + 20; // Margen o gap
    carrusel.scrollBy({ left: direccion * cardWidth, behavior: 'smooth' });

    // Esperar al final del scroll para actualizar visibilidad de flechas
    setTimeout(() => actualizarFlechasCarrusel(), 300);
}

function actualizarFlechasCarrusel() {
    const carrusel = document.getElementById('carruselCursos');
    const flechaIzq = document.querySelector('.carrusel-flecha.izquierda');
    const flechaDer = document.querySelector('.carrusel-flecha.derecha');

    if (!carrusel || !flechaIzq || !flechaDer) return;

    const scrollLeft = carrusel.scrollLeft;
    const scrollMax = carrusel.scrollWidth - carrusel.clientWidth;

    flechaIzq.style.display = scrollLeft > 0 ? 'block' : 'none';
    flechaDer.style.display = scrollLeft < scrollMax - 10 ? 'block' : 'none';
}

// También actualizamos flechas al cargar
document.addEventListener('DOMContentLoaded', () => {
    actualizarFlechasCarrusel();
});

