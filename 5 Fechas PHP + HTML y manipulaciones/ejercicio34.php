<?php
    $libros =[
        [
            "titulo" => "caperucita",
            "genero" => "cuento"
        ],
        [
            "titulo" => "Moby Dick",
            "genero" => "aventura"
        ],
        [
            "titulo" => "Los 3 cerditos",
            "genero" => "cuento"
        ],
        [
            "titulo" => "El tren de medianoche",
            "genero" => "drama"
        ],
        [
            "titulo" => "sherk",
            "genero" => "comedia"
        ],
        [
            "titulo" => "sherlock holmes",
            "genero" => "aventura"
        ]
    ];

    // filtrar por genero
    function filtrarPorGenero(array $catalogo, string $genero):array{
        if($genero === "todos"){
            return $catalogo;
        }    

        $resultado = [];

        foreach($catalogo as $libro){
            if($libro["genero"] === $genero){
                $resultado[] = $libro;
            }
        }
        
        return $resultado;
    }

    function ordenarPorTitulo(){
        
    }

    function contarCoincidencias(array $catalago, string $genero):int{
        $contador = 0;
        foreach($catalago as $libro){
            if($libro["genero"] === $genero){
                $contador = count($libro);
                
            }
        }

        return $contador;
    }

    $generos = $_GET["genero"] ?? "todos";
    $generos = strtolower(trim($generos));

    print_r(filtrarPorGenero($libros, $generos));
    
?>