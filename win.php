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
<body class="winbody">

<div class="windiv">
<main class="mainContent">
    <section class="backgroundIndex">
        <div class="containerIndex">
            <h1 class="titleIndex">Shoreline Strike</h1>
            <div class="panel">
                <h1>You Won!</h1>
                <form action="" method="post">
                    <p>Enter your username:</p>
                    <input type="text" name="username" placeholder="Enter your username" required>
                    <input type="hidden" name="points" value="<?php echo htmlspecialchars($_POST['points']); ?>"> 
                    <button type="submit" name="send">Send</button> 
                </form>
                <button onclick="window.location.href='index.php'">Main Menu</button>
            </div>
        </div>
    </section>
    </main>
</div>
</body>
</html>
