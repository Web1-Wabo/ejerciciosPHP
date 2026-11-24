<?php
declare(strict_types=1);
namespace App\Soporte;

function e(string $texto): string
{
    return htmlspecialchars($texto, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function soloMetodos(array $permitidos): string
{
    $metodo = $_SERVER['REQUEST_METHOD'] ?? '';
    if (!in_array($metodo, $permitidos, true)) {
        header('Allow: ' . implode(', ', $permitidos));
        http_response_code(405);
        exit('Método no permitido');
    }
    return $metodo;
}

function redirigir(string $ruta): never
{
    header('Location: ' . $ruta, true, 303);
    exit;
}
