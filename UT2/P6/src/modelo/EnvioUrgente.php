<?php

namespace App\modelo;

use Override;

class EnvioUrgente extends Envio
{
    #[Override]
    public function modalidad(): string
    {
        return "urgente";
    }

    #[Override]
    protected function cuotaFija(): int
    {
        return 600;
    }

    #[Override]
    protected function precioBloque(): int
    {
        return 200;
    }
}
