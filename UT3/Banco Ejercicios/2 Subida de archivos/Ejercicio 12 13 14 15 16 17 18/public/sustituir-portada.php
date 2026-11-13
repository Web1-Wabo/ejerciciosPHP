<?php
    declare(strict_types=1);

    use Random\RandomException;

    session_start();

    function e(string $texto): string
    {
        return htmlspecialchars($texto, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
    }

    function retirarPortada(string $nombre, string $directorio): void
    {
        if (preg_match('/\A[a-f0-9]{32}\.(jpg|png|webp)\z/', $nombre) !== 1) {
            error_log("Se intentó retirar una portada con un nombre no generado.");
            return;
        }
        $ruta = $directorio . "/" . $nombre;
        if (is_file($ruta) && !unlink($ruta)) {
            error_log("No se pudo retirar una portada del almacenamiento controlado.");
        }
    }

    $directorio = __DIR__ . "/../storage/portadas";
    $errores = [];
    $portadaAnterior = $_SESSION["portadaActual"] ?? null;
    if (!is_string($portadaAnterior)
        || preg_match('/\A[a-f0-9]{32}\.(jpg|png|webp)\z/', $portadaAnterior) !== 1) {
        $portadaAnterior = null;
    }
    $portadaActual = $portadaAnterior;
    $esPost = ($_SERVER["REQUEST_METHOD"] ?? "GET") === "POST";

    if ($esPost) {
        $archivo = $_FILES["portada"] ?? null;
        $nombreNuevo = null;
        $destinoNuevo = null;

        if ($archivo !== null) {
            $estructuraValida = is_array($archivo)
                && isset($archivo["error"], $archivo["size"], $archivo["tmp_name"])
                && is_int($archivo["error"])
                && is_int($archivo["size"])
                && is_string($archivo["tmp_name"]);

            if (!$estructuraValida) {
                $errores["portada"] = "Los datos recibidos no son válidos.";
            } elseif ($archivo["error"] === UPLOAD_ERR_NO_FILE) {
                $archivo = null;
            } elseif ($archivo["error"] !== UPLOAD_ERR_OK) {
                $errores["portada"] = "No se pudo recibir la nueva portada.";
            } elseif ($archivo["size"] < 1 || $archivo["size"] > 2 * 1024 * 1024) {
                $errores["portada"] = "La portada debe ocupar entre 1 byte y 2 MiB.";
            } else {
                $finfo = new finfo(FILEINFO_MIME_TYPE);
                $mime = $finfo->file($archivo["tmp_name"]);
                $permitidos = [
                    "image/jpeg" => "jpg",
                    "image/png" => "png",
                    "image/webp" => "webp",
                ];

                if ($mime === false || !isset($permitidos[$mime])) {
                    $errores["portada"] = "El contenido no es una imagen permitida.";
                } elseif (!is_dir($directorio) || !is_writable($directorio)) {
                    $errores["portada"] = "El almacenamiento de portadas no está disponible.";
                } else {
                    try {
                        $nombreNuevo = bin2hex(random_bytes(16)) . "." . $permitidos[$mime];
                        $destinoNuevo = $directorio . "/" . $nombreNuevo;
                        if (file_exists($destinoNuevo)) {
                            $errores["portada"] = "No se pudo preparar el destino.";
                        }
                    } catch (RandomException) {
                        $errores["portada"] = "No se pudo preparar el nombre de la portada.";
                    }
                }
            }
        }

        if ($errores === [] && $archivo !== null
            && $nombreNuevo !== null && $destinoNuevo !== null) {
            if (!move_uploaded_file($archivo["tmp_name"], $destinoNuevo)) {
                $errores["portada"] = "No se pudo guardar la nueva portada.";
            } else {
                $portadaActual = $nombreNuevo;
                try {
                    if (($_POST["simularFallo"] ?? "") === "1") {
                        throw new RuntimeException("Fallo de prueba al confirmar.");
                    }
                    $_SESSION["portadaActual"] = $portadaActual;
                } catch (RuntimeException) {
                    $portadaActual = $portadaAnterior;
                    if (!unlink($destinoNuevo)) {
                        error_log("No se pudo retirar la portada nueva tras cancelar la sustitución.");
                    }
                    $errores["general"] = "No se pudo confirmar el cambio; se conserva la portada anterior.";
                }

                if ($errores === [] && $portadaAnterior !== null) {
                    // En el proyecto real, esta lista se consulta en el repositorio de fichas.
                    $portadasDeOtrasFichas = [];
                    $otraFichaUsaImagen = in_array(
                        $portadaAnterior,
                        $portadasDeOtrasFichas,
                        true
                    );
                    if (!$otraFichaUsaImagen) {
                        retirarPortada($portadaAnterior, $directorio);
                    }
                }
            }
        } elseif ($errores === []) {
            $portadaActual = $portadaAnterior;
        }
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Sustituir portada</title>
</head>
<body>
    <?php if ($portadaActual !== null): ?>
        <p>Portada actual: <?= e($portadaActual) ?></p>
        <img src="portada.php?nombre=<?= e(rawurlencode($portadaActual)) ?>" alt="Portada actual">
    <?php else: ?>
        <p>No hay una portada anterior.</p>
    <?php endif; ?>

    <?php foreach ($errores as $error): ?><p><?= e($error) ?></p><?php endforeach; ?>
    <form method="post" enctype="multipart/form-data" action="sustituir-portada.php">
        <label for="portada">Nueva portada</label>
        <input id="portada" type="file" name="portada" accept="image/jpeg,image/png,image/webp">
        <label>
            <input type="checkbox" name="simularFallo" value="1">
            Simular un fallo al confirmar el cambio
        </label>
        <button type="submit">Sustituir</button>
    </form>
</body>
</html>
