class app{
    constructor(){
        document.addEventListener("DOMContentLoaded", () => {
            PAW.cargarScript("","./assets/js/components/.js",() => {
                 //let hora = new PAWHora(); //ver de usar tambien json
            });
         });
     }
}

let appX = new app();