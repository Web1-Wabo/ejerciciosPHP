<?php 
    $precioLibro = 24.90;
    $descuento = 15;
    $iva = 4;

    $precioReal = $precioLibro - ($precioLibro * ($descuento/100)) + ($precioLibro * ($iva/100));

    echo $precioReal;
?>