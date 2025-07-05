function drawGeoChart(datos) {
    const dataArray = [['Country', 'Usuarios']];
    if (Array.isArray(datos) && datos.length > 0) {
        datos.forEach(item => dataArray.push(item));
    }

    const data = google.visualization.arrayToDataTable(dataArray);

    const options = {
        region: '005',
        displayMode: 'regions',
        resolution: 'countries',
        colorAxis: { colors: ['#b2dfdb', '#00695c'] },
        backgroundColor: '#ffffff',
        datalessRegionColor: '#f0f0f0'
    };

    const chart = new google.visualization.GeoChart(document.getElementById('geo_chart_div'));
    chart.draw(data, options);
}

google.charts.load('current', {
    packages: ['geochart']
});

google.charts.setOnLoadCallback(() => {
    drawGeoChart(datosUsuariosPorPais);
});