<?php
    $libros = [
        [
           "titulo" => "caperucita",
           "autor" => "desconocido"
        ],
        [
            "titulo" => "Los 3 cerditos",
            "autor" => "Uno de los cerdos"
        ],
        [
            "titulo" => "El principito",
            "autor" => "Pepito"
        ],
        [
            "titulo" => "El tren de media noche",
            "Autor" => "Un tren"
        ]
    ];

    foreach($libros as $libro){
        foreach($libro as $clave => $valor){
            echo "$clave: $valor <br>";
        }
        echo "<br>";
    }
?>