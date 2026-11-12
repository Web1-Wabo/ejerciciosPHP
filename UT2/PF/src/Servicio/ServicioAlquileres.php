<?php

namespace Clase\Alquileres\Servicio;

use Clase\Alquileres\Modelo\EstadoAlquiler;
use Clase\Alquileres\Modelo\Alquiler;

class ServicioAlquileres
{
    public function normalizar(string $texto): string
    {
        return mb_strtolower(trim($texto), "UTF-8");
    }

    // Promoción, no hace falta crear otro array, no lo pide
    public function __construct(private array $alquileres) {}

    public function todos(): array
    {
        return $this->alquileres;
    }

    public function consultar(string $texto = "", ?EstadoAlquiler $estado = null): array
    {
        $filtrados = array_filter(
            $this->alquileres,
            fn(Alquiler $a): bool => (str_contains($this->normalizar($a->cliente()) ?: null, $this->normalizar($texto))
                // Falta no filtrar si estado es null -0,25
                && ($estado === null || $a->estado() === $estado))
        );

        // Falta toda la ordenación -0,5
        usort(
            $filtrados,
            fn(Alquiler $a, Alquiler $b): int => ($a->importe() <=> $b->importe()) === 0
                ? $a->id() <=> $b->id()
                : $a->importe() <=> $b->importe()
        );

        return $filtrados;
    }

    public function resumir(array $alquileres): array
    {
        return array_reduce(
            $alquileres,
            function (array $carry, Alquiler $a): array {
                $carry["cantidad"] += 1;
                $carry["importe"] += $a->importe();
                return $carry;
            },
            // Falta iniciar el acumulador para que funcionen los += -0,25
            ["cantidad" => 0, "importe" => 0]
        );
    }
}
