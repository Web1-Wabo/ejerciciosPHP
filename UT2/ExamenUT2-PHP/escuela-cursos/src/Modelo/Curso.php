<?php

declare(strict_types=1);

namespace Escuela\Curso\Modelo;

use Escuela\Curso\Compartido\GeneraEtiqueta;
use Escuela\Curso\Excepcion\CursoException;
use InvalidArgumentException;

abstract class Curso
{
    use GeneraEtiqueta;

    public function __construct(
        public readonly int $id,
        private string $nombre,
        private int $capacidad,
        private int $ocupadas,
        private EstadoCurso $estado,
        protected readonly int $precioBaseCentimos,
    ) {
        if (
            $id <= 0 || trim($nombre) === '' || $capacidad <= 0
            || $ocupadas < 0 || $ocupadas > $capacidad || $precioBaseCentimos <= 0
        ) {
            throw new \InvalidArgumentException('Datos del curso inválidos');
        }

        $this->nombre = trim($nombre);
    }

    public function id(): int
    {
        return $this->id;
    }

    public function nombre(): string
    {
        return $this->nombre;
    }

    public function nombreNormalizado(): string
    {
        return mb_strtolower(trim($this->nombre), 'UTF-8');
    }

    public function capacidad(): int
    {
        return $this->capacidad;
    }

    public function ocupadas(): int
    {
        return $this->ocupadas;
    }

    public function estado(): EstadoCurso
    {
        return $this->estado;
    }

    public function plazasLibres(): int
    {
        return $this->capacidad - $this->ocupadas;
    }

    abstract public function tipo(): string;

    abstract public function precioPlaza(): int;

    public function reservar(int $cantidad): void
    {
        // TODO M3: valida la reserva antes de modificar las plazas ocupadas.
        if ($cantidad <= 0) {
            throw new InvalidArgumentException("La cantidad no puede ser menor o igual a 0");
        }

        if ($this->estado === EstadoCurso::Cerrado) {
            throw new CursoException("El curso no puede estar cerrado");
        }

        if ($cantidad > $this->plazasLibres()) {
            throw new CursoException("La cantidad a reservar no puede ser mayor que las plazas libres");
        }

        $this->ocupadas += $cantidad;
    }

    public function cancelar(int $cantidad): void
    {
        // TODO M4: valida la cancelación antes de modificar las plazas ocupadas.

        if ($cantidad <= 0) {
            throw new InvalidArgumentException("La cantidad no puede ser menor o igual a 0");
        }

        if ($cantidad > $this->ocupadas) {
            throw new CursoException("La cantidad a cancelar no puede ser mayor que las plazas ocupadas");
        }

        $this->ocupadas -= $cantidad;
    }
}
