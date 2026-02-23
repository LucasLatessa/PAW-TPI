class PAWModalEquipos {
    constructor() {
        this.modal = document.getElementById('modal-equipos');
        this.btnAbrir = document.getElementById('btn-abrir-modal');
        this.btnCerrar = document.querySelector('.btn-cerrar');
        this.btnConfirmar = document.getElementById('btn-confirmar-equipos');
        this.contador = document.getElementById('equipos-seleccionados-contador');
        this.filtroInput = document.getElementById('filtro-equipos');
        this.checkTodos = document.getElementById('check-seleccionar-todos');
        
        if (this.modal && this.btnAbrir) {
            this.init();
        }
    }

    init() {
        // Abrir modal
        this.btnAbrir.addEventListener('click', () => {
            this.modal.style.display = 'block';
        });
        

        // Cerrar modal (boton X)
        this.btnCerrar.addEventListener('click', () => {
            this.modal.style.display = 'none';
        });

        // Cerrar al hacer click afuera
        window.addEventListener('click', (event) => {
            if (event.target === this.modal) {
                this.modal.style.display = 'none';
            }
        });

        // Confirmar seleccion
        this.btnConfirmar.addEventListener('click', () => {
            this.actualizarContador();
            this.modal.style.display = 'none';
        });

        // Logica del buscador
        if (this.filtroInput) {
            this.filtroInput.addEventListener('input', (e) => this.filtrar(e));
        }
        if (this.checkTodos) {
            this.checkTodos.addEventListener('change', (e) => this.toggleTodos(e.target.checked));
        }
    }
    toggleTodos(estado) {
        // Buscamos por filtros
        const itemsVisibles = document.querySelectorAll('.equipo-check-item:not([style*="display: none"])');
        
        itemsVisibles.forEach(item => {
            const cb = item.querySelector('.equipo-checkbox');
            if (cb) {
                cb.checked = estado;
            }
        });
        this.actualizarContador();
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