<?php
declare(strict_types=1);
namespace App\Repository;

use App\Model\Usuario;

interface UsuarioRepositorio{
    public function buscarPorId(int $id): ?Usuario;
    public function buscarPorCorreo(string $email): ?Usuario;
    public function siguienteId(): int;
    public function guardar(Usuario $usuario): void;
}
?>