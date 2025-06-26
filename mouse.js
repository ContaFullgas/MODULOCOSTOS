const lighting = document.querySelector('.lighting-effect');

document.addEventListener('mousemove', (e) => {
    // Calculamos las coordenadas del mouse
    const x = e.clientX;
    const y = e.clientY;

    // Actualizamos las variables CSS personalizadas
    lighting.style.setProperty('--x', x + 'px');
    lighting.style.setProperty('--y', y + 'px');
});
