document.addEventListener("DOMContentLoaded", (event) => {

    if (window.hasError) {

        document.body.style.transform = 'translateY(-15vh)'; 

        document.body.style.transition = 'transform 0.75s';

        setTimeout(() => {

            document.body.style.transform = 'translateY(-120vh)';

        }, 100);

    }

    // Animación desplace playa-agua
    document.body.style.transform = 'translateY(-15vh)'; 
    document.body.style.transition = 'transform 0.75s'; 
    setTimeout(() => {
        document.body.style.transform = 'translateY(-120vh)';
    }, 100);


    // TIMER START ---
    let seconds = 0;
    let minutes = 0;

    setInterval(() => {
        seconds++;
        if (seconds === 60) {
            seconds = 0;
            minutes++;
        }
        document.querySelector('.timer').innerText = String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0');
    }, 1000); //intervalo de un segundo


    //POINTS ---
    let points = 0;
    let streakWater = 0;
    let streakHit = 0;
    const pointsElement = document.querySelector('.points');
    pointsElement.innerText = points;

    //suma points
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
        
        pointsElement.innerText = points;
    }

    //resta points
    function subtractPoints(){
        streakHit=0;
        streakWater++;
        points -= streakWater;
       
        pointsElement.innerText = points;
    }


    // CELL FUNCTIONS ---

    //click de destapar cell dependiendo del estado de la celda
    function unhideCell(x_pos, y_pos, board, typePlayer) {
        let event;
        let tablename;
        //para destapar celdas de player
        if (typePlayer=="player"){
            if (document.querySelector(".tutorial-left-side")) {
                tablename=".tutorial-left-side .gameBoard";
            }
            else if (document.querySelector(".game-left-side")) {
                tablename=".game-left-side .gameBoard";
            }
            
        }
        //para destapar celdas del bot
        else if (typePlayer=="bot"){
            tablename=".bot-board .gameBoard";
        }

        //si el estado de la celda contiene un ship, se cambia a ship-hit, se pone del color, se llama al evento de golpeo
        if (board[y_pos][x_pos]['state'] === "show_ship") {
            board[y_pos][x_pos]['state'] = "ship_hit";
            let cell = document.querySelector(`${tablename} td[x_pos='${x_pos}'][y_pos='${y_pos}']`);
            if (cell) {
                cell.style.backgroundColor = '#FF1355';
                cell.innerHTML="X";
                //player
                if (typePlayer=="player"){
                    addPoints();
                    event = new CustomEvent('gameEventPlayer', {
                        detail: { type: 'ship_hit'}
                    });
                }
                //bot
                else if (typePlayer=="bot"){
                    event = new CustomEvent('gameEventBot', {
                        detail: { type: 'ship_hit'}
                    });
                }
                
            }
        
        //si el estado de la celda contiene agua, se cambia a water-hit, se pone el color, se llama al evento de waterhit
        } else if (board[y_pos][x_pos]['state'] === "water") {
            board[y_pos][x_pos]['state'] = "water_hit";
            let cell = document.querySelector(`${tablename} td[x_pos='${x_pos}'][y_pos='${y_pos}']`);
            if (cell) {
                cell.style.backgroundColor = 'lightblue';
                //player
                if (typePlayer=="player"){
                    subtractPoints();
                    event = new CustomEvent('gameEventPlayer', {
                        detail: { type: 'water_hit' }
                    });
                }
                //bot
                else if (typePlayer=="bot"){
                    event = new CustomEvent('gameEventBot', {
                        detail: { type: 'water_hit' }
                    });
                }
                
            }
        }

        //si hay evento, se envia el evento
        if (event) {
            document.dispatchEvent(event);
        }

        //después del evento, se comprueba si se ha hundido el barco despues del golpe, si se ha hundido la funcion pone la foto
        
        //chekeamos el del player
        checkShipsStatus(window.player_ShipsArray, window.player_BoardArray, "player");

        //si hay bot, chekeamos el del bot
        if (typeof bot_ShipsArray != 'undefined') {
            checkShipsStatus(window.bot_ShipsArray, bot_BoardArray, "bot");
        }

        //consola check
        console.log(window.player_BoardArray);
    }


// funcion que pone las fotos en el tablero
function showShipInBoard(ship){

    let vertical = false;
    if(ship.pos.length > 1){

        if (ship.pos[0][0] == ship.pos[1][0]){
            vertical = true;
        }
    }else{
        vertical = true;
    }

    let selected_fish = '';

    ship.pos.forEach(([x, y], index) => {
        let cell = document.querySelector(`td[x_pos='${x}'][y_pos='${y}']`);
        switch (ship.pos.length) {
            case 1:
                selected_fish = 'star';
                break;
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
                cell.style.backgroundImage = `url('static/img/${selected_fish}Divided/${selected_fish}${index+1}.png')`;
                cell.style.backgroundSize = 'cover';
                cell.style.backgroundPosition = 'center';
                cell.style.backgroundColor = '#3a92b2';
                cell.style.transform = 'rotate(90deg)';
                cell.innerHTML="";

            } else {
                cell.style.backgroundImage = `url('static/img/${selected_fish}Divided/${selected_fish}${index+1}.png')`;
                cell.style.backgroundSize = 'cover';
                cell.style.backgroundPosition = 'center';
                cell.style.backgroundColor = '#3a92b2';
                cell.innerHTML="";
            }
        });
        
    }


    //funcion para mostrar todos los barcos en el tablero enemigo
    function showAllShipsOnBoard(array_ships){
        array_ships.forEach(ship => {
            let vertical = false;
            if(ship.pos.length > 1){
        
                if (ship.pos[0][0] == ship.pos[1][0]){
                    vertical = true;
                }
            }else{
                vertical = true;
            }

        let selected_fish = '';

        ship.pos.forEach(([x, y], index) => {
            let cell = document.querySelector(`.bot-board td[x_pos='${x}'][y_pos='${y}']`);
            
            switch (ship.pos.length) {
                case 1:
                    selected_fish = 'star';
                    break;                
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
                cell.style.backgroundImage = `url('static/img/${selected_fish}Divided/${selected_fish}${index+1}.png')`;
                cell.style.backgroundSize = 'cover';
                cell.style.backgroundPosition = 'center';
                cell.style.backgroundColor = '#3a92b2';
                cell.style.transform = 'rotate(90deg)';

            } else {
                cell.style.backgroundImage = `url('static/img/${selected_fish}Divided/${selected_fish}${index+1}.png')`;
                cell.style.backgroundSize = 'cover';
                cell.style.backgroundPosition = 'center';
                cell.style.backgroundColor = '#3a92b2';
            }
        });
        
        });
    }

    //si existe un array de bot, llamamos a la funcion de poner imagenes de peces en el tablero de la derecha
    if (typeof bot_ShipsArray != 'undefined') {
        showAllShipsOnBoard(bot_ShipsArray); 
    }


    // NOTIFICATIONS ---
    function showToastNotification(message, type) {
        let toastContainer = document.getElementById('toast-container');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.id = 'toast-container';
            toastContainer.style.position = 'fixed'; 
            toastContainer.style.bottom = '-220px';
            toastContainer.style.left = '20px';
            toastContainer.style.zIndex = '9999';
            document.body.appendChild(toastContainer);
        }
    
        const toast = document.createElement('div');
        toast.classList.add('toast');
        toast.textContent = message;
    
        toast.style.padding = '10px 20px';
        toast.style.margintop = '1000px';
        toast.style.borderRadius = '5px';
        toast.style.color = '#fff';
        toast.style.fontSize = '14px';
        toast.style.boxShadow = '0 2px 10px rgba(0,0,0,0.1)';
        toast.style.opacity = '0';
        toast.style.transition = 'opacity 0.5s ease-in-out';
        toast.style.display = 'block'; 
        toast.style.position = 'absolute';
    
        switch(type) {
            case 'hit_player':
                toast.style.backgroundColor = '#28a745'; 
                break;
            case 'hit_bot':
                toast.style.backgroundColor = '#dc3545'; 
                break;
            case 'lose':
                toast.style.backgroundColor = '#dc3545'; 
                break;
            case 'win':
                toast.style.backgroundColor = '#ffc107'; 
                toast.style.color = '#000';
                break;
            case 'water':
                toast.style.backgroundColor = '#17a2b8'; 
                break;
            case 'sunk':
                toast.style.backgroundColor = '#1739b8';
                break;
            default:
                toast.style.backgroundColor = '#6c757d'; 
        }
    
        toastContainer.appendChild(toast);
    
        setTimeout(() => {
            toast.style.opacity = '1';
        }, 100); 
    
        setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => {
                toast.remove();
            }, 500); 
        }, 3000); //en pantalla durante 3seg
    }
    

    
    
    //funcion para chekear si todos los barcos se han hundido, si se han hundido llama a WinGame funcion
    function checkShipsStatus(ship_array, board_array, typePlayer) {
        let fish_sunk = false;
        let allShipsSunk = false;

        console.log(ship_array);

        ship_array.forEach(ship => {
            let allCellsHit = true; 

            ship.pos.forEach(([x, y]) => {
                if (board_array[y][x]['state'] !== 'ship_hit') {
                    allCellsHit = false;
                }
            });

            //si todas las celdas de un barco han sido tocadas se cambia el estado de todas ellas a "fish_sunk" y se llama a la funcion que muestra las imagenes en pantalla
            if (allCellsHit) {
                ship.pos.forEach(([x, y]) => {
                    board_array[y][x]['state'] = 'fish_sunk'; 
                });
                ship.isalive = false; 
                if (typePlayer=="player"){
                    showShipInBoard(ship); //en caso del player mostramos el fish en el tablero
                }
                fish_sunk = true; 
            }
        });

        //evento que saca mensaje y sonido de Hundido
        if (fish_sunk && typePlayer=="player") {
            let event2 = new CustomEvent('gameEventPlayer', {
                detail: { type: 'fish_sunk' }
            });
            document.dispatchEvent(event2);
        }
        else if (fish_sunk && typePlayer=="bot") {
            let event2 = new CustomEvent('gameEventBot', {
                detail: { type: 'fish_sunk' }
            });
            document.dispatchEvent(event2);
        }

        
        //si todos estan hundidos, lanza el win
        allShipsSunk = ship_array.every(ship => !ship.isalive);

        if (allShipsSunk && typePlayer=="player") {
            winGame();
            }

        else if (allShipsSunk && typePlayer=="bot") {
            loseGame();
            }
    }


    //funcion que activa y desactiva el overlay encima de la tabla para que no se pueda interactuar
    function toggleOverlay(show) {
        const overlay = document.getElementById('overlay');
        overlay.style.display = show ? 'block' : 'none';
    }
    



    // EASTER EGG ---
    let easterEggSequence = [[6, 0], [9, 0], [0, 5], [8, 0]];
    let currentIndex = 0; //cuantas coordenadas seguidas se han cumplido

    let cells = document.querySelectorAll('table.gameBoard td');

    //EVENT LISTENER CLICK ---
    //evento funcion que comprueba si cada click está siguiendo el patron del easter egg
    /*
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
                currentIndex = 0; //se reinicia el index
            }
    
            unhideCell(x_pos, y_pos, window.player_BoardArray, "player"); 
        });
    });
    */


    // WIN GAME ---
    function winGame(){
        toggleOverlay(true); //se pone el overlay

        //evento se crea y se llama, hace sonido y notificiacion
        let event = new CustomEvent('gameEventPlayer', {
            detail: { type: 'winEvent' }
        });
        document.dispatchEvent(event); 

        //espera 3 segundos, crea un formulario POST invisible que manda los puntos a win.php, y te lleva a win.php
        setTimeout(function(){
            let form = document.createElement('form');
            form.method = 'POST';
            form.action = 'win.php';

            let input1 = document.createElement('input');
            input1.type = 'hidden';
            input1.name = 'points';
            input1.value = points +500; //añadimos extra por ganar partida
            
            
            form.appendChild(input1);
            document.body.appendChild(form);
            form.submit();
        }, 3000);
    }


    // LOSE GAME ---
    function loseGame(){
        toggleOverlay(true); //se pone el overlay

        //evento se crea y se llama, hace sonido y notificiacion
        let event = new CustomEvent('gameEventPlayer', {
            detail: { type: 'loseEvent' }
        });
        document.dispatchEvent(event); 

        //espera 3 segundos, crea un formulario POST invisible que manda los puntos a win.php, y te lleva a win.php
        setTimeout(function(){
            let form = document.createElement('form');
            form.method = 'POST';
            form.action = 'lose.php';

            let input1 = document.createElement('input');
            input1.type = 'hidden';
            input1.name = 'points';
            input1.value = points; 

            form.appendChild(input1);
            document.body.appendChild(form);
            form.submit();
        }, 3000);
    }


    //LOSE GAME

    function loseGame(){
        toggleOverlay(true); 

        let event = new CustomEvent('gameEvent', {
            detail: { type: 'loseEvent' }
        });
        document.dispatchEvent(event); 

        setTimeout(function(){
            let form = document.createElement('form');
            form.method = 'POST';
            form.action = 'lose.php';

            let input1 = document.createElement('input');
            input1.type = 'hidden';
            input1.name = 'points';
            input1.value = points+500; 

            form.appendChild(input1);
            document.body.appendChild(form);
            form.submit();
        }, 3000);

    }
    


    // GAME EVENTS ---

    //escucha todos los eventos del tipo --- "gameEventPlayer" ---
    document.addEventListener('gameEventPlayer', function (e) {
        let sound; //para que se reescriba el contenido y todos los sonidos puedan sonar aunque sean muy seguidos
        
        if (e.detail.type === 'ship_hit') {
            sound = new Audio('static/sfx/fish_strike.mp3'); 
            sound.play();
            showToastNotification('Peix tocat!', 'hit_player');

        } else if (e.detail.type === 'water_hit') {
            sound = new Audio('static/sfx/water_splash.mp3'); 
            sound.play();
            showToastNotification('Aigua', 'water');

        } else if (e.detail.type === 'winEvent') {
            console.log('winEvent');
            sound = new Audio('static/sfx/win_sound_effect.mp3'); 
            sound.play();
            showToastNotification('Has guanyat!', 'win');

        } else if (e.detail.type === 'fish_sunk') {
            sound = new Audio('static/sfx/fishfloat.mp3'); 
            sound.play();
            showToastNotification('Peix enfonsat!', 'sunk');
        
        } else if (e.detail.type === 'loseEvent') {
            console.log('loseEvent');
            sound = new Audio('static/sfx/game_over.mp3'); 
            sound.play();
            showToastNotification('Has perdut', 'lose');
        
        } 
    });
    
    //escucha todos los eventos del tipo --- "gameEventBot" ---
    document.addEventListener('gameEventBot', function (e) {
        let sound; //para que se reescriba el contenido y todos los sonidos puedan sonar aunque sean muy seguidos
        
        if (e.detail.type === 'ship_hit') {
            sound = new Audio('static/sfx/fish_strike.mp3'); 
            sound.play();
            showToastNotification('Han tocat un peix!', 'hit_bot');

        } else if (e.detail.type === 'water_hit') {
            sound = new Audio('static/sfx/water_splash.mp3'); 
            sound.play();
            showToastNotification('Han tocat aigua', 'water');

        } else if (e.detail.type === 'fish_sunk') {
            sound = new Audio('static/sfx/fishfloat.mp3'); 
            sound.play();
            showToastNotification('Han enfonsat el teu peix!', 'sunk');
        
        } else if (e.detail.type === 'bot_shot') {
            sound = new Audio('static/sfx/throw.mp3'); 
            sound.play();
        } 
    });


    function highlightTable(selector, selected) {
        let table = document.querySelector(selector);
        let color = selected ? 'white' : 'grey';
    
        if (table) {
            table.style.transition = 'border-color 1s';
            let cells = table.querySelectorAll('td, th');
            cells.forEach(function(cell) {
                cell.style.transition = 'border-color 1s';
            });
    
            table.style.border = `3px solid ${color}`;
            cells.forEach(function(cell) {
                cell.style.border = `3px solid ${color}`;
            });
        } else {
            console.error(`No s'ha trobat una taula amb el selector: ${selector}`);
        }
    }




    // JUEGO PARTIDA CLASSICA ------
    function classicGame() {
        let lastShootBot = null;
        let lastHitBot = null ;
        let gameStart = true;
        let playerProjectiles = 40;
        let botProjectiles = 40;
        let shipsSunk = 0;

        let x_bot, y_bot;

        //restar proyectiles player
        function updatePlayerProjectiles() {
            playerProjectiles--;
            document.getElementById('projectileCount').innerText = playerProjectiles;  
        }
    

        //restar proyectiles bot
        function updateBotProjectiles() {
            botProjectiles--;
            document.getElementById('bot-projectiles').innerText = botProjectiles;
        }

        function playerTurn() {
            console.log("TURNO PLAYER");
            if (playerProjectiles===0){
                let count_ship_bot = 0;
                let count_ship_player = 0;

                window.player_ShipsArray.forEach(ship =>{
                    if (ship.isalive == false){
                        count_ship_player++;
                    }
                })

                window.bot_ShipsArray.forEach(ship =>{
                    if (ship.isalive == false){
                        count_ship_bot++;
                    }
                })

                if (count_ship_player>count_ship_bot){
                    winGame();
                    return;
                }
                else if(count_ship_player<count_ship_bot){
                    loseGame();
                    return;
                }
                else{
                    loseGame();
                    return;
                }
            }
            toggleOverlay(false);
            // Insertar efecto de que el jugador está jugando
            highlightTable(".game-left-side .gameBoard", true);
            highlightTable(".bot-board .gameBoard", false);    
            
            cells.forEach(function(cell) {
                cell.removeEventListener('click', classicGameClick);
                cell.addEventListener('click', classicGameClick);
            });
        }

        // funcion para click y para easter egg
        function classicGameClick(event) {
            let cell = event.target;
            let x_pos = parseInt(cell.getAttribute('x_pos'));
            let y_pos = parseInt(cell.getAttribute('y_pos'));

            // easter egg
            if (x_pos === easterEggSequence[currentIndex][0] && y_pos === easterEggSequence[currentIndex][1]) {
                console.log('correct');
                currentIndex++;

                if (currentIndex === easterEggSequence.length) {
                    winGame();
                    return; //salir de funcion
                }
            } else {
                currentIndex = 0; // reinicia secuencia si falla el easter egg
            }

            // LOGICA turno player
            if (window.player_BoardArray[y_pos][x_pos]['state'] === "water") {
                updatePlayerProjectiles(); //restar proyectil
                unhideCell(x_pos, y_pos, window.player_BoardArray, "player"); // Mostrar disparo
                toggleOverlay(true); 
                botTurn(); 
                return;
            } else if (window.player_BoardArray[y_pos][x_pos]['state'] === "show_ship") {
                updatePlayerProjectiles(); //restar proyectil
                unhideCell(x_pos, y_pos, window.player_BoardArray, "player"); // Mostrar disparo
                playerTurn();
                return;
            }

        }

        function botTurn() {
            console.log("TURNO BOT");
            toggleOverlay(true);
            if (botProjectiles==0){
                playerTurn();
                return;
            }

            highlightTable(".bot-board .gameBoard", true);
            highlightTable(".game-left-side .gameBoard", false);

            let shootable = false;

            //si hemos tocado algo en los ultimos turnos pero no hundido
            if (lastHitBot && lastHitBot[2] === 'show_ship' && window.bot_BoardArray[lastHitBot[0]][lastHitBot[1]]['status']!='fish_sunk') {
                
                //si en el ultimo turno hemos acertado
                if (lastHitBot[0]==lastShootBot[0] && lastHitBot[1]==lastShootBot[1]){

                    // Disparar a la derecha
                    if (lastHitBot[1] + 1 <= 10 && (window.bot_BoardArray[lastHitBot[0]][lastHitBot[1] + 1]['state'] === 'water' || 
                        window.bot_BoardArray[lastHitBot[0]][lastHitBot[1] + 1]['state'] === 'show_ship')) {
                        x_bot = lastHitBot[1] + 1;
                        y_bot = lastHitBot[0]; 
                        shootable = true;
                    }
                    // Disparar abajo
                    else if (lastHitBot[0] + 1 <= 10 && (window.bot_BoardArray[lastHitBot[0] + 1][lastHitBot[1]]['state'] === 'water' || 
                        window.bot_BoardArray[lastHitBot[0] + 1][lastHitBot[1]]['state'] === 'show_ship')) {
                        x_bot = lastHitBot[1];
                        y_bot = lastHitBot[0] + 1;
                        shootable = true;
                    }
                    // Disparar a la izquierda
                    else if (lastHitBot[1] - 1 >= 1 && (window.bot_BoardArray[lastHitBot[0]][lastHitBot[1] - 1]['state'] === 'water' || 
                        window.bot_BoardArray[lastHitBot[0]][lastHitBot[1] - 1]['state'] === 'show_ship')) {
                        x_bot = lastHitBot[1] - 1;
                        y_bot = lastHitBot[0];
                        shootable = true;
                    }
                    // Disparar arriba
                    else if (lastHitBot[0] - 1 >= 1 && (window.bot_BoardArray[lastHitBot[0] - 1][lastHitBot[1]]['state'] === 'water' || 
                        window.bot_BoardArray[lastHitBot[0] - 1][lastHitBot[1]]['state'] === 'show_ship')) {
                        x_bot = lastHitBot[1];
                        y_bot = lastHitBot[0] - 1;
                        shootable = true;
                    }

                    //si ninguna se puede, por si acaso, disparará aleatorio, para que no de error
                    else{
                        while (!shootable) {
                            y_bot = Math.floor(Math.random() * 10) + 1;
                            x_bot = Math.floor(Math.random() * 10) + 1;
                            
                            if (window.bot_BoardArray[y_bot][x_bot]['state'] === 'water' || 
                                window.bot_BoardArray[y_bot][x_bot]['state'] === 'show_ship') {
                                shootable = true;
                            }
                        }
                    }
                }
            }


            //disparo aleatorio
            else {
                while (!shootable) {
                    y_bot = Math.floor(Math.random() * 10) + 1;
                    x_bot = Math.floor(Math.random() * 10) + 1;
                    
                    if (window.bot_BoardArray[y_bot][x_bot]['state'] === 'water' || 
                        window.bot_BoardArray[y_bot][x_bot]['state'] === 'show_ship') {
                        shootable = true;
                    }
                }
            }

           //para saber donde dispararemos
            console.log(y_bot, x_bot);

            // guardamos el ultimo tiro siempre
            lastShootBot = [y_bot, x_bot, window.bot_BoardArray[y_bot][x_bot]['state'] ];
            
            //si da con un barco, guardamos esa posicion en lasthitbot
            if (window.bot_BoardArray[y_bot][x_bot]['state']=='show_ship'){
                lastHitBot = [y_bot, x_bot, window.bot_BoardArray[y_bot][x_bot]['state'] ];
            }
            //si ha tocado agua quitamos el lasthitbot porque no ha hiteado nada
            else if (window.bot_BoardArray[y_bot][x_bot]['state']=='water'){
                lastHitBot = null ;
            }



            //evento de disparo sonido
            let event = new CustomEvent('gameEventBot', {
                detail: { type: 'bot_shot' }
            });
            document.dispatchEvent(event); 

            // disparo
            setTimeout(() => {
                if (window.bot_BoardArray[y_bot][x_bot]['state'] === "water") {
                    updateBotProjectiles(); //restar proyectil
                    unhideCell(x_bot, y_bot, window.bot_BoardArray, "bot");
                    toggleOverlay(false);
                    playerTurn();
                    return;
                } else if (window.bot_BoardArray[y_bot][x_bot]['state'] === "show_ship") {
                    unhideCell(x_bot, y_bot, window.bot_BoardArray, "bot");
                    //si hunde el barco, quitamos lasthitbot
                    if (window.bot_BoardArray[y_bot][x_bot]['state']=='fish_sunk'){
                        lastHitBot= null;
                    }
                    updateBotProjectiles(); //restar proyectil
                    botTurn();
                    return;
                }
            }, 3000); // espera 3seg


        }

        if (gameStart) {
            playerTurn();
        }
    }





    //JUEGO TUTORIAL -------
    function tutorialGame(){
        // limpiar listeners previos si hay
        cells.forEach(function(cell) {
            cell.removeEventListener('click', singlePlayerClick);
        });
        //añade listener
        cells.forEach(function(cell) { 
            cell.addEventListener('click', singlePlayerClick);
        });

    
        function singlePlayerClick(event) {
            let cell = event.target;
            let y_pos = parseInt(cell.getAttribute('y_pos'));
            let x_pos = parseInt(cell.getAttribute('x_pos'));
            
            // easter egg
            if (x_pos === easterEggSequence[currentIndex][0] && y_pos === easterEggSequence[currentIndex][1]) {
                console.log('correct');
                currentIndex++;
        
                if (currentIndex === easterEggSequence.length) {
                    winGame();
                    return;
                }
            } else {
                currentIndex = 0; //reiniciar si no coincide
            }

            unhideCell(x_pos, y_pos, window.player_BoardArray, "player"); 
        }

        
    }



    // MAIN ------
    if (typeof bot_ShipsArray != 'undefined') {
        console.log("INICIANDO JUEGO CLASICO");
        classicGame();
    }
    else{
        console.log("INICIANDO JUEGO TUTORIAL");
        tutorialGame();

    }



});

