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
    // Mover el mapa y el marcador si ya existen
    map.setView([lat, lng], 10);
    marker.setLatLng([lat, lng]);
}

    // Actualizá los inputs ocultos siempre
    document.getElementById("latitud").value = lat;
    document.getElementById("longitud").value = lng;
}


    async function mostrarMapa() {
    document.getElementById("mapaContainer").style.display = "block";

    const ciudad = document.getElementById("ciudad").value.trim();
    const pais = document.getElementById("pais").value.trim();

    alert(`Buscando coordenadas para: ${ciudad}, ${pais}`);

    let lat = -34.6037; // Valor por defecto: Buenos Aires
    let lon = -58.3816;

    if (ciudad && pais) {
    const coords = await geocodificarCiudadYPais(ciudad, pais);
    if (coords) {
    lat = coords.lat;
    lon = coords.lon;
    alert(`Coordenadas encontradas: lat=${lat}, lon=${lon}`);
} else {
    alert("No se encontraron coordenadas, se usará ubicación por defecto.");
}
} else {
    alert("Faltan ciudad o país para buscar ubicación");
}

    initMap(lat, lon);
}


