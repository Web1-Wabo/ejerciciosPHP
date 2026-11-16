<?php
    $iva = 0.21;

    $precioFinal = function (float $p) use ($iva): float{
        return $p * (1+$iva);
    };

    $total = $precioFinal(100);
    echo $total;
?>