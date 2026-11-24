<?php
declare(strict_types=1);
namespace App\Repository;
use App\Model\Libro;

// Persistencia proporcionada para el servidor local de aula con un único escritor.
final class Catalogo
{
    public function __construct(private ArchivoJson $archivo) {}

    /** @return list<Libro> */
    public function todos(): array
    {
        $libros = [];
        foreach ($this->archivo->leer() as $fila) {
            if (!is_array($fila) || !is_int($fila['id'] ?? null) || $fila['id'] <= 0
                || !is_string($fila['titulo'] ?? null) || trim($fila['titulo']) === ''
                || isset($libros[$fila['id']])) {
                throw new \RuntimeException('El catálogo contiene un libro no válido o un ID repetido');
            }
            $libros[$fila['id']] = new Libro($fila['id'], $fila['titulo']);
        }
        return array_values($libros);
    }

    public function buscarPorId(int $id): ?Libro
    {
        foreach ($this->todos() as $libro) {
            if ($libro->id === $id) {
                return $libro;
            }
        }
        return null;
    }

    public function crear(string $titulo): Libro
    {
        $libros = $this->todos();
        $ids = array_column($libros, 'id');
        $libro = new Libro($ids === [] ? 1 : max($ids) + 1, $titulo);
        $libros[] = $libro;
        $this->guardar($libros);
        return $libro;
    }

    public function cambiarTitulo(int $id, string $titulo): ?Libro
    {
        $libros = $this->todos();
        foreach ($libros as $i => $libro) {
            if ($libro->id === $id) {
                $actualizado = new Libro($id, $titulo);
                $libros[$i] = $actualizado;
                $this->guardar($libros);
                return $actualizado;
            }
        }
        return null;
    }

    private function guardar(array $libros): void
    {
        $filas = [];
        foreach ($libros as $libro) {
            $filas[] = ['id' => $libro->id, 'titulo' => $libro->titulo];
        }
        $this->archivo->escribir($filas);
    }
}
