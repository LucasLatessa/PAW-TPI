class appCrearTorneo {
    constructor() {
        document.addEventListener("DOMContentLoaded", () => {
            PAW.cargarScript("PAWCrearTorneo", "../assets/js/components/paw-creartorneo.js", () => {
                let crearTorneo = new PAWCrearTorneo(); 
            });
            PAW.agregarStyle("/assets/styles/crearTorneo.css");
        });
    }
}

let appC = new appCrearTorneo();