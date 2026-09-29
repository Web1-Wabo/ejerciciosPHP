<?php
    require_once __DIR__ . "/../src/datos.php";
    require_once __DIR__ . "/../src/funciones.php";

    $librosFiltrados = $catalogo;

    echo ("La media de paginas " . calcularMediaPaginas($librosFiltrados) . " y el numero de libros es " . count($librosFiltrados) . "<br>");
    

    //Libro con mas paginas
    $libroLargo = obtenerLibroMasLargo($librosFiltrados);
    echo ("El libro con mas paginas es " . $libroLargo["titulo"]) . "<br>";

    $contadorDisp = 0;
    $contadorNDisp =0;
    foreach($librosFiltrados as $libros){
        if($libros["disponible"] === true){
            $contadorDisp++;
        }else{
            $contadorNDisp++;
        }
    }

    $fechaInforme = new DateTimeImmutable();


?>