document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('registerForm');

    form.addEventListener('submit', function(event) {
        // Limpio clases error previas
        const inputs = form.querySelectorAll('input');
        inputs.forEach(input => input.classList.remove('error'));

        // Obtengo campos
        const name = form.elements['name'];
        const lastname = form.elements['lastname'];
        const sex = form.elements['sex'];
        const date = form.elements['date'];
        const email = form.elements['email'];
        const password = form.elements['password'];
        const confirm_password = form.elements['confirm_password'];
        const nameuser = form.elements['nameuser'];

        let hayError = false;
        let mensaje = '';

        // Valido campo por campo y marco los que estén vacíos
        if (!name.value.trim()) {
            name.classList.add('error');
            hayError = true;
        }
        if (!lastname.value.trim()) {
            lastname.classList.add('error');
            hayError = true;
        }
        if (!sex.value.trim()) {
            sex.classList.add('error');
            hayError = true;
        }
        if (!date.value.trim()) {
            date.classList.add('error');
            hayError = true;
        }
        if (!email.value.trim()) {
            email.classList.add('error');
            hayError = true;
        }
        if (!password.value) {
            password.classList.add('error');
            hayError = true;
        }
        if (!confirm_password.value) {
            confirm_password.classList.add('error');
            hayError = true;
        }
        if (!nameuser.value.trim()) {
            nameuser.classList.add('error');
            hayError = true;
        }

        if (hayError) {
            alert('Por favor, complete todos los campos obligatorios.');
            event.preventDefault();
            return;
        }

        // Valido que las contraseñas coincidan
        if (password.value !== confirm_password.value) {
            password.classList.add('error');
            confirm_password.classList.add('error');
            alert('Las contraseñas no coinciden.');
            event.preventDefault();
            return;
        }
    });
});
