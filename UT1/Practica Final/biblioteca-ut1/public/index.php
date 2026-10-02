<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/datos.php';
require_once __DIR__ . '/../src/funciones.php';

$resultados = $libros;

$genero = $_GET['genero'] ?? null;
$disponible = $_GET['disponible'] ?? null;
$q = $_GET['q'] ?? null;
$orden = $_GET['orden'] ?? null;

if ($genero !== null) {
    $resultados = filtrarPorGenero($libros, (string) $genero);
} elseif ($disponible !== null && ($disponible === '1' || $disponible === '0')) {
    $resultados = filtrarPorDisponibilidad(
        $libros,
        $disponible === '1'
    );
} elseif ($q !== null) {
    $resultados = buscarPorTexto($libros, (string) $q);
} elseif ($orden !== null) {
    if ($orden === 'titulo') {
        $resultados = ordenarPorTitulo($libros);
    } elseif ($orden === 'paginas') {
        $resultados = ordenarPorPaginas($libros);
    }
}


?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Biblioteca UT1</title>
</head>

<body>
    <h1>Catálogo</h1>
    <p>Número de resultados: <?= count($resultados) ?></p>
    <ul>
        <?php foreach ($resultados as $libro): ?>
            <li>
                <?= htmlspecialchars($libro['titulo']) ?>
                — <?= htmlspecialchars($libro['autor']) ?>
                — <?= htmlspecialchars($libro['genero']) ?>
                — <?= $libro['paginas'] ?> páginas
                — <?= $libro['disponible'] ? 'Disponible' : 'No disponible' ?>
                - <a href="./libro.php/?id"<?= $libro["id"] ?>></a>
            </li>
        <?php endforeach; ?>
    </ul>
    <p><a href="estadisticas.php">Ver estadísticas</a></p>
</body>

</html>