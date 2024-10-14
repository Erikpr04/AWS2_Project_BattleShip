<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shoreline Strike</title>
    <style>
        body {
            background-color: lightblue;
            font-family: 'Geometry Soft Pro', sans-serif;
            overflow: hidden; /* Evitar scrolls durante el zoom */
            transition: transform 1s; /* Aseguramos que la transición del zoom se aplique */
        }

        .beach {
            background-color: yellow;
            height: 80vh; /* La playa ocupa la mayor parte de la ventana */
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            position: absolute; /* Permite que la playa se superponga al contenido */
            top: 0;
            left: 0;
            z-index: 1; /* Asegura que esté por encima de otros elementos */
        }

        .sea {
            background-color: blue;
            height: 50vh; /* Altura del mar */
            width: 100%;
            position: absolute; /* Permite que se superponga a la playa */
            bottom: 0; /* Fija el mar en la parte inferior */
            transform: scale(0.1); /* Comienza pequeño */
            transition: transform 1s; /* Animación de zoom */
            z-index: 0; /* Se coloca detrás de la playa */
        }

        .left-side {
            float: left;
            width: 70%;
        }

        .right-side {
            float: right;
            width: 30%;
        }

        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
            padding: 5px;
            text-align: center;
        }

        table {
            margin-left: auto;
            max-width: 600px; 
            max-height: 600px; 
            min-width: 500px; 
            min-height: 500px;
        }

        @font-face {
            font-family: 'Geometry Soft Pro';
            src: url('GeometrySoftPro-BoldN.woff2') format('woff2'),
            font-weight: bold;
            font-style: normal;
        }

        .counter-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            border: 2px solid #000;
            padding: 20px;
            width: 200px;
            margin: 0 auto;
        }

        .counter-container h3 {
            margin-bottom: 10px;
        }

        .timer, .points {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }

        .buttons {
            margin-top: 20px;
        }
    </style>
</head>
<body>

    <div class="beach">
        <h1>Shoreline Strike</h1>
    </div>

    <div class="sea">
        <div class="left-side">
            <table class="gameBoard">
                <?php
                for ($y = 0; $y < 10; $y++) {
                    echo "<tr>";
                    for ($x = 0; $x < 10; $x++) {
                        echo "<td>" . ($x + 1) . ", " . ($y + 1) . "</td>"; // Muestra las coordenadas (x, y)
                    }
                    echo "</tr>";
                }
                ?>
            </table>
        </div>

        <div class="right-side">
            <div class="counter-container">
                <h3>Time: <span class="timer">00:00</span></h3>
                <h3>Points: <span class="points">0</span></h3>
            </div>
        </div>
    </div>

    <script>
        // Función para hacer zoom en el div de la mar y mover la cámara
        window.onload = function() {
            const seaDiv = document.querySelector('.sea');
            document.body.style.transform = 'translateY(-20vh) scale(1.5)'; // Mueve la vista hacia arriba y aumenta el tamaño
            seaDiv.style.transform = 'scale(1)'; // Aumentar tamaño a 1 (normal)
        };

        let seconds = 0;
        let minutes = 0;

        // Función para actualizar el contador cada segundo
        setInterval(() => {
            seconds++;
            if (seconds === 60) {
                seconds = 0;
                minutes++;
            }
            document.querySelector('.timer').innerText = String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0');
        }, 1000);
        
        // Función para incrementar puntos (puedes adaptarla según tu lógica)
        const pointsElement = document.querySelector('.points');
        let points = 0;
        pointsElement.innerText = points;

        function incrementPoints() {
            points++;
            pointsElement.innerText = points;
        }
    </script>
</body>
</html>
