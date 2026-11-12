<?php

namespace App\Repositorio;

use App\contrato\RepositorioEnvios;
use App\modelo\Envio;
use EnvioException;
use InvalidArgumentException;
use Override;

class RepositorioEnviosMemoria implements RepositorioEnvios
{
    private array $envios = [];

    public function __construct() {}

    #[Override]
    public function registrar(Envio $envio): void
    {
        if ($this->buscar($envio->id()) !== null) {
            throw new EnvioException("El id ya esta");
        }

        $this->envios[] = $envio;
    }

    #[Override]
    public function todos(): array
    {
        return $this->envios;
    }

    #[Override]
    public function buscar(int $id): ?Envio
    {
        if ($id <= 0) {
            throw new InvalidArgumentException('El ID debe ser positivo.');
        }

        foreach ($this->envios as $envio) {
            if ($envio->id() === $id) {
                return $envio;
            }
        }

        return null;
    }
}
