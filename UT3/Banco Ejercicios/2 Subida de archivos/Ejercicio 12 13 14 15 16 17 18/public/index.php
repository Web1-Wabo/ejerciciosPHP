<?php
    declare(strict_types=1);

    use Random\RandomException;

    session_start();

    function e(string $texto): string
    {
        return htmlspecialchars($texto, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
    }

    $valores = ["titulo" => "", "paginas" => ""];
    $erroresCampos = [];
    $errorPortada = null;
    $erroresGenerales = [];
    $datos = null;
    $portadaActual = null;
    if (isset($_SESSION["portadaActual"])
        && is_string($_SESSION["portadaActual"])
        && preg_match('/\A[a-f0-9]{32}\.(jpg|png|webp)\z/', $_SESSION["portadaActual"]) === 1) {
        $rutaActual = __DIR__ . "/../storage/portadas/" . $_SESSION["portadaActual"];
        if (is_file($rutaActual)) {
            $portadaActual = $_SESSION["portadaActual"];
        }
    }
    $nombreNuevo = null;
    $destinoNuevo = null;
    $archivo = null;
    $formularioActual = basename($_SERVER["SCRIPT_NAME"] ?? "index.php");
    $esPost = ($_SERVER["REQUEST_METHOD"] ?? "GET") === "POST";

    if ($esPost) {
        foreach (["titulo", "paginas"] as $campo) {
            if (!array_key_exists($campo, $_POST)) {
                continue;
            }
            if (!is_string($_POST[$campo])) {
                $erroresCampos[$campo] = "El campo debe ser texto.";
                continue;
            }
            $valores[$campo] = trim($_POST[$campo]);
        }

        if (!isset($erroresCampos["titulo"])) {
            if ($valores["titulo"] === "") {
                $erroresCampos["titulo"] = "Escribe el título.";
            } elseif (mb_strlen($valores["titulo"], "UTF-8") > 120) {
                $erroresCampos["titulo"] = "El título no puede superar 120 caracteres.";
            }
        }

        if (!isset($erroresCampos["paginas"])) {
            $paginas = filter_var($valores["paginas"], FILTER_VALIDATE_INT);
            if ($paginas === false || $paginas < 1 || $paginas > 3000) {
                $erroresCampos["paginas"] = "Indica un entero entre 1 y 3000.";
            }
        }

        $archivo = $_FILES["portada"] ?? null;
        if ($archivo !== null) {
            $estructuraValida = is_array($archivo)
                && isset($archivo["error"], $archivo["size"], $archivo["tmp_name"])
                && is_int($archivo["error"])
                && is_int($archivo["size"])
                && is_string($archivo["tmp_name"]);

            if (!$estructuraValida) {
                $errorPortada = "Los datos recibidos para la portada no son válidos.";
            } elseif ($archivo["error"] === UPLOAD_ERR_NO_FILE) {
                $archivo = null;
            } elseif ($archivo["error"] === UPLOAD_ERR_INI_SIZE) {
                $errorPortada = "El archivo supera el límite configurado en PHP.";
            } elseif ($archivo["error"] === UPLOAD_ERR_PARTIAL) {
                $errorPortada = "La portada se recibió solo parcialmente.";
            } elseif ($archivo["error"] !== UPLOAD_ERR_OK) {
                $errorPortada = "No se pudo recibir la portada.";
            } elseif ($archivo["size"] < 1 || $archivo["size"] > 2 * 1024 * 1024) {
                $errorPortada = "La portada debe ocupar entre 1 byte y 2 MiB.";
            } else {
                $finfo = new finfo(FILEINFO_MIME_TYPE);
                $mime = $finfo->file($archivo["tmp_name"]);
                $permitidos = [
                    "image/jpeg" => "jpg",
                    "image/png" => "png",
                    "image/webp" => "webp",
                ];

                if ($mime === false || !isset($permitidos[$mime])) {
                    $errorPortada = "El contenido no es una imagen JPEG, PNG o WebP.";
                } else {
                    try {
                        $nombreNuevo = bin2hex(random_bytes(16)) . "." . $permitidos[$mime];
                        $directorio = __DIR__ . "/../storage/portadas";
                        $destinoNuevo = $directorio . "/" . $nombreNuevo;

                        if (!is_dir($directorio) || !is_writable($directorio)) {
                            $errorPortada = "El almacenamiento de portadas no está disponible.";
                        } elseif (file_exists($destinoNuevo)) {
                            $errorPortada = "No se pudo preparar un nombre para la portada.";
                        }
                    } catch (RandomException) {
                        $errorPortada = "No se pudo preparar el nombre de la portada.";
                    }
                }
            }
        }

        if ($erroresCampos === [] && $errorPortada === null && $erroresGenerales === []) {
            if ($archivo !== null && $nombreNuevo !== null && $destinoNuevo !== null) {
                if (move_uploaded_file($archivo["tmp_name"], $destinoNuevo)) {
                    $portadaActual = $nombreNuevo;
                    $_SESSION["portadaActual"] = $portadaActual;
                } else {
                    $errorPortada = "No se pudo guardar la portada.";
                    $erroresGenerales[] = "No se pudo completar el alta del libro.";
                }
            }

            if ($erroresCampos === [] && $errorPortada === null && $erroresGenerales === []) {
                $datos = [
                    "titulo" => $valores["titulo"],
                    "paginas" => (int) $valores["paginas"],
                ];
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alta de libro</title>
</head>
<body>
    <?php if ($datos !== null): ?>
        <p>Libro guardado: <?= e($datos["titulo"]) ?> (<?= $datos["paginas"] ?> páginas).</p>
        <?php if ($portadaActual !== null): ?>
            <img src="portada.php?nombre=<?= e(rawurlencode($portadaActual)) ?>" alt="Portada del libro">
        <?php endif; ?>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data" action="<?= e($formularioActual) ?>">
        <label for="titulo">Título</label>
        <input id="titulo" name="titulo" value="<?= e($valores["titulo"]) ?>">
        <?php if (isset($erroresCampos["titulo"])): ?><p><?= e($erroresCampos["titulo"]) ?></p><?php endif; ?>

        <label for="paginas">Páginas</label>
        <input id="paginas" name="paginas" type="number" value="<?= e($valores["paginas"]) ?>">
        <?php if (isset($erroresCampos["paginas"])): ?><p><?= e($erroresCampos["paginas"]) ?></p><?php endif; ?>

        <label for="portada">Portada (opcional, máximo 2 MiB)</label>
        <input id="portada" type="file" name="portada" accept="image/jpeg,image/png,image/webp">
        <?php if ($errorPortada !== null): ?><p><?= e($errorPortada) ?></p><?php endif; ?>
        <?php foreach ($erroresGenerales as $error): ?><p><?= e($error) ?></p><?php endforeach; ?>

        <button type="submit">Guardar</button>
    </form>
</body>
</html>
