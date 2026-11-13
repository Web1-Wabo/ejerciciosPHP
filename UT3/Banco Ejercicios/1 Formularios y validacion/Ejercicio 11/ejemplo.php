<?php
    declare(strict_types=1);

    function e(string $texto): string
    {
        return htmlspecialchars($texto, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
    }

    $valores = ["titulo" => "", "paginas" => ""];
    $errores = [];
    $datos = null;
    $esPost = ($_SERVER["REQUEST_METHOD"] ?? "GET") === "POST";

    if ($esPost) {
        foreach (["titulo", "paginas"] as $campo) {
            if (!array_key_exists($campo, $_POST)) {
                continue;
            }
            if (!is_string($_POST[$campo])) {
                $errores[$campo] = "El campo debe ser texto.";
                continue;
            }
            $valores[$campo] = trim($_POST[$campo]);
        }

        if (!isset($errores["titulo"])) {
            if ($valores["titulo"] === "") {
                $errores["titulo"] = "Escribe el título.";
            } elseif (mb_strlen($valores["titulo"], "UTF-8") > 120) {
                $errores["titulo"] = "El título no puede superar 120 caracteres.";
            }
        }

        if (!isset($errores["paginas"])) {
            $paginas = filter_var($valores["paginas"], FILTER_VALIDATE_INT);
            if ($paginas === false || $paginas < 1 || $paginas > 3000) {
                $errores["paginas"] = "Indica un entero entre 1 y 3000.";
            }
        }

        if ($errores === []) {
            $datos = [
                "titulo" => $valores["titulo"],
                "paginas" => (int) $valores["paginas"],
            ];
        }
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Alta de libro</title>
</head>
<body>
    <?php if ($datos !== null): ?>
        <p>Libro guardado: <?= e($datos["titulo"]) ?> (<?= $datos["paginas"] ?> páginas).</p>
    <?php endif; ?>
    <form method="post" action="ejemplo.php">
        <label for="titulo">Título</label>
        <input id="titulo" name="titulo" value="<?= e($valores["titulo"]) ?>">
        <?php if (isset($errores["titulo"])): ?><p><?= e($errores["titulo"]) ?></p><?php endif; ?>

        <label for="paginas">Páginas</label>
        <input id="paginas" name="paginas" type="number" value="<?= e($valores["paginas"]) ?>">
        <?php if (isset($errores["paginas"])): ?><p><?= e($errores["paginas"]) ?></p><?php endif; ?>

        <button type="submit">Guardar</button>
    </form>
</body>
</html>
