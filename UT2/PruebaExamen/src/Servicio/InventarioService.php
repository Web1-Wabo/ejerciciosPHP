<?php

declare(strict_types=1);

namespace Alquiler\Equipo\Servicio;

use Alquiler\Equipo\Contrato\EquipoRepository;
use Alquiler\Equipo\Excepcion\EquipoException;
use Alquiler\Equipo\Modelo\{Equipo, EstadoEquipo};
use App\Modelo\EstadoEnvio;
use InvalidArgumentException;

final class InventarioService
{
    public function __construct(private EquipoRepository $repository)
    {
    }

    public function filtrar(
        string $texto = '',
        ?EstadoEquipo $estado = null,
        int $minDisponibles = 0
    ): array {
        $texto = mb_strtolower(trim($texto), 'UTF-8');

        if ($minDisponibles < 0) {
            throw new \InvalidArgumentException('Mínimo negativo');
        }

        // TODO F1: filtra con array_filter y devuelve claves consecutivas.
        $equipos = $this->repository->todos();
        $aux = array_filter(
            $equipos,
            function(Equipo $e) use ($texto, $estado, $minDisponibles):bool{
                // Correcto aunque argumentos invertidos
                if(!str_contains($e->nombreNormalizado(), $texto))   {
                    return false;
                }
                // Comparar con e->estado()
                // $estado === null || $e->estado() === $estado -> TRUE
                if($estado !== null && $estado === $e->estado()){
                    return false;
                }

                // Si es 0 es true y luego, es true si $e->disponible >= $minDisponibles y $e->estado() === EstadoEquipo::Disponible
                if($minDisponibles < 0){
                    if($estado !== EstadoEquipo::Disponible){
                        return false;
                    }
                }

                // Ya juntar todo en una única condición
                return true;
            },
        );

        return array_values($aux);
    }

    public function ordenar(array $equipos): array
    {
        // TODO F2: ordena una copia mediante usort y un callback.
        $copia = $equipos;
        usort($copia, 
            fn(Equipo $e, Equipo $b):int => 
                ($e->disponibles() <=> $b->disponibles()) ?:
                ($b->tarifaDia() <=> $e->tarifaDia()) ?:
                ($e->id() <=> $b->id())
                
            );

        return $copia;
    }

    public function fichas(array $equipos, string $prefijo = 'Equipo: '): array
    {
        // TODO F3: utiliza array_map y una arrow function.
        $aux = array_map(fn(Equipo $e):string =>
            $e->ficha($prefijo)
        , $equipos);

        return $aux;
    }

    public function resumen(array $equipos): array
    {
        // TODO F4: acumula las tres cifras mediante array_reduce.
        $aux = array_reduce(
            $equipos,
            fn(array $carry, Equipo $e):array =>[
                "unidades" => $carry["unidades"] + $e->unidades(),
                // Cuidado con pequeñas erratas
                "reservadas"=> $carry["reservadas"] + $e->reservadas(),
                "valorDisponibleCentimos" => $carry["valorDisponibleCentimos"] + $e->disponibles() * $e->tarifaDia()
            ]
            , 
            // Falta iniciar el acumulador
            ["unidades" => 0, "reservadas" => 0, "valorDisponibleCentimos"=>0]
        );

        return $aux;
    }

    public function presupuestarLotes(
        int $cantidad,
        int $dias,
        int $presupuestoCentimos
    ): array {
        // TODO D1: repara las etapas conservando las funciones de arrays.
        // Terminar
        if ($cantidad <= 0 || $dias <= 0 || $presupuestoCentimos < 0) {
            throw new InvalidArgumentException('Cantidad, días o presupuesto no válidos');
        }

        $candidatos = array_filter(
            $this->repository->todos(),
            fn (Equipo $equipo): bool =>
                $equipo->estado() === EstadoEquipo::Disponible
                && $equipo->disponibles() >= $cantidad
        );

        $propuestas = array_map(
            fn (Equipo $equipo): array => [
                'id' => $equipo->id(),
                'importeCentimos' => $cantidad * $equipo->tarifaDia() * $dias,
                'disponiblesDespues' => $equipo->disponibles() - $cantidad
            ],
            $candidatos
        );

        $opciones = array_reduce(
            $propuestas,
            function (array $resultado, array $propuesta) use ($presupuestoCentimos): array {
                if ($propuesta['importeCentimos'] <= $presupuestoCentimos) {
                    $resultado[] = $propuesta;
                }

                return $resultado;
            },
            []
        );

        usort(
            $opciones,
            fn (array $a, array $b): int =>
                (($b['importeCentimos'] <=> $a['importeCentimos']) !== 0)
                    ? ($a['importeCentimos'] <=> $b['importeCentimos'])
                    : ($a['id'] <=> $b['id'])
        );

        return $opciones;
    }

    private function requerido(int $id): Equipo
    {
        return $this->repository->buscarPorId($id)
            ?? throw new EquipoException('El equipo no existe');
    }

    public function reservar(int $id, int $cantidad): void
    {
        $this->requerido($id)->reservar($cantidad);
    }

    public function devolver(int $id, int $cantidad): void
    {
        $this->requerido($id)->devolver($cantidad);
    }
}
