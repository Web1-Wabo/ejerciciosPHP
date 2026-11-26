<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Biblioteca de aula</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body class="<?= $tema ?>">
<header>
    <h1>Biblioteca de aula</h1>
    <nav><a href="index.php">Catálogo</a></nav>
    <nav><a href="registro.php">Crear cuenta</a></nav>
</header>
<?php if($aviso !== null):?>
    <p class="aviso"><?= \App\Soporte\e($aviso) ?></p>
<?php endif;?>
<main>
