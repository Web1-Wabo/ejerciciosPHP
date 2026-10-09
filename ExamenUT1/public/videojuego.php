<?php
    require_once __DIR__ . "/../src/datos.php";
    require_once __DIR__ . "/../src/funciones.php";

    $id = $_GET["id"] ?? 0;
    //$id = (int)$id;

    $resultado = $videojuegos;
    if ($id !== 0) {
        // Completa el tratamiento del caso en el que el videojuego no existe.
        $resultado = buscarPorId($resultado, $id);
    }

    if(empty($resultado)){
        ?>
        <!doctype html>
        <html lang="es">
        <head>
            <meta charset="utf-8">
            <title>Videojuego no encontrado</title>
        </head>
        <body>
            <h1>Video juego no encontrado</h1>
        </body>
        </html>
        <?php
        exit;
    }

    // Prepara las fechas y los valores que necesita la ficha.
    $fechaLanzamiento = new DateTime();// De dónde saco la fecha??

    $resultado["fechaLanzamiento"] = new DateTime($resultado["fechaLanzamiento"]);
    $fechaLanzamiento = $resultado["fechaLanzamiento"];

    

    $hoy = new DateTimeImmutable();
    $diasTranscurridos = $hoy->diff($fechaLanzamiento); //0? Habrá que calcular algo, no?
    $finNovedad = null;
    $estado =  $diasTranscurridos > 30 ? "Novedad" : "No es novedad";

    // COMPLETAR los cálculos anteriores utilizando los datos del videojuego.
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Ficha del videojuego</title>
</head>
<body>
    <h1><?= htmlspecialchars($resultado['titulo'] ?? '') ?></h1>

    <dl>
        <dt>Estudio</dt>
        <dd><?= htmlspecialchars($resultado['estudio'] ?? '') ?></dd>

        <dt>Género</dt>
        <dd><?= htmlspecialchars($resultado['genero'] ?? '') ?></dd>

        <dt>Plataforma</dt>
        <dd><?= htmlspecialchars($resultado['plataforma'] ?? '') ?></dd>

        <dt>Precio</dt>
        <dd>
            <?php if ($resultado !== null): ?>
                <?= number_format($resultado['precio'], 2, ',', '.') ?> €
            <?php endif; ?>
        </dd>

        <dt>Puntuación</dt>
        <dd><?= $resultado['puntuacion'] ?? '' ?></dd>

        <dt>Fecha de lanzamiento</dt>
        <dd><?=  $fechaLanzamiento->format("Y-m-d")?></dd>

        <dt>Días desde el lanzamiento</dt>
        <dd><?= $diasTranscurridos->days ?> </dd>

        <dt>Fin del periodo de novedad</dt>
        <dd><?= $fechaLanzamiento->modify("+30days")->format("Y-m-d"); ?></dd>

        <dt>Estado</dt>
        <dd><?= $estado?></dd>
    </dl>

    <p><a href="../index.php">Volver al catálogo</a></p>
</body>
</html>
