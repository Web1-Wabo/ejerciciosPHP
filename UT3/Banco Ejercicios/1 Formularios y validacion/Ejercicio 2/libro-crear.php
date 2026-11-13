<?php
    $titulo = $_POST["titulo"] ?? "";
    $autor = $_POST["autor"] ?? "";
    $paginas = $_POST["paginas"] ?? "";

    $e = static function (string $texto): string {
        return htmlspecialchars($texto, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
    };
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Datos recibidos</title>
</head>
<body>
    <p>Título: <?= $e(is_string($titulo) ? $titulo : "Dato no válido") ?></p>
    <p>Autor: <?= $e(is_string($autor) ? $autor : "Dato no válido") ?></p>
    <p>Páginas:
        <?= $e(is_string($paginas) ? $paginas : "Dato no válido") ?>
        (tipo: <?= $e(get_debug_type($paginas)) ?>)
    </p>
</body>
</html>