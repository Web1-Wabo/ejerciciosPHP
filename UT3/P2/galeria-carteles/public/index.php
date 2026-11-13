<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/src/archivos.php';

$carteles = listarCarteles();
if ($carteles === false) {
    error_log('No se pudo consultar el directorio de carteles.');
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galería de carteles</title>
</head>
<body>
    <h1>Galería de carteles</h1>
    <p><a href="subir.php">Subir cartel</a></p>

    <?php if ($carteles === false): ?>
        <p>No se pudo cargar la galería.</p>
    <?php elseif ($carteles === []): ?>
        <p>Todavía no hay carteles.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($carteles as $nombre): ?>
                <li>
                    <img src="imagen.php?nombre=<?= e(rawurlencode($nombre)) ?>"
                         alt="Cartel de actividad">
                    <p><?= e($nombre) ?></p>
                    <a href="subir.php?anterior=<?= e(rawurlencode($nombre)) ?>">Sustituir</a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</body>
</html>
