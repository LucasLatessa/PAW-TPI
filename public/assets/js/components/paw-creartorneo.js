class PAWCrearTorneo {
    constructor() {
        this.init();
    }

    init() {
        this.fechaInicioInput = document.getElementById('fechaInicio');
        this.fechaFinInput = document.getElementById('fechaFin');
        this.form = document.querySelector('.crear-form');
        this.addEventListeners();
    }

    addEventListeners() {
        this.form.addEventListener('submit', (event) => this.validateForm(event));
        this.fechaInicioInput.addEventListener('change', () => this.validateFechaInicio());
        this.fechaFinInput.addEventListener('change', () => this.validateFechaFin());
    }

    validateForm(event) {
        const fechaInicioValida = this.validateFechaInicio();
        const fechaFinValida = this.validateFechaFin();

        console.log('Fecha de inicio válida:', fechaInicioValida);
        console.log('Fecha de fin válida:', fechaFinValida);

        if (!fechaInicioValida || !fechaFinValida) {
            console.log('Formulario inválido. Se previene el envío.');
            event.preventDefault(); // Evita que se envíe el formulario si hay errores de validación
        }
    }

    validateFechaInicio() {
        const today = new Date();
        today.setHours(0, 0, 0, 0); // Establecer la hora a medianoche para solo comparar las fechas
    
        const fechaInicioParts = this.fechaInicioInput.value.split('-');
        const fechaInicio = new Date(fechaInicioParts[0], fechaInicioParts[1] - 1, fechaInicioParts[2]);
    
        if (fechaInicio <= today) {
            this.fechaInicioInput.setCustomValidity('La fecha de inicio debe ser mayor a la fecha actual.');
            this.fechaInicioInput.reportValidity();
            console.log('Fecha de inicio inválida:', this.fechaInicioInput.value);
            return false;
        } else {
            this.fechaInicioInput.setCustomValidity('');
            return true;
        }
    }

    validateFechaFin() {
        const fechaInicio = new Date(this.fechaInicioInput.value);
        const fechaFin = new Date(this.fechaFinInput.value);

        if (fechaFin <= fechaInicio) {
            this.fechaFinInput.setCustomValidity('La fecha de fin debe ser mayor a la fecha de inicio.');
            this.fechaFinInput.reportValidity();
            console.log('Fecha de fin inválida:', this.fechaFinInput.value);
            return false;
        } else {
            this.fechaFinInput.setCustomValidity('');
            return true;
        }
    }
}

// Iniciar la clase PAWCrearTorneo cuando el documento esté listo
document.addEventListener("DOMContentLoaded", () => {
    new PAWCrearTorneo();
});