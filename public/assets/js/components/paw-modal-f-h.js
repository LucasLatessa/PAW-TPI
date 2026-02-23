class PAWModalFH {
    constructor() {
        this.modal = document.getElementById('modalFecha');
        this.btnAbrir = document.querySelector('.btn-definir-fecha');
        this.btnCerrar = document.querySelector('.btn-close');
        this.form = this.modal ? this.modal.querySelector('form') : null;

        if (this.modal && (this.btnAbrir || document.body.contains(this.btnAbrir))) {
            this.init();
        }
    }

    init() {
        // Abrir modal
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('btn-definir-fecha')) {
                this.abrir();
            }
        });

        // Cerrar modal (boton X)
        if (this.btnCerrar) {
            this.btnCerrar.addEventListener('click', () => this.cerrar());
        }

        // Cerrar al hacer click afuera del contenido
        window.addEventListener('click', (event) => {
            if (event.target === this.modal) {
                this.cerrar();
            }
        });

        // Validacion simple antes de enviar
        if (this.form) {
            this.form.addEventListener('submit', (e) => {
                const fecha = document.getElementById('fecha').value;
                const hora = document.getElementById('hora').value;
                if (!fecha || !hora) {
                    e.preventDefault();
                    alert('Completa la fecha y la hora para seguir.');
                }
            });
        }
    }

    abrir() {
        this.modal.style.display = 'flex'; 
    }

    cerrar() {
        this.modal.style.display = 'none';
    }
}

// Para instanciarlo cuando carga el DOM
document.addEventListener('DOMContentLoaded', () => {
    new PAWModalFH();
});