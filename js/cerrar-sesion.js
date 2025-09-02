document.addEventListener("DOMContentLoaded", function () {
    let timeout;

    function cerrarSesionPorInactividad() {
        // Mostrar el mensaje y esperar a que el usuario lo acepte
        alert("⚠️ Se cerrará la sesión por inactividad.");
        // Redirige con el parámetro forzarCierre para que PHP cierre sesión
        window.location.href = "index.php?mensaje=inactividad&forzarCierre=true";
    }

    function resetTimer() {
        clearTimeout(timeout);
        // 300000 ms = 5 minutos
        timeout = setTimeout(cerrarSesionPorInactividad, 300000);
    }

    // Reinicia el contador al cargar la página y con interacción del usuario
    resetTimer();
    document.addEventListener('mousemove', resetTimer);
    document.addEventListener('keydown', resetTimer);
});
