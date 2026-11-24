<?php
declare(strict_types=1);

$app = require dirname(__DIR__) . '/config/inicio.php';
(new App\Controlador\CatalogoControlador($app))->mostrar();
