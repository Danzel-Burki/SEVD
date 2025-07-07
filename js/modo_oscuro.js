document.addEventListener('DOMContentLoaded', function() {
    const btnModoOscuro = document.querySelector('.btn-modo-oscuro');
    const icono = document.getElementById('icono-modo-oscuro');
    const body = document.body;

    // Cargar modo oscuro guardado en localStorage (si existe)
    if (localStorage.getItem('modoOscuro') === 'true') {
        body.classList.add('modo-oscuro');
        icono.textContent = '☀️';  // icono sol
    }

    btnModoOscuro.addEventListener('click', () => {
        body.classList.toggle('modo-oscuro');

        // Cambiar icono
        if (body.classList.contains('modo-oscuro')) {
            icono.textContent = '☀️';
            localStorage.setItem('modoOscuro', 'true');
        } else {
            icono.textContent = '🌙';
            localStorage.setItem('modoOscuro', 'false');
        }
    });
});