<?php
declare(strict_types=1);
namespace App\Model;

final class Libro
{
    public function __construct(
        public readonly int $id,
        public readonly string $titulo,
    ) {}
}
