<?php
    $catalogo = [
        [
            "titulo" => "Pinocho",
            "paginas" => 300,
            "disponible" => true
        ],
        [
            "titulo" => "Moby dick",
            "paginas" => 600,
            "disponible" => true
        ],
        [
            "titulo" => "Cenicienta",
            "paginas" => 502,
            "disponible" => false
        ]
    ];

    $contador =0;
    foreach($catalogo as $libro){
        if($libro["disponible"] == true && $libro["paginas"] < 500){    
            $contador++;
            foreach($libro as $clave => $valor){
                echo "$clave: $valor<br>";
                
            }
        }       
    }
    echo "Total de libros es $contador";
?>