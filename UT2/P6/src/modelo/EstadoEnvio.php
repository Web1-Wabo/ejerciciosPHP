<?php

namespace App\Modelo;

enum EstadoEnvio: string
{
    case Pendiente = "pendiente";
    case Enviado = "enviado";
    case Entregado = "entragado";
    case Cancelado = "cancelado";
}
