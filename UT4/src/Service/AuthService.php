<?php
declare(strict_types=1);

namespace App\Service;

use App\Model\Usuario;
use App\Repository\UsuarioRepositorio;
use InvalidArgumentException;

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

        if($email === $this->usuarios->buscarPorCorreo($email)){
            throw new InvalidArgumentException("este correo ya esta registrado");
        }
        $this->usuarios->guardar($usuario);
        return $usuario;
    }

    public function autenticar(string $email, string $clave): ?Usuario{
        $usuario = $this->usuarios->buscarPorCorreo($email);
        if($usuario === null || !password_verify($clave, $usuario->passwordHash)){
            return null;
        }
        return $usuario;
    }
}