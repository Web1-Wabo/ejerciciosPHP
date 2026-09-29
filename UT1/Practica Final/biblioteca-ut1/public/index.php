<?php
    require_once __DIR__ . "/../src/datos.php";
    require_once __DIR__ . "/../src/funciones.php";

    $genero = $_GET["genero"] ?? "";
    $genero = strtolower(trim($genero));
    $disponibilidad = $_GET["disponiblidad"] ?? "";
    $texto = $_GET["q"] ?? "";
    $texto = (string) strtolower(trim($texto));
    $orden = $_GET["orden"] ?? "paginas";


    $resultados = 0;
    $filtroGenero = filtrarPorGenero($catalogo, $genero);
    $filtroDisponibilidad = filtrarPorDisponibilidad($catalogo);
    $filtroTexto = buscarPorTexto($catalogo, $texto);


    if($filtroGenero !== null){
        foreach($filtroGenero as $libro){
            foreach($libro as $clave => $valor){
                echo "$clave: $valor <br>";
            }
        }
    }

    if($filtroDisponibilidad !== null){
        foreach($filtroDisponibilidad as $libro){
            foreach($libro as $clave => $valor){
                echo "$clave: $valor <br>";
            }
        }
    }

    if($filtroTexto !== null){
        foreach($filtroTexto as $libro){
            foreach($libro as $clave => $valor){
                echo "$clave: $valor <br>";
            }
        }

    }
    
    

    echo "Total de resultados es: $resultados";
?>