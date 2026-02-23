class appCrearTorneo {
    constructor() {
        document.addEventListener("DOMContentLoaded", () => {
            // Cargamos el script del componente modal
            PAW.cargarScript(
                "PAWModalEquipos", 
                "/assets/js/components/paw-modal-equipos.js", 
                () => {
                    new PAWModalEquipos();
                }
            );

        });
    }
}

let appTorneo = new appCrearTorneo();