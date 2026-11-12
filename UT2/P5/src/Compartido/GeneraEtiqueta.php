<?php

declare(strict_types=1);

// TODO M3. Declara el trait GeneraEtiqueta y su método etiqueta.
trait GeneraEtiqueta
{
    function etiqueta(string $prefijo = ""): string
    {
        $nombre = preg_replace('/\s+/u', ' ', trim($this->nombre())) ?? '';
        return $prefijo . $this->id() . ' · ' . $nombre;
    }
}
