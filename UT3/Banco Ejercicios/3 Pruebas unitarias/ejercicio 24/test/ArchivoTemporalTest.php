<?php

use PHPUnit\Framework\TestCase;
final class ArchivoTemporalTest extends TestCase
{
    private string $ruta;

    protected function setUp(): void
    {
        $ruta = tempnam(sys_get_temp_dir(), "ut3-");
        if ($ruta === false) {
            throw new RuntimeException("No se pudo crear el archivo temporal.");
        }
        $this->ruta = $ruta;
    }

    protected function tearDown(): void
    {
        if (isset($this->ruta) && is_file($this->ruta) && !unlink($this->ruta)) {
            throw new RuntimeException("No se pudo eliminar el archivo temporal.");
        }
        $this->assertFileDoesNotExist($this->ruta);
    }

    public function testCreaArchivoTemporal(): void
    {
        $this->assertFileExists($this->ruta);
    }
}
