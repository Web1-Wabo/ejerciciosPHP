<?php
    /* Lo he tenido que poner asi ya que me daba fallo*/ 
    require_once __DIR__ . "/../src/datos.php";
    require_once __DIR__ . "/../src/funciones.php";

    $genero = $_GET["genero"] ?? null;
    $disponible = $_GET["disponible"] ?? null;

    //llamada a los filtros
    $librosFiltrados = $catalogoLibros;

    if(!empty($genero)){
        $librosFiltrados = filtrarPorGenero($librosFiltrados, $genero);
    }    

    if($disponible !== null && $disponible !== ""){
        $librosFiltrados = filtrarDisponible($librosFiltrados);
    }

        //Mostrar catalogo completo
    foreach($catalogoLibros as $libros){
        foreach($libros as $clave => $valor){
            echo "$clave: $valor <br>";
        }
        echo "<br>";
    }

    //media de paginas
 
    echo ("La media de paginas " . calcularMediaPaginas($librosFiltrados) . " y el numero de libros es " . count($librosFiltrados) . "<br>");
    

    //Libro con mas paginas
    $libroLargo = obtenerLibroMasLargo($librosFiltrados);
    echo ("El libro con mas paginas es " . $libroLargo["titulo"]);


    foreach($catalogoLibros as &$libro){
        $libro["fechaAlta"] = new DateTimeImmutable($libro["fechaAlta"]);
    }

    
?>