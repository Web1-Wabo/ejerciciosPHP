<?php

    $catalogo = [
        [
            "titulo"=> "libro1",
            "disponible"=>true
        ],
        [
            "titulo"=> "libro2",
            "disponible"=>false
        ]
    ];

    $disponibles = array_filter(
        $catalogo,
        fn(array $libro): bool => $libro["disponible"] === true,
    );
    

?>