<?php

namespace App\Servicio;

use App\contrato\RepositorioEnvios;
use App\modelo\Envio;
use App\Modelo\EstadoEnvio;
use EnvioException;
use InvalidArgumentException;

class ServicioEnvios
{
    public function __construct(private RepositorioEnvios $repositorio) {}

    private function obtener(int $id): Envio
    {
        if ($id <= 0) {
            throw new InvalidArgumentException("El ID debe ser un entero positivo.");
        }

        $envio = $this->repositorio->buscar($id);

        if ($envio === null) {
            throw new EnvioException("No se ha encontrado el envío con ID {$id}.");
        }

        return $envio;
    }

    public function enviar(int $id): void
    {
        $envio = $this->obtener($id);
        $envio->enviar();
    }

    public function entregar(int $id): void
    {
        $envio = $this->obtener($id);
        $envio->entregar();
    }

    public function cancelar(int $id): void
    {
        $envio = $this->obtener($id);
        $envio->cancelar();
    }

    private static function normalizar(string $texto): string
    {
        $texto = preg_replace('/\s+/u', ' ', $texto);

        return mb_strtolower(trim($texto), 'UTF-8');
    }

    public function consultar(
        string $texto = '',
        ?string $modalidad = null,
        ?EstadoEnvio $estado = null,
        string $orden = 'precio'
    ): array {
        if ($modalidad !== null && $modalidad !== 'normal' && $modalidad !== 'urgente') {
            throw new InvalidArgumentException("Modalidad inválida");
        }

        if ($orden !== 'precio' && $orden !== 'peso') {
            throw new InvalidArgumentException("Orden inválido ");
        }

        $resultado = [];
        $textoNormalizado = $this->normalizar($texto);

        foreach ($this->repositorio->todos() as $envio) {
            if ($textoNormalizado !== '') {
                $clienteNormalizado = $this->normalizar($envio->cliente());
                if (!str_contains($clienteNormalizado, $textoNormalizado)) {
                    continue;
                }
            }

            if ($modalidad !== null && $envio->modalidad() !== $modalidad) {
                continue;
            }

            if ($estado !== null && $envio->estado() !== $estado) {
                continue;
            }

            $resultado[] = $envio;
        }

        usort($resultado, function (Envio $a, Envio $b) use ($orden) {
            $valA = ($orden === 'precio') ? $a->tarifa() : $a->peso();
            $valB = ($orden === 'precio') ? $b->tarifa() : $b->peso();

            if ($valA === $valB) {
                return $a->id() <=> $b->id();
            }

            return $valA <=> $valB;
        });

        return array_values($resultado);
    }

    public function etiquetas(array $envios): array
    {
        $etiquetas = [];

        foreach ($envios as $envio) {
            $etiquetas[] =
                $envio->id() . " | " .
                $envio->cliente() . " | " .
                $envio->modalidad() . " | " .
                $envio->estado()->value . " | " .
                $envio->tarifa();
        }

        return $etiquetas;
    }

    public function resumir(array $envios): array
    {
        $resumen = [
            'cantidad'      => 0,
            'peso'          => 0,
            'importeTotal'  => 0,
            'importeActivo' => 0,
            'pendiente'     => 0,
            'enviado'       => 0,
            'entregado'     => 0,
            'cancelado'     => 0,
        ];

        foreach ($envios as $envio) {

            $resumen['cantidad']++;
            $resumen['peso'] += $envio->peso();

            $tarifa = $envio->tarifa();
            $resumen['importeTotal'] += $tarifa;

            // Importe activo suma todos los no cancelados (incluye entregados)
            if ($envio->estado() !== EstadoEnvio::Cancelado) {
                $resumen['importeActivo'] += $tarifa;
            }

            // Conteo por cada estado de EstadoEnvio
            switch ($envio->estado()) {
                case EstadoEnvio::Pendiente:
                    $resumen['pendiente']++;
                    break;
                case EstadoEnvio::Enviado:
                    $resumen['enviado']++;
                    break;
                case EstadoEnvio::Entregado:
                    $resumen['entregado']++;
                    break;
                case EstadoEnvio::Cancelado:
                    $resumen['cancelado']++;
                    break;
            }
        }

        return $resumen;
    }
}
