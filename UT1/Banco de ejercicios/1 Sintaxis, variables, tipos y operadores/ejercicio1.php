<?php
    $titulo = "titulo ";
    $autor = "autor ";
    $numPag = 200;
    $precio = 20;
    $disponibilidad = true;

    $libro = "Titulo: " . $titulo . " Autor: " . $autor . " Numero de Paginas: ". $numPag . " Precio: ". $precio . " Disponibilidad: " .($disponibilidad ? "Disponible" : "No Disponible");
    echo $libro;
?>
