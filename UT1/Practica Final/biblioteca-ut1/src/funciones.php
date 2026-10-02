<?php

declare(strict_types=1);

function buscarPorId(array $libros, int $id): ?array
{
    foreach ($libros as $libro) {
        if ($libro['id'] === $id) {
            return $libro;
        }
    }
    return null;
}

function filtrarPorGenero(array $libros, string $genero): array
{
    $resultado = [];
    foreach ($libros as $libro) {
        if ($libro['genero'] === $genero) {
            $resultado[] = $libro;
        }
    }
    return $resultado;
}

function filtrarPorDisponibilidad(array $libros, bool $disponible): array
{
    $resultado = [];
    foreach ($libros as $libro) {
        if ($libro['disponible'] === $disponible) {
            $resultado[] = $libro;
        }
    }
    return $resultado;
}

function buscarPorTexto(array $libros, string $texto): array
{
    $resultado = [];
    $texto = strtolower($texto);

    foreach ($libros as $libro) {
        if (
            str_contains(strtolower($libro['titulo']), $texto) ||
            str_contains(strtolower($libro['autor']), $texto)
        ) {
            $resultado[] = $libro;
        }
    }

    return $resultado;
}


function ordenarPorTitulo(array $libros): array
{
    $ordenados = $libros;
    $cantidad = count($ordenados);

    for ($i = 0; $i < $cantidad; $i++) {
        for ($j = 0; $j < $cantidad - 1; $j++) {
            $tituloActual = strtolower($ordenados[$j]['titulo']);
            $tituloSiguiente = strtolower($ordenados[$j + 1]['titulo']);

            if ($tituloActual > $tituloSiguiente) {
                $temporal = $ordenados[$j];
                $ordenados[$j] = $ordenados[$j + 1];
                $ordenados[$j + 1] = $temporal;
            }
        }
    }

    return $ordenados;
}


function ordenarPorPaginas(array $libros): array
{
    $ordenados = $libros;
    $cantidad = count($ordenados);

    for ($i = 0; $i < $cantidad; $i++) {
        for ($j = 0; $j < $cantidad - 1; $j++) {
            if ($ordenados[$j]['paginas'] > $ordenados[$j + 1]['paginas']) {
                $temporal = $ordenados[$j];
                $ordenados[$j] = $ordenados[$j + 1];
                $ordenados[$j + 1] = $temporal;
            }
        }
    }

    return $ordenados;
}


function calcularMediaPaginas(array $libros): float
{
    if (count($libros) === 0) {
        return 0.0;
    }
    $totalPaginas = 0;
    foreach ($libros as $libro) {
        $totalPaginas += $libro['paginas'];
    }
    return $totalPaginas / count($libros);
}


function obtenerLibroMasLargo(array $libros): ?array
{
    if (count($libros) === 0) {
        return null;
    }
    $masLargo = $libros[0];
    foreach ($libros as $libro) {
        if ($libro['paginas'] > $masLargo['paginas']) {
            $masLargo = $libro;
        }
    }
    return $masLargo;
}

function contarPorGenero(array $libros): array
{
    $resultado = [];
    foreach ($libros as $libro) {
        $genero = $libro['genero'];
        if (isset($resultado[$genero])) {
            $resultado[$genero]++;
        } else {
            $resultado[$genero] = 1;
        }
    }
    return $resultado;
}