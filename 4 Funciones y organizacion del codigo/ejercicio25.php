<?php
    function duplicarValor($n) {
        $n *= 2;
    }

    function duplicarReferencia(&$n) {
        $n *= 2;
    }

    $a = 5;
    $b = 5;
    
    duplicarValor($a);
    duplicarReferencia($b);
    
    echo "$a - $b";

    //Las funciones se comportan de forma distinta porque una si cambia el valor original de la variable 
    // mientras que la otra no, entonces la respuesta del echo seria -5 porque b ahora vale 10
?>