<?php

declare(strict_types=1);

namespace Alquiler\Equipo\Modelo;

use Alquiler\Equipo\Compartido\GeneraFicha;
use Alquiler\Equipo\Excepcion\EquipoException;
use InvalidArgumentException;

abstract class Equipo
{
    use GeneraFicha;

    public function __construct(
        public readonly int $id,
        private string $nombre,
        private int $unidades,
        private int $reservadas,
        private EstadoEquipo $estado,
        protected readonly int $tarifaBaseCentimos,
    ) {
        if (
            $id <= 0 || trim($nombre) === '' || $unidades <= 0
            || $reservadas < 0 || $reservadas > $unidades || $tarifaBaseCentimos <= 0
        ) {
            throw new \InvalidArgumentException('Datos del equipo inválidos');
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

    public function unidades(): int
    {
        return $this->unidades;
    }

    public function reservadas(): int
    {
        return $this->reservadas;
    }

    public function estado(): EstadoEquipo
    {
        return $this->estado;
    }

    public function disponibles(): int
    {
        return $this->unidades - $this->reservadas;
    }

    abstract public function tipo(): string;

    abstract public function tarifaDia(): int;

    public function reservar(int $cantidad): void
    {
        // TODO M3: valida la reserva antes de modificar las unidades reservadas.
        if($cantidad <=0){
            throw new InvalidArgumentException("La cnaitdad no puede ser mayor o igual a cero");
        }

        if($this->estado() === EstadoEquipo::EnRevision){
            throw new EquipoException("El euqipo no puede estar en revisio");
        }

        if($this->disponibles() < $cantidad){
            throw new EquipoException("No puedes reservar mas de lo que hay");
        }

        $this->reservadas += $cantidad;
    }

    public function devolver(int $cantidad): void
    {
        // TODO M4: valida la devolución antes de modificar las unidades reservadas.
        if($cantidad <= 0){
            throw new InvalidArgumentException("La cnatidad no puede ser 0 o menos");
        }

        if($cantidad > $this->reservadas){
            throw new EquipoException("No se puede devolver mas de lo que hay");
        }

        $this->reservadas -= $cantidad;
    }
}
