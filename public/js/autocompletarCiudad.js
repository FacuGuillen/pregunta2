
    const ciudadInput = document.getElementById('ciudad');
    const autocompleteList = document.getElementById('autocomplete-list');

    ciudadInput.addEventListener('input', async function() {
    const query = this.value.trim();
    autocompleteList.innerHTML = '';

    if (query.length < 3) return; // Buscar a partir de 3 caracteres

    const pais = document.getElementById('pais').value.trim();

    let bbox = '';

    if (pais === 'ar') {
    bbox = '-73.5,-55.0,-53.5,-21.0'; // Argentina
} else if (pais === 'br') {
    bbox = '-74.0,-34.0,-34.0,5.0'; // Brasil
} else if (pais === 'cl') {
    bbox = '-75.0,-56.0,-66.0,-17.5'; // Chile
} else if (pais === 'uy') {
    bbox = '-58.5,-35.0,-53.0,-30.0'; // Uruguay
} else if (pais === 'py') {
    bbox = '-62.0,-28.5,-54.0,-19.0'; // Paraguay
} else if (pais === 'bo') {
    bbox = '-69.6,-22.9,-57.4,-9.7'; // Bolivia
} else if (pais === 'co') {
    bbox = '-78.0,-4.2,-66.8,13.5'; // Colombia
} else if (pais === 'ec') {
    bbox = '-81.5,-5.0,-75.2,1.8'; // Ecuador
} else if (pais === 'gy') {
    bbox = '-61.4,1.2,-56.5,8.6'; // Guyana
} else if (pais === 'pe') {
    bbox = '-81.3,-18.3,-68.7,-0.1'; // Perú
} else if (pais === 'sr') {
    bbox = '-58.0,1.0,-53.9,6.0'; // Surinam
} else if (pais === 've') {
    bbox = '-73.4,0.6,-59.8,12.2'; // Venezuela
}

    let url = `https://photon.komoot.io/api/?q=${encodeURIComponent(query)}&limit=5`;

    if (bbox) {
    url += `&bbox=${bbox}`;
}


    try {
    const response = await fetch(url);
    const data = await response.json();

    if (data.features.length === 0) {
    autocompleteList.innerHTML = '<div class="list-group-item disabled">No se encontraron ciudades</div>';
    return;
}

    data.features.forEach(feature => {
    const city = feature.properties.name;
    const state = feature.properties.state || '';
    const country = feature.properties.country || '';
    const lat = feature.geometry.coordinates[1];
    const lon = feature.geometry.coordinates[0];

    const item = document.createElement('div');
    item.classList.add('list-group-item', 'list-group-item-action');
    item.textContent = `${city}${state ? ', ' + state : ''}, ${country}`;
    item.style.cursor = 'pointer';

    item.addEventListener('click', () => {
    ciudadInput.value = city;
    // Guardar lat y lon en inputs ocultos
    document.getElementById('latitud').value = lat;
    document.getElementById('longitud').value = lon;
    autocompleteList.innerHTML = '';
});

    autocompleteList.appendChild(item);
});

} catch (error) {
    console.error('Error en autocomplete:', error);
}
});

