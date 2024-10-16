document.addEventListener("DOMContentLoaded", (event) => {
    // Body animation
    document.body.style.transform = 'translateY(-15vh)'; 
    document.body.style.transition = 'transform 0.75s'; 
    setTimeout(() => {
        document.body.style.transform = 'translateY(-120vh)';
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
        streakWater=0;
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
        streakWater++;
        points -= streakWater;
        changePointsText();
    }

    function unhideCell(x_pos, y_pos) {
        let event;

        if (window.mainArray[y_pos][x_pos]['state'] === "show_ship") {
            window.mainArray[y_pos][x_pos]['state'] = "ship_hit";
            let cell = document.querySelector(`td[x_pos='${x_pos}'][y_pos='${y_pos}']`);
            if (cell) {
                cell.style.backgroundColor = '#FF1355';
                addPoints();
                event = new CustomEvent('gameEvent', {
                    detail: { type: 'ship_hit'}
                });
            }
        } else if (window.mainArray[y_pos][x_pos]['state'] === "water") {
            window.mainArray[y_pos][x_pos]['state'] = "water_hit";
            let cell = document.querySelector(`td[x_pos='${x_pos}'][y_pos='${y_pos}']`);
            if (cell) {
                cell.style.backgroundColor = 'lightblue';
                subtractPoints();
                event = new CustomEvent('gameEvent', {
                    detail: { type: 'water_hit' }
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

    function showShipInBoard(ship){
        console.log(ship.pos[0][0],ship.pos[1][0]);

        let vertical = false;

        if (ship.pos[0][0] == ship.pos[1][0]){
            vertical = true;
        }

        let selected_fish = '';

        ship.pos.forEach(([x, y], index) => {
            let cell = document.querySelector(`td[x_pos='${x}'][y_pos='${y}']`);
            
            switch (ship.pos.length) {                
                case 2:
                    selected_fish = 'fish';
                    break;
                case 3:
                    selected_fish = 'squid';
                    break;
                case 4:
                    selected_fish = 'swordfish';
                    break;
                case 5:
                    selected_fish = 'eel';
                    break;
            }
        
            if (vertical) {
                cell.innerHTML = `<img src='static/img/${selected_fish}Divided/${selected_fish}${index+1}.png' alt='fish_image ${index+1}' style="transform: rotate(90deg); width: 100%; height: 100%;">`;
                cell.style.backgroundColor = '#3a92b2';

            } else {
                cell.innerHTML = `<img src='static/img/${selected_fish}Divided/${selected_fish}${index+1}.png' alt='fish_image ${index+1}' style="width: 100%;">`;
                cell.style.backgroundColor = '#3a92b2';

            }
        });
        
    }


    //function to check if ships are all sunk
    function checkShipsStatus(ship_array) {
        let fish_sunk = false;
        let allShipsSunk = false;

        console.log(ship_array);

        ship_array.forEach(ship => {
            let allCellsHit = true; 

            ship.pos.forEach(([x, y]) => {
                if (window.mainArray[y][x]['state'] !== 'ship_hit') {
                    allCellsHit = false;
                }
            });

            if (allCellsHit) {
                ship.pos.forEach(([x, y]) => {
                    window.mainArray[y][x]['state'] = 'fish_sunk'; 
                });
                ship.isalive = false; 
                showShipInBoard(ship); 
                fish_sunk = true; 
            }
        });

        if (fish_sunk) {
            let event2 = new CustomEvent('gameEvent', {
                detail: { type: 'fish_sunk' }
            });
            document.dispatchEvent(event2);
        }

        //check if all ships are sunk

        allShipsSunk = ship_array.every(ship => !ship.isalive);

        if (allShipsSunk) {
            winGame();
            }
    }

    function toggleOverlay(show) {
        const overlay = document.getElementById('overlay');
        overlay.style.display = show ? 'block' : 'none';
    }
    




    let easterEggSequence = [[6, 0], [9, 0], [0, 5], [8, 0]];
    let currentIndex = 0; 

    let cells = document.querySelectorAll('table.gameBoard td');

    cells.forEach(function(cell) { 
        cell.addEventListener('click', function() {
            let x_pos = parseInt(this.getAttribute('x_pos'));
            let y_pos = parseInt(this.getAttribute('y_pos'));
    
            if (x_pos === easterEggSequence[currentIndex][0] && y_pos === easterEggSequence[currentIndex][1]) {
                console.log('correct');
                currentIndex++;
    
                if (currentIndex === easterEggSequence.length) {
                    winGame();
                }
            } else {
                currentIndex = 0; 
            }
    
            unhideCell(x_pos, y_pos); 
        });
    });


    function winGame(){
        toggleOverlay(true); 

        let event = new CustomEvent('gameEvent', {
            detail: { type: 'winEvent' }
        });
        document.dispatchEvent(event); 

        setTimeout(function(){
            let form = document.createElement('form');
            form.method = 'POST';
            form.action = 'win.php';

            let input1 = document.createElement('input');
            input1.type = 'hidden';
            input1.name = 'points';
            input1.value = points; 

            form.appendChild(input1);
            document.body.appendChild(form);
            form.submit();
        }, 3000);

    }
    

    // ---Game events---

    //audio_sfx
    let hitSound = new Audio('static/sfx/fish_strike.mp3');
    let missSound = new Audio('static/sfx/water_splash.mp3');
    let winSound = new Audio('static/sfx/win_sound_effect.mp3');
    let buttonSound = new Audio('static/sfx/buttonclick1.mp3');

    document.addEventListener('gameEvent', function (e) {
        let sound;
        if (e.detail.type === 'ship_hit') {
            sound = new Audio('static/sfx/fish_strike.mp3'); 
            sound.play()

        } else if (e.detail.type === 'water_hit') {
            sound = new Audio('static/sfx/water_splash.mp3'); 
            sound.play()

        } else if (e.detail.type === 'winEvent') {
            console.log('winEvent');
            sound = new Audio('static/sfx/win_sound_effect.mp3'); 
            sound.play()
        } else if (e.detail.type === 'fish_sunk') {
            sound = new Audio('static/sfx/fishfloat.mp3'); 
            sound.play()
        }



    });
    

});
