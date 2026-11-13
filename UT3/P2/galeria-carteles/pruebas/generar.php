<?php
declare(strict_types=1);

$base = file_get_contents(__DIR__ . '/base.png');
if ($base === false || strlen($base) >= 2 * 1024 * 1024) {
    fwrite(STDERR, "Prepara un PNG menor de 2 MiB en pruebas/base.png.\n");
    exit(1);
}

foreach ([2097152, 2097153] as $bytes) {
    $contenido = $base . str_repeat("\0", $bytes - strlen($base));
    $ruta = __DIR__ . '/limite-' . $bytes . '.png';
    if (file_put_contents($ruta, $contenido) !== $bytes) {
        fwrite(STDERR, "No se pudo crear " . basename($ruta) . ".\n");
        exit(1);
    }
}

echo "Se generaron las dos imágenes de prueba.\n";
