document.addEventListener('DOMContentLoaded', function () {
    const map = L.map('mapPerfil').setView([userLat, userLon], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    L.marker([userLat, userLon]).addTo(map)
        .bindPopup('Ubicación del usuario')
        .openPopup();
});
