document.addEventListener('DOMContentLoaded', function () {
    const lat = parseFloat("{{latitud}}") || -34.6037;
    const lon = parseFloat("{{longitud}}") || -58.3816;

    const map = L.map('map').setView([lat, lon], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    L.marker([lat, lon]).addTo(map)
        .openPopup();
});
