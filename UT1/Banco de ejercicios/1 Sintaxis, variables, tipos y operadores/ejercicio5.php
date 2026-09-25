<?php
    $genero = $_GET["genero"] ?? "todos";
    $mensaje = ($genero !== "todos") ? "Filtro Activo" : "sin filtro";
?>