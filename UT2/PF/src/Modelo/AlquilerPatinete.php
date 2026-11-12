<?php

namespace Clase\Alquileres\Modelo;


class AlquilerPatinete extends Alquiler
{
    public function modalidad(): string
    {
        return "Patinete";
    }

    protected function tarifaDiaria(): int
    {
        return 1000;
    }
}
