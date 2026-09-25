<?php
    $libro = [
        "id" => "1",
        "titulo" => "Dune",
        "autor" => "Paquito el chocolatero",
        "paginas" => "200",
        "disponible" => ""
    ];

    $libro["disponible"] = true;

    foreach($libro as $campo => $valor){
        echo "$campo: $valor<br>";
    }
?>