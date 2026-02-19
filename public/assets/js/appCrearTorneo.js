document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('modal-equipos');
    const btnAbrir = document.getElementById('btn-abrir-modal');
    const btnCerrar = document.querySelector('.btn-cerrar');
    const btnConfirmar = document.getElementById('btn-confirmar-equipos');
    const contador = document.getElementById('equipos-seleccionados-contador');

    if (!btnAbrir || !modal) {
        console.error("No se encontró el botón o el modal en el DOM");
        return;
    }

    btnAbrir.addEventListener('click', () => {
        modal.style.display = 'block';
    });

    btnCerrar.addEventListener('click', () => {
        modal.style.display = 'none';
    });

    window.addEventListener('click', (event) => {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });

    btnConfirmar.addEventListener('click', () => {
        const seleccionados = document.querySelectorAll('.equipo-checkbox:checked').length;
        contador.innerText = `${seleccionados} equipos seleccionados`;
        modal.style.display = 'none';
    });

    // Buscador
    const filtroInput = document.getElementById('filtro-equipos');
    if (filtroInput) {
        filtroInput.addEventListener('input', (e) => {
            const busqueda = e.target.value.toLowerCase();
            document.querySelectorAll('.equipo-check-item').forEach(item => {
                const nombre = item.querySelector('span').innerText.toLowerCase();
                item.style.display = nombre.includes(busqueda) ? 'flex' : 'none';
            });
        });
    }
});