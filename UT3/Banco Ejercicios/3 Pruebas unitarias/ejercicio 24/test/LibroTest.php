<?php

use App\Model\Libro;
use App\Model\LibroNoDisponibleException;
use PHPUnit\Framework\TestCase;
final class LibroTest extends TestCase
{
    private Libro $libro;

    protected function setUp(): void
    {
        $this->libro = new Libro("Dune", "Frank Herbert", 412);
    }

    public function testConservaTitulo(): void
    {
        $this->assertSame("Dune", $this->libro->titulo());
    }

    public function testConservaPaginasComoEntero(): void
    {
        $this->assertSame(412, $this->libro->paginas());
    }

    public function testLibroNuevoEstaDisponible(): void
    {
        $this->assertTrue($this->libro->estaDisponible());
    }

    public function testPrestarDejaElLibroNoDisponible(): void
    {
        $this->libro->prestar();

        $this->assertFalse($this->libro->estaDisponible());
    }

    public function testPrestarDosVecesLanzaExcepcion(): void
    {
        $this->libro->prestar();
        $this->expectException(LibroNoDisponibleException::class);

        $this->libro->prestar();
    }

    public function testTituloVacioNoEsValido(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Libro("", "Frank Herbert", 412);
    }

    public function testPaginasNegativasNoSonValidas(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Libro("Dune", "Frank Herbert", -1);
    }
}
