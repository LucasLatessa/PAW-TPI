class appFechaHora {
    constructor() {
        document.addEventListener("DOMContentLoaded", () => {
            // Cargamos el script del componente modal
            PAW.cargarScript(
                "PAWModalFH", 
                "/assets/js/components/paw-modal-f-h.js", 
                () => {
                    new PAWModalFH();
                }
            );

        });
    }
}

let appFH = new appFechaHora();