
    document.addEventListener("DOMContentLoaded", function () {
    const eliminarForms = document.querySelectorAll('.eliminar-form');

    eliminarForms.forEach(form => {
    const btn = form.querySelector('.btn-eliminar');

    btn.addEventListener('click', function () {
    Swal.fire({
    title: '¿Estás seguro?',
    text: "¡Esta acción eliminará la pregunta!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'Sí, eliminar',
    cancelButtonText: 'Cancelar'
}).then((result) => {
    if (result.isConfirmed) {
    // Crear un formulario temporal si necesitás redirigir a /editor/eliminarPregunta
    form.action = "/editor/eliminarPregunta";
    form.submit();
}
});
});
});
});

