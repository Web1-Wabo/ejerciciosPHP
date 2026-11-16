<?php

declare(strict_types=1);

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

use function App\Soporte\validarPelicula;

final class DuracionTest extends TestCase
{
    // COMPLETAR 9. Proveedor y prueba de las cinco duraciones.
    public static function duraciones(): array
    {
        return [
            'mínimo' => ['1', true],
            'máximo' => ['300', true],
            'inferior' => ['0', false],
            'superior' => ['301', false],
            'texto' => ['12abc', false],
        ];
    }

    #[DataProvider('duraciones')]
    public function testDuracion(string $entrada, bool $valida): void
    {
        $post = $this->datosValidos();
        $post['duracion'] = $entrada;

        $resultado = validarPelicula($post);

        $this->assertSame($valida, !isset($resultado['errores']['duracion']));

        if ($valida) {
            $this->assertIsInt($resultado['datos']['duracion']);
            $this->assertSame((int) $entrada, $resultado['datos']['duracion']);
        } else {
            $this->assertNull($resultado['datos']);
        }
    }

    // Proporcionado. Datos válidos sobre los que varías solo duracion.
    private function datosValidos(): array
    {
        return [
            'titulo' => 'Dune',
            'director' => 'Denis Villeneuve',
            'duracion' => '155',
            'genero' => 'ciencia',
            'fechaEstreno' => '',
        ];
    }
}
