<?php
declare(strict_types=1);
namespace App\Service;

final class PortadaValidator
{
    public function validar(int $tamano, string $mime): ?string
    {
        if ($tamano < 1 || $tamano > 2 * 1024 * 1024) {
            return "La portada debe ocupar entre 1 byte y 2 MiB.";
        }
        if (!in_array($mime, ["image/jpeg", "image/png", "image/webp"], true)) {
            return "El formato de la portada no está permitido.";
        }
        return null;
    }
}
