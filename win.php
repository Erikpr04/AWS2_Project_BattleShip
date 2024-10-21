<?php
session_start();
if (!isset($_POST['points']) || $_POST['username']) {
    // Si no se ha ganado la partida, muestra el error 403 Forbidden
    header('HTTP/1.0 403 Forbidden');
    echo "403 Forbidden - No tienes permiso para acceder a esta página.";
    exit();
}

?>

<?php
if (!isset($_POST['points'])) {
    header('Location: index.php');
    exit();
}

$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send'])) {
    $username = trim($_POST['username']);
    $points = $_POST['points'];

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


    $timestamp = date('Y-m-d;H:i');

    $rankingData = "$username;$points;$timestamp;\n";

    $filePath = 'ranking.txt';
    file_put_contents($filePath, $rankingData, FILE_APPEND | LOCK_EX);
    echo "<script> window.location.href = 'ranking.php';</script>";

    exit();
}
?>

<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>You Won!</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body class="winbody">

    <div class="windiv">
        <main class="mainContent">
            <section class="backgroundIndex">
                <div class="containerIndex">
                    <h1 class="titleIndex">Shoreline Strike</h1>
                    <div class="panel">
                        <h1>Has guanyat!</h1>
                        <form action="" method="post">
                            <p>Escriu el teu nom:</p>
                            <input type="text" name="username" placeholder="Escriu el teu nom" required>
                            <input type="hidden" name="points" value="<?php echo htmlspecialchars($_POST['points']); ?>">
                            <button type="submit" name="send">Envia</button>
                        </form>
                        <button onclick="window.location.href='index.php'">Menú Principal</button>
                    </div>
                </div>
            </section>
        </main>
    </div>
</body>

</html>