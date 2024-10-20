<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shoreline Strike</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="game.js" defer></script>


</head>
<?php



//-----BOARD LOGIC-----


//crea una celda con valor X,Y,Estado
function createCell($x,$y) {
    return array('x_pos' => $x, 'y_pos' => $y, 'state' => "none");
}


//itera sobre el tablero y les asigna a todas las casillas el estado "agua"
function assignWaterCells($board) {

    for ($y = 1; $y < count($board); $y++) {
        for ($x = 1; $x < count($board[$y]); $x++) {
                $board[$y][$x]['state'] = "water";
        }
    }
    return $board;
}


//crea un tablero en forma de array llamando al creador de celdas (funcion createCell)
function createBoard($max_x,$max_y) {
    $board = array_fill(0,10,array_fill(0,10,0));
    for ($y = 0; $y < $max_y; $y++) {
        for ($x = 0; $x < $max_x; $x++) {
            $board[$y][$x] = createCell($x,$y);
        }
    }    
    return $board;
}






//-----SHIP LOGIC-----


//crea el barco con su longitud, su array de posiciones (anteriormente creado) y su estado de vida
function create_ship($length,$positions) {
    $ship = array(
        'length' => $length,
        'pos' => $positions,
        'isalive' => true
    );
    return $ship;
}


//crea un array de posiciones dada una longitud para este
 function generateRandomPositions($length) {
    $positions = array();

    // 0 - Vertical
    // 1 - Horizontal
    if (rand(0, 1) == 0) {
        // Vertical
        $x = rand(1, 9); 
        $y = rand(1, 10 - $length + 1); 
        for ($j = 0; $j < $length; $j++) {
            $positions[] = [$x, $y + $j];
        }
    } else {
        // Horizontal
        $x = rand(1, 10 - $length + 1); 
        $y = rand(1, 9); 
        for ($j = 0; $j < $length; $j++) {
            $positions[] = [$x + $j, $y];
        }
    }
    return $positions;
}



//se itera en el array de test para comprobar que no hay ningun barco cerca usando el array de barcos para ver las posiciones
function isTestShipPositionCollapsingShips($ships_array, $test_positions) {
    foreach ($test_positions as $position) {
        $pos_x = $position[0];
        $pos_y = $position[1];

        foreach ($ships_array as $ship) {
            foreach ($ship['pos'] as $ship_pos) {
                $ship_x = $ship_pos[0];
                $ship_y = $ship_pos[1];

                if (
                    ($pos_x - 1 == $ship_x && $pos_y == $ship_y) || 
                    ($pos_x + 1 == $ship_x && $pos_y == $ship_y) ||  
                    ($pos_x == $ship_x && $pos_y - 1 == $ship_y) ||  
                    ($pos_x == $ship_x && $pos_y + 1 == $ship_y)     
                ) {
                    return true;  
                }
            }
        }
    }

    return false;
}







// funcion general de crear el array de barcos
function generateShipArray() {
    $ships_array = array();

    for ($i = 2; $i <= 5; $i++) {
        $valid_positions = false;

        while (!$valid_positions) {
            $test_positions = generateRandomPositions($i);

            if (!isTestShipPositionCollapsingShips($ships_array, $test_positions)) {
                $valid_positions = true;
            }
        }


       //Debug   
       // foreach ($test_positions as $position) {
       //     echo "x = " . print_r($position[0] . " y = " . $position[1], true) . "<br>";
       //}
        
        $ships_array[] = create_ship($i, $test_positions);
        echo "<br>";
    }
    
    return $ships_array;
}




//-----DISPLAY LOGIC-----

// incrusta en la variable Tablero los barcos 
function displayShips($shipsArray,$board): array {

    for ($i = 0; $i < count($shipsArray); $i++) {
        $ship = $shipsArray[$i];
        for ($j = 0; $j < $ship['length']; $j++) {
            $board[$ship['pos'][$j][1]][$ship['pos'][$j][0]]['state'] = "show_ship";
        }
    }

    return $board;
    

}

//crea la tabla html, le pasa los valores a cada celda de su posicion para que la tengan de id y escribe los números y letras
function displayBoard($board) {
    echo "<table class ='gameBoard'>";
    for ($y = 0; $y < count($board); $y++) {
        echo "<tr>";
        for ($x = 0; $x < count($board[$y]); $x++) {
            if ($y == 0 and $x != 0) {
                $letter = chr(64+$x);
                echo "<td x_pos='$x' y_pos='$y'>";
                echo "$letter";
                echo "</td>";
            }
            else if ($x == 0 and $y != 0) {
                echo "<td x_pos='$x' y_pos='$y'>";
                echo "$y";
                echo "</td>";
            }else{
                echo "<td x_pos='$x' y_pos='$y'>";
                    echo " ";
                    echo "</td>";
            }
        }
        echo "</tr>";
    }
    echo "</table>";
}









//-----MAIN-----

$main_array = createBoard(11,11); //se crea el board
$main_array = assignWaterCells($main_array); //se asignan las casillas de agua
$ships_array = generateShipArray(); //se genera el array de barcos
$main_array = displayShips($ships_array,$main_array); //se ponen los barcos dentro del tablero



?>


<!-- EMPIEZA EL BODY -->


<body class="bodyIndexGame">
    <div class="overlay" id="overlay"></div>
    <div class="beach">
    <main class="mainContent">
        <section class="backgroundIndex">
            <div class="containerIndex">
                <h1 class="titleIndex">Shoreline Strike</h1>
                <div class="optionsIndex">
                    <noscript>
                        <button id="buttonPlayIndex" disabled>JUGAR</button>
                    </noscript>
                    <button id="buttonPlayIndex"><a href="game.php">JUGAR</a></button>
                    <br>
                    <button id="buttonRankingIndex"><a href="ranking.php">HALL OF FAME</a></button>
                </div>
            </div>
        </section>
    </main>
    </div>



    <div class="sea">
        <div class="left-side">
            <?php
            //debug prints, it tests the main array cell objects
            //echo "  x_pos: " . $main_array[3][3]['x_pos'];
            //echo "  y_pos: " . $main_array[3][3]['y_pos'];
            //echo "  state: " . $main_array[3][3]['state'];
            //echo "<p id='action'></p>";
            displayBoard($main_array); //se hace el tablero en html
            ?>
        </div>

        <div class="right-side">
            <div class="counter-container">
                <h3>Time: <span class="timer">00:00</span></h3>
                <h3>Points: <span class="points">0</span></h3>
                <h3>Munició: <span id="projectileCount">40</span></h3>
            </div>
        </div>
        </div>
    </div>

</body>

<script>
    window.mainArray = <?php echo json_encode($main_array); ?>;
    window.shipsArray = <?php echo json_encode($ships_array); ?>;
</script>

</html>