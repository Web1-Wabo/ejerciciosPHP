<?php
declare(strict_types=1);

use App\Repository\{ArchivoJson, Catalogo, JSONUsuarioRespository};

require dirname(__DIR__) . '/vendor/autoload.php';

\App\Soporte\inicarSesion();

$catalogo = new Catalogo(new ArchivoJson(dirname(__DIR__) . '/var/libros.json'));

$usuarios = new JSONUsuarioRespository(new ArchivoJson(dirname(__DIR__) . "/var/usuario.json"));

$usuarioID = $_SESSION["usuario_id"] ?? null;
$usuarioActual = is_int($usuarioID) ? $usuarios->buscarPorId($usuarioID) : null;

return compact('catalogo', "usuarios", "usuarioActual");
