<?php

declare(strict_types=1);

// TODO M4. Implementa la base abstracta Juego y las operaciones del préstamo.
abstract class Juego implements Prestable
{
    use GeneraEtiqueta;

    private int $id;
    private string $nombre;
    private bool $prestado = false;
    private int $dias = 0;

    public function __construct(int $id, string $nombre)
    {
        if ($id <= 0 || trim($nombre) === "") {
            throw new InvalidArgumentException("El ID no puedes ser menor de 1 o el NOMBRE esta vacio");
        } else {
            $this->id = $id;
            $this->nombre = trim($nombre);
        }
    }

    public function id(): int
    {
        return $this->id;
    }

    public function nombre(): string
    {
        return $this->nombre;
    }

    public function prestado(): bool
    {
        return $this->prestado;
    }

    public function dias(): int
    {
        return $this->dias;
    }

    public abstract function plazoMaximo(): int;

    public function ficha(): string
    {
        return $this->etiqueta() . " . " . ($this->prestado ? "Disponible" : "No disponible") . " . Dias" . $this->dias();
    }

    public function prestar(int $dias): void
    {
        if ($dias <= 0) {
            throw new InvalidArgumentException("No pueden ser 0 o menos dias");
        }

        if ($this->prestado === true) {
            throw new PrestamoException("Esta ya prestado");
        }

        if ($dias > $this->plazoMaximo()) {
            throw new PrestamoException('Se supera el plazo máximo del juego.');
        }
        $this->prestado = true;
        $this->dias = $dias;
    }

    public function renovar(int $diasExtra): void
    {
        if ($diasExtra <= 0) {
            throw new InvalidArgumentException("Los dias extra no pueden ser 0 o menos");
        }
        if (!$this->prestado) {
            throw new PrestamoException("El juego no esta prestado");
        }

        if ($this->dias + $diasExtra > $this->plazoMaximo()) {
            throw new PrestamoException("No puede ser mayor al plazo maximo");
        }

        $this->dias += $diasExtra;
    }

    public function devolver(): void
    {
        if (!$this->prestado) {
            throw new PrestamoException("No se puede prestar");
        }
        $this->prestado = false;
        $this->dias = 0;
    }
}
