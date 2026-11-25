<?php
declare(strict_types=1);

namespace App\Service;

use App\Model\Usuario;
use App\Repository\UsuarioRepositorio;

final class AuthService{
    public function __construct(private UsuarioRepositorio $usuarios){}

    public function registrar(string $nombre, string $email, string $clave) : Usuario {
        $usuario = new Usuario(
            $this->usuarios->siguienteId(),
            $nombre,
            $email,
            password_hash($clave, PASSWORD_DEFAULT),
            "ROLE_USER"
        );
        $this->usuarios->guardar($usuario);
        return $usuario;
    }
}