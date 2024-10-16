<?php
// Verificar si las variables han sido enviadas por POST
if (!isset($_POST['points'])) {
    header('Location: index.php');
    exit();
}

$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send'])) {
    $username = trim($_POST['username']);
    $points = $_POST['points']; 

    if (strpos($username, ';') !== false) {
        echo "<script>alert('Semicolon \" ; \" is not allowed!'); window.history.back();</script>";
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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>You Won!</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

<div class="beach">
        <div class="backgroundIndex">
            <div class="containerIndex">
                <div class="titleIndex">
                    <h1>Shoreline Strike</h1>
                </div>
                <div class="panel">
                    <h1>You Won!</h1>
                    <form action="" method="post">
                        <p>Enter your username:</p>
                        <input type="text" name="username" placeholder="Enter your username" required>
                        <input type="hidden" name="points" value="<?php echo htmlspecialchars($_POST['points']); ?>"> <!-- Usar puntos de la variable POST -->
                        <button type="submit" name="send">Send</button> 
                    </form>
                    <button onclick="window.location.href='index.php'">Main Menu</button>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
