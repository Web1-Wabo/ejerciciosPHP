<?php

declare(strict_types=1);

// TODO M1. Declara la interfaz Prestable con prestar, renovar y devolver.
interface Prestable
{
    public function prestar(int $dias): void;
    public function renovar(int $diasExtra): void;
    public function devolver(): void;
}
