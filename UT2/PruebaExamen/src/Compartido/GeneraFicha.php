<?php

declare(strict_types=1);

namespace Alquiler\Equipo\Compartido;

trait GeneraFicha
{
    public function ficha(string $prefijo = ''): string
    {
        // TODO T1: construye la ficha con id(), nombre() y disponibles().
        return $prefijo . "  {$this->id()} · {$this->nombre()} ({$this->disponibles()} disponibles)";
    }
}
