<?php
    $titulo = "Dune";
    $paginas = 412;
    const MAX_PRESTAMOS = 3;
    $disponible = true;
    echo "Libro: $titulo";
    $puede = $paginas > 400 && $disponible == true;

    //$Titulo = "Dune"
    //$paginas = "412"; -> lo he cambiado a valor numerico
    //const max_prestamos = 3; -> las constantes normalmente se definen en mayusculas
    //$disponible = TRUE -> el booleano lo he puesto en minuscula
    //Echo "Libro: " + $Titulo; -> he corregido la sintaxsis para una mejor practica
    //$puede = $paginas > 400 && $disponible = true;

?>

