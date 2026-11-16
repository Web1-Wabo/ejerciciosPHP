<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use function App\Soporte\inspeccionarCartel;

final class CartelTemporalTest extends TestCase
{
    private ?string $temporal = null;

    // COMPLETAR 10. setUp, prueba de rechazo y tearDown.
    protected function setUp(): void
    {
        $ruta = tempnam(sys_get_temp_dir(), "ut3_");

        if ($ruta === false) {
            throw new RuntimeException("Error de ejecucion, ruta falsa");
        }
        $this->temporal = $ruta;
        file_put_contents($this->temporal, 'Texto');
    }

    public function testRechazaTextoComoCartel(): void
    {
        $resultado = inspeccionarCartel($this->entradaArchivo());

        $this->assertNull($resultado['archivo']);
        $this->assertNotNull($resultado['error']);
    }

    // Proporcionado. Representa el temporal como entrada de inspeccionarCartel.
    public function entradaArchivo(): array
    {
        if ($this->temporal === null || !is_file($this->temporal)) {
            throw new RuntimeException('El temporal de prueba no está preparado.');
        }

        return [
            'cartel' => [
                'error' => UPLOAD_ERR_OK,
                'size' => filesize($this->temporal),
                'tmp_name' => $this->temporal,
            ],
        ];
    }

    protected function tearDown(): void
    {
        if ($this->temporal !== null && is_file($this->temporal)) {
            unlink($this->temporal);
        }

        if ($this->temporal !== null) {
            $this->assertFileDoesNotExist($this->temporal);
        }
    }
}
