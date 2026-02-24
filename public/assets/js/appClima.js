class appClima {
    constructor() {
        document.addEventListener("DOMContentLoaded", () => {
            PAW.cargarScript(
                "PAWClima", 
                "/assets/js/components/paw-clima.js", 
                () => {
                    new PAWClima();
                }
            );

        });
    }
}

const myAppClima = new appClima();