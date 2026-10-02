<?php
    /* Lo he tenido que poner asi ya que me daba fallo*/ 
    require_once __DIR__ . "/../src/datos.php";
    require_once __DIR__ . "/../src/funciones.php";

    $genero = $_GET["genero"] ?? "";
    $genero = strtolower(trim($genero));
    $disponible = $_GET["disponible"] ?? "";

    $timestamp = new DateTimeImmutable();
    $fechaRevision = new DateTimeImmutable();
    //llamada a los filtros
    $librosFiltrados = $catalogoLibros;

    $filtroGenero = filtrarPorGenero($librosFiltrados, $genero);
    $filtroDispo = filtrarDisponible($catalogoLibros);

    if ($genero !== "") {
        $librosFiltrados = filtrarPorGenero($librosFiltrados, $genero);
    }

    // 3. Si pide disponible, filtramos el resultado anterior (así se acumulan ambos filtros)
    if ($disponible === "1") {
        $librosFiltrados = filtrarDisponible($librosFiltrados);
    }



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php if (empty($librosFiltrados)): ?>
        <p>No hay libros filtrados</p>
    <?php else: ?>
        <ul>
            <?php foreach ($librosFiltrados as $libro): ?>
                <li>
                    Nombre: <?= htmlspecialchars($libro["titulo"], ENT_QUOTES, 'UTF-8') ?>,
                    Autor: <?= htmlspecialchars($libro["autor"], ENT_QUOTES, 'UTF-8') ?>,
                    Género: <?= htmlspecialchars($libro["genero"], ENT_QUOTES, 'UTF-8') ?>, 
                    Páginas: <?= (int)$libro["paginas"] ?>,
                    Disponible: <?= $libro["disponible"] ? 'Sí' : 'No' ?>,
                    Fecha Alta: <?= htmlspecialchars($libro["fechaAlta"], ENT_QUOTES, "UTF-8") ?>,<br>
                    
                    <?php
                        $fechaAlta = new DateTimeImmutable($libro["fechaAlta"]);

                        $diferencia = $fechaAlta->diff($timestamp);
                    ?>
                    Dias desde fecha alta <?=  $diferencia->days?>
                </li>
                <!---------------------------------------------------------->
                <!--                         ESTUDIAR                     -->
                <!---------------------------------------------------------->

                <!--Esto importante, para sintaxis en 1 linea, para no tener que poner mas etiquetas php-->
                <p>Fecha de revision de los libros=<?= $fechaRevision = $timestamp->modify("+30days")->format("Y-m-d"); ?></p>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
        

    <?php
        //media de paginas 
        echo ("La media de paginas " . calcularMediaPaginas($librosFiltrados) . " y el numero de libros es " . count($librosFiltrados) . "<br>");
        

        //Libro con mas paginas
        $libroLargo = obtenerLibroMasLargo($librosFiltrados);
        if($libroLargo !== null){
            echo ("El libro con mas paginas es " . $libroLargo["titulo"]);
        }else{
            echo "no hay libros";
        }

    
    ?>
    <!-- <p>Fecha de hoy es  <?//echo htmlspecialchars($timestamp); ?> </p>
            -->
</body>
</html>