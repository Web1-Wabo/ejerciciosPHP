<?php

declare(strict_types=1);

namespace Escuela\Curso\Contrato;

use Escuela\Curso\Modelo\Curso;

interface CursoRepository
{
    // TODO I1: declara las dos operaciones del repositorio.
    public function todos(): array;
    public function buscarPorId(int $id): ?Curso;
}
