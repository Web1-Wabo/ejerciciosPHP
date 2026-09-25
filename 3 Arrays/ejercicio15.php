<?php
    $generos = ["Ciencia ficcion", "Drama", "Romanticismo", "Accion", "Comedia"];
    $generos[] = "Policiaca";
    $generos[2] = "Tragicomedia";
    unset($generos[0]);

    foreach($generos as $genero){
        echo "$genero ";
    }
?>