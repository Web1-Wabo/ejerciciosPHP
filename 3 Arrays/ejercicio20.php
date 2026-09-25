<?php
    $numeros = [232,424,14132,546,254,1,23,446];

    sort($numeros);

    echo "Minimo $numeros[0] <br>";
    echo "Maximo $numeros[7] <br>";

    $suma = 0;
    foreach($numeros as $numero){
        $suma += $numero;
    }

    $media = $suma/count($numeros);
    echo "Media = $media"
?>