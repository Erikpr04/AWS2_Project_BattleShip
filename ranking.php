<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>RANKING</title>
</head>

<body>
    <div class="backgroundRanking">
        <div class="containerRanking">
            <?php
            // Función para cargar el ranking desde el archivo
            function loadRanking($file)
            {
                $ranking = [];
                if (file_exists($file)) {
                    $lines = file($file, FILE_IGNORE_NEW_LINES);
                    foreach ($lines as $line) {
                        list($name, $score, $date) = explode(';', $line);
                        $ranking[] = [
                            'name' => $name,
                            'score' => (int)$score,
                            'date' => $date
                        ];
                    }
                }
                return $ranking;
            }

            // Función para ordenar el ranking por puntuación
            function sortRanking($a, $b)
            {
                return $b['score'] - $a['score'];
            }

            // Parámetros para la paginación
            $perPage = 25;
            $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $rankingFile = 'ranking.txt';
            
            // Load and sort the ranking
            $ranking = loadRanking($rankingFile);
            usort($ranking, 'sortRanking');
            
            // Calculate pagination
            $totalResults = count($ranking);
            $totalPages = ceil($totalResults / $perPage);
            $start = ($currentPage - 1) * $perPage;
            $rankingPage = array_slice($ranking, $start, $perPage);
            
            // Display the ranking
            echo "<table border='1'>";
            echo "<tr><th>Name</th><th>Score</th><th>Date</th></tr>";
            foreach ($rankingPage as $record) {
                echo "<tr><td>{$record['name']}</td><td>{$record['score']}</td><td>{$record['date']}</td></tr>";
            }
            echo "</table>";
            ?>
        </div>
    </div>
</body>
</html>