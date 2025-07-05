// Gráfico % correctas
if(topUsuarioData) {
    new Chart(document.getElementById('graficoRespuestas'), {
        type: 'bar',
        data: {
            labels: [topUsuarioData.nombre_usuario],
            datasets: [{
                label: '% Correctas',
                data: [topUsuarioData.porcentaje_correctas],
                backgroundColor: 'rgba(75, 192, 192, 0.6)'
            }]
        }
    });
}

// Gráfico sexo
new Chart(document.getElementById('graficoSexo'), {
    type: 'pie',
    data: {
        labels: sexoData.map(d => d.sexo),
        datasets: [{
            data: sexoData.map(d => d.cantidad),
            backgroundColor: ['#3498db', '#e91e63', '#95a5a6']
        }]
    }
});

// Gráfico edad
new Chart(document.getElementById('graficoEdad'), {
    type: 'doughnut',
    data: {
        labels: edadData.map(d => d.grupo),
        datasets: [{
            data: edadData.map(d => d.cantidad),
            backgroundColor: ['#f1c40f', '#2ecc71', '#8e44ad']
        }]
    }
});
