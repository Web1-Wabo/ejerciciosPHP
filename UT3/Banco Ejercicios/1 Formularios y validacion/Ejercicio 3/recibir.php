<?php
    $titulo = "";
    $errores = [];
    $esPost = ($_SERVER["REQUEST_METHOD"] ?? "GET") === "POST";

    if ($esPost) {
        $entrada = $_POST["titulo"] ?? "";
        if (!is_string($entrada)) {
            $errores["titulo"] = "El título debe ser texto.";
        } else {
            $titulo = trim($entrada);
        }
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resultado</title>
</head>
<body>
    <?php if (isset($errores["titulo"])): ?>
        <p><?= htmlspecialchars($errores["titulo"], ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8") ?></p>
    <?php endif; ?>
    <form method="post" action="recibir.php">
        <label for="titulo">Título</label>
        <input id="titulo" name="titulo" value="<?= htmlspecialchars($titulo, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8") ?>">
        <button type="submit">Enviar</button>
    </form>
</body>
</html>