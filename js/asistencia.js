document.addEventListener('DOMContentLoaded', () => {
    const botonesCargar = document.querySelectorAll('.btn-cargar-asistencia');

    botonesCargar.forEach(btn => {
        btn.addEventListener('click', () => {
            const idusuario = btn.getAttribute('data-id');
            const formCont = document.getElementById(`form-${idusuario}`);

            // Ocultar todos los formularios abiertos
            document.querySelectorAll('.form-asistencia-container').forEach(fc => {
                if (fc.id !== `form-${idusuario}`) {
                    fc.style.display = 'none';
                }
            });

            // Alternar visibilidad del formulario actual
            if (formCont.style.display === 'block') {
                formCont.style.display = 'none';
            } else {
                formCont.style.display = 'block';

                // Ajustar campo fecha/hora según estado actual
                const estadoSelect = formCont.querySelector('select[name="estado"]');
                const fechaHoraInput = formCont.querySelector('input[name="fechahora"]');

                function ajustarFechaHora() {
                    if (estadoSelect.value === 'Ausente') {
                        fechaHoraInput.value = '';
                        fechaHoraInput.disabled = true;
                    } else {
                        fechaHoraInput.disabled = false;
                        if (!fechaHoraInput.value) {
                            // Poner fecha/hora actual
                            const ahora = new Date();
                            const isoString = ahora.toISOString().slice(0, 16);
                            fechaHoraInput.value = isoString;
                        }
                    }
                }

                ajustarFechaHora();

                estadoSelect.addEventListener('change', ajustarFechaHora);
            }
        });
    });
});
