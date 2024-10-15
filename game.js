

document.addEventListener("DOMContentLoaded", (event) => {
    document.body.style.transition = 'transform 1s'; 
    setTimeout(() => {
        document.body.style.transform = 'translateY(-90vh)';
    }, 100);
});


let seconds = 0;
let minutes = 0;

setInterval(() => {
    seconds++;
    if (seconds === 60) {
        seconds = 0;
        minutes++;
    }
    document.querySelector('.timer').innerText = String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0');
}, 1000);

const pointsElement = document.querySelector('.points');
let points = 0;
pointsElement.innerText = points;

function incrementPoints() {
    points++;
    pointsElement.innerText = points;
}





function unhideCell(x_pos, y_pos) {
    if (window.mainArray[y_pos][x_pos]['state'] === "show_ship") {
        window.mainArray[y_pos][x_pos]['state'] = "ship_hit";
        
        let cell = document.querySelector(`td[x_pos='${x_pos}'][y_pos='${y_pos}']`);
        if (cell) {
            cell.style.backgroundColor = 'red';
        }
    }

    console.log(`Celda clicked: x=${x_pos}, y=${y_pos}`);
    console.log(window.mainArray);
}


let celdas = document.querySelectorAll('table.gameBoard td');

celdas.forEach(function(celda) {
    celda.addEventListener('click', function() {
        let x_pos = this.getAttribute('x_pos');
        let y_pos = this.getAttribute('y_pos');
        
        unhideCell(x_pos, y_pos);
    });
});



