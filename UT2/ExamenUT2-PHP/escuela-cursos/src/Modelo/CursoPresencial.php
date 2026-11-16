<?php

declare(strict_types=1);

namespace Escuela\Curso\Modelo;

use InvalidArgumentException;

final class CursoPresencial extends Curso
{
    public function __construct(
        int $id,
        string $nombre,
        int $capacidad,
        int $ocupadas,
        EstadoCurso $estado,
        int $precioBaseCentimos,
        private readonly int $materialesCentimos,
    ) {
        // TODO M0: valida los materiales e inicializa la parte heredada.
        if ($materialesCentimos < 0) {
            throw new InvalidArgumentException("Los materiales no pueden ser menor a 0");
        }
        parent::__construct($id, $nombre, $capacidad, $ocupadas, $estado, $precioBaseCentimos);
    }

    public function tipo(): string
    {
        return 'presencial';
    }

    public function precioPlaza(): int
    {
        // TODO M1: calcula el precio de una plaza presencial.
        return $this->precioBaseCentimos + $this->materialesCentimos;
    }
}
