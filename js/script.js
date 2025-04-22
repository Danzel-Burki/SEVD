document.addEventListener("DOMContentLoaded", function () {
    const condicionSelect = document.getElementById("condicion");
    const carreraSelect = document.getElementById("carrera");
    const materiaSelect = document.getElementById("materia");
    const buscarButton = document.querySelector("button[name='buscar']");

    // Función para cargar opciones en un select
    function cargarOpciones(select, data, placeholder = "Seleccione una opción") {
        console.log(data);
        select.innerHTML = `<option value="">${placeholder}</option>`;
        data.forEach(item => {
            const option = document.createElement("option");
            option.value = item.id || item.id || item.id; // Asegúrate de que el valor sea correcto
            option.textContent = item.nombre || item.nombre || item.nombre; // Asegúrate de que el texto sea correcto
            select.appendChild(option);
        });
    }

    // Función para manejar errores en las solicitudes
    function manejarError(error) {
        console.error("Error en la solicitud:", error);
        alert("Ocurrió un error al cargar los datos. Inténtalo de nuevo más tarde.");
    }

    // Obtener condiciones
    fetch("php/obtener_datos.php?type=condiciones")
        .then(response => response.json())
        .then(data => cargarOpciones(condicionSelect, data, "Seleccione una condición"))
        .catch(manejarError);

    // Obtener carreras
    fetch("php/obtener_datos.php?type=carreras")
        .then(response => response.json())
        .then(data => cargarOpciones(carreraSelect, data, "Seleccione una carrera"))
        .catch(manejarError);

    // Evento para cargar materias según la carrera seleccionada
    carreraSelect.addEventListener("change", function () {
        const idcarrera = carreraSelect.value;
        if (idcarrera) {
            fetch(`php/obtener_datos.php?type=materias&idcarrera=${idcarrera}`)
                .then(response => response.json())
                .then(data => cargarOpciones(materiaSelect, data, "Seleccione una asignatura"))
                .catch(manejarError);
        } else {
            materiaSelect.innerHTML = "<option value=''>Seleccione una asignatura</option>";
        }
    });

    // Evento para el botón "Buscar inscripciones"
    buscarButton.addEventListener("click", function (event) {
        event.preventDefault(); // Evita la recarga del formulario

        const carrera = carreraSelect.value;
        const materia = materiaSelect.value;
        const condicion = condicionSelect.value;

        // Validación básica
        if (carrera && materia && condicion) {
            // Redirige al archivo acta_volante.php con parámetros en la URL
            const url = `php/acta_volante.php?carrera=${carrera}&materia=${materia}&condicion=${condicion}`;
            window.open(url, "_blank");
        } else {
            alert("Por favor, seleccione una carrera, asignatura y condición.");
        }
    });
});



