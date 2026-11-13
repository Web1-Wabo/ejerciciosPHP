<?php

use App\Service\PortadaValidator;
use PHPUnit\Framework\TestCase;

final class PortadaValidatorTest extends TestCase
{
    public function testAceptaUnByte(): void
    {
        $this->assertNull((new PortadaValidator())->validar(1, "image/jpeg"));
    }

    public function testAceptaDosMib(): void
    {
        $this->assertNull((new PortadaValidator())->validar(2 * 1024 * 1024, "image/jpeg"));
    }

    public function testRechazaTamanoSuperior(): void
    {
        $this->assertNotNull((new PortadaValidator())->validar(2 * 1024 * 1024 + 1, "image/jpeg"));
    }

    public function testAceptaMimePng(): void
    {
        $this->assertNull((new PortadaValidator())->validar(100, "image/png"));
    }

    public function testRechazaMimeDeTexto(): void
    {
        $this->assertNotNull((new PortadaValidator())->validar(100, "text/plain"));
    }
}
