<?php
    declare(strict_types=1);

    function e(string $texto): string
    {
        return htmlspecialchars($texto, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
    }

    $valor = "";
    $fecha = null;
    $error = null;
    $esPost = ($_SERVER["REQUEST_METHOD"] ?? "GET") === "POST";

    if ($esPost) {
        $entrada = $_POST["fechaAlta"] ?? "";
        if (!is_string($entrada)) {
            $error = "La fecha debe ser texto.";
        } else {
            $valor = trim($entrada);
            if ($valor !== "") {
                try {
                    $fecha = DateTimeImmutable::createFromFormat("!Y-m-d", $valor);
                } catch (ValueError) {
                    $fecha = false;
                }
                if ($fecha === false || $fecha->format("Y-m-d") !== $valor) {
                    $error = "La fecha no es válida.";
                    $fecha = null;
                }
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Fecha de alta</title>
</head>
<body>
    <form method="post" action="fecha.php">
        <label for="fechaAlta">Fecha de alta</label>
        <input id="fechaAlta" name="fechaAlta" type="text" value="<?= e($valor) ?>">
        <button type="submit">Validar</button>
    </form>
    <?php if ($error !== null): ?>
        <p><?= e($error) ?></p>
    <?php elseif ($esPost): ?>
        <p><?= $fecha === null ? "Sin fecha" : e($fecha->format("Y-m-d")) ?></p>
    <?php endif; ?>
</body>
</html>
