<?php

use Clase\Alquileres\Modelo\AlquilerBicicleta;
use Clase\Alquileres\Modelo\AlquilerPatinete;
use Clase\Alquileres\Modelo\EstadoAlquiler;
use Clase\Alquileres\Servicio\ServicioAlquileres;

require_once __DIR__ . "/vendor/autoload.php";

// Va dentro de bin/ -0,1

$alquileres = [
    $alquiler1 = new AlquilerBicicleta(501, "Lucia Torres", 2, EstadoAlquiler::Reservado),
    $alquiler2 = new AlquilerPatinete(502, "Alex Ruiz", 2, EstadoAlquiler::Activo),
    $alquiler3 = new AlquilerBicicleta(503, "Oscar Gil", 3, EstadoAlquiler::Finalizado),
    $alquiler4 = new AlquilerPatinete(504, "Lucia Molina", 1, EstadoAlquiler::Reservado),
];

$servicio = new ServicioAlquileres($alquileres);

// mostrar("", $alquileres, $servicio);


// mostrar("Lucia", $alquileres, $servicio);

// $alquiler1->iniciar();
// // $alquiler1->iniciar();

// mostrar("Lucia", $alquileres, $servicio);

$alquiler1->finalizar();
$alquiler3->iniciar();
$alquiler4->finalizar();
$alquiler2->finalizar();
$alquiler4->iniciar();

mostrar("", $alquileres, $servicio);



function mostrar(
    string $titulo,
    array $alquileres,
    ServicioAlquileres $servicio
): void {
    echo $titulo . PHP_EOL;
    if ($alquileres === []) {
        echo 'Sin resultados' . PHP_EOL;
    }

    foreach ($alquileres as $a) {
        echo $a->id() . ' | ' . $a->cliente() . ' | ' . $a->modalidad()
            . ' | días: ' . $a->dias() . ' | ' . $a->estado()->value
            . ' | ' . $a->importe() . ' céntimos' . PHP_EOL;
    }

    $resumen = $servicio->resumir($alquileres);

    echo 'Cantidad: ' . $resumen['cantidad']
        . ' | Importe: ' . $resumen['importe'] . ' céntimos'
        . PHP_EOL . PHP_EOL;
}
