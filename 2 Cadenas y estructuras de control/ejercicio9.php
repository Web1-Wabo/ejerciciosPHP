<?php
    $titulo = "   El nombre del viento   ";

    $cadenaEsp = trim($titulo);
    $longitud = strlen($titulo);
    $contieneViento = str_contains($cadenaEsp, "viento");
    $tituloModi = str_replace("viento", "fuego", $cadenaEsp);
    $palabras = explode(" ", $cadenaEsp)

?>