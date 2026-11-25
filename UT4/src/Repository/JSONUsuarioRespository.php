<?php

declare(strict_types=1);

namespace App\Repository;

use App\Model\Usuario;
use DomainException;
use Override;

final class JSONUsuarioRespository implements UsuarioRepositorio
{
    public function __construct(private ArchivoJson $archivo) {}

    private function filaValida(array $f): bool
    {
        foreach (['nombre', 'email', 'passwordHash', 'rol'] as $clave) {
            if (!is_string($f[$clave] ?? null) || $f[$clave] === '') {
                return false;
            }
        }
        return is_int($f['id'] ?? null) && $f['id'] > 0
            && in_array($f['rol'], ['ROLE_USER', 'ROLE_LIBRARIAN', 'ROLE_ADMIN'], true)
            && filter_var($f['email'], FILTER_VALIDATE_EMAIL) !== false
            && $f['email'] === strtolower(trim($f['email']));
    }
    public function leer(): array
    {
        $filas = $this->archivo->leer();

        $ids = [];
        $emails = [];

        foreach ($filas as $fila) {
            if (!is_array($fila) || !$this->filaValida($fila) || in_array($fila['id'], $ids, true) || in_array($fila['email'], $emails, true)) {
                throw new \RuntimeException('Usuario JSON no válido o repetido');
            }

            $ids[] = $fila['id'];
            $emails[] = $fila['email'];
        }
        return $filas;
    }
    private function leerFilas(): array
    {
        $filas = $this->archivo->leer();
        $ids = [];
        $emails = [];
        foreach ($filas as $fila) {
            if (!is_array($fila) || !$this->filaValida($fila) || in_array($fila['id'], $ids, true) || in_array($fila['email'], $emails, true)) {
                throw new \RuntimeException('Usuario JSON no válido o repetido');
            }
            $ids[] = $fila['id'];
            $emails[] = $fila['email'];
        }
        return $filas;
    }

    public function hidratar(array $fila): Usuario
    {
        return new Usuario(
            id: $fila["id"],
            nombre: $fila['nombre'],
            correo: $fila['email'],
            passwordHash: $fila['passwordHash'],
            rol: $fila['rol'],
        );
    }

    #[Override]
    public function buscarPorCorreo(string $email): ?Usuario
    {
        foreach ($this->leerFilas() as $fila) {
            if ($fila["email"] === $email) {
                return $this->hidratar($fila);
            }
        }
        return null;
    }

    #[Override]
    public function buscarPorId(int $id): ?Usuario
    {
        foreach ($this->leerFilas() as $fila) {
            if ($fila["id"] === $id) {
                return $this->hidratar($fila);
            }
        }
        return null;
    }

    #[Override]
    public function siguienteId(): int
    {
        $ids = array_column($this->leerFilas(), "id");
        return $ids === [] ? 1 : max($ids) + 1;
    }

    #[Override]
    public function guardar(Usuario $usuario): void
    {
        $nueva = [
            "id" => $usuario->id,
            "nombre" => $usuario->nombre,
            "email"=>$usuario->correo,
            "passwordHash"=>$usuario->passwordHash,
            "rol"=>$usuario->rol,
        ];
        if(!$this->filaValida($nueva)){
            throw new DomainException("usuario no valido");
        }

        $filas = $this->leerFilas();
        $posicion = null;
        foreach($filas as $i => $fila){
            if($fila["email"] === $usuario->correo && $fila["id"] !== $usuario->id){
                throw new DomainException("error de registro");
            }
            if($fila["id"] === $usuario->id){
                $posicion = $i;
            }
        }
        if($posicion === null){
            $filas[] = $nueva;
        }else{
            $filas[$posicion] = $nueva;
        }
        $this->archivo->escribir($filas);
    }
}
