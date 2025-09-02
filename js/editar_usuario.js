document.addEventListener("DOMContentLoaded", function () {
    const ojos = document.querySelectorAll(".eye-icon");
    const body = document.body;

    function actualizarIconos() {
        const modoOscuro = body.classList.contains("modo-oscuro");
        ojos.forEach(icono => {
            const inputId = icono.getAttribute("data-target");
            const input = document.getElementById(inputId);

            if (input) {
                if (input.type === "password") {
                    icono.src = modoOscuro ? "img/ojo_cerrado2.png" : "img/ojo_cerrado1.png";
                } else {
                    icono.src = modoOscuro ? "img/ojo_abierto2.png" : "img/ojo_abierto1.png";
                }
            }
        });
    }

    // Actualizar iconos al cargar la página
    actualizarIconos();

    ojos.forEach(icono => {
        icono.addEventListener("click", () => {
            const inputId = icono.getAttribute("data-target");
            const input = document.getElementById(inputId);

            if (input) {
                if (input.type === "password") {
                    input.type = "text";
                } else {
                    input.type = "password";
                }
                // Actualizar icono según el estado actual
                actualizarIconos();
            }
        });
    });

    // Detectar cambio de modo oscuro desde tu botón
    const btnModoOscuro = document.querySelector('.btn-modo-oscuro');
    btnModoOscuro.addEventListener('click', () => {
        // Esperar un tick para que se aplique la clase toggle
        setTimeout(actualizarIconos, 10);
    });
});
