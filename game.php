<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
</head>

<body>
    <div class="headerGame"></div>
    <div class="backgroundGame">
        <div class="containerGame">
            <table class="tableGame">
                <?php
                $column = 11;
                $row = 11;
                for ($i = 0; $i < $row; $i++) {
                    echo "<tr>";
                    for ($j = 0; $j < $column; $j++) {
                        if ($i == 0 && $j == 0) {
                            echo "<td></td>";
                        } elseif ($i == 0) {
                            echo "<td>" . chr(65 + $j - 1) . "</td>";
                        } elseif ($j == 0) {
                            echo "<td>" . ($i + $j) . "</td>";
                        } else {
                            echo "<td></td>";
                        }
                    }
                    echo "</tr>";
                }
                ?>
            </table>
        </div>
    </div>


</body>

</html>