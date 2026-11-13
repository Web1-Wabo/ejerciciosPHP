<?php
    declare(strict_types=1);

    $nombre = $_GET["nombre"] ?? "";
    if (!is_string($nombre)
        || preg_match('/\A[a-f0-9]{32}\.(jpg|png|webp)\z/', $nombre) !== 1) {
        http_response_code(404);
        exit;
    }

    $directorio = __DIR__ . "/../storage/portadas";
    $ruta = $directorio . "/" . $nombre;
    if (!is_file($ruta) || !is_readable($ruta)) {
        http_response_code(404);
        exit;
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($ruta);
    $permitidos = ["image/jpeg", "image/png", "image/webp"];
    if ($mime === false || !in_array($mime, $permitidos, true)) {
        http_response_code(404);
        exit;
    }

    header("Content-Type: " . $mime);
    header("X-Content-Type-Options: nosniff");
    readfile($ruta);
