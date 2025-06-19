document.querySelectorAll('.eye-icon').forEach(icon => {
    icon.addEventListener('click', () => {
        const inputId = icon.getAttribute('data-target');
        const input = document.getElementById(inputId);
        if (input.type === 'password') {
            input.type = 'text';
            icon.src = 'img/ojo_abierto1.png';
        } else {
            input.type = 'password';
            icon.src = 'img/ojo_cerrado1.png';
        }
    });
});