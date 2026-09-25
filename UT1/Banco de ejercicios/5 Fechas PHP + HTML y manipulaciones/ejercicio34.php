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

    function ordenarPorTitulo(array $libros):array{
        asort($libros);
        return $libros;
    }

    $generos = $_GET["genero"] ?? "todos";
    $generos = strtolower(trim($generos));

    $filtroGen = filtrarPorGenero($libros, $generos);
    $ordenado = ordenarPorTitulo($filtroGen);
    $totalResultados = count($ordenado);

    //print_r($ordenado);
    $hoy = new DateTimeImmutable();
    $plazo = $hoy->modify("+30days")->format("d/m/Y");
    
?>