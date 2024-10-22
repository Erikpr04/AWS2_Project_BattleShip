<?php
session_start();

// Verificar si el nombre de usuario está almacenado en la sesión
if (
    !isset($_SERVER['HTTP_REFERER']) ||
    (strpos($_SERVER['HTTP_REFERER'], 'index.php') === false && strpos($_SERVER['HTTP_REFERER'], 'game.php') === false)
) {
    // Si no es referida desde la página del juego, retorna un 403
    header('HTTP/1.1 403 Forbidden');
    echo " <div id='finalForbiScreen'>

<h2>403 Forbidden: Has de accedir desde Index</h2>

</div>";
    exit();
}
?>






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


/**
 * Creates a cell object with given $x and $y coordinates and "none" as initial state.
 * @param int $x x coordinate of the cell
 * @param int $y y coordinate of the cell
 * @return array cell object with given coordinates and initial state
 */
function createCell($x, $y)
{
    return array('x_pos' => $x, 'y_pos' => $y, 'state' => "none");
}


/**
 * Sets the state of all cells (except the border cells) in the given board to "water".
 * @param array $board the board to set the water cells in
 * @return array the same board with the water cells set
 */
function assignWaterCells($board)
{

    for ($y = 1; $y < count($board); $y++) {
        for ($x = 1; $x < count($board[$y]); $x++) {
            $board[$y][$x]['state'] = "water";
        }
    }
    return $board;
}


function createBoard($max_x, $max_y)
{
    $board = array_fill(0, 10, array_fill(0, 10, 0));
    for ($y = 0; $y < $max_y; $y++) {
        for ($x = 0; $x < $max_x; $x++) {
            $board[$y][$x] = createCell($x, $y);
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

function create_ship($length, $positions)
{
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

function generateRandomPositions($length)
{
    $positions = array();

    // 0 - Vertical
    // 1 - Horizontal
    if (rand(0, 1) == 0) {
        // Vertical

        $x = rand(1, 10); 
        $y = rand(1, 10 - $length); #18
        for ($j = 0; $j < $length; $j++) {
            $positions[] = [$x, $y + $j];
        }
    } else {
        // Horizontal
        $x = rand(1, 10 - $length); 
        $y = rand(1, 10); 
        for ($j = 0; $j < $length; $j++) {
            $positions[] = [$x + $j, $y];
        }
    }
    return $positions;
}


function isTestShipPositionCollapsingShips($ships_array, $test_positions) {    foreach ($test_positions as $position) {
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
                    ($pos_x == $ship_x && $pos_y + 1 == $ship_y) ||
                    ($pos_x == $ship_x && $pos_y == $ship_y)    
                ) {
                    return true;
                }
            }
        }
    }

    return false;
}








function generateShipArray($quantityship1,$quantityship2,$quantityship3,$quantityship4,$quantityship5) {
    $ships_array = array();
    $valid_positions = false;
    for ($i = 1; $i <= 5; $i++) {
        switch ($i) {
            case 1:
                $selectedLength = $quantityship1;
                break;
            case 2:
                $selectedLength = $quantityship2;
                break;
            case 3:
                $selectedLength = $quantityship3;
                break;
            case 4:
                $selectedLength = $quantityship4;
                break;
            case 5:
                $selectedLength = $quantityship5;
                break;
            }
            

            for ($j = 1; $j <= $selectedLength; $j++) {
                $valid_positions = false;
                while (!$valid_positions) {
                    $test_positions = generateRandomPositions($i);
        
                    if (!isTestShipPositionCollapsingShips($ships_array, $test_positions)) {
                        $valid_positions = true;
                    }
                }
                $ships_array[] = create_ship($i, $test_positions);

            }
        }


       //Debug   
       // foreach ($test_positions as $position) {
       //     echo "x = " . print_r($position[0] . " y = " . $position[1], true) . "<br>";
       //}
        
        echo "<br>";
    return $ships_array;
}






/**
 * Inserts cells into given board. Returns the same board with cells inserted
 * @param array $board board to insert cells into
 * @return array board with cells inserted
 */
function InsertCellsinBoard($board)
{

    for ($y = 0; $y < count($board); $y++) {
        for ($x = 0; $x < count($board[$y]); $x++) {
            $board[$y][$x] = createCell($x, $y);
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
function displayBoard($board)
{
    echo "<table class ='gameBoard'>";
    echo "<h3 class='usernameTag'>{$_SESSION['username']}</h3>";

    for ($y = 0; $y < count($board); $y++) {
        echo "<tr>";
        for ($x = 0; $x < count($board[$y]); $x++) {
            if ($y == 0 and $x != 0) {
                $letter = chr(64 + $x);
                echo "<td x_pos='$x' y_pos='$y'>";
                echo "$letter";
                echo "</td>";
            } else if ($x == 0 and $y != 0) {
                echo "<td x_pos='$x' y_pos='$y'>";
                echo "$y";
                echo "</td>";
            } else {
                if ($board[$y][$x]['state'] == "water" or $board[$y][$x]['state'] == "none") {
                    echo "<td x_pos='$x' y_pos='$y'>";
                    echo " ";
                    echo "</td>";
                } else if ($board[$y][$x]['state'] == "show_ship") {
                    echo "<td x_pos='$x' y_pos='$y'>";
                    echo " ";
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
function displayShips($shipsArray, $board): array
{

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

$main_array = createBoard(11, 11);
$main_array = InsertCellsinBoard($main_array);
$main_array = assignWaterCells($main_array);

$ships_array = generateShipArray(4,3,2,1,0);
$main_array = displayShips($ships_array,$main_array);




?>





<body class="bodyIndexGame">
    <div class="overlay" id="overlay"></div>
    <div class="beach">
    <main class="mainContent">
    <div class="backgroundIndex">
        <div class="containerIndex">
            <div class="titleIndex">
                <h1>Shoreline Strike</h1>
            </div>
            <div class="panelIndex">
                <form method="post">
                    <p>Enter your username:</p>
                    <input type="text" name="namePlayer" placeholder="Enter your username" required>
                    <button type="submit" name="play">Play</button>
                </form>
            </div>
        </div>
    </div>
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
    window.mainArray = <?php echo json_encode($main_array); ?>;
    window.shipsArray = <?php echo json_encode($ships_array); ?>;
    window.hasError = <?php echo isset($_SESSION['username']) ? true : false; ?>;
    window.username = <?php echo isset($_SESSION['username']) ?>;

</script>

</html>