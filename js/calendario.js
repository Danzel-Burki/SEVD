document.addEventListener("DOMContentLoaded", function () {
    const diasContainer = document.getElementById("dias-calendario");
    const mesAnio = document.getElementById("mes-anio");
    const btnAnterior = document.getElementById("mes-anterior");
    const btnSiguiente = document.getElementById("mes-siguiente");
    const resumen = document.getElementById("detalle-resumen");
    const extendido = document.getElementById("detalle-extendido");
    const infoExtendida = document.getElementById("info-extendida");

    let fechaActual = new Date();

    function renderizarCalendario() {
        diasContainer.innerHTML = "";

        const anio = fechaActual.getFullYear();
        const mes = fechaActual.getMonth();
        const primerDia = new Date(anio, mes, 1);
        const ultimoDia = new Date(anio, mes + 1, 0);
        const diaSemana = primerDia.getDay();

        mesAnio.textContent = primerDia.toLocaleString("es-ES", {
            month: "long",
            year: "numeric"
        }).toUpperCase();

        for (let i = 0; i < diaSemana; i++) {
            const celdaVacia = document.createElement("div");
            celdaVacia.classList.add("dia-calendario", "vacio");
            diasContainer.appendChild(celdaVacia);
        }

        for (let dia = 1; dia <= ultimoDia.getDate(); dia++) {
            const fechaStr = `${anio}-${(mes + 1).toString().padStart(2, '0')}-${dia.toString().padStart(2, '0')}`;
            const div = document.createElement("div");
            div.classList.add("dia-calendario");
            div.textContent = dia;

            if (asistencias[fechaStr]) {
                let estado = asistencias[fechaStr][0].estado;
                if (estado === "Presente") div.classList.add("presente");
                else if (estado === "Tardanza") div.classList.add("tardanza");
                else div.classList.add("ausente");
            }

            div.addEventListener("click", () => mostrarDetalles(fechaStr));
            diasContainer.appendChild(div);
        }
    }

    function mostrarDetalles(fechaStr) {
        const registros = asistencias[fechaStr] || [];

        const entrada = registros[0]?.hora ?? "---";
        const salida = registros[1]?.hora ?? "---";
        const estado = registros[0]?.estado ?? "---";
        const observacion = registros[0]?.observacion || "---";

        resumen.innerHTML = `
            <p><strong>Hora de entrada:</strong> ${entrada}</p>
            <p><strong>Hora de salida:</strong> ${salida}</p>
            <p><strong>Estado:</strong> ${estado}</p>
            <p><strong>Observación:</strong> ${observacion}</p>
            <button id="btn-ver-mas" class="btn-ver-mas">Más</button>
        `;

        document.getElementById("btn-ver-mas").addEventListener("click", () => {
            infoExtendida.innerHTML = registros.map((r, i) => `
                <p><strong>Registro ${i + 1}:</strong> ${r.hora} - ${r.estado} - ${r.observacion || "Ninguna"}</p>
            `).join("");
            resumen.style.display = "none";
            extendido.style.display = "block";
        });

        const btnMenos = document.getElementById("btn-ver-menos");
        if (btnMenos) {
            btnMenos.remove();
        }

        const btnVerMenos = document.createElement("button");
        btnVerMenos.id = "btn-ver-menos";
        btnVerMenos.textContent = "Menos";
        btnVerMenos.className = "btn-ver-menos";
        btnVerMenos.addEventListener("click", () => {
            resumen.style.display = "block";
            extendido.style.display = "none";
        });
        extendido.appendChild(btnVerMenos);
    }

    btnAnterior.addEventListener("click", () => {
        fechaActual.setMonth(fechaActual.getMonth() - 1);
        renderizarCalendario();
    });

    btnSiguiente.addEventListener("click", () => {
        fechaActual.setMonth(fechaActual.getMonth() + 1);
        renderizarCalendario();
    });

    renderizarCalendario();
});
