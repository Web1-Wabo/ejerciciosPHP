<?php
    declare(strict_types=1);
    namespace App\Model;

    final class Usuario{
        public function __construct(public readonly int $id,
                                    public readonly string $nombre,
                                    public readonly string $correo,
                                    public readonly string $passwordHash,
                                    public readonly string $rol,
        ){}
    }
?>