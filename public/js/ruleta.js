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
            { fillStyle: '#A0522D', text: 'Historia' },
            { fillStyle: '#7ED957', text: 'Ciencia' },
            { fillStyle: '#FF6B6B', text: 'Arte' },
            { fillStyle: '#A0A0A0', text: 'Geografía' },
            { fillStyle: '#FFA94D', text: 'Cultura' },
            { fillStyle: '#4A90E2', text: 'Deportes' }
        ],
        pointerGuide: {
            display: false
        },
        animation: {
            type: 'spinToStop',
            duration: 5,
            spins: 8,
            callbackFinished: (segment) => {
                Swal.fire(`Categoría elegida: ${segment.text}`).then(() => {
                    // Esto se ejecuta **después** de que el usuario cierre el Swal
                    window.location.href = `/juego/jugar/${encodeURIComponent(segment.text)}`;
                });
            }
        }
    });

    document.getElementById('canvas').addEventListener('click', () => {
        ruleta.startAnimation();
    });
});
