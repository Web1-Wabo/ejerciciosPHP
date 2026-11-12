<?php

declare(strict_types=1);

// TODO M6. Implementa la clase abstracta Actividad, sus reglas y las operaciones final.
abstract class Actividad implements Reservable
{
    use GeneraEtiqueta;

    public function __construct(
        private readonly int $id,
        private readonly string $nombre,
        private readonly int $capacidad,
        private int $ocupadas,
        private readonly EstadoActividad $estado,
        private readonly ?Responsable $responsable
    ) {
        if ($id <= 0) {
            throw new InvalidArgumentException("El id tiene que ser mayor que 0");
        }

        if (trim($nombre) === "") {
            throw new InvalidArgumentException("EL nombre no peude estar vaico");
        }

        if ($capacidad <= 0) {
            throw new InvalidArgumentException("la capacidad es menor a 0");
        }
        if ($ocupadas <= 0 || $ocupadas >= $capacidad) {
            throw new InvalidArgumentException("No puede haber mas o menos ocupado que de capaicidad");
        }
        // Cuándo se van a dar ambas? Igualmente no hace falta
        // if ($estado !== EstadoActividad::Abierta && $estado !== EstadoActividad::Cerrada) {
        //     throw new InvalidArgumentException("Esta caso no esta contemplado");
        // }
    }

    public function id(): int
    {
        return $this->id;
    }

    public function nombre(): string
    {
        return $this->nombre;
    }

    public function capacidad(): int
    {
        return $this->capacidad;
    }

    public function ocupadas(): int
    {
        return $this->ocupadas;
    }

    public function estado(): EstadoActividad
    {
        return $this->estado;
    }

    public function responsable(): ?Responsable
    {
        return $this->responsable;
    }

    #[Override]
    public function plazasLibres(): int
    {
        return $this->capacidad - $this->ocupadas;
    }

    public abstract function tipo(): string;
    public abstract function importe(int $cantidad): int;

    #[Override]
    public final function reservar(int $cantidad): void
    {
        if ($cantidad <= 0) {
            throw new InvalidArgumentException("No puede ser 0 o menor");
        }

        if ($this->estado !== EstadoActividad::Abierta) {
            throw new ReservaException("Activa cerrada. Aforo al maximo");
        }

        if ($cantidad > $this->plazasLibres()) {
            throw new PlazasInsuficientesException("Activa cerrada. Aforo al maximo");
        }

        $this->ocupadas += $cantidad;
    }

    #[Override]
    public function cancelar(int $cantidad): void
    {
        if ($cantidad <= 0) {
            throw new InvalidArgumentException("No puede ser 0 o menor");
        }

        if ($cantidad > $this->ocupadas) {
            throw new ReservaException("No se puede cancelar mas de lo ocupado");
        }

        $this->ocupadas -= $cantidad;
    }
}
