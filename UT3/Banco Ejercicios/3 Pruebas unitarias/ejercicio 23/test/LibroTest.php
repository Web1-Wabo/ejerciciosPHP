<?php

use App\Model\Libro;
use PHPUnit\Framework\TestCase;

final class LibroTest extends TestCase
{
    public function testConservaTitulo(): void
    {
        $libro = new Libro("Dune", "Frank Herbert", 412);

        $this->assertSame("Dune", $libro->titulo());
    }

    public function testDisponibleDespuesDePrestar(): void
    {
        $libro = new Libro("Dune", "Frank Herbert", 412);
        $libro->prestar();

        $this->assertFalse($libro->estaDisponible());
        $this->assertSame(412, $libro->paginas());
        $this->assertNotSame(412, "412");
        $this->assertEquals(412, "412");
    }
}