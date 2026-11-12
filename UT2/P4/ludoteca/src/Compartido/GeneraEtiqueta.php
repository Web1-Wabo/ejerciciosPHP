<?php

declare(strict_types=1);

// TODO M3. Implementa el trait GeneraEtiqueta usando id() y nombre().
trait GeneraEtiqueta
{
    public function etiqueta(): string
    {
        return $this->id() . " . " . $this->nombre();
    }
}
