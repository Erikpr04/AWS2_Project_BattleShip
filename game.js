document.addEventListener("DOMContentLoaded", (event) => {
    // Body animation
    document.body.style.transition = 'transform 1s'; 
    setTimeout(() => {
        document.body.style.transform = 'translateY(-90vh)';
    }, 100);


    // Timer start
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

    //Points
    let points = 0;
    let streakWater = 0;
    let streakHit = 0;
    const pointsElement = document.querySelector('.points');
    pointsElement.innerText = points;

    function changePointsText() {
        pointsElement.innerText = points;
    }

    function addPoints(){
        streakHit=0;
        streakHit++;
        points += (streakHit*10);
    
        if (minutes<1){
            points +=100;
        }
        else if (minutes<2){
            points +=75;
        }
        else if (minutes<3){
            points +=50;
        }
        else if (minutes<4){
            points +=25;
        }
        else if (minutes<5){
            points +=5;
        }
        changePointsText();
    }

    function subtractPoints(){
        streakHit=0;
        streakHit++;
        points -= streakHit;
        changePointsText();
    }

    function unhideCell(x_pos, y_pos) {
        let event;

        if (window.mainArray[y_pos][x_pos]['state'] === "show_ship") {
            window.mainArray[y_pos][x_pos]['state'] = "ship_hit";
            let cell = document.querySelector(`td[x_pos='${x_pos}'][y_pos='${y_pos}']`);
            if (cell) {
                cell.style.backgroundColor = 'red';
                addPoints();
                event = new CustomEvent('cellRevealed', {
                    detail: { type: 'ship_hit', x: x_pos, y: y_pos }
                });
            }
        } else if (window.mainArray[y_pos][x_pos]['state'] === "water") {
            window.mainArray[y_pos][x_pos]['state'] = "water_hit";
            let cell = document.querySelector(`td[x_pos='${x_pos}'][y_pos='${y_pos}']`);
            if (cell) {
                cell.style.backgroundColor = 'lightblue';
                subtractPoints();
                event = new CustomEvent('cellRevealed', {
                    detail: { type: 'water_hit', x: x_pos, y: y_pos }
                });
                
            }
        }

        if (event) {
            document.dispatchEvent(event);
        }
        //check and alert if all ships are sunk
        checkShipsStatus(window.shipsArray);

        console.log(window.mainArray);
    }




    //function to check if ships are all sunk
    function checkShipsStatus(ship_array) {
        let allShipsSunk = false;  
    
        ship_array.forEach(ship => {
            let allCellsHit = true;
    
            ship.pos.forEach(([x, y]) => {
                if (window.mainArray[y][x]['state'] !== 'ship_hit') {
                    allCellsHit = false;  
                }
            });
    
            ship.isalive = !allCellsHit;  
        });
        //double check to know if all ships are sunk
    
        allShipsSunk = ship_array.every(ship => !ship.isalive);
    
        if (allShipsSunk) {
            alert('YOU WON!! (Provisional)');
        }
    }
    



    let celdas = document.querySelectorAll('table.gameBoard td');

    celdas.forEach(function(celda) {
        celda.addEventListener('click', function() {
            let x_pos = parseInt(this.getAttribute('x_pos'));
            let y_pos = parseInt(this.getAttribute('y_pos'));

            unhideCell(x_pos, y_pos);
        });
    });

    // ---Game events---

    //audio_sfx
    let hitSound = new Audio('fish_strike.mp3');
    let missSound = new Audio('water_splash.mp3');
    let winSound = new Audio('win_sound_effect.mp3');
    let buttonSound = new Audio('buttonclick1.mp3');
    let waterTransitionSound = new Audio('water_transition.mp3');

    document.addEventListener('cellRevealed', function (e) {
        //handling events

        if (e.detail.type === 'ship_hit') {
            hitSound.play();
            //more reactions here plzz

        } else if (e.detail.type === 'water_hit') {
            missSound.play();
            //more reactions here plzz
        }
    });

});
