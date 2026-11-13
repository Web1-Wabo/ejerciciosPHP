<?php

use App\Model\Libro;
use App\Repository\LibroRepository;
use App\Service\BibliotecaService;
use PHPUnit\Framework\TestCase;

final class BibliotecaDoblesTest extends TestCase
{
    public function testStubDevuelveNullParaLibroInexistente(): void
    {
        $repositorio = $this->createStub(LibroRepository::class);
        $repositorio->method("buscarPorId")->willReturn(null);
        $servicio = new BibliotecaService($repositorio);
        $this->expectException(OutOfBoundsException::class);

        $servicio->prestar(999);
    }

    public function testMockCompruebaConsultaYPrestaElLibro(): void
    {
        $libro = new Libro("Dune", "Frank Herbert", 412);
        $repositorio = $this->createMock(LibroRepository::class);
        $repositorio->expects($this->once())
            ->method("buscarPorId")
            ->with($this->equalTo(1))
            ->willReturn($libro);
        $servicio = new BibliotecaService($repositorio);

        $servicio->prestar(1);

        $this->assertFalse($libro->estaDisponible());
    }
}
