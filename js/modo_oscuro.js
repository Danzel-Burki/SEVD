document.addEventListener('DOMContentLoaded', function () {
    const toggle = document.getElementById('toggle-dark-mode');

    // Restaurar preferencia guardada
    if (localStorage.getItem('modoOscuro') === 'true') {
        document.body.classList.add('dark-mode');
    }

    // Actualiza el icono y el title según el modo
    function actualizarIcono() {
        if (document.body.classList.contains('dark-mode')) {
            toggle.textContent = '🌙';
            toggle.title = 'Modo oscuro';
        } else {
            toggle.textContent = '☀️';
            toggle.title = 'Modo claro';
        }
    }

    actualizarIcono();

    // Activar/desactivar modo y actualizar icono y localStorage
    if (toggle) {
        toggle.addEventListener('click', function () {
            document.body.classList.toggle('dark-mode');
            localStorage.setItem('modoOscuro', document.body.classList.contains('dark-mode'));
            actualizarIcono();
        });
    }
});
