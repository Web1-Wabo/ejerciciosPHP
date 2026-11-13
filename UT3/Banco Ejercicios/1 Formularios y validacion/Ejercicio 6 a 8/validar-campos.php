<?php
    declare(strict_types=1);

    function e(string $texto): string
    {
        return htmlspecialchars($texto, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
    }

    $valores = [
        "titulo" => "",
        "autor" => "",
        "paginas" => "",
        "genero" => "",
        "observaciones" => "",
    ];
    $errores = [];
    $destacado = false;
    $esPost = ($_SERVER["REQUEST_METHOD"] ?? "GET") === "POST";

    if ($esPost) {
        foreach (["titulo", "autor", "paginas", "genero", "observaciones"] as $campo) {
            if (!array_key_exists($campo, $_POST)) {
                continue;
            }
            if (!is_string($_POST[$campo])) {
                $errores[$campo] = "El campo debe ser texto.";
                continue;
            }
            $valores[$campo] = $campo === "observaciones"
                ? $_POST[$campo]
                : trim($_POST[$campo]);
        }

        foreach (["titulo", "autor"] as $campo) {
            if (!isset($errores[$campo]) && $valores[$campo] === "") {
                $errores[$campo] = "Este campo es obligatorio.";
            }
        }
        if (!isset($errores["titulo"])
            && mb_strlen($valores["titulo"], "UTF-8") > 120) {
            $errores["titulo"] = "El título no puede superar 120 caracteres.";
        }

        if (!isset($errores["paginas"])) {
            $paginas = filter_var($valores["paginas"], FILTER_VALIDATE_INT);
            if ($paginas === false || $paginas < 1 || $paginas > 3000) {
                $errores["paginas"] = "Indica un entero entre 1 y 3000.";
            }
        }

        if (!isset($errores["genero"])
            && !in_array($valores["genero"], ["ciencia", "fantasia", "terror"], true)) {
            $errores["genero"] = "Elige un género válido.";
        }

        if (array_key_exists("destacado", $_POST)) {
            if ($_POST["destacado"] === "1") {
                $destacado = true;
            } else {
                $errores["destacado"] = "El valor de la casilla no es válido.";
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Validar ficha de libro</title>
</head>
<body>
    <?php if ($esPost && $errores === []): ?>
        <p>La ficha es válida.</p>
    <?php endif; ?>
    <form method="post" action="validar-campos.php">
        <label for="titulo">Título</label>
        <input id="titulo" name="titulo" value="<?= e($valores["titulo"]) ?>">
        <?php if (isset($errores["titulo"])): ?><p><?= e($errores["titulo"]) ?></p><?php endif; ?>

        <label for="autor">Autor</label>
        <input id="autor" name="autor" value="<?= e($valores["autor"]) ?>">
        <?php if (isset($errores["autor"])): ?><p><?= e($errores["autor"]) ?></p><?php endif; ?>

        <label for="paginas">Páginas</label>
        <input id="paginas" name="paginas" value="<?= e($valores["paginas"]) ?>">
        <?php if (isset($errores["paginas"])): ?><p><?= e($errores["paginas"]) ?></p><?php endif; ?>

        <label for="genero">Género</label>
        <select id="genero" name="genero">
            <?php foreach (["ciencia", "fantasia", "terror"] as $genero): ?>
                <option value="<?= e($genero) ?>" <?= $valores["genero"] === $genero ? "selected" : "" ?>>
                    <?= e(ucfirst($genero)) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <?php if (isset($errores["genero"])): ?><p><?= e($errores["genero"]) ?></p><?php endif; ?>

        <label>
            <input type="checkbox" name="destacado" value="1" <?= $destacado ? "checked" : "" ?>>
            Destacado
        </label>
        <?php if (isset($errores["destacado"])): ?><p><?= e($errores["destacado"]) ?></p><?php endif; ?>

        <label for="observaciones">Observaciones</label>
        <textarea id="observaciones" name="observaciones"><?= e($valores["observaciones"]) ?></textarea>
        <?php if (isset($errores["observaciones"])): ?><p><?= e($errores["observaciones"]) ?></p><?php endif; ?>

        <button type="submit">Guardar</button>
    </form>
</body>
</html>
