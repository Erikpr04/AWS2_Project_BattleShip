

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
