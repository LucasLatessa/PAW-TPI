class PAWModalEquipos {
    constructor() {
        this.modal = document.getElementById('modal-equipos');
        this.btnAbrir = document.getElementById('btn-abrir-modal');
        this.btnCerrar = document.querySelector('.btn-cerrar');
        this.btnConfirmar = document.getElementById('btn-confirmar-equipos');
        this.contador = document.getElementById('equipos-seleccionados-contador');
        this.filtroInput = document.getElementById('filtro-equipos');
        
        if (this.modal && this.btnAbrir) {
            this.init();
        }
    }

    init() {
        // Abrir modal
        this.btnAbrir.addEventListener('click', () => {
            this.modal.style.display = 'block';
        });

        // Cerrar modal (botón X)
        this.btnCerrar.addEventListener('click', () => {
            this.modal.style.display = 'none';
        });

        // Cerrar al hacer click afuera
        window.addEventListener('click', (event) => {
            if (event.target === this.modal) {
                this.modal.style.display = 'none';
            }
        });

        // Confirmar selección
        this.btnConfirmar.addEventListener('click', () => {
            this.actualizarContador();
            this.modal.style.display = 'none';
        });

        // Lógica del buscador
        if (this.filtroInput) {
            this.filtroInput.addEventListener('input', (e) => this.filtrar(e));
        }
    }

    actualizarContador() {
        const seleccionados = document.querySelectorAll('.equipo-checkbox:checked').length;
        this.contador.innerText = `${seleccionados} equipos seleccionados`;
    }

    filtrar(e) {
        const busqueda = e.target.value.toLowerCase();
        document.querySelectorAll('.equipo-check-item').forEach(item => {
            const nombre = item.querySelector('span').innerText.toLowerCase();
            item.style.display = nombre.includes(busqueda) ? 'flex' : 'none';
        });
    }
}