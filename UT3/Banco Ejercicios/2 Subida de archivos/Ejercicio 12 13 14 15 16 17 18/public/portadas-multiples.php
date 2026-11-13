<?php
    declare(strict_types=1);

    use Random\RandomException;

    function e(string $texto): string
    {
        return htmlspecialchars($texto, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
    }

    $errores = [];
    $nombresGuardados = [];
    $esPost = ($_SERVER["REQUEST_METHOD"] ?? "GET") === "POST";

    if ($esPost) {
        $archivos = $_FILES["portadas"] ?? null;
        $campos = ["name", "type", "tmp_name", "error", "size"];
        $estructuraValida = is_array($archivos);

        if ($estructuraValida) {
            foreach ($campos as $campo) {
                if (!isset($archivos[$campo]) || !is_array($archivos[$campo])) {
                    $estructuraValida = false;
                }
            }
        }

        if ($estructuraValida) {
            $indices = array_keys($archivos["name"]);
            foreach ($campos as $campo) {
                if (array_keys($archivos[$campo]) !== $indices) {
                    $estructuraValida = false;
                }
            }
        }

        if (!$estructuraValida) {
            $errores["general"] = "La estructura de los archivos recibidos no es válida.";
        } elseif (count($indices) === 0) {
            $errores["general"] = "Selecciona al menos una imagen.";
        } elseif (count($indices) > 3) {
            $errores["general"] = "Solo se pueden enviar hasta tres imágenes.";
        } else {
            $directorio = __DIR__ . "/../storage/portadas";
            $permitidos = [
                "image/jpeg" => "jpg",
                "image/png" => "png",
                "image/webp" => "webp",
            ];
            $preparados = [];

            if (!is_dir($directorio) || !is_writable($directorio)) {
                $errores["general"] = "El almacenamiento de portadas no está disponible.";
            } else {
                $finfo = new finfo(FILEINFO_MIME_TYPE);
                foreach ($indices as $indice) {
                    $nombreCliente = $archivos["name"][$indice];
                    $tipoCliente = $archivos["type"][$indice];
                    $temporal = $archivos["tmp_name"][$indice];
                    $codigo = $archivos["error"][$indice];
                    $tamano = $archivos["size"][$indice];

                    if (!is_string($nombreCliente) || !is_string($tipoCliente)
                        || !is_string($temporal) || !is_int($codigo) || !is_int($tamano)) {
                        $errores[$indice] = "Los datos recibidos no tienen los tipos esperados.";
                        continue;
                    }
                    if ($codigo !== UPLOAD_ERR_OK) {
                        $errores[$indice] = $codigo === UPLOAD_ERR_NO_FILE
                            ? "No se seleccionó un archivo."
                            : "No se pudo recibir este archivo.";
                        continue;
                    }
                    if ($tamano < 1 || $tamano > 2 * 1024 * 1024) {
                        $errores[$indice] = "El tamaño debe estar entre 1 byte y 2 MiB.";
                        continue;
                    }

                    $mime = $finfo->file($temporal);
                    if ($mime === false || !isset($permitidos[$mime])) {
                        $errores[$indice] = "El contenido no es una imagen permitida.";
                        continue;
                    }

                    try {
                        $nombre = bin2hex(random_bytes(16)) . "." . $permitidos[$mime];
                    } catch (RandomException) {
                        $errores[$indice] = "No se pudo preparar el nombre del archivo.";
                        continue;
                    }

                    $destino = $directorio . "/" . $nombre;
                    if (file_exists($destino)) {
                        $errores[$indice] = "No se pudo preparar el destino del archivo.";
                        continue;
                    }
                    $preparados[$indice] = [
                        "temporal" => $temporal,
                        "destino" => $destino,
                        "nombre" => $nombre,
                    ];
                }
            }

            if ($errores === []) {
                $movidos = [];
                foreach ($preparados as $indice => $archivo) {
                    if (!move_uploaded_file($archivo["temporal"], $archivo["destino"])) {
                        $errores[$indice] = "No se pudo guardar este archivo.";
                        break;
                    }
                    $movidos[] = $archivo;
                }

                if ($errores !== []) {
                    foreach ($movidos as $archivo) {
                        if (!unlink($archivo["destino"])) {
                            error_log("No se pudo retirar una portada nueva tras fallar la subida múltiple.");
                        }
                    }
                } else {
                    foreach ($preparados as $archivo) {
                        $nombresGuardados[] = $archivo["nombre"];
                    }
                }
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Subir varias portadas</title>
</head>
<body>
    <?php if ($nombresGuardados !== []): ?>
        <p>Se han guardado todas las portadas.</p>
        <?php foreach ($nombresGuardados as $nombre): ?>
            <img src="portada.php?nombre=<?= e(rawurlencode($nombre)) ?>" alt="Portada">
        <?php endforeach; ?>
    <?php endif; ?>

    <?php foreach ($errores as $error): ?><p><?= e($error) ?></p><?php endforeach; ?>
    <form method="post" enctype="multipart/form-data" action="portadas-multiples.php">
        <label for="portadas">Portadas (hasta tres)</label>
        <input id="portadas" type="file" name="portadas[]" accept="image/jpeg,image/png,image/webp" multiple>
        <button type="submit">Subir</button>
    </form>
</body>
</html>
