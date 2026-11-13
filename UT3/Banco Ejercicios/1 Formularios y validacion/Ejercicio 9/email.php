<?php
    declare(strict_types=1);

    function e(string $texto): string
    {
        return htmlspecialchars($texto, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
    }

    $esPost = ($_SERVER["REQUEST_METHOD"] ?? "GET") === "POST";
    $valorControl = "";
    $emailOriginal = null;
    $resultadoOriginal = "No se ha enviado el correo.";
    $resultadoNormalizado = "No se ha enviado el correo.";

    if ($esPost) {
        $emailOriginal = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);
        if (is_string($emailOriginal)) {
            $resultadoOriginal = "Correo válido: " . $emailOriginal;
        } elseif ($emailOriginal === false) {
            $resultadoOriginal = "El correo recibido no es válido.";
        }

        $entrada = $_POST["email"] ?? "";
        if (is_string($entrada)) {
            $valorControl = $entrada;
            $normalizado = trim($entrada);
            $validado = filter_var($normalizado, FILTER_VALIDATE_EMAIL);
            $resultadoNormalizado = $validado === false
                ? "El correo normalizado no es válido."
                : "Correo normalizado válido: " . $validado;
        } else {
            $resultadoNormalizado = "El campo debe ser texto.";
        }
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Validar correo</title>
</head>
<body>
    <form method="post" action="email.php">
        <label for="email">Correo</label>
        <input id="email" name="email" type="text" value="<?= e($valorControl) ?>">
        <button type="submit">Validar</button>
    </form>
    <?php if ($esPost): ?>
        <p><?= e($resultadoOriginal) ?></p>
        <p><?= e($resultadoNormalizado) ?></p>
    <?php endif; ?>
</body>
</html>
