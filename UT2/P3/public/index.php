<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/Autor.php';
require_once __DIR__ . '/../src/Categoria.php';
require_once __DIR__ . '/../src/Libro.php';
require_once __DIR__ . '/../src/datos.php';


$libros = [];

// Fase 4: convierte los registros de $datos en objetos Libro.
foreach ($datos as $registro) {
    $autor = $registro['autor'] === null
        ? null : new Autor($registro['autor']);
    $categoria = new Categoria($registro['categoria']);
    $libros[] = new Libro(
        // Datos del registro, objeto Autor o null y Categoria.
        $registro["id"],
        $registro["titulo"],
        $registro["paginas"],
        $autor,
        $categoria,
        $registro["disponible"],
    );
}

// Fase 5: realiza aquí las comprobaciones de préstamos e identidad.
// Añade textos a $mensajes; la vista los mostrará debajo del catálogo.
// Termina devolviendo Dune para conservar el estado inicial del catálogo.

$dune = $libros[0];
$otro = $libros[2];

$mensajes[] = 'Disponible: '
    . ($dune->disponible ? 'true' : 'false');

// echo $dune->disponible;
// echo $otro->disponible;

// $dune->prestar();
// echo $dune->disponible;

// $dune->devolver();
// echo $dune->disponible;

// $mensajes[]  = $dune->ficha();
// $mensajes[] = $libros[3]->ficha();

// print_r($mensajes); // el nombre del autor de solarias no esta

// $alias = $dune;
// echo $alias === $dune;
// $dune2 = $libros[0];
// echo $dune === $dune2;

// $dune->devolver();

// Lectura del formulario facilitada. La búsqueda conserva el texto escrito.
$entrada = $_GET['q'] ?? '';
$entradaBusqueda = is_string($entrada) ? $entrada : '';
$entradaBusqueda = trim(mb_strtolower($entradaBusqueda, "UTF-8"));
$soloDisponibles = isset($_GET['disponibles']);
$ordenEntrada = $_GET['orden'] ?? 'paginas';
$orden = is_string($ordenEntrada)
    && in_array($ordenEntrada, ['paginas', 'titulo'], true)
    ? $ordenEntrada : 'paginas';

// Fase 6.1: normaliza la búsqueda y filtra con array_filter.
$busqueda = $entradaBusqueda;
$visibles = $libros;



$visibles = array_filter(
    $visibles,
    fn(Libro $libro): bool => (str_contains(mb_strtolower($libro->titulo, "UTF-8"), $busqueda)) &&
        (!$soloDisponibles || $libro->disponible),
);

// print_r($visibles);

// Fase 6.2: ordena $visibles mediante usort y <=>.
usort(
    $visibles,
    fn(Libro $a, Libro $b): int  => $orden === "paginas" ?  $a->paginas <=> $b->paginas : $a->titulo <=> $b->titulo,
);

// Fase 6.3: resumen del catálogo completo, antes de filtrar.
// Completa disponibles con array_filter y paginas con array_reduce.
$resumen = ['total' => count($libros), 'disponibles' => 0, 'paginas' => 0];
$totalPag = array_reduce(
    $libros,
    fn(int $carry, Libro $l): int => $carry + $l->paginas,
    0,
);
// $totalPag = $resumen["paginas"];
$resumen["paginas"] = $totalPag;

$totalDisp = count(array_filter(
    $libros,
    fn(Libro $l): bool =>  $l->disponible,
));

$resumen["disponibles"] = $totalDisp;


// Fase 6.3: crea las fichas de los libros visibles con array_map.
$etiquetas = array_map(
    fn(Libro $l): string => $l->ficha(),
    $visibles,
);

// Fase 6.3: busca los ids 30 y 999 con array_find y añade mensajes.
$libro30 = array_find(
    $libros,
    fn(Libro $l): bool => $l->id === 30,
);
$mensajes[] = $libro30 ? "libro encontrado" : "libro no encontrado";

$libro999 = array_find(
    $libros,
    fn(Libro $l): bool => $l->id === 999 ?? "Libro no encontrado",
);

$mensajes[] = $libro999?->titulo ?? "Libro no encontrado";


require __DIR__ . '/../vista/catalogo.php';
