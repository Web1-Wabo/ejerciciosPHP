<?php
declare(strict_types=1);

namespace App\Controlador;

use function App\Soporte\soloMetodos;

final class CatalogoControlador extends Controlador
{
    public function mostrar(): void
    {
        soloMetodos(['GET']);
        $entrada = $_GET['q'] ?? '';
        $q = is_string($entrada) ? trim($entrada) : '';
        $libros = $this->app['catalogo']->todos();
        if ($q !== '') {
            $libros = array_filter(
                $libros,
                static fn ($libro) => stripos($libro->titulo, $q) !== false
            );
        }
        $favoritos = $_SESSION["favoritos"] ?? [];
        $this->vista('catalogo', compact('libros', 'q', "favoritos"));
    }
}
