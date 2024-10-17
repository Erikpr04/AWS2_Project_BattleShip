<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>RANKING</title>
</head>

<body class="bodyRanking">
    <div class="backgroundRanking">
        <div class="titleRanking">
            <h1>HALL OF FAME</h1>
        </div>
        <div class="containerRanking">
        <?php
            // Función para cargar el ranking desde el archiv ranking.txt
            function loadRanking($file) {
                $ranking = [];
                if (file_exists($file)) {
                    $lines = file($file, FILE_IGNORE_NEW_LINES);
                    foreach ($lines as $line) {
                        list($name, $score, $date, $time) = explode(';', $line);
                        $ranking[] = [
                            'name' => $name,
                            'score' => (int)$score,
                            'date' => $date,
                            'time' => $time
                        ];
                    }
                }
                return $ranking;
            }

            // Función para poder ordenar el ranking por puntuación
            function sortRanking($a, $b) {
                return $b['score'] - $a['score'];
            }

            // Función para poder agregar un nuevo usuario al ranking
            function addNewUser($file, $name, $score) {
                $date = date('Y-m-d');
                $time = date('H:i');
                $entry = "{$name};{$score};{$date};{$time};\n";
                file_put_contents($file, $entry, FILE_APPEND);
            }

            // Esto de aqui es para la paginación
            $perPage = 25;
            $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $rankingFile = 'ranking.txt';

            // Si se envía un nuevo usuario a través de un formulario lo agregamos
            if (isset($_POST['name']) && isset($_POST['score'])) {
                $name = $_POST['name'];
                $score = (int)$_POST['score'];
                addNewUser($rankingFile, $name, $score);
            }

            // Cargar y ordenar el ranking
            $ranking = loadRanking($rankingFile);
            usort($ranking, 'sortRanking');

            // Calcular la paginación
            $totalResults = count($ranking);
            $totalPages = ceil($totalResults / $perPage);
            $start = ($currentPage - 1) * $perPage;
            $rankingPage = array_slice($ranking, $start, $perPage);

            // Mostrar el ranking
            echo "<table>";
            echo "<tr><th>Posició</th><th>Nom</th><th>Puntuació</th><th>Data</th><th>Hora</th></tr>";
            foreach ($rankingPage as $index => $record) {
                $position = $start + $index + 1;
                echo "<tr><td>{$position}</td><td>{$record['name']}</td><td>{$record['score']}</td><td>{$record['date']}</td><td>{$record['time']}</td></tr>";
            }
            echo "</table>";

            // Mostrar el paginador
            echo "<div  class='paginationRanking'>";
            if ($currentPage > 1) {
                echo "<button><a href='?page=".($currentPage - 1)."'>Anterior</a></button> ";
            }
            if ($totalPages > 1){
                for ($i = 1; $i <= $totalPages; $i++) {
                    if ($i == $currentPage) {
                        echo "<strong>$i</strong> ";
                    } else {
                        echo "<a href='?page=$i'>$i</a> ";
                    }

                }
            }
            if ($currentPage < $totalPages) {
                echo "<button><a href='?page=".($currentPage + 1)."'>Següent</a></button>";
            }
            echo "</div>";
        ?>
        </div>
    </div>
</body>
</html>

