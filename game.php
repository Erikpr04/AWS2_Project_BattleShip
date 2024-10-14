<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shoreline Strike</title>
    <style>

body {
    background-color: lightblue;
    font-family: 'Geometry Soft Pro', sans-serif;
    overflow: hidden; 
    display: flex;
    flex-direction: column; 
    height: 100vh; 
    overflow: hidden; 
    margin: 0;
}

.beach {
    background: linear-gradient(to bottom, yellow 90%, rgba(255, 255, 255, 0) 100%); /* Degradado en el 20% inferior */
    height: 100%; 
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    position: absolute; 
    top: 0;
    left: 0;
    z-index: 1;
    overflow: hidden; 
}


.sea {
    width: 90%;
    height: 50%;

    z-index: 0;
    margin-top: 105vh;
    margin-left: auto;
    margin-right: auto;

}


.left-side {
    float: left;
    width: 70%;
    overflow: hidden;
}

.right-side {
    float: right;
    width: 30%;
    display: block;
}
.counter-container{
    margin-left: auto;
    margin-right: auto;
}

table {
    display: flex;
    margin-left: 30%;
    margin-right: auto; 

}

/* Estilos para las celdas */
td, th {
    border: 2px dotted ; /* Líneas divisorias como redes entre boyas */
    padding: 5px 10px; 
    text-align: center;
    box-shadow: 0 0 2px; /* Sombra para dar profundidad */
}

/* Marca como seleccionado solo el <td> en el que haces hover, a partir de la segunda fila y segunda columna */
tr:not(:first-child) td:not(:first-child):hover {
    background-color: tomato
}



tr:first-child,td:first-child{
    font-size: 30px;
} 





@font-face {
    font-family: 'Geometry Soft Pro';
    src: url('GeometrySoftPro-BoldN.woff2') format('woff2'),
    font-weight: bold;
    font-style: normal;
}

.counter-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    border: 2px solid #000;
    padding: 20px;
    width: 200px;
    margin: 0 auto;
}

.counter-container h3 {
    margin-bottom: 10px;
}

.timer, .points {
    font-size: 24px;
    font-weight: bold;
    color: #333;
}

.buttons {
    margin-top: 20px;
}
    </style>
</head>
<?php



//-----BOARD LOGIC-----



function createCell($x,$y) {
    return array('x_pos' => $x, 'y_pos' => $y, 'state' => "none");
}


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


/**
 * Returns a ship with given length
 * @param int $length length of ship
 * @return array ship with given length
 */

function create_ship($length,$positions) {
    $ship = array(
        'length' => $length,
        'pos' => $positions,
        'isalive' => true
    );
    return $ship;
}



/**
 * Generates an array of positions for a ship with given length.
 * The positions will either be horizontal or vertical and will fit within the 10x10 board.
 * @param int $length length of ship
 * @return array list of positions for the ship
 */

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



/**
 * Checks if there are any ships in the given ships array that are adjacent to any cell in the given test_positions array.
 * Two cells are considered adjacent if they share an edge.
 * @param array $ships_array array of ships
 * @param array $test_positions array of positions to check
 * @return boolean true if there is a ship adjacent to any cell in the test_positions array, false otherwise
 */
function checkIfCellsAround($ships_array, $test_positions) {
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







/**
 * Generates an array of ships with random positions.
 * The ships will be of length 2, 3, 4, and 5, and will not be adjacent to each other.
 * @return array array of ships with random positions
 */
function generateShipArray() {
    $ships_array = array();

    for ($i = 2; $i <= 5; $i++) {
        $valid_positions = false;

        while (!$valid_positions) {
            $test_positions = generateRandomPositions($i);

            if (!checkIfCellsAround($ships_array, $test_positions)) {
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






/**
 * Inserts cells into given board. Returns the same board with cells inserted
 * @param array $board board to insert cells into
 * @return array board with cells inserted
 */
function InsertCellsinBoard($board) {

   for ($y = 0; $y < count($board); $y++) {
       for ($x = 0; $x < count($board[$y]); $x++) {
           $board[$y][$x] = createCell($x,$y);
       }
   }       
   return $board;
}







//-----DISPLAY LOGIC-----


/**
 * Displays the board given as a parameter.
 * The board is displayed as a HTML table and will include numbers and letters on the outside of the board to label the cells.
 * The state of each cell in the board is reflected in the table. If the state is "none", a space is displayed, and if the state is "show_ship", an X is displayed.
 * @param array $board the board to display
 */
function displayBoard($board) {
    echo "<table class ='gameBoard'>";
    for ($y = 0; $y < count($board); $y++) {
        echo "<tr>";
        for ($x = 0; $x < count($board[$y]); $x++) {
            if ($y == 0 and $x != 0) {
                $letter = chr(64+$x);
                echo "<td>";
                echo "$letter";
                echo "</td>";
            }
            else if ($x == 0 and $y != 0) {
                echo "<td>";
                echo "$y";
                echo "</td>";
            }else{
                if ($board[$y][$x]['state'] == "none") {
                    echo "<td>";
                    echo " ";
                    echo "</td>";
                }else if ($board[$y][$x]['state'] == "show_ship") {
                    echo "<td>";
                    echo "X";
                    echo "</td>";
                }
            }

        }
        echo "</tr>";
    }
    echo "</table>";
}


/**
 * Sets the state of cells in the board to show_ship for each ship in the given ships array.
 * @param array $shipsArray array of ships
 * @param array $board the board to set the ship cells in
 * @return array the same board with the ship cells set
 */
function displayShips($shipsArray,$board): array {

    //sets ship cells state to show_ship

    for ($i = 0; $i < count($shipsArray); $i++) {
        $ship = $shipsArray[$i];
        for ($j = 0; $j < $ship['length']; $j++) {
            $board[$ship['pos'][$j][1]][$ship['pos'][$j][0]]['state'] = "show_ship";
        }
    }

    return $board;
    

}






//-----MAIN-----

$main_array = createBoard(11,11);
$main_array = InsertCellsinBoard($main_array);
$ships_array = generateShipArray();
$main_array = displayShips($ships_array,$main_array);


?>





<body>

    <div class="beach">
        <h1>Shoreline Strike</h1>
        <button id="playButton">Play</button>
        <button id="hallOfFameButton">Hall of Fame</button>
    </div>


    <div class="sea">
        <div class="left-side">
            <?php
            echo "  x_pos: " . $main_array[3][3]['x_pos'];
            echo "  y_pos: " . $main_array[3][3]['y_pos'];
            echo "  state: " . $main_array[3][3]['state'];
            displayBoard($main_array);
            ?>
        </div>

        <div class="right-side">
            <div class="counter-container">
                <h3>Time: <span class="timer">00:00</span></h3>
                <h3>Points: <span class="points">0</span></h3>
            </div>
        </div>
    </div>

</body>
<script>


document.addEventListener("DOMContentLoaded", (event) => {
    document.body.style.transition = 'transform 1s'; 
    setTimeout(() => {
        document.body.style.transform = 'translateY(-90vh)';
    }, 100);
});

document.getElementById('hallOfFameButton').addEventListener('click', function() {
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
</script>

</html>