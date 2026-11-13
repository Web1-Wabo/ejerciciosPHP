<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/src/archivos.php';

$casos = [
    0 => false,
    1 => true,
    2097152 => true,
    2097153 => false,
];
$correctos = true;

foreach ($casos as $bytes => $esperado) {
    $obtenido = tamanoCartelValido($bytes);
    $estado = $obtenido === $esperado ? 'correcto' : 'incorrecto';
    echo $bytes . ' bytes: ' . $estado . PHP_EOL;
    if ($obtenido !== $esperado) {
        $correctos = false;
    }
}

if (!$correctos) {
    exit(1);
}
