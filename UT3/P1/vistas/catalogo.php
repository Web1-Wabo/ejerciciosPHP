<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Catálogo de la biblioteca</title>
</head>

<body>
    <h1>Catálogo de la biblioteca</h1>
    <form action="index.php" method="get">
        <!-- Texto de búsqueda-->
        <p>
            <label for="q">Título o autor</label>
            <input type="text" id="q" name="q">
        </p>

        <!-- Género -->
        <p>
            <label for="genero">Género</label>
            <select id="genero" name="genero">
                <option value="">Todos</option>
                <option value="ciencia">Ciencia</option>
                <option value="fantasia">Fantasía</option>
                <option value="terror">Terror</option>
            </select>
        </p>

        <!-- Tipo de libro -->
        <p>
            <label for="tipo">Tipo de libro</label>
            <select id="tipo" name="tipo">
                <option value="">Todos</option>
                <option value="papel">Papel</option>
                <option value="digital">Digital</option>
            </select>
        </p>

        <!-- Solo destacados -->
        <p>
            <label for="destacados">Solo destacados</label>
            <input type="checkbox" value="1" id="destacados" name="destacados">
        </p>
        <button type="submit">Buscar</button>
    </form>

    <p>
        <a href="alta.php">Dar de alta un libro</a>
        <a href="index.php">Ver catálogo inicial</a>
    </p>

    <?php if ($filas === []): ?>
        <p>No hay libros que coincidan</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Autor</th>
                    <th>Género</th>
                    <th>Tipo</th>
                    <th>Páginas</th>
                    <th>Unidades</th>
                    <th>Coste de reposición</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($filas as $fila): ?>
                    <tr>
                        <td><?= htmlspecialchars($fila['titulo'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($fila['autor'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($fila['genero'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></td>
                        <td><?= $fila['tipo'] === 'papel' ? 'Papel' : 'Digital' ?></td>
                        <td><?= $fila['paginas'] ?></td>
                        <td><?= $fila['unidades'] ?></td>
                        <td><?= number_format($fila['costeCentimos'] / 100, 2, ',', '.') ?> €</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
    <h2>Resumen</h2>
    <h3>Número de títulos</h3>
    <p><?= $resumen['titulos'] ?></p>
    <h3>Unidades</h3>
    <p><?= $resumen['unidades'] ?></p>
    <h3>Páginas</h3>
    <p><?= $resumen['paginas'] ?></p>
    <h3>Coste total</h3>
    <!-- Muestras directamente coste céntimos en lugar de pasarlo a euros -->
    <p><?= $resumen['costeCentimos']?> €</p>
</body>

</html>