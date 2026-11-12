<?php
declare(strict_types=1);

function entrada(array $origen, string $clave, string $defecto = ''): string
{
    // F1. Lee solo cadenas y aplica el valor por defecto.
    if (!isset($origen[$clave]) || !is_string($origen[$clave])) {
        return $defecto;
    }

    return $origen[$clave];
}

function enteroPositivo(string $valor): int
{
    // F2. Valida con filter_var sin convertir previamente.
    $validador = filter_var($valor, FILTER_VALIDATE_INT, [
        'options' => ['min_range' => 1]
    ]);

    if ($validador === false) {
        throw new InvalidArgumentException("Indica un entero positivo.");
    }

    return $validador;
}

function limpiarEspacios(string $texto): string
{
    return preg_replace('/\s+/u', ' ', trim($texto)) ?? '';
}

function normalizar(string $texto): string
{
    return mb_strtolower(limpiarEspacios($texto), 'UTF-8');
}

function buscarPorId(array $actividades, int $id): ?Actividad
{
    // F3. Usa array_find con un callback tipado.
    return array_find(
        $actividades,
        fn(Actividad $actividad): bool => $actividad->id() === $id
    );
}

function filtrarActividades(
    array $actividades, string $texto, string $tipo,
    ?EstadoActividad $estado, bool $soloDisponibles
): array {
    // F4. Combina las cuatro condiciones mediante array_filter y reindexa con array_values.
    $textoNormalizado = normalizar($texto);

    $filtradas = array_filter(
        $actividades,
        function (Actividad $actividad) use ($textoNormalizado, $tipo, $estado, $soloDisponibles): bool {
            // 1. Filtrado por texto
            if ($textoNormalizado !== '' && !str_contains(normalizar($actividad->nombre()), $textoNormalizado)) {
                return false;
            }

            // 2. Filtrado por tipo
            if ($tipo !== '' && $actividad->tipo() !== $tipo) {
                return false;
            }

            // 3. Filtrado por estado
            if ($estado !== null && $actividad->estado() !== $estado) {
                return false;
            }

            // 4. Filtrado por disponibilidad (debe estar Abierta Y tener plazas libres)
            if ($soloDisponibles) {
                if ($actividad->estado() !== EstadoActividad::Abierta || $actividad->plazasLibres() <= 0) {
                    return false;
                }
            }

            return true;
        }
    );

    return array_values($filtradas);
}

function ordenarActividades(array $actividades, string $orden): array
{
    // F5. Ordena una copia con usort y desempata por id ascendente.
    $copia = $actividades;

    usort($copia, function (Actividad $a, Actividad $b) use ($orden): int {
        $resultado = match ($orden) {
            'libres' => $a->plazasLibres() <=> $b->plazasLibres(),
            'nombre' => normalizar($a->nombre()) <=> normalizar($b->nombre()),
            default  => normalizar($a->nombre()) <=> normalizar($b->nombre()),
        };

        if ($resultado === 0) {
            return $a->id() <=> $b->id();
        }

        return $resultado;
    });

    return $copia;
}

function transformarActividades(array $actividades, callable $transformacion): array
{
    return array_map($transformacion, $actividades);
}

function generarEtiquetas(
    array $actividades, string $prefijo = 'Actividad: '
): array {
    // F6. Usa transformarActividades y closure capturando prefijo con use.
    return transformarActividades(
        $actividades,
        // Te pide una closure con use
        fn(Actividad $actividad): string => $actividad->etiqueta($prefijo)
    );
}

function resumirActividades(array $actividades): array
{
    // F7. Calcula las cinco magnitudes requeridas con array_reduce.
    $inicial = [
        'cantidad'  => 0,
        'capacidad' => 0,
        'ocupadas'  => 0,
        'libres'    => 0,
        'importe'   => 0,
    ];

    return array_reduce(
        $actividades,
        function (array $acc, Actividad $actividad): array {
            $acc['cantidad']++;
            $acc['capacidad'] += $actividad->capacidad();
            $acc['ocupadas']  += $actividad->ocupadas();
            $acc['libres']    += $actividad->plazasLibres();
            $acc['importe']   += $actividad->importe($actividad->ocupadas());

            return $acc;
        },
        $inicial
    );
}

function e(string $texto): string
{
    return htmlspecialchars($texto, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function euros(int $centimos): string
{
    return number_format($centimos / 100, 2, ',', '.') . ' €';
}