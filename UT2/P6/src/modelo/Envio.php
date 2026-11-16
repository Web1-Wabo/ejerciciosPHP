<?php

namespace App\modelo;

use App\Modelo\EstadoEnvio;
use EnvioException;
use InvalidArgumentException;

abstract class Envio
{
    private readonly int $id;
    private string $cliente;
    private int $peso;
    private EstadoEnvio $estado;

    public function __construct(int $id, string $cliente, int $peso, EstadoEnvio $estado)
    {
        $this->id = $id;
        $this->cliente = trim($cliente);
        $this->peso = $peso;
        $this->estado = $estado;

        if ($this->id <= 0 || $this->cliente === "") {
            throw new InvalidArgumentException("Id no positivo o cliente sin nombre");
        }

        if ($this->peso < 1 || $this->peso > 20000) {
            throw new InvalidArgumentException("El peso tiene que estar entre 1 y 20000 gramos");
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

    public function peso(): int
    {
        return $this->peso;
    }

    public function estado(): EstadoEnvio
    {
        return $this->estado;
    }

    public function tarifa(): int
    {
        $bloques = intdiv($this->peso + 999, 1000);

        return $this->cuotaFija() + $bloques * $this->precioBloque();
    }

    public abstract function modalidad(): string;
    protected abstract function cuotaFija(): int;
    protected abstract function precioBloque(): int;


    private function cambiarEstado(
        EstadoEnvio $origen,
        EstadoEnvio $destino
    ): void {
        if ($this->estado !== $origen) {
            throw new EnvioException(
                "No se puede pasar de {$this->estado->value} a {$destino->value}."
            );
        }

        $this->estado = $destino;
    }

    public function enviar(): void
    {
        $this->cambiarEstado(EstadoEnvio::Pendiente, EstadoEnvio::Enviado);
    }

    public function entregar(): void
    {
        $this->cambiarEstado(EstadoEnvio::Enviado, EstadoEnvio::Entregado);
    }

    public function cancelar(): void
    {
        $this->cambiarEstado(EstadoEnvio::Pendiente, EstadoEnvio::Cancelado);
    }
}
