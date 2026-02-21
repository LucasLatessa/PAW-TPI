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

            // cargamos css aca
            // PAW.agregarStyle("/assets/styles/modal-equipos.css");
        });
    }
}

let appTorneo = new appCrearTorneo();