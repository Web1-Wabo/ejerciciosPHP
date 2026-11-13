<?php

use App\Service\LibroFormService;
use App\Service\Validator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class LibroFormServiceTest extends TestCase
{
    public static function paginas(): array
    {
        return [
            "mínimo" => ["1", true],
            "máximo" => ["3000", true],
            "cero" => ["0", false],
            "mayor que el máximo" => ["3001", false],
            "texto después del número" => ["12abc", false],
            "decimal" => ["12.5", false],
            "array" => [["412"], false],
        ];
    }

    #[DataProvider("paginas")]
    public function testValidaPaginas(mixed $entrada, bool $aceptada): void
    {
        $datos = $this->datosValidos();
        $datos["paginas"] = $entrada;
        $resultado = $this->servicio()->procesar($datos);

        $this->assertSame(
            !$aceptada,
            array_key_exists("paginas", $resultado["errores"])
        );
        if ($aceptada) {
            $this->assertSame((int) $entrada, $resultado["datos"]["paginas"]);
        } else {
            $this->assertNull($resultado["datos"]);
        }
    }

    public function testDevuelveValoresErroresYDatosTipados(): void
    {
        $datos = $this->datosValidos();
        $datos["titulo"] = "  Dune  ";

        $resultado = $this->servicio()->procesar($datos);

        $this->assertSame("Dune", $resultado["valores"]["titulo"]);
        $this->assertSame("412", $resultado["valores"]["paginas"]);
        $this->assertSame([], $resultado["errores"]);
        $this->assertSame(412, $resultado["datos"]["paginas"]);
        $this->assertNull($resultado["datos"]["fechaAlta"]);
    }

    public function testConvierteFechaValida(): void
    {
        $datos = $this->datosValidos();
        $datos["fechaAlta"] = "2026-10-24";

        $resultado = $this->servicio()->procesar($datos);

        $this->assertInstanceOf(DateTimeImmutable::class, $resultado["datos"]["fechaAlta"]);
        $this->assertSame("2026-10-24", $resultado["datos"]["fechaAlta"]->format("Y-m-d"));
    }

    public function testRechazaFechaImposible(): void
    {
        $datos = $this->datosValidos();
        $datos["fechaAlta"] = "2026-02-31";

        $resultado = $this->servicio()->procesar($datos);

        $this->assertArrayHasKey("fechaAlta", $resultado["errores"]);
        $this->assertNull($resultado["datos"]);
    }

    public function testAceptaTituloDe120Caracteres(): void
    {
        $datos = $this->datosValidos();
        $datos["titulo"] = str_repeat("a", 120);

        $resultado = $this->servicio()->procesar($datos);

        $this->assertArrayNotHasKey("titulo", $resultado["errores"]);
    }

    public function testRechazaTituloDe121Caracteres(): void
    {
        $datos = $this->datosValidos();
        $datos["titulo"] = str_repeat("a", 121);

        $resultado = $this->servicio()->procesar($datos);

        $this->assertArrayHasKey("titulo", $resultado["errores"]);
    }

    public function testConservaErrorDeTipoEnTitulo(): void
    {
        $datos = $this->datosValidos();
        $datos["titulo"] = ["Dune"];

        $resultado = $this->servicio()->procesar($datos);

        $this->assertSame("Debe ser texto.", $resultado["errores"]["titulo"]);
    }

    public function testRechazaGeneroNoPermitido(): void
    {
        $datos = $this->datosValidos();
        $datos["genero"] = "aventura";

        $resultado = $this->servicio()->procesar($datos);

        $this->assertArrayHasKey("genero", $resultado["errores"]);
    }

    private function servicio(): LibroFormService
    {
        return new LibroFormService(new Validator());
    }

    private function datosValidos(): array
    {
        return [
            "titulo" => "Dune",
            "autor" => "Frank Herbert",
            "paginas" => "412",
            "genero" => "ciencia",
            "fechaAlta" => "",
        ];
    }
}
