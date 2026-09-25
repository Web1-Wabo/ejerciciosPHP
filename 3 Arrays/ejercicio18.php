<?php
    $catalogo = [
        [
            "titulo" => "Caperucita",
            "autor" => "Ursula K. Le Guin"
        ],
        [
            "titulo" => "Oliver y benji",
            "autor" => "Benji"
        ]
    ];

    foreach($catalogo as $cata){
        foreach($cata as $clave => $valor){
            if($cata["autor"] == "Ursula K. Le Guin"){
                echo "$clave: $valor<br>";
            }
        }
    }
?>