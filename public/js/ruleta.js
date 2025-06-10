document.addEventListener('DOMContentLoaded', () => {
    console.log('Winwheel:', typeof Winwheel); // para chequear que está definido

    if(typeof Winwheel === 'undefined') {
        console.error('Winwheel no está cargado.');
        return;
    }

    const ruleta = new Winwheel({
        canvasId: 'canvas',
        numSegments: 6,
        segments: [
            { fillStyle: '#eae56f', text: 'Historia' },
            { fillStyle: '#89f26e', text: 'Ciencia' },
            { fillStyle: '#7de6ef', text: 'Arte' },
            { fillStyle: '#e7706f', text: 'Geografía' },
            { fillStyle: '#ff9a76', text: 'Cultura' },
            { fillStyle: '#b56cee', text: 'Deportes' }
        ],
        pointerGuide: {
            display: false
        },
        animation: {
            type: 'spinToStop',
            duration: 5,
            spins: 8,
            callbackFinished: (segment) => {
                alert(`Categoría elegida: ${segment.text}`);  // muestra el alert y espera que el usuario cierre

                // Cuando el usuario cierre el alert, redirigimos:
                window.location.href = `juego/jugar/${encodeURIComponent(segment.text)}`;
            }
        }
    });

    document.getElementById('canvas').addEventListener('click', () => {
        ruleta.startAnimation();
    });
});
