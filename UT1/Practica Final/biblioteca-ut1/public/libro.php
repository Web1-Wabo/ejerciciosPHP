<?php
    require_once __DIR__ . "/../src/datos.php";
    require_once __DIR__ . "/../src/funciones.php";

    $id = $_GET["id"] ?? null;
    $id = (int)$id;


    $librosPorID = filtrarPorId($catalogo, $id);

    if($librosPorID !== null){
        foreach($librosPorID as $libros){
            echo $libros;
        }
    }

    if($libros["id"] === null){
        echo "no existe libro con este ID";
    }
    // echo $buscador;
?>