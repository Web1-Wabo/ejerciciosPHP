<?php

use function App\Soporte\e;
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Catálogo de la biblioteca</title>

    <style>
        table,
        td,
        th {
            border: solid;
        }
    </style>
</head>

<body>
    <h1>Catálogo de la biblioteca</h1>
    <p><a href="alta.php">Dar de alta un libro</a></p>
    <!-- Bloque de errores proporcionado. Cada entrada identifica el campo y su mensaje. -->
    <?php if ($errores !== []): ?>
        <ul role="alert">
            <?php foreach ($errores as $campo => $mensaje): ?>
                <li><?= e($campo) ?>: <?= e($mensaje) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    <!-- COMPLETAR 1. Formulario GET con controles HTML estáticos. -->
    <form action="index.php" method="get">
        <label for="q">Titulo o autor</label>
        <input type="text" name="q" id="q">

        <label for="genero">Genero</label>
        <select name="genero" id="genero">
            <option value="">Todos</option>
            <option value="ciencia">Ciencia</option>
            <option value="fantasia">Fantasia</option>
            <option value="terror">Terror</option>
        </select>

        <button type="submit">Buscar</button>
    </form>

    <?php if ($errores === []): ?>
        <!-- COMPLETAR 3. Tabla de libros o mensaje si no hay coincidencias. -->

        <?php if ($libros !== []): ?>
            <table>
                <thead>
                    <tr>
                        <th>Titulo</th>
                        <th>Autor</th>
                        <th>Paginas</th>
                        <th>Genero</th>
                        <th>Fecha de alta</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($libros as $libro): ?>
                        <tr>
                            <td><?= htmlspecialchars($libro->titulo(), ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8") ?></td>
                            <td><?= htmlspecialchars($libro->autor(), ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8") ?></td>
                            <td><?= htmlspecialchars($libro->genero(), ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8") ?></td>
                            <td><?= htmlspecialchars($libro->paginas(), ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8") ?></td>
                            <td><?= htmlspecialchars($libro->fechaAlta() ? $libro->fechaAlta()->format("Y-m-d") : "Sin fecha", ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8") ?></td>

                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No hay libros</p>
        <?php endif ?>
    <?php endif; ?>
    <p><a href="index.php">Limpiar filtros</a></p>
</body>

</html>