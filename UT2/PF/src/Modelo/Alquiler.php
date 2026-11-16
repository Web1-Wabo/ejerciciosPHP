<?php

namespace Clase\Alquileres\Modelo;

use Clase\Alquileres\Excepcion\AlquilerException;
use InvalidArgumentException;

abstract class Alquiler
{
    // Mejor con promoción
    public function __construct(private readonly int $id, private string $cliente, private int $dias, private EstadoAlquiler $estado)
    {
        $this->cliente = trim($cliente);

        // 0 tampoco
        if ($this->id <= 0) {
            throw new InvalidArgumentException("El id tiene que ser mayor a 0");
        }

        if ($this->cliente === "") {
            throw new InvalidArgumentException("El nombre no puede estar vacio");
        }

        if ($this->dias < 1 || $this->dias > 7) {
            throw new InvalidArgumentException("El numero de dias tiene que estar entre 1 y 7");
        }
    }

    public function id(): int
    {
        return $this->id;
    }

    public function cliente(): string
    {
        return $this->cliente;
    }

    public function dias(): int
    {
        return $this->dias;
    }

    public function estado(): EstadoAlquiler
    {
        return $this->estado;
    }

    public abstract function modalidad(): string;
    protected abstract function tarifaDiaria(): int;

    public function importe(): int
    {
        return $this->dias * $this->tarifaDiaria();
    }

    // Permites iniciar un alquiler finalizado y finalizar uno reservado.
    public function iniciar(): void
    {
        // Aquí debe ser diferente de reservado -0,25
        if ($this->estado !== EstadoAlquiler::Reservado) {
            throw new AlquilerException("El estado al que quiere cambiar ya esta");
        } else {
            $this->estado = EstadoAlquiler::Activo;
        }
    }

    public function finalizar(): void
    {
        // Aquí debe ser diferente de activo -0,25
        if ($this->estado !== EstadoAlquiler::Activo) {
            throw new AlquilerException("El estado al que quiere cambiar, ya lo tiene");
        } else {
            $this->estado = EstadoAlquiler::Finalizado;
        }
    }
}
