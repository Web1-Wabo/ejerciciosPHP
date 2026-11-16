<?php

use function App\Soporte\e;
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Alta de un libro</title>
</head>

<body>
    <h1>Alta de un libro</h1>
    <?php if ($creado === null): ?>
        <!-- Bloque de errores proporcionado. Cada entrada identifica el campo y su mensaje. -->
        <?php if ($errores !== []): ?>
            <ul role="alert">
                <?php foreach ($errores as $campo => $mensaje): ?>
                    <li><?= e($campo) ?>: <?= e($mensaje) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        <!-- COMPLETAR 4. Formulario POST multipart con controles HTML estáticos. -->
        <form action="alta.php" method="post" enctype="multipart/form-data">
            <label for="titulo">Titulo</label>
            <input type="text" name="titulo" id="titulo" maxlength="120" required>

            <label for="autor">Autor</label>
            <input type="text" name="autor" id="autor" maxlength="100" required>

            <label for="paginas">Paginas</label>
            <input type="text" name="paginas" id="paginas" minlength="1" maxlength="4">

            <label for="genero">Genero</label>
            <select name="genero" id="genero">
                <option value="">Sin genero</option>
                <option value="ciencia">Ciencia</option>
                <option value="fantasia">Fantasia</option>
                <option value="terror">Terror</option>
            </select>

            <label for="fechaAlta">Fecha de alta</label>
            <input type="text" name="fechaAlta" id="fechaAlta" placeholder="YYYY-MM-DD">

            <label for="portada">Portada</label>
            <input type="file" name="portada" id="portada" accept="image/jpeg,image/png,image/webp">

            <button type="submit">Crear</button>
        </form>
    <?php else: ?>
        <!-- Confirmación proporcionada. Solo aparece cuando asignas $creado. -->
        <h2>Libro creado</h2>
        <dl>
            <dt>Título</dt>
            <dd><?= e($creado->titulo()) ?></dd>
            <dt>Autor</dt>
            <dd><?= e($creado->autor()) ?></dd>
            <dt>Páginas</dt>
            <dd><?= $creado->paginas() ?></dd>
            <dt>Género</dt>
            <dd><?= e($creado->genero()) ?></dd>
            <dt>Fecha de alta</dt>
            <dd><?= e($creado->fechaAlta()?->format('Y-m-d') ?? 'Sin fecha') ?></dd>
        </dl>
        <p>La colección de este envío contiene <?= count($biblioteca->todos()) ?> libros.</p>
        <?php if ($creado->portada() !== null): ?>
            <img src="portada.php?nombre=<?= e($creado->portada()) ?>"
                alt="Portada del libro creado" width="180">
        <?php else: ?>
            <p>Sin portada</p>
        <?php endif; ?>
        <p><a href="alta.php">Nueva alta</a></p>
    <?php endif; ?>
    <p><a href="index.php">Volver al catálogo</a></p>
</body>

</html>