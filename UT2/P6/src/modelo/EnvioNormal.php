<?php

namespace App\modelo;

use Override;

class EnvioNormal extends Envio
{
    #[Override]
    public function modalidad(): string
    {
        return "normal";
    }

    #[Override]
    protected function cuotaFija(): int
    {
        return 350;
    }

    #[Override]
    protected function precioBloque(): int
    {
        return 120;
    }
}
