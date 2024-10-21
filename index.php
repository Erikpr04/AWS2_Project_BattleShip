
<?php

$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['play'])) {
    $username = trim(string: $_POST['username']);

    if (strpos($username, ';') !== false) {
        echo "<script>alert('El nom no pot conenir el caràcter \" ; \" !'); window.history.back();</script>";
        exit();
    }
    if (strlen($username) < 3) {
        echo "<script>alert('El nom ha de contenir mínim 3 caràcters!'); window.history.back();</script>";
        exit();
    }
    if (strlen($username) > 30) {
        echo "<script>alert('El nom no pot sobrepassar els 30 caràcters!'); window.history.back();</script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Shoreline Strike</title>
</head>


<body class="bodyIndex">
    <div class="descriptionIndex">
        <h2>Descripción del Juego</h2>
        <p>
            "Hundir la Flota" es un juego clásico de estrategia naval, en el cual dos jugadores compiten para hundir los barcos del oponente.
            Cada jugador coloca sus barcos en un tablero cuadrado y, por turnos, intenta adivinar las posiciones de los barcos del otro.
        </p>
        <p>
            El tablero tiene un tamaño típico de 10x10, donde las filas están etiquetadas con letras y las columnas con números.
            Los peces se colocan horizontal o verticalmente, y varían en tamaño. Los tipos de pueden pueden incluir:
        </p>
        <ul>
            <li>Anguila (5 casillas)</li>
            <li>Pez espada (4 casillas)</li>
            <li>Calamar (3 casillas)</li>
            <li>Pez (2 casillas)</li>
        </ul>
        <p>
            Los jugadores deben marcar cada disparo en el tablero.
            Si aciertan en la ubicación de un pez, se anota como un "tocado"; si fallan, es "agua".
            El objetivo del juego es cazar todos los peces del oponente antes de que cacen los tuyos.
        </p>
        <p>
            Este juego ha sido una popular actividad de entretenimiento desde su invención, proporcionando diversión y desarrollando habilidades de pensamiento estratégico y lógico.
        </p>
    </div>
    <div class="optionsGameIndex">
        <ul>
            <p>OPCIONS</p>
            <li><input type="checkbox" id="bulletIlimited" style="cursor:pointer"><label for="bulletIlimited">Munició ilimitada</label></li>
            <li><input type="checkbox" id="armoredShips" style="cursor:pointer"><label for="armoredShips">Vaixells acoirassats</label></li>
            <li><input type="checkbox" id="specialAttack" style="cursor:pointer"><label for="specialAttack">Atacs especials</label></li>
        </ul>
                <form method="post" action="game.php">
                    <p>Introduïu el vostre nom d'usuari:</p>
                    <input type="text" name="username" placeholder="usuari">
                    <button type="submit" name="play">Play</button>
                </form>
                <button onclick="window.location.href='ranking.php'">HALL OF FAME</button>
            </div>
            <?php
            $username = trim($_POST['username']);
            if (!empty($username)) {
                $_SESSION['username'] = $username;
                exit;
            } 
            ?>
        </div>
    </div>
</body>


</html>