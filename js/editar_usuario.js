document.addEventListener("DOMContentLoaded", function () {
    // Mostrar/ocultar contraseñas
    const ojos = document.querySelectorAll(".eye-icon");

    ojos.forEach(icono => {
        icono.addEventListener("click", () => {
            const inputId = icono.getAttribute("data-target");
            const input = document.getElementById(inputId);

            if (input) {
                if (input.type === "password") {
                    input.type = "text";
                    icono.src = "img/ojo_abierto1.png"; // Cambiá por tu ícono de ojo abierto
                } else {
                    input.type = "password";
                    icono.src = "img/ojo_cerrado1.png"; // Cambiá por tu ícono de ojo cerrado
                }
            }
        });
    });
});
