<?php
    function doble(int $n):int{
        return 2*$n;
    }

    function cuadrado(int $n):int{
        return 2**$n;
    }

    function aplicar(int $n, callable $callback):int{
        return $callback($n);
    }

    $duplicar = aplicar(5, "doble");
    echo $duplicar . "<br>";

    $raizC = aplicar(4, "cuadrado");
    echo $raizC;
?>