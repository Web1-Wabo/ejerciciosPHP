<?php
declare(strict_types=1);

use App\Repository\{ArchivoJson, Catalogo};

require dirname(__DIR__) . '/vendor/autoload.php';

\App\Soporte\inicarSesion();

$catalogo = new Catalogo(new ArchivoJson(dirname(__DIR__) . '/var/libros.json'));

return compact('catalogo');
