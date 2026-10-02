<?php
    function buscarPorID(array $libros, int $id): ?array{
        foreach($libros as $libro){
            if($libro["id"] === $id){
                return $libro;
            }
        }
        return null;
    
    }

    function filtrarPorGenero(array $libros, string $genero): array{
        $coincidenciaGenero = [];
        // if(empty($libros)){
        //     return $coincidenciaGenero;
        // } 
        foreach($libros as $libro){
            if($libro["genero"] === $genero){
                $coincidenciaGenero[] = $libro;
            }
        }

        return $coincidenciaGenero;
    }

    function filtrarDisponible(array $libros): array{
        $coincidenciaDisp = [];
        foreach($libros as $libro){
            if($libro["disponible"] === true){
                $coincidenciaDisp[] = $libro;
            }
        }

        return $coincidenciaDisp;
    }

    function calcularMediaPaginas(array $libros): float{
        if(empty($libros)){
            return 0;
        }
        $totalPag = 0;
        $contador = 0;
        foreach($libros as $libro){
            $totalPag += $libro["paginas"];
            $contador++;
        }

        return $totalPag/$contador;
    }
    
    function obtenerLibroMasLargo(array $libros): ?array{
        if(empty($libros)){
            return null;
        }
        $maxPaginas =0;
        $libroMasLargo = null;

        foreach($libros as $libro){
            if($libro["paginas"] > $maxPaginas){
                $maxPaginas = $libro["paginas"];
                $libroMasLargo = $libro;
            }
        }

        return $libroMasLargo;

    }
?>