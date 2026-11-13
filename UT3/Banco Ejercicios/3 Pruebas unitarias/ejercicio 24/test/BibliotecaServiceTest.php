<?php

use App\Model\Libro;
use App\Repository\ArrayLibroRepository;
use App\Service\BibliotecaService;
use PHPUnit\Framework\TestCase;

final class BibliotecaServiceTest extends TestCase
{
    public function testPrestaElLibroEncontradoEnElRepositorio(): void
    {
        $repositorio = new ArrayLibroRepository();
        $libro = new Libro("Dune", "Frank Herbert", 412);
        $id = $repositorio->guardar($libro);
        $servicio = new BibliotecaService($repositorio);

        $servicio->prestar($id);

        $this->assertFalse($repositorio->buscarPorId($id)->estaDisponible());
    }

    public function testIdAusenteLanzaExcepcion(): void
    {
        $servicio = new BibliotecaService(new ArrayLibroRepository());
        $this->expectException(OutOfBoundsException::class);

        $servicio->prestar(999);
    }
}
