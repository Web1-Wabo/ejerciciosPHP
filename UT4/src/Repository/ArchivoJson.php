<?php
declare(strict_types=1);
namespace App\Repository;

// Mecánica de archivo proporcionada. El repositorio decide el esquema de Usuario.
final class ArchivoJson
{
    public function __construct(private string $ruta) {}

    public function leer(): array
    {
        $json = file_get_contents($this->ruta);
        if ($json === false) {
            throw new \RuntimeException('No se pudo leer el archivo');
        }
        $filas = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
        if (!is_array($filas) || !array_is_list($filas)
            || !str_starts_with(ltrim($json), '[')) {
            throw new \RuntimeException('Se esperaba una lista JSON');
        }
        return $filas;
    }

    public function escribir(array $filas): void
    {
        $json = json_encode($filas, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);
        $bytes = file_put_contents($this->ruta, $json . "\n", LOCK_EX);
        if ($bytes !== strlen($json) + 1) {
            throw new \RuntimeException('No se pudo guardar el archivo completo');
        }
    }
}
