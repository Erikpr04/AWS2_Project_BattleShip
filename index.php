<!-- index.php -->
<?php
session_start(); 
if (isset($_SESSION['username'])) {
    session_destroy();
}

$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    //CHECKBOX OPCIONES --------
    //municion ilimitada
    $ammoLimited = $_POST['ammoLimited'];
    //ilimitada sí
    if ($ammoLimited == '1') {
        $_SESSION['ammoLimited'] = 1;
    //ilimitada no
    } else {
        $_SESSION['ammoLimited'] = 0;
    }
    
    //armadura
    $armoredShips = $_POST['armoredShips'];
    //armadura sí
    if ($armoredShips == '1') {
        $_SESSION['armoredShips'] = 1;
    //armadura no
    } else {
        $_SESSION['armoredShips'] = 0;
    }

    //ataque especial
    $specialAttack = $_POST['specialAttack'];
    //especial sí
    if ($specialAttack == '1') {
        $_SESSION['specialAttack'] = 1;
    //especial no
    } else {
        $_SESSION['specialAttack'] = 0;
    }
    

    //USERNAME ----------
    $username = $_POST['username']; 
    if (strpos($username, ';') !== false) {
        echo "<script>alert('El nom no pot contenir el caràcter \" ; \" !'); window.history.back();</script>";
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

    $_SESSION['username'] = $username;


    //TIPO GAME ----
    if (isset($_POST['classic'])) {
        header('Location: game.php');
        exit();
    } elseif (isset($_POST['tutorial'])) {
        header('Location: tutorial.php');
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
        <h2>Descripció del Joc</h2>
        <p>
            "Enfonsar la Flota" és un joc clàssic d'estratègia naval, en el qual dos jugadors competeixen per enfonsar els vaixells de l'oponent. 
            Cada jugador col·loca els seus vaixells en un tauler quadrat i, per torns, intenta endevinar les posicions dels vaixells de l'altre.
        </p>
        <p>
            El tauler té una mida típica de 10x10, on les files estan etiquetades amb lletres i les columnes amb números. 
            Els peixos es col·loquen horitzontalment o verticalment, i varien en mida. Els tipus de peixos poden incloure: 
        </p>
        <ul>
            <li>Anguila (5 caselles)</li>
            <li>Peix espasa (4 caselles)</li>
            <li>Calamar (3 caselles)</li>
            <li>Peix (2 caselles)</li>
            <li>Estrella (1 casella)</li>
        </ul>
        <p>
            Els jugadors han de marcar cada tir al tauler. 
            Si encerten a la ubicació d'un peix, s'anota com un "tocat"; si fallen, és "aigua". 
            L'objectiu del joc és caçar tots els peixos de l'oponent abans que cacin els teus.
        </p>
        <p>
            Aquest joc ha estat una activitat popular d'entreteniment des de la seva invenció, proporcionant diversió i desenvolupant habilitats de pensament estratègic i lògic. 
        </p>
    </div>
    <div class="optionsGameIndex">
    <form method="post">
    <ul>
            <p>OPCIONS</p>
            <li>
                <input type="hidden" name="ammoLimited" value="0">
                <input type="checkbox" id="ammoLimited" name="ammoLimited" value="1" style="cursor:pointer;">
                <label for="ammoLimited">Munició limitada</label>
            </li>
            <li>
                <input type="hidden" name="armoredShips" value="0">
                <input type="checkbox" id="armoredShips" name="armoredShips" value="1" style="cursor:pointer;">
                <label for="armoredShips">Peixos Escorredis</label>
            </li>
            <li>
                <input type="hidden" name="specialAttack" value="0">
                <input type="checkbox" id="specialAttack" name="specialAttack" value="1" style="cursor:pointer;">
                <label for="specialAttack">Atacs especials</label>
            </li>
        </ul>
    </div>
    <div class="backgroundIndex">
        <div class="containerIndex">
            <div class="titleIndex">
                <h1>Shoreline Strike</h1>
            </div>
            <div class="panelIndex">
                    <p>Introduïu el vostre nom d'usuari:</p>
                    <input type="text" name="username" placeholder="usuari" required>
                    <button type="submit" name="classic">Classic Mode</button>
                    <button type="submit" name="tutorial">Tutorial</button>
                </form>
                <button id="hallOfFameButton" onclick="window.location.href='ranking.php'">Hall of Fame</button>
            </div>
        </div>
    </div>
</body>
</html>