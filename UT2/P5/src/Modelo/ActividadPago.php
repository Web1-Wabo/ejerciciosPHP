<?php

declare(strict_types=1);

// TODO M7. Implementa la especialización final de pago y su constructor.
final class ActividadPago extends Actividad
{
    public function __construct(int $id, string $nombre, int $capacidad, int $ocupadas, EstadoActividad $estado, ?Responsable $responsable, private readonly int $precioCentimos)
    {
        parent::__construct($id, $nombre, $capacidad, $ocupadas, $estado, $responsable);

        if ($precioCentimos <= 0) {
            throw new InvalidArgumentException("El precio no puede ser 0 o menos");
        }
    }
    #[Override]
    public function tipo(): string
    {
        return "pago";
    }

    #[Override]
    public function importe(int $cantidad): int
    {
        if ($cantidad < 0) {
            throw new InvalidArgumentException("La cantidad no puede ser menor o igual a 0");
        }
        return $cantidad * $this->precioCentimos;
    }
}
