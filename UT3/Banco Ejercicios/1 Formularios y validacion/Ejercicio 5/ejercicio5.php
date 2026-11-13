<?php
    $titulo = trim(' <Dune> & "edición" ');

    function e(string $texto): string
    {
        return htmlspecialchars(
            $texto,
            ENT_QUOTES | ENT_SUBSTITUTE,
            "UTF-8"
        );
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Escapar texto</title>
</head>
<body>
    <p><?= e($titulo) ?></p>
    <input name="titulo" value="<?= e($titulo) ?>">
</body>
</html>