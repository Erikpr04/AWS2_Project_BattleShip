<!-- index.php -->

<!DOCTYPE html>
<html lang="en">

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
    </div>
    <div class="backgroundIndex">
        <div class="containerIndex">
            <div class="titleIndex">
                <h1>Shoreline Strike</h1>
            </div>
            <div class="optionsIndex">
                <form  method="post" class="formIndex">
                    <label for="namePlayer" style="font-size: 20px;">Introdueix el tu nom:</label>
                    <br>
                    <input type="text" id="namePlayer" name="namePlayer" style="width: 300px; margin: 0 auto 25px" required>
                    <br>
                    <button id="buttonPlayIndex" type="submit" name="play">Jugar</button>
                </form>
                <button id="buttonRankingIndex"><a href="ranking.php">HALL OF FAME</a></button>
                <?php 
                    if (isset($_POST['play'])) {
                        $namePlayer = trim($_POST['namePlayer']);
                        if (!empty($namePlayer)) {
                            $_SESSION['namePlayer'] = $namePlayer;
                            header('Location: game.php');
                            exit;
                        }
                    }
                ?>
            </div>
        </div>
    </div>
</body>

</html>