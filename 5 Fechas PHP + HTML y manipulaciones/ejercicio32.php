<?php
    $titulos = ["Caperucita", "Moby Dick", "El principito", "The Mentalist"];  
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
    </head>
    <body>
        <ul>
            <?php foreach($titulos as $titulo): ?>
                <li>
                    <?= htmlspecialchars($titulo) ?>
                </li>
            <?php endforeach?>
        </ul>
    </body>
</html>