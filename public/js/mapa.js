async function geocodificarCiudadYPais(ciudad, pais) {
    const query = encodeURIComponent(`${ciudad}, ${pais}`);
    const url = `https://nominatim.openstreetmap.org/search?format=json&q=${query}`;

    try {
    const response = await fetch(url, { headers: { 'Accept-Language': 'es' } });
    const data = await response.json();
    alert("Respuesta geocodificación: " + JSON.stringify(data));  // <-- alerta para ver datos
    if (data && data.length > 0) {
    return {
    lat: parseFloat(data[0].lat),
    lon: parseFloat(data[0].lon)
};
} else {
    alert("No se encontraron resultados para la ciudad y país");
    return null;
}
} catch (err) {
    alert("Error al geocodificar: " + err.message);
    return null;
}
}

    let map, marker;

function initMap(lat, lng) {
    const mapContainer = document.getElementById('map');
    if (!mapContainer) {
        console.warn('No existe el contenedor #map, no se inicializa el mapa Leaflet');
        return;
    }

    if (!map) {
        map = L.map('map').setView([lat, lng], 10);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

        marker = L.marker([lat, lng], { draggable: true }).addTo(map);
        marker.on('dragend', function (e) {
            const { lat, lng } = marker.getLatLng();
            document.getElementById("latitud").value = lat;
            document.getElementById("longitud").value = lng;
        });
    } else {
        map.setView([lat, lng], 10);
        marker.setLatLng([lat, lng]);
    }

    document.getElementById("latitud").value = lat;
    document.getElementById("longitud").value = lng;
}


async function mostrarMapa() {
    document.getElementById("mapaContainer").style.display = "block";

    await new Promise(r => setTimeout(r, 200)); // <- importante

    const ciudad = document.getElementById("ciudad").value.trim();
    const pais = document.getElementById("pais").value.trim();

    let lat = -34.6037;
    let lon = -58.3816;

    if (ciudad && pais) {
        const coords = await geocodificarCiudadYPais(ciudad, pais);
        if (coords) {
            lat = coords.lat;
            lon = coords.lon;
        }
    }

    initMap(lat, lon);

    if (map) {
        map.invalidateSize();
    }


}
