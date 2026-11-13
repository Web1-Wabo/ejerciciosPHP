<?php
declare(strict_types=1);

use Random\RandomException;

const MIME_CARTELES = [
    'image/jpeg' => 'jpg',
    'image/png' => 'png',
    'image/webp' => 'webp',
];
const MAX_BYTES_CARTEL = 2097152;

function directorioCarteles(): string
{
    return dirname(__DIR__) . '/storage/carteles';
}

function rutaCartel(string $nombre): string
{
    return directorioCarteles() . '/' . $nombre;
}

function e(string $texto): string
{
    return htmlspecialchars($texto, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function nombreCartelValido(mixed $nombre): bool
{
    return is_string($nombre)
        && preg_match('/\A[a-f0-9]{32}\.(jpg|png|webp)\z/', $nombre) === 1;
}

function mimeCartel(string $nombre): ?string
{
    if (!nombreCartelValido($nombre)) {
        return null;
    }

    $ruta = rutaCartel($nombre);
    if (!is_file($ruta) || is_link($ruta) || !is_readable($ruta)) {
        return null;
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($ruta);
    if ($mime === false || !isset(MIME_CARTELES[$mime])
        || pathinfo($nombre, PATHINFO_EXTENSION) !== MIME_CARTELES[$mime]) {
        return null;
    }

    return $mime;
}

function listarCarteles(): array|false
{
    $rutas = glob(directorioCarteles() . '/*');
    if ($rutas === false) {
        return false;
    }

    $carteles = [];
    foreach ($rutas as $ruta) {
        $nombre = basename($ruta);
        if (mimeCartel($nombre) !== null) {
            $carteles[] = $nombre;
        }
    }

    sort($carteles, SORT_STRING);
    return $carteles;
}

function tamanoCartelValido(int $bytes): bool
{
    return $bytes >= 1 && $bytes <= MAX_BYTES_CARTEL;
}

function mensajeErrorSubida(int $codigo): string
{
    return match ($codigo) {
        UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE =>
            'El cartel supera el límite de recepción de PHP.',
        UPLOAD_ERR_PARTIAL => 'El cartel se recibió solo parcialmente.',
        UPLOAD_ERR_NO_TMP_DIR, UPLOAD_ERR_CANT_WRITE =>
            'PHP no pudo guardar el archivo temporal.',
        UPLOAD_ERR_EXTENSION => 'Una extensión de PHP detuvo la subida.',
        default => 'Se produjo un error al recibir el cartel.',
    };
}

function validarSubidaCartel(
    array $archivos,
    bool $obligatorio,
    ?string $nombreAnterior = null
): array {
    if (!array_key_exists('imagen', $archivos)) {
        return $obligatorio
            ? ['archivo' => null, 'error' => 'Selecciona un cartel.']
            : ['archivo' => null, 'error' => null];
    }

    $archivo = $archivos['imagen'];
    if (!is_array($archivo)
        || !isset($archivo['error'], $archivo['size'], $archivo['tmp_name'])
        || !is_int($archivo['error'])
        || !is_int($archivo['size'])
        || !is_string($archivo['tmp_name'])) {
        return ['archivo' => null, 'error' => 'La entrada de la imagen no es válida.'];
    }

    if ($archivo['error'] === UPLOAD_ERR_NO_FILE) {
        return $obligatorio
            ? ['archivo' => null, 'error' => 'Selecciona un cartel.']
            : ['archivo' => null, 'error' => null];
    }

    if ($archivo['error'] !== UPLOAD_ERR_OK) {
        return ['archivo' => null, 'error' => mensajeErrorSubida($archivo['error'])];
    }

    if (!tamanoCartelValido($archivo['size'])) {
        return ['archivo' => null, 'error' => 'El cartel debe ocupar entre 1 byte y 2 MiB.'];
    }

    $temporal = $archivo['tmp_name'];
    if ($temporal === '' || !is_file($temporal) || !is_readable($temporal)) {
        return ['archivo' => null, 'error' => 'No se pudo leer el archivo recibido.'];
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($temporal);
    if ($mime === false || !isset(MIME_CARTELES[$mime])) {
        return ['archivo' => null, 'error' => 'El formato del cartel no está permitido.'];
    }

    $directorio = directorioCarteles();
    if (!is_dir($directorio) || !is_writable($directorio)) {
        return ['archivo' => null, 'error' => 'El almacenamiento de carteles no está disponible.'];
    }

    $nombre = null;
    $ruta = null;
    try {
        for ($intento = 0; $intento < 5; $intento++) {
            $candidato = bin2hex(random_bytes(16)) . '.' . MIME_CARTELES[$mime];
            $rutaCandidata = $directorio . '/' . $candidato;
            if ($candidato !== $nombreAnterior
                && !file_exists($rutaCandidata)
                && !is_link($rutaCandidata)) {
                $nombre = $candidato;
                $ruta = $rutaCandidata;
                break;
            }
        }
    } catch (RandomException) {
        return ['archivo' => null, 'error' => 'No se pudo preparar el nombre del cartel.'];
    }

    if ($nombre === null || $ruta === null) {
        return ['archivo' => null, 'error' => 'No se pudo preparar un nombre libre para el cartel.'];
    }

    return [
        'archivo' => [
            'temporal' => $temporal,
            'nombre' => $nombre,
            'ruta' => $ruta,
            'mime' => $mime,
        ],
        'error' => null,
    ];
}

function retirarCartel(string $nombre): bool
{
    if (!nombreCartelValido($nombre)) {
        return false;
    }

    $ruta = rutaCartel($nombre);
    if (!is_file($ruta) || is_link($ruta)) {
        return false;
    }

    return unlink($ruta);
}

function postExcesivo(): bool
{
    $contenido = $_SERVER['CONTENT_LENGTH'] ?? null;
    if (!is_string($contenido) && !is_int($contenido)) {
        return false;
    }

    $bytes = filter_var((string) $contenido, FILTER_VALIDATE_INT);
    return $bytes !== false && $bytes > 8 * 1024 * 1024;
}
