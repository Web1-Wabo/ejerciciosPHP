<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/src/archivos.php';

$esSustitucion = array_key_exists('anterior', $_GET);
$anterior = null;
if ($esSustitucion) {
    $parametro = $_GET['anterior'];
    if (!nombreCartelValido($parametro) || mimeCartel($parametro) === null) {
        http_response_code(404);
        exit;
    }
    $anterior = $parametro;
}

$errores = [];
$mensaje = null;
$nombreGuardado = null;
$eraSustitucion = $anterior !== null;
$esPost = ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';

if ($esPost) {
    if (postExcesivo()) {
        $errores['imagen'] = 'El envío supera los 8 MiB permitidos por PHP.';
    } else {
        $resultado = validarSubidaCartel($_FILES, !$esSustitucion, $anterior);
        if ($resultado['error'] !== null) {
            $errores['imagen'] = $resultado['error'];
        } elseif ($resultado['archivo'] === null) {
            $mensaje = 'No se ha cambiado el cartel.';
        } else {
            $nuevo = $resultado['archivo'];
            if (file_exists($nuevo['ruta']) || is_link($nuevo['ruta'])) {
                $errores['imagen'] = 'El destino ya está ocupado. Vuelve a enviar el cartel.';
            } elseif (!move_uploaded_file($nuevo['temporal'], $nuevo['ruta'])) {
                $errores['imagen'] = 'No se pudo guardar el cartel.';
            } elseif ($anterior !== null && !retirarCartel($anterior)) {
                if (!retirarCartel($nuevo['nombre'])) {
                    error_log('No se pudo retirar el cartel nuevo después de fallar la sustitución.');
                    $errores['imagen'] = 'No se pudo retirar el cartel anterior ni completar la limpieza.';
                } else {
                    $errores['imagen'] = 'No se pudo retirar el cartel anterior; se ha conservado.';
                }
            } else {
                $nombreGuardado = $nuevo['nombre'];
                if ($eraSustitucion) {
                    $anterior = $nombreGuardado;
                }
                $mensaje = !$eraSustitucion
                    ? 'Cartel subido correctamente.'
                    : 'Cartel sustituido correctamente.';
            }
        }
    }
}

$accion = 'subir.php';
if ($anterior !== null) {
    $accion .= '?anterior=' . rawurlencode($anterior);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $esSustitucion ? 'Sustituir cartel' : 'Subir cartel' ?></title>
</head>
<body>
    <h1><?= $esSustitucion ? 'Sustituir cartel' : 'Subir cartel' ?></h1>

    <?php if ($anterior !== null && $nombreGuardado === null): ?>
        <p>Cartel actual: <?= e($anterior) ?></p>
        <img src="imagen.php?nombre=<?= e(rawurlencode($anterior)) ?>"
             alt="Cartel actual">
    <?php endif; ?>

    <?php if ($mensaje !== null): ?>
        <p><?= e($mensaje) ?></p>
    <?php endif; ?>

    <?php if ($nombreGuardado !== null): ?>
        <p>Nombre generado: <?= e($nombreGuardado) ?></p>
        <img src="imagen.php?nombre=<?= e(rawurlencode($nombreGuardado)) ?>"
             alt="Cartel guardado">
    <?php endif; ?>

    <?php if ($errores !== []): ?>
        <ul role="alert">
            <?php foreach ($errores as $error): ?>
                <li><?= e($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="post" action="<?= e($accion) ?>" enctype="multipart/form-data">
        <label for="imagen">Imagen del cartel</label>
        <input type="file" id="imagen" name="imagen"
               accept="image/jpeg,image/png,image/webp">
        <button type="submit">Guardar</button>
    </form>

    <p><a href="index.php">Volver a la galería</a></p>
</body>
</html>
