<?php

declare(strict_types=1);

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

use function App\Soporte\validarVideojuego;

final class HorasTest extends TestCase
{
    // COMPLETAR 9. Proveedor y prueba de los cinco valores de horas.
    #[DataProvider('proveedorHoras')]
    public function casosHoras(string $entrada, bool $valida): void
    {
        $post = $this->datosValidos();
        $post['horas'] = $entrada;

        $resultado = validarVideojuego($post);

        $this->assertSame($valida, !isset($resultado['errores']['horas']));

        if ($valida) {
            $this->assertIsInt($resultado['datos']['horas']);
            $this->assertSame((int) $entrada, $resultado['datos']['horas']);
        } else {
            $this->assertNull($resultado['datos']);
        }
    }

    // Proporcionado. Datos válidos sobre los que varías solo horas.
    private static function datosValidos(): array
    {
        return [
            'mínimo' => ['1', true],
            'máximo' => ['300', true],
            'inferior' => ['0', false],
            'superior' => ['301', false],
            'texto' => ['12abc', false],
        ];
    }
}
