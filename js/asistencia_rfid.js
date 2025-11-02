document.addEventListener('DOMContentLoaded', () => {
  const btnsAsistencia = document.querySelectorAll('.btn-asistencia-rfid');
  const modal = document.getElementById('rfid-modal');
  const spinner = document.getElementById('rfid-spinner');
  const statusText = document.getElementById('rfid-status');

  function mostrarModal(texto, tipo = null) {
    modal.style.display = 'flex';
    spinner.className = 'spinner';
    statusText.textContent = texto;
    if (tipo === 'success') spinner.classList.add('success');
    if (tipo === 'error') spinner.classList.add('error');
  }

  function ocultarModal(callback = null) {
    setTimeout(() => {
      modal.style.display = 'none';
      if (callback) callback();
    }, 4000);
  }

  btnsAsistencia.forEach(btn => {
    btn.addEventListener('click', async () => {
      const idusuario = btn.getAttribute('data-id');
      mostrarModal('Acerque la tarjeta al lector...');

      try {
        let uid = null;
        const tiempoMax = 6000;
        const inicio = Date.now();

        // 🔁 Espera hasta 6 segundos a que el lector devuelva el UID
        while (Date.now() - inicio < tiempoMax && !uid) {
          const response = await fetch('http://localhost:5000/leer_uid');
          const data = await response.text();
          if (data && !data.includes('ERROR') && data.trim() !== '') {
            uid = data.trim();
            break;
          }
          await new Promise(resolve => setTimeout(resolve, 500));
        }

        // ⛔ Si no se leyó tarjeta
        if (!uid) {
          mostrarModal('❌ No se pudo leer la tarjeta.', 'error');
          ocultarModal();
          return;
        }

        // ✅ Enviar UID al PHP para registrar asistencia
        const formData = new FormData();
        formData.append('uid_rfid', uid);
        formData.append('idusuario', idusuario);

        const res = await fetch('php/registrar_asistencia_rfid.php', {
          method: 'POST',
          body: formData
        });

        const texto = (await res.text()).trim();
        console.log('Respuesta del servidor PHP:', texto);

        // 💬 Interpretar la respuesta del PHP
        if (texto.includes('✅ Asistencia registrada')) {
          mostrarModal('✅ Asistencia registrada correctamente.', 'success');
          ocultarModal(() => location.reload());
        } else if (texto.includes('❌ UID no vinculado')) {
          mostrarModal('❌ Este RFID no pertenece a ningún usuario.', 'error');
          ocultarModal();
        } else {
          mostrarModal('❌ Error al registrar asistencia.', 'error');
          ocultarModal();
        }

      } catch (error) {
        console.error('Error al registrar asistencia RFID:', error);
        mostrarModal('❌ Error de conexión con el lector.', 'error');
        ocultarModal();
      }
    });
  });
});
