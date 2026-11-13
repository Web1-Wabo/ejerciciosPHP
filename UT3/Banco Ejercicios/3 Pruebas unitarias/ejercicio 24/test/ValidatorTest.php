<?php

use App\Service\Validator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class ValidatorTest extends TestCase
{
    public static function titulosVacios(): array
    {
        return [
            "cadena vacía" => [""],
            "espacios" => ["   "],
            "tabulación" => ["\t"],
        ];
    }

    #[DataProvider("titulosVacios")]
    public function testTituloRequerido(string $valor): void
    {
        $validator = new Validator();

        $this->assertSame(
            "Escribe el título.",
            $validator->requerido($valor, "Escribe el título.")
        );
    }

    public function testTituloConTextoNoTieneError(): void
    {
        $validator = new Validator();

        $this->assertNull($validator->requerido("Dune", "Escribe el título."));
    }
}
