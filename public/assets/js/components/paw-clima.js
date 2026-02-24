class PAWClima {
    constructor() {
        this.ubicacionInfo = document.getElementById('ubicacionInfo');
        if (!this.ubicacionInfo) return;

        this.init();
    }

    init() {
        // ni bien se instancia, pedimos ubicacion
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                (pos) => {
                    const { latitude, longitude } = pos.coords;
                    // guardamos en localStorage para tenerlo a mano
                    localStorage.setItem('user_lat', latitude);
                    localStorage.setItem('user_lng', longitude);
                    
                    this.getClima(latitude, longitude);
                },
                (err) => {
                    console.warn("El usuario nego la ubicacion o hubo un error", err);
                },
                { enableHighAccuracy: true, timeout: 10000 }
            );
        }
    }

    async getClima(lat, lng) {
        try {
            const res = await fetch(`/api/weather?lat=${lat}&lng=${lng}`);
            const data = await res.json();

            if (data.main) {
                this.render(data);
            }
        } catch (e) {
            console.error("Error al traer clima via AJAX", e);
        }
    }

    render(data) {
        const icon = data.weather[0].icon;
        const desc = data.weather[0].description;
        const temp = Math.round(data.main.temp);
        const cityName = data.name;

        // Limpiamos y renderizamos con la estructura de clases de CSS
        this.ubicacionInfo.innerHTML = `
                <div class="clima-info-ajax" style="display: flex; flex-direction: column; gap: 4px; padding: 8px;">
                    <div style="display: flex; align-items: center; gap: 5px; color: var(--color-primario); font-weight: bold; font-size: 0.85rem;">
                        <ion-icon name="location-sharp"></ion-icon>
                        <span>${cityName}</span>
                    </div>

                    <div style="display: flex; align-items: center; gap: 10px;">
                            <p style="margin: 0; font-weight: 800; color: #333; font-size: 1.1rem;">${temp}°C</p>
                            <p style="margin: 0; font-size: 0.75rem; text-transform: capitalize; color: #666; line-height: 1;">${desc}</p>
                    </div>
                </div>
            `;
        
        // Lo hacemos visible
        this.ubicacionInfo.style.display = 'flex';
    }
}