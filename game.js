// game.js
document.addEventListener('DOMContentLoaded', function() {
    const descriptionButton = document.getElementById('buttonDescription');
    const descriptionText = document.getElementById('descriptionText');

    descriptionButton.addEventListener('click', function() {
        console.log('Botón Descripción pulsado'); // Para depuración
        if (descriptionText.style.maxHeight) {
            // Si ya está desplegado, colapsarlo
            descriptionText.style.maxHeight = null;
        } else {
            // Si está colapsado, desplegarlo
            descriptionText.style.maxHeight = descriptionText.scrollHeight + "px";
        }
    });
});
