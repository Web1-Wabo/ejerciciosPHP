<?php
    $q = "";
    $error = null;
    if (array_key_exists("q", $_GET)) {
        if (is_string($_GET["q"])) {
            $q = trim($_GET["q"]);
        } else {
            $error = "La búsqueda no es válida.";
        }
    }

    $e = static function (string $texto): string {
        return htmlspecialchars($texto, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
    };
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Buscar libros</title>
</head>
<body>
    <form method="get" action="libros.php">
        <label for="q">Buscar</label>
        <input type="text" name="q" id="q" value="<?= $e($q) ?>">
        <button type="submit">Buscar</button>
    </form>
    <?php if ($error !== null): ?>
        <p><?= $e($error) ?></p>
    <?php else: ?>
        <p>Búsqueda: <?= $e($q) ?></p>
    <?php endif; ?>
</body>
</html>