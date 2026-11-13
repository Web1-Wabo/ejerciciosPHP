<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/src/archivos.php';

$nombre = $_GET['nombre'] ?? null;
if (!nombreCartelValido($nombre)) {
    http_response_code(404);
    exit;
}

$mime = mimeCartel($nombre);
if ($mime === null) {
    http_response_code(404);
    exit;
}

header('Content-Type: ' . $mime);
header('X-Content-Type-Options: nosniff');
readfile(rutaCartel($nombre));
exit;
