document.addEventListener('DOMContentLoaded', () => {
  const btnsVincular = document.querySelectorAll('.vincular-rfid-btn');
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

  btnsVincular.forEach(btn => {
    btn.addEventListener('click', async () => {
      const idusuario = btn.getAttribute('data-id');
      mostrarModal('Acerque la tarjeta al lector...');

      try {
        // Espera activa durante un máximo de 6 segundos, pero corta si detecta UID antes
        let uid = null;
        const tiempoMax = 6000;
        const inicio = Date.now();

        while (Date.now() - inicio < tiempoMax && !uid) {
          const response = await fetch('http://localhost:5000/leer_uid');
          const data = await response.text();
          if (data && !data.includes('ERROR') && data.trim() !== '') {
            uid = data.trim();
            break;
          }
          await new Promise(resolve => setTimeout(resolve, 500));
        }

        if (!uid) {
          mostrarModal('❌ No se pudo leer la tarjeta.', 'error');
          ocultarModal();
          return;
        }

        // Enviar el UID al PHP para verificar y asignar
        const formData = new FormData();
        formData.append('idusuario', idusuario);
        formData.append('uid', uid);

        const res = await fetch('php/vincular_rfid.php', {
          method: 'POST',
          body: formData
        });

        const texto = (await res.text()).trim();
        console.log('Respuesta del servidor PHP:', texto);

        // Manejo de respuesta según el texto del PHP
        if (texto.includes('UID ya está asignado')) {
          mostrarModal('❌ Ese RFID ya pertenece a otro usuario.', 'error');
          ocultarModal();
        } else if (texto.includes('RFID asignado correctamente')) {
          mostrarModal('✅ Tarjeta vinculada correctamente.', 'success');
          ocultarModal(() => location.reload()); // 🔁 Recarga la página después de cerrar el modal
        } else {
          mostrarModal('❌ Error al asignar RFID.', 'error');
          ocultarModal();
        }

      } catch (error) {
        console.error('Error al vincular RFID:', error);
        mostrarModal('❌ Error de conexión con el lector.', 'error');
        ocultarModal();
      }
    });
  });
});
