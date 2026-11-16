<?php

declare(strict_types=1);

namespace Alquiler\Equipo\Modelo;

enum EstadoEquipo: string
{
    // TODO E1: declara los casos del estado de un equipo.
    case Disponible = "disponible";
    case EnRevision = "en_revision";
}
