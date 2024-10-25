<?php
session_start();

$username = isset($_SESSION['username']) ? $_SESSION['username'] : '';
$points = isset($_POST['points']) ? $_POST['points'] : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send'])) {
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

    $username = $_POST['username'];

    $timestamp = date('Y-m-d;H:i');
    $rankingData = "\n$username;$points;$timestamp;\n";
    $filePath = 'ranking.txt';
    file_put_contents($filePath, $rankingData, FILE_APPEND | LOCK_EX);

    header('Location: ranking.php');
    exit();
}

if (
    !isset($_SERVER['HTTP_REFERER']) ||
    (strpos($_SERVER['HTTP_REFERER'], 'index.php') === false && strpos($_SERVER['HTTP_REFERER'], 'lose.php') !== false)
) {
    // Si no es referida desde la página del juego, retorna un 403
    header('HTTP/1.1 403 Forbidden');
    ?>
    <!DOCTYPE html>
    <html lang="ca">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="style.css">
        <title>403 Forbidden</title>
    </head>
    <body class="bodyForbidden">
        <div class="finalForbiScreen">
            <h2>403 Forbidden: Has de accedir desde Game</h2>
        </div>
    </body>
    </html>

    <?php
    session_destroy();
    exit();
}
?>

<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Has perdut...</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body class="winbody">

    <div class="windiv">
        <main class="mainContent">
            <section class="backgroundIndex">
                <div class="containerIndex">
                    <h1 class="titleIndex">Shoreline Strike</h1>
                    <div class="panel">
                        <h1>Has perdut...</h1>
                        <form method="post">
                            <p>Escriu el teu nom:</p>
                            <input type="text" name="username" placeholder="Escriu el teu nom" value="<?php echo htmlspecialchars($username); ?>" required>
                            <input type="hidden" name="points" value="<?php echo htmlspecialchars($points); ?>"> 
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
